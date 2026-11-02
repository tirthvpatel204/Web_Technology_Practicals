<?php
header('Content-Type: application/json');
require_once '../config/db.php';
$stmt = $pdo->query("SELECT * FROM events");
echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
?>