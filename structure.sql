-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 01. Aug 2018 um 01:32
-- Server-Version: 10.1.33-MariaDB
-- PHP-Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `kassensystem`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kartentransaktionen`
--

CREATE TABLE `kartentransaktionen` (
  `trans_id` bigint(20) UNSIGNED NOT NULL,
  `user` varchar(16) NOT NULL,
  `vertreter` varchar(16) NOT NULL,
  `amount` float NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- TRUNCATE Tabelle vor dem Einfügen `kartentransaktionen`
--

TRUNCATE TABLE `kartentransaktionen`;
--
-- Daten für Tabelle `kartentransaktionen`
--

INSERT INTO `kartentransaktionen` (`trans_id`, `user`, `vertreter`, `amount`, `datetime`) VALUES
(1, '1', '3000000014783', 66.99, '0000-00-00 00:00:00'),
(2, '1', '3000000014783', 16.77, '2018-06-25 12:21:23'),
(3, '1', '3000000014783', -66.69, '2018-06-25 12:21:58'),
(4, '1', '3000000014783', -0.3, '2018-06-25 12:22:40'),
(5, '1', '3000000014783', -16.77, '2018-06-25 12:24:03'),
(6, '1', '3000000014783', 100, '2018-06-25 12:24:36'),
(7, '1', '3000000014783', -55.44, '2018-06-25 12:25:01'),
(8, '1', '3000000014783', -55, '2018-06-25 12:25:24'),
(9, '3000000001769', '3000000014783', 15, '2018-06-25 12:52:31'),
(10, '3000000001769', '3000000014783', -5, '2018-06-25 12:54:09'),
(11, '1', '3000000014783', 100, '2018-06-25 13:01:00'),
(12, '1', '3000000014783', -100, '2018-06-25 13:02:23'),
(13, '3000000014660', '3000000014783', 5, '2018-06-27 21:01:25'),
(14, '3000000014660', '3000000014783', -4.33, '2018-06-27 21:03:20'),
(15, '3000000014783', '3000000014783', 12.66, '2018-06-29 10:42:18'),
(16, '3000000014783', '3000000014783', -5, '2018-06-29 10:42:58'),
(17, '3000000014783', '3000000014783', -5, '2018-07-05 15:56:01'),
(18, '3000000014783', '3000000014783', 19.07, '2018-07-05 15:58:06'),
(19, '1', '3000000014783', 1000, '2018-07-10 08:42:17'),
(20, '1', '3000000014783', 100000, '2018-07-10 15:05:02'),
(21, '1', '3000000014783', -100000, '2018-07-11 14:59:48'),
(22, '1', '3000000014783', 0.99, '2018-07-11 15:01:05'),
(23, '1', '3000000014783', 758.98, '2018-07-12 08:19:36'),
(24, '1', '3000000014783', -0.45, '2018-07-12 08:20:02'),
(26, '1', '3000000014783', 100, '2018-07-15 08:40:46'),
(28, '1', '3000000014783', 100, '2018-07-15 08:41:33'),
(29, '1', '3000000014783', -100, '2018-07-15 08:42:13'),
(30, '3000000014783', '3000000014783', -19.07, '2018-07-17 21:10:57'),
(31, '3000000014783', '3000000014783', 20, '2018-07-17 21:11:33'),
(32, '3000000014783', '3000000014783', 11, '2018-07-19 17:37:52'),
(33, '3000000014783', '3000000014783', -31, '2018-07-21 13:03:08'),
(34, '3000000014783', '3000000014783', 50, '2018-07-21 13:03:33'),
(35, '1', '3000000014783', 19.77, '2018-07-29 01:15:15'),
(36, '1', '3000000014783', -20, '2018-07-29 01:16:04'),
(37, '3000000014783', '3000000014783', 20, '2018-08-01 00:04:24');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `log`
--

CREATE TABLE `log` (
  `entry` bigint(20) UNSIGNED NOT NULL,
  `exec_user` varchar(16) NOT NULL COMMENT 'executing user',
  `action` int(3) UNSIGNED NOT NULL COMMENT 'what is performed',
  `what` varchar(500) NOT NULL COMMENT 'on what'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- TRUNCATE Tabelle vor dem Einfügen `log`
--

TRUNCATE TABLE `log`;
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sources`
--

CREATE TABLE `sources` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- TRUNCATE Tabelle vor dem Einfügen `sources`
--

TRUNCATE TABLE `sources`;
--
-- Daten für Tabelle `sources`
--

INSERT INTO `sources` (`id`, `name`) VALUES
(0, 'keine Firma angegeben'),
(1, 'Testfirma'),
(4, 'Metro'),
(5, 'Talmarant'),
(6, 'Sansibar'),
(7, 'Veltins'),
(8, 'Vilsa');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `transaktionen`
--

CREATE TABLE `transaktionen` (
  `trans_id` bigint(20) UNSIGNED NOT NULL,
  `user` varchar(16) NOT NULL,
  `vertreter` varchar(16) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- TRUNCATE Tabelle vor dem Einfügen `transaktionen`
--

TRUNCATE TABLE `transaktionen`;
--
-- Daten für Tabelle `transaktionen`
--

INSERT INTO `transaktionen` (`trans_id`, `user`, `vertreter`, `datetime`) VALUES
(16, '1', '3000000014783', '2018-07-10 15:16:03'),
(17, '1', '3000000014783', '2018-07-10 15:22:56'),
(18, '1', '3000000014783', '2018-07-10 15:24:04'),
(19, '1', '3000000014783', '2018-07-10 15:24:49'),
(20, '1', '3000000014783', '2018-07-10 15:25:48'),
(21, '1', '3000000014783', '2018-07-10 15:28:40'),
(22, '1', '3000000014783', '2018-07-10 15:29:16'),
(23, '1', '3000000014783', '2018-07-10 15:31:00'),
(24, '1', '3000000014783', '2018-07-10 15:31:41'),
(25, '1', '3000000014783', '2018-07-10 15:32:32'),
(26, '1', '3000000014783', '2018-07-11 15:14:55'),
(27, '1', '3000000014783', '2018-07-16 13:40:50'),
(28, '3000000014783', '3000000014783', '2018-07-17 21:41:53'),
(29, '1', '3000000014783', '2018-07-17 21:42:06'),
(30, '1', '3000000014783', '2018-07-23 19:41:06'),
(31, '1', '3000000014783', '2018-07-23 20:04:21'),
(32, '1', '3000000014783', '2018-07-23 20:05:13'),
(33, '1', '3000000014783', '2018-07-23 20:05:40'),
(34, '1', '3000000014783', '2018-07-23 20:09:27'),
(35, '1', '3000000014783', '2018-07-23 20:09:53'),
(36, '1', '3000000014783', '2018-07-23 20:10:32'),
(37, '1', '3000000014783', '2018-07-23 20:11:30'),
(38, '1', '3000000014783', '2018-07-23 20:11:52'),
(39, '1', '3000000014783', '2018-07-23 20:12:40'),
(40, '1', '3000000014783', '2018-07-23 20:14:07'),
(41, '1', '3000000014783', '2018-07-23 20:14:10'),
(42, '1', '3000000014783', '2018-07-23 20:14:48'),
(43, '1', '3000000014783', '2018-07-23 20:15:00'),
(44, '1', '3000000014783', '2018-07-23 20:18:26'),
(45, '1', '3000000014783', '2018-07-23 20:18:49'),
(46, '1', '3000000014783', '2018-07-23 20:19:06'),
(47, '1', '3000000014783', '2018-07-23 20:21:19'),
(48, '1', '3000000014783', '2018-07-23 20:22:21'),
(49, '1', '3000000014783', '2018-07-23 20:22:58'),
(50, '1', '3000000014783', '2018-07-23 20:23:21'),
(51, '1', '3000000014783', '2018-07-23 20:23:43'),
(52, '1', '3000000014783', '2018-07-23 20:24:17'),
(53, '1', '3000000014783', '2018-07-23 20:24:59'),
(54, '1', '3000000014783', '2018-07-23 20:27:50'),
(55, '1', '3000000014783', '2018-07-23 22:24:57'),
(56, '1', '3000000014783', '2018-07-28 17:49:08'),
(57, '1', '3000000014783', '2018-07-28 17:50:29'),
(58, '1', '3000000014783', '2018-07-28 17:52:51'),
(59, '1', '3000000014783', '2018-07-28 17:52:57'),
(60, '1', '3000000014783', '2018-07-28 17:53:37'),
(61, '1', '3000000014783', '2018-07-29 00:11:49'),
(62, '1', '3000000014783', '2018-07-29 00:12:30'),
(63, '1', '3000000014783', '2018-07-29 01:24:25'),
(64, '1', '3000000014783', '2018-07-29 01:27:14'),
(65, '1', '3000000014783', '2018-07-29 01:36:36'),
(66, '1', '3000000014783', '2018-07-29 01:37:55'),
(67, '1', '3000000014783', '2018-07-29 01:45:24'),
(68, '3000000014783', '3000000014783', '2018-07-30 19:53:31'),
(69, '1', '3000000014783', '2018-07-31 01:11:28'),
(70, '3000000014783', '3000000014783', '2018-08-01 00:25:02'),
(71, '3000000014783', '3000000014783', '2018-08-01 01:07:29'),
(72, '3000000014783', '3000000014783', '2018-08-01 01:07:53'),
(73, '3000000014783', '3000000014783', '2018-08-01 01:08:50');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `ausweis` varchar(16) NOT NULL,
  `amount` float UNSIGNED DEFAULT NULL,
  `pw` varchar(60) DEFAULT NULL,
  `access` int(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- TRUNCATE Tabelle vor dem Einfügen `users`
--

TRUNCATE TABLE `users`;
--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`ausweis`, `amount`, `pw`, `access`) VALUES
('1', 341.86, NULL, 0),
('3000000001769', 10, NULL, 0),
('3000000014660', 0.67, NULL, 0),
('3000000014783', 51.97, '$2y$11$ROtg3mN9PNO9xi/Nd//aM.BydaPl50Bf9g8pbspoDK1x6d4/iAwiG', 2730),
('4014519020776', 0, '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `waren`
--

CREATE TABLE `waren` (
  `id` varchar(40) NOT NULL,
  `cur_rev` int(10) UNSIGNED NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- TRUNCATE Tabelle vor dem Einfügen `waren`
--

TRUNCATE TABLE `waren`;
--
-- Daten für Tabelle `waren`
--

INSERT INTO `waren` (`id`, `cur_rev`, `deleted`) VALUES
('1', 2, 0),
('100', 0, 0),
('101', 0, 0),
('11', 4, 0),
('2', 15, 0),
('3', 4, 0),
('4', 2, 0),
('4005249005102', 3, 0),
('4006248115021', 17, 0),
('4104450004079', 0, 0),
('5', 6, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `warenrevisionen`
--

CREATE TABLE `warenrevisionen` (
  `id` varchar(40) NOT NULL,
  `revision` int(10) UNSIGNED NOT NULL,
  `price` float NOT NULL COMMENT 'netto',
  `mehrwertsteuer_voll` tinyint(1) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `source` int(10) UNSIGNED NOT NULL,
  `s_price` float NOT NULL,
  `s_mwst_full` tinyint(1) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- TRUNCATE Tabelle vor dem Einfügen `warenrevisionen`
--

TRUNCATE TABLE `warenrevisionen`;
--
-- Daten für Tabelle `warenrevisionen`
--

INSERT INTO `warenrevisionen` (`id`, `revision`, `price`, `mehrwertsteuer_voll`, `description`, `source`, `s_price`, `s_mwst_full`, `created`) VALUES
('1', 0, 9.99, 1, 'Ich bin ein Test', 0, 0, 0, '2018-06-29 19:26:36'),
('1', 1, 9.99, 1, 'Ich bin ein Test', 0, 0, 0, '2018-06-29 17:35:35'),
('1', 2, 9.99, 1, 'Ich bin ein Test', 0, 0, 0, '2018-06-29 18:54:19'),
('100', 0, -0.19, 0, 'Pfand Rückgabe', 0, -0, 0, '2018-07-29 01:23:10'),
('101', 0, -0.13, 1, 'Pfand Rückgabe', 0, 0, 1, '2018-08-01 01:06:30'),
('11', 0, 9966, 1, 'Testprodukt', 0, 0, 0, '2018-07-02 09:39:42'),
('11', 1, 99.66, 1, 'Testprodukt', 0, 0, 0, '2018-07-06 08:22:17'),
('11', 2, 99.66, 0, 'Testprodukt', 1, 0, 0, '2018-07-06 08:28:52'),
('11', 3, 1.56, 1, 'Testprodukt', 1, 0.77, 1, '2018-07-19 17:55:15'),
('11', 4, 30, 1, 'Bla', 4, 10, 1, '2018-07-19 20:31:11'),
('2', 0, 6.66, 1, 'Neues Superprodukt', 0, 0, 0, '2018-06-29 19:23:40'),
('2', 1, 6.66, 0, 'Neues Superprodukt', 0, 0, 0, '2018-06-29 19:32:45'),
('2', 2, 6.66, 0, 'Neues Superprodukt', 0, 0, 0, '2018-06-29 19:34:35'),
('2', 3, 6.66, 1, 'Neues Superprodukt', 0, 0, 0, '2018-07-05 15:59:34'),
('2', 4, 2.1, 1, 'Essen Talmarant', 5, 2.1, 0, '2018-07-17 21:18:15'),
('2', 5, 2.1, 1, 'Essen Talmarant', 5, 2.1, 1, '2018-07-17 21:19:05'),
('2', 6, 2.1, 1, 'Essen Talmarant', 5, 1.78, 1, '2018-07-17 21:20:03'),
('2', 7, 2.1, 1, 'Essen Talmarant', 5, 1.78, 1, '2018-07-22 19:05:14'),
('2', 8, 2.1, 0, 'Essen Talmarant', 5, 1.78, 0, '2018-07-22 19:06:58'),
('2', 9, 2.1, 1, 'Essen Talmarant', 5, 1.78, 0, '2018-07-22 19:09:11'),
('2', 10, 2.1, 1, 'Essen Talmarant', 5, 1.78, 1, '2018-07-22 19:09:19'),
('2', 11, 2.1, 1, 'Essen Talmarant', 5, 1.78, 1, '2018-07-22 19:09:28'),
('2', 12, 2.1, 1, 'Essen Talmarant', 5, 1.78, 0, '2018-07-22 19:09:41'),
('2', 13, 2.1, 1, 'Essen Talmarant', 5, 1.78, 0, '2018-07-22 19:09:57'),
('2', 14, 2.1, 1, 'Essen Talmarant', 5, 1.78, 1, '2018-07-22 19:10:04'),
('2', 15, 2.1, 1, 'Essen Talmarant', 5, 1.78, 1, '2018-07-22 19:10:11'),
('3', 0, 6.67, 1, 'Neues Superprodukt', 0, 0, 0, '2018-06-29 19:29:45'),
('3', 1, 6.66, 1, 'Neues Superprodukt', 0, 0, 0, '2018-06-29 19:31:05'),
('3', 2, 6.66, 1, 'Neues Superprodukt', 4, 6.66, 0, '2018-07-16 13:33:16'),
('3', 3, 6.66, 1, 'Neues Superprodukt', 4, 6.66, 0, '2018-07-16 13:34:07'),
('3', 4, 6.66, 1, 'Neues Superprodukt', 1, 6.66, 0, '2018-07-19 20:29:29'),
('4', 0, 6.66, 1, 'Neues Superprodukt', 0, 0, 0, '2018-06-29 19:31:15'),
('4', 1, 6.66, 1, 'Neues Superprodukt', 0, 0, 0, '2018-06-29 19:31:22'),
('4', 2, 6.66, 1, 'Neues Superprodukt', 0, 0, 0, '2018-07-02 09:16:53'),
('4005249005102', 0, 1.42, 0, 'Pils', 7, 0.45, 0, '2018-08-01 00:12:35'),
('4005249005102', 1, 1.42, 0, 'Pils', 7, 0.45, 0, '2018-08-01 00:12:55'),
('4005249005102', 2, 1.42, 0, 'Pils', 7, 0.45, 0, '2018-08-01 00:13:02'),
('4005249005102', 3, 1.42, 1, 'Pils', 7, 0.45, 1, '2018-08-01 00:16:10'),
('4006248115021', 0, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-17 21:33:06'),
('4006248115021', 1, 20, 0, 'Darjeeling Tee', 6, 7.99, 0, '2018-07-22 19:12:44'),
('4006248115021', 2, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:13:14'),
('4006248115021', 3, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:37:53'),
('4006248115021', 4, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:38:02'),
('4006248115021', 5, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:38:07'),
('4006248115021', 6, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:38:15'),
('4006248115021', 7, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:38:21'),
('4006248115021', 8, 20, 0, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:39:41'),
('4006248115021', 9, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:39:50'),
('4006248115021', 10, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:39:55'),
('4006248115021', 11, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:39:59'),
('4006248115021', 12, 20, 0, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:40:09'),
('4006248115021', 13, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:41:14'),
('4006248115021', 14, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:41:23'),
('4006248115021', 15, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:44:26'),
('4006248115021', 16, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:44:33'),
('4006248115021', 17, 20, 1, 'Darjeeling Tee', 6, 7.99, 1, '2018-07-22 19:44:43'),
('4104450004079', 0, 0.84, 1, 'Mineralwasser Classic (inkl. 15ct Pfand)', 8, 0.29, 1, '2018-08-01 01:05:41'),
('5', 0, 1.68, 1, 'Hanuta', 0, 0.84, 1, '2018-07-21 13:21:34'),
('5', 1, 1.68, 0, 'Hanuta', 0, 0.84, 0, '2018-07-22 01:27:15'),
('5', 2, 1.68, 0, 'Hanuta', 0, 0.84, 0, '2018-07-22 01:27:28'),
('5', 3, 1.68, 0, 'Hanuta', 0, 0.84, 0, '2018-07-22 01:31:04'),
('5', 4, 1.68, 0, 'Hanuta', 0, 0.84, 0, '2018-07-22 01:31:10'),
('5', 5, 1.68, 0, 'Hanuta', 0, 0.84, 0, '2018-07-22 01:31:17'),
('5', 6, 1.68, 1, 'Hanuta', 0, 0.84, 1, '2018-08-01 00:17:43');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `warentransaktionen`
--

CREATE TABLE `warentransaktionen` (
  `trans_id` bigint(20) UNSIGNED NOT NULL,
  `waren_id` varchar(40) NOT NULL,
  `revision` int(10) UNSIGNED NOT NULL,
  `menge` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- TRUNCATE Tabelle vor dem Einfügen `warentransaktionen`
--

TRUNCATE TABLE `warentransaktionen`;
--
-- Daten für Tabelle `warentransaktionen`
--

INSERT INTO `warentransaktionen` (`trans_id`, `waren_id`, `revision`, `menge`) VALUES
(16, '1', 2, 1),
(16, '11', 2, 1),
(16, '2', 3, 1),
(16, '3', 1, 1),
(16, '4', 2, 1),
(18, '1', 2, 1),
(18, '11', 2, 1),
(18, '2', 3, 1),
(18, '3', 1, 1),
(18, '4', 2, 1),
(19, '1', 2, 1),
(19, '11', 2, 1),
(19, '2', 3, 1),
(19, '3', 1, 1),
(19, '4', 2, 1),
(20, '1', 2, 1),
(20, '11', 2, 1),
(20, '2', 3, 1),
(20, '3', 1, 1),
(20, '4', 2, 1),
(22, '1', 2, 1),
(23, '1', 2, 1),
(24, '1', 2, 1),
(25, '1', 2, 1),
(26, '1', 2, 1),
(26, '2', 3, 1),
(27, '1', 2, 1),
(27, '2', 3, 1),
(27, '3', 3, 1),
(28, '11', 2, 1),
(28, '4006248115021', 0, 1),
(29, '11', 2, 1),
(29, '4006248115021', 0, 1),
(31, '1', 2, 7),
(33, '1', 2, 12),
(38, '1', 2, 10),
(53, '1', 2, 5),
(54, '1', 2, 1),
(54, '2', 15, 1),
(54, '3', 4, 1),
(54, '4', 2, 1),
(54, '5', 5, 1),
(55, '1', 2, 8),
(56, '1', 2, 1),
(57, '1', 2, 1),
(61, '2', 15, 4),
(62, '5', 5, 8),
(63, '100', 0, 5),
(64, '100', 0, 5),
(65, '100', 0, 5),
(66, '100', 0, 5),
(67, '100', 0, 5),
(68, '5', 5, 6),
(70, '4005249005102', 3, 2),
(70, '5', 6, 1),
(71, '4104450004079', 0, 1),
(72, '4104450004079', 0, 1),
(73, '101', 0, 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `kartentransaktionen`
--
ALTER TABLE `kartentransaktionen`
  ADD PRIMARY KEY (`trans_id`),
  ADD KEY `user` (`user`),
  ADD KEY `vertreter` (`vertreter`);

--
-- Indizes für die Tabelle `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`entry`);

--
-- Indizes für die Tabelle `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `transaktionen`
--
ALTER TABLE `transaktionen`
  ADD PRIMARY KEY (`trans_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ausweis`);

--
-- Indizes für die Tabelle `waren`
--
ALTER TABLE `waren`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `warenrevisionen`
--
ALTER TABLE `warenrevisionen`
  ADD PRIMARY KEY (`id`,`revision`);

--
-- Indizes für die Tabelle `warentransaktionen`
--
ALTER TABLE `warentransaktionen`
  ADD PRIMARY KEY (`trans_id`,`waren_id`,`revision`),
  ADD KEY `waren_id` (`waren_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `kartentransaktionen`
--
ALTER TABLE `kartentransaktionen`
  MODIFY `trans_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT für Tabelle `log`
--
ALTER TABLE `log`
  MODIFY `entry` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `sources`
--
ALTER TABLE `sources`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `transaktionen`
--
ALTER TABLE `transaktionen`
  MODIFY `trans_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `kartentransaktionen`
--
ALTER TABLE `kartentransaktionen`
  ADD CONSTRAINT `kartentransaktionen_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`ausweis`),
  ADD CONSTRAINT `kartentransaktionen_ibfk_2` FOREIGN KEY (`vertreter`) REFERENCES `users` (`ausweis`);

--
-- Constraints der Tabelle `warenrevisionen`
--
ALTER TABLE `warenrevisionen`
  ADD CONSTRAINT `warenrevisionen_ibfk_1` FOREIGN KEY (`id`) REFERENCES `waren` (`id`);

--
-- Constraints der Tabelle `warentransaktionen`
--
ALTER TABLE `warentransaktionen`
  ADD CONSTRAINT `warentransaktionen_ibfk_1` FOREIGN KEY (`trans_id`) REFERENCES `transaktionen` (`trans_id`),
  ADD CONSTRAINT `warentransaktionen_ibfk_2` FOREIGN KEY (`waren_id`) REFERENCES `waren` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
