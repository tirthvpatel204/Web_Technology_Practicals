<?php
$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

$conn = new mysqli("localhost", "root", "", "secure_login");

if ($conn->connect_error) {
    die("Database connection failed");
}

$timeout = 900;

if (isset($_SESSION['user_id'])) {
    if (time() - ($_SESSION['last_activity'] ?? 0) > $timeout) {
        $_SESSION = [];
        session_destroy();
        header("Location: login.php?timeout=1");
        exit;
    }

    $_SESSION['last_activity'] = time();
}
?>