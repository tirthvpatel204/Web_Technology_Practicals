<?php
require "db.php";

$search = trim($_GET["search"] ?? "");
$course = trim($_GET["course"] ?? "");
$page = max(1, (int)($_GET["page"] ?? 1));
$perPage = 5;
$offset = ($page - 1) * $perPage;

$where = [];
$types = "";
$params = [];

if ($search !== "") {
    $where[] = "(name LIKE ? OR email LIKE ?)";
    $like = "%" . $search . "%";
    $types .= "ss";
    $params[] = $like;
    $params[] = $like;
}
if ($course !== "") {
    $where[] = "course = ?";
    $types .= "s";
    $params[] = $course;
}
$whereSql = $where ? " WHERE " . implode(" AND ", $where) : "";

$countSql = "SELECT COUNT(*) AS total FROM students" . $whereSql;
$countStmt = $conn->prepare($countSql);
if ($types !== "") $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$total = (int)$countStmt->get_result()->fetch_assoc()["total"];
$countStmt->close();

$totalPages = max(1, (int)ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}

$sql = "SELECT id, name, email, course, phone, created_at FROM students" . $whereSql . " ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$queryTypes = $types . "ii";
$queryParams = $params;
$queryParams[] = $perPage;
$queryParams[] = $offset;
$stmt->bind_param($queryTypes, ...$queryParams);
$stmt->execute();
$students = $stmt->get_result();

$coursesResult = $conn->query("SELECT DISTINCT course FROM students ORDER BY course");
$courses = [];
while ($row = $coursesResult->fetch_assoc()) $courses[] = $row["course"];

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8"); }
$message = $_SESSION["flash"] ?? "";
if (session_status() === PHP_SESSION_NONE) session_start();
$message = $_SESSION["flash"] ?? "";
unset($_SESSION["flash"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Management</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-shell">
  <header class="topbar">
    <div><div class="eyebrow">ADMIN CONSOLE</div><h1>Student Management</h1><p>Manage student records in one place.</p></div>
    <a class="btn primary" href="add.php">＋ Add Student</a>
  </header>

  <?php if ($message !== ""): ?><div class="notice"><?= e($message) ?></div><?php endif; ?>

  <section class="stats">
    <div class="stat-card"><span>Total matching students</span><strong><?= $total ?></strong></div>
    <div class="stat-card"><span>Current page</span><strong><?= $page ?> <small>/ <?= $totalPages ?></small></strong></div>
    <div class="stat-card"><span>Records per page</span><strong><?= $perPage ?></strong></div>
  </section>

  <section class="panel">
    <form class="filters" method="get" action="index.php">
      <div class="field search-field"><label for="search">Search students</label><input id="search" name="search" value="<?= e($search) ?>" placeholder="Name or email..."></div>
      <div class="field"><label for="course">Filter by course</label><select id="course" name="course"><option value="">All courses</option><?php foreach ($courses as $c): ?><option value="<?= e($c) ?>" <?= $course === $c ? "selected" : "" ?>><?= e($c) ?></option><?php endforeach; ?></select></div>
      <button class="btn primary" type="submit">Search / Filter</button>
      <a class="btn secondary" href="index.php">Reset</a>
      <a class="btn secondary" href="export.php?search=<?= urlencode($search) ?>&course=<?= urlencode($course) ?>">Export CSV</a>
    </form>

    <div class="table-wrap">
      <table>
        <thead><tr><th>ID</th><th>Student</th><th>Email</th><th>Course</th><th>Phone</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if ($students->num_rows === 0): ?>
          <tr><td colspan="6" class="empty">No students found. Add a record or change your search.</td></tr>
        <?php else: while ($student = $students->fetch_assoc()): ?>
          <tr>
            <td class="id-cell">#<?= (int)$student["id"] ?></td>
            <td><strong><?= e($student["name"]) ?></strong><div class="muted">Added <?= e(date("d M Y", strtotime($student["created_at"]))) ?></div></td>
            <td><?= e($student["email"]) ?></td>
            <td><span class="course-pill"><?= e($student["course"]) ?></span></td>
            <td><?= e($student["phone"] ?: "—") ?></td>
            <td><div class="actions"><a class="action edit" href="edit.php?id=<?= (int)$student["id"] ?>">Edit</a><form method="post" action="delete.php" onsubmit="return confirm('Are you sure you want to delete this student?');"><input type="hidden" name="id" value="<?= (int)$student["id"] ?>"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><button class="action delete" type="submit">Delete</button></form></div></td>
          </tr>
        <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

    <div class="table-footer">
      <span>Showing <?= $total ? $offset + 1 : 0 ?>–<?= min($offset + $perPage, $total) ?> of <?= $total ?> records</span>
      <nav class="pagination">
        <?php $queryBase = ["search" => $search, "course" => $course]; ?>
        <?php if ($page > 1): ?><a href="?<?= e(http_build_query($queryBase + ["page" => $page - 1])) ?>">← Previous</a><?php else: ?><span class="disabled">← Previous</span><?php endif; ?>
        <?php for ($p = 1; $p <= $totalPages; $p++): ?><a class="<?= $p === $page ? "active" : "" ?>" href="?<?= e(http_build_query($queryBase + ["page" => $p])) ?>"><?= $p ?></a><?php endfor; ?>
        <?php if ($page < $totalPages): ?><a href="?<?= e(http_build_query($queryBase + ["page" => $page + 1])) ?>">Next →</a><?php else: ?><span class="disabled">Next →</span><?php endif; ?>
      </nav>
    </div>
  </section>
  <footer>Student Management System <span>PHP · MySQLi · XAMPP</span></footer>
</div>
</body>
</html>
<?php
function csrf_token() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION["csrf"])) $_SESSION["csrf"] = bin2hex(random_bytes(32));
    return $_SESSION["csrf"];
}
?>