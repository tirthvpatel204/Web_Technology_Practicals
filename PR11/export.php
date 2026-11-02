<?php
require "db.php";
$search = trim($_GET["search"] ?? "");
$course = trim($_GET["course"] ?? "");
$where = [];
$types = "";
$params = [];
if ($search !== "") {
    $where[] = "(name LIKE ? OR email LIKE ?)";
    $like = "%" . $search . "%";
    $types .= "ss"; $params[] = $like; $params[] = $like;
}
if ($course !== "") { $where[] = "course = ?"; $types .= "s"; $params[] = $course; }
$sql = "SELECT id, name, email, course, phone, created_at FROM students" . ($where ? " WHERE " . implode(" AND ", $where) : "") . " ORDER BY id";
$stmt = $conn->prepare($sql);
if ($types !== "") $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=students_" . date("Y-m-d") . ".csv");
$output = fopen("php://output", "w");
fwrite($output, "\xEF\xBB\xBF");
fputcsv($output, ["ID", "Name", "Email", "Course", "Phone", "Created At"]);
while ($row = $result->fetch_assoc()) {
    // Prefix formula-like values to reduce spreadsheet formula injection risk.
    foreach (["name", "email", "course", "phone"] as $key) {
        if (isset($row[$key]) && preg_match('/^[=+\-@]/', $row[$key])) $row[$key] = "'" . $row[$key];
    }
    fputcsv($output, $row);
}
fclose($output);
$stmt->close();
exit;
?>