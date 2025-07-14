-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 04:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `damac`
--

-- --------------------------------------------------------

--
-- Table structure for table `brochure_downloads`
--

CREATE TABLE `brochure_downloads` (
  `id` bigint(20) NOT NULL,
  `title` varchar(10) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `campaign_id` varchar(255) DEFAULT NULL,
  `utm_source` varchar(255) DEFAULT NULL,
  `utm_medium` varchar(255) DEFAULT NULL,
  `utm_campaign` varchar(255) DEFAULT NULL,
  `web_source` varchar(255) DEFAULT NULL,
  `ad_group` varchar(255) DEFAULT NULL,
  `campaign_name` varchar(255) DEFAULT NULL,
  `goal` varchar(255) DEFAULT NULL,
  `digital_source` varchar(255) DEFAULT NULL,
  `channel_cluster` varchar(255) DEFAULT NULL,
  `banner_size` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `placement` varchar(255) DEFAULT NULL,
  `ad_position` varchar(255) DEFAULT NULL,
  `match_type` varchar(255) DEFAULT NULL,
  `network` varchar(255) DEFAULT NULL,
  `bid_type` varchar(255) DEFAULT NULL,
  `gclid` varchar(255) DEFAULT NULL,
  `fbclid` varchar(255) DEFAULT NULL,
  `lead_source` varchar(100) DEFAULT 'digital',
  `last_mile_conversion` varchar(100) DEFAULT 'instapageForm',
  `device` varchar(50) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `os` varchar(100) DEFAULT NULL,
  `resolution` varchar(50) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `time_spent_before_form_submit` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `landing_page_url` text DEFAULT NULL,
  `website_language` varchar(10) DEFAULT 'EN',
  `country_name_sync` varchar(100) DEFAULT NULL,
  `city_sync` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country_code_sync` varchar(10) DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `ga_client_id` varchar(255) DEFAULT NULL,
  `fbid` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `refid` varchar(255) DEFAULT NULL,
  `adid` varchar(255) DEFAULT NULL,
  `download_count` int(11) DEFAULT 0,
  `first_download_at` timestamp NULL DEFAULT NULL,
  `last_download_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `title` varchar(10) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `comments` text DEFAULT NULL,
  `campaign_id` varchar(100) DEFAULT NULL,
  `utm_source` varchar(100) DEFAULT NULL,
  `utm_medium` varchar(100) DEFAULT NULL,
  `utm_campaign` varchar(100) DEFAULT NULL,
  `web_source` varchar(100) DEFAULT NULL,
  `ad_group` varchar(100) DEFAULT NULL,
  `campaign_name` varchar(100) DEFAULT NULL,
  `goal` varchar(100) DEFAULT NULL,
  `digital_source` varchar(100) DEFAULT NULL,
  `channel_cluster` varchar(100) DEFAULT NULL,
  `banner_size` varchar(100) DEFAULT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `placement` varchar(100) DEFAULT NULL,
  `ad_position` varchar(100) DEFAULT NULL,
  `match_type` varchar(100) DEFAULT NULL,
  `network` varchar(100) DEFAULT NULL,
  `bid_type` varchar(100) DEFAULT NULL,
  `gclid` varchar(100) DEFAULT NULL,
  `fbclid` varchar(100) DEFAULT NULL,
  `lead_source` varchar(50) DEFAULT NULL,
  `last_mile_conversion` varchar(50) DEFAULT NULL,
  `device` varchar(50) DEFAULT NULL,
  `project_name` varchar(100) DEFAULT NULL,
  `os` varchar(100) DEFAULT NULL,
  `resolution` varchar(50) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `time_spent_before_form_submit` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `landing_page_url` varchar(255) DEFAULT NULL,
  `website_language` varchar(10) DEFAULT NULL,
  `country_name_sync` varchar(100) DEFAULT NULL,
  `city_sync` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country_code_sync` varchar(10) DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `ga_client_id` varchar(100) DEFAULT NULL,
  `fbid` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `refid` varchar(100) DEFAULT NULL,
  `adid` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brochure_downloads`
--
ALTER TABLE `brochure_downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_campaign_id` (`campaign_id`),
  ADD KEY `idx_utm_source` (`utm_source`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brochure_downloads`
--
ALTER TABLE `brochure_downloads`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
