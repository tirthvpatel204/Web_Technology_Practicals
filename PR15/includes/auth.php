<?php
// includes/auth.php - RBAC Authentication Middleware
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}

function require_role($role) {
    require_login();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        http_response_code(403);
        include __DIR__ . '/../includes/header.php';
        echo '<div class="container mt-5"><div class="alert alert-danger shadow-sm"><h4><i class="fas fa-ban"></i> Access Denied</h4><p>You do not have permission to access this page.</p><a href="'.BASE_URL.'" class="btn btn-primary mt-3">Return Home</a></div></div>';
        include __DIR__ . '/../includes/footer.php';
        exit();
    }
}
?>