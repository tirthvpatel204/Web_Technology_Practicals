
//25AIML058   TIRTH V PATEL



<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_role(['admin']);

$activePage = 'audit_logs';
$pageTitle = 'Security & Activity Audit Logs';
$assetPrefix = '../';
$currentUser = current_user();

// CSV Export Action
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=audit_logs_' . date('Ymd_His') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'User ID', 'User Name', 'Action', 'Details', 'IP Address', 'Timestamp']);

    $stmt = $pdo->query('SELECT id, user_id, user_name, action, details, ip_address, created_at FROM audit_logs ORDER BY id DESC');
    while ($row = $stmt->fetch()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Clear Logs Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'clear_logs') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (verify_csrf($csrfToken)) {
        $pdo->exec('TRUNCATE TABLE audit_logs');
        log_audit($pdo, 'AUDIT_LOGS_PURGED', "All prior audit trail entries cleared by Admin {$currentUser['name']}");
        flash('warning', 'Audit logs cleared. A fresh log entry documenting the purge action has been created.');
    } else {
        flash('danger', 'CSRF verification failed.');
    }
    header('Location: audit-logs.php');
    exit;
}

// Filter handling
$actionFilter = trim($_GET['action_filter'] ?? '');
$search = trim($_GET['search'] ?? '');

$sql = 'SELECT id, user_id, user_name, action, details, ip_address, created_at FROM audit_logs WHERE 1=1';
$params = [];

if ($actionFilter !== '') {
    $sql .= ' AND action = ?';
    $params[] = $actionFilter;
}

if ($search !== '') {
    $sql .= ' AND (user_name LIKE ? OR details LIKE ? OR ip_address LIKE ?)';
    $like = "%{$search}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$sql .= ' ORDER BY id DESC LIMIT 250';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$logs = $stmt->fetchAll();

// Distinct actions for dropdown
$distinctActions = $pdo->query('SELECT DISTINCT action FROM audit_logs ORDER BY action ASC')->fetchAll(PDO::FETCH_COLUMN);

require __DIR__ . '/../includes/header.php';
?>

<!-- Header Actions -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1">System Audit &amp; Traceability Trail</h2>
        <p class="text-muted small mb-0">
            Immutable log tracking logins, RBAC status changes, permissions violations, and data mutations.
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="audit-logs.php?export=csv" class="btn btn-outline-secondary btn-sm fw-semibold d-flex align-items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>Export CSV</span>
        </a>

        <form method="post" action="audit-logs.php" class="d-inline" onsubmit="return confirm('WARNING: Are you sure you want to clear all audit logs?');">
            <input type="hidden" name="action" value="clear_logs">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                Clear Logs
            </button>
        </form>
    </div>
</div>

<!-- Filter Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="get" action="audit-logs.php" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by user, IP address, or action details..." value="<?= e($search) ?>">
            </div>
            <div class="col-12 col-md-4">
                <select name="action_filter" class="form-select form-select-sm">
                    <option value="">-- All Actions (<?= count($distinctActions) ?> types) --</option>
                    <?php foreach ($distinctActions as $act): ?>
                        <option value="<?= e($act) ?>" <?= ($actionFilter === $act) ? 'selected' : '' ?>>
                            <?= e($act) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter Logs</button>
                <?php if ($actionFilter !== '' || $search !== ''): ?>
                    <a href="audit-logs.php" class="btn btn-light btn-sm border">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Logs Table Panel -->
<div class="content-panel">
    <div class="panel-header">
        <div>
            <h2>Recorded Audit Events</h2>
            <span class="text-muted small">Displaying <?= count($logs) ?> latest entries</span>
        </div>
        <span class="badge bg-danger-subtle text-danger border">Admin Security Area</span>
    </div>

    <div class="table-responsive">
        <table class="table table-modern align-middle">
            <thead>
                <tr>
                    <th>Log #</th>
                    <th>Timestamp</th>
                    <th>Actor / User</th>
                    <th>Action</th>
                    <th>Event Details</th>
                    <th>Origin IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $l): ?>
                    <?php
                    // Dynamic action badge color mapping
                    $actionBadgeClass = 'bg-secondary-subtle text-secondary';
                    if (strpos($l['action'], 'LOGIN_SUCCESS') !== false) {
                        $actionBadgeClass = 'bg-success-subtle text-success border-success-subtle';
                    } elseif (strpos($l['action'], 'UNAUTHORIZED') !== false || strpos($l['action'], 'LOGIN_FAILED') !== false) {
                        $actionBadgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                    } elseif (strpos($l['action'], 'STATUS_TOGGLED') !== false || strpos($l['action'], 'ROLE_CHANGED') !== false) {
                        $actionBadgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                    } elseif (strpos($l['action'], 'DELETED') !== false || strpos($l['action'], 'CANCELLED') !== false) {
                        $actionBadgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                    } elseif (strpos($l['action'], 'CREATED') !== false || strpos($l['action'], 'ADDED') !== false) {
                        $actionBadgeClass = 'bg-primary-subtle text-primary border-primary-subtle';
                    }
                    ?>
                    <tr>
                        <td class="font-monospace text-muted small">#<?= (int)$l['id'] ?></td>
                        <td class="small text-nowrap">
                            <div class="fw-semibold text-dark"><?= date('M d, Y', strtotime($l['created_at'])) ?></div>
                            <div class="text-muted font-monospace" style="font-size: 0.72rem;"><?= date('H:i:s', strtotime($l['created_at'])) ?></div>
                        </td>
                        <td>
                            <div class="person-cell">
                                <div class="cell-avatar" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                    <?= strtoupper(substr($l['user_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark small"><?= e($l['user_name']) ?></div>
                                    <div class="text-muted" style="font-size: 0.7rem;">UID: <?= (int)($l['user_id'] ?? 0) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge border font-monospace <?= $actionBadgeClass ?>" style="font-size: 0.72rem;">
                                <?= e($l['action']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="small text-dark" style="max-width: 420px; line-height: 1.35;">
                                <?= e($l['details']) ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-muted border font-monospace" style="font-size: 0.72rem;">
                                <?= e($l['ip_address'] ?: '127.0.0.1') ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No audit activity matching your filter criteria.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
