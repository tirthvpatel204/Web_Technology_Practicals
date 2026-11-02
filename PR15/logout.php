<?php
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'includes/functions.php';

if (isset($_SESSION['user_id'])) {
    log_audit($pdo, $_SESSION['user_id'], 'LOGOUT', 'User logged out.');
}

$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
header("Location: login.php");
exit();
?>