<?php
require_once 'config.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) { flash('danger', 'Invalid event ID.'); header('Location: index.php'); exit; }
$stmt = $pdo->prepare('SELECT poster FROM events WHERE id = ?');
$stmt->execute([$id]); $event = $stmt->fetch();
if (!$event) { flash('danger', 'Event not found.'); header('Location: index.php'); exit; }
$stmt = $pdo->prepare('DELETE FROM events WHERE id = ?');
$stmt->execute([$id]);
delete_poster($event['poster']);
flash('success', 'Event deleted successfully.');
header('Location: index.php');
exit;
