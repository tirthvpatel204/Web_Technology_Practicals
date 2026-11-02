<?php
header('Content-Type: application/json');
require_once '../config/db.php';
require_once '../config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $stmt = $pdo->query("SELECT id, name, email, department, semester, enrollment_number FROM students");
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if(!empty($data['name']) && !empty($data['email'])) {
            $stmt = $pdo->prepare("INSERT INTO students (name, email, department, semester, enrollment_number, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$data['name'], $data['email'], $data['department'] ?? '', $data['semester'] ?? 1, $data['enrollment'] ?? '']);
            echo json_encode(['status' => 'success', 'message' => 'Student added successfully.']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
        break;
}
?>