-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2025 at 06:54 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `faculty_staff_office`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `email`, `created_at`) VALUES
(2, 'Jumaw', '$2y$10$CI1U18Pjnuwe64fwVYqGXeUGWjGTT4oMHqVsygv5s.6ukYpfwpqty', 'admin1@faculty.edu', '2025-03-29 05:19:49'),
(3, 'admin2', 'secure456', 'admin2@faculty.edu', '2025-03-29 05:19:49'),
(4, 'superadmin', 'admin789', 'superadmin@faculty.edu', '2025-03-29 05:19:49');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_lists`
--

CREATE TABLE `faculty_lists` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `academic_rank` varchar(100) DEFAULT NULL,
  `institute` varchar(150) DEFAULT NULL,
  `education` text DEFAULT NULL,
  `research_title` text DEFAULT NULL,
  `research_link` varchar(255) DEFAULT NULL,
  `google_scholar_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_lists`
--

INSERT INTO `faculty_lists` (`id`, `name`, `photo_path`, `designation`, `email`, `academic_rank`, `institute`, `education`, `research_title`, `research_link`, `google_scholar_link`, `created_at`, `updated_at`) VALUES
(16, 'Greendee Roper Panogalon', 'uploads/photos/67fce5ab09ef9_1744627115.jpg', 'Designation 1', 'greendeeroperpanogalon@gmail.com', 'Professor IV', 'Institute of Computing', '[\"N\\/A\"]', '[\"Title 1\"]', '[\"https:\\/\\/drive.google.com\\/drive\\/u\\/0\\/my-drive\"]', 'https://drive.google.com/drive/u/0/my-drive', '2025-04-14 10:38:35', '2025-04-14 10:38:35');

-- --------------------------------------------------------

--
-- Table structure for table `office_lists`
--

CREATE TABLE `office_lists` (
  `id` int(11) NOT NULL,
  `office_name` varchar(100) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `head` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_lists`
--

CREATE TABLE `staff_lists` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `academic_rank` varchar(100) DEFAULT NULL,
  `institute` varchar(150) DEFAULT NULL,
  `education` text DEFAULT NULL,
  `research_title` text DEFAULT NULL,
  `research_link` varchar(255) DEFAULT NULL,
  `google_scholar_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_lists`
--

INSERT INTO `staff_lists` (`id`, `name`, `photo_path`, `designation`, `email`, `academic_rank`, `institute`, `education`, `research_title`, `research_link`, `google_scholar_link`, `created_at`, `updated_at`) VALUES
(6, 'Meriam Apatan Cerna', 'uploads/photos/67fce5ed75612_1744627181.jpg', 'Designation 1', 'merdsbakeshop@gmail.com', 'Professor', 'Institute of Leadership, Entrepreneurship and Good Governance', '[\"N\\/A\"]', '[\"Title 1\"]', '[\"https:\\/\\/drive.google.com\\/drive\\/u\\/0\\/my-drive\"]', 'https://drive.google.com/drive/u/0/my-drive', '2025-04-14 10:39:41', '2025-04-14 10:39:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `faculty_lists`
--
ALTER TABLE `faculty_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office_lists`
--
ALTER TABLE `office_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_lists`
--
ALTER TABLE `staff_lists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculty_lists`
--
ALTER TABLE `faculty_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `office_lists`
--
ALTER TABLE `office_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `staff_lists`
--
ALTER TABLE `staff_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- -- Create the database
-- CREATE DATABASE IF NOT EXISTS faculty_staff_office;
-- USE faculty_staff_office;

-- -- Create admins table
-- CREATE TABLE `admins` (
--   `admin_id` int(11) NOT NULL AUTO_INCREMENT,
--   `username` varchar(50) NOT NULL,
--   `password` varchar(255) NOT NULL,
--   `email` varchar(100) NOT NULL,
--   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
--   PRIMARY KEY (`admin_id`),
--   UNIQUE KEY `username` (`username`),
--   UNIQUE KEY `email` (`email`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -- Insert data into admins table
-- INSERT INTO `admins` (`username`, `password`, `email`, `created_at`) VALUES
-- ('Jumaw', '$2y$10$CI1U18Pjnuwe64fwVYqGXeUGWjGTT4oMHqVsygv5s.6ukYpfwpqty', 'admin1@faculty.edu', '2025-03-29 05:19:49'),
-- ('admin2', 'secure456', 'admin2@faculty.edu', '2025-03-29 05:19:49'),
-- ('superadmin', 'admin789', 'superadmin@faculty.edu', '2025-03-29 05:19:49');

-- -- Create faculty_lists table
-- CREATE TABLE `faculty_lists` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `name` varchar(100) NOT NULL,
--   `photo_path` varchar(255) DEFAULT NULL,
--   `designation` varchar(100) DEFAULT NULL,
--   `email` varchar(100) DEFAULT NULL,
--   `academic_rank` varchar(100) DEFAULT NULL,
--   `institute` varchar(150) DEFAULT NULL,
--   `education` text DEFAULT NULL,
--   `research_title` text DEFAULT NULL,
--   `research_link` varchar(255) DEFAULT NULL,
--   `google_scholar_link` varchar(255) DEFAULT NULL,
--   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
--   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -- Insert data into faculty_lists table
-- INSERT INTO `faculty_lists` (`name`, `photo_path`, `designation`, `email`, `academic_rank`, `institute`, `education`, `research_title`, `research_link`, `google_scholar_link`, `created_at`, `updated_at`) VALUES
-- ('Greendee Roper Panogalon', 'uploads/photos/67fce5ab09ef9_1744627115.jpg', 'Designation 1', 'greendeeroperpanogalon@gmail.com', 'Professor IV', 'Institute of Computing', '[\"N\\/A\"]', '[\"Title 1\"]', '[\"https:\\/\\/drive.google.com\\/drive\\/u\\/0\\/my-drive\"]', 'https://drive.google.com/drive/u/0/my-drive', '2025-04-14 10:38:35', '2025-04-14 10:38:35');

-- -- Create office_lists table
-- CREATE TABLE `office_lists` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `office_name` varchar(100) DEFAULT NULL,
--   `about` text DEFAULT NULL,
--   `head` varchar(100) DEFAULT NULL,
--   `contact_number` varchar(20) DEFAULT NULL,
--   `email` varchar(100) DEFAULT NULL,
--   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
--   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -- Create staff_lists table
-- CREATE TABLE `staff_lists` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `name` varchar(100) NOT NULL,
--   `photo_path` varchar(255) DEFAULT NULL,
--   `designation` varchar(100) DEFAULT NULL,
--   `email` varchar(100) DEFAULT NULL,
--   `academic_rank` varchar(100) DEFAULT NULL,
--   `institute` varchar(150) DEFAULT NULL,
--   `education` text DEFAULT NULL,
--   `research_title` text DEFAULT NULL,
--   `research_link` varchar(255) DEFAULT NULL,
--   `google_scholar_link` varchar(255) DEFAULT NULL,
--   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
--   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -- Insert data into staff_lists table
-- INSERT INTO `staff_lists` (`name`, `photo_path`, `designation`, `email`, `academic_rank`, `institute`, `education`, `research_title`, `research_link`, `google_scholar_link`, `created_at`, `updated_at`) VALUES
-- ('Meriam Apatan Cerna', 'uploads/photos/67fce5ed75612_1744627181.jpg', 'Designation 1', 'merdsbakeshop@gmail.com', 'Professor', 'Institute of Leadership, Entrepreneurship and Good Governance', '[\"N\\/A\"]', '[\"Title 1\"]', '[\"https:\\/\\/drive.google.com\\/drive\\/u\\/0\\/my-drive\"]', 'https://drive.google.com/drive/u/0/my-drive', '2025-04-14 10:39:41', '2025-04-14 10:39:41');