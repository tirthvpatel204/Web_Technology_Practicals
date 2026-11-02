<?php
$page_title = "Manage Events";
require_once '../includes/auth.php';
require_role('admin');
require_once '../config/db.php';
$events = $pdo->query("SELECT * FROM events")->fetchAll();
require_once '../includes/header.php';
?>
<div class="container-fluid"><div class="row">
<div class="col-md-3 col-lg-2 bg-white sidebar p-3 min-vh-100"><a href="index.php" class="btn btn-outline-secondary w-100">Back</a></div>
<main class="col-md-9 col-lg-10 p-4">
    <h2 class="fw-bold text-navy mb-4">Event Management</h2>
    <div class="card border-0 shadow-sm p-4">
        <table class="table">
            <tr><th>Title</th><th>Date</th><th>Venue</th></tr>
            <?php foreach($events as $e): ?>
            <tr><td><?= $e['title'] ?></td><td><?= $e['event_date'] ?></td><td><?= $e['venue'] ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>
</div></div>
<?php require_once '../includes/footer.php'; ?>