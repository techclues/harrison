<?php
declare(strict_types=1);

/*
 * Enquiry-form mail settings (Contact and Appointment). These defaults hold NO secrets.
 * To override anything (especially SMTP credentials) copy mail.local.php.example to
 * mail.local.php: it is git-ignored and merged over these values.
 *
 * transport:
 *   'log'  — writes each enquiry to storage/outbox/*.eml instead of sending it. For local testing.
 *   'mail' — PHP's built-in mail(). Works on most shared hosting with no setup.
 *   'smtp' — authenticated SMTP via PHPMailer (most reliable for delivery; fill in 'smtp' below).
 */
return [
    'to' => 'techcluesltd@gmail.com',          // where enquiries are delivered
    'from' => 'info@harrison.co.uk',           // sender shown on the email: use a mailbox on the site's own domain
    'from_name' => 'Harrison Accountants website',
    'transport' => 'log',                      // set to 'mail' or 'smtp' when the site is deployed
    'smtp' => [
        'host' => '',
        'port' => 587,
        'secure' => 'tls',                     // 'tls' (STARTTLS, port 587) or 'ssl' (port 465)
        'username' => '',
        'password' => '',
    ],
    // Abuse protection
    'min_seconds' => 3,                        // a form submitted faster than this after loading is treated as a bot
    'max_age_hours' => 24,                     // a form page older than this must be reloaded
    'rate_limit' => 5,                         // submissions allowed per visitor...
    'rate_window_minutes' => 60,               // ...in this window
];
