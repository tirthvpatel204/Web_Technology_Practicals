// 25AIML058

<?php
require 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin'
        ? 'admin.php' : 'student.php'));
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare(
        "SELECT id, username, password, role FROM users WHERE username = ?"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        $update = $conn->prepare(
            "UPDATE users SET last_login = NOW() WHERE id = ?"
        );
        $update->bind_param("i", $user['id']);
        $update->execute();

        header("Location: " .
            ($user['role'] === 'admin' ? 'admin.php' : 'student.php'));
        exit;
    }

    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login</title>
</head>
<body>
    <h2>Secure Login</h2>

    <?php if (isset($_GET['timeout'])): ?>
        <p>Session expired. Please login again.</p>
    <?php endif; ?>

    <p style="color:red"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>

    <form method="post" action="login.php">
        <label for="username">Username:</label>
        <input id="username" type="text" name="username" required><br><br>

        <label for="password">Password:</label>
        <input id="password" type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
