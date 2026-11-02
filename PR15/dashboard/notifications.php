<?php
$page_title = "Notifications";
require_once '../includes/auth.php';
require_role('student');
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? OR user_id IS NULL ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifs = $stmt->fetchAll();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4"><i class="fas fa-bell text-cyan me-2"></i> Notifications</h2>
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="list-group list-group-flush">
                    <?php if(empty($notifs)): ?>
                        <p class="text-muted text-center py-4">No notifications available.</p>
                    <?php else: ?>
                        <?php foreach($notifs as $n): ?>
                        <div class="list-group-item border-0 border-bottom py-3">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 fw-bold text-navy"><?= htmlspecialchars($n['title']) ?></h5>
                                <small class="text-muted"><?= $n['created_at'] ?></small>
                            </div>
                            <p class="mb-1 text-muted"><?= htmlspecialchars($n['message']) ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>