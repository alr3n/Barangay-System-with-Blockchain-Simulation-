-- ============================================================
-- Barangay San Jose Integrated System
-- Database: barangay_db
-- MySQL 8.0+ compatible SQL dump
-- Run this ONLY if you prefer SQL import over php artisan migrate
-- ============================================================

CREATE DATABASE IF NOT EXISTS `barangay_db`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `barangay_db`;

-- -----------------------------------------------
-- Table: users
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(150)    NOT NULL,
    `email`          VARCHAR(150)    NOT NULL UNIQUE,
    `password`       VARCHAR(255)    NOT NULL,
    `role`           ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    `is_active`      TINYINT(1)      NOT NULL DEFAULT 1,
    `remember_token` VARCHAR(100)    DEFAULT NULL,
    `created_at`     TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------
-- Table: households
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS `households` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `household_code` VARCHAR(30)     NOT NULL UNIQUE,
    `address`        VARCHAR(255)    NOT NULL,
    `purok`          VARCHAR(100)    DEFAULT NULL,
    `street`         VARCHAR(100)    DEFAULT NULL,
    `house_type`     ENUM('owned','rented','shared','other') NOT NULL DEFAULT 'owned',
    `created_at`     TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------
-- Table: residents
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS `residents` (
    `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `resident_code`     VARCHAR(30)     NOT NULL UNIQUE,
    `first_name`        VARCHAR(100)    NOT NULL,
    `middle_name`       VARCHAR(100)    DEFAULT NULL,
    `last_name`         VARCHAR(100)    NOT NULL,
    `birthdate`         DATE            NOT NULL,
    `gender`            ENUM('male','female') NOT NULL,
    `civil_status`      ENUM('single','married','widowed','separated','annulled') NOT NULL DEFAULT 'single',
    `address`           VARCHAR(255)    NOT NULL,
    `contact_number`    VARCHAR(20)     DEFAULT NULL,
    `occupation`        VARCHAR(100)    DEFAULT NULL,
    `household_id`      BIGINT UNSIGNED DEFAULT NULL,
    `is_household_head` TINYINT(1)      NOT NULL DEFAULT 0,
    `resident_status`   ENUM('active','inactive','deceased','transferred') NOT NULL DEFAULT 'active',
    `profile_photo`     VARCHAR(255)    DEFAULT NULL,
    `remarks`           TEXT            DEFAULT NULL,
    `created_at`        TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`        TIMESTAMP       NULL DEFAULT NULL,
    `deleted_at`        TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_residents_household`
        FOREIGN KEY (`household_id`) REFERENCES `households`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------
-- Table: clearances
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS `clearances` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `control_number` VARCHAR(30)     NOT NULL UNIQUE,
    `resident_id`    BIGINT UNSIGNED NOT NULL,
    `issued_by`      BIGINT UNSIGNED NOT NULL,
    `document_type`  ENUM('barangay_clearance','residency_certificate','indigency_certificate') NOT NULL,
    `purpose`        VARCHAR(255)    NOT NULL,
    `hash_code`      VARCHAR(64)     NOT NULL UNIQUE,
    `qr_code_path`   VARCHAR(255)    DEFAULT NULL,
    `status`         ENUM('active','revoked') NOT NULL DEFAULT 'active',
    `issued_date`    DATE            NOT NULL,
    `expiry_date`    DATE            DEFAULT NULL,
    `fee`            DECIMAL(8,2)    NOT NULL DEFAULT 0.00,
    `notes`          TEXT            DEFAULT NULL,
    `created_at`     TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_clearances_resident`
        FOREIGN KEY (`resident_id`) REFERENCES `residents`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_clearances_user`
        FOREIGN KEY (`issued_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------
-- Table: verification_records
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS `verification_records` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hash_queried` VARCHAR(64)     NOT NULL,
    `result`       ENUM('verified','invalid','tampered') NOT NULL,
    `ip_address`   VARCHAR(45)     DEFAULT NULL,
    `user_agent`   VARCHAR(255)    DEFAULT NULL,
    `clearance_id` BIGINT UNSIGNED DEFAULT NULL,
    `created_at`   TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_verif_clearance`
        FOREIGN KEY (`clearance_id`) REFERENCES `clearances`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------
-- Table: blotters
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS `blotters` (
    `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `blotter_number`       VARCHAR(30)     NOT NULL UNIQUE,
    `complainant_name`     VARCHAR(150)    NOT NULL,
    `complainant_address`  VARCHAR(255)    NOT NULL,
    `complainant_contact`  VARCHAR(20)     DEFAULT NULL,
    `respondent_name`      VARCHAR(150)    NOT NULL,
    `respondent_address`   VARCHAR(255)    NOT NULL,
    `respondent_contact`   VARCHAR(20)     DEFAULT NULL,
    `incident_date`        DATE            NOT NULL,
    `incident_time`        TIME            DEFAULT NULL,
    `incident_location`    VARCHAR(255)    NOT NULL,
    `incident_type`        VARCHAR(100)    NOT NULL,
    `incident_details`     TEXT            NOT NULL,
    `status`               ENUM('pending','ongoing','resolved') NOT NULL DEFAULT 'pending',
    `resolution_notes`     TEXT            DEFAULT NULL,
    `resolved_date`        DATE            DEFAULT NULL,
    `handled_by`           BIGINT UNSIGNED DEFAULT NULL,
    `created_at`           TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`           TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_blotter_handler`
        FOREIGN KEY (`handled_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------
-- Table: activity_logs
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     BIGINT UNSIGNED DEFAULT NULL,
    `action`      VARCHAR(50)     NOT NULL,
    `module`      VARCHAR(50)     NOT NULL,
    `description` TEXT            NOT NULL,
    `ip_address`  VARCHAR(45)     DEFAULT NULL,
    `created_at`  TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_actlog_user`
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------
-- Migrations tracker (required by Laravel)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS `migrations` (
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch`     INT          NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SAMPLE DATA
-- ============================================================

-- Users (passwords are bcrypt of admin123 / staff123)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
('System Administrator', 'admin@barangay.gov.ph', '$2y$12$YUqidxIMWMvBKB1GVQS7aOgKx6IQRWnrYH1GJGmyVH6YM5EiyZqEi', 'admin', 1, NOW(), NOW()),
('Maria Santos',         'staff@barangay.gov.ph', '$2y$12$P7KLbz1s3.g/D3LUcqJDheJqaMfOZ6xpPcQUlNMYzAp7P89G8sEJG', 'staff', 1, NOW(), NOW()),
('Juan Dela Cruz',       'juan@barangay.gov.ph',  '$2y$12$P7KLbz1s3.g/D3LUcqJDheJqaMfOZ6xpPcQUlNMYzAp7P89G8sEJG', 'staff', 1, NOW(), NOW());

-- NOTE: The passwords above may not match because bcrypt is random-salted.
-- Use `php artisan db:seed` instead for correct hashed passwords.
-- This SQL is provided for schema reference only.
