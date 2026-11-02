//25AIML058

<?php
declare(strict_types=1);

/**
 * Practical 14: Database Connection & Initialization
 * Uses PDO with prepared statements, UTF-8 charset, and exception error handling.
 */

$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'pr14_tp';
$dbUser = 'root';
$dbPass = '';

try {
    // Connect to MySQL server
    $dsn = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    // Ensure database exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$dbName}`");

} catch (PDOException $e) {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Database Connection Error</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    </head>
    <body class="bg-light d-flex align-items-center min-vh-100">
        <div class="container text-center py-5">
            <div class="card shadow-sm p-4 mx-auto" style="max-width: 500px;">
                <h3 class="text-danger mb-3">Database Connection Failed</h3>
                <p class="text-muted">Could not connect to MySQL using PDO.</p>
                <div class="alert alert-secondary text-start small font-monospace">
                    <?= htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') ?>
                </div>
                <p class="small text-muted">Make sure MySQL is running in XAMPP and credentials in <code>config/db.php</code> are correct.</p>
                <a href="setup.php" class="btn btn-primary">Run Database Setup</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
