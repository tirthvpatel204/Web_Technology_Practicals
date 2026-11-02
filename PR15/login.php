<?php
$page_title = "Login";
require_once 'config/db.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            log_audit($pdo, $user['id'], 'LOGIN', 'User logged in successfully.');

            if ($user['role'] == 'admin') {
                header("Location: admin/index.php");
            } elseif ($user['role'] == 'faculty') {
                header("Location: faculty/index.php");
            } else {
                header("Location: dashboard/index.php");
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
require_once 'includes/header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="text-cyan mb-2"><i class="fas fa-graduation-cap fa-3x"></i></div>
                    <h3 class="fw-bold text-navy">Welcome Back</h3>
                    <p class="text-muted small">Sign in to your StudentHub account</p>
                </div>

                <?php if($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <!-- Fixed Button Styling with High Contrast -->
                    <button type="submit" class="btn btn-primary bg-cyan border-0 text-white w-100 py-2 fw-bold shadow">Login</button>
                </form>
                <div class="text-center mt-3">
                    <small class="text-muted">Don't have an account? <a href="register.php" class="text-decoration-none fw-bold text-cyan">Register here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>