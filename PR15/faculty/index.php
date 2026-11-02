<?php
$page_title = "Faculty Dashboard";
require_once '../includes/auth.php';
require_role('faculty');
require_once '../config/db.php';
require_once '../includes/header.php';
?>
<div class="container py-5">
    <div class="card border-0 shadow-sm p-4">
        <h2 class="fw-bold text-navy mb-3"><i class="fas fa-chalkboard-teacher text-cyan me-2"></i> Faculty Control Panel</h2>
        <p class="text-muted">Welcome, Faculty member. Use this portal to record attendance, post assignments, upload study materials, and manage student results.</p>
        <hr>
        <div class="row g-3">
            <div class="col-md-4"><a href="attendance.php" class="btn btn-outline-cyan w-100 py-3 fw-bold">Manage Attendance</a></div>
            <div class="col-md-4"><a href="assignments.php" class="btn btn-outline-primary w-100 py-3 fw-bold">Post Assignments</a></div>
            <div class="col-md-4"><a href="materials.php" class="btn btn-outline-success w-100 py-3 fw-bold">Upload Materials</a></div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>