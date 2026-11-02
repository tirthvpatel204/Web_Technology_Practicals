<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login(); // All authenticated users can view, but only admin & editor can modify

$currentUser = current_user();
$canManage = in_array($currentUser['role'], ['admin', 'editor'], true);

$activePage = 'events';
$pageTitle = 'Campus Events & Workshops';
$assetPrefix = '../';

// -------------------------------------------------------------------------
// POST HANDLERS (Create Event / Delete Event)
// -------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$canManage) {
        log_audit($pdo, 'UNAUTHORIZED_ACCESS_ATTEMPT', "Viewer role tried to modify events");
        flash('danger', 'Unauthorized: Viewers do not have permission to modify events.');
        header('Location: events.php');
        exit;
    }

    $action = $_POST['action'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verify_csrf($csrfToken)) {
        flash('danger', 'Security token mismatch. Please try again.');
        header('Location: events.php');
        exit;
    }

    if ($action === 'create_event') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $eventDate = trim($_POST['event_date'] ?? '');

        if ($title === '' || $eventDate === '') {
            flash('danger', 'Event title and date are required.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO events (title, description, event_date) VALUES (?, ?, ?)');
            $stmt->execute([$title, $description, $eventDate]);

            log_audit(
                $pdo,
                'EVENT_CREATED',
                "Event created: '{$title}' scheduled for {$eventDate}"
            );

            flash('success', "Event '{$title}' created successfully!");
        }
    } elseif ($action === 'delete_event') {
        $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
        if ($eventId) {
            $stmtGet = $pdo->prepare('SELECT title FROM events WHERE id = ?');
            $stmtGet->execute([$eventId]);
            $ev = $stmtGet->fetch();

            if ($ev) {
                $stmtDel = $pdo->prepare('DELETE FROM events WHERE id = ?');
                $stmtDel->execute([$eventId]);

                log_audit(
                    $pdo,
                    'EVENT_DELETED',
                    "Event ID {$eventId} ('{$ev['title']}') deleted along with any registrations"
                );

                flash('success', "Event '{$ev['title']}' deleted.");
            }
        }
    }

    header('Location: events.php');
    exit;
}

// Fetch events with registration count aggregate
$stmt = $pdo->query(
    'SELECT e.id, e.title, e.description, e.event_date, e.created_at,
            (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) AS reg_count
     FROM events e
     ORDER BY e.event_date ASC'
);
$events = $stmt->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<!-- Header Actions -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1">Campus Events &amp; Symposiums</h2>
        <p class="text-muted small mb-0">
            Track symposiums, hackathons, and technical bootcamps. Displays live registered count per event.
        </p>
    </div>
    <div class="d-flex gap-2">
        <input type="text" class="form-control form-control-sm" placeholder="Search events..." data-table-search="eventsTable" style="max-width: 200px;">
        <?php if ($canManage): ?>
            <button class="btn btn-primary btn-sm fw-semibold d-flex align-items-center gap-1 shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#addEventModal">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Create Event</span>
            </button>
        <?php endif; ?>
    </div>
</div>

<!-- Events Table Panel -->
<div class="content-panel">
    <div class="panel-header">
        <div>
            <h2>Scheduled Events</h2>
            <span class="text-muted small"><?= count($events) ?> campus events in database</span>
        </div>
        <span class="badge bg-light text-secondary border">
            <?= $canManage ? 'Full Access (Admin/Editor)' : 'Read-Only (Viewer)' ?>
        </span>
    </div>

    <div class="table-responsive">
        <table id="eventsTable" class="table table-modern align-middle">
            <thead>
                <tr>
                    <th>Event ID</th>
                    <th>Event Title &amp; Details</th>
                    <th>Date Scheduled</th>
                    <th>Status</th>
                    <th>Registrations</th>
                    <?php if ($canManage): ?>
                        <th class="text-end">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $ev): ?>
                    <?php
                    $isUpcoming = strtotime($ev['event_date']) >= strtotime(date('Y-m-d'));
                    ?>
                    <tr>
                        <td class="font-monospace text-muted small">#<?= (int)$ev['id'] ?></td>
                        <td>
                            <div class="fw-bold text-dark mb-1"><?= e($ev['title']) ?></div>
                            <div class="text-muted small" style="max-width: 380px;"><?= e($ev['description'] ?: 'No description provided.') ?></div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark"><?= date('M d, Y', strtotime($ev['event_date'])) ?></div>
                            <div class="text-muted small"><?= date('l', strtotime($ev['event_date'])) ?></div>
                        </td>
                        <td>
                            <?php if ($isUpcoming): ?>
                                <span class="badge bg-success-subtle text-success border">Upcoming</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary border">Completed</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border font-monospace px-2 py-1">
                                <?= (int)$ev['reg_count'] ?> registered
                            </span>
                        </td>
                        <?php if ($canManage): ?>
                            <td class="text-end">
                                <form method="post" action="events.php" class="d-inline" onsubmit="return confirm('Delete event <?= e($ev['title']) ?>? All associated registrations will also be removed.');">
                                    <input type="hidden" name="action" value="delete_event">
                                    <input type="hidden" name="event_id" value="<?= (int)$ev['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2" title="Delete Event">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($events)): ?>
                    <tr>
                        <td colspan="<?= $canManage ? '6' : '5' ?>" class="text-center py-4 text-muted">
                            No events currently scheduled.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add Event (Admin & Editor only) -->
<?php if ($canManage): ?>
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="post" action="events.php">
                    <input type="hidden" name="action" value="create_event">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold" id="addEventModalLabel">Create New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Event Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Cloud & DevOps Workshop 2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Scheduled Date</label>
                            <input type="date" name="event_date" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Event Description</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="Summary of speakers, agenda, and target participants"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-semibold px-4">Publish Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
