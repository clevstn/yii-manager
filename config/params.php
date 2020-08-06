<?php
$params = require __DIR__ . '/bootstrap.php';
/**
 * This is used for user-defined parameters.
 */
$customs = [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
];

return array_merge($customs, $params);