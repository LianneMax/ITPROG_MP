-- MySQL Workbench Forward Engineering
-- Added user accounts in students table

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
  `class_days` VARCHAR(2) NOT NULL,
  `class_start_time` VARCHAR(45) NOT NULL,
  `class_end_time` VARCHAR(45) NOT NULL,
  `enroll_cap` INT(3) UNSIGNED NOT NULL,
  `enrolled_students` INT(3) UNSIGNED NOT NULL,
  `professor` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`offering_code`),
  INDEX `fk_section_offerings_courses1_idx` (`course_code` ASC),
  CONSTRAINT `fk_section_offerings_courses1`
    FOREIGN KEY (`course_code`)
    REFERENCES `itmosys_db`.`courses` (`course_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `itmosys_db`.`students`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`students` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`students` (
  `student_id` INT(8) UNSIGNED NOT NULL,
  `student_name` VARCHAR(200) NOT NULL,
  `password` VARCHAR(45) NULL,
  PRIMARY KEY (`student_id`))
ENGINE = InnoDB;

INSERT INTO `students` (`student_id`, `student_name`, `password`) VALUES
(1, 'user1', 'password123'),
(2, 'user2', 'pass456'),
(3, 'user3', 'securePwd789'),
(4, 'user4', 'myPassword2024'),
(5, 'user5', 'anotherPass543');

-- -----------------------------------------------------
-- Table `itmosys_db`.`students_classes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `itmosys_db`.`students_classes` ;

CREATE TABLE IF NOT EXISTS `itmosys_db`.`students_classes` (
  `student_id` INT(8) UNSIGNED NOT NULL,
  `offering_code` INT(4) UNSIGNED NOT NULL,
  PRIMARY KEY (`student_id`),
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


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
