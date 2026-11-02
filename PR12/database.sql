CREATE DATABASE IF NOT EXISTS event_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE event_management;

CREATE TABLE IF NOT EXISTS events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('Workshop','Conference','Festival','Sports','Webinar','Other') NOT NULL DEFAULT 'Other',
    event_date DATE NOT NULL,
    status ENUM('Open','Closed') NOT NULL DEFAULT 'Open',
    poster VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO events (title, description, category, event_date, status, poster) VALUES
('AI & Data Science Workshop', 'A hands-on introduction to practical AI and data science tools.', 'Workshop', '2026-11-12', 'Open', NULL),
('Campus Innovation Festival', 'A student showcase of creative projects, prototypes and ideas.', 'Festival', '2026-12-05', 'Open', NULL),
('Web Development Seminar', 'A seminar covering modern frontend and backend development.', 'Conference', '2026-09-20', 'Closed', NULL);
