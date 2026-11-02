PRACTICAL 10: SECURE LOGIN/LOGOUT WITH PHP SESSIONS

Requirements:
- XAMPP (Apache + MySQL)
- PHP with mysqli and mysqlnd enabled

Setup:
1. Copy the secure_login_practical10 folder to C:\xampp\htdocs\
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin and import database.sql.
4. Visit http://localhost/secure_login_practical10/seed.php once.
5. Delete seed.php after the test users are created.
6. Open http://localhost/secure_login_practical10/login.php

Test accounts:
Admin:
  Username: admin
  Password: Admin@123

Student:
  Username: student
  Password: Student@123

Tests:
- Login with correct credentials.
- Try an incorrect password.
- Log in as student and visit admin.php; expect HTTP 403.
- Log out and verify dashboard access is blocked.
- To test timeout, remain inactive for 15 minutes and make another request.

Notes:
- Session timeout is 900 seconds (15 minutes).
- Remember-me token is not included; it is an optional extension in the practical.
- For production use, enable HTTPS and add CSRF protection, login rate limiting,
  and robust session invalidation.
