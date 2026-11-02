<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';

/**
 * Returns current authenticated user array or null.
 */
function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

/**
 * Requires an authenticated user session.
 */
function require_login(): void {
    if (!current_user()) {
        flash('warning', 'Please sign in to access this page.');
        $redirectUrl = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../login.php' : 'login.php';
        header("Location: {$redirectUrl}");
        exit;
    }
}

/**
 * Enforces Role-Based Access Control (RBAC).
 * If user does not have an allowed role, logs an audit entry and displays 403 Forbidden.
 */
function require_role(array $allowedRoles): void {
    require_login();
    $user = current_user();

    if (!in_array($user['role'], $allowedRoles, true)) {
        global $pdo;
        if (isset($pdo)) {
            log_audit(
                $pdo,
                'UNAUTHORIZED_ACCESS_ATTEMPT',
                "User with role '{$user['role']}' attempted to access restricted page: " . ($_SERVER['REQUEST_URI'] ?? 'unknown')
            );
        }

        http_response_code(403);
        $prefix = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
        require __DIR__ . '/../403.php';
        exit;
    }
}

/**
 * Check if the logged in user has any of the given roles.
 */
function has_role(array $roles): bool {
    $user = current_user();
    if (!$user) {
        return false;
    }
    return in_array($user['role'], $roles, true);
}

/**
 * Checks fine-grained module permissions.
 */
function can_access(string $permission): bool {
    $user = current_user();
    if (!$user) {
        return false;
    }

    $permissions = [
        'admin' => ['dashboard', 'users', 'students', 'events', 'registrations', 'audit_logs', 'analytics', 'settings'],
        'editor' => ['dashboard', 'students', 'events', 'registrations', 'analytics'],
        'viewer' => ['dashboard', 'events_view']
    ];

    $allowed = $permissions[$user['role']] ?? [];
    return in_array($permission, $allowed, true);
}

/**
 * Inserts an activity entry into the audit_logs table.
 */
function log_audit(PDO $pdo, string $action, string $details = ''): void {
    try {
        $user = current_user();
        $userId = $user['id'] ?? null;
        $userName = $user['name'] ?? 'System/Guest';

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $ip = substr(trim(explode(',', $ip)[0]), 0, 45);

        $stmt = $pdo->prepare(
            'INSERT INTO audit_logs (user_id, user_name, action, details, ip_address, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([$userId, $userName, $action, $details, $ip]);
    } catch (Throwable $e) {
        // Silently prevent audit logging failures from crashing primary user transactions
        error_log("Audit log failure: " . $e->getMessage());
    }
}

/**
 * HTML Escaping helper against XSS.
 */
function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Set flash alert message.
 */
function flash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type, // 'success', 'danger', 'warning', 'info'
        'message' => $message
    ];
}

/**
 * Retrieve and clear flash alert message.
 */
function get_flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * CSRF token helpers.
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(?string $token): bool {
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
