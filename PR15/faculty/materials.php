<?php
$page_title = "Faculty Study Materials";
require_once '../includes/auth.php';
require_role('faculty');
require_once '../config/db.php';
require_once '../includes/header.php';
?>
<div class="container py-5">
    <h2 class="fw-bold text-navy mb-4">Upload Study Materials</h2>
    <div class="card border-0 shadow-sm p-4">
        <p class="text-muted">Faculty study material upload module.</p>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>