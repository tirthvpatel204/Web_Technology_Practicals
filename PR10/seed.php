<?php
require 'db.php';

$users = [
    ['admin', 'Admin@123', 'admin'],
    ['student', 'Student@123', 'student']
];

$stmt = $conn->prepare(
    "INSERT INTO users (username, password, role)
     VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE username = username"
);

foreach ($users as $user) {
    $username = $user[0];
    $password = password_hash($user[1], PASSWORD_DEFAULT);
    $role = $user[2];

    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
}

echo "Test users created successfully.";
?>