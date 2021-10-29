-- phpMyAdmin SQL Dump
-- version 5.0.0-dev
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 29. Okt 2021 um 20:48
-- Server-Version: 10.3.29-MariaDB-0+deb10u1-log
-- PHP-Version: 7.3.29-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `de_supporttool`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `de_user_stat`
--

CREATE TABLE `de_user_stat` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `datum` varchar(10) NOT NULL DEFAULT '',
  `h0` tinyint(1) NOT NULL DEFAULT 0,
  `h1` tinyint(1) NOT NULL DEFAULT 0,
  `h2` tinyint(1) NOT NULL DEFAULT 0,
  `h3` tinyint(1) NOT NULL DEFAULT 0,
  `h4` tinyint(1) NOT NULL DEFAULT 0,
  `h5` tinyint(1) NOT NULL DEFAULT 0,
  `h6` tinyint(1) NOT NULL DEFAULT 0,
  `h7` tinyint(1) NOT NULL DEFAULT 0,
  `h8` tinyint(1) NOT NULL DEFAULT 0,
  `h9` tinyint(1) NOT NULL DEFAULT 0,
  `h10` tinyint(1) NOT NULL DEFAULT 0,
  `h11` tinyint(1) NOT NULL DEFAULT 0,
  `h12` tinyint(1) NOT NULL DEFAULT 0,
  `h13` tinyint(1) NOT NULL DEFAULT 0,
  `h14` tinyint(1) NOT NULL DEFAULT 0,
  `h15` tinyint(1) NOT NULL DEFAULT 0,
  `h16` tinyint(1) NOT NULL DEFAULT 0,
  `h17` tinyint(1) NOT NULL DEFAULT 0,
  `h18` tinyint(1) NOT NULL DEFAULT 0,
  `h19` tinyint(1) NOT NULL DEFAULT 0,
  `h20` tinyint(1) NOT NULL DEFAULT 0,
  `h21` tinyint(1) NOT NULL DEFAULT 0,
  `h22` tinyint(1) NOT NULL DEFAULT 0,
  `h23` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `de_user_stat`
--
ALTER TABLE `de_user_stat`
  ADD KEY `username` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
