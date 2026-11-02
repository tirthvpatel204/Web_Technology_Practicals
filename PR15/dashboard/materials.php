<?php
$page_title = "Study Materials";
require_once '../includes/auth.php';
require_role('student');
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT semester FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();
$semester = $student['semester'] ?? 1;

$stmt = $pdo->prepare("SELECT * FROM study_materials WHERE semester = ?");
$stmt->execute([$semester]);
$materials = $stmt->fetchAll();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4"><i class="fas fa-book-open text-cyan me-2"></i> Study Materials & Notes</h2>
            <div class="row g-4">
                <?php if(empty($materials)): ?>
                    <div class="col-12"><div class="card border-0 shadow-sm p-4 text-center text-muted">No study materials uploaded for your semester yet.</div></div>
                <?php else: ?>
                    <?php foreach($materials as $mat): ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                            <span class="badge bg-cyan text-white align-self-start mb-2"><?= htmlspecialchars($mat['subject_name']) ?></span>
                            <h4 class="fw-bold text-navy h5"><?= htmlspecialchars($mat['title']) ?></h4>
                            <p class="text-muted small"><?= htmlspecialchars($mat['description']) ?></p>
                            <div class="mt-auto">
                                <a href="<?= BASE_URL ?>uploads/materials/<?= htmlspecialchars($mat['file_path']) ?>" class="btn btn-outline-cyan btn-sm fw-bold w-100" download><i class="fas fa-download me-1"></i> Download PDF</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>