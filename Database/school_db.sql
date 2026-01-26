-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2026 at 08:33 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `section` varchar(10) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `section`, `department_id`, `created_at`) VALUES
(1, 'Grade 9', 'A', 1, '2026-01-26 11:45:54'),
(2, 'Grade 9', 'B', 2, '2026-01-26 11:45:54'),
(3, 'Grade 10', 'A', 1, '2026-01-26 11:45:54'),
(4, 'Grade 10', 'B', 3, '2026-01-26 11:45:54'),
(5, 'Grade 11', 'A', 2, '2026-01-26 11:45:54'),
(6, 'Grade 11', 'B', 4, '2026-01-26 11:45:54'),
(7, 'Grade 12', 'A', 1, '2026-01-26 11:45:54'),
(8, 'Grade 12', 'B', 5, '2026-01-26 11:45:54'),
(9, 'Grade 12', 'C', 3, '2026-01-26 11:45:54'),
(10, 'Grade 10', 'C', 4, '2026-01-26 11:45:54');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(5, 'Biology'),
(4, 'Chemistry'),
(1, 'Computer Science'),
(9, 'Economics'),
(6, 'English'),
(8, 'Geography'),
(7, 'History'),
(2, 'Mathematics'),
(10, 'Physical Education'),
(3, 'Physics');

-- --------------------------------------------------------

--
-- Table structure for table `fee_payments`
--

CREATE TABLE `fee_payments` (
  `payment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `fee_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_method` enum('Cash','Card','Online') DEFAULT NULL,
  `receipt_no` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fee_payments`
--

INSERT INTO `fee_payments` (`payment_id`, `student_id`, `fee_id`, `amount_paid`, `payment_date`, `payment_method`, `receipt_no`) VALUES
(2, 5, 1, 320.00, '2026-01-27', 'Card', 'fds');

-- --------------------------------------------------------

--
-- Table structure for table `fee_structure`
--

CREATE TABLE `fee_structure` (
  `fee_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fee_structure`
--

INSERT INTO `fee_structure` (`fee_id`, `subject_id`, `amount`, `description`) VALUES
(1, 3, 45343.00, 'sa');

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `enrollment_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subjects`
--

INSERT INTO `student_subjects` (`id`, `student_id`, `subject_id`, `enrollment_date`) VALUES
(2, 5, 10, '2026-01-26');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `class_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `department_id` int(11) DEFAULT NULL,
  `credit_hours` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `class_id`, `created_at`, `department_id`, `credit_hours`) VALUES
(1, 'Mathematics', 1, '2026-01-26 11:46:33', 2, 4),
(2, 'Physics', 2, '2026-01-26 11:46:33', 3, 3),
(3, 'Chemistry', 3, '2026-01-26 11:46:33', 4, 3),
(4, 'Biology', 4, '2026-01-26 11:46:33', 5, 3),
(5, 'English', 5, '2026-01-26 11:46:33', 6, 2),
(6, 'History', 6, '2026-01-26 11:46:33', 7, 2),
(7, 'Geography', 7, '2026-01-26 11:46:33', 8, 2),
(8, 'Economics', 8, '2026-01-26 11:46:33', 9, 3),
(9, 'Computer Science', 9, '2026-01-26 11:46:33', 1, 4),
(10, 'Physical Education', 10, '2026-01-26 11:46:33', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subjects`
--

CREATE TABLE `teacher_subjects` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `assigned_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_subjects`
--

INSERT INTO `teacher_subjects` (`id`, `teacher_id`, `subject_id`, `assigned_date`) VALUES
(1, 1, 3, '2026-01-26');

-- --------------------------------------------------------

--
-- Table structure for table `timetables`
--

CREATE TABLE `timetables` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `day_of_week` enum('Mon','Tue','Wed','Thu','Fri','Sat') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetables`
--

INSERT INTO `timetables` (`id`, `class_id`, `subject_id`, `teacher_id`, `day_of_week`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(1, 3, 4, 1, 'Tue', '12:29:00', '13:29:00', '2026-01-26 12:29:29', '2026-01-26 12:31:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `usertype` enum('Teacher','Student') NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `dob` date NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `usertype`, `first_name`, `last_name`, `email`, `password_hash`, `gender`, `dob`, `contact_number`, `address`, `profile_picture`, `class_id`, `created_at`, `updated_at`) VALUES
(1, 'Teacher', 'Alice', 'Johnson', 'alice.johnson@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'Female', '1985-03-12', '1234567890', '123 Main St', 'images/logo.png', NULL, '2026-01-26 11:49:36', '2026-01-26 11:49:36'),
(2, 'Teacher', 'Bob', 'Smith', 'bob.smith@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'Male', '1978-07-22', '1234567891', '456 Oak St', 'images/logo.png', NULL, '2026-01-26 11:49:36', '2026-01-26 11:49:36'),
(3, 'Teacher', 'Carol', 'Lee', 'carol.lee@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'Female', '1982-11-05', '1234567892', '789 Pine St', 'images/logo.png', NULL, '2026-01-26 11:49:36', '2026-01-26 11:49:36'),
(4, 'Student', 'David', 'Brown', 'david.brown@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'Male', '2005-04-15', '1234567893', '321 Elm St', 'images/logo.png', 1, '2026-01-26 11:49:36', '2026-01-26 11:49:36'),
(5, 'Student', 'Emma', 'Davis', 'emma.davis@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'Female', '2006-06-20', '1234567894', '654 Maple St', 'images/logo.png', 2, '2026-01-26 11:49:36', '2026-01-26 11:49:36'),
(6, 'Student', 'Frank', 'Miller', 'frank.miller@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'Male', '2005-12-03', '1234567895', '987 Cedar St', 'images/logo.png', 3, '2026-01-26 11:49:36', '2026-01-26 11:49:36'),
(7, 'Student', 'Grace', 'Wilson', 'grace.wilson@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'Female', '2006-09-18', '1234567896', '159 Birch St', 'images/logo.png', 4, '2026-01-26 11:49:36', '2026-01-26 11:49:36'),
(8, 'Student', 'Henry', 'Moore', 'henry.moore@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'Male', '2005-02-28', '1234567897', '753 Walnut St', 'images/logo.png', 5, '2026-01-26 11:49:36', '2026-01-26 11:49:36'),
(9, 'Student', 'Isabella', 'Taylor', 'isabella.taylor@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'Female', '2006-01-12', '1234567898', '852 Spruce St', 'images/logo.png', 6, '2026-01-26 11:49:36', '2026-01-26 11:49:36'),
(10, 'Student', 'Jack', 'Anderson', 'jack.anderson@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'Male', '2005-08-30', '1234567899', '951 Cherry St', 'images/logo.png', 7, '2026-01-26 11:49:36', '2026-01-26 11:49:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `fee_payments`
--
ALTER TABLE `fee_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `receipt_no` (`receipt_no`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `fee_id` (`fee_id`);

--
-- Indexes for table `fee_structure`
--
ALTER TABLE `fee_structure`
  ADD PRIMARY KEY (`fee_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `fk_subject_department` (`department_id`);

--
-- Indexes for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `timetables`
--
ALTER TABLE `timetables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_schedule` (`class_id`,`day_of_week`,`start_time`,`end_time`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `class_id` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fee_payments`
--
ALTER TABLE `fee_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fee_structure`
--
ALTER TABLE `fee_structure`
  MODIFY `fee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `timetables`
--
ALTER TABLE `timetables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fee_payments`
--
ALTER TABLE `fee_payments`
  ADD CONSTRAINT `fee_payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_payments_ibfk_2` FOREIGN KEY (`fee_id`) REFERENCES `fee_structure` (`fee_id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_structure`
--
ALTER TABLE `fee_structure`
  ADD CONSTRAINT `fee_structure_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE SET NULL;

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `fk_subject_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD CONSTRAINT `teacher_subjects_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;

--
-- Constraints for table `timetables`
--
ALTER TABLE `timetables`
  ADD CONSTRAINT `timetables_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
