<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_role(['admin', 'editor']);

$activePage = 'registrations';
$pageTitle = 'Event Registrations';
$assetPrefix = '../';
$currentUser = current_user();

// -------------------------------------------------------------------------
// POST HANDLERS (Create Registration / Cancel Registration)
// -------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verify_csrf($csrfToken)) {
        flash('danger', 'Security token mismatch. Please try again.');
        header('Location: registrations.php');
        exit;
    }

    if ($action === 'create_registration') {
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);

        if (!$userId || !$eventId) {
            flash('danger', 'Please select both a user and an event.');
        } else {
            // Check for existing registration
            $chk = $pdo->prepare('SELECT id FROM registrations WHERE user_id = ? AND event_id = ?');
            $chk->execute([$userId, $eventId]);
            if ($chk->fetch()) {
                flash('warning', 'This user is already registered for the selected event.');
            } else {
                $stmtAdd = $pdo->prepare('INSERT INTO registrations (user_id, event_id, registered_at) VALUES (?, ?, NOW())');
                $stmtAdd->execute([$userId, $eventId]);

                // Fetch details for logging
                $stmtUser = $pdo->prepare('SELECT name, email FROM users WHERE id = ?');
                $stmtUser->execute([$userId]);
                $uData = $stmtUser->fetch();

                $stmtEvent = $pdo->prepare('SELECT title FROM events WHERE id = ?');
                $stmtEvent->execute([$eventId]);
                $eData = $stmtEvent->fetch();

                log_audit(
                    $pdo,
                    'REGISTRATION_CREATED',
                    "User '{$uData['name']}' ({$uData['email']}) registered for event '{$eData['title']}'"
                );

                flash('success', "Registered {$uData['name']} for '{$eData['title']}' successfully!");
            }
        }
    } elseif ($action === 'cancel_registration') {
        $regId = filter_input(INPUT_POST, 'reg_id', FILTER_VALIDATE_INT);
        if ($regId) {
            $stmtGet = $pdo->prepare(
                'SELECT u.name AS user_name, e.title AS event_title
                 FROM registrations r
                 JOIN users u ON u.id = r.user_id
                 JOIN events e ON e.id = r.event_id
                 WHERE r.id = ?'
            );
            $stmtGet->execute([$regId]);
            $regData = $stmtGet->fetch();

            if ($regData) {
                $stmtDel = $pdo->prepare('DELETE FROM registrations WHERE id = ?');
                $stmtDel->execute([$regId]);

                log_audit(
                    $pdo,
                    'REGISTRATION_CANCELLED',
                    "Registration ID {$regId} cancelled: {$regData['user_name']} from {$regData['event_title']}"
                );

                flash('success', "Registration for {$regData['user_name']} was cancelled.");
            }
        }
    }

    header('Location: registrations.php');
    exit;
}

// Fetch all registrations with joined user and event details
$stmt = $pdo->query(
    'SELECT r.id, r.registered_at,
            u.id AS user_id, u.name AS user_name, u.email AS user_email, u.role AS user_role,
            e.id AS event_id, e.title AS event_title, e.event_date
     FROM registrations r
     JOIN users u ON u.id = r.user_id
     JOIN events e ON e.id = r.event_id
     ORDER BY r.registered_at DESC'
);
$registrations = $stmt->fetchAll();

// Fetch active users and events for the new registration modal
$userList = $pdo->query("SELECT id, name, email FROM users WHERE status = 'active' ORDER BY name ASC")->fetchAll();
$eventList = $pdo->query("SELECT id, title, event_date FROM events ORDER BY event_date ASC")->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<!-- Header Actions -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1">Event Registrations Roster</h2>
        <p class="text-muted small mb-0">
            Relational mapping connecting user accounts with campus events. Real-time trace with cancellation support.
        </p>
    </div>
    <div class="d-flex gap-2">
        <input type="text" class="form-control form-control-sm" placeholder="Search registrations..." data-table-search="regTable" style="max-width: 200px;">
        <button class="btn btn-primary btn-sm fw-semibold d-flex align-items-center gap-1 shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#addRegModal">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Register User</span>
        </button>
    </div>
</div>

<!-- Registrations Content Panel -->
<div class="content-panel">
    <div class="panel-header">
        <div>
            <h2>Confirmed Registrations</h2>
            <span class="text-muted small"><?= count($registrations) ?> total event bookings</span>
        </div>
        <span class="badge bg-light text-secondary border">RBAC: Admin &amp; Editor</span>
    </div>

    <div class="table-responsive">
        <table id="regTable" class="table table-modern align-middle">
            <thead>
                <tr>
                    <th>Reg #</th>
                    <th>Participant</th>
                    <th>Role</th>
                    <th>Registered Event</th>
                    <th>Event Date</th>
                    <th>Booking Timestamp</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $r): ?>
                    <tr>
                        <td class="font-monospace text-muted small">#<?= (int)$r['id'] ?></td>
                        <td>
                            <div class="person-cell">
                                <div class="cell-avatar" style="background: #ecfdf5; color: #047857;">
                                    <?= strtoupper(substr($r['user_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark"><?= e($r['user_name']) ?></div>
                                    <div class="text-muted small"><?= e($r['user_email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-role <?= e($r['user_role']) ?>">
                                <?= strtoupper(e($r['user_role'])) ?>
                            </span>
                        </td>
                        <td>
                            <span class="fw-semibold text-primary"><?= e($r['event_title']) ?></span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?= date('M d, Y', strtotime($r['event_date'])) ?>
                            </span>
                        </td>
                        <td class="text-muted small">
                            <?= date('M d, Y H:i', strtotime($r['registered_at'])) ?>
                        </td>
                        <td class="text-end">
                            <form method="post" action="registrations.php" class="d-inline" onsubmit="return confirm('Cancel registration for <?= e($r['user_name']) ?> from <?= e($r['event_title']) ?>?');">
                                <input type="hidden" name="action" value="cancel_registration">
                                <input type="hidden" name="reg_id" value="<?= (int)$r['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2" title="Cancel Booking">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($registrations)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No event registrations found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add Registration -->
<div class="modal fade" id="addRegModal" tabindex="-1" aria-labelledby="addRegModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="post" action="registrations.php">
                <input type="hidden" name="action" value="create_registration">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="addRegModalLabel">Register User for Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Select User Account</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Choose User --</option>
                            <?php foreach ($userList as $u): ?>
                                <option value="<?= (int)$u['id'] ?>">
                                    <?= e($u['name']) ?> (<?= e($u['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Select Campus Event</label>
                        <select name="event_id" class="form-select" required>
                            <option value="">-- Choose Event --</option>
                            <?php foreach ($eventList as $ev): ?>
                                <option value="<?= (int)$ev['id'] ?>">
                                    <?= e($ev['title']) ?> &bull; <?= date('M d, Y', strtotime($ev['event_date'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-semibold px-4">Confirm Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
