-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2020 at 07:41 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gd2`
--

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `driveId` varchar(150) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `alt_data` text DEFAULT NULL,
  `subtitles` text DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `views` int(25) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT 'active = 0,  broken = 1 , deleted = 2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`id`, `user_id`, `title`, `driveId`, `data`, `alt_data`, `subtitles`, `thumb`, `type`, `slug`, `views`, `created_at`, `updated_at`, `status`) VALUES
(126, 29, 'Unknown Title', NULL, 'https://photos.app.goo.gl/CZH8BwBsCEEZNyVf6', '[]', 'NULL', NULL, 'GPhoto', 'WP5ZMPjgBfDZBiK', 0, '2020-11-03 07:35:15', '2020-11-03 07:35:15', 0),
(127, 29, 'Eka Fantasi Heeneka - Change 1st .wmv_480p.mp4', '1o6p1s6Gl971k1enen3XnyDV2G6vYwhHc', '{\"sources\":{\"360\":{\"file\":\"https:\\/\\/r5---sn-hp57knly.c.drive.google.com\\/videoplayback?expire=1604441294&ei=jpyhX9CcHM_xrvIPzKiXqAY&ip=198.136.48.146&cp=QVRFVkVfUFJXRVhPOlJRTHNOdlNzZ0xIOXRyWU5CYXhITVhSdzB2WkpHLTdqck1uUEtkUTM2R2c&id=9456badb98661152&itag=18&source=webdrive&requiressl=yes&mh=tw&mm=32&mn=sn-hp57knly&ms=su&mv=u&mvi=5&pl=22&ttl=transient&susc=dr&driveid=1o6p1s6Gl971k1enen3XnyDV2G6vYwhHc&app=explorer&mime=video\\/mp4&vprv=1&prv=1&dur=228.112&lmt=1603006792685493&mt=1604426127&sparams=expire%2Cei%2Cip%2Ccp%2Cid%2Citag%2Csource%2Crequiressl%2Cttl%2Csusc%2Cdriveid%2Capp%2Cmime%2Cvprv%2Cprv%2Cdur%2Clmt&sig=AOq0QJ8wRQIhAOYpMPjshJt3uITA8woMrvv-2p-cr3gVJ5PTXcZIAkPEAiAjGJ3-Qcl5zdPFS-YQOHPIaoGaBumblTW_vU6hmYUdwQ==&lsparams=mh%2Cmm%2Cmn%2Cms%2Cmv%2Cmvi%2Cpl&lsig=AG3C_xAwRQIhAPAhxDCC3XmRVXTG3fQUsBnIOOBH-N5UPk8sAHh9JLF1AiAETCwbb-UtNj2jSNQL2epxAh5oMc5bd1LChGzR68cpCA==\",\"quality\":\"360\",\"type\":\"video\\/mp4\"}},\"cookies\":[\"DRIVE_STREAM=HAzU-2Vorr0\",\"NID=204=BLHo_xpTIXVseEv004BE0J6TkjU2TT5zWfGfvHKdYbEW8s86U_-PN94aJBaiHNQo1hWXLGTm2wm-5sWWUELu1e0c0ea-QLgGmjAlAUrVCIw9yfcfMMtiFpKm6Kobo4PzTniVfx3E7l0n5t506ZJI2U2wnPd7uihhIA6Ez1sfryQ\",\"NID=204=RrzgHRl1rBMhSWzECAfpjjC36-tjQmHe8q96qtTtQPGMhgkj0-SaCPYXS23TCIwBZ_u7lKBl-HhZjROBk12J0KDDIlg_f6s4_MAMwmR8T6C7DtffaWFXY6i26MlPB6PSXWA-42wOVh0Mm2_tf2JISQQxlwySwkDEykpqwgNJG9M\"]}', '[]', 'NULL', 'https://lh3.googleusercontent.com/d/1o6p1s6Gl971k1enen3XnyDV2G6vYwhHc=w1280-h720-n', 'GDrive', 'fXI6CdtzlQiW7MK', 1, '2020-11-03 18:08:18', '2020-11-03 18:08:18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `config` varchar(50) NOT NULL,
  `var` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`config`, `var`) VALUES
('version', '1.5'),
('firewall', '0'),
('auto_embed', ''),
('timezone', 'Asia/Colombo'),
('dark_theme', '0'),
('allowed_domains', '[]'),
('stream', '1'),
('ae_views', '0'),
('adminId', '29'),
('is_blocked', '0'),
('vastAds', '[]'),
('popAds', '&lt;script&gt;&lt;/script&gt;'),
('apikey', 'Ash#45a56GAY77^agh%12$de'),
('netflix_skin', '1'),
('player_logo', 'player_logo.png'),
('sublist', '[\"english\",\"spanish\",\"french\",\"hindi\",\"russian\"]');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL DEFAULT 'user',
  `permission` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_logged` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `permission`, `created_at`, `last_logged`, `deleted`) VALUES
(29, 'admin', 'admin@gmail.com', '$2y$10$mOuzWcg12bjLtjgtRRHdhuKg.MUFMxSQy.vYDX7jqjFtYLHjWbhQG', 'admin', '[]', '2020-10-13 15:55:02', '2020-10-12 16:57:11', 0),
(31, 'user', 'user@gmail.com', '$2y$10$aRkZaxuK1rlcPyGFTSm8fOCTZmFjBo6H1EKswkyZa1KZtjrS90.jC', 'user', '[\"1\",\"2\"]', '2020-10-28 06:24:31', '2020-10-28 06:24:31', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
