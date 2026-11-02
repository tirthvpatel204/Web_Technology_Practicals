<?php
session_start();
require "db.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") { header("Location: index.php"); exit; }
if (empty($_SESSION["csrf"]) || !hash_equals($_SESSION["csrf"], $_POST["csrf"] ?? "")) {
    $_SESSION["flash"] = "Security check failed. Please try again.";
    header("Location: index.php"); exit;
}
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    $_SESSION["flash"] = "Invalid student ID.";
    header("Location: index.php"); exit;
}
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) $_SESSION["flash"] = $stmt->affected_rows ? "Student deleted successfully." : "Student record was not found.";
else $_SESSION["flash"] = "Unable to delete student.";
$stmt->close();
header("Location: index.php");
exit;
?>