<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;
$role = $user['role'] ?? 'guest';
$assetPrefix = (strpos($_SERVER['PHP_SELF'] ?? '', '/admin/') !== false) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden | Access Denied</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $assetPrefix ?>assets/css/style.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 p-3">
    <div class="card shadow-lg border-0 text-center p-4 p-md-5" style="max-width: 520px; border-radius: 20px;">
        <div class="mb-3">
            <span class="badge bg-danger-subtle text-danger fs-5 px-3 py-2 rounded-pill fw-semibold">
                HTTP 403 - Access Denied
            </span>
        </div>
        <div class="display-1 fw-bold text-danger mb-2">403</div>
        <h3 class="fw-bold mb-2">Role-Based Access Control Restriction</h3>
        <p class="text-muted mb-4">
            Your current logged-in role is <strong class="badge bg-secondary"><?= htmlspecialchars(strtoupper($role), ENT_QUOTES, 'UTF-8') ?></strong>.
            This area requires higher administrative privileges (e.g., <strong>ADMIN</strong>).
            This unauthorized access attempt has been recorded in the security <strong class="text-dark">audit_logs</strong>.
        </p>

        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
            <a href="<?= $assetPrefix ?>dashboard.php" class="btn btn-primary px-4 py-2">
                Back to Dashboard
            </a>
            <a href="<?= $assetPrefix ?>logout.php" class="btn btn-outline-danger px-4 py-2">
                Switch Account / Sign Out
            </a>
        </div>
    </div>
</body>
</html>
