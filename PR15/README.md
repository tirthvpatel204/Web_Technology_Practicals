# StudentHub – Smart College Management & Student Portal

StudentHub is a complete, modern, professional, responsive, and fully functional college management system engineered using **HTML5, CSS3, JavaScript, PHP 8, MySQL, and Bootstrap 5**.

## Features & Modules
1. **Public Website:** Home, About Us, Contact Us, FAQ, Login, Registration.
2. **Student Dashboard:** Attendance tracker, assignments, results, SGPA calculator, timetable, study materials, college events, notifications, feedback, and profile.
3. **Admin Dashboard:** Student CRUD operations, faculty management, audit logs, and overview statistics.
4. **Faculty Portal:** Attendance recording, assignment posting, and grade management.
5. **REST JSON API (`api/students.php`):** GET, POST endpoints supporting Fetch API and live UI updates.
6. **Role-Based Access Control (RBAC):** Strict server-side session authentication for Admin, Faculty, and Student roles.

## Installation Instructions (XAMPP & phpMyAdmin)
1. Download and extract the `StudentHub.zip` archive into your XAMPP `htdocs` directory (`C:/xampp/htdocs/StudentHub/`).
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open phpMyAdmin (`http://localhost/phpmyadmin/`), create a new database named `studenthub`, and import the `database.sql` file.
4. Access the project in your browser at: `http://localhost/StudentHub/`

## Default Login Credentials
- **Admin:** `admin@example.test` / `password`
- **Faculty:** `faculty@example.test` / `password`
- **Student:** `student@example.test` / `password`
