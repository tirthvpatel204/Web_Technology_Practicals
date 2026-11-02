<?php
$page_title = "Manage Faculty";
require_once '../includes/auth.php';
require_role('admin');
require_once '../config/db.php';
$faculty = $pdo->query("SELECT * FROM faculty")->fetchAll();
require_once '../includes/header.php';
?>
<div class="container-fluid"><div class="row">
<div class="col-md-3 col-lg-2 bg-white sidebar p-3 min-vh-100">
    <a href="index.php" class="btn btn-outline-secondary w-100 mb-3">Back to Dashboard</a>
</div>
<main class="col-md-9 col-lg-10 p-4">
    <h2 class="fw-bold text-navy mb-4">Faculty Management</h2>
    <div class="card border-0 shadow-sm p-4">
        <table class="table">
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Department</th></tr>
            <?php foreach($faculty as $f): ?>
            <tr><td><?= $f['id'] ?></td><td><?= $f['name'] ?></td><td><?= $f['email'] ?></td><td><?= $f['department'] ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>
</div></div>
<?php require_once '../includes/footer.php'; ?>