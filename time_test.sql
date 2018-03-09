-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2017 at 06:54 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `time_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `time_cadastral`
--

CREATE TABLE IF NOT EXISTS `time_cadastral` (
`cadastralId` mediumint(8) unsigned NOT NULL,
  `councilId` smallint(5) unsigned NOT NULL,
  `psNo` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `planningCategory` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parishName` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crownAllotment` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `township` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lot` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `planNo` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vol1` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vol2` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vol3` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fol1` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fol2` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fol3` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_cadastral`
--

INSERT INTO `time_cadastral` (`cadastralId`, `councilId`, `psNo`, `planningCategory`, `parishName`, `crownAllotment`, `section`, `township`, `lot`, `planNo`, `vol1`, `vol2`, `vol3`, `fol1`, `fol2`, `fol3`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, '1', 'Rule 1', 'Jason', '55', 'health', 'Dasmarinas', '55', '2', '5', '55', '5', '2', '5', '5'),
(3, 1, '32', NULL, NULL, NULL, NULL, NULL, '5', '6', '54', '54', '54', '54', '54', '54'),
(5, 1, '5', '5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 1, '5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 1, '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5'),
(10, 1, '555', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 1, NULL, NULL, NULL, NULL, NULL, NULL, '5', '5', '25', '25', '25', '25', '25', '25'),
(13, 1, '5', 'test', 'testing', 'testing', 'testing', 'testing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `time_checklist`
--

CREATE TABLE IF NOT EXISTS `time_checklist` (
`checklistId` smallint(5) unsigned NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_checklist`
--

INSERT INTO `time_checklist` (`checklistId`, `title`, `description`) VALUES
(12, 'test', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `time_checklist_item`
--

CREATE TABLE IF NOT EXISTS `time_checklist_item` (
`checklistItemId` mediumint(8) unsigned NOT NULL,
  `checklistId` smallint(5) unsigned NOT NULL,
  `displayOrder` smallint(6) unsigned NOT NULL,
  `newRow` tinyint(1) NOT NULL DEFAULT '0',
  `fieldType` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fieldTitle` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fieldWidth` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_checklist_item`
--

INSERT INTO `time_checklist_item` (`checklistItemId`, `checklistId`, `displayOrder`, `newRow`, `fieldType`, `fieldTitle`, `fieldWidth`) VALUES
(44, 12, 1, 0, 'Checkbox', 'checkbox', 'col-md-4'),
(45, 12, 2, 0, 'Date', 'date', 'col-md-4'),
(46, 12, 3, 0, 'Input', 'input', 'col-md-4');

-- --------------------------------------------------------

--
-- Table structure for table `time_contactlink`
--

CREATE TABLE IF NOT EXISTS `time_contactlink` (
`contactLinkId` mediumint(8) unsigned NOT NULL,
  `jobId` mediumint(9) unsigned NOT NULL,
  `contactPersonId` mediumint(9) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_contactperson`
--

CREATE TABLE IF NOT EXISTS `time_contactperson` (
`contactPersonId` mediumint(8) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(90) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_contactperson`
--

INSERT INTO `time_contactperson` (`contactPersonId`, `name`, `company`, `address1`, `address2`, `phone`, `fax`, `mobile`, `email`) VALUES
(1, 'Sample', 'sample', 'sample', '', '123122', '', '12313123', 'sample@gmail.com'),
(2, 'Ad1', 'asd', 'ads', 'asd', '1231313', '', '123123', 'gmail@gmail.com'),
(3, 'Jays', 'JAY', 'dasma', NULL, '123123', NULL, '123123', 'jay@gmail.com'),
(4, 'Qwe', 'qwe', 'qwe', NULL, '12', NULL, '121', 'qwe@gmail.com'),
(5, 'Wqe', 'qwe', 'qwe', NULL, '12', NULL, '12', 'g@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `time_council`
--

CREATE TABLE IF NOT EXISTS `time_council` (
`councilId` smallint(5) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_council`
--

INSERT INTO `time_council` (`councilId`, `name`) VALUES
(1, 'SHRCC'),
(2, 'QCPDS'),
(3, 'QWE'),
(4, 'Asdrtu'),
(5, 'ZXC');

-- --------------------------------------------------------

--
-- Table structure for table `time_customer`
--

CREATE TABLE IF NOT EXISTS `time_customer` (
`customerId` mediumint(8) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customerType` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postCode` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_customer`
--

INSERT INTO `time_customer` (`customerId`, `name`, `customerType`, `address1`, `address2`, `city`, `region`, `postCode`, `phone`, `fax`, `mobile`, `email`, `active`) VALUES
(1, 'John gifter C Pojas', 'Person', 'Dasmarinas', NULL, 'cavite', 'Region4', NULL, NULL, NULL, NULL, 'gifterp@gmail.com', 1),
(2, 'Test test', 'Business', 'samples', '', '', '', '', '', '', '', 'sample@gmail.com', 1),
(3, 'Qwe1', 'Business', 'qwe', 'qwe', '', '', '', '', '', '', 'gmail@gmail.com', 1),
(4, 'Shannon Mae Allilio', 'Business', 'Alilio Drive', NULL, 'Alfonso', 'Cavite', '4114', '098987854', NULL, NULL, 'shannon@gmail.com', 1),
(5, 'Qwe', 'Business', 'qwe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'qwe@gmail.xcom', 1);

-- --------------------------------------------------------

--
-- Table structure for table `time_department`
--

CREATE TABLE IF NOT EXISTS `time_department` (
`departmentId` smallint(5) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_department`
--

INSERT INTO `time_department` (`departmentId`, `name`) VALUES
(1, 'Security'),
(2, 'Technicals'),
(3, 'Securitys'),
(4, 'Human Resources');

-- --------------------------------------------------------

--
-- Table structure for table `time_job`
--

CREATE TABLE IF NOT EXISTS `time_job` (
`jobId` mediumint(8) unsigned NOT NULL,
  `parentJobId` mediumint(8) unsigned DEFAULT NULL,
  `customerId` mediumint(8) unsigned NOT NULL,
  `userId` smallint(5) unsigned NOT NULL,
  `departmentId` smallint(5) unsigned NOT NULL,
  `jobCustomFieldsId` mediumint(8) unsigned NOT NULL,
  `cadastralId` mediumint(8) unsigned DEFAULT NULL,
  `jobReferenceNo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jobName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jobType` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `streetNo` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `streetName` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suburb` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postCode` mediumint(6) DEFAULT NULL,
  `easting` int(11) DEFAULT NULL,
  `northing` int(11) DEFAULT NULL,
  `zone` smallint(6) DEFAULT NULL,
  `tender` tinyint(1) DEFAULT NULL,
  `received` date DEFAULT NULL,
  `start` date DEFAULT NULL,
  `finish` date DEFAULT NULL,
  `archived` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_job`
--

INSERT INTO `time_job` (`jobId`, `parentJobId`, `customerId`, `userId`, `departmentId`, `jobCustomFieldsId`, `cadastralId`, `jobReferenceNo`, `jobName`, `jobType`, `streetNo`, `streetName`, `suburb`, `postCode`, `easting`, `northing`, `zone`, `tender`, `received`, `start`, `finish`, `archived`) VALUES
(1, NULL, 4, 1, 1, 6, 3, 'GT001', 'Gifts Technologies', 'Final entry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2016-12-03', NULL, NULL, 0),
(2, NULL, 1, 0, 0, 0, 8, 'GC001', 'Gift child', 'Trial jobs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(3, NULL, 1, 0, 0, 0, 2, 'GSC001', 'GIFT SECOND CHILD', 'Trial jobs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(4, NULL, 4, 0, 0, 0, NULL, 'GTJ0012', 'GIFT THIRD JOBs', 'Qwerty', '23', 'IDAHO', 'ICV', 4112, 1, 1, 55, NULL, NULL, NULL, NULL, 0),
(5, NULL, 1, 0, 0, 0, NULL, 'GFJ001', 'GIFT FOURTH JOB', 'Qwerty', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(6, NULL, 1, 0, 0, 0, NULL, 'a', 'GIFT FIFTH JOB', 'Qwerty', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(7, NULL, 1, 0, 0, 0, NULL, 'b', 'GIFT SIXTH JOB', 'Qwerty', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(8, NULL, 1, 0, 0, 0, NULL, 'a', 'GIFT SEVEN JOB', 'Qwerty', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(12, NULL, 4, 0, 0, 0, 5, 'CB0011', 'Callbacks', NULL, NULL, NULL, NULL, 4114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(13, 12, 1, 0, 0, 0, NULL, '00021', 'Child Callback', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(14, 12, 1, 0, 0, 0, NULL, 'CC002', 'Child Callback Senior', 'Trial jobs', 'B10 L20', 'Indiana St', 'ICV', NULL, 23, 56, 4, NULL, NULL, NULL, NULL, 0),
(15, 12, 1, 0, 0, 0, NULL, 'CCB003', 'Child Callback Elder', 'Qwerty', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(16, 12, 1, 0, 0, 0, NULL, 'CCB004', 'Child Callback Freshmen', 'Asd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(17, 12, 1, 0, 0, 0, NULL, 'CB005', 'Child Callback junior', 'Trial jobs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(18, 12, 1, 0, 0, 0, NULL, '00006', 'Child Job Sophomore', 'Trial jobs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(19, 12, 4, 0, 0, 0, NULL, 'CB001', 'cc', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(20, 12, 4, 0, 0, 0, NULL, 'cc', 'cc', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(21, 12, 4, 0, 0, 0, NULL, 'a', 'qwe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(22, 12, 4, 0, 0, 0, NULL, 'asd', 'asd', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(23, NULL, 4, 0, 0, 0, 7, 'fgh', 'fgh', 'Asd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(24, NULL, 3, 0, 0, 0, NULL, 'gfh', 'fghf', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(25, NULL, 2, 0, 0, 0, NULL, 'asda', 'asd', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(26, NULL, 2, 0, 0, 0, NULL, 'NC', 'No Cadastral', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(27, NULL, 4, 0, 0, 0, NULL, 'GSC001', 'GIFT SECOND CHILD', 'Trial jobs', '25', NULL, NULL, NULL, 5555, 5633, 54, NULL, NULL, NULL, NULL, 0),
(28, NULL, 4, 0, 0, 0, NULL, 'b', 'GIFT SECOND CHILD', 'Trial jobs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(29, 1, 3, 0, 0, 0, NULL, 'GT0001', 'trial job', 'Test Job', NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0),
(30, 12, 4, 0, 0, 0, NULL, 'b', 'qwe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(31, NULL, 4, 0, 0, 0, 9, 'AB001', 'Test job', 'Test Job', '55', '54', '223', 5, 55555, 55555, 54, NULL, NULL, NULL, NULL, 0),
(32, 31, 2, 0, 0, 0, NULL, '0001', 'Sample Test Child', 'Test Job', '25', 'Indiana ', '23', 4114, 55555, 55555, 54, NULL, NULL, NULL, NULL, 0),
(33, 1, 4, 0, 0, 0, NULL, 'GT0002', 'Gifts Child', 'Trial jobs', '', '', '', 0, 0, 0, 0, NULL, NULL, NULL, NULL, 0),
(34, NULL, 4, 1, 2, 2, 10, 'AB001', 'Astral book', '', '', '', '', 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '0000-00-00', 0),
(37, NULL, 4, 1, 1, 4, NULL, 'AB003', 'Abstral Bias', 'Trial jobs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(38, NULL, 4, 1, 4, 5, 13, 'RB001', 'Read Book', 'Test Job', 'b10', 'IOWA', NULL, NULL, NULL, NULL, NULL, 5, '2016-12-01', '2016-12-02', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `time_jobnote`
--

CREATE TABLE IF NOT EXISTS `time_jobnote` (
`jobNoteId` mediumint(8) unsigned NOT NULL,
  `jobId` mediumint(8) unsigned NOT NULL,
  `userId` smallint(5) unsigned NOT NULL,
  `noteDate` date NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_jobnote`
--

INSERT INTO `time_jobnote` (`jobNoteId`, `jobId`, `userId`, `noteDate`, `comment`) VALUES
(1, 2, 1, '2016-11-19', 'dasd'),
(2, 3, 1, '2016-11-23', 'yellow red blue'),
(3, 4, 1, '2016-11-30', 'qweqew');

-- --------------------------------------------------------

--
-- Table structure for table `time_jobtype`
--

CREATE TABLE IF NOT EXISTS `time_jobtype` (
`jobTypeId` smallint(5) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_jobtype`
--

INSERT INTO `time_jobtype` (`jobTypeId`, `name`) VALUES
(2, 'Test Job'),
(3, 'Trial jobs'),
(4, 'AFirsts'),
(5, 'Qwerty'),
(6, 'Qwe'),
(7, 'S'),
(8, 'Final entry'),
(9, 'Final entrysadasd'),
(10, 'ASecond'),
(11, 'Athird');

-- --------------------------------------------------------

--
-- Table structure for table `time_job_checklist`
--

CREATE TABLE IF NOT EXISTS `time_job_checklist` (
`jobChecklistId` mediumint(8) unsigned NOT NULL,
  `checklistId` smallint(5) unsigned NOT NULL,
  `jobId` mediumint(8) unsigned NOT NULL,
  `displayOrder` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_job_checklist`
--

INSERT INTO `time_job_checklist` (`jobChecklistId`, `checklistId`, `jobId`, `displayOrder`) VALUES
(5, 12, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `time_job_checklist_data`
--

CREATE TABLE IF NOT EXISTS `time_job_checklist_data` (
`jobChecklistDataId` mediumint(8) unsigned NOT NULL,
  `jobChecklistId` mediumint(9) NOT NULL,
  `checklistItemId` smallint(6) NOT NULL,
  `dateData` date DEFAULT NULL,
  `checkboxData` tinyint(1) DEFAULT '0',
  `textData` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_job_checklist_data`
--

INSERT INTO `time_job_checklist_data` (`jobChecklistDataId`, `jobChecklistId`, `checklistItemId`, `dateData`, `checkboxData`, `textData`) VALUES
(5, 5, 44, NULL, 0, NULL),
(6, 5, 45, '2016-11-28', 0, NULL),
(7, 5, 46, NULL, 0, 'hellow');

-- --------------------------------------------------------

--
-- Table structure for table `time_job_customfields`
--

CREATE TABLE IF NOT EXISTS `time_job_customfields` (
`jobCustomFieldsId` mediumint(8) unsigned NOT NULL,
  `budget` decimal(11,2) DEFAULT NULL,
  `purchaseOrderNo` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crownAllotment` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parish` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_job_customfields`
--

INSERT INTO `time_job_customfields` (`jobCustomFieldsId`, `budget`, `purchaseOrderNo`, `crownAllotment`, `section`, `parish`, `area`) VALUES
(2, '500.00', 'AC0001', NULL, NULL, NULL, NULL),
(3, '255.25', NULL, NULL, NULL, NULL, NULL),
(4, '1500.50', 'AC005', NULL, NULL, NULL, NULL),
(5, '255.00', '25', NULL, NULL, NULL, NULL),
(6, '55.50', 'PO002', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `time_job_invoice`
--

CREATE TABLE IF NOT EXISTS `time_job_invoice` (
`invoiceId` mediumint(8) unsigned NOT NULL,
  `jobId` mediumint(9) unsigned NOT NULL,
  `invoiceDate` date NOT NULL,
  `actualInvoiceNo` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `mockInvoiceId` mediumint(9) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_job_invoice`
--

INSERT INTO `time_job_invoice` (`invoiceId`, `jobId`, `invoiceDate`, `actualInvoiceNo`, `amount`, `mockInvoiceId`) VALUES
(1, 3, '2016-11-23', '5', '5.00', 5),
(2, 3, '2016-11-23', '2', '2.00', 2),
(3, 37, '2016-11-30', '5', '5.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `time_mail`
--

CREATE TABLE IF NOT EXISTS `time_mail` (
`mailId` mediumint(8) unsigned NOT NULL,
  `jobId` mediumint(8) unsigned DEFAULT NULL,
  `sendDate` date NOT NULL,
  `recipient` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_mail`
--

INSERT INTO `time_mail` (`mailId`, `jobId`, `sendDate`, `recipient`, `description`) VALUES
(1, NULL, '2016-10-18', 'qwe12', 'qwe12345'),
(2, NULL, '2016-11-25', 'qwe', 'qwe'),
(3, NULL, '2016-11-25', 'testing lang ', '');

-- --------------------------------------------------------

--
-- Table structure for table `time_mockinvoice`
--

CREATE TABLE IF NOT EXISTS `time_mockinvoice` (
`mockInvoiceId` mediumint(8) unsigned NOT NULL,
  `jobId` mediumint(8) unsigned NOT NULL,
  `childJobId` mediumint(9) DEFAULT NULL,
  `readyToInvoice` tinyint(1) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_mockinvoice_category`
--

CREATE TABLE IF NOT EXISTS `time_mockinvoice_category` (
`mockInvoiceCategoryId` int(11) unsigned NOT NULL,
  `category` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `time_mockinvoice_category`
--

INSERT INTO `time_mockinvoice_category` (`mockInvoiceCategoryId`, `category`) VALUES
(1, 'Testing');

-- --------------------------------------------------------

--
-- Table structure for table `time_mockinvoice_description`
--

CREATE TABLE IF NOT EXISTS `time_mockinvoice_description` (
`mockInvoiceDescriptionId` int(11) unsigned NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `time_mockinvoice_row`
--

CREATE TABLE IF NOT EXISTS `time_mockinvoice_row` (
`mockInvoiceRowId` mediumint(8) unsigned NOT NULL,
  `mockInvoiceId` mediumint(8) unsigned NOT NULL,
  `timesheetTaskId` smallint(5) unsigned NOT NULL,
  `adjustedTotal` decimal(9,2) DEFAULT NULL,
  `invoiceComment` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_system_settings`
--

CREATE TABLE IF NOT EXISTS `time_system_settings` (
`systemSettingsId` smallint(5) unsigned NOT NULL,
  `businessName` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jobNameFormat` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defaultZone` tinyint(3) unsigned NOT NULL DEFAULT '54',
  `hasCadastral` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_system_settings`
--

INSERT INTO `time_system_settings` (`systemSettingsId`, `businessName`, `jobNameFormat`, `defaultZone`, `hasCadastral`) VALUES
(1, 'Improved Timesheets', NULL, 55, 1);

-- --------------------------------------------------------

--
-- Table structure for table `time_timesheet_entry`
--

CREATE TABLE IF NOT EXISTS `time_timesheet_entry` (
`timesheetEntryId` int(10) unsigned NOT NULL,
  `jobId` mediumint(8) unsigned NOT NULL,
  `userId` smallint(5) unsigned NOT NULL,
  `timesheetTaskId` smallint(5) unsigned NOT NULL,
  `startDateTime` datetime NOT NULL,
  `endDateTime` datetime NOT NULL,
  `totalHours` decimal(6,2) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `disbursement` decimal(7,2) DEFAULT '0.00',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `writtenOff` tinyint(1) NOT NULL DEFAULT '0',
  `invoiced` tinyint(1) NOT NULL DEFAULT '0',
  `mockInvoiceId` mediumint(8) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_timesheet_entry`
--

INSERT INTO `time_timesheet_entry` (`timesheetEntryId`, `jobId`, `userId`, `timesheetTaskId`, `startDateTime`, `endDateTime`, `totalHours`, `comment`, `disbursement`, `archived`, `writtenOff`, `invoiced`, `mockInvoiceId`) VALUES
(50, 1, 2, 1, '2016-10-01 11:15:00', '2016-10-01 11:45:00', '0.50', 'Comment\n\nTwo lines down', '23.45', 0, 0, 0, NULL),
(52, 1, 2, 1, '2016-10-01 12:00:00', '2016-10-01 14:30:00', '2.50', 'Comment\n\nTwo lines down', '23.45', 0, 0, 0, NULL),
(53, 1, 2, 1, '2016-10-01 06:45:00', '2016-10-01 09:15:00', '2.50', 'Comment\n\nTwo lines down', '23.45', 0, 0, 0, NULL),
(55, 1, 2, 1, '2016-10-01 06:15:00', '2016-10-01 07:15:00', '1.00', '', '0.00', 0, 0, 0, NULL),
(56, 1, 2, 3, '2016-10-01 09:30:00', '2016-10-01 11:00:00', '1.50', '', '0.00', 0, 0, 0, NULL),
(57, 1, 2, 4, '2016-10-01 14:30:00', '2016-10-01 15:30:00', '1.00', '', '0.00', 0, 0, 0, NULL),
(58, 1, 1, 21, '2016-10-31 06:00:00', '2016-10-31 06:15:00', '0.25', '', '0.00', 0, 0, 0, NULL),
(59, 11, 1, 21, '2016-11-04 06:00:00', '2016-11-04 06:15:00', '0.25', '', '0.00', 0, 0, 0, NULL),
(61, 11, 1, 22, '2016-11-06 06:00:00', '2016-11-06 07:00:00', '1.00', 'sad', '0.00', 0, 0, 0, NULL),
(62, 37, 1, 22, '2016-11-07 06:00:00', '2016-11-07 06:15:00', '0.25', '', '0.00', 0, 0, 0, NULL),
(63, 1, 1, 21, '2016-11-09 06:00:00', '2016-11-09 06:15:00', '0.25', '', '0.00', 0, 0, 0, NULL),
(64, 1, 1, 4, '2016-11-11 06:00:00', '2016-11-11 06:15:00', '0.25', '', '0.00', 0, 0, 0, NULL),
(65, 11, 1, 1, '2016-11-11 23:00:00', '2016-11-12 00:00:00', '1.00', '', '0.00', 0, 0, 0, NULL),
(66, 1, 1, 21, '2016-11-13 06:00:00', '2016-11-13 07:15:00', '1.25', 'hellow comment', '5.00', 0, 0, 0, 25),
(67, 3, 1, 50, '2016-11-23 06:00:00', '2016-11-23 07:00:00', '1.00', '', '0.00', 0, 0, 0, NULL),
(68, 3, 1, 13, '2016-11-23 07:15:00', '2016-11-23 07:30:00', '0.25', '', '0.00', 0, 0, 0, NULL),
(69, 12, 1, 15, '2016-11-23 07:00:00', '2016-11-23 07:15:00', '0.25', '', '0.00', 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `time_timesheet_multiplier`
--

CREATE TABLE IF NOT EXISTS `time_timesheet_multiplier` (
`timesheetMultiplierId` smallint(5) unsigned NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` double NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_timesheet_multiplier`
--

INSERT INTO `time_timesheet_multiplier` (`timesheetMultiplierId`, `name`, `description`, `type`, `value`) VALUES
(8, 'gifter', 'bonus', 'hourly', 55);

-- --------------------------------------------------------

--
-- Table structure for table `time_timesheet_settings`
--

CREATE TABLE IF NOT EXISTS `time_timesheet_settings` (
`timesheetSettingsId` tinyint(3) unsigned NOT NULL,
  `multiDaySelectDisabled` tinyint(1) NOT NULL DEFAULT '1',
  `timeFormat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'h(:mm)a',
  `businessHoursStart` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '08:30',
  `businessHoursEnd` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '17:30',
  `businessDaysOfWeek` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1,2,3,4,5',
  `slotDuration` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00:15:00',
  `slotLabelInterval` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00:30:00',
  `defaultView` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'agendaDay',
  `scrollTime` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '06:00:00',
  `weekViewColumnFormat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ddd D/MM',
  `dayViewColumnFormat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dddd D/MM'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_timesheet_settings`
--

INSERT INTO `time_timesheet_settings` (`timesheetSettingsId`, `multiDaySelectDisabled`, `timeFormat`, `businessHoursStart`, `businessHoursEnd`, `businessDaysOfWeek`, `slotDuration`, `slotLabelInterval`, `defaultView`, `scrollTime`, `weekViewColumnFormat`, `dayViewColumnFormat`) VALUES
(1, 1, 'h(:mm)a', '08:30', '17:30', '1,2,3,4,5', '00:15:00', '00:30:00', 'agendaDay', '06:00:00', 'ddd D/MM', 'dddd D/MM');

-- --------------------------------------------------------

--
-- Table structure for table `time_timesheet_task`
--

CREATE TABLE IF NOT EXISTS `time_timesheet_task` (
`timesheetTaskId` smallint(5) unsigned NOT NULL,
  `timesheetTaskGroupId` smallint(5) unsigned NOT NULL,
  `taskName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taskDescription` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chargeable` tinyint(1) NOT NULL DEFAULT '0',
  `hiddenReports` tinyint(1) NOT NULL DEFAULT '0',
  `timeTaken` tinyint(3) unsigned NOT NULL DEFAULT '60',
  `createButton` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `displayOrder` smallint(5) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_timesheet_task`
--

INSERT INTO `time_timesheet_task` (`timesheetTaskId`, `timesheetTaskGroupId`, `taskName`, `taskDescription`, `chargeable`, `hiddenReports`, `timeTaken`, `createButton`, `color`, `active`, `displayOrder`) VALUES
(1, 1, 'Accommodation', NULL, 1, 0, 60, 0, NULL, 1, 1),
(2, 1, 'Meals', NULL, 1, 0, 60, 0, NULL, 1, 2),
(3, 2, 'Staff Trainings', '', 1, 0, 60, 0, '#000000', 0, 2),
(4, 2, 'Administrationsssss', '', 1, 0, 60, 1, '', 1, 3),
(11, 8, '3 task', '', 1, 1, 60, 0, '#2a0d0d', 1, 2),
(13, 8, '2 task', '', 1, 1, 60, 0, '#d5448e', 1, 1),
(15, 8, '4Task', '', 1, 1, 60, 0, '#2f1c8b', 1, 3),
(21, 18, 'qwe2', '', 1, 0, 60, 0, '#da1016', 1, 1),
(22, 18, 'qweqweqew', '', 1, 0, 60, 0, '#da1016', 1, 2),
(33, 28, '234345', '', 1, 0, 60, 0, '#380b0b', 1, 2),
(36, 28, '456456w', '', 1, 0, 60, 0, '#db425d', 1, 1),
(37, 7, 'asdasd1', '', 1, 0, 60, 0, '#d92525', 1, 2),
(39, 7, 'qweqweqwe', '', 1, 0, 60, 0, '', 1, 1),
(42, 8, 'we', '', 1, 0, 60, 0, '', 1, 5),
(43, 8, 'qweqweqwe', '', 1, 1, 60, 1, '#632222', 1, 4),
(45, 20, 'asdasd', '', 0, 1, 60, 1, '#802828', 0, 1),
(50, 2, 'qweqwe', '', 1, 0, 60, 0, '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `time_timesheet_taskgroup`
--

CREATE TABLE IF NOT EXISTS `time_timesheet_taskgroup` (
`timesheetTaskGroupId` smallint(5) unsigned NOT NULL,
  `groupName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupColor` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `displayOrder` smallint(6) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_timesheet_taskgroup`
--

INSERT INTO `time_timesheet_taskgroup` (`timesheetTaskGroupId`, `groupName`, `groupColor`, `displayOrder`) VALUES
(1, 'Disbursementss', '#8b8794', 5),
(2, 'General Nones', '#190404', 4),
(3, 'Leaves', '#56a811', 6),
(7, 'Detail Design', '#2e1414', 7),
(8, 'Project Management', '#00105b', 1),
(18, 'Red Cross', '#da1016', 3),
(20, 'testing ulit', '#311493', 8),
(22, 'ssss', '#cccccc', 2),
(23, 'qweasdadsads', '#cccccc', 10),
(24, 'asdsd', '#cccccc', 9);

-- --------------------------------------------------------

--
-- Table structure for table `time_user`
--

CREATE TABLE IF NOT EXISTS `time_user` (
`userId` smallint(5) unsigned NOT NULL,
  `firstName` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initials` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chargeRate` decimal(6,2) NOT NULL DEFAULT '0.00',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `accessLevel` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_user`
--

INSERT INTO `time_user` (`userId`, `firstName`, `surname`, `initials`, `username`, `password`, `email`, `chargeRate`, `active`, `accessLevel`) VALUES
(1, 'John Gifter', 'Poja', 'C', 'gifter', '744097b3d574de803114a9f6c5d905d9faaf33f08d6d1fb6f1128ee78599ae00de4a8d8377dbd2e099b594a211f7e61a578e8fb3fb0dc58070dfe5fa7825de60', 'gifterp@gmail.com', '0.00', 1, 99),
(2, 'Matt', 'Batten', 'MB', 'mbatten', '15755b5c360302cebac68ea8b3f4083e7d0099a5e9c2e3cdf2f3ba164958f468c35fc10901c25306576abe6b41df871a013c89a54a286ff04a06f5a667917456', 'matt@improvedsoftware.com.au', '0.00', 1, 99),
(3, 'Qwe1', 'Qwe', 'A', 'qwe', '629a9d63e7738f02f220b4085030126078e90ba25a3443706d99b58415d18dc9aff6224d3aba11cd74fda5ff07a0f9037a6994514159f81ae8c6a27e9e0f2b4e', 'gmail@gmail.com', '1.00', 1, 0),
(4, 'Zoe Chanelle', 'Poja', 'Chaz', 'zoe', '4e2028f56355ebdd3515ec38bf94e6dbdfde23fbf3dbec35cd91a71b410960fc9d13119449d5eb977f71c78b18898340fc343339a92ac9973e9202d9cbff3f2f', NULL, '1.00', 1, 0),
(6, 'B', 'B', 'B', 'a', 'fd30700b21ec30d06b82ccdeaa5f4595f7ffd68526ba701a012265b05cb615d3f53e1932b1d9effd1b4066c302356df7939bcf924378efa8bb76a671d3698952', NULL, '1.00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `time_user_savedjob`
--

CREATE TABLE IF NOT EXISTS `time_user_savedjob` (
`userSavedJobId` mediumint(8) unsigned NOT NULL,
  `jobId` mediumint(8) unsigned NOT NULL,
  `userId` mediumint(8) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_user_savedjob`
--

INSERT INTO `time_user_savedjob` (`userSavedJobId`, `jobId`, `userId`) VALUES
(1, 1, 2),
(2, 1, 2),
(3, 1, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `time_cadastral`
--
ALTER TABLE `time_cadastral`
 ADD PRIMARY KEY (`cadastralId`);

--
-- Indexes for table `time_checklist`
--
ALTER TABLE `time_checklist`
 ADD PRIMARY KEY (`checklistId`);

--
-- Indexes for table `time_checklist_item`
--
ALTER TABLE `time_checklist_item`
 ADD PRIMARY KEY (`checklistItemId`);

--
-- Indexes for table `time_contactlink`
--
ALTER TABLE `time_contactlink`
 ADD PRIMARY KEY (`contactLinkId`);

--
-- Indexes for table `time_contactperson`
--
ALTER TABLE `time_contactperson`
 ADD PRIMARY KEY (`contactPersonId`);

--
-- Indexes for table `time_council`
--
ALTER TABLE `time_council`
 ADD PRIMARY KEY (`councilId`);

--
-- Indexes for table `time_customer`
--
ALTER TABLE `time_customer`
 ADD PRIMARY KEY (`customerId`);

--
-- Indexes for table `time_department`
--
ALTER TABLE `time_department`
 ADD PRIMARY KEY (`departmentId`);

--
-- Indexes for table `time_job`
--
ALTER TABLE `time_job`
 ADD PRIMARY KEY (`jobId`);

--
-- Indexes for table `time_jobnote`
--
ALTER TABLE `time_jobnote`
 ADD PRIMARY KEY (`jobNoteId`);

--
-- Indexes for table `time_jobtype`
--
ALTER TABLE `time_jobtype`
 ADD PRIMARY KEY (`jobTypeId`);

--
-- Indexes for table `time_job_checklist`
--
ALTER TABLE `time_job_checklist`
 ADD PRIMARY KEY (`jobChecklistId`);

--
-- Indexes for table `time_job_checklist_data`
--
ALTER TABLE `time_job_checklist_data`
 ADD PRIMARY KEY (`jobChecklistDataId`);

--
-- Indexes for table `time_job_customfields`
--
ALTER TABLE `time_job_customfields`
 ADD PRIMARY KEY (`jobCustomFieldsId`);

--
-- Indexes for table `time_job_invoice`
--
ALTER TABLE `time_job_invoice`
 ADD PRIMARY KEY (`invoiceId`);

--
-- Indexes for table `time_mail`
--
ALTER TABLE `time_mail`
 ADD PRIMARY KEY (`mailId`);

--
-- Indexes for table `time_mockinvoice`
--
ALTER TABLE `time_mockinvoice`
 ADD PRIMARY KEY (`mockInvoiceId`);

--
-- Indexes for table `time_mockinvoice_category`
--
ALTER TABLE `time_mockinvoice_category`
 ADD PRIMARY KEY (`mockInvoiceCategoryId`);

--
-- Indexes for table `time_mockinvoice_description`
--
ALTER TABLE `time_mockinvoice_description`
 ADD PRIMARY KEY (`mockInvoiceDescriptionId`);

--
-- Indexes for table `time_mockinvoice_row`
--
ALTER TABLE `time_mockinvoice_row`
 ADD PRIMARY KEY (`mockInvoiceRowId`);

--
-- Indexes for table `time_system_settings`
--
ALTER TABLE `time_system_settings`
 ADD PRIMARY KEY (`systemSettingsId`);

--
-- Indexes for table `time_timesheet_entry`
--
ALTER TABLE `time_timesheet_entry`
 ADD PRIMARY KEY (`timesheetEntryId`);

--
-- Indexes for table `time_timesheet_multiplier`
--
ALTER TABLE `time_timesheet_multiplier`
 ADD PRIMARY KEY (`timesheetMultiplierId`);

--
-- Indexes for table `time_timesheet_settings`
--
ALTER TABLE `time_timesheet_settings`
 ADD PRIMARY KEY (`timesheetSettingsId`);

--
-- Indexes for table `time_timesheet_task`
--
ALTER TABLE `time_timesheet_task`
 ADD PRIMARY KEY (`timesheetTaskId`);

--
-- Indexes for table `time_timesheet_taskgroup`
--
ALTER TABLE `time_timesheet_taskgroup`
 ADD PRIMARY KEY (`timesheetTaskGroupId`);

--
-- Indexes for table `time_user`
--
ALTER TABLE `time_user`
 ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `time_user_savedjob`
--
ALTER TABLE `time_user_savedjob`
 ADD PRIMARY KEY (`userSavedJobId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `time_cadastral`
--
ALTER TABLE `time_cadastral`
MODIFY `cadastralId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `time_checklist`
--
ALTER TABLE `time_checklist`
MODIFY `checklistId` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `time_checklist_item`
--
ALTER TABLE `time_checklist_item`
MODIFY `checklistItemId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `time_contactlink`
--
ALTER TABLE `time_contactlink`
MODIFY `contactLinkId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `time_contactperson`
--
ALTER TABLE `time_contactperson`
MODIFY `contactPersonId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `time_council`
--
ALTER TABLE `time_council`
MODIFY `councilId` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `time_customer`
--
ALTER TABLE `time_customer`
MODIFY `customerId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `time_department`
--
ALTER TABLE `time_department`
MODIFY `departmentId` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `time_job`
--
ALTER TABLE `time_job`
MODIFY `jobId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `time_jobnote`
--
ALTER TABLE `time_jobnote`
MODIFY `jobNoteId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `time_jobtype`
--
ALTER TABLE `time_jobtype`
MODIFY `jobTypeId` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `time_job_checklist`
--
ALTER TABLE `time_job_checklist`
MODIFY `jobChecklistId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `time_job_checklist_data`
--
ALTER TABLE `time_job_checklist_data`
MODIFY `jobChecklistDataId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `time_job_customfields`
--
ALTER TABLE `time_job_customfields`
MODIFY `jobCustomFieldsId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `time_job_invoice`
--
ALTER TABLE `time_job_invoice`
MODIFY `invoiceId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `time_mail`
--
ALTER TABLE `time_mail`
MODIFY `mailId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `time_mockinvoice`
--
ALTER TABLE `time_mockinvoice`
MODIFY `mockInvoiceId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `time_mockinvoice_category`
--
ALTER TABLE `time_mockinvoice_category`
MODIFY `mockInvoiceCategoryId` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `time_mockinvoice_description`
--
ALTER TABLE `time_mockinvoice_description`
MODIFY `mockInvoiceDescriptionId` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `time_mockinvoice_row`
--
ALTER TABLE `time_mockinvoice_row`
MODIFY `mockInvoiceRowId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `time_system_settings`
--
ALTER TABLE `time_system_settings`
MODIFY `systemSettingsId` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `time_timesheet_entry`
--
ALTER TABLE `time_timesheet_entry`
MODIFY `timesheetEntryId` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `time_timesheet_multiplier`
--
ALTER TABLE `time_timesheet_multiplier`
MODIFY `timesheetMultiplierId` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `time_timesheet_settings`
--
ALTER TABLE `time_timesheet_settings`
MODIFY `timesheetSettingsId` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `time_timesheet_task`
--
ALTER TABLE `time_timesheet_task`
MODIFY `timesheetTaskId` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `time_timesheet_taskgroup`
--
ALTER TABLE `time_timesheet_taskgroup`
MODIFY `timesheetTaskGroupId` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `time_user`
--
ALTER TABLE `time_user`
MODIFY `userId` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `time_user_savedjob`
--
ALTER TABLE `time_user_savedjob`
MODIFY `userSavedJobId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
