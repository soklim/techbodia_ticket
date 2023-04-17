-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2023 at 09:43 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ticket`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign_ticket_types`
--

CREATE TABLE `assign_ticket_types` (
  `id` int(11) NOT NULL,
  `ticket_type_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `can_create` tinyint(1) DEFAULT 0,
  `can_resolve` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assign_ticket_types`
--

INSERT INTO `assign_ticket_types` (`id`, `ticket_type_id`, `role_id`, `can_create`, `can_resolve`) VALUES
(1, 1, 3, 0, 1),
(2, 2, 3, 0, 1),
(3, 3, 2, 1, 1),
(4, 1, 1, 1, 1),
(5, 2, 1, 1, 1),
(6, 3, 1, 1, 1),
(8, 2, 4, 1, 0),
(9, 1, 2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_modules`
--

CREATE TABLE `group_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `route` text DEFAULT NULL,
  `icon` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_modules`
--

INSERT INTO `group_modules` (`id`, `name`, `route`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Security', '#', 'shield-quarter', '2022-11-28 19:25:13', '2023-02-04 10:59:25'),
(2, 'List', '#', 'list-ol', '2022-11-28 19:25:13', '2023-04-16 13:58:20');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(8, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(9, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(10, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(11, '2016_06_01_000004_create_oauth_clients_table', 1),
(12, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(13, '2019_08_19_000000_create_failed_jobs_table', 1),
(14, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `route_name` varchar(255) NOT NULL,
  `order_num` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `group_id`, `route_name`, `order_num`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Role', 1, '/roles', NULL, 1, '2022-11-28 19:25:13', '2022-11-28 19:25:13'),
(2, 'User', 1, '/users', NULL, 1, '2022-11-28 19:25:13', '2022-11-28 19:25:13'),
(3, 'Group Module', 1, '/group_modules', NULL, 1, '2022-11-28 19:25:13', '2023-04-17 05:19:52'),
(4, 'Module', 1, '/modules', NULL, 1, '2022-11-28 19:25:13', '2023-04-17 05:19:58'),
(13, 'Permission', 1, '/module_permissions', NULL, 1, '2022-12-14 07:24:46', '2022-12-14 07:24:46'),
(24, 'Ticket Request', 2, '/ticket', NULL, 1, '2023-04-16 13:58:43', '2023-04-17 05:27:01'),
(25, 'Ticket Type', 2, '/ticket_type', NULL, 1, '2023-04-16 14:01:33', '2023-04-16 14:01:33'),
(26, 'Assign Ticket Type', 2, '/assign_ticket_type', NULL, 1, '2023-04-17 03:06:52', '2023-04-17 03:06:52');

-- --------------------------------------------------------

--
-- Table structure for table `module_permissions`
--

CREATE TABLE `module_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `a_create` tinyint(1) NOT NULL DEFAULT 0,
  `a_read` tinyint(1) NOT NULL DEFAULT 0,
  `a_update` tinyint(1) NOT NULL DEFAULT 0,
  `a_delete` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `module_permissions`
--

INSERT INTO `module_permissions` (`id`, `module_id`, `role_id`, `a_create`, `a_read`, `a_update`, `a_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, '2022-11-28 19:25:25', '2023-04-17 03:21:46'),
(2, 2, 1, 1, 1, 1, 1, '2022-11-28 19:25:25', '2023-04-17 03:21:46'),
(3, 3, 1, 1, 1, 1, 1, '2022-11-28 19:25:25', '2023-04-17 03:21:46'),
(4, 4, 1, 1, 1, 1, 1, '2022-11-28 19:25:25', '2023-04-17 03:21:46'),
(12, 13, 1, 1, 1, 1, 1, NULL, '2023-04-17 03:21:46'),
(14, 1, 2, 0, 0, 0, 0, '2022-12-15 05:57:52', '2023-04-16 13:59:20'),
(15, 2, 2, 0, 0, 0, 0, '2022-12-15 05:57:52', '2023-04-16 13:59:20'),
(16, 3, 2, 0, 0, 0, 0, '2022-12-15 05:57:52', '2023-04-16 13:59:20'),
(17, 4, 2, 0, 0, 0, 0, '2022-12-15 05:57:52', '2023-04-16 13:59:20'),
(26, 13, 2, 0, 0, 0, 0, '2022-12-15 05:57:52', '2023-04-16 13:59:20'),
(27, 1, 5, 0, 0, 0, 0, '2022-12-15 06:00:59', '2023-03-26 08:14:14'),
(28, 2, 5, 0, 0, 0, 0, '2022-12-15 06:01:00', '2023-03-26 08:14:14'),
(29, 3, 5, 0, 0, 0, 0, '2022-12-15 06:01:00', '2023-03-26 08:14:14'),
(30, 4, 5, 0, 0, 0, 0, '2022-12-15 06:01:00', '2023-03-26 08:14:14'),
(39, 13, 5, 0, 0, 0, 0, '2022-12-15 06:01:00', '2023-03-26 08:14:14'),
(40, 1, 4, 0, 0, 0, 0, '2022-12-15 06:01:21', '2023-04-16 14:00:50'),
(41, 2, 4, 0, 0, 0, 0, '2022-12-15 06:01:21', '2023-04-16 14:00:50'),
(42, 3, 4, 0, 0, 0, 0, '2022-12-15 06:01:21', '2023-04-16 14:00:50'),
(43, 4, 4, 0, 0, 0, 0, '2022-12-15 06:01:21', '2023-04-16 14:00:50'),
(52, 13, 4, 0, 0, 0, 0, '2022-12-15 06:01:21', '2023-04-16 14:00:50'),
(53, 1, 3, 0, 0, 0, 0, '2022-12-15 06:01:29', '2023-04-17 04:00:58'),
(54, 2, 3, 0, 0, 0, 0, '2022-12-15 06:01:29', '2023-04-17 04:00:58'),
(55, 3, 3, 0, 0, 0, 0, '2022-12-15 06:01:29', '2023-04-17 04:00:58'),
(56, 4, 3, 0, 0, 0, 0, '2022-12-15 06:01:29', '2023-04-17 04:00:58'),
(65, 13, 3, 0, 0, 0, 0, '2022-12-15 06:01:29', '2023-04-17 04:00:58'),
(66, 14, 1, 1, 1, 1, 1, '2022-12-23 08:34:53', '2023-03-13 09:08:17'),
(71, 14, 5, 0, 0, 0, 0, '2023-01-15 07:52:39', '2023-03-26 08:14:14'),
(76, 14, 4, 0, 0, 0, 0, '2023-01-15 08:09:53', '2023-01-15 08:09:53'),
(94, 24, 1, 1, 1, 1, 1, '2023-04-16 13:58:54', '2023-04-17 03:21:46'),
(95, 24, 2, 1, 1, 1, 1, '2023-04-16 13:59:20', '2023-04-16 13:59:20'),
(96, 24, 3, 1, 1, 1, 0, '2023-04-16 13:59:40', '2023-04-17 04:00:58'),
(97, 24, 4, 1, 1, 1, 1, '2023-04-16 14:00:50', '2023-04-16 14:00:50'),
(98, 25, 1, 1, 1, 1, 1, '2023-04-17 03:21:46', '2023-04-17 03:21:46'),
(99, 26, 1, 1, 1, 1, 1, '2023-04-17 03:21:46', '2023-04-17 03:21:46'),
(100, 25, 3, 0, 0, 0, 0, '2023-04-17 04:00:58', '2023-04-17 04:00:58'),
(101, 26, 3, 0, 0, 0, 0, '2023-04-17 04:00:58', '2023-04-17 04:00:58');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('9c5499cdbd196ba78dd11d2edf313eb9cc3b202e2f7de0ad62b60761bc51ac037fc6da2887e2488d', 1, 1, 'authToken', '[]', 0, '2023-04-17 00:10:25', '2023-04-17 00:10:25', '2024-04-17 07:10:25');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'JsPZ8OqpcZ0OmF9hKXPfUqH1oyJTxCIvTudXiJza', NULL, 'http://localhost', 1, 0, 0, '2023-04-17 00:10:03', '2023-04-17 00:10:03'),
(2, NULL, 'Laravel Password Grant Client', 'w2zTSuB84gPXeJpmeSyDnIAQgSTvAB2KR6kCBVSX', 'users', 'http://localhost', 0, 1, 0, '2023-04-17 00:10:03', '2023-04-17 00:10:03');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-04-17 00:10:03', '2023-04-17 00:10:03');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', NULL, '2022-11-28 19:24:57', '2023-04-16 12:51:05'),
(2, 'QA', NULL, '2022-11-29 05:50:34', '2023-04-16 12:50:27'),
(3, 'RD', NULL, '2022-12-03 18:49:04', '2023-04-16 12:50:37'),
(4, 'PM', NULL, '2022-12-03 18:49:18', '2023-04-16 12:50:58');

-- --------------------------------------------------------

--
-- Table structure for table `statuss`
--

CREATE TABLE `statuss` (
  `id` int(11) NOT NULL,
  `status_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statuss`
--

INSERT INTO `statuss` (`id`, `status_name`) VALUES
(1, 'Pending'),
(2, 'Resolved'),
(3, 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `description` varchar(500) NOT NULL,
  `ticket_type_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `severity` tinyint(1) DEFAULT 0,
  `priority` tinyint(1) DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `resolved_by` int(11) DEFAULT NULL,
  `resolved_date` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `summary`, `description`, `ticket_type_id`, `status_id`, `severity`, `priority`, `created_by`, `created_date`, `resolved_by`, `resolved_date`, `deleted_by`, `deleted_date`) VALUES
(1, 'test APi', 'test API', 1, 2, 0, 0, 1, '2023-04-16 15:21:21', 3, '2023-04-17 02:34:33', NULL, NULL),
(2, 'test1', 'test1', 1, 3, 0, 0, 14, '2023-04-16 15:38:17', NULL, NULL, 14, '2023-04-17 02:36:04'),
(3, 'test case', 'test case', 3, 2, 0, 0, 14, '2023-04-16 15:39:20', 14, '2023-04-17 02:35:27', NULL, NULL),
(4, 'Feature001', 'Feature001 desc', 2, 1, 0, 0, 4, '2023-04-17 02:30:19', NULL, NULL, NULL, NULL),
(5, 'test333', 'test333', 1, 1, 0, 1, 14, '2023-04-17 02:51:27', NULL, NULL, NULL, NULL),
(6, 'test case', 'test case', 3, 1, 0, 1, 14, '2023-04-17 04:04:20', NULL, NULL, NULL, NULL),
(7, 'test case normal', 'test case normal', 1, 1, 1, 0, 14, '2023-04-17 04:04:36', NULL, NULL, NULL, NULL),
(8, 'test', 'test', 2, 1, 0, 1, 1, '2023-04-17 06:09:37', NULL, NULL, NULL, NULL),
(9, 'test APi', 'test API', 1, 1, 0, 0, 1, '2023-04-17 06:29:32', NULL, NULL, NULL, NULL),
(10, 'test APi', 'test API', 1, 1, 0, 0, 1, '2023-04-17 06:56:30', NULL, NULL, NULL, NULL),
(11, 'test APi', 'test API', 1, 1, 0, 0, 1, '2023-04-17 00:14:34', NULL, NULL, NULL, NULL),
(12, 'test APid', 'test APId', 1, 1, 0, 0, 1, '2023-04-17 00:15:24', NULL, NULL, NULL, NULL),
(13, 'test APid', 'test APId', 1, 1, 0, 0, 1, '2023-04-17 00:16:20', NULL, NULL, NULL, NULL),
(14, 'test APid', 'test APId', 1, 1, 0, 0, 1, '2023-04-17 00:16:47', NULL, NULL, NULL, NULL),
(15, 'test APid', 'test APId', 1, 1, 0, 0, 1, '2023-04-17 00:30:51', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` int(11) NOT NULL,
  `ticket_type_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_types`
--

INSERT INTO `ticket_types` (`id`, `ticket_type_name`) VALUES
(1, 'Normal'),
(2, 'Feature Request'),
(3, 'Test Case');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `image`, `password`, `role_id`, `active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ខេង សុខលីម', 'admin', '/assets/images/user_profiles/1678528771.jpg', '$2y$10$8V5XAeZRsJvN4u3TSQpAVevry4s78igDCKMmMaXZi.Uxu/IXTBNJC', 1, 1, NULL, '2022-11-28 19:24:57', '2023-03-11 10:00:35'),
(3, 'RD 001', 'RD001', NULL, '$2y$10$rgAiza07vkYlNOtU5T35Zu9Xxj9yjO0dcYJ3T9SXuX/B4lOcU1cNG', 3, 1, NULL, '2022-12-03 20:46:58', '2023-04-16 13:47:11'),
(4, 'PM 001', 'PM001', NULL, '$2y$10$cxuiC3xWkyZGdn6SkxWHduJjKmwzAN5jHJYMQpB2Pb29VkDS7U.66', 4, 1, NULL, '2022-12-03 20:56:25', '2023-04-16 13:47:06'),
(14, 'QA 001', 'QA001', NULL, '$2y$10$2BVDY8tNKzXhn4grhVPUDuXs1ghnRwN14urNAezHRVQcZ/GcN73SW', 2, 1, NULL, '2023-04-16 13:46:59', '2023-04-16 13:47:30');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_mccd`
-- (See below for the actual view)
--
CREATE TABLE `view_mccd` (
);

-- --------------------------------------------------------

--
-- Structure for view `view_mccd`
--
DROP TABLE IF EXISTS `view_mccd`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_mccd`  AS SELECT 1 AS `levelId`, 0 AS `question_id`, `d`.`id` AS `section_id`, `d`.`description` AS `description`, `d`.`description_kh` AS `description_kh`, 0 AS `answer_type`, 0 AS `setting_type_id`, 0 AS `order_no` FROM `medical_sections` AS `d` union all select 2 AS `levelId`,`q`.`id` AS `question_id`,`q`.`section_id` AS `section_id`,ifnull(`q`.`question`,'') AS `description`,ifnull(`q`.`question_kh`,'') AS `description_kh`,`q`.`answer_type` AS `answer_type`,`q`.`setting_type_id` AS `setting_type_id`,`q`.`order_no` AS `order_no` from `medical_questions` `q`  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assign_ticket_types`
--
ALTER TABLE `assign_ticket_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `group_modules`
--
ALTER TABLE `group_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_permissions`
--
ALTER TABLE `module_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statuss`
--
ALTER TABLE `statuss`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assign_ticket_types`
--
ALTER TABLE `assign_ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_modules`
--
ALTER TABLE `group_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `module_permissions`
--
ALTER TABLE `module_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `statuss`
--
ALTER TABLE `statuss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
