-- Import this file through phpMyAdmin. Existing records are not removed.
-- The application reads its connection settings from .env.
CREATE DATABASE IF NOT EXISTS sun_son_solar_local
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sun_son_solar_local;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    birthday DATE NULL,
    gender VARCHAR(30) NULL,
    email VARCHAR(254) NOT NULL,
    phone VARCHAR(30) NULL,
    address VARCHAR(300) NULL,
    username VARCHAR(80) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    department VARCHAR(100) NOT NULL,
    role ENUM('employee', 'ceo', 'it_head') NOT NULL DEFAULT 'employee',
    approved TINYINT(1) NOT NULL DEFAULT 0,
    consent_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB;

-- Shared registration/login limits. Store hashed limit keys, not raw IP addresses.
CREATE TABLE IF NOT EXISTS registration_limits (
    client_hash CHAR(64) NOT NULL,
    attempts INT UNSIGNED NOT NULL,
    expires_at BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (client_hash),
    KEY idx_limits_expiry (expires_at)
) ENGINE=InnoDB;

-- Follow-up interview additions
-- Import once through phpMyAdmin with your existing database selected.
-- Safe to re-import. Existing users, passwords, approval and roles are preserved.
USE sun_son_solar_local;

CREATE TABLE IF NOT EXISTS departments (
    id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_departments_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO departments (name) VALUES
('Administration'), ('IT'), ('Dispatch'), ('Accounting'),
('HR'), ('Marketing'), ('Sales'), ('Customer Service')
ON DUPLICATE KEY UPDATE name = VALUES(name);

SET @column_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'department_id');
SET @migration_sql = IF(@column_exists = 0,
 'ALTER TABLE users ADD COLUMN department_id TINYINT UNSIGNED NULL', 'SELECT 1');
PREPARE migration_statement FROM @migration_sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;

SET @column_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'phone_type');
SET @migration_sql = IF(@column_exists = 0,
 'ALTER TABLE users ADD COLUMN phone_type VARCHAR(16) NOT NULL DEFAULT ''unspecified''', 'SELECT 1');
PREPARE migration_statement FROM @migration_sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;

-- Map known department names; keep unrecognized legacy text for manual review.
UPDATE users AS u INNER JOIN departments AS d
 ON LOWER(TRIM(u.department)) = LOWER(d.name)
 SET u.department_id = d.id WHERE u.department_id IS NULL;

SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
 WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND CONSTRAINT_NAME = 'fk_users_department');
SET @migration_sql = IF(@constraint_exists = 0,
 'ALTER TABLE users ADD CONSTRAINT fk_users_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE RESTRICT ON UPDATE RESTRICT', 'SELECT 1');
PREPARE migration_statement FROM @migration_sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;
