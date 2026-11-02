<?php
$page_title = "Student Dashboard";
require_once '../includes/auth.php';
require_role('student');
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];

// Fetch student info
$stmt = $pdo->prepare("SELECT * FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();

// Fetch attendance stats
$stmt = $pdo->prepare("SELECT SUM(present_classes) as present, SUM(total_classes) as total FROM attendance WHERE student_id = ?");
$stmt->execute([$student['id'] ?? 0]);
$att = $stmt->fetch();
$attendance_pct = ($att && $att['total'] > 0) ? round(($att['present'] / $att['total']) * 100, 2) : 0;

// Fetch pending assignments count
$stmt = $pdo->prepare("SELECT COUNT(*) FROM assignments WHERE semester = ?");
$stmt->execute([$student['semester'] ?? 1]);
$pending_assignments = $stmt->fetchColumn();

// Fetch upcoming events
$stmt = $pdo->query("SELECT COUNT(*) FROM events WHERE event_date >= NOW()");
$upcoming_events = $stmt->fetchColumn();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-navy fw-bold">Welcome, <?= htmlspecialchars($student['name'] ?? $_SESSION['name']) ?>!</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="badge bg-cyan text-white px-3 py-2">Department: <?= htmlspecialchars($student['department'] ?? 'N/A') ?> (Sem <?= htmlspecialchars($student['semester'] ?? '1') ?>)</span>
                </div>
            </div>

            <!-- Widgets Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-4 border-cyan">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Attendance Rate</h6>
                                <h3 class="fw-bold text-navy mb-0"><?= $attendance_pct ?>%</h3>
                            </div>
                            <div class="text-cyan fa-2x"><i class="fas fa-calendar-check"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-4 border-primary">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Pending Assignments</h6>
                                <h3 class="fw-bold text-navy mb-0"><?= $pending_assignments ?></h3>
                            </div>
                            <div class="text-primary fa-2x"><i class="fas fa-tasks"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-4 border-success">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Upcoming Events</h6>
                                <h3 class="fw-bold text-navy mb-0"><?= $upcoming_events ?></h3>
                            </div>
                            <div class="text-success fa-2x"><i class="fas fa-calendar-day"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Notice Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold text-navy mb-3"><i class="fas fa-bullhorn text-cyan me-2"></i> Student Portal Overview</h4>
                <p class="text-muted">Welcome to your StudentHub portal. Use the sidebar to check attendance, view exam results, download study materials, calculate your SGPA, and register for college events.</p>
            </div>
        </main>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>