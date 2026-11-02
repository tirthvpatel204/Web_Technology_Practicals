<?php
header('Content-Type: application/json');
require_once '../config/db.php';
$stmt = $pdo->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 10");
echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
?>