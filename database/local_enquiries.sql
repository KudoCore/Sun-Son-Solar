-- Run once in phpMyAdmin. Re-running preserves existing enquiries and accounts.
USE sun_son_solar_local;
CREATE TABLE IF NOT EXISTS enquiries (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    reference VARCHAR(19) NOT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(254) NOT NULL,
    phone VARCHAR(30) NULL,
    interest VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    consent_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY enquiries_reference_unique (reference),
    KEY enquiries_created_at_index (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
