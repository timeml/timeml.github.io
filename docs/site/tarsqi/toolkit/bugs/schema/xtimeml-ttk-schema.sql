-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: 69.5.6.130
-- Generation Time: Dec 15, 2007 at 11:28 AM
-- Server version: 4.0.27
-- PHP Version: 4.4.4
-- 
-- Database: `xtimeml-ttk`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `bt-bugs`
-- 

CREATE TABLE `bt-bugs` (
  `bugID` int(8) NOT NULL auto_increment,
  `bugName` varchar(255) NOT NULL default '',
  `date` timestamp(14) NOT NULL,
  `status` enum('open','fixed','workaround','re-opened') NOT NULL default 'open',
  `bugType` enum('error','feature_request','coverage','redesign','other') NOT NULL default 'error',
  `component` enum('unknown','gui','preprocessing','gutime','evita','slinket','s2t','blinker','classifier','arglinker','docmodel','utilities','other') NOT NULL default 'unknown',
  PRIMARY KEY  (`bugID`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bt-description`
-- 

CREATE TABLE `bt-description` (
  `bugID` int(8) NOT NULL default '0',
  `descriptionID` int(8) NOT NULL auto_increment,
  `date` timestamp(14) NOT NULL,
  `status` enum('open','fixed','workaround','re-opened') NOT NULL default 'open',
  `description` text NOT NULL,
  PRIMARY KEY  (`descriptionID`),
  KEY `date` (`date`),
  KEY `bugID` (`bugID`)
) TYPE=MyISAM;
