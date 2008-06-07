-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 06. Juni 2008 um 08:57
-- Server Version: 5.0.51
-- PHP-Version: 5.2.5

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `TimeRecording`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `tr_bookings`
--
-- Erzeugt am: 06. Juni 2008 um 08:13
--

DROP TABLE IF EXISTS `tr_bookings`;
CREATE TABLE IF NOT EXISTS `tr_bookings` (
  `bookid` int(11) unsigned NOT NULL auto_increment,
  `mid` int(11) unsigned NOT NULL,
  `symid` int(11) unsigned NOT NULL,
  `stamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`bookid`),
  KEY `symid` (`symid`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- RELATIONEN DER TABELLE `tr_bookings`:
--   `symid`
--       `tr_symbols` -> `symid`
--

--
-- Daten fuer Tabelle `tr_bookings`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `tr_groups`
--
-- Erzeugt am: 06. Juni 2008 um 07:56
--

DROP TABLE IF EXISTS `tr_groups`;
CREATE TABLE IF NOT EXISTS `tr_groups` (
  `gid` int(11) unsigned NOT NULL,
  `groupname` varchar(128) collate latin1_general_ci NOT NULL,
  UNIQUE KEY `gid` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten fuer Tabelle `tr_groups`
--

INSERT INTO `tr_groups` (`gid`, `groupname`) VALUES
(0, 'Administrator'),
(1, 'Gast'),
(2, 'Mitarbeiter'),
(3, 'Buchhaltung');

-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `tr_symbols`
--
-- Erzeugt am: 06. Juni 2008 um 08:12
--

DROP TABLE IF EXISTS `tr_symbols`;
CREATE TABLE IF NOT EXISTS `tr_symbols` (
  `symid` int(11) unsigned NOT NULL auto_increment,
  `symbolname` varchar(128) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`symid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Daten fuer Tabelle `tr_symbols`
--

INSERT INTO `tr_symbols` (`symid`, `symbolname`) VALUES
(1, 'Kommen'),
(2, 'Gehen'),
(3, 'Testen'),
(4, 'Pause');

-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `tr_users`
--
-- Erzeugt am: 06. Juni 2008 um 08:30
--

DROP TABLE IF EXISTS `tr_users`;
CREATE TABLE IF NOT EXISTS `tr_users` (
  `mid` int(11) unsigned NOT NULL auto_increment,
  `gid` int(11) unsigned NOT NULL default '2',
  `email` varchar(128) collate latin1_general_ci NOT NULL,
  `password` varchar(32) collate latin1_general_ci NOT NULL,
  `firstname` varchar(128) collate latin1_general_ci NOT NULL COMMENT 'Vorname',
  `lastname` varchar(128) collate latin1_general_ci NOT NULL COMMENT 'Nachname',
  PRIMARY KEY  (`mid`),
  KEY `groupid` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- RELATIONEN DER TABELLE `tr_users`:
--   `mid`
--       `tr_bookings` -> `mid`
--   `gid`
--       `tr_groups` -> `gid`
--

--
-- Daten fuer Tabelle `tr_users`
--


--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `tr_bookings`
--
ALTER TABLE `tr_bookings`
  ADD CONSTRAINT `tr_bookings_ibfk_1` FOREIGN KEY (`symid`) REFERENCES `tr_symbols` (`symid`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `tr_users`
--
ALTER TABLE `tr_users`
  ADD CONSTRAINT `tr_users_ibfk_2` FOREIGN KEY (`mid`) REFERENCES `tr_bookings` (`mid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_users_ibfk_3` FOREIGN KEY (`gid`) REFERENCES `tr_groups` (`gid`) ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS=1;
