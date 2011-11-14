-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 03, 2011 at 10:13 AM
-- Server version: 5.1.44
-- PHP Version: 5.3.2

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `recordsManagementDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `rm_associatedUnits`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_associatedUnits`;
CREATE TABLE IF NOT EXISTS `rm_associatedUnits` (
  `associatedUnitsID` int(11) NOT NULL AUTO_INCREMENT,
  `retentionScheduleID` int(11) NOT NULL,
  `departmentID` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`associatedUnitsID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_associatedUnits`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_associatedUnits_temp`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_associatedUnits_temp`;
CREATE TABLE IF NOT EXISTS `rm_associatedUnits_temp` (
  `tempID` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `divisionID` int(11) NOT NULL,
  `departmentID` int(11) NOT NULL,
  `isOfficeOfPrimaryResponsibility` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tempID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_associatedUnits_temp`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_audit`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_audit`;
CREATE TABLE IF NOT EXISTS `rm_audit` (
  `auditID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `updateDate` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `previousData` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `currentData` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`auditID`),
  FULLTEXT KEY `search` (`username`,`updateDate`,`previousData`,`currentData`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_audit`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_departmentContacts`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_departmentContacts`;
CREATE TABLE IF NOT EXISTS `rm_departmentContacts` (
  `contactID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `jobTitle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `departmentID` int(11) NOT NULL,
  `phoneNumber` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `emailAddress` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `submitDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contactID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_departmentContacts`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_departments`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_departments`;
CREATE TABLE IF NOT EXISTS `rm_departments` (
  `departmentID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `divisionID` int(11) NOT NULL,
  `departmentName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`departmentID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1005 ;

--
-- Dumping data for table `rm_departments`
--

INSERT DELAYED IGNORE INTO `rm_departments` (`departmentID`, `divisionID`, `departmentName`, `timestamp`) VALUES
(1000, 1000, 'Default Department', '2011-02-25 15:55:41');

-- --------------------------------------------------------

--
-- Table structure for table `rm_disposition`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_disposition`;
CREATE TABLE IF NOT EXISTS `rm_disposition` (
  `dispositionID` int(11) NOT NULL AUTO_INCREMENT,
  `dispositionShort` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `dispositionLong` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`dispositionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `rm_disposition`
--

INSERT DELAYED IGNORE INTO `rm_disposition` (`dispositionID`, `dispositionShort`, `dispositionLong`, `description`, `timestamp`) VALUES
(1, 'D', 'Destroy', 'At the end of the retention period, these records must be securely destroyed.  Paper records must be shredded by an approved shredding vendor or an approved University-owned shredding equipment.  Other physical media must be securely destroyed and disposed of of in a manner that prevents the data on it from being recovered.  All instances of electronic records must be permanently deleted. ', '2009-03-10 08:32:28'),
(2, 'R', 'Recycle', 'These records are not confidential and do not have to be shredded.  Please recycle theses records when no longer needed.  All instances of electronic records must be deleted.', '2009-03-10 08:32:54'),
(3, 'P', 'Permanent', 'These records are permanent University records.  At the end of the retention period, custodianship of permanent records is transferred to the university Archives to ensure long-term preservation.', '2009-03-10 08:33:30'),
(4, 'N/A', 'Not applicable', '', '2009-03-10 08:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `rm_divisions`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_divisions`;
CREATE TABLE IF NOT EXISTS `rm_divisions` (
  `divisionID` int(11) NOT NULL AUTO_INCREMENT,
  `divisionName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`divisionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1004 ;

--
-- Dumping data for table `rm_divisions`
--

INSERT DELAYED IGNORE INTO `rm_divisions` (`divisionID`, `divisionName`, `timestamp`) VALUES
(1000, 'Default Division', '2011-02-25 15:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `rm_docTypes`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_docTypes`;
CREATE TABLE IF NOT EXISTS `rm_docTypes` (
  `docTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `docType` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`docTypeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `rm_docTypes`
--

INSERT DELAYED IGNORE INTO `rm_docTypes` (`docTypeID`, `docType`, `timestamp`) VALUES
(1, 'pdf', '2009-02-18 16:35:09'),
(2, 'docx', '2009-02-18 16:35:16'),
(3, 'doc', '2009-02-18 16:35:22'),
(4, 'txt', '2009-02-18 16:35:26'),
(5, 'gif', '2009-02-18 16:35:31'),
(6, 'jpg', '2009-02-18 16:35:37'),
(7, 'jpeg', '2009-02-18 16:35:42'),
(8, 'vsd', '2009-02-18 16:35:48'),
(9, 'xls', '2009-02-18 16:35:53'),
(10, 'xlsx', '2009-02-18 16:35:58'),
(11, 'ppt', '2009-02-18 16:36:02'),
(12, 'pptx', '2009-02-18 16:36:13'),
(20, 'vdx', '2009-09-10 14:10:58'),
(14, 'tiff', '2009-02-18 16:36:23'),
(15, 'tif', '2009-02-18 16:36:29'),
(16, 'pdf/a', '2009-04-15 14:47:43'),
(17, 'rtf', '2009-06-29 16:09:38');

-- --------------------------------------------------------

--
-- Table structure for table `rm_fieldTypes`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_fieldTypes`;
CREATE TABLE IF NOT EXISTS `rm_fieldTypes` (
  `fieldTypeID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `fieldType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fieldTypeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `rm_fieldTypes`
--

INSERT DELAYED IGNORE INTO `rm_fieldTypes` (`fieldTypeID`, `fieldType`, `timestamp`) VALUES
(1, 'text', '2008-08-04 13:26:22'),
(2, 'checkbox', '2008-08-04 13:26:32'),
(3, 'radio', '2008-08-04 13:26:39'),
(4, 'textarea', '2008-08-22 13:15:31'),
(5, 'file', '2008-08-22 14:07:00');

-- --------------------------------------------------------

--
-- Table structure for table `rm_fullTextSearch`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_fullTextSearch`;
CREATE TABLE IF NOT EXISTS `rm_fullTextSearch` (
  `searchID` int(11) NOT NULL AUTO_INCREMENT,
  `retentionScheduleID` int(11) DEFAULT NULL,
  `recordCode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recordName` varchar(510) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recordDescription` text COLLATE utf8_unicode_ci,
  `recordCategory` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `retentionPeriod` text COLLATE utf8_unicode_ci,
  `retentionNotes` text COLLATE utf8_unicode_ci,
  `disposition` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `officeOfPrimaryResponsibility` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approvedByCounselDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`searchID`),
  FULLTEXT KEY `rm_fullText` (`recordName`,`recordDescription`,`retentionPeriod`,`disposition`,`officeOfPrimaryResponsibility`,`approvedByCounselDate`,`recordCategory`,`recordCode`,`keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_fullTextSearch`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_recordCategories`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_recordCategories`;
CREATE TABLE IF NOT EXISTS `rm_recordCategories` (
  `recordCategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `recordCategory` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`recordCategoryID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `rm_recordCategories`
--

INSERT DELAYED IGNORE INTO `rm_recordCategories` (`recordCategoryID`, `recordCategory`, `timestamp`) VALUES
(1, 'Default Category', '2011-08-01 11:25:30');

-- --------------------------------------------------------

--
-- Table structure for table `rm_recordType`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_recordType`;
CREATE TABLE IF NOT EXISTS `rm_recordType` (
  `recordInformationID` int(11) NOT NULL AUTO_INCREMENT,
  `recordTypeDepartment` int(11) NOT NULL,
  `recordInformationDivisionID` int(11) NOT NULL,
  `recordName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `recordDescription` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `recordCategory` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `managementDivisionID` int(11) NOT NULL,
  `managementDepartmentID` int(11) NOT NULL,
  `recordNotesDeptAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `recordNotesRmNotes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `recordFormat` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherPhysicalText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherElectronicText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `recordStorage` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherDUBuildingText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherOffsiteStorageText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherElectronicSystemText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `formatAndLocationDeptAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `formatAndLocationRmNotes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `recordRetentionAnswer` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usageNotesAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `retentionAuthoritiesAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `vitalRecord` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `vitalRecordNotesAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `recordRegulations` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `personallyIdentifiableInformationAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `personallyIdentifiableInformationRmNotes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `otherDepartmentCopiesAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updateTimestamp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`recordInformationID`),
  FULLTEXT KEY `recordTypeFT1` (`recordDescription`,`recordNotesDeptAnswer`,`recordNotesRmNotes`,`otherPhysicalText`,`otherElectronicText`,`otherDUBuildingText`,`otherOffsiteStorageText`,`otherElectronicSystemText`,`formatAndLocationDeptAnswer`,`formatAndLocationRmNotes`,`usageNotesAnswer`,`retentionAuthoritiesAnswer`,`vitalRecordNotesAnswer`,`personallyIdentifiableInformationAnswer`,`personallyIdentifiableInformationRmNotes`,`otherDepartmentCopiesAnswer`),
  FULLTEXT KEY `recordTypeFT2` (`recordName`,`recordCategory`,`recordFormat`,`recordStorage`,`vitalRecord`,`recordRegulations`,`recordRetentionAnswer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_recordType`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_recordTypeDeleted`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_recordTypeDeleted`;
CREATE TABLE IF NOT EXISTS `rm_recordTypeDeleted` (
  `recordInformationID` int(11) NOT NULL AUTO_INCREMENT,
  `recordTypeDepartment` int(11) NOT NULL,
  `recordInformationDivisionID` int(11) NOT NULL,
  `recordName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `recordDescription` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `recordCategory` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `managementDivisionID` int(11) NOT NULL,
  `managementDepartmentID` int(11) NOT NULL,
  `recordNotesDeptAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `recordNotesRmNotes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `recordFormat` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherPhysicalText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherElectronicText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `recordStorage` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherDUBuildingText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherOffsiteStorageText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherElectronicSystemText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `formatAndLocationDeptAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `formatAndLocationRmNotes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `recordRetentionAnswer` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usageNotesAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `retentionAuthoritiesAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `vitalRecord` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `vitalRecordNotesAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `recordRegulations` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `personallyIdentifiableInformationAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `personallyIdentifiableInformationRmNotes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `otherDepartmentCopiesAnswer` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updateTimestamp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`recordInformationID`),
  FULLTEXT KEY `recordTypeFT1` (`recordDescription`,`recordNotesDeptAnswer`,`recordNotesRmNotes`,`otherPhysicalText`,`otherElectronicText`,`otherDUBuildingText`,`otherOffsiteStorageText`,`otherElectronicSystemText`,`formatAndLocationDeptAnswer`,`formatAndLocationRmNotes`,`usageNotesAnswer`,`retentionAuthoritiesAnswer`,`vitalRecordNotesAnswer`,`personallyIdentifiableInformationAnswer`,`personallyIdentifiableInformationRmNotes`,`otherDepartmentCopiesAnswer`),
  FULLTEXT KEY `recordTypeFT2` (`recordName`,`recordCategory`,`recordFormat`,`recordStorage`,`vitalRecord`,`recordRegulations`,`recordRetentionAnswer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_recordTypeDeleted`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_retentionSchedule`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_retentionSchedule`;
CREATE TABLE IF NOT EXISTS `rm_retentionSchedule` (
  `retentionScheduleID` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `recordCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `recordName` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `recordDescription` text COLLATE utf8_unicode_ci NOT NULL,
  `recordCategory` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `retentionPeriod` text COLLATE utf8_unicode_ci NOT NULL,
  `primaryAuthorityRetention` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `retentionNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `retentionDecisions` text COLLATE utf8_unicode_ci NOT NULL,
  `disposition` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `primaryAuthority` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `officeOfPrimaryResponsibility` int(11) NOT NULL,
  `override` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `primaryOwnerOverride` text COLLATE utf8_unicode_ci NOT NULL,
  `relatedAuthorities` text COLLATE utf8_unicode_ci NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `vitalRecord` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `approvedByCounsel` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `approvedByCounselDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updateTimestamp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updateUser` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`retentionScheduleID`),
  UNIQUE KEY `recordCode` (`recordCode`),
  FULLTEXT KEY `rm_retentionSchedule` (`uuid`,`recordName`,`recordDescription`,`recordCategory`,`retentionPeriod`,`primaryAuthorityRetention`,`retentionNotes`,`retentionDecisions`,`disposition`,`primaryAuthority`,`notes`,`approvedByCounsel`,`approvedByCounselDate`,`recordCode`,`keywords`,`primaryOwnerOverride`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_retentionSchedule`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_retentionScheduleDeleted`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_retentionScheduleDeleted`;
CREATE TABLE IF NOT EXISTS `rm_retentionScheduleDeleted` (
  `retentionScheduleID` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `recordCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `recordName` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `recordDescription` text COLLATE utf8_unicode_ci NOT NULL,
  `recordCategory` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `retentionPeriod` text COLLATE utf8_unicode_ci NOT NULL,
  `primaryAuthorityRetention` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `retentionNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `retentionDecisions` text COLLATE utf8_unicode_ci NOT NULL,
  `disposition` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `primaryAuthority` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `officeOfPrimaryResponsibility` int(11) NOT NULL,
  `override` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `primaryOwnerOverride` text COLLATE utf8_unicode_ci NOT NULL,
  `relatedAuthorities` text COLLATE utf8_unicode_ci NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `vitalRecord` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `approvedByCounsel` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `approvedByCounselDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updateTimestamp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updateUser` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`retentionScheduleID`),
  FULLTEXT KEY `rm_retentionScheduleDeleted` (`uuid`,`recordName`,`recordDescription`,`recordCategory`,`retentionPeriod`,`primaryAuthorityRetention`,`retentionNotes`,`retentionDecisions`,`disposition`,`primaryAuthority`,`notes`,`approvedByCounsel`,`approvedByCounselDate`,`recordCode`,`keywords`,`primaryOwnerOverride`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_retentionScheduleDeleted`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_rsRelatedAuthorities`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_rsRelatedAuthorities`;
CREATE TABLE IF NOT EXISTS `rm_rsRelatedAuthorities` (
  `rsRelatedAuthorityID` int(11) NOT NULL AUTO_INCREMENT,
  `retentionScheduleID` int(11) NOT NULL,
  `rsRelatedAuthority` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `rsRelatedAuthorityRetention` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rsRelatedAuthorityID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_rsRelatedAuthorities`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_sessions`
--
-- Creation: Oct 03, 2011 at 10:12 AM
-- Last update: Oct 03, 2011 at 10:12 AM
--

DROP TABLE IF EXISTS `rm_sessions`;
CREATE TABLE IF NOT EXISTS `rm_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rm_sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyContactFields`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveyContactFields`;
CREATE TABLE IF NOT EXISTS `rm_surveyContactFields` (
  `contactFieldID` int(11) NOT NULL AUTO_INCREMENT,
  `contactQuestionID` int(11) NOT NULL,
  `contactField` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `fieldTypeID` int(11) NOT NULL,
  `required` tinyint(4) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contactFieldID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `rm_surveyContactFields`
--

INSERT DELAYED IGNORE INTO `rm_surveyContactFields` (`contactFieldID`, `contactQuestionID`, `contactField`, `fieldTypeID`, `required`, `timestamp`) VALUES
(1, 1, 'First Name', 1, 1, '2008-11-10 08:42:49'),
(2, 1, 'Last Name', 1, 1, '2008-11-10 08:42:56'),
(3, 1, 'Phone', 1, 1, '2008-11-10 08:43:03'),
(4, 1, 'Email', 1, 1, '2008-11-10 08:43:09'),
(5, 1, 'What are this contact''s major duties/responsibilities', 4, 1, '2008-11-10 08:43:17'),
(6, 1, 'First Name', 1, 0, '2008-11-10 08:43:24'),
(7, 1, 'Last Name', 1, 0, '2008-11-10 08:43:29'),
(8, 1, 'Phone', 1, 0, '2008-11-10 08:43:48'),
(9, 1, 'Email', 1, 0, '2008-11-10 08:43:55'),
(10, 1, 'What are this contact''s major duties/responsibilities', 4, 0, '2008-11-10 08:44:03'),
(11, 1, 'First Name', 1, 0, '2008-11-10 08:44:10'),
(12, 1, 'Last Name', 1, 0, '2008-11-10 08:44:22'),
(13, 1, 'Phone', 1, 0, '2008-11-10 08:44:27'),
(14, 1, 'Email', 1, 0, '2008-11-10 08:44:31'),
(15, 1, 'What are this contact''s major duties/responsibilities', 4, 0, '2008-11-10 08:44:38');

-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyContactNotes`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveyContactNotes`;
CREATE TABLE IF NOT EXISTS `rm_surveyContactNotes` (
  `contactNotesID` int(11) NOT NULL AUTO_INCREMENT,
  `contactID` int(11) NOT NULL,
  `contactNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contactNotesID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_surveyContactNotes`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyContactQuestions`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveyContactQuestions`;
CREATE TABLE IF NOT EXISTS `rm_surveyContactQuestions` (
  `contactQuestionID` int(11) NOT NULL AUTO_INCREMENT,
  `surveyID` int(11) NOT NULL,
  `questionType` int(11) NOT NULL,
  `contactQuestion` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contactQuestionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rm_surveyContactQuestions`
--

INSERT DELAYED IGNORE INTO `rm_surveyContactQuestions` (`contactQuestionID`, `surveyID`, `questionType`, `contactQuestion`, `timestamp`) VALUES
(1, 1, 4, 'Please list names and email addresses of 1-3 people that the Records Management program could interview for more detailed information about the records in your department. These people should have, among them, contact with or knowledge of most (at least 90%) of the records in your department.', '2008-11-10 08:42:41');

-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyContactResponses`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveyContactResponses`;
CREATE TABLE IF NOT EXISTS `rm_surveyContactResponses` (
  `contactResponseID` int(11) NOT NULL AUTO_INCREMENT,
  `surveyID` int(11) NOT NULL,
  `departmentID` int(11) NOT NULL,
  `contactFieldID` int(11) NOT NULL,
  `contactResponse` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contactResponseID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_surveyContactResponses`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyNotes`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveyNotes`;
CREATE TABLE IF NOT EXISTS `rm_surveyNotes` (
  `surveyNotesID` int(11) NOT NULL AUTO_INCREMENT,
  `departmentID` int(11) NOT NULL,
  `contactID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `deptSurveyNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `rmSurveyNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`surveyNotesID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_surveyNotes`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyQuestionResponses`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveyQuestionResponses`;
CREATE TABLE IF NOT EXISTS `rm_surveyQuestionResponses` (
  `responseID` int(11) NOT NULL AUTO_INCREMENT,
  `contactID` int(11) NOT NULL,
  `surveyID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `response` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`responseID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_surveyQuestionResponses`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyQuestions`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveyQuestions`;
CREATE TABLE IF NOT EXISTS `rm_surveyQuestions` (
  `questionID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `surveyID` int(11) NOT NULL,
  `question` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `fieldTypeID` int(11) NOT NULL,
  `subQuestion` tinyint(1) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `questionType` int(11) NOT NULL,
  `questionOrder` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`questionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `rm_surveyQuestions`
--

INSERT DELAYED IGNORE INTO `rm_surveyQuestions` (`questionID`, `surveyID`, `question`, `fieldTypeID`, `subQuestion`, `required`, `questionType`, `questionOrder`, `timestamp`) VALUES
(1, 1, 'What is the mission or purpose of your department?', 4, 0, 1, 1, 0, '2008-11-10 08:27:02'),
(2, 1, 'If available, please attach a copy of your department''s organizational chart.', 5, 0, 0, 1, 0, '2008-11-10 08:27:35'),
(3, 1, 'Has a departmental history or overview ever been written? ', 0, 1, 1, 2, 0, '2008-11-10 08:28:22'),
(4, 1, 'What are the programs or subdivisions within your department?<br />Example: University Disability Services -- Disability Services Program and Learning Effectiveness Program', 4, 0, 1, 1, 0, '2008-11-10 08:30:04'),
(5, 1, 'Please list any department-wide committees involved in curriculum or policy development.', 4, 0, 0, 1, 0, '2008-11-10 08:30:29'),
(6, 1, 'What is unusual or unique about your department in regards to records?', 4, 0, 0, 1, 0, '2008-11-10 08:30:47'),
(7, 1, 'What records are you most concerned about managing in your department?', 4, 0, 1, 1, 0, '2008-11-10 08:31:39'),
(8, 1, 'Do any of your department''s records fall into the following categories? (Check all that apply)', 0, 1, 1, 3, 0, '2008-11-10 08:32:12'),
(9, 1, 'What electronic systems are used in the department?', 0, 1, 1, 3, 0, '2008-11-10 08:36:11'),
(10, 1, 'Does your department store records outside your department''s main offices? <br />Examples include third-party vendors that store electronic or paper records, and other DU buildings that store paper records.', 0, 1, 1, 2, 0, '2008-11-10 08:39:05'),
(11, 1, 'Do you share records frequently with other departments (besides HR and Purchasing/Financial)?', 0, 1, 1, 2, 0, '2008-11-10 08:40:28'),
(12, 1, 'Do you know of any organizations that issue records retention or compliance guidelines for your field?', 0, 1, 1, 2, 0, '2008-11-10 08:41:26'),
(13, 1, 'Please share any questions, concerns, or additional information you have about records management within your department.', 4, 0, 0, 1, 0, '2008-11-10 08:42:13');

-- --------------------------------------------------------

--
-- Table structure for table `rm_surveys`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveys`;
CREATE TABLE IF NOT EXISTS `rm_surveys` (
  `surveyID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `surveyName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surveyDescription` text COLLATE utf8_unicode_ci NOT NULL,
  `surveyUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`surveyID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `rm_surveys`
--

INSERT DELAYED IGNORE INTO `rm_surveys` (`surveyID`, `surveyName`, `surveyDescription`, `surveyUrl`, `timestamp`) VALUES
(1, 'Departmental Records Management Survey', 'Please take no more than 15 minutes to fill out this survey about the functions and recordkeeping activities of your department.  We are looking for a brief, general overview of your department and its activities, and will be following up with more detailed in-person interviews.  If you are unsure of the answers to any questions, skip the question or answer "no" or "not sure" if a response is required.  \n<br /><br />\nThis survey will help the Records Management program prepare for a detailed inventory of your department''s records. Information collected from this survey and the inventory will be used to prepare a retention schedule for your department''s records.\n<br /><br />\nNote: The questions below apply to all records in your department regardless of format (paper or electronic).\n<br /><br />\nIf you have any questions or technical difficulties with this survey, please contact Joanna Lamb at <a href="mailto:records@du.edu">records@du.edu</a> or x13662.\n', 'http://130.253.139.225/recordsManagement/surveys/departmental-records-management-survey.html', '2008-11-10 08:25:01'),
(4, 'Follow up Departmental Records Management Survey', 'This survey will be sent to contacts provided by the Departmental Records Management Survey. Its purpose is to identify records on an item level that interviewee deals with on a daily basis. Results will be used to pre-populate B forms to use in one-on-one interview.', NULL, '2009-03-05 09:59:45');

-- --------------------------------------------------------

--
-- Table structure for table `rm_surveySubChoiceQuestionResponses`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveySubChoiceQuestionResponses`;
CREATE TABLE IF NOT EXISTS `rm_surveySubChoiceQuestionResponses` (
  `subChoiceResponseID` int(11) NOT NULL AUTO_INCREMENT,
  `surveyID` int(11) NOT NULL,
  `contactID` int(11) NOT NULL,
  `subQuestionID` int(11) DEFAULT NULL,
  `subChoiceQuestionID` int(11) DEFAULT NULL,
  `subChoiceQuestion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `response` text COLLATE utf8_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subChoiceResponseID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_surveySubChoiceQuestionResponses`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveySubChoiceQuestions`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveySubChoiceQuestions`;
CREATE TABLE IF NOT EXISTS `rm_surveySubChoiceQuestions` (
  `subChoiceQuestionID` int(11) NOT NULL AUTO_INCREMENT,
  `subQuestionID` int(11) NOT NULL,
  `subChoiceQuestion` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `fieldTypeID` tinyint(4) NOT NULL,
  `toggle` tinyint(4) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subChoiceQuestionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `rm_surveySubChoiceQuestions`
--

INSERT DELAYED IGNORE INTO `rm_surveySubChoiceQuestions` (`subChoiceQuestionID`, `subQuestionID`, `subChoiceQuestion`, `fieldTypeID`, `toggle`, `timestamp`) VALUES
(1, 6, 'If you know of any relevant legislation, list it here.  Otherwise enter "not sure".', 4, 1, '2008-11-10 08:33:30'),
(3, 14, 'other', 1, 1, '2009-02-12 12:00:33');

-- --------------------------------------------------------

--
-- Table structure for table `rm_surveySubQuestionResponses`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveySubQuestionResponses`;
CREATE TABLE IF NOT EXISTS `rm_surveySubQuestionResponses` (
  `subResponseID` int(11) NOT NULL AUTO_INCREMENT,
  `surveyID` int(11) NOT NULL,
  `contactID` int(11) NOT NULL,
  `questionID` int(11) DEFAULT NULL,
  `subQuestionID` int(11) DEFAULT NULL,
  `subQuestion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `response` text COLLATE utf8_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subResponseID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rm_surveySubQuestionResponses`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveySubQuestions`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_surveySubQuestions`;
CREATE TABLE IF NOT EXISTS `rm_surveySubQuestions` (
  `subQuestionID` int(11) NOT NULL AUTO_INCREMENT,
  `questionID` int(11) NOT NULL,
  `fieldTypeID` int(11) NOT NULL,
  `subQuestion` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `subChoiceQuestionCheck` tinyint(4) NOT NULL,
  `toggle` tinyint(4) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subQuestionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `rm_surveySubQuestions`
--

INSERT DELAYED IGNORE INTO `rm_surveySubQuestions` (`subQuestionID`, `questionID`, `fieldTypeID`, `subQuestion`, `subChoiceQuestionCheck`, `toggle`, `timestamp`) VALUES
(1, 3, 5, 'Please attach.', 0, 1, '2008-11-10 08:29:03'),
(2, 8, 2, 'Records containing personally identifiable information such as SSNs', 0, 0, '2008-11-10 08:33:30'),
(3, 8, 2, 'Student records', 0, 0, '2008-11-10 08:33:52'),
(4, 8, 2, 'Records containing other sensitive or confidential information', 0, 0, '2008-11-10 08:34:07'),
(5, 8, 2, 'Records with restricted access', 0, 0, '2008-11-10 08:34:26'),
(6, 8, 2, 'Records whose retention is governed by legislation', 1, 0, '2008-11-10 08:34:38'),
(7, 8, 2, 'None of the above', 0, 0, '2008-11-10 08:34:52'),
(8, 8, 2, 'Not sure', 0, 0, '2008-11-10 08:35:31'),
(9, 9, 2, 'Banner', 0, 0, '2008-11-10 08:36:28'),
(10, 9, 2, 'Blackboard', 0, 0, '2008-11-10 08:36:40'),
(11, 9, 2, 'Portfolio', 0, 0, '2008-11-10 08:36:56'),
(12, 9, 2, 'Network Drives', 0, 0, '2008-11-10 08:37:08'),
(13, 9, 2, 'VAGA', 0, 0, '2008-11-10 08:37:19'),
(14, 9, 2, 'Other', 1, 0, '2008-11-10 08:37:51'),
(15, 10, 4, 'Where?', 0, 1, '2008-11-10 08:39:23'),
(16, 11, 4, 'Please list departments:', 0, 1, '2008-11-10 08:41:02'),
(17, 12, 4, 'Please list.', 0, 1, '2008-11-10 08:41:52');

-- --------------------------------------------------------

--
-- Table structure for table `rm_users`
--
-- Creation: Oct 03, 2011 at 10:07 AM
-- Last update: Oct 03, 2011 at 10:07 AM
--

DROP TABLE IF EXISTS `rm_users`;
CREATE TABLE IF NOT EXISTS `rm_users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `rm_users`
--

INSERT DELAYED IGNORE INTO `rm_users` (`userID`, `username`, `passcode`, `timestamp`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '2010-04-14 15:23:52');
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
