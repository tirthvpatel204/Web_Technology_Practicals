<?php
$page_title = "College Events";
require_once '../includes/auth.php';
require_role('student');
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();
$student_id = $student['id'] ?? 0;

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
    $event_id = intval($_POST['event_id']);
    
    // Check if already registered
    $check = $pdo->prepare("SELECT id FROM event_registrations WHERE event_id = ? AND student_id = ?");
    $check->execute([$event_id, $student_id]);
    if ($check->fetch()) {
        $error = "You are already registered for this event.";
    } else {
        $ins = $pdo->prepare("INSERT INTO event_registrations (event_id, student_id, registered_at) VALUES (?, ?, NOW())");
        if ($ins->execute([$event_id, $student_id])) {
            $success = "Successfully registered for the event!";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll();

require_once '../includes/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="fw-bold text-navy mb-4"><i class="fas fa-calendar-day text-cyan me-2"></i> Upcoming College Events</h2>

            <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

            <div class="row g-4">
                <?php if(empty($events)): ?>
                    <div class="col-12"><div class="card border-0 shadow-sm p-4 text-center text-muted">No upcoming events scheduled.</div></div>
                <?php else: ?>
                    <?php foreach($events as $ev): 
                        // Check registration
                        $reg_check = $pdo->prepare("SELECT id FROM event_registrations WHERE event_id = ? AND student_id = ?");
                        $reg_check->execute([$ev['id'], $student_id]);
                        $is_registered = $reg_check->fetch();
                    ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                            <span class="badge bg-success align-self-start mb-2"><i class="fas fa-calendar me-1"></i> <?= $ev['event_date'] ?></span>
                            <h4 class="fw-bold text-navy h5"><?= htmlspecialchars($ev['title']) ?></h4>
                            <p class="text-muted small"><?= htmlspecialchars($ev['description']) ?></p>
                            <p class="text-muted mb-3"><small><i class="fas fa-map-marker-alt text-cyan me-1"></i> Venue: <?= htmlspecialchars($ev['venue']) ?></small></p>
                            <div class="mt-auto">
                                <?php if($is_registered): ?>
                                    <button class="btn btn-success btn-sm w-100 fw-bold" disabled><i class="fas fa-check-circle me-1"></i> Registered Successfully</button>
                                <?php else: ?>
                                    <form action="" method="POST">
                                        <input type="hidden" name="event_id" value="<?= $ev['id'] ?>">
                                        <button type="submit" class="btn btn-cyan btn-sm text-white fw-bold w-100 shadow">Register for Event</button>
                                    </form>
                                <?php endif; ?>
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