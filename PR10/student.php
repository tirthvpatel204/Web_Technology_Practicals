<?php
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    http_response_code(403);
    exit("403 Forbidden: Student access required.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Student Dashboard</title></head>
<body>
    <h2>Welcome Student</h2>
    <p>You have access to the Student Dashboard.</p>
    <p>Username: <?= htmlspecialchars((string)($_SESSION['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
