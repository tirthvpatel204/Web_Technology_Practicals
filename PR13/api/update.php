<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    jsonResponse(false, 'Method not allowed. Use POST.', [], 405);
}
$input = readJsonInput();
$id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) {
    jsonResponse(false, 'A valid student ID is required.', [], 422);
}
$data = validateStudent($input);
try {
    $stmt = $pdo->prepare('UPDATE students SET name = :name, email = :email, course = :course WHERE id = :id');
    $stmt->execute($data + ['id' => $id]);
    if ($stmt->rowCount() === 0) {
        $check = $pdo->prepare('SELECT id FROM students WHERE id = ?');
        $check->execute([$id]);
        if (!$check->fetch()) {
            jsonResponse(false, 'Student not found.', [], 404);
        }
    }
    jsonResponse(true, 'Student updated successfully.', ['data' => ['id' => $id]]);
} catch (PDOException $exception) {
    error_log('Update student failed: ' . $exception->getMessage());
    jsonResponse(false, 'Could not update student.', [], 500);
}
