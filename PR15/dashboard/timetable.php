<?php
$page_title = "Timetable";
require_once '../includes/auth.php';
require_role('student');
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT semester FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();
$semester = $student['semester'] ?? 1;

$stmt = $pdo->prepare("SELECT * FROM timetable WHERE semester = ? ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), start_time");
$stmt->execute([$semester]);
$timetable = $stmt->fetchAll();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4"><i class="fas fa-calendar-alt text-cyan me-2"></i> Weekly Timetable</h2>
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-dark bg-navy">
                            <tr>
                                <th>Day</th>
                                <th>Subject</th>
                                <th>Faculty</th>
                                <th>Time</th>
                                <th>Classroom</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($timetable)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No timetable scheduled for your semester.</td></tr>
                            <?php else: ?>
                                <?php foreach($timetable as $t): ?>
                                <tr>
                                    <td class="fw-bold bg-light"><?= htmlspecialchars($t['day_of_week']) ?></td>
                                    <td class="fw-bold text-navy"><?= htmlspecialchars($t['subject_name']) ?></td>
                                    <td><?= htmlspecialchars($t['faculty_name']) ?></td>
                                    <td><?= $t['start_time'] ?> - <?= $t['end_time'] ?></td>
                                    <td><span class="badge bg-cyan text-white"><?= htmlspecialchars($t['classroom']) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>