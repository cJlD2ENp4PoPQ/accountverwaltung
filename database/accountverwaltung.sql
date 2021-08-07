-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 02. Mai 2021 um 18:42
-- Server-Version: 5.7.31-log
-- PHP-Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `loginsystem_export`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `de_newsletter`
--

CREATE TABLE `de_newsletter` (
  `reg_mail` varchar(100) COLLATE latin1_german2_ci NOT NULL DEFAULT '',
  `sendmail` tinyint(1) NOT NULL DEFAULT '0',
  `register` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `de` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `en` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_credits`
--

CREATE TABLE `ls_credits` (
  `user_id` mediumint(8) UNSIGNED DEFAULT NULL,
  `from_user` mediumint(8) UNSIGNED NOT NULL,
  `time` int(10) UNSIGNED NOT NULL,
  `credits` smallint(6) NOT NULL,
  `typ` tinyint(3) UNSIGNED NOT NULL,
  `seen` tinyint(3) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_credit_use`
--

CREATE TABLE `ls_credit_use` (
  `datum` varchar(10) NOT NULL DEFAULT '',
  `xDE` mediumint(9) UNSIGNED DEFAULT '0',
  `SDE` mediumint(9) UNSIGNED DEFAULT '0',
  `RDE` mediumint(9) UNSIGNED DEFAULT '0',
  `DEDV` mediumint(9) UNSIGNED DEFAULT '0',
  `NDE` mediumint(9) UNSIGNED DEFAULT '0',
  `QDE` mediumint(9) UNSIGNED DEFAULT '0',
  `BGDE` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `ENSDE` mediumint(9) UNSIGNED DEFAULT '0',
  `CDE` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `NSE` mediumint(9) UNSIGNED DEFAULT '0',
  `SSE` mediumint(9) UNSIGNED DEFAULT '0',
  `EA1` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `ALU1` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `AND1` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `EDE` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `premium` mediumint(8) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_de_kb`
--

CREATE TABLE `ls_de_kb` (
  `id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `server` varchar(5) CHARACTER SET utf8 NOT NULL,
  `atter` text CHARACTER SET utf8 NOT NULL,
  `deffer` text CHARACTER SET utf8 NOT NULL,
  `kb` text CHARACTER SET utf8 NOT NULL,
  `kbversion` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_geodata`
--

CREATE TABLE `ls_geodata` (
  `loc` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `plz` varchar(5) NOT NULL,
  `lon` double NOT NULL DEFAULT '0',
  `lat` double NOT NULL DEFAULT '0',
  `ort` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_km_transactions`
--

CREATE TABLE `ls_km_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(15) NOT NULL,
  `kanzalooPurchaseId` varchar(60) NOT NULL,
  `startTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `endTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `totalCost` mediumint(9) NOT NULL,
  `purchaseStatus` varchar(15) NOT NULL,
  `purchaseId` varchar(10) NOT NULL,
  `operator` varchar(12) NOT NULL,
  `msisdn` varchar(24) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_news`
--

CREATE TABLE `ls_news` (
  `id` int(5) UNSIGNED NOT NULL,
  `language` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `betreff` varchar(50) NOT NULL DEFAULT '',
  `nachricht` text NOT NULL,
  `time` int(10) UNSIGNED DEFAULT '0',
  `klicks` int(6) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_psc_transactions`
--

CREATE TABLE `ls_psc_transactions` (
  `user_id` mediumint(8) UNSIGNED NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mtid` varchar(30) NOT NULL,
  `amount` double(4,2) NOT NULL DEFAULT '0.00',
  `status` char(1) NOT NULL DEFAULT 'C'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_tickets_posts`
--

CREATE TABLE `ls_tickets_posts` (
  `ticket_id` mediumint(8) UNSIGNED NOT NULL,
  `created` bigint(20) UNSIGNED NOT NULL,
  `poster` varchar(40) NOT NULL,
  `message` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `launcherkey` varchar(16) NOT NULL,
  `register` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `logins` smallint(5) UNSIGNED NOT NULL,
  `acc_status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `last_ip` varchar(15) NOT NULL DEFAULT '',
  `credits` int(11) DEFAULT '0',
  `patime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `spielername` varchar(20) NOT NULL DEFAULT '',
  `vorname` varchar(20) NOT NULL DEFAULT '',
  `nachname` varchar(20) NOT NULL DEFAULT '',
  `plz` varchar(5) NOT NULL DEFAULT '',
  `ort` varchar(30) NOT NULL DEFAULT '',
  `strasse` varchar(30) NOT NULL DEFAULT '',
  `land` varchar(30) NOT NULL DEFAULT '',
  `telefon` varchar(40) NOT NULL DEFAULT '',
  `tag` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `monat` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `jahr` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `geschlecht` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `werberid` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `supporter` varchar(100) NOT NULL,
  `tupdate` tinyint(3) UNSIGNED NOT NULL,
  `tickets` smallint(5) UNSIGNED NOT NULL,
  `sonderaktion` tinyint(4) NOT NULL,
  `kommentar` text NOT NULL,
  `showeblink` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `newslang` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `tlscore` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tlplatz` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `cooperation` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `bp_userid` int(10) UNSIGNED NOT NULL,
  `bp_userlang` varchar(2) NOT NULL,
  `bp_usercountry` varchar(2) NOT NULL,
  `bp_affiliateID` int(10) UNSIGNED NOT NULL,
  `loginkey` varchar(16) NOT NULL,
  `loginkeytime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `kwickuid` bigint(20) UNSIGNED NOT NULL,
  `betatester` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `forum_user_id` int(11) NOT NULL,
  `forum_nick` varchar(20) NOT NULL,
  `gupdate` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `glng` double(10,6) NOT NULL DEFAULT '0.000000',
  `glat` double(10,6) NOT NULL DEFAULT '0.000000',
  `observation_by` varchar(20) NOT NULL,
  `newsletter_accept` tinyint(4) NOT NULL DEFAULT '0',
  `fb_id` text NOT NULL,
  `fb_access_token` text NOT NULL,
  `google_id` text NOT NULL,
  `google_access_token` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ls_user_count`
--

CREATE TABLE `ls_user_count` (
  `server` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `datum` date NOT NULL DEFAULT '0000-00-00',
  `anzahl` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
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
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `de_newsletter`
--
ALTER TABLE `de_newsletter`
  ADD PRIMARY KEY (`reg_mail`);

--
-- Indizes für die Tabelle `ls_credit_use`
--
ALTER TABLE `ls_credit_use`
  ADD PRIMARY KEY (`datum`);

--
-- Indizes für die Tabelle `ls_de_kb`
--
ALTER TABLE `ls_de_kb`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ls_geodata`
--
ALTER TABLE `ls_geodata`
  ADD KEY `plz` (`plz`);

--
-- Indizes für die Tabelle `ls_km_transactions`
--
ALTER TABLE `ls_km_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kanzalooPurchaseId` (`kanzalooPurchaseId`);

--
-- Indizes für die Tabelle `ls_news`
--
ALTER TABLE `ls_news`
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
-- AUTO_INCREMENT für Tabelle `ls_km_transactions`
--
ALTER TABLE `ls_km_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ls_news`
--
ALTER TABLE `ls_news`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
