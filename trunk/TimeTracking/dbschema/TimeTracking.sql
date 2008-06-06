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
-- Datenbank: `TimeTracking`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tt_bookings`
--
-- Erzeugt am: 06. Juni 2008 um 08:13
--

DROP TABLE IF EXISTS `tt_bookings`;
CREATE TABLE IF NOT EXISTS `tt_bookings` (
  `bookid` int(11) unsigned NOT NULL auto_increment,
  `mid` int(11) unsigned NOT NULL,
  `symid` int(11) unsigned NOT NULL,
  `stamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`bookid`),
  KEY `symid` (`symid`),
  KEY `mid` (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- RELATIONEN DER TABELLE `tt_bookings`:
--   `symid`
--       `tt_symbols` -> `symid`
--

--
-- Daten für Tabelle `tt_bookings`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tt_groups`
--
-- Erzeugt am: 06. Juni 2008 um 07:56
--

DROP TABLE IF EXISTS `tt_groups`;
CREATE TABLE IF NOT EXISTS `tt_groups` (
  `gid` int(11) unsigned NOT NULL,
  `groupname` varchar(128) collate latin1_general_ci NOT NULL,
  UNIQUE KEY `gid` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `tt_groups`
--

INSERT INTO `tt_groups` (`gid`, `groupname`) VALUES
(0, 'Administrator'),
(1, 'Gast'),
(2, 'Mitarbeiter'),
(3, 'Buchhaltung');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tt_symbols`
--
-- Erzeugt am: 06. Juni 2008 um 08:12
--

DROP TABLE IF EXISTS `tt_symbols`;
CREATE TABLE IF NOT EXISTS `tt_symbols` (
  `symid` int(11) unsigned NOT NULL auto_increment,
  `symbolname` varchar(128) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`symid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `tt_symbols`
--

INSERT INTO `tt_symbols` (`symid`, `symbolname`) VALUES
(1, 'Kommen'),
(2, 'Gehen'),
(3, 'Testen'),
(4, 'Pause');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tt_users`
--
-- Erzeugt am: 06. Juni 2008 um 08:30
--

DROP TABLE IF EXISTS `tt_users`;
CREATE TABLE IF NOT EXISTS `tt_users` (
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
-- RELATIONEN DER TABELLE `tt_users`:
--   `mid`
--       `tt_bookings` -> `mid`
--   `gid`
--       `tt_groups` -> `gid`
--

--
-- Daten für Tabelle `tt_users`
--


--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `tt_bookings`
--
ALTER TABLE `tt_bookings`
  ADD CONSTRAINT `tt_bookings_ibfk_1` FOREIGN KEY (`symid`) REFERENCES `tt_symbols` (`symid`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `tt_users`
--
ALTER TABLE `tt_users`
  ADD CONSTRAINT `tt_users_ibfk_2` FOREIGN KEY (`mid`) REFERENCES `tt_bookings` (`mid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tt_users_ibfk_3` FOREIGN KEY (`gid`) REFERENCES `tt_groups` (`gid`) ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS=1;
