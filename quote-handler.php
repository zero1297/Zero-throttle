<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

header('Content-Type: application/json');

$toAddress = 'CortesCleanouts@outlook.com';
$fromAddress = 'noreply@cortescleanouts.com';
// Copy kept in an on-domain mailbox: delivered locally on the SiteGround
// server, so the lead still arrives even if the outbound spam filter or
// Outlook rejects the main copy.
$copyAddress = 'noreply@cortescleanouts.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

// SMTP credentials live in quote-config.php, which is gitignored and must be
// created on the server by hand — see quote-config.sample.php.
$configFile = __DIR__ . '/quote-config.php';
$config = file_exists($configFile) ? require $configFile : null;
if (!is_array($config)
    || empty($config['smtp_host'])
    || empty($config['smtp_username'])
    || empty($config['smtp_password'])
    || $config['smtp_password'] === 'PUT-THE-MAILBOX-PASSWORD-HERE') {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Email is not configured on the server']);
    exit;
}

function clean_field($value) {
    $value = trim($value ?? '');
    // Strip anything that could be used for header injection.
    return preg_replace('/[\r\n]+/', ' ', $value);
}

$name = clean_field($_POST['name'] ?? '');
$phone = clean_field($_POST['phone'] ?? '');
$zip = clean_field($_POST['zip'] ?? '');
// Body-only field, so newlines are safe to keep.
$items = trim($_POST['items'] ?? '');

if ($name === '' || $phone === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Name and phone are required']);
    exit;
}

$body = "New quote request from the Cortes Cleanouts website:\n\n"
    . "Name: {$name}\n"
    . "Phone: {$phone}\n"
    . "Zip code: {$zip}\n"
    . "What needs to go:\n{$items}\n";

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_username'];
    $mail->Password = $config['smtp_password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->Timeout = 15;

    $mail->CharSet = PHPMailer::CHARSET_UTF8;
    // The default "PHPMailer" X-Mailer header attracts spam scoring; a lone
    // space makes PHPMailer omit the header entirely.
    $mail->XMailer = ' ';

    $mail->setFrom($fromAddress, 'Cortes Cleanouts Website');
    $mail->addAddress($toAddress);
    $mail->addBCC($copyAddress);

    $mail->Subject = 'New quote request — ' . $name;
    $mail->Body = $body;

    $mail->send();
    echo json_encode(['ok' => true]);
} catch (PHPMailerException $e) {
    error_log('Quote form email failed: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Failed to send email']);
}
