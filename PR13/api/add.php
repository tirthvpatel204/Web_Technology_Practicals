<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    jsonResponse(false, 'Method not allowed. Use POST.', [], 405);
}
$data = validateStudent(readJsonInput());
try {
    $stmt = $pdo->prepare('INSERT INTO students (name, email, course) VALUES (:name, :email, :course)');
    $stmt->execute($data);
    jsonResponse(true, 'Student added successfully.', ['data' => ['id' => (int)$pdo->lastInsertId()] ], 201);
} catch (PDOException $exception) {
    error_log('Add student failed: ' . $exception->getMessage());
    if ($exception->getCode() === '23000') {
        jsonResponse(false, 'A record with these details may already exist.', [], 409);
    }
    jsonResponse(false, 'Could not add student.', [], 500);
}
