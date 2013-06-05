-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2013 at 12:55 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `spms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(45) NOT NULL,
  `secondName` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` char(36) NOT NULL,
  `email` varchar(45) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `username_2` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `firstName`, `secondName`, `username`, `password`, `email`, `last_login`) VALUES
(1, 'John', 'Brett', 'jbrett', 'password', 'jbrett@fakemail.com', '2013-05-24 23:57:09'),
(2, 'Helen', 'Byrne', 'helenbyrne', 'password', 'helenbyrne10@fakemail.com', '2013-05-24 23:57:09');

-- --------------------------------------------------------

--
-- Stand-in structure for view `all_results`
--
CREATE TABLE IF NOT EXISTS `all_results` (
`student_id` int(11)
,`group` char(1)
,`lab_id` int(11)
,`mark` int(3)
,`colour` char(10)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `all_students`
--
CREATE TABLE IF NOT EXISTS `all_students` (
`id` int(11)
,`firstName` varchar(30)
,`lastName` varchar(30)
,`module` varchar(10)
,`email` varchar(50)
,`group` char(1)
);
-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE IF NOT EXISTS `lab` (
  `id` int(11) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `finish_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lab`
--

INSERT INTO `lab` (`id`, `start_date`, `finish_date`) VALUES
(1, '2013-05-17 17:12:35', '2013-05-24 17:12:35'),
(2, '2013-05-24 12:33:48', '2013-05-31 17:12:35'),
(3, '2013-05-31 12:33:14', '2013-06-07 16:43:36');

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE IF NOT EXISTS `result` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` char(36) NOT NULL,
  `lab_id` int(11) NOT NULL,
  `mark` int(3) NOT NULL,
  `colour` char(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`,`lab_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`id`, `student_id`, `lab_id`, `mark`, `colour`) VALUES
(1, '1', 1, 50, 'green'),
(2, '2', 1, 40, 'orange'),
(3, '1', 2, 70, 'green'),
(4, '2', 2, 10, 'red'),
(5, '3', 3, 70, 'green'),
(6, '1', 3, 90, 'orange'),
(7, '3', 2, 40, 'orange'),
(12, '2', 3, 70, 'green'),
(13, '4', 1, 50, 'orange'),
(14, '4', 3, 50, 'orange'),
(15, '5', 2, 30, 'red'),
(16, '5', 3, 30, 'red'),
(17, '6', 1, 70, 'green'),
(18, '6', 2, 50, 'orange'),
(19, '6', 3, 30, 'red'),
(22, '7', 1, 30, 'red'),
(23, '7', 2, 50, 'orange'),
(24, '7', 3, 75, 'green'),
(25, '8', 1, 70, 'green'),
(26, '8', 2, 70, 'green'),
(27, '8', 3, 70, 'green'),
(28, '9', 1, 50, 'orange'),
(29, '9', 2, 50, 'orange'),
(30, '10', 1, 30, 'red'),
(31, '10', 2, 30, 'red'),
(32, '10', 3, 30, 'red');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `module` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `Group` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `firstName`, `lastName`, `module`, `email`, `Group`) VALUES
(1, 'James', 'King', 'CS101', 'jking@fakemail.com', 'A'),
(2, 'Julie', 'Taylor', 'CS101', 'jtaylor@fakemail.com', 'A'),
(3, 'John', 'Williams', 'CS101', 'jwilliams@fakemail.com', 'A'),
(4, 'Eugene', 'Lee', 'CS101', 'elee@fakemail.com', 'A'),
(5, 'Paul', 'Jones', 'CS102', 'pjones@fakemail.com', 'A'),
(6, 'Ray', 'Moore', 'CS102', 'rmooe@fakemail.com', 'B'),
(7, 'Paula', 'Gates', 'CS102', 'pgates@fakemail.com', 'B'),
(8, 'Lisa', 'Wong', 'CS102', 'lwong@fakemail.com', 'B'),
(9, 'Gary', 'Donovan', 'CS101', 'gdonovan@fakemail.com', 'B'),
(10, 'John', 'Byrne', 'CS101', 'jbyrne@fakemail.com', 'B');

-- --------------------------------------------------------

--
-- Structure for view `all_results`
--
DROP TABLE IF EXISTS `all_results`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_results` AS select `s`.`id` AS `student_id`,`s`.`Group` AS `group`,`r`.`lab_id` AS `lab_id`,`r`.`mark` AS `mark`,`r`.`colour` AS `colour` from (`student` `s` join `result` `r`) where (`s`.`id` = `r`.`student_id`) order by `s`.`id`,`r`.`lab_id`;

-- --------------------------------------------------------

--
-- Structure for view `all_students`
--
DROP TABLE IF EXISTS `all_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_students` AS select `student`.`id` AS `id`,`student`.`firstName` AS `firstName`,`student`.`lastName` AS `lastName`,`student`.`module` AS `module`,`student`.`email` AS `email`,`student`.`Group` AS `group` from `student` order by `student`.`firstName`,`student`.`lastName`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
