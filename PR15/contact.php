<?php
$page_title = "Contact Us";
require_once 'includes/header.php';
require_once 'config/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt->execute([$name, $email, $subject, $message])) {
            $success = "Your message has been sent successfully. We will get back to you soon!";
        } else {
            $error = "Failed to send message. Please try again.";
        }
    }
}
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h2 class="fw-bold text-navy mb-3 text-center">Get in Touch</h2>
                <p class="text-muted text-center mb-4">Have questions or feedback? Drop us a message below.</p>
                
                <?php if($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                <?php if($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Message</label>
                            <textarea name="message" rows="5" class="form-control" required></textarea>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-cyan text-white px-5 py-2 fw-bold shadow">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>