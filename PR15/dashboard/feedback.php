<?php
$page_title = "Feedback";
require_once '../includes/auth.php';
require_role('student');
require_once '../config/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $rating = intval($_POST['rating'] ?? 5);
    $description = trim($_POST['description'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (empty($subject) || empty($description)) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO feedback (user_id, subject, rating, description, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt->execute([$user_id, $subject, $rating, $description])) {
            $success = "Feedback submitted successfully!";
        } else {
            $error = "Failed to submit feedback.";
        }
    }
}

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4"><i class="fas fa-comment-dots text-cyan me-2"></i> Submit Feedback</h2>
            <div class="card border-0 shadow-sm rounded-4 p-4 col-md-8">
                <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
                <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject / Topic</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rating (1 to 5)</label>
                        <select name="rating" class="form-select" required>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Very Poor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description / Comments</label>
                        <textarea name="description" rows="4" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-cyan text-white fw-bold px-4 shadow">Submit Feedback</button>
                </form>
            </div>
        </main>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>