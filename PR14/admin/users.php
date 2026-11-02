<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);

$activePage = 'users';
$pageTitle = 'User Management & Status Control';
$assetPrefix = '../';
$currentUser = current_user();

// -------------------------------------------------------------------------
// POST ACTION HANDLERS
// -------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verify_csrf($csrfToken)) {
        flash('danger', 'Security token mismatch. Please try again.');
        header('Location: users.php');
        exit;
    }

    // 1. INTERMEDIATE EXTENSION: Status Toggle (Active <-> Inactive)
    if ($action === 'toggle_status') {
        $targetId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $newStatus = $_POST['new_status'] ?? '';

        if ($targetId && in_array($newStatus, ['active', 'inactive'], true)) {
            // Guard: Cannot deactivate own session
            if ($targetId === $currentUser['id']) {
                flash('warning', 'Action prohibited: You cannot deactivate your own administrative account.');
            } else {
                $stmtGet = $pdo->prepare('SELECT name, email, status FROM users WHERE id = ?');
                $stmtGet->execute([$targetId]);
                $targetUser = $stmtGet->fetch();

                if ($targetUser) {
                    $stmtUpdate = $pdo->prepare('UPDATE users SET status = ? WHERE id = ?');
                    $stmtUpdate->execute([$newStatus, $targetId]);

                    log_audit(
                        $pdo,
                        'USER_STATUS_TOGGLED',
                        "User ID {$targetId} ({$targetUser['name']}) status toggled from '{$targetUser['status']}' to '{$newStatus}'"
                    );

                    flash('success', "Status for '{$targetUser['name']}' successfully changed to " . strtoupper($newStatus) . '.');
                }
            }
        }
    }

    // 2. Role Change Action
    elseif ($action === 'change_role') {
        $targetId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $newRole = $_POST['new_role'] ?? '';

        if ($targetId && in_array($newRole, ['admin', 'editor', 'viewer'], true)) {
            if ($targetId === $currentUser['id'] && $newRole !== 'admin') {
                flash('warning', 'Action prohibited: You cannot demote your own administrative role.');
            } else {
                $stmtGet = $pdo->prepare('SELECT name, role FROM users WHERE id = ?');
                $stmtGet->execute([$targetId]);
                $targetUser = $stmtGet->fetch();

                if ($targetUser) {
                    $stmtUpdate = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
                    $stmtUpdate->execute([$newRole, $targetId]);

                    log_audit(
                        $pdo,
                        'USER_ROLE_CHANGED',
                        "User ID {$targetId} ({$targetUser['name']}) role changed from '{$targetUser['role']}' to '{$newRole}'"
                    );

                    flash('success', "Role for '{$targetUser['name']}' updated to " . strtoupper($newRole) . '.');
                }
            }
        }
    }

    // 3. Create New User
    elseif ($action === 'create_user') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $role = $_POST['role'] ?? 'viewer';
        $status = $_POST['status'] ?? 'active';

        if ($name === '' || $email === '' || $password === '') {
            flash('danger', 'All fields are required to create a new account.');
        } elseif (!in_array($role, ['admin', 'editor', 'viewer'], true)) {
            flash('danger', 'Invalid role selected.');
        } else {
            // Check email uniqueness
            $chk = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $chk->execute([$email]);
            if ($chk->fetch()) {
                flash('danger', "An account with email '{$email}' already exists.");
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmtAdd = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)');
                $stmtAdd->execute([$name, $email, $hash, $role, $status]);

                log_audit(
                    $pdo,
                    'USER_CREATED',
                    "New user created: {$name} ({$email}) with role: {$role}, status: {$status}"
                );

                flash('success', "New user '{$name}' created successfully!");
            }
        }
    }

    header('Location: users.php');
    exit;
}

// Fetch all users
$stmt = $pdo->query('SELECT id, name, email, role, status, created_at FROM users ORDER BY id ASC');
$allUsers = $stmt->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<!-- Top Action Row -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1">User Accounts &amp; Access Controls</h2>
        <p class="text-muted small mb-0">
            Enforce Role-Based Access Control (RBAC) and toggle account activation status. Deactivated users cannot sign in.
        </p>
    </div>
    <div class="d-flex gap-2">
        <input type="text" class="form-control form-control-sm" placeholder="Search users..." data-table-search="usersTable" style="max-width: 200px;">
        <button class="btn btn-primary btn-sm fw-semibold d-flex align-items-center gap-1 shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Add User</span>
        </button>
    </div>
</div>

<!-- Main Table Panel -->
<div class="content-panel">
    <div class="panel-header">
        <div>
            <h2>All Registered Users</h2>
            <span class="text-muted small">Total: <?= count($allUsers) ?> accounts configured in database</span>
        </div>
        <span class="badge bg-primary-subtle text-primary border">Admin Only View</span>
    </div>

    <div class="table-responsive">
        <table id="usersTable" class="table table-modern align-middle">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>User Details</th>
                    <th>Assigned Role</th>
                    <th>Account Status</th>
                    <th>Registered Date</th>
                    <th class="text-end">Administrative Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allUsers as $u): ?>
                    <?php $isSelf = ((int)$u['id'] === $currentUser['id']); ?>
                    <tr>
                        <td class="font-monospace text-muted small">#<?= (int)$u['id'] ?></td>
                        <td>
                            <div class="person-cell">
                                <div class="cell-avatar">
                                    <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">
                                        <?= e($u['name']) ?>
                                        <?php if ($isSelf): ?>
                                            <span class="badge bg-info-subtle text-info border ms-1" style="font-size: 0.65rem;">You</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted small"><?= e($u['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <!-- Role change form -->
                            <?php if (!$isSelf): ?>
                                <form method="post" action="users.php" class="d-inline">
                                    <input type="hidden" name="action" value="change_role">
                                    <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <select name="new_role" class="form-select form-select-sm d-inline-block w-auto py-1" onchange="this.form.submit()" style="font-size: 0.8rem;">
                                        <option value="admin" <?= ($u['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                        <option value="editor" <?= ($u['role'] === 'editor') ? 'selected' : '' ?>>Editor</option>
                                        <option value="viewer" <?= ($u['role'] === 'viewer') ? 'selected' : '' ?>>Viewer</option>
                                    </select>
                                </form>
                            <?php else: ?>
                                <span class="badge-role <?= e($u['role']) ?>"><?= strtoupper(e($u['role'])) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($u['status'] === 'active'): ?>
                                <span class="badge-status-active">
                                    <span class="status-dot active"></span>Active
                                </span>
                            <?php else: ?>
                                <span class="badge-status-inactive">
                                    <span class="status-dot inactive"></span>Inactive (Blocked)
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small">
                            <?= date('M d, Y', strtotime($u['created_at'])) ?>
                        </td>
                        <td class="text-end">
                            <?php if (!$isSelf): ?>
                                <!-- INTERMEDIATE EXTENSION: Active/Inactive Status Toggle Button -->
                                <form method="post" action="users.php" class="d-inline">
                                    <input type="hidden" name="action" value="toggle_status">
                                    <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <input type="hidden" name="new_status" value="<?= ($u['status'] === 'active') ? 'inactive' : 'active' ?>">

                                    <?php if ($u['status'] === 'active'): ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-3" onclick="return confirm('Are you sure you want to deactivate <?= e($u['name']) ?>? They will be blocked from logging in.');">
                                            Deactivate
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-outline-success btn-sm py-1 px-3">
                                            Activate
                                        </button>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small fst-italic">Active Session</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add New User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="post" action="users.php">
                <input type="hidden" name="action" value="create_user">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="addUserModalLabel">Create New User Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Priya Patel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="priya@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter secure password" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Role</label>
                            <select name="role" class="form-select">
                                <option value="viewer">Viewer</option>
                                <option value="editor">Editor</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Initial Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-semibold px-4">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
