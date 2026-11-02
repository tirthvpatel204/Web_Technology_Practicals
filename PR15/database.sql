CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'faculty', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    department VARCHAR(100) NOT NULL,
    semester INT NOT NULL,
    enrollment_number VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    department VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    present_classes INT NOT NULL DEFAULT 0,
    total_classes INT NOT NULL DEFAULT 0,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    semester INT NOT NULL,
    deadline DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    internal_marks INT NOT NULL,
    external_marks INT NOT NULL,
    total_marks INT NOT NULL,
    grade VARCHAR(5) NOT NULL,
    grade_points DECIMAL(3,1) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    event_date DATETIME NOT NULL,
    venue VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE timetable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    semester INT NOT NULL,
    day_of_week VARCHAR(20) NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    faculty_name VARCHAR(100) NOT NULL,
    classroom VARCHAR(50) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL
);

CREATE TABLE study_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    semester INT NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'general',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(150) NOT NULL,
    rating INT NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT NOT NULL,
    ip_address VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Initial Admin, Faculty & Student
INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@example.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Faculty Demo', 'faculty@example.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'faculty'),
('Student Demo', 'student@example.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

INSERT INTO students (user_id, name, email, department, semester, enrollment_number) VALUES 
(3, 'Student Demo', 'student@example.test', 'Artificial Intelligence & Machine Learning', 4, 'DEMO-0001');

INSERT INTO faculty (user_id, name, email, department) VALUES 
(2, 'Faculty Demo', 'faculty@example.test', 'Computer Engineering');

INSERT INTO attendance (student_id, subject_name, present_classes, total_classes) VALUES 
(1, 'Data Structures & Algorithms', 38, 45),
(1, 'Database Management Systems', 42, 45),
(1, 'Operating Systems', 28, 40);

INSERT INTO assignments (subject_name, title, description, semester, deadline) VALUES 
('Data Structures & Algorithms', 'Binary Search Tree Implementation', 'Implement a BST in C++ with insert, delete, and traversal methods.', 4, '2026-06-15');

INSERT INTO results (student_id, subject_name, internal_marks, external_marks, total_marks, grade, grade_points) VALUES 
(1, 'Data Structures & Algorithms', 26, 54, 80, 'A+', 9.0),
(1, 'Database Management Systems', 28, 58, 86, 'O', 10.0);

INSERT INTO events (title, description, event_date, venue) VALUES 
('TechHack 2026', 'Annual 24-hour coding hackathon showcasing AI & web prototypes.', '2026-07-20 09:00:00', 'University Auditorium');

INSERT INTO timetable (semester, day_of_week, subject_name, faculty_name, classroom, start_time, end_time) VALUES 
(4, 'Monday', 'Data Structures & Algorithms', 'Faculty Demo', 'LH-101', '09:00:00', '10:00:00'),
(4, 'Monday', 'Database Management Systems', 'Prof. Suresh Kumar', 'LH-102', '10:00:00', '11:00:00');
