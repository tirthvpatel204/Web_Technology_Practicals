<?php
$page_title = "Assignments";
require_once '../includes/auth.php';
require_role('student');
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, semester FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();
$student_id = $student['id'] ?? 0;
$semester = $student['semester'] ?? 1;

$success = '';
$error = '';

// Handle Assignment Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assignment_id'])) {
    $assignment_id = intval($_POST['assignment_id']);
    
    if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] == 0) {
        $file_name = $_FILES['assignment_file']['name'];
        $file_tmp = $_FILES['assignment_file']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_exts = ['pdf', 'zip', 'docx', 'txt', 'cpp', 'py'];
        if (in_array($file_ext, $allowed_exts)) {
            $new_filename = 'asm_' . $assignment_id . '_std_' . $student_id . '_' . time() . '.' . $file_ext;
            $upload_path = __DIR__ . '/../uploads/assignments/' . $new_filename;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Check if already submitted
                $check = $pdo->prepare("SELECT id FROM assignment_submissions WHERE assignment_id = ? AND student_id = ?");
                $check->execute([$assignment_id, $student_id]);
                if ($check->fetch()) {
                    $upd = $pdo->prepare("UPDATE assignment_submissions SET file_path = ?, submitted_at = NOW() WHERE assignment_id = ? AND student_id = ?");
                    $upd->execute([$new_filename, $assignment_id, $student_id]);
                } else {
                    $ins = $pdo->prepare("INSERT INTO assignment_submissions (assignment_id, student_id, file_path, submitted_at) VALUES (?, ?, ?, NOW())");
                    $ins->execute([$assignment_id, $student_id, $new_filename]);
                }
                $success = "Assignment submitted successfully!";
            } else {
                $error = "Failed to upload file.";
            }
        } else {
            $error = "Invalid file type. Allowed: PDF, ZIP, DOCX, TXT, CPP, PY.";
        }
    } else {
        $error = "Please select a file to upload.";
    }
}

$stmt = $pdo->prepare("SELECT * FROM assignments WHERE semester = ?");
$stmt->execute([$semester]);
$assignments = $stmt->fetchAll();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4"><i class="fas fa-tasks text-cyan me-2"></i> Semester Assignments</h2>

            <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

            <div class="row g-4">
                <?php if(empty($assignments)): ?>
                    <div class="col-12"><div class="card border-0 shadow-sm p-4 text-center text-muted">No assignments posted for your semester yet.</div></div>
                <?php else: ?>
                    <?php foreach($assignments as $asm): 
                        // Check submission status
                        $sub_check = $pdo->prepare("SELECT * FROM assignment_submissions WHERE assignment_id = ? AND student_id = ?");
                        $sub_check->execute([$asm['id'], $student_id]);
                        $submission = $sub_check->fetch();
                    ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-cyan text-white"><?= htmlspecialchars($asm['subject_name']) ?></span>
                                <?php if($submission): ?>
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Submitted</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php endif; ?>
                            </div>
                            <h4 class="fw-bold text-navy h5"><?= htmlspecialchars($asm['title']) ?></h4>
                            <p class="text-muted small mb-3"><?= htmlspecialchars($asm['description']) ?></p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                                <small class="text-danger fw-bold"><i class="fas fa-clock me-1"></i> Due: <?= $asm['deadline'] ?></small>
                                <button class="btn btn-sm btn-cyan text-white fw-bold shadow" data-bs-toggle="modal" data-bs-target="#submitModal<?= $asm['id'] ?>">
                                    <?= $submission ? 'Re-submit Work' : 'Submit Work' ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submission Modal -->
                    <div class="modal fade" id="submitModal<?= $asm['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="modal-header bg-navy text-white">
                                        <h5 class="modal-title fw-bold">Submit Assignment: <?= htmlspecialchars($asm['title']) ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="assignment_id" value="<?= $asm['id'] ?>">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Upload Solution File (PDF, ZIP, DOCX, Code)</label>
                                            <input type="file" name="assignment_file" class="form-control" required>
                                            <small class="text-muted">Max size: 10MB.</small>
                                        </div>
                                        <?php if($submission): ?>
                                            <div class="alert alert-info py-2 small">
                                                <i class="fas fa-info-circle me-1"></i> You have already submitted a file: <b><?= htmlspecialchars($submission['file_path']) ?></b>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-cyan text-white btn-sm fw-bold shadow">Upload & Submit</button>
                                    </div>
                                </form>
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