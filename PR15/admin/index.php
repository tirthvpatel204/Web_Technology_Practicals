<?php
$page_title = "Admin Dashboard";
require_once '../includes/auth.php';
require_role('admin');
require_once '../config/db.php';

$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$total_faculty = $pdo->query("SELECT COUNT(*) FROM faculty")->fetchColumn();
$total_events = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$total_assignments = $pdo->query("SELECT COUNT(*) FROM assignments")->fetchColumn();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <!-- Admin Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse shadow-sm p-0 min-vh-100">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4 px-3">
                    <h6 class="text-navy fw-bold mb-0">Admin Control Panel</h6>
                    <small class="text-muted">Administrator</small>
                </div>
                <ul class="nav flex-column px-2">
                    <li class="nav-item mb-1"><a class="nav-link rounded active bg-cyan text-white fw-bold" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Overview</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded text-dark" href="students.php"><i class="fas fa-users me-2"></i> Students CRUD</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded text-dark" href="faculty.php"><i class="fas fa-chalkboard-teacher me-2"></i> Faculty</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded text-dark" href="events.php"><i class="fas fa-calendar-day me-2"></i> Events</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded text-dark" href="audit.php"><i class="fas fa-shield-alt me-2"></i> Audit Logs</a></li>
                    <li class="nav-item mt-4"><a class="nav-link rounded text-danger bg-light" href="<?= BASE_URL ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4">Admin Dashboard Overview</h2>
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-4 border-cyan">
                        <h6 class="text-muted mb-1">Total Students</h6>
                        <h3 class="fw-bold text-navy mb-0"><?= $total_students ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-4 border-primary">
                        <h6 class="text-muted mb-1">Total Faculty</h6>
                        <h3 class="fw-bold text-navy mb-0"><?= $total_faculty ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-4 border-success">
                        <h6 class="text-muted mb-1">Total Events</h6>
                        <h3 class="fw-bold text-navy mb-0"><?= $total_events ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-4 border-warning">
                        <h6 class="text-muted mb-1">Assignments</h6>
                        <h3 class="fw-bold text-navy mb-0"><?= $total_assignments ?></h3>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>