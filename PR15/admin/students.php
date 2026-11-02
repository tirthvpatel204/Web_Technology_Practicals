<?php
$page_title = "Manage Students";
require_once '../includes/auth.php';
require_role('admin');
require_once '../config/db.php';

$students = $pdo->query("SELECT * FROM students ORDER BY id DESC")->fetchAll();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse shadow-sm p-0 min-vh-100">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column px-2">
                    <li class="nav-item mb-1"><a class="nav-link rounded text-dark" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Overview</a></li>
                    <li class="nav-item mb-1"><a class="nav-link rounded active bg-cyan text-white fw-bold" href="students.php"><i class="fas fa-users me-2"></i> Students CRUD</a></li>
                    <li class="nav-item mt-4"><a class="nav-link rounded text-danger bg-light" href="<?= BASE_URL ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-navy">Student Management (CRUD)</h2>
                <a href="<?= BASE_URL ?>register.php" class="btn btn-cyan text-white fw-bold shadow"><i class="fas fa-plus me-1"></i> Add Student</a>
            </div>
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark bg-navy">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Semester</th>
                                <th>Enrollment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($students as $s): ?>
                            <tr>
                                <td><?= $s['id'] ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($s['name']) ?></td>
                                <td><?= htmlspecialchars($s['email']) ?></td>
                                <td><?= htmlspecialchars($s['department']) ?></td>
                                <td>Sem <?= htmlspecialchars($s['semester']) ?></td>
                                <td><span class="badge bg-cyan text-white"><?= htmlspecialchars($s['enrollment_number']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>