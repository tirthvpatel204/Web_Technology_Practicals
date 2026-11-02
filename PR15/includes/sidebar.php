<?php
// Student Sidebar Navigation Component
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse shadow-sm p-0 min-vh-100">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4 px-3">
            <h6 class="text-navy fw-bold mb-0">Student Portal</h6>
            <small class="text-muted"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></small>
        </div>
        <ul class="nav flex-column px-2">
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'index.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'profile.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="profile.php"><i class="fas fa-user-graduate me-2"></i> My Profile</a></li>
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'attendance.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="attendance.php"><i class="fas fa-calendar-check me-2"></i> Attendance</a></li>
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'assignments.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="assignments.php"><i class="fas fa-tasks me-2"></i> Assignments</a></li>
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'results.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="results.php"><i class="fas fa-award me-2"></i> Results</a></li>
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'sgpa.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="sgpa.php"><i class="fas fa-calculator me-2"></i> SGPA Calculator</a></li>
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'timetable.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="timetable.php"><i class="fas fa-calendar-alt me-2"></i> Timetable</a></li>
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'materials.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="materials.php"><i class="fas fa-book-open me-2"></i> Study Materials</a></li>
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'events.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="events.php"><i class="fas fa-calendar-day me-2"></i> College Events</a></li>
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'notifications.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="notifications.php"><i class="fas fa-bell me-2"></i> Notifications</a></li>
            <li class="nav-item mb-1"><a class="nav-link rounded <?= $current_page == 'feedback.php' ? 'active bg-cyan text-white fw-bold' : 'text-dark' ?>" href="feedback.php"><i class="fas fa-comment-dots me-2"></i> Feedback</a></li>
            <li class="nav-item mt-4"><a class="nav-link rounded text-danger bg-light" href="<?= BASE_URL ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>
</div>
