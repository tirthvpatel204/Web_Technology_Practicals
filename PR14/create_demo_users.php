<?php
// Run once locally after importing database.sql, then delete this file.
require_once __DIR__ . '/config/db.php';
$users = [
    ['Admin User', 'admin@example.com', 'admin123', 'admin'],
    ['Editor User', 'editor@example.com', 'admin123', 'editor'],
    ['Viewer User', 'viewer@example.com', 'admin123', 'viewer']
];
$stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), password_hash = VALUES(password_hash), role = VALUES(role), status = VALUES(status)');
foreach ($users as [$name, $email, $plain, $role]) {
    $stmt->execute([$name, $email, password_hash($plain, PASSWORD_DEFAULT), $role, 'active']);
}
echo 'Demo users created. Admin: admin@example.com / admin123. Delete this file after use.';
