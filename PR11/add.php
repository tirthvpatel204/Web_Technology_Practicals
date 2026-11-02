
<?php
session_start();
require "db.php";

if(empty($_SESSION["csrf"])){
    $_SESSION["csrf"] = bin2hex(random_bytes(32));
}

$errors = [];
$name = $email = $course = $phone = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(!hash_equals($_SESSION["csrf"], $_POST["csrf"] ?? "")){
        $errors[] = "Invalid form token. Please try again.";
    }

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $phone = trim($_POST["phone"] ?? "");

    if($name === "" || strlen($name) > 100){
        $errors[] = "Please enter a valid name.";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150){
        $errors[] = "Please enter a valid email.";
    }

    if($course === "" || strlen($course) > 100){
        $errors[] = "Please enter a valid course.";
    }

    if(strlen($phone) > 15){
        $errors[] = "Phone must be 15 characters or fewer.";
    }

    if(empty($errors)){
        $stmt = $conn->prepare(
            "INSERT INTO students (name, email, course, phone)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("ssss", $name, $email, $course, $phone);

        if($stmt->execute()){
            $_SESSION["flash"] = "Student added successfully!";
            header("Location: index.php");
            exit;
        }else{
            if($stmt->errno === 1062){
                $errors[] = "This email already exists.";
            }else{
                $errors[] = "Failed to add student.";
            }
        }

        $stmt->close();
    }
}

function e($value){
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-shell">
    <a class="back-link" href="index.php">← Back to Students</a>

    <div class="form-card">
        <h1>Add Student</h1>
        <p class="form-intro">Enter student details below.</p>

        <?php if(!empty($errors)): ?>
            <div class="error-box">
                <?php foreach($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="hidden" name="csrf"
                   value="<?= e($_SESSION["csrf"]) ?>">

            <div class="field">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name"
                       maxlength="100" required
                       value="<?= e($name) ?>">
            </div>

            <div class="field">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email"
                       maxlength="150" required
                       value="<?= e($email) ?>">
            </div>

            <div class="field">
                <label for="course">Course *</label>
                <input type="text" id="course" name="course"
                       maxlength="100" required
                       value="<?= e($course) ?>">
            </div>

            <div class="field">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone"
                       maxlength="15"
                       value="<?= e($phone) ?>">
            </div>

            <div class="form-actions">
                <button class="btn primary" type="submit">
                    Add Student
                </button>
                <a class="btn secondary" href="index.php">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
