<?php
header('Content-Type: application/json');

$toAddress = 'CortesCleanouts@outlook.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
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
$items = clean_field($_POST['items'] ?? '');

if ($name === '' || $phone === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Name and phone are required']);
    exit;
}

$subject = 'New quote request — ' . $name;

$body = "New quote request from the Cortes Cleanouts website:\n\n"
    . "Name: {$name}\n"
    . "Phone: {$phone}\n"
    . "Zip code: {$zip}\n"
    . "What needs to go:\n{$items}\n";

$headers = [];
$headers[] = 'From: Cortes Cleanouts Website <' . $toAddress . '>';
$headers[] = 'Reply-To: ' . $toAddress;
$headers[] = 'X-Mailer: PHP/' . phpversion();

$sent = mail($toAddress, $subject, $body, implode("\r\n", $headers));

if ($sent) {
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Failed to send email']);
}
