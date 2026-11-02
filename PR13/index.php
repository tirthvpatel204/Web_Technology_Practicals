<?php
// Student Management System - Frontend
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Management System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand"><span class="brand-icon">S</span><div><strong>StudentHub</strong><small>Management Portal</small></div></div>
        <nav><a class="nav-link active" href="#dashboard"><span>▦</span> Dashboard</a><a class="nav-link" href="#students"><span>♙</span> Students</a></nav>
        <div class="sidebar-note"><span class="status-dot"></span> System operational</div>
    </aside>
    <main class="main-content" id="dashboard">
        <header class="topbar"><div><p class="eyebrow">OVERVIEW</p><h1>Student Dashboard</h1><p class="subtitle">Manage your student records in one place.</p></div><div class="topbar-badge"><span class="avatar">AD</span><div><strong>Administrator</strong><small>Management</small></div></div></header>
        <section class="stats-grid">
            <article class="stat-card"><div class="stat-icon blue">♙</div><p>Total Students</p><strong id="totalStudents">—</strong><small>Registered records</small></article>
            <article class="stat-card"><div class="stat-icon green">✓</div><p>System Status</p><strong class="status-text">Active</strong><small>API connection ready</small></article>
            <article class="stat-card"><div class="stat-icon purple">↻</div><p>Live Updates</p><strong>Enabled</strong><small>No page reload required</small></article>
        </section>
        <section class="panel" id="students">
            <div class="panel-heading"><div><h2>Student Records</h2><p>Add, edit, search or remove students.</p></div><button class="primary-btn" id="showFormBtn">＋ Add Student</button></div>
            <div class="toolbar"><label class="search-box"><span>⌕</span><input type="search" id="searchInput" placeholder="Search by name, email or course..."></label><button class="secondary-btn" id="refreshBtn">↻ Refresh</button></div>
            <div id="alertBox" class="alert" role="status" hidden></div>
            <form id="studentForm" class="student-form" hidden>
                <div class="form-title"><h3 id="formHeading">Add new student</h3><button type="button" class="icon-btn" id="closeFormBtn" aria-label="Close form">✕</button></div>
                <input type="hidden" id="studentId">
                <div class="form-grid">
                    <label>Full name<input id="name" name="name" type="text" maxlength="100" placeholder="e.g. Rahul Patel" required></label>
                    <label>Email address<input id="email" name="email" type="email" maxlength="150" placeholder="e.g. rahul@example.com" required></label>
                    <label class="full-width">Course<input id="course" name="course" type="text" maxlength="100" placeholder="e.g. BCA" required></label>
                </div>
                <div class="form-actions"><button type="button" class="secondary-btn" id="cancelBtn">Cancel</button><button type="submit" class="primary-btn" id="saveBtn">Save Student</button></div>
            </form>
            <div class="table-wrap"><table><thead><tr><th>STUDENT</th><th>EMAIL ADDRESS</th><th>COURSE</th><th class="action-col">ACTIONS</th></tr></thead><tbody id="studentTableBody"><tr><td colspan="4" class="empty-state">Loading students...</td></tr></tbody></table></div>
            <div class="table-footer"><span id="recordCount">Loading records...</span><span>StudentHub · JSON API</span></div>
        </section>
        <footer>StudentHub <span>•</span> PHP + MySQL + Fetch API Practical 13</footer>
    </main>
</div>
<script src="script.js"></script>
</body>
</html>
