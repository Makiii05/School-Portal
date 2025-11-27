-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 12:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `enrollment`
--

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `collection_id` mediumint(8) UNSIGNED NOT NULL,
  `or_number` varchar(10) DEFAULT NULL,
  `or_date` date DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `semester_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `cash` decimal(8,2) UNSIGNED DEFAULT 0.00,
  `gcash` decimal(8,2) UNSIGNED DEFAULT 0.00,
  `gcash_refno` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`collection_id`, `or_number`, `or_date`, `student_id`, `semester_id`, `cash`, `gcash`, `gcash_refno`) VALUES
(1, '1000', '2025-10-24', 17, 1, 1000.00, 0.00, ''),
(2, '1001', '2025-10-24', 17, 1, 1000.00, 0.00, ''),
(3, '1003', '2025-11-11', 16, 2, 0.00, 1200.00, '2344443'),
(4, '1004', '2025-11-11', 15, 1, 2000.00, 0.00, NULL),
(14, '1005', '2025-11-13', 17, 2, 7300.00, 0.00, ''),
(15, '1007', '2025-11-14', 17, 1, 4800.00, 0.00, ''),
(17, '1008', '2025-11-19', 17, 2, 0.00, 500.00, '1231231231232');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `code`, `name`) VALUES
(1, 'BSCS', 'Bachelor of Science in Computer Science'),
(2, 'BSA', 'Bachelor of Science in Accountancy'),
(4, 'BSC', 'Bachelor of Science in Criminology');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`) VALUES
(1, '401'),
(2, '402'),
(3, '403'),
(4, '404'),
(5, '405'),
(6, '406'),
(7, '407');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `semester_id` tinyint(4) UNSIGNED NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`semester_id`, `code`, `start_date`, `end_date`, `type`) VALUES
(1, '1st 25-26', '2025-08-11', '2025-12-15', 'Regular'),
(2, '2nd 25-26', '2026-01-26', '2026-05-18', 'Regular');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `student_no` varchar(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_no`, `name`, `gender`, `course_id`) VALUES
(15, '00001', 'Faye Carubio', 'M', 1),
(16, '00003', 'Nathaniel Ramos', 'M', 2),
(17, '00004', 'Dino Agito', 'F', 1),
(21, '00002', 'Ethan Velasquez', 'M', 1),
(22, '00005', 'Marvin Cruz', 'M', 2),
(23, '00006', 'Liam Ortega', 'F', 4),
(24, '00007', 'Caleb Navarro', 'F', 2);

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `semester_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `midterm` decimal(10,2) DEFAULT NULL,
  `fcg` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subjects`
--

INSERT INTO `student_subjects` (`id`, `student_id`, `subject_id`, `semester_id`, `midterm`, `fcg`) VALUES
(42, 15, 1, 1, NULL, 2.25),
(43, 15, 3, 1, NULL, 2.25),
(44, 15, 2, 1, NULL, NULL),
(45, 15, 1, 2, 1.00, NULL),
(46, 15, 3, 2, NULL, NULL),
(47, 15, 2, 2, NULL, NULL),
(48, 16, 1, 2, NULL, NULL),
(49, 16, 3, 2, NULL, NULL),
(50, 16, 2, 2, NULL, NULL),
(51, 15, 4, 2, NULL, NULL),
(64, 17, 3, 1, 1.00, 1.00),
(66, 17, 1, 1, 2.00, 2.25),
(81, 17, 1, 2, 2.25, NULL),
(82, 17, 3, 2, NULL, 2.25),
(83, 17, 2, 2, NULL, NULL),
(84, 21, 1, 1, 1.00, 1.00),
(85, 21, 3, 1, NULL, 3.50),
(86, 22, 1, 1, NULL, 3.50),
(87, 22, 3, 1, NULL, 3.50),
(88, 22, 1, 2, 2.50, NULL),
(89, 22, 3, 2, NULL, NULL),
(90, 22, 2, 2, NULL, NULL),
(91, 22, 4, 2, NULL, NULL),
(92, 23, 1, 1, NULL, 3.50),
(93, 23, 3, 1, NULL, 5.00),
(94, 23, 2, 1, NULL, NULL),
(95, 23, 4, 1, NULL, NULL),
(96, 23, 1, 2, NULL, NULL),
(97, 23, 3, 2, NULL, NULL),
(98, 23, 2, 2, NULL, NULL),
(99, 23, 4, 2, NULL, NULL),
(100, 24, 1, 1, NULL, 1.00),
(101, 24, 3, 1, NULL, NULL),
(102, 24, 2, 1, NULL, NULL),
(103, 24, 4, 1, NULL, NULL),
(104, 24, 1, 2, NULL, NULL),
(105, 24, 3, 2, NULL, NULL),
(106, 24, 2, 2, NULL, NULL),
(107, 24, 4, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `des` varchar(50) DEFAULT NULL,
  `days` varchar(50) NOT NULL,
  `time` varchar(50) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `price_unit` int(11) NOT NULL,
  `unit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `code`, `des`, `days`, `time`, `room_id`, `teacher_id`, `price_unit`, `unit`) VALUES
(1, 'CS3A8ANALYSIS', 'Elementary Analysis', 'MW', '10:00-12:00', 7, 1, 1000, 3),
(2, 'CS3A8APPDEV', 'Application Development', 'TTH', '1:00-2:30', 5, 2, 1000, 3),
(3, 'CS3A8AUTOLANG', 'Automata and Language', 'T', '4:00-5:30', 1, 1, 1100, 3),
(4, 'CS3A8CSELECT', 'Computer Science Elective', 'F', '1:00-4:00', 7, 3, 1500, 3);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `teacher_code` varchar(20) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `gender` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `teacher_code`, `name`, `gender`) VALUES
(1, '00001', 'Maria', 'F'),
(2, '00002', 'Mario', 'M'),
(3, '00003', 'Juana', 'F'),
(4, '00004', 'Shimiya', 'M');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user` varchar(100) DEFAULT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user`, `pass`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator'),
(10, '00001', '202cb962ac59075b964b07152d234b70', 'Student'),
(11, '00003', '202cb962ac59075b964b07152d234b70', 'Student'),
(12, '00004', '202cb962ac59075b964b07152d234b70', 'Student'),
(16, '00001', '202cb962ac59075b964b07152d234b70', 'Teacher'),
(17, '00002', '202cb962ac59075b964b07152d234b70', 'Teacher'),
(18, '00003', '202cb962ac59075b964b07152d234b70', 'Teacher'),
(19, '00004', '202cb962ac59075b964b07152d234b70', 'Teacher'),
(20, '00002', '202cb962ac59075b964b07152d234b70', 'Student'),
(21, '00005', '202cb962ac59075b964b07152d234b70', 'Student'),
(22, '00006', '202cb962ac59075b964b07152d234b70', 'Student'),
(23, '00007', '202cb962ac59075b964b07152d234b70', 'Student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`collection_id`),
  ADD UNIQUE KEY `or_number` (`or_number`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`semester_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `collection_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `collections_ibfk_2` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`semester_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `fk_student_subjects_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_subjects` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `student_subjects_ibfk_3` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`semester_id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
