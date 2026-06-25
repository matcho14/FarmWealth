-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2026 at 04:11 PM
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
-- Database: `farmwealth`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_type` varchar(50) NOT NULL,
  `is_parent` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `shed_id` bigint(20) UNSIGNED DEFAULT NULL,
  `linkable_type` varchar(255) DEFAULT NULL,
  `linkable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`id`, `code`, `name`, `parent_id`, `account_type`, `is_parent`, `is_active`, `opening_balance`, `shed_id`, `linkable_type`, `linkable_id`, `created_at`, `updated_at`) VALUES
(1, '1000', 'الأصول', NULL, 'asset_current', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(2, '1100', 'الأصول المتداولة', 1, 'asset_current', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(3, '1110', 'الصندوق والنقدية', 2, 'asset_current', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(4, '1120', 'العملاء', 2, 'asset_current', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(5, '1130', 'المخازن', 2, 'asset_current', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(6, '1140', 'المدفوعات المقدمة', 2, 'asset_current', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(7, '1200', 'الأصول الثابتة', 1, 'asset_fixed', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(8, '1210', 'المباني والمنشآت', 7, 'asset_fixed', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(9, '1220', 'الأثاث والمعدات', 7, 'asset_fixed', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(10, '1230', 'مركبات', 7, 'asset_fixed', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(11, '1240', 'أصول أخرى', 7, 'asset_fixed', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(12, '2000', 'الخصوم', NULL, 'liability_current', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(13, '2100', 'الخصوم المتداولة', 12, 'liability_current', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(14, '2110', 'الموردين', 13, 'liability_current', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(15, '2120', 'المقبوضات المقدمة', 13, 'liability_current', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(16, '2130', 'الضرائب المدفوعة', 13, 'liability_current', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(17, '2200', 'الخصوم طويلة الأجل', 12, 'liability_long', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(18, '2210', 'قروض طويلة الأجل', 17, 'liability_long', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(19, '2220', 'خصوم أخرى', 17, 'liability_long', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(20, '3000', 'حقوق الملكية', NULL, 'equity', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(21, '3100', 'رأس المال', 20, 'equity', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(22, '3200', 'الأرباح المحتجزة', 20, 'equity', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(23, '3300', 'تسويات الأرباح', 20, 'equity', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(24, '4000', 'الإيرادات', NULL, 'revenue', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(25, '4100', 'إيرادات المبيعات', 24, 'revenue', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(26, '4200', 'إيرادات أخرى', 24, 'revenue', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(27, '5000', 'المصاريف', NULL, 'expense', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(28, '5100', 'مصاريف الإنتاج', 27, 'expense', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(29, '5110', 'علف', 28, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(30, '5120', 'أدوية وعلاجات', 28, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(31, '5130', 'كهرباء', 28, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(32, '5140', 'غاز ووقود', 28, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(33, '5150', 'مياه', 28, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(34, '5160', 'رواتب وعمولات', 28, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(35, '5170', 'صيانة', 28, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(36, '5180', 'فحوصات واختبارات', 28, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(37, '5190', 'مصاريف إنتاج أخرى', 28, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(38, '5200', 'مصاريف إدارية وعمومية', 27, 'expense', 1, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(39, '5210', 'إيجارات', 38, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(40, '5220', 'رواتب إدارية', 38, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(41, '5230', 'مستلزمات مكتبية', 38, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(42, '5240', 'اتصالات وإنترنت', 38, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(43, '5250', 'تسويق وإعلان', 38, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(44, '5260', 'تأمينات', 38, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(45, '5270', 'مصاريف إدارية أخرى', 38, 'expense', 0, 1, 0.00, NULL, NULL, NULL, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(46, '11201', 'محمد كمال', 4, 'asset_current', 0, 1, 0.00, NULL, 'App\\Models\\Client', 1, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(47, '2201', 'اسلام هايدا', 14, 'liability_current', 0, 1, 0.00, NULL, 'App\\Models\\Supplier', 1, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(48, '2202', 'ايفا', 14, 'liability_current', 0, 1, 0.00, NULL, 'App\\Models\\Supplier', 2, '2026-06-23 14:30:53', '2026-06-23 14:30:53'),
(49, '2203', 'مورد علف', 14, 'liability_current', 0, 1, 0.00, NULL, 'App\\Models\\Supplier', 3, '2026-06-23 14:30:53', '2026-06-23 14:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `opening_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `phone`, `email`, `address`, `opening_balance`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'محمد كمال', '01272324240', 'mohamed.madkour198614@gmail.com', 'cairo - egypt', 0.00, NULL, '2026-06-15 13:48:45', '2026-06-15 13:48:45');

-- --------------------------------------------------------

--
-- Table structure for table `cycles`
--

CREATE TABLE `cycles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shed_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `initial_chicks` int(11) NOT NULL,
  `floor_chicks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`floor_chicks`)),
  `mortality_count` int(11) NOT NULL DEFAULT 0,
  `sold_chicks` int(11) DEFAULT NULL,
  `total_weight` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','completed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cycles`
--

INSERT INTO `cycles` (`id`, `shed_id`, `start_date`, `end_date`, `initial_chicks`, `floor_chicks`, `mortality_count`, `sold_chicks`, `total_weight`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-05-01', NULL, 20000, '{\"1\":7000,\"2\":7000,\"3\":6000}', 0, NULL, 0.00, 'active', '2026-05-12 12:26:37', '2026-05-12 12:26:37'),
(2, 1, '2026-05-18', NULL, 25000, '{\"1\":7000,\"2\":9000,\"3\":9000}', 0, NULL, 0.00, 'active', '2026-05-18 18:20:25', '2026-05-18 18:20:25'),
(3, 2, '2026-05-18', NULL, 22500, '{\"1\":10500,\"2\":12000}', 0, NULL, 0.00, 'active', '2026-05-18 18:22:39', '2026-05-18 18:22:39'),
(4, 4, '2026-05-23', NULL, 30000, '{\"1\":10000,\"2\":10000,\"3\":10000}', 0, NULL, 0.00, 'active', '2026-05-23 16:12:57', '2026-05-23 16:12:57'),
(5, 5, '2026-05-30', NULL, 33800, '{\"1\":8450,\"2\":8450,\"3\":8450,\"4\":8450}', 0, NULL, 0.00, 'active', '2026-05-30 22:23:49', '2026-05-30 22:23:49'),
(6, 6, '2026-06-16', NULL, 30000, '{\"1\":10000,\"2\":10000,\"3\":10000}', 0, NULL, 0.00, 'active', '2026-06-16 20:20:19', '2026-06-16 20:20:19'),
(7, 7, '2026-06-21', NULL, 30000, '{\"1\":10000,\"2\":10000,\"3\":10000}', 0, NULL, 0.00, 'active', '2026-06-21 17:19:56', '2026-06-21 17:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `cycle_dispensations`
--

CREATE TABLE `cycle_dispensations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cycle_id` bigint(20) UNSIGNED NOT NULL,
  `shed_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `floor_number` int(11) NOT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `dispensation_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cycle_dispensations`
--

INSERT INTO `cycle_dispensations` (`id`, `cycle_id`, `shed_id`, `item_id`, `floor_number`, `quantity`, `unit_cost`, `total_cost`, `dispensation_date`, `notes`, `created_at`, `updated_at`) VALUES
(3, 1, 1, 1, 1, 10.000, 1000.00, 10000.00, '2026-05-12', NULL, '2026-05-12 12:34:47', '2026-05-12 12:34:47'),
(4, 5, 5, 3, 1, 30.000, 120.00, 3600.00, '2026-05-31', NULL, '2026-05-31 14:59:09', '2026-05-31 14:59:09'),
(5, 5, 5, 3, 3, 50.000, 120.00, 6000.00, '2026-05-31', NULL, '2026-05-31 15:08:35', '2026-05-31 15:08:35'),
(6, 5, 5, 3, 1, 20.000, 120.00, 2400.00, '2026-06-15', NULL, '2026-06-15 14:30:39', '2026-06-15 14:30:39'),
(7, 5, 5, 3, 2, 20.000, 120.00, 2400.00, '2026-06-15', NULL, '2026-06-15 14:31:40', '2026-06-15 14:31:40'),
(8, 6, 6, 3, 1, 25.000, 120.00, 3000.00, '2026-06-16', NULL, '2026-06-16 20:22:44', '2026-06-16 20:22:44'),
(9, 6, 6, 3, 2, 25.000, 120.00, 3000.00, '2026-06-16', NULL, '2026-06-16 20:22:53', '2026-06-16 20:22:53'),
(10, 6, 6, 3, 3, 25.000, 120.00, 3000.00, '2026-06-16', NULL, '2026-06-16 20:23:00', '2026-06-16 20:23:00'),
(11, 7, 7, 5, 1, 20.000, 150.00, 3000.00, '2026-06-21', NULL, '2026-06-21 17:24:32', '2026-06-21 17:24:32'),
(12, 7, 7, 5, 2, 20.000, 150.00, 3000.00, '2026-06-21', NULL, '2026-06-21 17:25:16', '2026-06-21 17:25:16'),
(13, 7, 7, 5, 3, 20.000, 150.00, 3000.00, '2026-06-21', NULL, '2026-06-21 17:25:22', '2026-06-21 17:25:22');

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
-- Table structure for table `financial_records`
--

CREATE TABLE `financial_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cycle_id` bigint(20) UNSIGNED NOT NULL,
  `shed_id` bigint(20) UNSIGNED DEFAULT NULL,
  `chart_of_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('expense','revenue') NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `floor_number` int(11) DEFAULT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dispensation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_type` enum('cash','credit') NOT NULL DEFAULT 'cash',
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text NOT NULL,
  `record_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `treasury_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `financial_records`
--

INSERT INTO `financial_records` (`id`, `cycle_id`, `shed_id`, `chart_of_account_id`, `type`, `quantity`, `floor_number`, `item_id`, `dispensation_id`, `weight`, `amount`, `paid_amount`, `payment_type`, `client_id`, `description`, `record_date`, `created_at`, `updated_at`, `treasury_id`) VALUES
(1, 1, NULL, NULL, 'expense', 10, 1, 1, 3, NULL, 10000.00, 0.00, 'cash', NULL, 'صرف صنف: تيلمى - دور 1 - ', '2026-05-12', '2026-05-12 12:34:47', '2026-05-12 12:34:47', NULL),
(2, 1, NULL, NULL, 'expense', NULL, NULL, NULL, NULL, NULL, 2000.00, 0.00, 'cash', NULL, 'شيل رتش', '2026-05-12', '2026-05-12 12:45:08', '2026-05-12 12:45:08', 2),
(3, 1, NULL, NULL, 'revenue', NULL, NULL, NULL, NULL, NULL, 2000.00, 0.00, 'cash', NULL, 'بيع سبلة', '2026-05-12', '2026-05-12 12:46:03', '2026-05-12 12:46:03', 2),
(4, 1, NULL, NULL, 'revenue', 2000, NULL, NULL, NULL, 4000.00, 50000.00, 0.00, 'cash', NULL, 'مبيعات كتاكيت (2000)', '2026-05-12', '2026-05-12 12:46:20', '2026-05-12 12:46:20', 1),
(5, 5, NULL, NULL, 'expense', 30, 1, 3, 4, NULL, 3600.00, 0.00, 'cash', NULL, 'صرف صنف: علف بادي - دور 1 - ', '2026-05-31', '2026-05-31 14:59:09', '2026-05-31 14:59:09', NULL),
(6, 5, NULL, NULL, 'expense', 50, 3, 3, 5, NULL, 6000.00, 0.00, 'cash', NULL, 'صرف صنف: علف بادي - دور 3 - ', '2026-05-31', '2026-05-31 15:08:35', '2026-05-31 15:08:35', NULL),
(7, 5, NULL, NULL, 'revenue', 1000, NULL, NULL, NULL, 2500.00, 175000.00, 0.00, 'credit', 1, 'مبيعات كتاكيت (1000)', '2026-06-15', '2026-06-15 14:26:43', '2026-06-15 14:26:43', NULL),
(8, 5, NULL, NULL, 'expense', 20, 1, 3, 6, NULL, 2400.00, 0.00, 'cash', NULL, 'صرف صنف: علف تجربة - دور 1 - ', '2026-06-15', '2026-06-15 14:30:39', '2026-06-15 14:30:39', NULL),
(9, 5, NULL, NULL, 'expense', 20, 2, 3, 7, NULL, 2400.00, 0.00, 'cash', NULL, 'صرف صنف: علف تجربة - دور 2 - ', '2026-06-15', '2026-06-15 14:31:40', '2026-06-15 14:31:40', NULL),
(10, 6, NULL, NULL, 'expense', 25, 1, 3, 8, NULL, 3000.00, 0.00, 'cash', NULL, 'صرف صنف: علف تجربة - دور 1 - ', '2026-06-16', '2026-06-16 20:22:44', '2026-06-16 20:22:44', NULL),
(11, 6, NULL, NULL, 'expense', 25, 2, 3, 9, NULL, 3000.00, 0.00, 'cash', NULL, 'صرف صنف: علف تجربة - دور 2 - ', '2026-06-16', '2026-06-16 20:22:53', '2026-06-16 20:22:53', NULL),
(12, 6, NULL, NULL, 'expense', 25, 3, 3, 10, NULL, 3000.00, 0.00, 'cash', NULL, 'صرف صنف: علف تجربة - دور 3 - ', '2026-06-16', '2026-06-16 20:23:00', '2026-06-16 20:23:00', NULL),
(13, 6, NULL, NULL, 'revenue', 1000, NULL, NULL, NULL, 2500.00, 175000.00, 0.00, 'cash', 1, 'مبيعات كتاكيت (1000)', '2026-06-16', '2026-06-16 20:24:06', '2026-06-17 11:29:24', 1),
(14, 6, NULL, NULL, 'revenue', 500, NULL, NULL, NULL, 1250.00, 120000.00, 120000.00, 'cash', 1, 'مبيعات كتاكيت (500)', '2026-06-17', '2026-06-17 14:00:01', '2026-06-17 14:00:01', 1),
(15, 6, NULL, NULL, 'revenue', 500, NULL, NULL, NULL, 1250.00, 120000.00, 0.00, 'credit', 1, 'مبيعات كتاكيت (500)', '2026-06-17', '2026-06-17 14:00:40', '2026-06-17 14:01:01', NULL),
(16, 5, NULL, NULL, 'revenue', 1000, NULL, NULL, NULL, 2500.00, 180000.00, 180000.00, 'cash', 1, 'مبيعات كتاكيت (1000)', '2026-06-21', '2026-06-21 16:15:24', '2026-06-21 16:15:24', 1),
(17, 7, NULL, NULL, 'expense', 20, 1, 5, 11, NULL, 3000.00, 0.00, 'cash', NULL, 'صرف صنف: علف بادى عادى - دور 1 - ', '2026-06-21', '2026-06-21 17:24:32', '2026-06-21 17:24:32', NULL),
(18, 7, NULL, NULL, 'expense', 20, 2, 5, 12, NULL, 3000.00, 0.00, 'cash', NULL, 'صرف صنف: علف بادى عادى - دور 2 - ', '2026-06-21', '2026-06-21 17:25:16', '2026-06-21 17:25:16', NULL),
(19, 7, NULL, NULL, 'expense', 20, 3, 5, 13, NULL, 3000.00, 0.00, 'cash', NULL, 'صرف صنف: علف بادى عادى - دور 3 - ', '2026-06-21', '2026-06-21 17:25:22', '2026-06-21 17:25:22', NULL),
(20, 7, NULL, NULL, 'revenue', 1000, NULL, NULL, NULL, 2500.00, 175000.00, 175000.00, 'cash', 1, 'مبيعات كتاكيت (1000)', '2026-06-21', '2026-06-21 17:26:23', '2026-06-21 17:26:23', 1),
(21, 7, 7, 31, 'expense', NULL, NULL, NULL, NULL, NULL, 2000.00, 0.00, 'cash', NULL, 'فاتورة كهرباء', '2026-06-23', '2026-06-23 16:14:29', '2026-06-23 16:14:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transfers`
--

CREATE TABLE `inventory_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `shed_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `transfer_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_transfers`
--

INSERT INTO `inventory_transfers` (`id`, `item_id`, `shed_id`, `quantity`, `unit_cost`, `total_cost`, `transfer_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10.000, 1000.00, 10000.00, '2026-05-12', NULL, '2026-05-12 12:32:50', '2026-05-12 12:32:50'),
(2, 3, 5, 1.000, 1000.00, 1000.00, '2026-05-31', NULL, '2026-05-31 12:17:14', '2026-05-31 12:17:14'),
(3, 3, 5, 200.000, 120.00, 24000.00, '2026-05-31', NULL, '2026-05-31 14:58:38', '2026-05-31 14:58:38'),
(4, 3, 6, 200.000, 120.00, 24000.00, '2026-06-16', NULL, '2026-06-16 20:22:21', '2026-06-16 20:22:21'),
(5, 5, 7, 100.000, 150.00, 15000.00, '2026-06-21', NULL, '2026-06-21 17:24:03', '2026-06-21 17:24:03');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` enum('feed','medicine','other') NOT NULL DEFAULT 'other',
  `unit` varchar(255) NOT NULL DEFAULT 'كيلو',
  `quantity_in_stock` decimal(15,3) NOT NULL DEFAULT 0.000,
  `last_purchase_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `category`, `unit`, `quantity_in_stock`, `last_purchase_price`, `supplier_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'تيلمى', 'other', 'علبة', 90.000, 1000.00, NULL, NULL, '2026-05-12 12:28:43', '2026-05-12 12:32:50'),
(3, 'علف تجربة', 'feed', 'شيكارة', 1600.000, 120.00, NULL, NULL, '2026-05-30 22:44:45', '2026-06-16 20:22:21'),
(4, 'علف بادى مميز', 'feed', 'شيكارة', 0.000, 0.00, NULL, NULL, '2026-05-31 15:06:45', '2026-05-31 15:09:55'),
(5, 'علف بادى عادى', 'feed', 'شيكارة', 100.000, 150.00, NULL, NULL, '2026-05-31 15:06:45', '2026-06-21 17:24:03'),
(6, 'علف نامى مميز', 'feed', 'شيكارة', 0.000, 0.00, NULL, NULL, '2026-05-31 15:06:45', '2026-05-31 15:09:55'),
(7, 'علف نامى عادى', 'feed', 'شيكارة', 0.000, 0.00, NULL, NULL, '2026-05-31 15:06:45', '2026-05-31 15:09:55'),
(8, 'علف ناهى عادى', 'feed', 'شيكارة', 0.000, 0.00, NULL, NULL, '2026-05-31 15:06:45', '2026-05-31 15:09:55'),
(9, 'علف ناهى مميز', 'feed', 'شيكارة', 0.000, 0.00, NULL, NULL, '2026-05-31 15:06:45', '2026-05-31 15:09:55'),
(10, 'علف بياض 18 %', 'feed', 'شيكارة', 0.000, 0.00, NULL, NULL, '2026-05-31 15:06:45', '2026-05-31 15:09:55'),
(11, 'علف بياض 17 %', 'feed', 'شيكارة', 0.000, 0.00, NULL, NULL, '2026-05-31 15:06:45', '2026-05-31 15:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entry_number` varchar(255) NOT NULL,
  `entry_date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `entry_number`, `entry_date`, `description`, `reference_type`, `reference_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'PUR-2026-0001', '2026-05-12', 'فاتورة شراء رقم PUR-2026-0001', 'purchase_invoice', 1, NULL, '2026-05-12 12:29:06', '2026-05-12 12:29:06'),
(2, 'JE-2026-0002', '2026-05-12', 'تحويل عهدة', 'manual', NULL, NULL, '2026-05-12 12:39:28', '2026-05-12 12:39:28'),
(3, 'JE-2026-0003', '2026-05-12', 'مصروف دورة: شيل رتش', 'financial_record', 2, NULL, '2026-05-12 12:45:08', '2026-05-12 12:45:08'),
(4, 'JE-2026-0004', '2026-05-12', 'إيراد دورة: بيع سبلة', 'financial_record', 3, NULL, '2026-05-12 12:46:03', '2026-05-12 12:46:03'),
(5, 'JE-2026-0005', '2026-05-12', 'إيراد مبيعات دورة: مبيعات كتاكيت (2000)', 'financial_record', 4, NULL, '2026-05-12 12:46:20', '2026-05-12 12:46:20'),
(6, 'JE-2026-0006', '2026-05-30', 'دندنة', 'manual', NULL, NULL, '2026-05-30 22:46:26', '2026-05-30 22:46:26'),
(7, 'PUR-2026-0002', '2026-05-31', 'فاتورة شراء رقم PUR-2026-0002', 'purchase_invoice', 2, NULL, '2026-05-31 12:15:58', '2026-05-31 12:15:58'),
(8, 'PUR-2026-0003', '2026-05-31', 'فاتورة شراء رقم PUR-2026-0003', 'purchase_invoice', 3, NULL, '2026-05-31 14:53:48', '2026-05-31 14:53:48'),
(9, 'JE-2026-0009', '2026-05-31', 'قيد استهلاك مخزون - دورة #5 - علف بادي', 'financial_record', 5, NULL, '2026-05-31 14:59:09', '2026-05-31 14:59:09'),
(10, 'JE-2026-0010', '2026-05-31', 'قيد استهلاك مخزون - دورة #5 - علف بادي', 'financial_record', 6, NULL, '2026-05-31 15:08:35', '2026-05-31 15:08:35'),
(11, 'JE-2026-0011', '2026-06-15', 'مبيعات دورة: مبيعات كتاكيت (1000)', 'financial_record', 7, NULL, '2026-06-15 14:26:43', '2026-06-15 14:26:43'),
(12, 'JE-2026-0012', '2026-06-15', 'قيد استهلاك مخزون - دورة #5 - علف تجربة', 'financial_record', 8, NULL, '2026-06-15 14:30:39', '2026-06-15 14:30:39'),
(13, 'JE-2026-0013', '2026-06-15', 'قيد استهلاك مخزون - دورة #5 - علف تجربة', 'financial_record', 9, NULL, '2026-06-15 14:31:40', '2026-06-15 14:31:40'),
(14, 'JE-2026-0014', '2026-06-16', 'قيد استهلاك مخزون - دورة #6 - علف تجربة', 'financial_record', 10, NULL, '2026-06-16 20:22:44', '2026-06-16 20:22:44'),
(15, 'JE-2026-0015', '2026-06-16', 'قيد استهلاك مخزون - دورة #6 - علف تجربة', 'financial_record', 11, NULL, '2026-06-16 20:22:53', '2026-06-16 20:22:53'),
(16, 'JE-2026-0016', '2026-06-16', 'قيد استهلاك مخزون - دورة #6 - علف تجربة', 'financial_record', 12, NULL, '2026-06-16 20:23:00', '2026-06-16 20:23:00'),
(17, 'JE-2026-0017', '2026-06-16', 'مبيعات دورة: مبيعات كتاكيت (1000)', 'financial_record', 13, NULL, '2026-06-16 20:24:06', '2026-06-16 20:24:06'),
(18, 'JE-2026-0018', '2026-06-17', 'مبيعات دورة: مبيعات كتاكيت (500)', 'financial_record', 14, NULL, '2026-06-17 14:00:01', '2026-06-17 14:00:01'),
(19, 'JE-2026-0019', '2026-06-17', 'مبيعات دورة: مبيعات كتاكيت (500)', 'financial_record', 15, NULL, '2026-06-17 14:00:40', '2026-06-17 14:00:40'),
(20, 'JE-2026-0020', '2026-06-21', 'مبيعات دورة: مبيعات كتاكيت (1000)', 'financial_record', 16, NULL, '2026-06-21 16:15:24', '2026-06-21 16:15:24'),
(21, 'PUR-2026-0004', '2026-06-21', 'فاتورة شراء رقم 150', 'purchase_invoice', 4, NULL, '2026-06-21 17:22:16', '2026-06-21 17:22:16'),
(22, 'JE-2026-0022', '2026-06-21', 'سداد', 'manual', NULL, NULL, '2026-06-21 17:23:14', '2026-06-21 17:23:14'),
(23, 'JE-2026-0023', '2026-06-21', 'قيد استهلاك مخزون - دورة #7 - علف بادى عادى', 'financial_record', 17, NULL, '2026-06-21 17:24:32', '2026-06-21 17:24:32'),
(24, 'JE-2026-0024', '2026-06-21', 'قيد استهلاك مخزون - دورة #7 - علف بادى عادى', 'financial_record', 18, NULL, '2026-06-21 17:25:16', '2026-06-21 17:25:16'),
(25, 'JE-2026-0025', '2026-06-21', 'قيد استهلاك مخزون - دورة #7 - علف بادى عادى', 'financial_record', 19, NULL, '2026-06-21 17:25:22', '2026-06-21 17:25:22'),
(26, 'JE-2026-0026', '2026-06-21', 'مبيعات دورة: مبيعات كتاكيت (1000)', 'financial_record', 20, NULL, '2026-06-21 17:26:23', '2026-06-21 17:26:23'),
(27, 'JE-2026-0027', '2026-06-23', 'مصروف دورة: فاتورة كهرباء', 'financial_record', 21, NULL, '2026-06-23 16:14:29', '2026-06-23 16:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entry_lines`
--

CREATE TABLE `journal_entry_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_entry_id` bigint(20) UNSIGNED NOT NULL,
  `account_type` varchar(255) NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entry_lines`
--

INSERT INTO `journal_entry_lines` (`id`, `journal_entry_id`, `account_type`, `account_id`, `debit`, `credit`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'supplier', 1, 0.00, 100000.00, 'فاتورة شراء رقم PUR-2026-0001', '2026-05-12 12:29:06', '2026-05-12 12:29:06'),
(2, 2, 'treasury', 2, 50000.00, 0.00, NULL, '2026-05-12 12:39:28', '2026-05-12 12:39:28'),
(3, 2, 'treasury', 1, 0.00, 50000.00, NULL, '2026-05-12 12:39:28', '2026-05-12 12:39:28'),
(4, 3, 'cycle', 1, 2000.00, 0.00, 'شيل رتش', '2026-05-12 12:45:08', '2026-05-12 12:45:08'),
(5, 3, 'treasury', 2, 0.00, 2000.00, 'شيل رتش', '2026-05-12 12:45:08', '2026-05-12 12:45:08'),
(6, 4, 'treasury', 2, 2000.00, 0.00, 'بيع سبلة', '2026-05-12 12:46:03', '2026-05-12 12:46:03'),
(7, 4, 'cycle', 1, 0.00, 2000.00, 'بيع سبلة', '2026-05-12 12:46:03', '2026-05-12 12:46:03'),
(8, 5, 'treasury', 1, 50000.00, 0.00, 'مبيعات كتاكيت (2000)', '2026-05-12 12:46:20', '2026-05-12 12:46:20'),
(9, 5, 'cycle', 1, 0.00, 50000.00, 'مبيعات كتاكيت (2000)', '2026-05-12 12:46:20', '2026-05-12 12:46:20'),
(10, 6, 'supplier', 2, 0.00, 0.00, NULL, '2026-05-30 22:46:26', '2026-05-30 22:46:26'),
(11, 6, 'treasury', 1, 0.00, 0.00, NULL, '2026-05-30 22:46:26', '2026-05-30 22:46:26'),
(12, 7, 'supplier', 2, 0.00, 1000.00, 'فاتورة شراء رقم PUR-2026-0002', '2026-05-31 12:15:58', '2026-05-31 12:15:58'),
(13, 8, 'supplier', 2, 0.00, 240000.00, 'فاتورة شراء رقم PUR-2026-0003', '2026-05-31 14:53:48', '2026-05-31 14:53:48'),
(14, 9, 'cycle', 5, 3600.00, 0.00, 'استهلاك صنف علف بادي', '2026-05-31 14:59:09', '2026-05-31 14:59:09'),
(15, 9, 'item', 3, 0.00, 3600.00, 'صرف من مخزن العنبر', '2026-05-31 14:59:09', '2026-05-31 14:59:09'),
(16, 10, 'cycle', 5, 6000.00, 0.00, 'استهلاك صنف علف بادي', '2026-05-31 15:08:35', '2026-05-31 15:08:35'),
(17, 10, 'item', 3, 0.00, 6000.00, 'صرف من مخزن العنبر', '2026-05-31 15:08:35', '2026-05-31 15:08:35'),
(18, 11, 'client', 1, 175000.00, 0.00, 'مبيعات كتاكيت (1000)', '2026-06-15 14:26:43', '2026-06-15 14:26:43'),
(19, 11, 'cycle', 5, 0.00, 175000.00, 'إيرادات مبيعات', '2026-06-15 14:26:43', '2026-06-15 14:26:43'),
(20, 12, 'cycle', 5, 2400.00, 0.00, 'استهلاك صنف علف تجربة', '2026-06-15 14:30:39', '2026-06-15 14:30:39'),
(21, 12, 'item', 3, 0.00, 2400.00, 'صرف من مخزن العنبر', '2026-06-15 14:30:39', '2026-06-15 14:30:39'),
(22, 13, 'cycle', 5, 2400.00, 0.00, 'استهلاك صنف علف تجربة', '2026-06-15 14:31:40', '2026-06-15 14:31:40'),
(23, 13, 'item', 3, 0.00, 2400.00, 'صرف من مخزن العنبر', '2026-06-15 14:31:40', '2026-06-15 14:31:40'),
(24, 14, 'cycle', 6, 3000.00, 0.00, 'استهلاك صنف علف تجربة', '2026-06-16 20:22:44', '2026-06-16 20:22:44'),
(25, 14, 'item', 3, 0.00, 3000.00, 'صرف من مخزن العنبر', '2026-06-16 20:22:44', '2026-06-16 20:22:44'),
(26, 15, 'cycle', 6, 3000.00, 0.00, 'استهلاك صنف علف تجربة', '2026-06-16 20:22:53', '2026-06-16 20:22:53'),
(27, 15, 'item', 3, 0.00, 3000.00, 'صرف من مخزن العنبر', '2026-06-16 20:22:53', '2026-06-16 20:22:53'),
(28, 16, 'cycle', 6, 3000.00, 0.00, 'استهلاك صنف علف تجربة', '2026-06-16 20:23:00', '2026-06-16 20:23:00'),
(29, 16, 'item', 3, 0.00, 3000.00, 'صرف من مخزن العنبر', '2026-06-16 20:23:00', '2026-06-16 20:23:00'),
(34, 17, 'treasury', 1, 175000.00, 0.00, 'مبيعات كتاكيت (1000) - مبيعات كاش', '2026-06-17 11:29:24', '2026-06-17 11:29:24'),
(35, 17, 'cycle', 6, 0.00, 175000.00, 'إيرادات مبيعات', '2026-06-17 11:29:24', '2026-06-17 11:29:24'),
(36, 18, 'treasury', 1, 120000.00, 0.00, 'مبيعات كتاكيت (500) - مبيعات كاش', '2026-06-17 14:00:01', '2026-06-17 14:00:01'),
(37, 18, 'cycle', 6, 0.00, 120000.00, 'إيرادات مبيعات', '2026-06-17 14:00:01', '2026-06-17 14:00:01'),
(40, 19, 'client', 1, 120000.00, 0.00, 'مبيعات كتاكيت (500) - مبيعات آجلة', '2026-06-17 14:01:01', '2026-06-17 14:01:01'),
(41, 19, 'cycle', 6, 0.00, 120000.00, 'إيرادات مبيعات', '2026-06-17 14:01:01', '2026-06-17 14:01:01'),
(42, 20, 'treasury', 1, 180000.00, 0.00, 'مبيعات كتاكيت (1000) - مبيعات كاش', '2026-06-21 16:15:24', '2026-06-21 16:15:24'),
(43, 20, 'cycle', 5, 0.00, 180000.00, 'إيرادات مبيعات', '2026-06-21 16:15:24', '2026-06-21 16:15:24'),
(44, 21, 'supplier', 3, 0.00, 30000.00, 'فاتورة شراء رقم 150', '2026-06-21 17:22:16', '2026-06-21 17:22:16'),
(45, 22, 'supplier', 3, 30000.00, 0.00, NULL, '2026-06-21 17:23:14', '2026-06-21 17:23:14'),
(46, 22, 'treasury', 1, 0.00, 30000.00, NULL, '2026-06-21 17:23:14', '2026-06-21 17:23:14'),
(47, 23, 'cycle', 7, 3000.00, 0.00, 'استهلاك صنف علف بادى عادى', '2026-06-21 17:24:32', '2026-06-21 17:24:32'),
(48, 23, 'item', 5, 0.00, 3000.00, 'صرف من مخزن العنبر', '2026-06-21 17:24:32', '2026-06-21 17:24:32'),
(49, 24, 'cycle', 7, 3000.00, 0.00, 'استهلاك صنف علف بادى عادى', '2026-06-21 17:25:16', '2026-06-21 17:25:16'),
(50, 24, 'item', 5, 0.00, 3000.00, 'صرف من مخزن العنبر', '2026-06-21 17:25:16', '2026-06-21 17:25:16'),
(51, 25, 'cycle', 7, 3000.00, 0.00, 'استهلاك صنف علف بادى عادى', '2026-06-21 17:25:23', '2026-06-21 17:25:23'),
(52, 25, 'item', 5, 0.00, 3000.00, 'صرف من مخزن العنبر', '2026-06-21 17:25:23', '2026-06-21 17:25:23'),
(53, 26, 'treasury', 1, 175000.00, 0.00, 'مبيعات كتاكيت (1000) - مبيعات كاش', '2026-06-21 17:26:23', '2026-06-21 17:26:23'),
(54, 26, 'cycle', 7, 0.00, 175000.00, 'إيرادات مبيعات', '2026-06-21 17:26:23', '2026-06-21 17:26:23'),
(55, 27, 'chart_of_account', 31, 2000.00, 0.00, 'فاتورة كهرباء', '2026-06-23 16:14:29', '2026-06-23 16:14:29'),
(56, 27, 'treasury', 1, 0.00, 2000.00, 'فاتورة كهرباء', '2026-06-23 16:14:29', '2026-06-23 16:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine_dispensations`
--

CREATE TABLE `medicine_dispensations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `medicine_id` bigint(20) UNSIGNED NOT NULL,
  `cycle_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `dispensation_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine_entries`
--

CREATE TABLE `medicine_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `medicine_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `remaining_quantity` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `entry_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000001_create_sheds_table', 1),
(5, '2024_01_01_000002_create_cycles_table', 1),
(6, '2024_01_01_000003_create_financial_records_table', 1),
(7, '2026_04_13_195001_create_medicines_table', 1),
(8, '2026_04_13_195002_create_medicine_entries_table', 1),
(9, '2026_04_13_195003_create_medicine_dispensations_table', 1),
(10, '2026_04_14_183604_add_weight_to_sales_and_cycles', 1),
(11, '2026_05_11_153945_add_floors_to_sheds_and_cycles_table', 1),
(12, '2026_05_11_153956_add_floor_number_to_mortality_records_table', 1),
(13, '2026_05_11_161046_create_suppliers_table', 1),
(14, '2026_05_11_161047_create_items_table', 1),
(15, '2026_05_11_161048_create_treasuries_table', 1),
(16, '2026_05_11_161059_create_purchase_invoices_table', 1),
(17, '2026_05_11_161100_create_purchase_invoice_items_table', 1),
(18, '2026_05_11_161101_create_journal_entries_table', 1),
(19, '2026_05_11_161102_create_journal_entry_lines_table', 1),
(20, '2026_05_11_163219_create_shed_inventories_table', 1),
(21, '2026_05_11_163221_create_inventory_transfers_table', 1),
(22, '2026_05_11_163222_create_cycle_dispensations_table', 1),
(23, '2026_05_11_163223_add_floor_item_to_financial_records_table', 1),
(24, '2026_05_11_170333_add_role_and_permissions_to_users_table', 1),
(25, '2026_05_12_154235_add_treasury_id_to_financial_records_table', 2),
(26, '2026_05_23_162528_create_units_table', 3),
(27, '2026_05_31_180338_add_category_to_items_table', 4),
(28, '2026_06_15_000000_create_clients_table', 5),
(29, '2026_06_15_000001_create_sale_invoices_table', 5),
(30, '2026_06_15_000002_create_sale_invoice_items_table', 5),
(31, '2026_06_15_000003_add_payment_type_to_financial_records', 6),
(32, '2026_06_17_000001_add_paid_amount_to_financial_records_table', 7),
(33, '2026_06_22_000001_create_chart_of_accounts_table', 8),
(34, '2026_06_22_000002_add_chart_of_account_to_financial_records_table', 8),
(35, '2026_06_22_000003_add_linkable_to_chart_of_accounts_table', 8),
(36, '2026_06_22_000004_normalize_linkable_type_in_chart_of_accounts_table', 8),
(37, '2026_06_23_000001_add_assigned_shed_to_users_table', 8),
(38, '2026_06_23_154236_create_transactions_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `mortality_records`
--

CREATE TABLE `mortality_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cycle_id` bigint(20) UNSIGNED NOT NULL,
  `floor_number` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `record_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mortality_records`
--

INSERT INTO `mortality_records` (`id`, `cycle_id`, `floor_number`, `count`, `record_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 10, '2026-05-18', NULL, '2026-05-18 18:20:57', '2026-05-18 18:20:57'),
(2, 2, 2, 70, '2026-05-18', NULL, '2026-05-18 18:20:57', '2026-05-18 18:20:57'),
(3, 2, 3, 50, '2026-05-18', NULL, '2026-05-18 18:20:57', '2026-05-18 18:20:57'),
(4, 3, 1, 7, '2026-05-15', NULL, '2026-05-30 22:34:48', '2026-05-30 22:34:48'),
(5, 3, 2, 7, '2026-05-15', NULL, '2026-05-30 22:34:48', '2026-05-30 22:34:48'),
(6, 3, 1, 4, '2026-05-16', NULL, '2026-05-30 22:35:20', '2026-05-30 22:35:20'),
(7, 3, 2, 4, '2026-05-16', NULL, '2026-05-30 22:35:20', '2026-05-30 22:35:20'),
(8, 5, 1, 66, '2026-05-30', NULL, '2026-05-30 22:38:12', '2026-05-30 22:38:12'),
(9, 5, 2, 76, '2026-05-30', NULL, '2026-05-30 22:38:12', '2026-05-30 22:38:12'),
(10, 5, 3, 72, '2026-05-30', NULL, '2026-05-30 22:38:12', '2026-05-30 22:38:12'),
(11, 5, 4, 72, '2026-05-30', NULL, '2026-05-30 22:38:12', '2026-05-30 22:38:12'),
(12, 6, 1, 10, '2026-06-17', NULL, '2026-06-16 20:21:09', '2026-06-16 20:21:09'),
(13, 6, 2, 25, '2026-06-18', NULL, '2026-06-16 20:21:32', '2026-06-16 20:21:32'),
(14, 7, 1, 20, '2026-06-21', NULL, '2026-06-21 17:20:35', '2026-06-21 17:20:35');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoices`
--

CREATE TABLE `purchase_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `treasury_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_invoices`
--

INSERT INTO `purchase_invoices` (`id`, `invoice_number`, `supplier_id`, `treasury_id`, `invoice_date`, `total_amount`, `paid_amount`, `payment_status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'PUR-2026-0001', 1, NULL, '2026-05-12', 100000.00, 0.00, 'unpaid', NULL, '2026-05-12 12:29:05', '2026-05-12 12:29:05'),
(2, 'PUR-2026-0002', 2, NULL, '2026-05-31', 1000.00, 0.00, 'unpaid', NULL, '2026-05-31 12:15:58', '2026-05-31 12:15:58'),
(3, 'PUR-2026-0003', 2, NULL, '2026-05-31', 240000.00, 0.00, 'unpaid', NULL, '2026-05-31 14:53:48', '2026-05-31 14:53:48'),
(4, '150', 3, NULL, '2026-06-21', 30000.00, 0.00, 'unpaid', NULL, '2026-06-21 17:22:16', '2026-06-21 17:22:16');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoice_items`
--

CREATE TABLE `purchase_invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_invoice_items`
--

INSERT INTO `purchase_invoice_items` (`id`, `invoice_id`, `item_id`, `quantity`, `unit_price`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100.000, 1000.00, 100000.00, '2026-05-12 12:29:05', '2026-05-12 12:29:05'),
(2, 2, 3, 1.000, 1000.00, 1000.00, '2026-05-31 12:15:58', '2026-05-31 12:15:58'),
(3, 3, 3, 2000.000, 120.00, 240000.00, '2026-05-31 14:53:48', '2026-05-31 14:53:48'),
(4, 4, 5, 200.000, 150.00, 30000.00, '2026-06-21 17:22:16', '2026-06-21 17:22:16');

-- --------------------------------------------------------

--
-- Table structure for table `sale_invoices`
--

CREATE TABLE `sale_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `treasury_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_invoice_items`
--

CREATE TABLE `sale_invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,3) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7yTPxoZXdkFN9gyfKulCWkPLxGLER5nvXXU7K1MB', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibVpNZElpeDV1NDc5WDlYcGxQTGV5aUtJbmIxdmh0ZzNKWUp3aUVjNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1782396190),
('TPjQq9DfezsRzOtHd6dAYqMWUs7Eq9n6R7ly1HD9', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicmZFbkROeWVVeEpBdDY5dWJaWWtmbFJWaGE1Q1V0UVdlSDJUQm91ZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1782241817),
('VnTOeuEVkEAwjFBfkJWJ5t5paqTxqB2NDrwWPdIS', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic1l3R0R5d0xuNklSV3NKaXF4cms4dkFaZksyZVdXa0RGdUhncWRwTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6OTc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBlbnNlLXJlcG9ydD9jaGFydF9vZl9hY2NvdW50X2lkPSZjeWNsZV9pZD0mZnJvbV9kYXRlPSZzaGVkX2lkPTEmdG9fZGF0ZT0iO3M6NToicm91dGUiO3M6MTQ6ImV4cGVuc2UtcmVwb3J0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1782242913);

-- --------------------------------------------------------

--
-- Table structure for table `sheds`
--

CREATE TABLE `sheds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `floors` int(11) NOT NULL DEFAULT 1,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sheds`
--

INSERT INTO `sheds` (`id`, `name`, `description`, `floors`, `status`, `created_at`, `updated_at`) VALUES
(1, 'عنبر الحدادين', NULL, 3, 'active', '2026-05-12 12:26:15', '2026-05-12 12:26:15'),
(2, 'جنزور', NULL, 2, 'active', '2026-05-18 18:22:03', '2026-05-18 18:22:03'),
(4, 'تست 1', NULL, 3, 'active', '2026-05-23 16:12:32', '2026-05-23 16:12:32'),
(5, 'دندنة', 'عنبر دندنة دورة ٢٣-٥-٢٠٢٦', 4, 'active', '2026-05-30 22:22:57', '2026-05-30 22:22:57'),
(6, 'عنبر القاهرة', NULL, 3, 'active', '2026-06-16 20:19:48', '2026-06-16 20:19:48'),
(7, 'عنبر شبين الكوم', NULL, 3, 'active', '2026-06-21 17:19:28', '2026-06-21 17:19:28');

-- --------------------------------------------------------

--
-- Table structure for table `shed_inventories`
--

CREATE TABLE `shed_inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shed_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(15,3) NOT NULL DEFAULT 0.000,
  `avg_unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shed_inventories`
--

INSERT INTO `shed_inventories` (`id`, `shed_id`, `item_id`, `quantity`, `avg_unit_cost`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0.000, 1000.00, '2026-05-12 12:32:50', '2026-05-12 12:34:47'),
(2, 5, 3, 81.000, 120.00, '2026-05-31 12:17:14', '2026-06-15 14:31:40'),
(3, 6, 3, 125.000, 120.00, '2026-06-16 20:22:21', '2026-06-16 20:23:00'),
(4, 7, 5, 40.000, 150.00, '2026-06-21 17:24:03', '2026-06-21 17:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `email`, `address`, `opening_balance`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'ايفا', NULL, NULL, NULL, 0.00, NULL, '2026-05-12 12:28:01', '2026-05-12 12:28:01'),
(2, 'مورد علف', '01000000000', NULL, NULL, 0.00, NULL, '2026-05-23 16:16:39', '2026-05-23 16:16:39'),
(3, 'اسلام هايدا', NULL, NULL, 'طوخ', 100000.00, NULL, '2026-06-21 17:21:33', '2026-06-21 17:21:33');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chart_of_account_id` bigint(20) UNSIGNED NOT NULL,
  `service_usage_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `treasuries`
--

CREATE TABLE `treasuries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `treasuries`
--

INSERT INTO `treasuries` (`id`, `name`, `opening_balance`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'خزينة دكتور عبد الحميد', 1000000.00, NULL, '2026-05-12 12:38:37', '2026-05-12 12:38:37'),
(2, 'خزينة عنبر الحدادين', 0.00, NULL, '2026-05-12 12:38:49', '2026-05-12 12:38:49'),
(3, 'دندنة', 0.00, NULL, '2026-05-30 22:49:47', '2026-05-30 22:49:47'),
(4, 'جنزور', 0.00, NULL, '2026-05-30 22:50:04', '2026-05-30 22:50:04');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'زجاجة', '2026-05-31 14:51:24', '2026-05-31 14:51:24'),
(2, 'كيس', '2026-05-31 14:51:30', '2026-05-31 14:51:30'),
(3, 'شيكارة', '2026-05-31 14:51:36', '2026-05-31 14:51:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `assigned_shed_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `permissions`, `assigned_shed_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'المدير العام', 'admin@admin.com', NULL, '$2y$12$8LeKDfRfGLhOit27/LWafOILdSswcBjKXbu8qWC9WRaoVjW7y9CJO', 'admin', NULL, NULL, NULL, '2026-05-11 14:15:47', '2026-05-11 14:15:47'),
(2, 'موظف تجريبي', 'user@user.com', NULL, '$2y$12$vYThSjxSp2tUs5FRNDy6y.zxfyca0Av9qkh9eHRYwiZs1lcWw7Sfy', 'user', '[\"sheds\",\"cycles\"]', NULL, NULL, '2026-05-11 14:15:47', '2026-05-11 14:15:47'),
(3, 'المدير العام', 'matcho1419@gmail.com', NULL, '$2y$12$3j9zhKwHZWFeMRwp.VlPI.AfO39GTmGtXIWBJNRxCtyoDaJz.BPa.', 'admin', NULL, NULL, 'JNKqSzvswkxEtVKzx3J9MA2gigZNOAtFnqdBZymsu2f55rNkOWVHIHMJ5CMP', '2026-05-11 14:17:26', '2026-05-11 14:17:26'),
(4, 'دكتور عبد الحميد', 'abdohamza89@gmail.com', NULL, '$2y$12$8NA4seUqVpg6MHafgs8IT.1Kzx4eWX.6YoSyMUc25Tssz/o9BGjyS', 'admin', NULL, NULL, 'iVBV12mA1iESydxph5A33KhKM5wxjWWiRyiqh1vOuCa4c5GtuIDPsoRjSWN5', '2026-05-18 18:17:40', '2026-05-18 18:17:40'),
(5, 'ahmed.mohmed', 'ahmed.mohmed1699@gmail.com', NULL, '$2y$12$wBTUUOb82VsJJ.t/ggVJK.AtgE7YTnebwWbnj9foyP3R8.NsgoeCe', 'user', '[\"sheds\",\"suppliers\",\"items\",\"inventory\",\"purchase-invoices\",\"journal-entries\",\"annual-report\"]', NULL, NULL, '2026-05-23 14:49:05', '2026-05-23 14:49:05'),
(6, 'Mostafa Abdelghfour', 'damostafa84@gmail.com', NULL, '$2y$12$GEsPH6.LuRMsPqnVVGwIKe9C/4BIfW/4SlYHgiKcNOSe5iInt7ry6', 'user', '[\"sheds\",\"cycles\",\"inventory\",\"journal-entries\"]', NULL, 'RdvFCOR8eAm3QwTaA3iD4TKdnhVYBOqhQPvLyfE1iTjIn6gzbaQuJOmoeC8T', '2026-05-30 22:28:48', '2026-06-21 16:25:36'),
(7, 'Mohamed Abdelhady', 'vet.mohamed600@gmail.com', NULL, '$2y$12$vL04epYuGuCO2gyGnocTX.kMuRlt5nGmhraPi9OnZ/Xct5.DltXWG', 'user', '[\"sheds\",\"cycles\",\"inventory\",\"journal-entries\"]', NULL, 'NWFL2cw4WLDAVU6xvcVQ5S9iemwfFk4lMFgYrjf3vqgQJfbhtibaGJp9BoSf', '2026-05-30 22:32:24', '2026-05-30 22:38:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chart_of_accounts_code_unique` (`code`),
  ADD KEY `chart_of_accounts_shed_id_foreign` (`shed_id`),
  ADD KEY `chart_of_accounts_account_type_is_parent_index` (`account_type`,`is_parent`),
  ADD KEY `chart_of_accounts_parent_id_index` (`parent_id`),
  ADD KEY `chart_of_accounts_linkable_type_linkable_id_index` (`linkable_type`,`linkable_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cycles`
--
ALTER TABLE `cycles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cycles_shed_id_foreign` (`shed_id`);

--
-- Indexes for table `cycle_dispensations`
--
ALTER TABLE `cycle_dispensations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cycle_dispensations_cycle_id_foreign` (`cycle_id`),
  ADD KEY `cycle_dispensations_shed_id_foreign` (`shed_id`),
  ADD KEY `cycle_dispensations_item_id_foreign` (`item_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `financial_records`
--
ALTER TABLE `financial_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `financial_records_cycle_id_foreign` (`cycle_id`),
  ADD KEY `financial_records_item_id_foreign` (`item_id`),
  ADD KEY `financial_records_treasury_id_foreign` (`treasury_id`),
  ADD KEY `financial_records_client_id_foreign` (`client_id`),
  ADD KEY `financial_records_shed_id_index` (`shed_id`),
  ADD KEY `financial_records_chart_of_account_id_index` (`chart_of_account_id`);

--
-- Indexes for table `inventory_transfers`
--
ALTER TABLE `inventory_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_transfers_item_id_foreign` (`item_id`),
  ADD KEY `inventory_transfers_shed_id_foreign` (`shed_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_entries_entry_number_unique` (`entry_number`);

--
-- Indexes for table `journal_entry_lines`
--
ALTER TABLE `journal_entry_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_lines_journal_entry_id_foreign` (`journal_entry_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine_dispensations`
--
ALTER TABLE `medicine_dispensations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicine_dispensations_medicine_id_foreign` (`medicine_id`),
  ADD KEY `medicine_dispensations_cycle_id_foreign` (`cycle_id`);

--
-- Indexes for table `medicine_entries`
--
ALTER TABLE `medicine_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicine_entries_medicine_id_foreign` (`medicine_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mortality_records`
--
ALTER TABLE `mortality_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mortality_records_cycle_id_foreign` (`cycle_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `purchase_invoices_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_invoices_treasury_id_foreign` (`treasury_id`);

--
-- Indexes for table `purchase_invoice_items`
--
ALTER TABLE `purchase_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `purchase_invoice_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `sale_invoices`
--
ALTER TABLE `sale_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_invoices_client_id_foreign` (`client_id`),
  ADD KEY `sale_invoices_treasury_id_foreign` (`treasury_id`);

--
-- Indexes for table `sale_invoice_items`
--
ALTER TABLE `sale_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `sale_invoice_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sheds`
--
ALTER TABLE `sheds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shed_inventories`
--
ALTER TABLE `shed_inventories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shed_inventories_shed_id_item_id_unique` (`shed_id`,`item_id`),
  ADD KEY `shed_inventories_item_id_foreign` (`item_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_chart_of_account_id_foreign` (`chart_of_account_id`);

--
-- Indexes for table `treasuries`
--
ALTER TABLE `treasuries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `units_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_assigned_shed_id_foreign` (`assigned_shed_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cycles`
--
ALTER TABLE `cycles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cycle_dispensations`
--
ALTER TABLE `cycle_dispensations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_records`
--
ALTER TABLE `financial_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `inventory_transfers`
--
ALTER TABLE `inventory_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `journal_entry_lines`
--
ALTER TABLE `journal_entry_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine_dispensations`
--
ALTER TABLE `medicine_dispensations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine_entries`
--
ALTER TABLE `medicine_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `mortality_records`
--
ALTER TABLE `mortality_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_invoice_items`
--
ALTER TABLE `purchase_invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sale_invoices`
--
ALTER TABLE `sale_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_invoice_items`
--
ALTER TABLE `sale_invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sheds`
--
ALTER TABLE `sheds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shed_inventories`
--
ALTER TABLE `shed_inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `treasuries`
--
ALTER TABLE `treasuries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chart_of_accounts_shed_id_foreign` FOREIGN KEY (`shed_id`) REFERENCES `sheds` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cycles`
--
ALTER TABLE `cycles`
  ADD CONSTRAINT `cycles_shed_id_foreign` FOREIGN KEY (`shed_id`) REFERENCES `sheds` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cycle_dispensations`
--
ALTER TABLE `cycle_dispensations`
  ADD CONSTRAINT `cycle_dispensations_cycle_id_foreign` FOREIGN KEY (`cycle_id`) REFERENCES `cycles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cycle_dispensations_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `cycle_dispensations_shed_id_foreign` FOREIGN KEY (`shed_id`) REFERENCES `sheds` (`id`);

--
-- Constraints for table `financial_records`
--
ALTER TABLE `financial_records`
  ADD CONSTRAINT `financial_records_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `financial_records_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `financial_records_cycle_id_foreign` FOREIGN KEY (`cycle_id`) REFERENCES `cycles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `financial_records_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `financial_records_shed_id_foreign` FOREIGN KEY (`shed_id`) REFERENCES `sheds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `financial_records_treasury_id_foreign` FOREIGN KEY (`treasury_id`) REFERENCES `treasuries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_transfers`
--
ALTER TABLE `inventory_transfers`
  ADD CONSTRAINT `inventory_transfers_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `inventory_transfers_shed_id_foreign` FOREIGN KEY (`shed_id`) REFERENCES `sheds` (`id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `journal_entry_lines`
--
ALTER TABLE `journal_entry_lines`
  ADD CONSTRAINT `journal_entry_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medicine_dispensations`
--
ALTER TABLE `medicine_dispensations`
  ADD CONSTRAINT `medicine_dispensations_cycle_id_foreign` FOREIGN KEY (`cycle_id`) REFERENCES `cycles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medicine_dispensations_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medicine_entries`
--
ALTER TABLE `medicine_entries`
  ADD CONSTRAINT `medicine_entries_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mortality_records`
--
ALTER TABLE `mortality_records`
  ADD CONSTRAINT `mortality_records_cycle_id_foreign` FOREIGN KEY (`cycle_id`) REFERENCES `cycles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
  ADD CONSTRAINT `purchase_invoices_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `purchase_invoices_treasury_id_foreign` FOREIGN KEY (`treasury_id`) REFERENCES `treasuries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchase_invoice_items`
--
ALTER TABLE `purchase_invoice_items`
  ADD CONSTRAINT `purchase_invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `purchase_invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_invoice_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `sale_invoices`
--
ALTER TABLE `sale_invoices`
  ADD CONSTRAINT `sale_invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_invoices_treasury_id_foreign` FOREIGN KEY (`treasury_id`) REFERENCES `treasuries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sale_invoice_items`
--
ALTER TABLE `sale_invoice_items`
  ADD CONSTRAINT `sale_invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `sale_invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_invoice_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shed_inventories`
--
ALTER TABLE `shed_inventories`
  ADD CONSTRAINT `shed_inventories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shed_inventories_shed_id_foreign` FOREIGN KEY (`shed_id`) REFERENCES `sheds` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_assigned_shed_id_foreign` FOREIGN KEY (`assigned_shed_id`) REFERENCES `sheds` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
