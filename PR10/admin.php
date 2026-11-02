// 25AIML058

<?php
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit("403 Forbidden: Admin access required.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Admin Dashboard</title></head>
<body>
    <h2>Welcome Admin</h2>
    <p>You have access to the Admin Dashboard.</p>
    <p>Last login: <?= htmlspecialchars((string)($_SESSION['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>


\