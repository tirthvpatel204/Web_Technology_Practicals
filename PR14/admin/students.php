<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_role(['admin', 'editor']);

$activePage = 'students';
$pageTitle = 'Student Records';
$assetPrefix = '../';
$currentUser = current_user();

// -------------------------------------------------------------------------
// POST HANDLERS (Add Student / Delete Student)
// -------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verify_csrf($csrfToken)) {
        flash('danger', 'Security token mismatch. Please try again.');
        header('Location: students.php');
        exit;
    }

    if ($action === 'add_student') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);

        if ($name === '' || $email === '' || $course === '' || !$semester) {
            flash('danger', 'All student fields are required.');
        } else {
            // Check email uniqueness
            $chk = $pdo->prepare('SELECT id FROM students WHERE email = ?');
            $chk->execute([$email]);
            if ($chk->fetch()) {
                flash('danger', "Student with email '{$email}' already exists.");
            } else {
                $stmtAdd = $pdo->prepare('INSERT INTO students (name, email, course, semester) VALUES (?, ?, ?, ?)');
                $stmtAdd->execute([$name, $email, $course, $semester]);

                log_audit(
                    $pdo,
                    'STUDENT_ADDED',
                    "New student enrolled: {$name} ({$course}, Sem {$semester})"
                );

                flash('success', "Student '{$name}' enrolled successfully!");
            }
        }
    } elseif ($action === 'delete_student') {
        $studentId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
        if ($studentId) {
            $stmtGet = $pdo->prepare('SELECT name, email FROM students WHERE id = ?');
            $stmtGet->execute([$studentId]);
            $student = $stmtGet->fetch();

            if ($student) {
                $stmtDel = $pdo->prepare('DELETE FROM students WHERE id = ?');
                $stmtDel->execute([$studentId]);

                log_audit(
                    $pdo,
                    'STUDENT_DELETED',
                    "Student ID {$studentId} ({$student['name']}) removed from database"
                );

                flash('success', "Student '{$student['name']}' was removed.");
            }
        }
    }

    header('Location: students.php');
    exit;
}

// Fetch all students
$stmt = $pdo->query('SELECT id, name, email, course, semester, created_at FROM students ORDER BY id DESC');
$students = $stmt->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<!-- Header Actions -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1">Student Enrollment Directory</h2>
        <p class="text-muted small mb-0">
            Accessible by <strong>Admin</strong> and <strong>Editor</strong> roles. Manage enrolled student data and curriculum details.
        </p>
    </div>
    <div class="d-flex gap-2">
        <input type="text" class="form-control form-control-sm" placeholder="Search students..." data-table-search="studentsTable" style="max-width: 200px;">
        <button class="btn btn-primary btn-sm fw-semibold d-flex align-items-center gap-1 shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Add Student</span>
        </button>
    </div>
</div>

<!-- Students Content Panel -->
<div class="content-panel">
    <div class="panel-header">
        <div>
            <h2>Enrolled Students</h2>
            <span class="text-muted small"><?= count($students) ?> student records in system</span>
        </div>
        <span class="badge bg-light text-secondary border">RBAC: Admin &amp; Editor</span>
    </div>

    <div class="table-responsive">
        <table id="studentsTable" class="table table-modern align-middle">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Email Address</th>
                    <th>Enrolled Course</th>
                    <th>Semester</th>
                    <th>Date Enrolled</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td class="font-monospace text-muted small">#<?= (int)$s['id'] ?></td>
                        <td>
                            <div class="person-cell">
                                <div class="cell-avatar" style="background: #e0f2fe; color: #0369a1;">
                                    <?= strtoupper(substr($s['name'], 0, 1)) ?>
                                </div>
                                <div class="fw-semibold text-dark"><?= e($s['name']) ?></div>
                            </div>
                        </td>
                        <td class="text-secondary small"><?= e($s['email']) ?></td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?= e($s['course']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border">
                                Sem <?= (int)$s['semester'] ?>
                            </span>
                        </td>
                        <td class="text-muted small">
                            <?= date('M d, Y', strtotime($s['created_at'])) ?>
                        </td>
                        <td class="text-end">
                            <form method="post" action="students.php" class="d-inline" onsubmit="return confirm('Delete student <?= e($s['name']) ?>?');">
                                <input type="hidden" name="action" value="delete_student">
                                <input type="hidden" name="student_id" value="<?= (int)$s['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2" title="Delete Student Record">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No student records found in the database.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add Student -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="post" action="students.php">
                <input type="hidden" name="action" value="add_student">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="addStudentModalLabel">Enroll New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Student Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Bhavik Dave" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Student Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g., bhavik.d@student.edu" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="form-label small fw-semibold">Course / Branch</label>
                            <select name="course" class="form-select" required>
                                <option value="Computer Engineering">Computer Engineering</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Artificial Intelligence">Artificial Intelligence</option>
                                <option value="Data Science">Data Science</option>
                                <option value="Electronics & Communication">Electronics &amp; Comm.</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-semibold">Semester</label>
                            <select name="semester" class="form-select" required>
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($i === 4) ? 'selected' : '' ?>>Sem <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-semibold px-4">Enroll Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
