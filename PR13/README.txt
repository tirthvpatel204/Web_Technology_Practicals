STUDENTHUB — PRACTICAL 13
PHP JSON API + Fetch API CRUD (without page reload)

FEATURES
- List, add, update and delete students without full-page reload
- PHP endpoints return JSON and use appropriate HTTP status codes
- MySQL database integration using PDO prepared statements
- Frontend and backend input validation
- Loading, success and error messages
- Debounced client-side search (250 ms)
- Responsive dashboard layout

FILES
index.php             Main website
style.css             Website design
script.js             Fetch API and frontend CRUD logic
config/db.php         MySQL connection and shared JSON/validation helpers
api/list.php          GET: list students
api/add.php           POST: add student
api/update.php        POST: update student
api/delete.php        POST: delete student
database.sql          Table creation SQL

DEPLOYMENT TO INFINITYFREE OR OTHER PHP HOSTING
1. Create a MySQL database from your hosting control panel.
2. Open phpMyAdmin for that database and import database.sql, or run its SQL manually.
3. Open config/db.php and replace:
   YOUR_MYSQL_HOST
   YOUR_DATABASE_NAME
   YOUR_DATABASE_USERNAME
   YOUR_DATABASE_PASSWORD
   with the exact values provided by your hosting control panel.
4. Upload the project files and folders to the hosting website's document root
   (often htdocs). Keep the folder structure unchanged.
5. Visit your website's index.php URL.
6. Test Add, Edit, Delete and Search. Open browser Developer Tools > Network
   and inspect requests to api/list.php, api/add.php, api/update.php and api/delete.php.

IMPORTANT
- Do not upload real database credentials to public repositories.
- Use HTTPS on the live website.
- This is a practical/demo application. Before public production use, add
  authentication/authorization, CSRF protection, rate limiting and stricter
  operational logging.
- PHP 8+ with PDO MySQL is recommended. The host must support PHP and MySQL.
- This project is intended to run on PHP hosting, not static-only hosting such
  as GitHub Pages.
