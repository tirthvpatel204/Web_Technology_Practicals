-- ====================================================================
-- Practical 14: Admin Dashboard with RBAC, Dynamic Menu & Audit Log
-- Database Initialization Script
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `pr14_tp` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pr14_tp`;

-- Drop existing tables in correct order if recreating
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `registrations`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Users Table (RBAC with Roles & Active/Inactive Status)
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'editor', 'viewer') NOT NULL DEFAULT 'viewer',
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Students Table
CREATE TABLE `students` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `course` VARCHAR(100) NOT NULL,
    `semester` TINYINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_students_course` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Events Table
CREATE TABLE `events` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `event_date` DATE NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_events_date` (`event_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Registrations Table (Links users with events)
CREATE TABLE `registrations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `event_id` INT UNSIGNED NOT NULL,
    `registered_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_reg_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_user_event` (`user_id`, `event_id`),
    INDEX `idx_reg_date` (`registered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Audit Logs Table (Full Traceability & System Auditing)
CREATE TABLE `audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `user_name` VARCHAR(100) NOT NULL,
    `action` VARCHAR(100) NOT NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_action` (`action`),
    INDEX `idx_audit_created` (`created_at`),
    CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================
-- SEED DATA
-- Default Passwords:
--   admin@example.com  -> admin123  (Role: admin)
--   editor@example.com -> editor123 (Role: editor)
--   viewer@example.com -> viewer123 (Role: viewer)
-- ====================================================================

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `status`, `created_at`) VALUES
(1, 'Admin Officer', 'admin@example.com', '$2y$10$YRVD9NcdKockdi6GpcbNHu1l2DXsRpNCnNp91dABOkTuBp.BL.C2S', 'admin', 'active', '2026-06-10 10:00:00'),
(2, 'Devika Sharma', 'editor@example.com', '$2y$10$V5Yg1DN7uLwxreHCyW/yKOR.au2eaIAI4yBi4AhHJi.FScUWIewHK', 'editor', 'active', '2026-06-12 11:30:00'),
(3, 'Rahul Verma', 'viewer@example.com', '$2y$10$y7.6Q8ohTvL91g4LaOAYsub9ALJZO7/YSj/ijm.lvIUo7SQhMS/Gm', 'viewer', 'active', '2026-07-05 14:15:00'),
(4, 'Sneha Patel', 'sneha@example.com', '$2y$10$y7.6Q8ohTvL91g4LaOAYsub9ALJZO7/YSj/ijm.lvIUo7SQhMS/Gm', 'viewer', 'active', '2026-08-01 09:20:00'),
(5, 'Vikram Joshi (Inactive)', 'vikram@example.com', '$2y$10$y7.6Q8ohTvL91g4LaOAYsub9ALJZO7/YSj/ijm.lvIUo7SQhMS/Gm', 'editor', 'inactive', '2026-08-15 16:45:00'),
(6, 'Ananya Roy', 'ananya@example.com', '$2y$10$y7.6Q8ohTvL91g4LaOAYsub9ALJZO7/YSj/ijm.lvIUo7SQhMS/Gm', 'viewer', 'active', '2026-09-02 12:10:00'),
(7, 'Rohan Mehta', 'rohan@example.com', '$2y$10$y7.6Q8ohTvL91g4LaOAYsub9ALJZO7/YSj/ijm.lvIUo7SQhMS/Gm', 'viewer', 'active', '2026-10-01 10:05:00');

-- Students Seed Data
INSERT INTO `students` (`id`, `name`, `email`, `course`, `semester`, `created_at`) VALUES
(1, 'Aarav Patel', 'aarav.patel@student.edu', 'Computer Engineering', 5, '2026-07-10 09:00:00'),
(2, 'Diya Shah', 'diya.shah@student.edu', 'Information Technology', 4, '2026-07-11 10:15:00'),
(3, 'Rohan Verma', 'rohan.v@student.edu', 'Computer Engineering', 5, '2026-08-02 11:20:00'),
(4, 'Pooja Trivedi', 'pooja.t@student.edu', 'Artificial Intelligence', 3, '2026-08-14 13:40:00'),
(5, 'Karan Singhania', 'karan.s@student.edu', 'Data Science', 6, '2026-09-01 15:00:00'),
(6, 'Isha Desai', 'isha.d@student.edu', 'Information Technology', 3, '2026-09-15 16:30:00');

-- Events Seed Data
INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `created_at`) VALUES
(1, 'Annual Tech Symposium 2026', 'Inter-college technical festival featuring hackathons and workshops.', '2026-11-15', '2026-06-15 10:00:00'),
(2, 'AI & Cloud Bootcamp', 'Hands-on training session on deploying containerized cloud services.', '2026-11-28', '2026-07-01 11:30:00'),
(3, 'National Hackathon Genesis', '36-hour continuous software engineering & innovation hackathon.', '2026-12-05', '2026-08-10 09:15:00'),
(4, 'Cybersecurity Summit', 'Keynote addresses on zero-trust architectures and ethical hacking.', '2026-12-20', '2026-09-05 14:00:00'),
(5, 'Web Engineering Showcase', 'Final semester capstone web application demonstration.', '2027-01-10', '2026-10-01 15:30:00');

-- Event Registrations (Spanning multiple months for Analytics Chart)
INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `registered_at`) VALUES
(1, 3, 1, '2026-07-12 10:30:00'),
(2, 4, 1, '2026-07-20 14:10:00'),
(3, 3, 2, '2026-08-05 11:00:00'),
(4, 6, 2, '2026-08-18 16:20:00'),
(5, 7, 2, '2026-08-25 09:45:00'),
(6, 4, 3, '2026-09-04 13:15:00'),
(7, 6, 3, '2026-09-12 15:30:00'),
(8, 7, 3, '2026-09-22 17:00:00'),
(9, 3, 4, '2026-10-03 10:20:00'),
(10, 4, 4, '2026-10-15 12:40:00'),
(11, 6, 4, '2026-10-22 14:50:00'),
(12, 7, 5, '2026-10-26 18:00:00');

-- Initial Audit Logs (Demonstrating Traceability)
INSERT INTO `audit_logs` (`id`, `user_id`, `user_name`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'Admin Officer', 'SYSTEM_INITIALIZATION', 'Database seeded with default schema and records', '127.0.0.1', '2026-10-20 09:00:00'),
(2, 1, 'Admin Officer', 'LOGIN_SUCCESS', 'Admin logged into administration portal', '127.0.0.1', '2026-10-26 09:15:00'),
(3, 1, 'Admin Officer', 'USER_STATUS_TOGGLED', 'User ID 5 (Vikram Joshi) set to inactive', '127.0.0.1', '2026-10-26 09:22:00'),
(4, 2, 'Devika Sharma', 'LOGIN_SUCCESS', 'Editor signed in successfully', '127.0.0.1', '2026-10-26 10:00:00'),
(5, 2, 'Devika Sharma', 'STUDENT_ADDED', 'New student enrolled: Isha Desai (IT, Sem 3)', '127.0.0.1', '2026-10-26 10:14:00'),
(6, 2, 'Devika Sharma', 'EVENT_CREATED', 'New event created: Web Engineering Showcase', '127.0.0.1', '2026-10-26 10:30:00'),
(7, 3, 'Rahul Verma', 'UNAUTHORIZED_ACCESS_ATTEMPT', 'User with role viewer attempted to access /admin/users.php', '127.0.0.1', '2026-10-26 11:05:00');
