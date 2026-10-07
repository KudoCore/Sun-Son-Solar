-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2026 at 01:45 PM
-- Server version: 12.3.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sun_son_solar_local`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(4, 'Accounting'),
(1, 'Administration'),
(8, 'Customer Service'),
(3, 'Dispatch'),
(5, 'HR'),
(2, 'IT'),
(6, 'Marketing'),
(7, 'Sales');

-- --------------------------------------------------------

--
-- Table structure for table `registration_limits`
--

CREATE TABLE `registration_limits` (
  `client_hash` char(64) NOT NULL,
  `attempts` int(10) UNSIGNED NOT NULL,
  `expires_at` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registration_limits`
--

INSERT INTO `registration_limits` (`client_hash`, `attempts`, `expires_at`) VALUES
('48ecb537fe3774a025f547f3b91be6bf1430ea22ad5ac9e0cdaa62a87e2d717c', 1, 1791374232),
('4d44b5db05eb0a83e865ffc74518af8f109b8e202994407617dccbd1b71656bf', 1, 1791374232);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `username` varchar(80) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `department` varchar(100) NOT NULL,
  `role` enum('employee','ceo','it_head') NOT NULL DEFAULT 'employee',
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `consent_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `department_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `phone_type` varchar(16) NOT NULL DEFAULT 'unspecified'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `middle_name`, `birthday`, `gender`, `email`, `phone`, `address`, `username`, `password_hash`, `department`, `role`, `approved`, `consent_at`, `created_at`, `department_id`, `phone_type`) VALUES
(5, 'Kathrine', 'Sinagaraw', 'Olap', '1990-07-01', NULL, 'katherine.sinagaraw@sunsonsolar.com', NULL, NULL, 'KittyKat16', '$2y$12$0NcDTYOmkwjMZDOHRcbG0eWd5IR81EEpzMJJ3PS8uNaGMJjkaqKsW', 'Administration', 'ceo', 1, NULL, '2026-10-07 11:41:30', 1, 'company'),
(6, 'Sol', 'Solis', 'Sun', '1967-01-08', NULL, NULL, NULL, NULL, 'admin', '$2y$12$pZjOgYp7Pf1Ixr3cQQNdDepkmm37VowHC2bxq2RE7xKnty5XA7nTy', 'IT', 'it_head', 1, NULL, '2026-10-07 11:41:30', 2, 'company');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_departments_name` (`name`);

--
-- Indexes for table `registration_limits`
--
ALTER TABLE `registration_limits`
  ADD PRIMARY KEY (`client_hash`),
  ADD KEY `idx_limits_expiry` (`expires_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `fk_users_department` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
