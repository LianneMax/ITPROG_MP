-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema itmosys_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema itmosys_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `itmosys_db` DEFAULT CHARACTER SET utf8 ;
USE `itmosys_db` ;

-- -----------------------------------------------------
-- Table `itmosys_db`.`course_codes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`course_codes` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`course_codes` (
  `course_code` VARCHAR(7) NOT NULL,
  PRIMARY KEY (`course_code`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `itmosys_db`.`courses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`courses` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`courses` (
  `course_code` VARCHAR(7) NOT NULL,
  `course_title` VARCHAR(100) NOT NULL,
  `units` INT(1) UNSIGNED NOT NULL,
  `co_requisite` VARCHAR(7) NULL,
  PRIMARY KEY (`course_code`),
  CONSTRAINT `fk_courses_course_codes`
    FOREIGN KEY (`course_code`)
    REFERENCES `itmosys_db`.`course_codes` (`course_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `itmosys_db`.`prerequisites`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`prerequisites` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`prerequisites` (
  `course_code` VARCHAR(7) NOT NULL,
  `prerequisite` VARCHAR(7) NOT NULL,
  PRIMARY KEY (`course_code`, `prerequisite`),
  INDEX `fk_prerequisites_course_codes1_idx` (`prerequisite` ASC),
  CONSTRAINT `fk_prerequisites_courses1`
    FOREIGN KEY (`course_code`)
    REFERENCES `itmosys_db`.`courses` (`course_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prerequisites_course_codes1`
    FOREIGN KEY (`prerequisite`)
    REFERENCES `itmosys_db`.`course_codes` (`course_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `itmosys_db`.`section_offerings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`section_offerings` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`section_offerings` (
  `offering_code` INT(4) UNSIGNED NOT NULL,
  `course_code` VARCHAR(7) NOT NULL,
  `section` VARCHAR(3) NOT NULL,
  `class_days` VARCHAR(2) NOT NULL,
  `class_start_time` VARCHAR(45) NOT NULL,
  `class_end_time` VARCHAR(45) NOT NULL,
  `enroll_cap` INT(3) UNSIGNED NOT NULL,
  `enrolled_students` INT(3) UNSIGNED NOT NULL,
  `professor` VARCHAR(200) NOT NULL,
  `room` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`offering_code`),
  INDEX `fk_section_offerings_courses1_idx` (`course_code` ASC),
  CONSTRAINT `fk_section_offerings_courses1`
    FOREIGN KEY (`course_code`)
    REFERENCES `itmosys_db`.`courses` (`course_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `itmosys_db`.`id_numbers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`id_numbers` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`id_numbers` (
  `id_number` INT(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_number`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `itmosys_db`.`students`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`students` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`students` (
  `student_id` INT(8) UNSIGNED NOT NULL,
  `student_lastname` VARCHAR(100) NOT NULL,
  `student_firstname` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`student_id`),
  CONSTRAINT `fk_students_id_numbers1`
    FOREIGN KEY (`student_id`)
    REFERENCES `itmosys_db`.`id_numbers` (`id_number`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `itmosys_db`.`students_classes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`students_classes` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`students_classes` (
  `student_id` INT(8) UNSIGNED NOT NULL,
  `offering_code` INT(4) UNSIGNED NOT NULL,
  `grade` DECIMAL(2,1) NULL,
  PRIMARY KEY (`offering_code`, `student_id`),
  INDEX `fk_students_classes_section_offerings1_idx` (`offering_code` ASC),
  CONSTRAINT `fk_students_classes_students1`
    FOREIGN KEY (`student_id`)
    REFERENCES `itmosys_db`.`students` (`student_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_students_classes_section_offerings1`
    FOREIGN KEY (`offering_code`)
    REFERENCES `itmosys_db`.`section_offerings` (`offering_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `itmosys_db`.`admins`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`admins` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`admins` (
  `admin_id` INT(8) UNSIGNED NOT NULL,
  `admin_lastname` VARCHAR(100) NOT NULL,
  `admin_firstname` VARCHAR(100) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`admin_id`),
  CONSTRAINT `fk_admins_id_numbers1`
    FOREIGN KEY (`admin_id`)
    REFERENCES `itmosys_db`.`id_numbers` (`id_number`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `itmosys_db`.`past_enrollments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`past_enrollments` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`past_enrollments` (
  `student_id` INT(8) UNSIGNED NOT NULL,
  `course_code` VARCHAR(7) NOT NULL,
  `grade` DECIMAL(2,1) NOT NULL,
  PRIMARY KEY (`student_id`, `course_code`, `grade`),
  INDEX `fk_past_enrollments_courses1_idx` (`course_code` ASC) ,
  CONSTRAINT `fk_past_enrollments_students1`
    FOREIGN KEY (`student_id`)
    REFERENCES `itmosys_db`.`students` (`student_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_past_enrollments_courses1`
    FOREIGN KEY (`course_code`)
    REFERENCES `itmosys_db`.`courses` (`course_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `itmosys_db`.`course_codes`
-- -----------------------------------------------------
START TRANSACTION;
USE `itmosys_db`;
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('ITCMSY1');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('LBYCMSY');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('ITNET01');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('LBYITN1');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('ITISORG');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('ITNET02');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('LBYITN2');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('ITSECUR');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('IT-PROG');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('ITSYSAD');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('LBYSYAD');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('ITNET03');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('LBYITN3');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('ITSRAQA');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('ITSYSOP');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('ITNET04');
INSERT INTO `itmosys_db`.`course_codes` (`course_code`) VALUES ('LBYITN4');

COMMIT;


-- -----------------------------------------------------
-- Data for table `itmosys_db`.`courses`
-- -----------------------------------------------------
START TRANSACTION;
USE `itmosys_db`;
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('ITCMSY1', ' Introduction to Computing Platforms and Operating Systems', 3, 'LBYCMSY');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('LBYCMSY', ' Introduction to Computing Platforms and Operating Systems - Laboratory', 1, 'ITCMSY1');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('ITNET01', 'Introduction to Networks', 3, 'LBYITN1');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('LBYITN1', 'Introduction to Networks - Laboratory', 1, 'ITNET01');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('ITISORG', 'Organizational Management', 3, '');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('ITNET02', 'Basic Routing and Switching', 3, 'LBYITN2');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('LBYITN2', 'Basic Routing and Switching - Laboratory', 1, 'ITNET02');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('ITSECUR', 'Introduction to Information Security', 3, '');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('IT-PROG', 'Integrative Programming', 3, '');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('ITSYSAD', 'System Administration and Maintenance', 3, 'LBYSYAD');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('LBYSYAD', 'System Administration and Maintenance - Laboratory', 1, 'ITSYSAD');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('ITNET03', 'Advanced Routing and Switching', 3, 'LBYITN3');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('LBYITN3', 'Advanced Routing and Switching - Laboratory', 1, 'ITNET03');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('ITSRAQA', 'System Requirement Analysis and QA', 3, '');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('ITSYSOP', 'IT System Operation', 3, '');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('ITNET04', 'WAN Connectivity', 3, 'LBYITN4');
INSERT INTO `itmosys_db`.`courses` (`course_code`, `course_title`, `units`, `co_requisite`) VALUES ('LBYITN4', 'WAN Connectivity - Laboratory', 1, 'ITNET04');

COMMIT;


-- -----------------------------------------------------
-- Data for table `itmosys_db`.`prerequisites`
-- -----------------------------------------------------
START TRANSACTION;
USE `itmosys_db`;
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITNET02', 'ITNET01');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITNET02', 'LBYITN1');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('LBYITN2', 'ITNET01');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('LBYITN2', 'LBYITN1');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITSECUR', 'ITNET01');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITSECUR', 'LBYITN1');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITSYSAD', 'ITCMSY1');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITSYSAD', 'LBYCMSY');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('LBYSYAD', 'ITCMSY1');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('LBYSYAD', 'LBYCMSY');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITNET03', 'ITNET02');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITNET03', 'LBYITN2');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('LBYITN3', 'ITNET02');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('LBYITN3', 'LBYITN2');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITSYSOP', 'ITNET02');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITSYSOP', 'LBYITN2');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITSYSOP', 'ITCMSY1');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITSYSOP', 'LBYCMSY');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITNET04', 'ITNET03');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('ITNET04', 'LBYITN3');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('LBYITN4', 'ITNET03');
INSERT INTO `itmosys_db`.`prerequisites` (`course_code`, `prerequisite`) VALUES ('LBYITN4', 'LBYITN3');

COMMIT;


-- -----------------------------------------------------
-- Data for table `itmosys_db`.`section_offerings`
-- -----------------------------------------------------
START TRANSACTION;
USE `itmosys_db`;
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (1900, 'IT-PROG', 'S17', 'MH', '18:00', '19:30', 45, 0, 'Naruto', 'Online');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (1600, 'ITNET02', 'S16', 'MH', '09:15', '10:45', 20, 0, 'Fritz Flores', 'GK405');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (1700, 'LBYITN2', 'S16', 'MH', '11:00', '12:30', 20, 0, 'Fritz Flores', 'GK405');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (2000, 'ITSYSAD', 'S10', 'MH', '09:15', '10:45', 45, 0, 'Katrina Solomon', 'GK210');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (2100, 'LBYSYAD', 'S10', 'MH', '11:00', '12:30', 45, 0, 'Katrina Solomon', 'GK210');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (1500, 'ITISORG', 'S12', 'TF', '09:15', '10:45', 45, 0, 'Sentinel Prime', 'AG1107');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (1501, 'ITISORG', 'S13', 'TF', '11:00', '12:30', 45, 0, 'Sentinel Prime', 'AG1107');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (1901, 'IT-PROG', 'S18', 'TF', '09:15', '10:45', 45, 0, 'Naruto', 'Online');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (1601, 'ITNET02', 'S17', 'MH', '14:30', '16:00', 20, 0, 'Hiroki Asaba', 'GK405');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (1701, 'LBYITN2', 'S17', 'MH', '16:00', '17:45', 20, 0, 'Hiroki Asaba', 'GK405');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (2001, 'ITSYSAD', 'S11', 'TF', '14:30', '16:00', 45, 0, 'Katrina Solomon', 'GK210');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (2101, 'LBYSYAD', 'S11', 'TF', '16:00', '17:45', 45, 0, 'Katrina Solomon', 'GK210');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (1800, 'ITSECUR', 'S15', 'W', '09:15', '12:30', 45, 0, 'Keinaz Domingo', 'GK404A');
INSERT INTO `itmosys_db`.`section_offerings` (`offering_code`, `course_code`, `section`, `class_days`, `class_start_time`, `class_end_time`, `enroll_cap`, `enrolled_students`, `professor`, `room`) VALUES (1801, 'ITSECUR', 'S16', 'S', '09:15', '12:30', 45, 0, 'Keinaz Domingo', 'GK404A');

COMMIT;


-- -----------------------------------------------------
-- Data for table `itmosys_db`.`id_numbers`
-- -----------------------------------------------------
START TRANSACTION;
USE `itmosys_db`;
INSERT INTO `itmosys_db`.`id_numbers` (`id_number`) VALUES (1);
INSERT INTO `itmosys_db`.`id_numbers` (`id_number`) VALUES (2);
INSERT INTO `itmosys_db`.`id_numbers` (`id_number`) VALUES (3);
INSERT INTO `itmosys_db`.`id_numbers` (`id_number`) VALUES (4);
INSERT INTO `itmosys_db`.`id_numbers` (`id_number`) VALUES (5);
INSERT INTO `itmosys_db`.`id_numbers` (`id_number`) VALUES (21);
INSERT INTO `itmosys_db`.`id_numbers` (`id_number`) VALUES (22);
INSERT INTO `itmosys_db`.`id_numbers` (`id_number`) VALUES (23);
INSERT INTO `itmosys_db`.`id_numbers` (`id_number`) VALUES (24);
INSERT INTO `itmosys_db`.`id_numbers` (`id_number`) VALUES (25);

COMMIT;


-- -----------------------------------------------------
-- Data for table `itmosys_db`.`students`
-- -----------------------------------------------------
START TRANSACTION;
USE `itmosys_db`;
INSERT INTO `itmosys_db`.`students` (`student_id`, `student_lastname`, `student_firstname`, `password`) VALUES (1, 'Lee', 'Justin', 'password123');
INSERT INTO `itmosys_db`.`students` (`student_id`, `student_lastname`, `student_firstname`, `password`) VALUES (2, 'Ang', 'Jeremiah', 'pass456');
INSERT INTO `itmosys_db`.`students` (`student_id`, `student_lastname`, `student_firstname`, `password`) VALUES (3, 'Balbastro', 'Lianne', 'securePwd789');
INSERT INTO `itmosys_db`.`students` (`student_id`, `student_lastname`, `student_firstname`, `password`) VALUES (4, 'Duelas', 'Charles', 'myPassword2024');
INSERT INTO `itmosys_db`.`students` (`student_id`, `student_lastname`, `student_firstname`, `password`) VALUES (5, 'Pax', 'Orion', 'anotherPass543');

COMMIT;


-- -----------------------------------------------------
-- Data for table `itmosys_db`.`students_classes`
-- -----------------------------------------------------
START TRANSACTION;
USE `itmosys_db`;
INSERT INTO `itmosys_db`.`students_classes` (`student_id`, `offering_code`, `grade`) VALUES (1, 1600, NULL);
INSERT INTO `itmosys_db`.`students_classes` (`student_id`, `offering_code`, `grade`) VALUES (1, 1700, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `itmosys_db`.`admins`
-- -----------------------------------------------------
START TRANSACTION;
USE `itmosys_db`;
INSERT INTO `itmosys_db`.`admins` (`admin_id`, `admin_lastname`, `admin_firstname`, `password`) VALUES (21, 'Trion', 'Alpha', 'password123');
INSERT INTO `itmosys_db`.`admins` (`admin_id`, `admin_lastname`, `admin_firstname`, `password`) VALUES (22, 'Prime', 'Megatronus', 'pass456');
INSERT INTO `itmosys_db`.`admins` (`admin_id`, `admin_lastname`, `admin_firstname`, `password`) VALUES (23, 'Lopez', 'Jason', 'securePwd789');
INSERT INTO `itmosys_db`.`admins` (`admin_id`, `admin_lastname`, `admin_firstname`, `password`) VALUES (24, 'Reynolds', 'Ryan', 'myPassword2024');
INSERT INTO `itmosys_db`.`admins` (`admin_id`, `admin_lastname`, `admin_firstname`, `password`) VALUES (25, 'Jackman', 'Hugh', 'anotherPass543');

COMMIT;


-- -----------------------------------------------------
-- Data for table `itmosys_db`.`past_enrollments`
-- -----------------------------------------------------
START TRANSACTION;
USE `itmosys_db`;
INSERT INTO `itmosys_db`.`past_enrollments` (`student_id`, `course_code`, `grade`) VALUES (1, 'ITNET01', 3.5);
INSERT INTO `itmosys_db`.`past_enrollments` (`student_id`, `course_code`, `grade`) VALUES (1, 'LBYITN1', 3.5);
INSERT INTO `itmosys_db`.`past_enrollments` (`student_id`, `course_code`, `grade`) VALUES (1, 'ITSECUR', 0);
INSERT INTO `itmosys_db`.`past_enrollments` (`student_id`, `course_code`, `grade`) VALUES (1, 'ITSECUR', 4);
INSERT INTO `itmosys_db`.`past_enrollments` (`student_id`, `course_code`, `grade`) VALUES (2, 'ITNET01', 4);
INSERT INTO `itmosys_db`.`past_enrollments` (`student_id`, `course_code`, `grade`) VALUES (2, 'LBYITN1', 4);

COMMIT;

