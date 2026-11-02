<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/csrf.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME . ' - Smart College Portal' ?></title>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/dashboard.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/responsive.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-navy shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= BASE_URL ?>">
                <i class="fas fa-graduation-cap text-cyan me-2 fa-lg"></i> <?= SITE_NAME ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>about.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>contact.php">Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>faq.php">FAQ</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle btn btn-outline-cyan text-white px-3 py-1 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['name'] ?? 'Account') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <?php if($_SESSION['role'] == 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/index.php"><i class="fas fa-tachometer-alt me-2 text-primary"></i> Admin Dashboard</a></li>
                                <?php elseif($_SESSION['role'] == 'faculty'): ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>faculty/index.php"><i class="fas fa-tachometer-alt me-2 text-primary"></i> Faculty Dashboard</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>dashboard/index.php"><i class="fas fa-tachometer-alt me-2 text-primary"></i> Student Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-2"><a class="btn btn-outline-light btn-sm px-3" href="<?= BASE_URL ?>login.php">Login</a></li>
                        <li class="nav-item ms-2"><a class="btn btn-cyan btn-sm px-3 text-white" href="<?= BASE_URL ?>register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
