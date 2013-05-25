-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2013 at 03:57 PM
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
(1, '2013-05-24 17:12:35', '2013-05-24 17:12:35'),
(2, '2013-05-24 17:12:46', '2013-05-24 17:12:35');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`id`, `student_id`, `lab_id`, `mark`, `colour`) VALUES
(1, '1', 1, 50, 'green'),
(2, '2', 1, 40, 'yellow'),
(3, '1', 2, 70, 'green'),
(4, '2', 2, 10, 'red');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `firstName`, `lastName`, `module`, `email`) VALUES
(1, 'James', 'King', 'CS101', 'jking@fakemail.com'),
(2, 'Julie', 'Taylor', 'CS101', 'jtaylor@fakemail.com'),
(3, 'John', 'Williams', 'CS101', 'jwilliams@fakemail.com'),
(4, 'Eugene', 'Lee', 'CS101', 'elee@fakemail.com'),
(5, 'Paul', 'Jones', 'CS102', 'pjones@fakemail.com'),
(6, 'Ray', 'Moore', 'CS102', 'rmooe@fakemail.com'),
(7, 'Paula', 'Gates', 'CS102', 'pgates@fakemail.com'),
(8, 'Lisa', 'Wong', 'CS102', 'lwong@fakemail.com'),
(9, 'Gary', 'Donovan', 'CS101', 'gdonovan@fakemail.com'),
(10, 'John', 'Byrne', 'CS101', 'jbyrne@fakemail.com');

-- --------------------------------------------------------

--
-- Structure for view `all_results`
--
DROP TABLE IF EXISTS `all_results`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_results` AS select `s`.`id` AS `student_id`,`l`.`id` AS `lab_id`,`r`.`mark` AS `mark`,`r`.`colour` AS `colour` from ((`student` `s` join `lab` `l`) join `result` `r`) where ((`r`.`student_id` = `s`.`id`) and (`r`.`lab_id` = `l`.`id`)) order by `s`.`firstName`;

-- --------------------------------------------------------

--
-- Structure for view `all_students`
--
DROP TABLE IF EXISTS `all_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_students` AS select `student`.`id` AS `id`,`student`.`firstName` AS `firstName`,`student`.`lastName` AS `lastName`,`student`.`module` AS `module`,`student`.`email` AS `email` from `student` order by `student`.`firstName`,`student`.`lastName`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
