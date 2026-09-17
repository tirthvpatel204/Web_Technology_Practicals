<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$course = trim($_POST["course"] ?? "");
$message = trim($_POST["message"] ?? "");

$name = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
$email = htmlspecialchars($email, ENT_QUOTES, "UTF-8");
$phone = htmlspecialchars($phone, ENT_QUOTES, "UTF-8");
$course = htmlspecialchars($course, ENT_QUOTES, "UTF-8");
$message = htmlspecialchars($message, ENT_QUOTES, "UTF-8");

$errors = [];

if ($name === "") {
    $errors[] = "Name is required";
} elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
    $errors[] = "Name can contain only letters and spaces";
}

if ($email === "") {
    $errors[] = "Email is required";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address";
}

if ($phone === "") {
    $errors[] = "Phone number is required";
} elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
    $errors[] = "Phone number must contain exactly 10 digits";
}

if ($course === "") {
    $errors[] = "Please select a course";
}

if ($message === "") {
    $errors[] = "Message is required";
}

if (!empty($errors)) {
    $errorMessage = implode(", ", $errors);

    header("Location: index.php?error=" . urlencode($errorMessage));
    exit();
}

$dataFolder = __DIR__ . "/data";
$file = $dataFolder . "/registrations.csv";

if (!is_dir($dataFolder)) {
    mkdir($dataFolder, 0755, true);
}

$isNewFile = !file_exists($file) || filesize($file) === 0;

$handle = fopen($file, "a");

if ($handle === false) {
    header("Location: index.php?error=" . urlencode("Unable to open storage file"));
    exit();
}

if (flock($handle, LOCK_EX)) {

    if ($isNewFile) {
        fputcsv($handle, [
            "Name",
            "Email",
            "Phone",
            "Course",
            "Message",
            "Date"
        ]);
    }

    fputcsv($handle, [
        $name,
        $email,
        $phone,
        $course,
        $message,
        date("Y-m-d H:i:s")
    ]);

    flock($handle, LOCK_UN);
}

fclose($handle);

header("Location: index.php?success=1");
exit();

?>