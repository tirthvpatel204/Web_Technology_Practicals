<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    jsonResponse(false, 'Method not allowed. Use GET.', [], 405);
}
try {
    $stmt = $pdo->query('SELECT id, name, email, course FROM students ORDER BY id DESC');
    jsonResponse(true, 'Students loaded successfully.', ['data' => $stmt->fetchAll()]);
} catch (PDOException $exception) {
    error_log('List students failed: ' . $exception->getMessage());
    jsonResponse(false, 'Could not load students.', [], 500);
}
