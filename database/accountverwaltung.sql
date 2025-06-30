-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250312.09988faae1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 30. Jun 2025 um 15:42
-- Server-Version: 11.5.2-MariaDB-log
-- PHP-Version: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Datenbank: `loginsystem_export`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `de_newsletter`
--

CREATE TABLE `de_newsletter` (
  `reg_mail` varchar(100) NOT NULL DEFAULT '',
  `sendmail` tinyint(1) NOT NULL DEFAULT 0,
  `register` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `de` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `en` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_de_kb`
--

CREATE TABLE `ls_de_kb` (
  `id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `server` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `atter` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `deffer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `kb` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `kbversion` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_tickets`
--

CREATE TABLE `ls_tickets` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` mediumint(8) UNSIGNED NOT NULL,
  `thema` text NOT NULL,
  `created` bigint(20) UNSIGNED NOT NULL,
  `modified` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `supporter` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_tickets_posts`
--

CREATE TABLE `ls_tickets_posts` (
  `ticket_id` mediumint(8) UNSIGNED NOT NULL,
  `created` bigint(20) UNSIGNED NOT NULL,
  `poster` varchar(40) NOT NULL,
  `message` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_user`
--

CREATE TABLE `ls_user` (
  `user_id` mediumint(9) NOT NULL,
  `loginname` varchar(100) NOT NULL DEFAULT '',
  `reg_mail` varchar(100) NOT NULL DEFAULT '',
  `pass` varchar(255) NOT NULL DEFAULT '',
  `newpass` varchar(255) NOT NULL DEFAULT '',
  `launcherkey` varchar(16) NOT NULL DEFAULT '',
  `register` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `logins` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `acc_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `credits` int(11) DEFAULT 0,
  `patime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `spielername` varchar(20) NOT NULL DEFAULT '',
  `vorname` varchar(20) NOT NULL DEFAULT '',
  `nachname` varchar(20) NOT NULL DEFAULT '',
  `plz` varchar(5) NOT NULL DEFAULT '',
  `ort` varchar(30) NOT NULL DEFAULT '',
  `strasse` varchar(30) NOT NULL DEFAULT '',
  `land` varchar(30) NOT NULL DEFAULT '',
  `telefon` varchar(40) NOT NULL DEFAULT '',
  `tag` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `monat` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `jahr` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `geschlecht` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `werberid` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `supporter` varchar(100) NOT NULL DEFAULT '',
  `tupdate` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `tickets` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `sonderaktion` tinyint(4) NOT NULL DEFAULT 0,
  `kommentar` text DEFAULT NULL,
  `showeblink` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `newslang` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `tlscore` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `tlplatz` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `loginkey` varchar(16) NOT NULL DEFAULT '',
  `loginkeytime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `betatester` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `forum_user_id` int(11) NOT NULL DEFAULT 0,
  `forum_nick` varchar(20) NOT NULL DEFAULT '',
  `observation_by` varchar(20) NOT NULL DEFAULT '',
  `newsletter_accept` tinyint(4) NOT NULL DEFAULT 0,
  `fb_id` text DEFAULT NULL,
  `fb_access_token` text DEFAULT NULL,
  `google_id` text DEFAULT NULL,
  `google_access_token` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_user_count`
--

CREATE TABLE `ls_user_count` (
  `server` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `datum` date NOT NULL DEFAULT '0000-00-00',
  `anzahl` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `pa_anz` mediumint(8) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_user_log`
--

CREATE TABLE `ls_user_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serverid` smallint(5) UNSIGNED NOT NULL,
  `userid` mediumint(8) UNSIGNED NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL,
  `file` varchar(25) DEFAULT NULL,
  `getpost` varchar(4096) NOT NULL
) ENGINE=MEMORY DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `de_newsletter`
--
ALTER TABLE `de_newsletter`
  ADD PRIMARY KEY (`reg_mail`);

--
-- Indizes für die Tabelle `ls_de_kb`
--
ALTER TABLE `ls_de_kb`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ls_tickets`
--
ALTER TABLE `ls_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ls_tickets_posts`
--
ALTER TABLE `ls_tickets_posts`
  ADD PRIMARY KEY (`ticket_id`,`created`);

--
-- Indizes für die Tabelle `ls_user`
--
ALTER TABLE `ls_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `loginname` (`loginname`),
  ADD UNIQUE KEY `reg_mail` (`reg_mail`),
  ADD KEY `plz` (`plz`),
  ADD KEY `tlscore` (`tlscore`),
  ADD KEY `register` (`register`),
  ADD KEY `last_login` (`last_login`),
  ADD KEY `tlplatz` (`tlplatz`),
  ADD KEY `werberid` (`werberid`),
  ADD KEY `loginkey` (`loginkey`);

--
-- Indizes für die Tabelle `ls_user_log`
--
ALTER TABLE `ls_user_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `ls_de_kb`
--
ALTER TABLE `ls_de_kb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ls_tickets`
--
ALTER TABLE `ls_tickets`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ls_user`
--
ALTER TABLE `ls_user`
  MODIFY `user_id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ls_user_log`
--
ALTER TABLE `ls_user_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
