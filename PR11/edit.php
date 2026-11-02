<?php
session_start();
require "db.php";
if (empty($_SESSION["csrf"])) $_SESSION["csrf"] = bin2hex(random_bytes(32));
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) { $_SESSION["flash"] = "Invalid student ID."; header("Location: index.php"); exit; }
$stmt = $conn->prepare("SELECT name, email, course, phone FROM students WHERE id = ?");
$stmt->bind_param("i", $id); $stmt->execute(); $student = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$student) { $_SESSION["flash"] = "Student not found."; header("Location: index.php"); exit; }
$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!hash_equals($_SESSION["csrf"], $_POST["csrf"] ?? "")) $errors[] = "Invalid form token. Please try again.";
    $student["name"] = trim($_POST["name"] ?? "");
    $student["email"] = trim($_POST["email"] ?? "");
    $student["course"] = trim($_POST["course"] ?? "");
    $student["phone"] = trim($_POST["phone"] ?? "");
    if ($student["name"] === "" || mb_strlen($student["name"]) > 100) $errors[] = "Name is required (maximum 100 characters).";
    if (!filter_var($student["email"], FILTER_VALIDATE_EMAIL) || mb_strlen($student["email"]) > 150) $errors[] = "Enter a valid email address.";
    if ($student["course"] === "" || mb_strlen($student["course"]) > 100) $errors[] = "Course is required (maximum 100 characters).";
    if (mb_strlen($student["phone"]) > 15) $errors[] = "Phone must be 15 characters or fewer.";
    if (!$errors) {
        $update = $conn->prepare("UPDATE students SET name = ?, email = ?, course = ?, phone = ? WHERE id = ?");
        $update->bind_param("ssssi", $student["name"], $student["email"], $student["course"], $student["phone"], $id);
        if ($update->execute()) { $_SESSION["flash"] = "Student updated successfully."; header("Location: index.php"); exit; }
        $errors[] = $update->errno === 1062 ? "That email address is already used by another student." : "Unable to update student.";
        $update->close();
    }
}
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8"); }
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Student</title><link rel="stylesheet" href="style.css"></head><body>
<div class="form-shell"><a class="back-link" href="index.php">← Back to students</a><div class="form-card"><div class="eyebrow">UPDATE RECORD #<?= (int)$id ?></div><h1>Edit Student</h1><p class="form-intro">Update the student's information.</p>
<?php if ($errors): ?><div class="error-box"><?php foreach ($errors as $error): ?><div><?= e($error) ?></div><?php endforeach; ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?= e($_SESSION["csrf"]) ?>">
<div class="field"><label for="name">Full name *</label><input id="name" name="name" maxlength="100" required value="<?= e($student["name"]) ?>"></div>
<div class="field"><label for="email">Email address *</label><input id="email" name="email" type="email" maxlength="150" required value="<?= e($student["email"]) ?>"></div>
<div class="field"><label for="course">Course *</label><input id="course" name="course" maxlength="100" required value="<?= e($student["course"]) ?>"></div>
<div class="field"><label for="phone">Phone</label><input id="phone" name="phone" maxlength="15" value="<?= e($student["phone"]) ?>"></div>
<div class="form-actions"><button class="btn primary" type="submit">Save Changes</button><a class="btn secondary" href="index.php">Cancel</a></div>
</form></div></div></body></html>