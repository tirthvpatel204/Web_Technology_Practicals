<?php
$page_title = "Audit Logs";
require_once '../includes/auth.php';
require_role('admin');
require_once '../config/db.php';
$logs = $pdo->query("SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 50")->fetchAll();
require_once '../includes/header.php';
?>
<div class="container-fluid"><div class="row">
<div class="col-md-3 col-lg-2 bg-white sidebar p-3 min-vh-100"><a href="index.php" class="btn btn-outline-secondary w-100">Back</a></div>
<main class="col-md-9 col-lg-10 p-4">
    <h2 class="fw-bold text-navy mb-4">System Audit Logs</h2>
    <div class="card border-0 shadow-sm p-4">
        <table class="table">
            <tr><th>User ID</th><th>Action</th><th>Details</th><th>IP</th><th>Time</th></tr>
            <?php foreach($logs as $l): ?>
            <tr><td><?= $l['user_id'] ?></td><td><b><?= $l['action'] ?></b></td><td><?= $l['details'] ?></td><td><?= $l['ip_address'] ?></td><td><?= $l['created_at'] ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>
</div></div>
<?php require_once '../includes/footer.php'; ?>