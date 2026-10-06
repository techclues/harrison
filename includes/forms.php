<?php
declare(strict_types=1);
/*
 * Enquiry forms (Contact, Appointment): validation, abuse protection and delivery.
 * Used by the form pages (token + status) and by send.php (handling). No third-party services,
 * and nothing is stored beyond a short-lived rate-limit counter and, with the 'log' transport,
 * the local outbox used for testing.
 */
require_once __DIR__ . '/site.php';


/** Mail settings: committed defaults merged with the optional git-ignored local file. */
function form_config(): array
{
    static $cfg = null;
    if ($cfg !== null) return $cfg;
    $cfg = require __DIR__ . '/../config/mail.php';
    $local = __DIR__ . '/../config/mail.local.php';
    if (is_file($local)) {
        $override = require $local;
        if (is_array($override)) {
            foreach ($override as $k => $v) {
                $cfg[$k] = is_array($v) && isset($cfg[$k]) && is_array($cfg[$k]) ? array_replace($cfg[$k], $v) : $v;
            }
        }
    }
    return $cfg;
}

/** Runtime data folder. The HARRISON_STORAGE environment variable lets the test suite use a throwaway folder. */
function form_storage_root(): string
{
    $env = getenv('HARRISON_STORAGE');
    return is_string($env) && $env !== '' ? rtrim($env, '/\\') : __DIR__ . '/../storage';
}

function form_storage_dir(string $sub): string
{
    $dir = form_storage_root() . '/' . $sub;
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    return $dir;
}

/** Per-install random secret used to sign form tokens. Created on first use. */
function form_secret(): string
{
    $file = form_storage_dir('') . '/.secret';
    $secret = is_file($file) ? trim((string) file_get_contents($file)) : '';
    if (strlen($secret) < 32) {
        $secret = bin2hex(random_bytes(32));
        file_put_contents($file, $secret, LOCK_EX);
    }
    return $secret;
}

/** Signed timestamp, placed in a hidden field when a form page is rendered. */
function form_token(): string
{
    $ts = (string) time();
    return $ts . '.' . hash_hmac('sha256', $ts, form_secret());
}

/** Returns '' if the token is acceptable, otherwise a short reason code. */
function form_token_check(string $token, array $cfg): string
{
    if (!preg_match('/^(\d{9,12})\.([a-f0-9]{64})$/', $token, $m)) return 'missing';
    if (!hash_equals(hash_hmac('sha256', $m[1], form_secret()), $m[2])) return 'forged';
    $age = time() - (int) $m[1];
    if ($age < (int) $cfg['min_seconds']) return 'too-fast';
    if ($age > (int) $cfg['max_age_hours'] * 3600) return 'expired';
    return '';
}

/** Counts this attempt; false once the visitor is over the limit. */
function form_rate_ok(array $cfg): bool
{
    $key = hash_hmac('sha256', $_SERVER['REMOTE_ADDR'] ?? 'cli', form_secret());
    $file = form_storage_dir('ratelimit') . '/' . substr($key, 0, 40) . '.json';
    $window = (int) $cfg['rate_window_minutes'] * 60;
    $now = time();
    $fh = fopen($file, 'c+');
    if (!$fh) return true; // never block real people because the disk is unwritable
    flock($fh, LOCK_EX);
    $times = json_decode((string) stream_get_contents($fh), true);
    $times = array_values(array_filter(is_array($times) ? $times : [], fn($t) => is_int($t) && $t > $now - $window));
    $ok = count($times) < (int) $cfg['rate_limit'];
    if ($ok) $times[] = $now;
    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, json_encode($times));
    flock($fh, LOCK_UN);
    fclose($fh);
    return $ok;
}

/** Single-line text: control characters (including CR/LF) removed, whitespace collapsed, length capped. */
function form_line(mixed $value, int $max): string
{
    $s = is_string($value) ? $value : '';
    $s = preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $s) ?? '';
    $s = trim(preg_replace('/\s+/u', ' ', $s) ?? '');
    return strlen($s) > $max ? rtrim(substr($s, 0, $max)) : $s;
}

/** Multi-line text: line breaks kept (normalised), other control characters removed. */
function form_text(mixed $value, int $max): string
{
    $s = is_string($value) ? $value : '';
    $s = str_replace(["\r\n", "\r"], "\n", $s);
    $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $s) ?? '';
    $s = trim(preg_replace("/\n{3,}/", "\n\n", $s) ?? '');
    return strlen($s) > $max ? rtrim(substr($s, 0, $max)) : $s;
}

/**
 * Validates a submission. Returns [cleaned data, errors keyed by field name].
 * $type is 'contact' or 'appointment'.
 */
function form_validate(string $type, array $post): array
{
    $e = [];
    $d = [
        'email' => form_line($post['email'] ?? '', 254),
        'phone' => form_line($post['phone'] ?? '', 30),
        'service' => form_line($post['service'] ?? '', 80),
        'message' => form_text($post['message'] ?? '', 5000),
    ];
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $e['email'] = 'Please enter a valid email address.';
    if ($d['phone'] !== '' && !preg_match('/^[0-9 +().\-]{6,30}$/', $d['phone'])) $e['phone'] = 'Please enter a valid phone number.';
    if ($d['service'] !== '' && !in_array($d['service'], array_column(services(), 'title'), true)) $d['service'] = '';
    if (($post['consent'] ?? '') !== 'yes') $e['consent'] = 'Please tick the box to agree.';

    if ($type === 'appointment') {
        $d['first_name'] = form_line($post['first_name'] ?? '', 60);
        $d['last_name'] = form_line($post['last_name'] ?? '', 60);
        $d['name'] = trim($d['first_name'] . ' ' . $d['last_name']);
        if ($d['first_name'] === '') $e['first_name'] = 'Please enter your first name.';
        if ($d['last_name'] === '') $e['last_name'] = 'Please enter your last name.';
        if ($d['phone'] === '') $e['phone'] = 'Please enter a phone number.';
        $d['date'] = form_line($post['date'] ?? '', 10);
        $dt = DateTimeImmutable::createFromFormat('!Y-m-d', $d['date'], new DateTimeZone('Europe/London'));
        if (!$dt || $dt->format('Y-m-d') !== $d['date']) $e['date'] = 'Please choose a date.';
        elseif ($dt < new DateTimeImmutable('today', new DateTimeZone('Europe/London'))) $e['date'] = 'Please choose a date that is not in the past.';
        $d['time'] = form_line($post['time'] ?? '', 5);
        if ($d['time'] !== '' && (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $d['time']) || $d['time'] < '10:00' || $d['time'] > '18:00')) {
            $e['time'] = 'Please choose a time between 10:00 and 18:00.';
        }
    } else {
        $d['name'] = form_line($post['name'] ?? '', 120);
        if ($d['name'] === '') $e['name'] = 'Please enter your name.';
        if (strlen($d['message']) < 5) $e['message'] = 'Please tell us a little about what you need.';
    }
    return [$d, $e];
}

/** Subject and plain-text body for the notification email. */
function form_compose(string $type, array $d): array
{
    $label = $type === 'appointment' ? 'appointment request' : 'enquiry';
    $subject = 'New ' . $label . ' from the Harrison website: ' . $d['name'];
    $lines = ['New ' . $label . ' from the Harrison Accountants website.', '', 'Name: ' . $d['name'], 'Email: ' . $d['email'], 'Phone: ' . ($d['phone'] ?: '(not given)')];
    if ($type === 'appointment') {
        $lines[] = 'Preferred date: ' . $d['date'];
        $lines[] = 'Preferred time: ' . ($d['time'] ?: '(no preference)');
    }
    $lines[] = 'Service of interest: ' . ($d['service'] ?: '(not specified)');
    $lines[] = '';
    $lines[] = 'Message:';
    $lines[] = $d['message'] !== '' ? $d['message'] : '(none)';
    $lines[] = '';
    $lines[] = 'Sent ' . (new DateTimeImmutable('now', new DateTimeZone('Europe/London')))->format('j F Y, H:i') . ' (UK time). Reply to this email to answer the sender.';
    return [form_line($subject, 200), implode("\n", $lines)];
}

/** Delivers one email. Returns [ok, error text for the server log only]. */
function form_send(array $cfg, string $subject, string $body, string $replyEmail, string $replyName): array
{
    $to = (string) $cfg['to'];
    $from = (string) $cfg['from'];
    $fromName = form_line($cfg['from_name'], 80);
    $replyName = form_line($replyName, 120);
    if (!filter_var($to, FILTER_VALIDATE_EMAIL) || !filter_var($from, FILTER_VALIDATE_EMAIL)) return [false, 'Invalid to/from address in config'];
    $enc = fn(string $s) => '=?UTF-8?B?' . base64_encode($s) . '?=';

    switch ($cfg['transport']) {
        case 'log':
            $file = form_storage_dir('outbox') . '/' . date('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.eml';
            $eml = "To: $to\nFrom: $fromName <$from>\nReply-To: $replyName <$replyEmail>\nSubject: $subject\n\n$body\n";
            return file_put_contents($file, $eml, LOCK_EX) === false ? [false, 'Could not write outbox'] : [true, null];

        case 'mail':
            $headers = [
                'From: ' . $enc($fromName) . " <$from>",
                'Reply-To: ' . $enc($replyName) . " <$replyEmail>",
                'MIME-Version: 1.0',
                'Content-Type: text/plain; charset=UTF-8',
                'Content-Transfer-Encoding: 8bit',
            ];
            $ok = @mail($to, $enc($subject), $body, implode("\r\n", $headers), '-f' . $from);
            return [$ok, $ok ? null : 'mail() returned false'];

        case 'smtp':
            $s = (array) $cfg['smtp'];
            if (($s['host'] ?? '') === '') return [false, 'SMTP host not configured'];
            require_once __DIR__ . '/vendor/phpmailer/Exception.php';
            require_once __DIR__ . '/vendor/phpmailer/PHPMailer.php';
            require_once __DIR__ . '/vendor/phpmailer/SMTP.php';
            try {
                $m = new PHPMailer\PHPMailer\PHPMailer(true);
                $m->isSMTP();
                $m->Host = $s['host'];
                $m->Port = (int) $s['port'];
                $m->SMTPAuth = ($s['username'] ?? '') !== '';
                $m->Username = (string) ($s['username'] ?? '');
                $m->Password = (string) ($s['password'] ?? '');
                $m->SMTPSecure = ($s['secure'] ?? 'tls') === 'ssl' ? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS : PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $m->CharSet = 'UTF-8';
                $m->setFrom($from, $fromName);
                $m->addAddress($to);
                $m->addReplyTo($replyEmail, $replyName);
                $m->isHTML(false);
                $m->Subject = $subject;
                $m->Body = $body;
                $m->send();
                return [true, null];
            } catch (Throwable $ex) {
                return [false, 'SMTP: ' . $ex->getMessage()];
            }
    }
    return [false, 'Unknown transport'];
}

/** Server-side problems go here (never shown to visitors, and no enquiry content is logged). */
function form_log_error(string $what): void
{
    @file_put_contents(form_storage_dir('') . '/errors.log', date('c') . ' ' . $what . "\n", FILE_APPEND | LOCK_EX);
}

/** Messages shown after a normal (non-JavaScript) submit, selected by ?status=. */
function form_status_message(?string $code): array
{
    return match ($code) {
        'sent' => ['ok', 'Thank you. Your message has been sent and we will be in touch.'],
        'invalid' => ['error', 'Please check the form and complete the required fields.'],
        'spam' => ['error', 'Sorry, we could not send that. Please reload the page and try again.'],
        'rate' => ['error', 'You have sent several messages recently. Please try again later, or call us.'],
        'fail' => ['error', 'Sorry, something went wrong sending your message. Please try again, or call us.'],
        default => ['', ''],
    };
}
