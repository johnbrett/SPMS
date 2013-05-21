-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2013 at 08:24 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `directory`
--

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `module` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
