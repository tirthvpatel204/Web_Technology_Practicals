<?php
require_once 'config.php';
require_admin();

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC, id DESC");
$events = $stmt->fetchAll();

$total = count($events);
$open = count(array_filter($events, fn($x) => $x['status'] === 'Open'));
$closed = $total - $open;

$filter = $_GET['status'] ?? '';

if (in_array($filter, ['Open', 'Closed'], true)) {
    $events = array_values(
        array_filter($events, fn($x) => $x['status'] === $filter)
    );
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Events Dashboard | EventHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <span class="brand-dot">EH</span> EventHub
        </a>
        <div class="d-flex align-items-center gap-3 text-white">
            <span class="small">
                Admin: <?= e($_SESSION['admin_username'] ?? 'admin') ?>
            </span>
            <a class="btn btn-sm btn-outline-light" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<main class="container py-4 py-lg-5">

    <!-- Dashboard Heading -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="eyebrow mb-1">ADMIN WORKSPACE</p>
            <h1 class="page-title mb-1">Event management</h1>
            <p class="text-secondary mb-0">
                Create, update and organize your events.
            </p>
        </div>
        <a class="btn btn-primary btn-lg" href="event_form.php">
            <span class="me-2">＋</span>Add event
        </a>
    </div>

    <!-- Success and Error Messages -->
    <?php show_flash(); ?>

    <!-- STUDENT CREDIT CARD -->
    <section class="creator-credit">
        <div class="creator-glow"></div>
        <div class="creator-content">
            <div class="creator-icon">
                <span>✦</span>
            </div>

            <span class="creator-label">STUDENT PROJECT</span>

            <h2>Developed By</h2>

            <h3 class="creator-id">25AIML058</h3>

            <div class="creator-line"></div>

            <p class="creator-project">Event Management System</p>

            <div class="creator-footer">
                <span class="creator-dot"></span>
                DESIGNED &amp; DEVELOPED WITH PASSION
                <span class="creator-dot"></span>
            </div>
        </div>
    </section>
    <!-- END STUDENT CREDIT CARD -->

    <!-- Event Statistics -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stat-card">
                <span class="stat-label">Total events</span>
                <div class="stat-number"><?= $total ?></div>
                <span class="stat-note">All saved records</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <span class="stat-label">Open events</span>
                <div class="stat-number text-success"><?= $open ?></div>
                <span class="stat-note">Available to attend</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <span class="stat-label">Closed events</span>
                <div class="stat-number text-secondary"><?= $closed ?></div>
                <span class="stat-note">No longer accepting attendees</span>
            </div>
        </div>

    </div>

    <!-- Events Table -->
    <div class="panel">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h2 class="section-title mb-1">Your events</h2>
                <p class="small text-secondary mb-0">
                    Poster images accept JPG, PNG or WEBP up to 2 MB.
                </p>
            </div>

            <form method="get" class="d-flex gap-2">
                <select name="status" class="form-select" aria-label="Filter by status">
                    <option value="">All statuses</option>
                    <option value="Open" <?= $filter === 'Open' ? 'selected' : '' ?>>
                        Open
                    </option>
                    <option value="Closed" <?= $filter === 'Closed' ? 'selected' : '' ?>>
                        Closed
                    </option>
                </select>
                <button class="btn btn-outline-secondary" type="submit">Filter</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!$events): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                No events found. Add your first event to get started.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if ($event['poster']): ?>
                                        <img
                                            class="poster-thumb"
                                            src="uploads/<?= e($event['poster']) ?>"
                                            alt="Event poster"
                                        >
                                    <?php else: ?>
                                        <div class="poster-placeholder">EV</div>
                                    <?php endif; ?>

                                    <div>
                                        <div class="fw-semibold">
                                            <?= e($event['title']) ?>
                                        </div>
                                        <div class="small text-secondary">
                                            <?= e(mb_strimwidth($event['description'], 0, 58, '…')) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td><?= e($event['category']) ?></td>

                            <td>
                                <?= e(date('d M Y', strtotime($event['event_date']))) ?>
                            </td>

                            <td>
                                <span class="status-pill <?= $event['status'] === 'Open' ? 'status-open' : 'status-closed' ?>">
                                    <?= e($event['status']) ?>
                                </span>
                            </td>

                            <td class="text-end text-nowrap">
                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="event_form.php?id=<?= (int)$event['id'] ?>"
                                >Edit</a>

                                <form
                                    class="d-inline"
                                    method="post"
                                    action="delete.php"
                                    onsubmit="return confirm('Delete this event permanently?')"
                                >
                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= e(csrf_token()) ?>"
                                    >
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int)$event['id'] ?>"
                                    >
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <span>EventHub</span>
        <span class="footer-separator">•</span>
        Practical 12
        <span class="footer-separator">•</span>
        PHP + MySQL CRUD
        <div class="footer-credit">Student ID: 25AIML058</div>
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>