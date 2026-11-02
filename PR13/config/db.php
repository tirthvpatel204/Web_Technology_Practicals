<?php
declare(strict_types=1);

// Update these values using your hosting provider's MySQL details.
$host = 'YOUR_MYSQL_HOST';
$dbname = 'YOUR_DATABASE_NAME';
$username = 'YOUR_DATABASE_USERNAME';
$password = 'YOUR_DATABASE_PASSWORD';

header('Content-Type: application/json; charset=utf-8');

function jsonResponse(bool $success, string $message, array $extra = [], int $statusCode = 200): never {
    http_response_code($statusCode);
    echo json_encode(
        array_merge(['success' => $success, 'message' => $message], $extra),
        JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
    );
    exit;
}

function readJsonInput(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: '', true);
    if (!is_array($data)) {
        jsonResponse(false, 'Invalid JSON request body.', [], 400);
    }
    return $data;
}

function validateStudent(array $data): array {
    $name = trim((string)($data['name'] ?? ''));
    $email = trim((string)($data['email'] ?? ''));
    $course = trim((string)($data['course'] ?? ''));

    if ($name === '' || $email === '' || $course === '') {
        jsonResponse(false, 'Name, email and course are required.', [], 422);
    }
    if (mb_strlen($name) > 100 || mb_strlen($email) > 150 || mb_strlen($course) > 100) {
        jsonResponse(false, 'One or more fields exceed the allowed length.', [], 422);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(false, 'Please provide a valid email address.', [], 422);
    }
    return ['name' => $name, 'email' => $email, 'course' => $course];
}

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $exception) {
    error_log('Database connection failed: ' . $exception->getMessage());
    jsonResponse(false, 'Database connection failed. Check your hosting database settings.', [], 500);
}
