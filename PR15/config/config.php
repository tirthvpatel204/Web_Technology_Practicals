<?php
// config/config.php - Session & Constants Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_NAME', 'StudentHub');
define('BASE_URL', 'http://localhost/StudentHub_15_TP/'); // <-- Match your exact folder name in htdocs
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Timezone
date_default_timezone_set('Asia/Kolkata');
?>