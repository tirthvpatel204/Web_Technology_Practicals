# Laboratory Practical Report

**Subject**: Web Technology  
**Practical No.**: 14  
**Title**: Admin Dashboard with Role-Based Access Control, Dynamic Menu, and Audit Log  
**Course Outcomes (CO) Addressed**:
- **CO1**: Apply web development fundamentals and security principles.
- **CO4**: Implement server-side logic and database persistence using PHP and MySQL.
- **CO5**: Design and build modular, role-based administrative dashboards with dynamic content and audit trails.

---

## 1. Problem Statement
Develop an administrative dashboard that dynamically displays user statistics, student counts, event counts, and latest event registrations. Restrict access using **Role-Based Access Control (RBAC)**, dynamically render sidebar navigation based on assigned user roles, and maintain complete audit traceability in `audit_logs` for critical administrative operations.

---

## 2. Key Analysis & Interpretation

### Question 1: Are dashboard values loaded dynamically from the database?
**Answer**: Yes. All metric cards, rosters, and analytical charts on `dashboard.php` are computed on every request using dynamic SQL aggregate queries:

```sql
-- Total Users and Active/Inactive breakdown
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM users WHERE status = 'active';

-- Total Enrolled Students
SELECT COUNT(*) FROM students;

-- Total Campus Events & Upcoming Events
SELECT COUNT(*) FROM events;
SELECT COUNT(*) FROM events WHERE event_date >= CURDATE();

-- Total Event Registrations
SELECT COUNT(*) FROM registrations;

-- Latest Registrations Roster (Joined across 3 tables)
SELECT r.id, u.name AS user_name, u.email AS user_email,
       e.title AS event_title, e.event_date, r.registered_at
FROM registrations r
JOIN users u ON u.id = r.user_id
JOIN events e ON e.id = r.event_id
ORDER BY r.registered_at DESC
LIMIT 5;

-- Monthly Registrations for Chart.js Analytics (Advanced Extension)
SELECT DATE_FORMAT(registered_at, '%b %Y') AS month_label,
       DATE_FORMAT(registered_at, '%Y-%m') AS sort_month,
       COUNT(*) AS reg_count
FROM registrations
GROUP BY sort_month, month_label
ORDER BY sort_month ASC;
```

---

### Question 2: Is access restricted to admin users?
**Answer**: Yes. Enforced via the server-side middleware `require_role(['admin'])` defined in `includes/auth.php`.
- If an unauthenticated user visits a protected URL, they are redirected to `login.php`.
- If an authenticated user with insufficient permissions (e.g., `viewer` or `editor`) attempts to access an admin-only page (such as `admin/users.php` or `admin/audit-logs.php`), the application responds with **HTTP 403 Forbidden**, renders a custom access-denied page (`403.php`), and logs an `UNAUTHORIZED_ACCESS_ATTEMPT` event into `audit_logs`.

```php
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
        require __DIR__ . '/../403.php';
        exit;
    }
}
```

---

### Question 3: Are role-based menus working?
**Answer**: Yes. Navigation menu items are defined as a structured configuration array in `includes/header.php`. Each menu link specifies an array of authorized roles:

```php
$menuItems = [
    ['id' => 'dashboard',     'title' => 'Dashboard',       'roles' => ['admin', 'editor', 'viewer']],
    ['id' => 'students',      'title' => 'Students',        'roles' => ['admin', 'editor']],
    ['id' => 'events',        'title' => 'Events',          'roles' => ['admin', 'editor']],
    ['id' => 'registrations', 'title' => 'Registrations',   'roles' => ['admin', 'editor']],
    ['id' => 'users',         'title' => 'Users & Status',  'roles' => ['admin']],
    ['id' => 'audit_logs',    'title' => 'Audit Logs',      'roles' => ['admin']],
];
```

The template filters these items using `in_array($userRole, $item['roles'], true)`, ensuring users only see navigation options they are authorized to access.

---

### Question 4: Are important admin actions recorded in `audit_logs`?
**Answer**: Yes. All state mutations and security events are logged through `log_audit()`:

```php
function log_audit(PDO $pdo, string $action, string $details = ''): void {
    $user = current_user();
    $userId = $user['id'] ?? null;
    $userName = $user['name'] ?? 'System/Guest';
    $ip = substr($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', 0, 45);

    $stmt = $pdo->prepare(
        'INSERT INTO audit_logs (user_id, user_name, action, details, ip_address, created_at)
         VALUES (?, ?, ?, ?, ?, NOW())'
    );
    $stmt->execute([$userId, $userName, $action, $details, $ip]);
}
```

---

## 3. Role-Based Access Control (RBAC) Matrix

| Feature / Page | Path | Admin | Editor | Viewer |
|---|---|:---:|:---:|:---:|
| **Dashboard** | `dashboard.php` | ✅ Read | ✅ Read | ✅ Read |
| **User Management** | `admin/users.php` | ✅ Full (Toggle Status/Role) | ❌ 403 Forbidden | ❌ 403 Forbidden |
| **Student Enrollment** | `admin/students.php` | ✅ Full (Add/Delete) | ✅ Full (Add/Delete) | ❌ 403 Forbidden |
| **Events Management** | `admin/events.php` | ✅ Full (Create/Delete) | ✅ Full (Create/Delete) | ✅ View-Only |
| **Registrations Roster**| `admin/registrations.php`| ✅ Full (Book/Cancel) | ✅ Full (Book/Cancel) | ❌ 403 Forbidden |
| **Audit Logs Viewer** | `admin/audit-logs.php` | ✅ Full (View/Export/Clear)| ❌ 403 Forbidden | ❌ 403 Forbidden |

---

## 4. Test Cases & Verification Results

| Test Case ID | Test Objective | Input / Action | Expected Result | Actual Result | Status |
|:---:|---|---|---|---|:---:|
| **TC01** | Admin Authentication | Sign in with `admin@example.com` / `admin123` | Redirects to `dashboard.php`, shows Admin role badge | Successfully logged in, `LOGIN_SUCCESS` recorded | **PASS** |
| **TC02** | Live Aggregation | Load `dashboard.php` | Displays dynamic counts for Users, Students, Events, and Registrations | Metric cards render dynamic values from database | **PASS** |
| **TC03** | Dynamic Menu (Admin) | Check sidebar as Admin | Shows all 6 menu links (Dashboard, Students, Events, Registrations, Users, Audit Logs) | Full sidebar rendered | **PASS** |
| **TC04** | Dynamic Menu (Viewer) | Sign in with `viewer@example.com` | Shows only Dashboard & Events links in sidebar | Users and Audit Logs links are hidden | **PASS** |
| **TC05** | RBAC Page Enforcement | Viewer directly visits `/admin/users.php` | HTTP 403 Forbidden page rendered | HTTP 403 returned, `UNAUTHORIZED_ACCESS_ATTEMPT` logged | **PASS** |
| **TC06** | Status Toggle (Intermediate) | Admin clicks "Deactivate" on User ID 4 | Status toggles to `inactive`, `USER_STATUS_TOGGLED` recorded | User status changed to `inactive`, action logged | **PASS** |
| **TC07** | Inactive User Blocked | Deactivated user attempts login | Login blocked with "Your account has been deactivated" | Access denied, `LOGIN_FAILED` recorded | **PASS** |
| **TC08** | Analytics Chart (Advanced) | View `dashboard.php` | Chart.js renders monthly registrations bar chart | Dynamic monthly data rendered in chart | **PASS** |
| **TC09** | Student Record Mutation | Enroll student "Bhavik Dave" in IT Sem 4 | New row added to `students`, `STUDENT_ADDED` logged | Student enrolled, verified in table & audit log | **PASS** |
| **TC10** | CSV Audit Log Export | Click "Export CSV" in `audit-logs.php` | Downloads `audit_logs_YYYYMMDD_HHMMSS.csv` | CSV streamed with all audit records | **PASS** |

---

## 5. Viva Voce Questions & Answers

### Q1: What is Role-Based Access Control (RBAC) and how is it implemented here?
**Answer**: RBAC is an authorization mechanism where permissions are assigned to specific roles (e.g., `admin`, `editor`, `viewer`), and users are assigned to these roles. In this project, RBAC is implemented on two levels:
1. **Presentation Layer**: Navigation menu items are dynamically filtered in `header.php` so users only see routes permitted for their role.
2. **Controller Layer**: Every protected script invokes `require_role([...])` before rendering any output. Unauthorized access triggers an HTTP 403 response and logs a security violation in `audit_logs`.

### Q2: Why is client-side menu hiding alone insufficient for security?
**Answer**: Client-side menu hiding is merely a UI convenience. A malicious or inquisitive user can still manually navigate to the URL (e.g., `/admin/users.php`) or forge HTTP requests. Real security requires server-side validation on every single incoming request using session data stored securely on the server.

### Q3: How does the active/inactive status toggle prevent unauthorized access?
**Answer**: During authentication in `login.php`, the server checks `$user['status'] === 'active'`. If an account is inactive, `password_verify` might succeed, but the session is not created. Instead, an error message is returned and a `LOGIN_FAILED` audit entry is created.

### Q4: Why are prepared statements essential for the dashboard queries and audit logs?
**Answer**: Prepared statements separate the SQL code structure from the query parameters. The database compiles the SQL template first, and data values are bound subsequently. This completely eliminates SQL Injection vulnerabilities, even when user inputs are passed into search filters or insert statements.

### Q5: How is CSRF (Cross-Site Request Forgery) mitigated for admin actions?
**Answer**: Critical state-altering POST forms (status toggles, role updates, student deletions) include a cryptographic CSRF token generated via `bin2hex(random_bytes(32))` stored in the user's session. The server validates this token using `hash_equals()` before executing the action.
