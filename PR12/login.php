<?php
require_once 'config.php';
if (!empty($_SESSION['admin_logged_in'])) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    // Demo credentials; change these before deployment.
    if ($user === 'admin' && $pass === 'admin123') {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = 'admin';
        flash('success', 'Welcome, admin. You are signed in.');
        header('Location: index.php'); exit;
    }
    $error = 'Incorrect username or password.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin Login | EventHub</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="style.css" rel="stylesheet"></head>
<body class="login-page"><div class="login-card"><div class="brand-mark">EH</div><h1>EventHub Admin</h1><p class="text-secondary">Sign in to manage your events</p>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label class="form-label">Username</label><input class="form-control mb-3" name="username" required autocomplete="username"><label class="form-label">Password</label><input class="form-control mb-4" type="password" name="password" required autocomplete="current-password"><button class="btn btn-primary w-100">Sign in</button></form><div class="small text-secondary mt-3">Demo: <code>admin</code> / <code>admin123</code></div></div></body></html>
