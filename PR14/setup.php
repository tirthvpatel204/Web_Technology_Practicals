<?php
declare(strict_types=1);

/**
 * Practical 14: Automated Database Installer & Reset Utility
 */

$host = '127.0.0.1';
$port = '3306';
$user = 'root';
$pass = '';
$dbname = 'pr14_tp';

$message = '';
$status = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || (php_sapi_name() === 'cli')) {
    try {
        $pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $sqlPath = __DIR__ . '/database.sql';
        if (!file_exists($sqlPath)) {
            throw new Exception("database.sql file not found at: {$sqlPath}");
        }

        $sql = file_get_contents($sqlPath);
        $pdo->exec($sql);

        $message = "Database '{$dbname}' and all tables were successfully created and populated with demo seed data!";
        $status = 'success';
    } catch (Throwable $e) {
        $message = "Setup error: " . $e->getMessage();
        $status = 'danger';
    }

    if (php_sapi_name() === 'cli') {
        echo "[$status] $message\n";
        exit($status === 'success' ? 0 : 1);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup | Practical 14</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 p-3">
    <div class="card shadow border-0" style="max-width: 550px; width: 100%; border-radius: 16px;">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-weight: bold; font-size: 1.25rem;">
                    14
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">Database Setup &amp; Reset</h4>
                    <p class="text-muted small mb-0">Practical 14 - Admin Dashboard (RBAC &amp; Audit Logs)</p>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?= $status ?> d-flex align-items-center gap-2 mb-4">
                    <div><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
                </div>
            <?php endif; ?>

            <p class="text-secondary small">
                Clicking the button below will execute <code>database.sql</code>, create the <code>pr14_tp</code> database, set up all 5 tables (<code>users</code>, <code>students</code>, <code>events</code>, <code>registrations</code>, <code>audit_logs</code>), and seed sample accounts.
            </p>

            <div class="bg-light border rounded-3 p-3 mb-4 small font-monospace">
                <strong>Demo Accounts Created:</strong><br>
                &bull; Admin:  <code>admin@example.com</code> / <code>admin123</code><br>
                &bull; Editor: <code>editor@example.com</code> / <code>editor123</code><br>
                &bull; Viewer: <code>viewer@example.com</code> / <code>viewer123</code>
            </div>

            <form method="post" class="d-grid gap-2">
                <button type="submit" class="btn btn-primary py-2 fw-semibold">
                    Initialize / Reset Database Now
                </button>
                <a href="login.php" class="btn btn-outline-secondary py-2">
                    Proceed to Login &rarr;
                </a>
            </form>
        </div>
    </div>
</body>
</html>
