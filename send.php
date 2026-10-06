<?php
declare(strict_types=1);
/*
 * Handles the Contact and Appointment forms. Answers JSON to the page's script, or redirects back
 * with ?status= for visitors without JavaScript. Order of checks: method, honeypot, signed token,
 * rate limit, validation, delivery.
 */
require_once __DIR__ . '/includes/forms.php';
date_default_timezone_set('Europe/London');

$pages = ['contact' => 'contact.php', 'appointment' => 'appointment.php'];
$type = ($_POST['form'] ?? '') === 'appointment' ? 'appointment' : 'contact';
$wantsJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');

function form_reply(bool $wantsJson, string $page, int $http, bool $ok, string $code, string $message, array $errors = []): never
{
    if ($wantsJson) {
        http_response_code($http);
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: no-store');
        echo json_encode(['ok' => $ok, 'message' => $message, 'errors' => (object) $errors], JSON_UNESCAPED_UNICODE);
    } else {
        header('Location: ' . $page . '?status=' . rawurlencode($code) . '#form-status', true, 303);
    }
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ' . $pages[$type], true, 303);
    exit;
}
$page = $pages[$type];
$cfg = form_config();
[, $sentMessage] = form_status_message('sent');

// 1. Honeypot: real visitors never see or fill this field. Pretend it worked so bots move on.
if (($_POST['website'] ?? '') !== '') {
    form_reply($wantsJson, $page, 200, true, 'sent', $sentMessage);
}

// 2. Signed, time-stamped token from the page that was actually loaded.
$tokenProblem = form_token_check((string) ($_POST['ts'] ?? ''), $cfg);
if ($tokenProblem !== '') {
    form_reply($wantsJson, $page, 400, false, 'spam', form_status_message('spam')[1]);
}

// 3. Rate limit per visitor.
if (!form_rate_ok($cfg)) {
    form_reply($wantsJson, $page, 429, false, 'rate', form_status_message('rate')[1]);
}

// 4. Validation.
[$data, $errors] = form_validate($type, $_POST);
if ($errors) {
    form_reply($wantsJson, $page, 422, false, 'invalid', form_status_message('invalid')[1], $errors);
}

// 5. Delivery.
[$subject, $body] = form_compose($type, $data);
[$sent, $why] = form_send($cfg, $subject, $body, $data['email'], $data['name']);
if (!$sent) {
    form_log_error('send failed (' . $type . '): ' . $why);
    form_reply($wantsJson, $page, 500, false, 'fail', form_status_message('fail')[1]);
}
form_reply($wantsJson, $page, 200, true, 'sent', $sentMessage);
