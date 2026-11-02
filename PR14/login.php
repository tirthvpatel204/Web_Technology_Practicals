<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (current_user()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Please enter both your email address and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, name, email, password_hash, role, status FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Check active/inactive status (Intermediate Extension)
            if ($user['status'] !== 'active') {
                $error = 'Your account has been deactivated by an administrator. Access denied.';
                log_audit($pdo, 'LOGIN_FAILED', "Attempt to login to deactivated account: {$email}");
            } else {
                // Successful Login
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id'    => (int)$user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role']
                ];

                log_audit($pdo, 'LOGIN_SUCCESS', "User signed in successfully with role: {$user['role']}");
                flash('success', "Welcome back, {$user['name']}! You have signed in as " . strtoupper($user['role']) . '.');
                header('Location: dashboard.php');
                exit;
            }
        } else {
            $error = 'Invalid email address or password combination.';
            log_audit($pdo, 'LOGIN_FAILED', "Failed login attempt for email: {$email}");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Practical 14 Admin Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">
    <div class="login-card-container">
        <div class="login-panel">
            <div class="text-center mb-4">
                <div class="brand-icon-box mx-auto mb-3" style="width: 52px; height: 52px; font-size: 1.4rem;">
                    14
                </div>
                <div class="badge bg-primary-subtle text-primary border border-primary-subtle text-uppercase px-2 py-1 mb-2 fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                    Practical 14 &bull; Web Technology
                </div>
                <h2 class="fw-bold mb-1">Admin Portal Sign In</h2>
                <p class="text-muted small">Role-Based Access Control &amp; Traceability System</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 small p-3 mb-4" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                </div>
            <?php endif; ?>

            <form method="post" action="login.php" class="mb-4">
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
                    <input type="email" class="form-control py-2" id="email" name="email" required autocomplete="username" placeholder="e.g., admin@example.com">
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
                    <input type="password" class="form-control py-2" id="password" name="password" required autocomplete="current-password" placeholder="Enter password">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                    Sign In to Portal &rarr;
                </button>
            </form>

            <!-- Quick-Fill Demo Credentials for Viva / Testing -->
            <div class="border-top pt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase small fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.05em;">Quick-Test Demo Roles</span>
                    <span class="badge bg-light text-secondary border" style="font-size: 0.65rem;">Click to Auto-Fill</span>
                </div>

                <div class="demo-account-pill d-flex align-items-center justify-content-between" data-demo-email="admin@example.com" data-demo-password="admin123">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-role admin">Admin</span>
                        <span class="fw-semibold text-dark">admin@example.com</span>
                    </div>
                    <span class="text-muted font-monospace small">admin123</span>
                </div>

                <div class="demo-account-pill d-flex align-items-center justify-content-between" data-demo-email="editor@example.com" data-demo-password="editor123">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-role editor">Editor</span>
                        <span class="fw-semibold text-dark">editor@example.com</span>
                    </div>
                    <span class="text-muted font-monospace small">editor123</span>
                </div>

                <div class="demo-account-pill d-flex align-items-center justify-content-between" data-demo-email="viewer@example.com" data-demo-password="viewer123">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-role viewer">Viewer</span>
                        <span class="fw-semibold text-dark">viewer@example.com</span>
                    </div>
                    <span class="text-muted font-monospace small">viewer123</span>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="setup.php" class="text-decoration-none small text-secondary">
                    &bull; Database Installer / Reset Utility &bull;
                </a>
            </div>
        </div>
    </div>

    <!-- App JS -->
    <script src="assets/js/app.js"></script>
</body>
</html>
