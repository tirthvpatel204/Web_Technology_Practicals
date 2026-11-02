<?php
require "db.php";
// Run this file once from localhost to insert 20 sample records.
// Existing emails are skipped safely using INSERT IGNORE.
$names = [
    "Aarav Patel", "Diya Shah", "Krish Desai", "Anaya Mehta", "Reyansh Joshi",
    "Kiara Trivedi", "Vivaan Parmar", "Aadhya Dave", "Advik Modi", "Myra Bhatt",
    "Arjun Pandya", "Saanvi Vyas", "Dhruv Thakkar", "Ira Amin", "Kabir Soni",
    "Navya Patel", "Dev Shah", "Riya Desai", "Yash Mehta", "Mahi Joshi"
];
$courses = ["B.Tech AIML", "B.Tech CSE", "BCA", "B.Sc IT", "MCA"];
$stmt = $conn->prepare("INSERT IGNORE INTO students (name, email, course, phone) VALUES (?, ?, ?, ?)");
$added = 0;
foreach ($names as $i => $name) {
    $email = "student" . ($i + 1) . "@example.com";
    $course = $courses[$i % count($courses)];
    $phone = "98765" . str_pad((string)($i + 1), 5, "0", STR_PAD_LEFT);
    $stmt->bind_param("ssss", $name, $email, $course, $phone);
    $stmt->execute();
    $added += $stmt->affected_rows;
}
$stmt->close();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Seed Students</title><link rel="stylesheet" href="style.css"></head><body><div class="form-shell"><div class="form-card"><div class="eyebrow">DATABASE SETUP</div><h1>Sample data inserted</h1><p><?= (int)$added ?> new sample student record(s) added. Duplicate emails were skipped.</p><p class="warning">For safety, delete or rename seed.php after running it.</p><a class="btn primary" href="index.php">Open Student Dashboard</a></div></div></body></html>