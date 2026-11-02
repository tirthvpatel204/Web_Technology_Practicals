<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

$user = current_user();

if ($user && isset($pdo)) {
    log_audit($pdo, 'LOGOUT', "User {$user['name']} ({$user['email']}) logged out.");
}

// Clear session
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();

// Restart new session just to flash signout confirmation
session_start();
flash('info', 'You have been signed out safely.');
header('Location: login.php');
exit;
