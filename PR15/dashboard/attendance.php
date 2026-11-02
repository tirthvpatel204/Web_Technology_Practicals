<?php
$page_title = "Attendance Management";
require_once '../includes/auth.php';
require_role('student');
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();
$student_id = $student['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM attendance WHERE student_id = ?");
$stmt->execute([$student_id]);
$attendance_records = $stmt->fetchAll();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4"><i class="fas fa-calendar-check text-cyan me-2"></i> My Attendance Records</h2>
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark bg-navy">
                            <tr>
                                <th>Subject</th>
                                <th>Present Classes</th>
                                <th>Total Classes</th>
                                <th>Percentage</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($attendance_records)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No attendance records found.</td></tr>
                            <?php else: ?>
                                <?php foreach($attendance_records as $att): 
                                    $pct = $att['total_classes'] > 0 ? round(($att['present_classes'] / $att['total_classes']) * 100, 2) : 0;
                                    $is_low = $pct < 75;
                                ?>
                                <tr class="<?= $is_low ? 'table-danger' : '' ?>">
                                    <td class="fw-bold"><?= htmlspecialchars($att['subject_name']) ?></td>
                                    <td><?= $att['present_classes'] ?></td>
                                    <td><?= $att['total_classes'] ?></td>
                                    <td class="fw-bold"><?= $pct ?>%</td>
                                    <td>
                                        <?php if($is_low): ?>
                                            <span class="badge bg-danger">Low Attendance (< 75%)</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Good</span>
                                        <?php endif; ?>
                                    </td>
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