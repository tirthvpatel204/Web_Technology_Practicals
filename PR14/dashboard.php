<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
require_login();

$user = current_user();
$activePage = 'dashboard';
$pageTitle = 'Executive Dashboard';

// -------------------------------------------------------------------------
// 1. DYNAMIC SQL AGGREGATION QUERIES (Key Question 1)
// -------------------------------------------------------------------------

// User Statistics
$totalUsers = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$activeUsers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn();
$inactiveUsers = $totalUsers - $activeUsers;

// Student Count
$totalStudents = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();

// Event Statistics
$totalEvents = (int)$pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
$upcomingEvents = (int)$pdo->query('SELECT COUNT(*) FROM events WHERE event_date >= CURDATE()')->fetchColumn();

// Registrations Count
$totalRegistrations = (int)$pdo->query('SELECT COUNT(*) FROM registrations')->fetchColumn();

// -------------------------------------------------------------------------
// 2. LATEST REGISTRATIONS (Joined query with user and event info)
// -------------------------------------------------------------------------
$stmtLatest = $pdo->query(
    'SELECT r.id, u.name AS user_name, u.email AS user_email, u.role AS user_role,
            e.title AS event_title, e.event_date, r.registered_at
     FROM registrations r
     JOIN users u ON u.id = r.user_id
     JOIN events e ON e.id = r.event_id
     ORDER BY r.registered_at DESC
     LIMIT 5'
);
$latestRegistrations = $stmtLatest->fetchAll();

// -------------------------------------------------------------------------
// 3. ADVANCED EXTENSION: MONTHLY EVENT REGISTRATIONS FOR ANALYTICS CHART
// -------------------------------------------------------------------------
$stmtMonthly = $pdo->query(
    "SELECT DATE_FORMAT(registered_at, '%b %Y') AS month_label,
            DATE_FORMAT(registered_at, '%Y-%m') AS sort_month,
            COUNT(*) AS reg_count
     FROM registrations
     GROUP BY sort_month, month_label
     ORDER BY sort_month ASC"
);
$monthlyRows = $stmtMonthly->fetchAll();

$chartLabels = array_column($monthlyRows, 'month_label');
$chartValues = array_map('intval', array_column($monthlyRows, 'reg_count'));

// Role Distribution Data
$stmtRoles = $pdo->query('SELECT role, COUNT(*) AS cnt FROM users GROUP BY role');
$roleRows = $stmtRoles->fetchAll();
$roleLabels = array_column($roleRows, 'role');
$roleCounts = array_map('intval', array_column($roleRows, 'cnt'));

// -------------------------------------------------------------------------
// 4. RECENT AUDIT LOGS (Admin Dashboard widget)
// -------------------------------------------------------------------------
$recentLogs = [];
if ($user['role'] === 'admin') {
    $stmtLogs = $pdo->query(
        'SELECT id, user_name, action, details, created_at
         FROM audit_logs
         ORDER BY id DESC
         LIMIT 5'
    );
    $recentLogs = $stmtLogs->fetchAll();
}

require __DIR__ . '/includes/header.php';
?>

<!-- Welcome Banner -->
<section class="welcome-card d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-white text-primary fw-bold text-uppercase px-2 py-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                LIVE AGGREGATION &bull; DATABASE CONNECTED
            </span>
            <span class="badge bg-white-50 text-white small">Role: <?= strtoupper(e($user['role'])) ?></span>
        </div>
        <h2 class="fw-bold mb-1">Welcome back, <?= e($user['name']) ?>!</h2>
        <p class="text-white-50 mb-0 small">
            Here is your dynamic overview of platform metrics, student counts, upcoming events, and registration trends.
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <?php if ($user['role'] === 'admin'): ?>
            <a href="admin/users.php" class="btn btn-light btn-sm fw-semibold shadow-sm px-3 py-2">
                Manage Users &amp; Status
            </a>
            <a href="admin/audit-logs.php" class="btn btn-outline-light btn-sm fw-semibold px-3 py-2">
                Security Audit Log
            </a>
        <?php elseif ($user['role'] === 'editor'): ?>
            <a href="admin/students.php" class="btn btn-light btn-sm fw-semibold shadow-sm px-3 py-2">
                Manage Students
            </a>
            <a href="admin/events.php" class="btn btn-outline-light btn-sm fw-semibold px-3 py-2">
                Manage Events
            </a>
        <?php else: ?>
            <a href="admin/events.php" class="btn btn-light btn-sm fw-semibold shadow-sm px-3 py-2">
                Browse Events
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- 4 Key Metric Stat Cards (Live SQL Aggregations) -->
<section class="row g-3 mb-4">
    <!-- Total Users -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div class="stat-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="badge bg-purple-subtle text-purple border" style="font-size: 0.72rem;">
                    <?= $activeUsers ?> Active &bull; <?= $inactiveUsers ?> Inactive
                </span>
            </div>
            <div class="stat-value"><?= $totalUsers ?></div>
            <div class="stat-label">Total Users</div>
            <div class="stat-meta text-muted">
                <?= $activeUsers ?> active accounts with RBAC roles
            </div>
        </div>
    </div>

    <!-- Total Students -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div class="stat-icon primary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <span class="badge bg-primary-subtle text-primary border" style="font-size: 0.72rem;">Enrolled</span>
            </div>
            <div class="stat-value"><?= $totalStudents ?></div>
            <div class="stat-label">Total Students</div>
            <div class="stat-meta text-muted">Enrolled across CE, IT, AI &amp; DS courses</div>
        </div>
    </div>

    <!-- Total Events -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div class="stat-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="badge bg-warning-subtle text-warning border" style="font-size: 0.72rem;">
                    <?= $upcomingEvents ?> Upcoming
                </span>
            </div>
            <div class="stat-value"><?= $totalEvents ?></div>
            <div class="stat-label">Total Events</div>
            <div class="stat-meta text-muted">Symposiums, Hackathons &amp; Bootcamps</div>
        </div>
    </div>

    <!-- Total Registrations -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div class="stat-icon success">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="badge bg-success-subtle text-success border" style="font-size: 0.72rem;">Verified</span>
            </div>
            <div class="stat-value"><?= $totalRegistrations ?></div>
            <div class="stat-label">Registrations</div>
            <div class="stat-meta text-muted">Total student and user event bookings</div>
        </div>
    </div>
</section>

<!-- ADVANCED EXTENSION: Analytics Charts Grid (Chart.js) -->
<section class="row g-3 mb-4">
    <!-- Monthly Registrations Bar / Area Chart -->
    <div class="col-12 col-lg-8">
        <div class="content-panel h-100 mb-0">
            <div class="panel-header">
                <div>
                    <h2>Event Registrations by Month</h2>
                    <span class="text-muted small">Advanced Extension: Dynamic SQL aggregation of monthly registrations</span>
                </div>
                <span class="badge bg-primary-subtle text-primary border">Chart.js Analytics</span>
            </div>
            <div class="panel-body">
                <div style="height: 260px; position: relative;">
                    <canvas id="monthlyRegistrationsChart"
                            data-labels='<?= json_encode($chartLabels) ?>'
                            data-values='<?= json_encode($chartValues) ?>'></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- User Role Distribution Doughnut Chart -->
    <div class="col-12 col-lg-4">
        <div class="content-panel h-100 mb-0">
            <div class="panel-header">
                <div>
                    <h2>User Role Breakdown</h2>
                    <span class="text-muted small">RBAC distribution in system</span>
                </div>
                <span class="badge bg-secondary-subtle text-secondary border">RBAC</span>
            </div>
            <div class="panel-body d-flex flex-column align-items-center justify-content-center">
                <div style="height: 220px; width: 100%; position: relative;">
                    <canvas id="roleDistributionChart"
                            data-roles='<?= json_encode($roleLabels) ?>'
                            data-counts='<?= json_encode($roleCounts) ?>'></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Registrations & Audit Activity Stream -->
<section class="row g-3">
    <!-- Latest Registrations Table -->
    <div class="<?= ($user['role'] === 'admin') ? 'col-12 col-xl-8' : 'col-12' ?>">
        <div class="content-panel mb-0">
            <div class="panel-header">
                <div>
                    <h2>Latest Event Registrations</h2>
                    <span class="text-muted small">Real-time joined records from registrations, users, and events tables</span>
                </div>
                <span class="badge bg-light text-secondary border">Latest <?= count($latestRegistrations) ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Event Title</th>
                            <th>Event Date</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestRegistrations as $reg): ?>
                            <tr>
                                <td>
                                    <div class="person-cell">
                                        <div class="cell-avatar">
                                            <?= strtoupper(substr($reg['user_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark"><?= e($reg['user_name']) ?></div>
                                            <div class="text-muted small" style="font-size: 0.75rem;"><?= e($reg['user_email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-primary"><?= e($reg['event_title']) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= date('M d, Y', strtotime($reg['event_date'])) ?>
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <?= date('M d, Y H:i', strtotime($reg['registered_at'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($latestRegistrations)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    No registrations recorded yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Security Audit Trail Stream (Visible only to Admin) -->
    <?php if ($user['role'] === 'admin'): ?>
        <div class="col-12 col-xl-4">
            <div class="content-panel mb-0">
                <div class="panel-header">
                    <div>
                        <h2>Audit Trail Activity</h2>
                        <span class="text-muted small">Latest actions logged</span>
                    </div>
                    <a href="admin/audit-logs.php" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 0.75rem;">
                        View All &rarr;
                    </a>
                </div>
                <div class="panel-body p-0">
                    <ul class="list-group list-group-flush small">
                        <?php foreach ($recentLogs as $log): ?>
                            <li class="list-group-item px-3 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-secondary-subtle text-secondary border font-monospace" style="font-size: 0.7rem;">
                                        <?= e($log['action']) ?>
                                    </span>
                                    <span class="text-muted" style="font-size: 0.7rem;">
                                        <?= date('M d, H:i', strtotime($log['created_at'])) ?>
                                    </span>
                                </div>
                                <div class="fw-semibold text-dark mb-1">
                                    <?= e($log['user_name']) ?>
                                </div>
                                <div class="text-muted text-truncate" style="font-size: 0.8rem;" title="<?= e($log['details']) ?>">
                                    <?= e($log['details']) ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($recentLogs)): ?>
                            <li class="list-group-item p-4 text-center text-muted">
                                No audit log records available.
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
