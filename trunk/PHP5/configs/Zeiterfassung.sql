-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 27. Mai 2008 um 16:29
-- Server Version: 5.0.51
-- PHP-Version: 5.2.5

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `Zeiterfassung`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Mitarbeiter`
--
-- Erzeugt am: 27. Mai 2008 um 16:28
--

DROP TABLE IF EXISTS `Mitarbeiter`;
CREATE TABLE IF NOT EXISTS `Mitarbeiter` (
  `MId` int(16) NOT NULL,
  `Namen` varchar(32) character set latin1 NOT NULL,
  `Vornamen` varchar(32) character set latin1 NOT NULL,
  `LoginNamen` varchar(32) character set latin1 NOT NULL,
  `LoginPasswort` varchar(32) character set latin1 NOT NULL,
  PRIMARY KEY  (`MId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `Mitarbeiter`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ZBTyp`
--
-- Erzeugt am: 27. Mai 2008 um 16:28
--

DROP TABLE IF EXISTS `ZBTyp`;
CREATE TABLE IF NOT EXISTS `ZBTyp` (
  `TypId` int(16) NOT NULL,
  `Bezeichnung` varchar(32) character set latin1 NOT NULL,
  `Symbol` varchar(3) character set latin1 NOT NULL,
  PRIMARY KEY  (`TypId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `ZBTyp`
--

INSERT INTO `ZBTyp` (`TypId`, `Bezeichnung`, `Symbol`) VALUES
(0, 'Kommen', 'KOM'),
(1, 'Gehen', 'GEH');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ZeitBuchung`
--
-- Erzeugt am: 27. Mai 2008 um 16:28
--

DROP TABLE IF EXISTS `ZeitBuchung`;
CREATE TABLE IF NOT EXISTS `ZeitBuchung` (
  `Bid` int(16) NOT NULL,
  `TypId` int(16) NOT NULL,
  `Datum` date NOT NULL,
  `Mid` int(16) NOT NULL,
  `KstId` int(16) NOT NULL,
  `KoaId` int(16) NOT NULL,
  PRIMARY KEY  (`Bid`),
  KEY `TypId` (`TypId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- RELATIONEN DER TABELLE `ZeitBuchung`:
--   `TypId`
--       `ZBTyp` -> `TypId`
--

--
-- Daten für Tabelle `ZeitBuchung`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ZeitKonto`
--
-- Erzeugt am: 27. Mai 2008 um 16:28
--

DROP TABLE IF EXISTS `ZeitKonto`;
CREATE TABLE IF NOT EXISTS `ZeitKonto` (
  `Mid` int(16) NOT NULL,
  `Periode` int(16) NOT NULL,
  `Jahr` int(4) NOT NULL,
  `MinSoll` int(16) NOT NULL,
  `MinHaben` int(16) NOT NULL,
  `MinSaldo` int(16) NOT NULL,
  PRIMARY KEY  (`Mid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `ZeitKonto`
--


--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `ZeitBuchung`
--
ALTER TABLE `ZeitBuchung`
  ADD CONSTRAINT `ZeitBuchung_ibfk_1` FOREIGN KEY (`TypId`) REFERENCES `ZBTyp` (`TypId`) ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS=1;
