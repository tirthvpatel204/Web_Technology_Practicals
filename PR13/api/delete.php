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
try {
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
    $stmt->execute([$id]);
    if ($stmt->rowCount() === 0) {
        jsonResponse(false, 'Student not found.', [], 404);
    }
    jsonResponse(true, 'Student deleted successfully.', ['data' => ['id' => $id]]);
} catch (PDOException $exception) {
    error_log('Delete student failed: ' . $exception->getMessage());
    jsonResponse(false, 'Could not delete student.', [], 500);
}
