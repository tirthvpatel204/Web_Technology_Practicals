<?php
require_once 'config.php';
require_admin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$editing = $id > 0;
$event = ['title'=>'','description'=>'','category'=>'Workshop','event_date'=>'','status'=>'Open','poster'=>null];
if ($editing) {
    $stmt = $pdo->prepare('SELECT * FROM events WHERE id = ?');
    $stmt->execute([$id]); $found = $stmt->fetch();
    if (!$found) { flash('danger', 'Event not found.'); header('Location: index.php'); exit; }
    $event = $found;
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $event['title'] = trim($_POST['title'] ?? '');
    $event['description'] = trim($_POST['description'] ?? '');
    $event['category'] = trim($_POST['category'] ?? '');
    $event['event_date'] = $_POST['event_date'] ?? '';
    $event['status'] = $_POST['status'] ?? 'Open';
    if ($event['title'] === '' || mb_strlen($event['title']) > 150) $errors[] = 'Title is required and must be 150 characters or fewer.';
    if ($event['description'] === '') $errors[] = 'Description is required.';
    if (!in_array($event['category'], ['Workshop','Conference','Festival','Sports','Webinar','Other'], true)) $errors[] = 'Choose a valid category.';
    $d = DateTime::createFromFormat('Y-m-d', $event['event_date']);
    if (!$d || $d->format('Y-m-d') !== $event['event_date']) $errors[] = 'Enter a valid event date.';
    if (!in_array($event['status'], ['Open','Closed'], true)) $errors[] = 'Choose a valid status.';
    $newPoster = null;
    if (!$errors) {
        try {
            $newPoster = save_poster($_FILES['poster'] ?? []);
            if ($editing) {
                $poster = $newPoster ?? $event['poster'];
                $stmt = $pdo->prepare('UPDATE events SET title=?, description=?, category=?, event_date=?, status=?, poster=? WHERE id=?');
                $stmt->execute([$event['title'],$event['description'],$event['category'],$event['event_date'],$event['status'],$poster,$id]);
                if ($newPoster) delete_poster($event['poster']);
                $event['poster'] = $poster;
                flash('success', 'Event updated successfully.');
            } else {
                $stmt = $pdo->prepare('INSERT INTO events (title, description, category, event_date, status, poster) VALUES (?,?,?,?,?,?)');
                $stmt->execute([$event['title'],$event['description'],$event['category'],$event['event_date'],$event['status'],$newPoster]);
                flash('success', 'Event created successfully.');
            }
            header('Location: index.php'); exit;
        } catch (Throwable $ex) {
            if ($newPoster) delete_poster($newPoster);
            $errors[] = $ex instanceof RuntimeException ? $ex->getMessage() : 'Unable to save the event. Please try again.';
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $editing?'Edit':'Add' ?> Event | EventHub</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="style.css" rel="stylesheet"></head><body><nav class="navbar navbar-dark"><div class="container"><a class="navbar-brand fw-bold" href="index.php"><span class="brand-dot">EH</span> EventHub</a><a class="btn btn-sm btn-outline-light" href="logout.php">Logout</a></div></nav><main class="container py-4 py-lg-5 form-wrap"><a href="index.php" class="back-link">← Back to events</a><div class="mb-4 mt-3"><p class="eyebrow mb-1">EVENT DETAILS</p><h1 class="page-title"><?= $editing?'Edit event':'Create an event' ?></h1><p class="text-secondary">Fill in the details below. Fields marked required must be completed.</p></div><div class="panel">
<?php if ($errors): ?><div class="alert alert-danger"><strong>Please fix the following:</strong><ul class="mb-0 mt-2"><?php foreach($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="mb-3"><label class="form-label">Event title *</label><input class="form-control" name="title" maxlength="150" required value="<?= e($event['title']) ?>" placeholder="e.g. AI Innovation Summit"></div><div class="mb-3"><label class="form-label">Description *</label><textarea class="form-control" name="description" rows="4" required placeholder="Describe the event..."><?= e($event['description']) ?></textarea></div><div class="row g-3"><div class="col-md-6"><label class="form-label">Category *</label><select class="form-select" name="category" required><?php foreach(['Workshop','Conference','Festival','Sports','Webinar','Other'] as $cat): ?><option <?= $event['category']===$cat?'selected':'' ?> value="<?= e($cat) ?>"><?= e($cat) ?></option><?php endforeach; ?></select></div><div class="col-md-6"><label class="form-label">Event date *</label><input class="form-control" type="date" name="event_date" required value="<?= e($event['event_date']) ?>"></div></div><div class="row g-3 mt-1"><div class="col-md-6"><label class="form-label">Event status *</label><select class="form-select" name="status"><option value="Open" <?= $event['status']==='Open'?'selected':'' ?>>Open</option><option value="Closed" <?= $event['status']==='Closed'?'selected':'' ?>>Closed</option></select></div><div class="col-md-6"><label class="form-label">Poster image</label><input class="form-control" type="file" name="poster" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"><div class="form-text">JPG, PNG or WEBP · Maximum 2 MB<?= $editing?' · Leave empty to keep current poster':'' ?></div></div></div>
<?php if ($editing && $event['poster']): ?><div class="mt-3"><div class="small text-secondary mb-2">Current poster</div><img src="uploads/<?= e($event['poster']) ?>" alt="Current event poster" class="current-poster"></div><?php endif; ?>
<div class="d-flex gap-2 mt-4"><button class="btn btn-primary px-4"><?= $editing?'Save changes':'Create event' ?></button><a class="btn btn-light border px-4" href="index.php">Cancel</a></div></form></div></main></body></html>
