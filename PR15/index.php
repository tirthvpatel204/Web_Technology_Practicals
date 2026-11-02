<?php
$page_title = "Home";
require_once 'includes/header.php';
require_once 'config/db.php';

// Fetch public stats
$stmt = $pdo->query("SELECT COUNT(*) FROM students");
$total_students = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM faculty");
$total_faculty = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM events");
$total_events = $stmt->fetchColumn();
?>

<!-- Hero Section -->
<section class="hero-section text-white py-5" style="background: linear-gradient(135deg, #14213D 0%, #2563EB 100%);">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <span class="badge bg-cyan text-navy px-3 py-2 rounded-pill fw-bold mb-3"><i class="fas fa-rocket me-1"></i> Next-Gen Campus Platform</span>
                <h1 class="display-4 fw-bold mb-3">Empowering Education Through Smart Management</h1>
                <p class="lead mb-4 text-white-50">StudentHub is a comprehensive college portal engineered for real-time tracking, seamless assignments, attendance management, and role-based academic excellence.</p>
                <div class="d-flex gap-3">
                    <a href="register.php" class="btn btn-cyan btn-lg px-4 text-white fw-bold shadow">Get Started</a>
                    <a href="login.php" class="btn btn-outline-light btn-lg px-4">Portal Login</a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="p-4 bg-white rounded-4 shadow-lg text-dark">
                    <i class="fas fa-graduation-cap fa-4x text-cyan mb-3"></i>
                    <h3 class="fw-bold text-navy">StudentHub Portal</h3>
                    <p class="text-muted small">Integrated with PHP 8, MySQL, PDO, and Bootstrap 5.</p>
                    <hr>
                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="fw-bold text-navy mb-0"><?= $total_students ?: 120 ?>+</h5>
                            <small class="text-muted">Students</small>
                        </div>
                        <div class="col-4">
                            <h5 class="fw-bold text-navy mb-0"><?= $total_faculty ?: 25 ?>+</h5>
                            <small class="text-muted">Faculty</small>
                        </div>
                        <div class="col-4">
                            <h5 class="fw-bold text-navy mb-0"><?= $total_events ?: 10 ?>+</h5>
                            <small class="text-muted">Events</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-navy">Platform Features</h2>
            <p class="text-muted">Designed to streamline campus life and administrative workflows.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 hover-lift">
                    <div class="text-cyan mb-3"><i class="fas fa-calendar-check fa-2x"></i></div>
                    <h4 class="fw-bold text-navy h5">Attendance Tracking</h4>
                    <p class="text-muted small">Monitor subject-wise attendance with automated percentage calculation and low-attendance warning alerts (< 75%).</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 hover-lift">
                    <div class="text-cyan mb-3"><i class="fas fa-tasks fa-2x"></i></div>
                    <h4 class="fw-bold text-navy h5">Assignment Management</h4>
                    <p class="text-muted small">Upload assignment submissions, track deadlines, view pending tasks, and grade student assignments securely.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 hover-lift">
                    <div class="text-cyan mb-3"><i class="fas fa-award fa-2x"></i></div>
                    <h4 class="fw-bold text-navy h5">Results & SGPA Calculator</h4>
                    <p class="text-muted small">View semester-wise internal/external marks, calculate SGPA/CGPA instantly, and analyze academic performance.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 hover-lift">
                    <div class="text-cyan mb-3"><i class="fas fa-book-open fa-2x"></i></div>
                    <h4 class="fw-bold text-navy h5">Study Material Repository</h4>
                    <p class="text-muted small">Access and download lecture notes, PDFs, and curriculum resources categorized by subject and semester.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 hover-lift">
                    <div class="text-cyan mb-3"><i class="fas fa-calendar-day fa-2x"></i></div>
                    <h4 class="fw-bold text-navy h5">College Events & RSVPs</h4>
                    <p class="text-muted small">Stay updated with upcoming cultural, technical, and sports events. Register instantly and track participant lists.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4 hover-lift">
                    <div class="text-cyan mb-3"><i class="fas fa-shield-alt fa-2x"></i></div>
                    <h4 class="fw-bold text-navy h5">Role-Based Access Control</h4>
                    <p class="text-muted small">Secure authentication for Admins, Faculty, and Students with encrypted passwords and session protection.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>