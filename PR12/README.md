# Practical 12 — Event Management CRUD with Poster Upload

## Requirements
- XAMPP (Apache + MySQL), PHP 8+ recommended
- Browser; internet connection for Bootstrap CDN and Google Fonts (optional styling)

## Installation
1. Extract `Practical_12_Event_Management.zip`.
2. Copy the `Practical_12_Event_Management` folder into `C:\xampp\htdocs\`.
3. Start Apache and MySQL from XAMPP Control Panel.
4. Open `http://localhost/phpmyadmin`.
5. Import `database.sql` using the Import tab.
6. Confirm database credentials in `config.php` (default XAMPP: root with blank password).
7. Ensure `uploads` is writable by the web server.
8. Open `http://localhost/Practical_12_Event_Management/login.php`.

## Demo login
- Username: `admin`
- Password: `admin123`

## Features
- Admin login and logout; CRUD pages are protected by session checks.
- Create, view, update and delete event records using PDO prepared statements.
- Poster upload allows only JPEG/JPG, PNG, and WEBP; maximum size 2 MB.
- MIME type and image validity are checked server-side; file names are randomized.
- Keeps the old poster when updating without uploading a new one; deletes replaced/deleted posters.
- Category and Open/Closed status filters; success/failure messages.
- CSRF token on form submissions; HTML output escaping.
- `uploads/.htaccess` disables directory listing and blocks PHP-like files on Apache.

## Important notes
- Demo credentials are hard-coded for classroom demonstration. Replace with a proper admin table and `password_hash()` / `password_verify()` before production use.
- Apache must honor `.htaccess` for upload-directory protection. For stronger protection, store uploads outside the public web root and serve them through a controlled endpoint.
- PHP's `upload_max_filesize` and `post_max_size` must permit files of at least 2 MB.
- For evidence, create an event, edit it, delete a test event, upload a valid poster, and test a >2 MB file and a non-image file. Take screenshots from your own running XAMPP app; no real screenshots are fabricated in this package.
