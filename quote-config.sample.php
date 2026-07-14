<?php

// SMTP settings for quote-handler.php.
//
// Setup: copy this file to quote-config.php (same folder as quote-handler.php
// on the server) and fill in the real mailbox password. quote-config.php is
// gitignored so the password never ends up in the repository.
//
// The values below match SiteGround's mail service for cortescleanouts.com.
// The authoritative settings are shown in Site Tools → Email → Accounts →
// (actions menu next to noreply@cortescleanouts.com) → Mail Configuration.
return [
    'smtp_host' => 'mail.cortescleanouts.com',
    'smtp_username' => 'noreply@cortescleanouts.com',
    'smtp_password' => 'PUT-THE-MAILBOX-PASSWORD-HERE',
];
