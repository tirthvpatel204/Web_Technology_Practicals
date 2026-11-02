<?php
$page_title = "Results";
require_once '../includes/auth.php';
require_role('student');
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();
$student_id = $student['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM results WHERE student_id = ?");
$stmt->execute([$student_id]);
$results = $stmt->fetchAll();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4"><i class="fas fa-award text-cyan me-2"></i> Examination Results</h2>
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark bg-navy">
                            <tr>
                                <th>Subject</th>
                                <th>Internal Marks</th>
                                <th>External Marks</th>
                                <th>Total Marks</th>
                                <th>Grade</th>
                                <th>Grade Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($results)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">No examination results published yet.</td></tr>
                            <?php else: ?>
                                <?php foreach($results as $res): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($res['subject_name']) ?></td>
                                    <td><?= $res['internal_marks'] ?></td>
                                    <td><?= $res['external_marks'] ?></td>
                                    <td class="fw-bold"><?= $res['total_marks'] ?></td>
                                    <td><span class="badge bg-success"><?= $res['grade'] ?></span></td>
                                    <td class="fw-bold"><?= $res['grade_points'] ?></td>
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