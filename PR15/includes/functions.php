<?php
// includes/functions.php - Helper Functions & Audit Logger
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function log_audit($pdo, $user_id, $action, $details) {
    try {
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $action, $details, $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN']);
    } catch (Exception $e) {
        // Fail silently or log error
    }
}

function send_notification($pdo, $user_id, $title, $message, $type = 'general') {
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, is_read, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
    $stmt->execute([$user_id, $title, $message, $type]);
}
?>