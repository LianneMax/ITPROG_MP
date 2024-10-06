-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2024 at 07:22 PM
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
-- Database: `itmosys_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_code` varchar(7) NOT NULL,
  `course_title` varchar(100) NOT NULL,
  `units` int(1) UNSIGNED NOT NULL,
  `co_requisite` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_codes`
--

CREATE TABLE `course_codes` (
  `course_code` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prerequisites`
--

CREATE TABLE `prerequisites` (
  `course_code` varchar(7) NOT NULL,
  `prerequisite` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_offerings`
--

CREATE TABLE `section_offerings` (
  `offering_code` int(4) UNSIGNED NOT NULL,
  `course_code` varchar(7) NOT NULL,
  `class_days` varchar(2) NOT NULL,
  `class_start_time` varchar(45) NOT NULL,
  `class_end_time` varchar(45) NOT NULL,
  `enroll_cap` int(3) UNSIGNED NOT NULL,
  `enrolled_students` int(3) UNSIGNED NOT NULL,
  `professor` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(8) UNSIGNED NOT NULL,
  `student_name` varchar(200) NOT NULL,
  `password` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `students` (`student_id`, `student_name`, `password`) VALUES
(1, 'user1', 'password123'),
(2, 'user2', 'pass456'),
(3, 'user3', 'securePwd789'),
(4, 'user4', 'myPassword2024'),
(5, 'user5', 'anotherPass543');


-- --------------------------------------------------------

--
-- Table structure for table `students_classes`
--

CREATE TABLE `students_classes` (
  `student_id` int(8) UNSIGNED NOT NULL,
  `offering_code` int(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_code`);

--
-- Indexes for table `course_codes`
--
ALTER TABLE `course_codes`
  ADD PRIMARY KEY (`course_code`);

--
-- Indexes for table `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD PRIMARY KEY (`course_code`,`prerequisite`),
  ADD KEY `fk_prerequisites_course_codes1_idx` (`prerequisite`);

--
-- Indexes for table `section_offerings`
--
ALTER TABLE `section_offerings`
  ADD PRIMARY KEY (`offering_code`),
  ADD KEY `fk_section_offerings_courses1_idx` (`course_code`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `students_classes`
--
ALTER TABLE `students_classes`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `fk_students_classes_section_offerings1_idx` (`offering_code`);


--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_courses_course_codes` FOREIGN KEY (`course_code`) REFERENCES `course_codes` (`course_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD CONSTRAINT `fk_prerequisites_course_codes1` FOREIGN KEY (`prerequisite`) REFERENCES `course_codes` (`course_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prerequisites_courses1` FOREIGN KEY (`course_code`) REFERENCES `courses` (`course_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `section_offerings`
--
ALTER TABLE `section_offerings`
  ADD CONSTRAINT `fk_section_offerings_courses1` FOREIGN KEY (`course_code`) REFERENCES `courses` (`course_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `students_classes`
--
ALTER TABLE `students_classes`
  ADD CONSTRAINT `fk_students_classes_section_offerings1` FOREIGN KEY (`offering_code`) REFERENCES `section_offerings` (`offering_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_students_classes_students1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
