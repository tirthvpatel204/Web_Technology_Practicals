<?php
$page_title = "Student Registration";
require_once 'config/db.php';
require_once 'config/config.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $department = trim($_POST['department'] ?? '');
    $semester = trim($_POST['semester'] ?? '');
    $enrollment = trim($_POST['enrollment'] ?? '');

    if (empty($name) || empty($email) || empty($password) || empty($department) || empty($semester) || empty($enrollment)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        try {
            // Check if email already exists in users table
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email address is already registered.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                // Start transaction safely
                $pdo->beginTransaction();

                // 1. Insert into users table
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, 'student', NOW())");
                $stmt->execute([$name, $email, $hashed_password]);
                $user_id = $pdo->lastInsertId();

                // 2. Insert into students table
                $stmt = $pdo->prepare("INSERT INTO students (user_id, name, email, department, semester, enrollment_number, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$user_id, $name, $email, $department, $semester, $enrollment]);

                $pdo->commit();
                $success = "Registration successful! You can now <a href='login.php' class='fw-bold text-cyan'>Login here</a>.";
            }
        } catch (Exception $e) {
            // Only roll back if a transaction is currently active
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
require_once 'includes/header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-navy">Student Registration</h3>
                    <p class="text-muted small">Create your StudentHub account</p>
                </div>

                <?php if($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                <?php if($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Department</label>
                            <select name="department" class="form-select" required>
                                <option value="">Select Department</option>
                                <option value="Computer Engineering" <?= (isset($_POST['department']) && $_POST['department'] == 'Computer Engineering') ? 'selected' : '' ?>>Computer Engineering</option>
                                <option value="Artificial Intelligence & Machine Learning" <?= (isset($_POST['department']) && $_POST['department'] == 'Artificial Intelligence & Machine Learning') ? 'selected' : '' ?>>AI & Machine Learning</option>
                                <option value="Information Technology" <?= (isset($_POST['department']) && $_POST['department'] == 'Information Technology') ? 'selected' : '' ?>>Information Technology</option>
                                <option value="Electronics & Communication" <?= (isset($_POST['department']) && $_POST['department'] == 'Electronics & Communication') ? 'selected' : '' ?>>Electronics & Communication</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="">Select Semester</option>
                                <?php for($i=1; $i<=8; $i++): ?>
                                    <option value="<?= $i ?>" <?= (isset($_POST['semester']) && $_POST['semester'] == $i) ? 'selected' : '' ?>>Semester <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Enrollment Number</label>
                        <input type="text" name="enrollment" class="form-control" required value="<?= htmlspecialchars($_POST['enrollment'] ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary bg-cyan border-0 text-white w-100 py-2 fw-bold shadow">Register Account</button>
                </form>
                <div class="text-center mt-3">
                    <small class="text-muted">Already have an account? <a href="login.php" class="text-decoration-none fw-bold text-cyan">Login here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>