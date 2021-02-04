# phpMyAdmin SQL Dump
# version 2.5.3-rc1
# http://www.phpmyadmin.net
#
# Host: 127.0.0.1
# Generation Time: Dec 17, 2007 at 12:28 AM
# Server version: 4.0.14
# PHP Version: 4.4.7
# 
# Database : `xtimeml-ttk`
# 

# --------------------------------------------------------

#
# Table structure for table `bt-bugs`
#

DROP TABLE IF EXISTS `bt-bugs`;
CREATE TABLE `bt-bugs` (
  `bugID` int(8) NOT NULL auto_increment,
  `bugName` varchar(255) NOT NULL default '',
  `date` date default NULL,
  `status` enum('open','fixed','workaround','re-opened') NOT NULL default 'open',
  `bugType` enum('error','feature_request','coverage','redesign','other') NOT NULL default 'error',
  `component` enum('unknown','gui','preprocessing','gutime','evita','slinket','s2t','blinker','classifier','arglinker','docmodel','utilities','other') NOT NULL default 'unknown',
  PRIMARY KEY  (`bugID`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

# --------------------------------------------------------

#
# Table structure for table `bt-description`
#

DROP TABLE IF EXISTS `bt-description`;
CREATE TABLE `bt-description` (
  `bugID` int(8) NOT NULL default '0',
  `descriptionID` int(8) NOT NULL auto_increment,
  `date` date default NULL,
  `status` enum('open','fixed','workaround','re-opened') NOT NULL default 'open',
  `description` text NOT NULL,
  PRIMARY KEY  (`descriptionID`),
  KEY `date` (`date`),
  KEY `bugID` (`bugID`)
) TYPE=MyISAM AUTO_INCREMENT=11 ;
    

