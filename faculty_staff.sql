-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 03:43 AM
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
-- Database: `faculty_staff`
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

INSERT INTO `faculty_lists` (`id`, `name`, `photo_path`, `academic_rank`, `institute`, `education`, `research_title`, `research_link`, `google_scholar_link`, `created_at`, `updated_at`) VALUES
(24, 'Greendee Roper', '', 'Professor', 'Engineering', '[\"manila\",\"davao\"]', '[\"Title 1\",\"Title 2\",\"Title 3\"]', '[\"https:\\/\\/drive.google.com\\/file\\/d\\/1A2B3C4D5E6F7G8H9I\\/view?usp=sharing\",\"https:\\/\\/drive.google.com\\/file\\/d\\/1A2B3C4D5E6F7G8H9I\\/view?usp=sharing\",\"https:\\/\\/drive.google.com\\/file\\/d\\/1A2B3C4D5E6F7G8H9I\\/view?usp=sharing\"]', 'https://drive.google.com/file/d/1A2B3C4D5E6F7G8H9I/view?usp=sharing', '2025-04-06 14:02:17', '2025-04-06 16:31:11'),
(25, 'Meryam Cerna', 'uploads/photos/67f29a83f2813_1743952515.jpg', 'Admin', 'Engineering', '[\"manila\"]', '[\"2\",\"3223\"]', '[\"https:\\/\\/drive.google.com\\/file\\/u\\/0\\/d\\/1A2B3C4D5E6F7G8H9I\\/view?usp=sharing&pli=1\",\"https:\\/\\/drive.google.com\\/file\\/u\\/0\\/d\\/1A2B3C4D5E6F7G8H9I\\/view?usp=sharing&pli=1\"]', 'https://drive.google.com/file/u/0/d/1A2B3C4D5E6F7G8H9I/view?usp=sharing&pli=1', '2025-04-06 15:15:16', '2025-04-06 16:47:35'),
(26, 'Merde Panogalon', '', 'Admin', 'Engineering', '[\"Manila\",\"Davao\"]', '[\"Title 1\",\"Title 2\"]', '[\"https:\\/\\/drive.google.com\\/file\\/u\\/0\\/d\\/1A2B3C4D5E6F7G8H9I\\/view?usp=sharing&pli=1\",\"https:\\/\\/drive.google.com\\/file\\/u\\/0\\/d\\/1A2B3C4D5E6F7G8H9I\\/view?usp=sharing&pli=1\"]', 'https://drive.google.com/file/u/0/d/1A2B3C4D5E6F7G8H9I/view?usp=sharing&pli=1', '2025-04-06 16:20:19', '2025-04-06 16:42:57'),
(27, 'Digong Duterte', 'uploads/photos/67f2b032879cf_1743958066.png', 'Admin', 'Computing', '[\"Education 1\"]', '[\"Title 1\"]', '[\"https:\\/\\/drive.google.com\\/file\\/u\\/0\\/d\\/1A2B3C4D5E6F7G8H9I\\/view?usp=sharing&pli=1\"]', 'https://drive.google.com/file/u/0/d/1A2B3C4D5E6F7G8H9I/view?usp=sharing&pli=1', '2025-04-06 16:22:22', '2025-04-06 16:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `staff_lists`
--

CREATE TABLE `staff_lists` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `staff_lists`
--
ALTER TABLE `staff_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
