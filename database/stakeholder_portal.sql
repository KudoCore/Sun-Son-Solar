-- Stakeholder portal upgrade. Run after sun_son_solar.sql.
-- It preserves existing records and repairs the documented starter-account credentials.
USE sun_son_solar_local;

ALTER TABLE users MODIFY department VARCHAR(100) NULL,
                  MODIFY role VARCHAR(24) NOT NULL DEFAULT 'customer';
SET @column_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'is_active');
SET @migration_sql = IF(@column_exists = 0,
 'ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1', 'SELECT 1');
PREPARE migration_statement FROM @migration_sql;
EXECUTE migration_statement;
DEALLOCATE PREPARE migration_statement;

UPDATE users SET role = 'admin' WHERE role IN ('ceo', 'it_head');
UPDATE users SET role = 'employee' WHERE role NOT IN ('customer','employee','technician','dispatcher','admin','hr');
UPDATE users SET is_active = 0 WHERE role <> 'customer' AND approved = 0;

INSERT INTO departments (name) VALUES
('Administration'), ('IT'), ('Dispatch'), ('Accounting'), ('HR'),
('Marketing'), ('Sales'), ('Customer Service')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Default credentials are stored only as bcrypt hashes; non-seed user passwords are preserved.
INSERT INTO users
 (first_name, middle_name, last_name, birthday, gender, email, phone, address,
  username, password_hash, department, department_id, phone_type, role, approved,
  is_active, consent_at, created_at)
SELECT 'Katherine', 'Olap', 'Sinagaraw', '1990-07-01', NULL,
       'katherine.sinagaraw@sunsonsolar.com', '09291230983', NULL,
       'KittyKat16', '$2y$12$lQZR0fOgW6cHunlaXXvE5u9kfoEfvh3X88pm29aIdWyFKA7vPgKPa',
       'Administration', d.id, 'personal', 'admin', 1, 1, UTC_TIMESTAMP(), UTC_TIMESTAMP()
FROM departments d WHERE d.name = 'Administration'
  AND NOT EXISTS (SELECT 1 FROM users WHERE username = 'KittyKat16' OR email = 'katherine.sinagaraw@sunsonsolar.com');

INSERT INTO users
 (first_name, middle_name, last_name, birthday, gender, email, phone, address,
  username, password_hash, department, department_id, phone_type, role, approved,
  is_active, consent_at, created_at)
SELECT 'Sol', NULL, 'Solis', '1967-01-08', NULL, 'sol.solis', '09291230983', NULL,
       'admin', '$2y$12$G5rDsemCaphBeFBaFnE9yuFQPy28s9RMF/kWr8ZbVkHYVJ2XsC7U2',
       'IT', d.id, 'personal', 'admin', 1, 1, UTC_TIMESTAMP(), UTC_TIMESTAMP()
FROM departments d WHERE d.name = 'IT'
  AND NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin' OR email = 'sol.solis');

CREATE TABLE IF NOT EXISTS service_categories (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  description VARCHAR(500) NOT NULL DEFAULT '',
  base_price DECIMAL(12,2) NULL,
  duration_minutes INT UNSIGNED NOT NULL DEFAULT 60,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id), UNIQUE KEY uq_service_category_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO service_categories (name, description, base_price) VALUES
('Consultation', 'Solar needs assessment and project consultation.', NULL),
('Installation', 'Solar panel and equipment installation service.', NULL),
('Maintenance', 'Inspection, cleaning and maintenance service.', NULL)
ON DUPLICATE KEY UPDATE name = VALUES(name);

CREATE TABLE IF NOT EXISTS service_bookings (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_id BIGINT UNSIGNED NOT NULL,
  category_id BIGINT UNSIGNED NOT NULL,
  technician_id BIGINT UNSIGNED NULL,
  requested_at DATETIME NULL,
  scheduled_start DATETIME NULL,
  scheduled_end DATETIME NULL,
  service_address VARCHAR(300) NOT NULL,
  customer_notes TEXT NULL,
  status VARCHAR(24) NOT NULL DEFAULT 'requested',
  staff_notes TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id), KEY idx_booking_customer (customer_id),
  KEY idx_booking_technician_time (technician_id, scheduled_start),
  KEY idx_booking_status_time (status, scheduled_start),
  CONSTRAINT fk_booking_customer FOREIGN KEY (customer_id) REFERENCES users(id),
  CONSTRAINT fk_booking_category FOREIGN KEY (category_id) REFERENCES service_categories(id),
  CONSTRAINT fk_booking_technician FOREIGN KEY (technician_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS notifications (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NULL,
  title VARCHAR(160) NOT NULL,
  message VARCHAR(1000) NOT NULL,
  kind VARCHAR(32) NOT NULL DEFAULT 'update',
  read_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id), KEY idx_notification_user (user_id, created_at),
  CONSTRAINT fk_notification_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS service_payments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  booking_id BIGINT UNSIGNED NOT NULL,
  customer_id BIGINT UNSIGNED NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  status VARCHAR(24) NOT NULL DEFAULT 'pending',
  reference VARCHAR(80) NULL,
  paid_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id), KEY idx_payment_customer (customer_id, created_at),
  CONSTRAINT fk_payment_booking FOREIGN KEY (booking_id) REFERENCES service_bookings(id),
  CONSTRAINT fk_payment_customer FOREIGN KEY (customer_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS support_tickets (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  subject VARCHAR(160) NOT NULL,
  message TEXT NOT NULL,
  status VARCHAR(24) NOT NULL DEFAULT 'open',
  staff_reply TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id), KEY idx_ticket_user (user_id, created_at), KEY idx_ticket_status (status),
  CONSTRAINT fk_ticket_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS technician_checkins (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  booking_id BIGINT UNSIGNED NOT NULL,
  technician_id BIGINT UNSIGNED NOT NULL,
  checked_in_at DATETIME NOT NULL,
  latitude DECIMAL(10,7) NOT NULL,
  longitude DECIMAL(10,7) NOT NULL,
  PRIMARY KEY (id), KEY idx_checkin_booking (booking_id, checked_in_at),
  CONSTRAINT fk_checkin_booking FOREIGN KEY (booking_id) REFERENCES service_bookings(id),
  CONSTRAINT fk_checkin_technician FOREIGN KEY (technician_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS technician_reports (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  booking_id BIGINT UNSIGNED NOT NULL,
  technician_id BIGINT UNSIGNED NOT NULL,
  report TEXT NOT NULL,
  status VARCHAR(24) NOT NULL DEFAULT 'new',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id), KEY idx_report_status (status, created_at),
  CONSTRAINT fk_report_booking FOREIGN KEY (booking_id) REFERENCES service_bookings(id),
  CONSTRAINT fk_report_technician FOREIGN KEY (technician_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS announcements (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(160) NOT NULL,
  body TEXT NOT NULL,
  audience VARCHAR(24) NOT NULL DEFAULT 'all_staff',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_by BIGINT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id), KEY idx_announcement_audience (audience, is_active, created_at),
  CONSTRAINT fk_announcement_author FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_preferences (
  user_id BIGINT UNSIGNED NOT NULL,
  promotions TINYINT(1) NOT NULL DEFAULT 1,
  schedule_updates TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (user_id),
  CONSTRAINT fk_preferences_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS customer_testimonials (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  quote_text VARCHAR(1200) NOT NULL,
  display_name VARCHAR(120) NOT NULL,
  consent_to_publish TINYINT(1) NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id), KEY idx_testimonial_public (is_published, created_at),
  CONSTRAINT fk_testimonial_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS app_settings (
  setting_key VARCHAR(80) NOT NULL,
  setting_value TEXT NOT NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO app_settings (setting_key, setting_value) VALUES
('booking_enabled', '1'), ('default_booking_duration', '60')
ON DUPLICATE KEY UPDATE setting_key = VALUES(setting_key);
