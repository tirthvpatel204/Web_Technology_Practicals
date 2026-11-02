<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;
$userRole = $user['role'] ?? 'viewer';
$assetPrefix = $assetPrefix ?? '';
$activePage = $activePage ?? 'dashboard';
$pageTitle = $pageTitle ?? 'Admin Dashboard';

// Dynamic Navigation Definition based on RBAC
$menuItems = [
    [
        'section' => 'Main',
        'items' => [
            [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'url' => $assetPrefix . 'dashboard.php',
                'roles' => ['admin', 'editor', 'viewer'],
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>'
            ]
        ]
    ],
    [
        'section' => 'Records & Moderation',
        'items' => [
            [
                'id' => 'students',
                'title' => 'Students',
                'url' => $assetPrefix . 'admin/students.php',
                'roles' => ['admin', 'editor'],
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-5.25 6.557c0 1.25.75 2.25 2.25 2.25" /></svg>'
            ],
            [
                'id' => 'events',
                'title' => 'Events',
                'url' => $assetPrefix . 'admin/events.php',
                'roles' => ['admin', 'editor'],
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>'
            ],
            [
                'id' => 'registrations',
                'title' => 'Registrations',
                'url' => $assetPrefix . 'admin/registrations.php',
                'roles' => ['admin', 'editor'],
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            ]
        ]
    ],
    [
        'section' => 'Administration & Security',
        'items' => [
            [
                'id' => 'users',
                'title' => 'Users & Status',
                'url' => $assetPrefix . 'admin/users.php',
                'roles' => ['admin'],
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>'
            ],
            [
                'id' => 'audit_logs',
                'title' => 'Audit Logs',
                'url' => $assetPrefix . 'admin/audit-logs.php',
                'roles' => ['admin'],
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>'
            ]
        ]
    ]
];

$flash = function_exists('get_flash') ? get_flash() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> | Admin Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Custom Executive Design System -->
    <link rel="stylesheet" href="<?= $assetPrefix ?>assets/css/style.css">
</head>
<body>
<div class="app-container">
    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- Executive RBAC Sidebar -->
    <aside class="app-sidebar">
        <a href="<?= $assetPrefix ?>dashboard.php" class="sidebar-brand">
            <div class="brand-icon-box">14</div>
            <div>
                <div style="line-height: 1.1;">Admin<span style="color: #818cf8;">Desk</span></div>
                <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 400; text-transform: uppercase; letter-spacing: 0.05em;">WT Practical 14</div>
            </div>
        </a>

        <!-- Mini Profile with Dynamic Role Badge -->
        <div class="sidebar-profile">
            <div class="user-avatar-circle">
                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="text-truncate">
                <div class="text-white fw-bold small text-truncate" style="max-width: 140px;">
                    <?= htmlspecialchars($user['name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="d-flex align-items-center gap-1 mt-1">
                    <span class="status-dot active"></span>
                    <span class="badge-role <?= htmlspecialchars($userRole, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars(strtoupper($userRole), ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Dynamic Role-Filtered Navigation -->
        <nav class="sidebar-nav">
            <?php foreach ($menuItems as $section): ?>
                <?php
                // Check if any items in this section match the current role
                $visibleItems = array_filter($section['items'], function ($item) use ($userRole) {
                    return in_array($userRole, $item['roles'], true);
                });
                ?>
                <?php if (!empty($visibleItems)): ?>
                    <div class="nav-section-title"><?= htmlspecialchars($section['section'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php foreach ($visibleItems as $item): ?>
                        <a href="<?= $item['url'] ?>" class="<?= ($activePage === $item['id']) ? 'active' : '' ?>">
                            <?= $item['icon'] ?>
                            <span><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= $assetPrefix ?>logout.php" class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                <span>Sign Out</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="app-main">
        <!-- Topbar Sticky Header -->
        <header class="app-topbar">
            <div class="topbar-left">
                <button id="sidebarToggle" class="btn btn-sm btn-light d-lg-none" type="button" aria-label="Toggle Navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="topbar-title">
                    <span class="text-uppercase small fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.06em;">ADMINISTRATION &bull; RBAC</span>
                    <h1><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-flex align-items-center gap-2 bg-light px-3 py-1 rounded-pill border small">
                    <span class="text-muted">Access Level:</span>
                    <strong class="text-primary text-uppercase"><?= htmlspecialchars($userRole, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="cell-avatar" style="width: 38px; height: 38px; border-radius: 50%;">
                        <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="d-none d-sm-block text-start" style="line-height: 1.2;">
                        <div class="small fw-bold text-dark"><?= htmlspecialchars($user['name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="text-muted" style="font-size: 0.72rem;"><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="app-content">
            <?php if ($flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
