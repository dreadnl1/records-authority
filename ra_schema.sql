-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 20, 2011 at 03:26 PM
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
-- Database: `rmTestDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `rm_associatedUnits`
--

DROP TABLE IF EXISTS `rm_associatedUnits`;
CREATE TABLE IF NOT EXISTS `rm_associatedUnits` (
  `associatedUnitsID` int(11) NOT NULL AUTO_INCREMENT,
  `retentionScheduleID` int(11) NOT NULL,
  `departmentID` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`associatedUnitsID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1109 ;

--
-- Dumping data for table `rm_associatedUnits`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_associatedUnits_temp`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `rm_audit`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_departmentContacts`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `rm_departmentContacts`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_departments`
--

DROP TABLE IF EXISTS `rm_departments`;
CREATE TABLE IF NOT EXISTS `rm_departments` (
  `departmentID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `divisionID` int(11) NOT NULL,
  `departmentName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`departmentID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1001 ;

--
-- Dumping data for table `rm_departments`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_disposition`
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


-- --------------------------------------------------------

--
-- Table structure for table `rm_divisions`
--

DROP TABLE IF EXISTS `rm_divisions`;
CREATE TABLE IF NOT EXISTS `rm_divisions` (
  `divisionID` int(11) NOT NULL AUTO_INCREMENT,
  `divisionName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`divisionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1001 ;

--
-- Dumping data for table `rm_divisions`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_docTypes`
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


-- --------------------------------------------------------

--
-- Table structure for table `rm_fieldTypes`
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


-- --------------------------------------------------------

--
-- Table structure for table `rm_fullTextSearch`
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
  `retentionPeriod` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `retentionNotes` text COLLATE utf8_unicode_ci,
  `disposition` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `officeOfPrimaryResponsibility` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approvedByCounselDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`searchID`),
  FULLTEXT KEY `rm_fullText` (`recordName`,`recordDescription`,`retentionPeriod`,`disposition`,`officeOfPrimaryResponsibility`,`approvedByCounselDate`,`recordCategory`,`recordCode`,`keywords`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=194 ;

--
-- Dumping data for table `rm_fullTextSearch`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_recordCategories`
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


-- --------------------------------------------------------

--
-- Table structure for table `rm_recordType`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=129 ;

--
-- Dumping data for table `rm_recordType`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_recordTypeDeleted`
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
-- Table structure for table `rm_recordTypeFormatAndLocation`
--

DROP TABLE IF EXISTS `rm_recordTypeFormatAndLocation`;
CREATE TABLE IF NOT EXISTS `rm_recordTypeFormatAndLocation` (
  `formatAndLocationID` int(11) NOT NULL AUTO_INCREMENT,
  `recordTypeDepartment` int(11) NOT NULL,
  `electronicRecord` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `system` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherText` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paperVersion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherBuilding` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherStorage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `finalRecordExist` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `backupMedia` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `recordLocation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherRecordLocation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fileFormat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `formatAndLocationDeptAnswer` text COLLATE utf8_unicode_ci,
  `formatAndLocationRmNotes` text COLLATE utf8_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `recordInformationID` int(11) DEFAULT NULL,
  PRIMARY KEY (`formatAndLocationID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=46 ;

--
-- Dumping data for table `rm_recordTypeFormatAndLocation`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_recordTypeManagement`
--

DROP TABLE IF EXISTS `rm_recordTypeManagement`;
CREATE TABLE IF NOT EXISTS `rm_recordTypeManagement` (
  `managementID` int(11) NOT NULL AUTO_INCREMENT,
  `recordTypeDepartment` int(11) NOT NULL,
  `accessAndUseDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `accessAndUseRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `yearsActive` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `yearsAvailable` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activePeriodDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `activePeriodRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `yearsKept` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `retentionPeriodDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `retentionPeriodRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `managementDivisionID` int(11) NOT NULL,
  `managementDepartmentID` int(11) NOT NULL,
  `custodianDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `custodianRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `legislationGovernRecords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `legislation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `legislationHowLong` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `legalRequirmentsDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `legalRequirmentsRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `standardsAndBestPracticesDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `standardsAndBestPracticesRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `destroyRecords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `howOftenDestruction` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `howAreRecordsDestroyed` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `destructionDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `destructionRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `transferToArchives` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `howOftenArchive` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transferToArchivesDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `transferToArchivesRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `vitalRecords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `manageVitalRecords` text COLLATE utf8_unicode_ci NOT NULL,
  `vitalRecordsDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `vitalRecordsRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `sensitiveInformation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `describeInformation` text COLLATE utf8_unicode_ci NOT NULL,
  `sensitiveInformationDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `sensitiveInformationRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `secureRecords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `describeSecurityRecords` text COLLATE utf8_unicode_ci NOT NULL,
  `securityDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `securityRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `duplication` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duplicationDivisionID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duplicationDepartmentID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `masterCopyDivisionID` int(11) NOT NULL,
  `masterCopyDepartmentID` int(11) NOT NULL,
  `duplicationDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `duplicationRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `recordInformationID` int(11) DEFAULT NULL,
  PRIMARY KEY (`managementID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=108 ;

--
-- Dumping data for table `rm_recordTypeManagement`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_recordTypeRecordInformation`
--

DROP TABLE IF EXISTS `rm_recordTypeRecordInformation`;
CREATE TABLE IF NOT EXISTS `rm_recordTypeRecordInformation` (
  `recordInformationID` int(11) NOT NULL AUTO_INCREMENT,
  `recordTypeDepartment` int(11) NOT NULL,
  `recordInformationDivisionID` int(11) NOT NULL,
  `recordName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `recordDescription` text COLLATE utf8_unicode_ci NOT NULL,
  `recordCategory` text COLLATE utf8_unicode_ci NOT NULL,
  `recordNotesDeptAnswer` text COLLATE utf8_unicode_ci NOT NULL,
  `recordNotesRmNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`recordInformationID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=129 ;

--
-- Dumping data for table `rm_recordTypeRecordInformation`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_retentionSchedule`
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
  FULLTEXT KEY `rm_retentionSchedule` (`uuid`,`recordName`,`recordDescription`,`recordCategory`,`retentionPeriod`,`primaryAuthorityRetention`,`retentionNotes`,`retentionDecisions`,`disposition`,`primaryAuthority`,`notes`,`vitalRecord`,`approvedByCounsel`,`approvedByCounselDate`,`recordCode`,`keywords`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=201 ;

--
-- Dumping data for table `rm_retentionSchedule`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_retentionScheduleDeleted`
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
  `disposition` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `primaryAuthority` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `officeOfPrimaryResponsibility` int(11) NOT NULL,
  `relatedAuthorities` text COLLATE utf8_unicode_ci NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `vitalRecord` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `approvedByCounsel` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `approvedByCounselDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updateTimestamp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updateUser` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`retentionScheduleID`),
  FULLTEXT KEY `uuid` (`uuid`,`recordName`,`recordDescription`,`recordCategory`,`retentionPeriod`,`primaryAuthorityRetention`,`retentionNotes`,`retentionDecisions`,`disposition`,`primaryAuthority`,`notes`,`vitalRecord`,`approvedByCounsel`,`approvedByCounselDate`,`recordCode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=199 ;

--
-- Dumping data for table `rm_retentionScheduleDeleted`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_rsRelatedAuthorities`
--

DROP TABLE IF EXISTS `rm_rsRelatedAuthorities`;
CREATE TABLE IF NOT EXISTS `rm_rsRelatedAuthorities` (
  `rsRelatedAuthorityID` int(11) NOT NULL AUTO_INCREMENT,
  `retentionScheduleID` int(11) NOT NULL,
  `rsRelatedAuthority` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `rsRelatedAuthorityRetention` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rsRelatedAuthorityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `rm_rsRelatedAuthorities`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_sessions`
--

DROP TABLE IF EXISTS `rm_sessions`;
CREATE TABLE IF NOT EXISTS `rm_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rm_sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyContactFields`
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


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyContactNotes`
--

DROP TABLE IF EXISTS `rm_surveyContactNotes`;
CREATE TABLE IF NOT EXISTS `rm_surveyContactNotes` (
  `contactNotesID` int(11) NOT NULL AUTO_INCREMENT,
  `contactID` int(11) NOT NULL,
  `contactNotes` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contactNotesID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rm_surveyContactNotes`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyContactQuestions`
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


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyContactResponses`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=61 ;

--
-- Dumping data for table `rm_surveyContactResponses`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyNotes`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=53 ;

--
-- Dumping data for table `rm_surveyNotes`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyQuestionResponses`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=66 ;

--
-- Dumping data for table `rm_surveyQuestionResponses`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveyQuestions`
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


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveys`
--

DROP TABLE IF EXISTS `rm_surveys`;
CREATE TABLE IF NOT EXISTS `rm_surveys` (
  `surveyID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `surveyName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surveyDescription` text COLLATE utf8_unicode_ci NOT NULL,
  `surveyUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`surveyID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `rm_surveys`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveySubChoiceQuestionResponses`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `rm_surveySubChoiceQuestionResponses`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveySubChoiceQuestions`
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


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveySubQuestionResponses`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=60 ;

--
-- Dumping data for table `rm_surveySubQuestionResponses`
--


-- --------------------------------------------------------

--
-- Table structure for table `rm_surveySubQuestions`
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


-- --------------------------------------------------------

--
-- Table structure for table `rm_users`
--

DROP TABLE IF EXISTS `rm_users`;
CREATE TABLE IF NOT EXISTS `rm_users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `rm_users`
--

INSERT DELAYED IGNORE INTO `rm_users` (`userID`, `username`, `passcode`, `timestamp`) VALUES
(1, 'admin', 'feebc824cc06f056df9d1877c8686ff483b3eb26', '2010-04-14 15:23:52');
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
