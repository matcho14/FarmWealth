-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2026 at 05:21 PM
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
(5, 5, '2026-05-30', NULL, 33800, '{\"1\":8450,\"2\":8450,\"3\":8450,\"4\":8450}', 0, NULL, 0.00, 'active', '2026-05-30 22:23:49', '2026-05-30 22:23:49');

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
(5, 5, 5, 3, 3, 50.000, 120.00, 6000.00, '2026-05-31', NULL, '2026-05-31 15:08:35', '2026-05-31 15:08:35');

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
  `type` enum('expense','revenue') NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `floor_number` int(11) DEFAULT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dispensation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `record_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `treasury_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `financial_records`
--

INSERT INTO `financial_records` (`id`, `cycle_id`, `type`, `quantity`, `floor_number`, `item_id`, `dispensation_id`, `weight`, `amount`, `description`, `record_date`, `created_at`, `updated_at`, `treasury_id`) VALUES
(1, 1, 'expense', 10, 1, 1, 3, NULL, 10000.00, 'صرف صنف: تيلمى - دور 1 - ', '2026-05-12', '2026-05-12 12:34:47', '2026-05-12 12:34:47', NULL),
(2, 1, 'expense', NULL, NULL, NULL, NULL, NULL, 2000.00, 'شيل رتش', '2026-05-12', '2026-05-12 12:45:08', '2026-05-12 12:45:08', 2),
(3, 1, 'revenue', NULL, NULL, NULL, NULL, NULL, 2000.00, 'بيع سبلة', '2026-05-12', '2026-05-12 12:46:03', '2026-05-12 12:46:03', 2),
(4, 1, 'revenue', 2000, NULL, NULL, NULL, 4000.00, 50000.00, 'مبيعات كتاكيت (2000)', '2026-05-12', '2026-05-12 12:46:20', '2026-05-12 12:46:20', 1),
(5, 5, 'expense', 30, 1, 3, 4, NULL, 3600.00, 'صرف صنف: علف بادي - دور 1 - ', '2026-05-31', '2026-05-31 14:59:09', '2026-05-31 14:59:09', NULL),
(6, 5, 'expense', 50, 3, 3, 5, NULL, 6000.00, 'صرف صنف: علف بادي - دور 3 - ', '2026-05-31', '2026-05-31 15:08:35', '2026-05-31 15:08:35', NULL);

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
(3, 3, 5, 200.000, 120.00, 24000.00, '2026-05-31', NULL, '2026-05-31 14:58:38', '2026-05-31 14:58:38');

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
(3, 'علف تجربة', 'feed', 'شيكارة', 1800.000, 120.00, NULL, NULL, '2026-05-30 22:44:45', '2026-05-31 21:14:28'),
(4, 'علف بادى مميز', 'feed', 'شيكارة', 0.000, 0.00, NULL, NULL, '2026-05-31 15:06:45', '2026-05-31 15:09:55'),
(5, 'علف بادى عادى', 'feed', 'شيكارة', 0.000, 0.00, NULL, NULL, '2026-05-31 15:06:45', '2026-05-31 15:09:55'),
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
(10, 'JE-2026-0010', '2026-05-31', 'قيد استهلاك مخزون - دورة #5 - علف بادي', 'financial_record', 6, NULL, '2026-05-31 15:08:35', '2026-05-31 15:08:35');

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
(17, 10, 'item', 3, 0.00, 6000.00, 'صرف من مخزن العنبر', '2026-05-31 15:08:35', '2026-05-31 15:08:35');

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
(27, '2026_05_31_180338_add_category_to_items_table', 4);

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
(11, 5, 4, 72, '2026-05-30', NULL, '2026-05-30 22:38:12', '2026-05-30 22:38:12');

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
(3, 'PUR-2026-0003', 2, NULL, '2026-05-31', 240000.00, 0.00, 'unpaid', NULL, '2026-05-31 14:53:48', '2026-05-31 14:53:48');

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
(3, 3, 3, 2000.000, 120.00, 240000.00, '2026-05-31 14:53:48', '2026-05-31 14:53:48');

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
('CwGVjuSOhAfkc2oLHhsgYBGU21jxtXdShORscCmT', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoib0RiTE5Sa1RweFl1OXEwZk9Sd3JWUmNURXFBMEluRFNmcjlMUU90WiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvY3ljbGVzLzEiO3M6NToicm91dGUiO3M6MTE6ImN5Y2xlcy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1780759795),
('W9doTy175wglQ60JrevtSRuMVIU1nHSNM6pCqq0B', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRzZCY3FIOEFkQ2tpb3J5WTZkRlFaU3VuOXNRamtoU0F5TG9STkFINSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2Vycy81L2VkaXQiO3M6NToicm91dGUiO3M6MTA6InVzZXJzLmVkaXQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1780759968);

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
(5, 'دندنة', 'عنبر دندنة دورة ٢٣-٥-٢٠٢٦', 4, 'active', '2026-05-30 22:22:57', '2026-05-30 22:22:57');

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
(2, 5, 3, 121.000, 120.00, '2026-05-31 12:17:14', '2026-05-31 15:08:35');

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
(2, 'مورد علف', '01000000000', NULL, NULL, 0.00, NULL, '2026-05-23 16:16:39', '2026-05-23 16:16:39');

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `permissions`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'المدير العام', 'admin@admin.com', NULL, '$2y$12$8LeKDfRfGLhOit27/LWafOILdSswcBjKXbu8qWC9WRaoVjW7y9CJO', 'admin', NULL, NULL, '2026-05-11 14:15:47', '2026-05-11 14:15:47'),
(2, 'موظف تجريبي', 'user@user.com', NULL, '$2y$12$vYThSjxSp2tUs5FRNDy6y.zxfyca0Av9qkh9eHRYwiZs1lcWw7Sfy', 'user', '[\"sheds\",\"cycles\"]', NULL, '2026-05-11 14:15:47', '2026-05-11 14:15:47'),
(3, 'المدير العام', 'matcho1419@gmail.com', NULL, '$2y$12$3j9zhKwHZWFeMRwp.VlPI.AfO39GTmGtXIWBJNRxCtyoDaJz.BPa.', 'admin', NULL, 'xzrFlN7ARjWq57hgxtFgNv7Ntz6RV7OcnywJhOmAV1wBAhpHppchtBsAHXbY', '2026-05-11 14:17:26', '2026-05-11 14:17:26'),
(4, 'دكتور عبد الحميد', 'abdohamza89@gmail.com', NULL, '$2y$12$8NA4seUqVpg6MHafgs8IT.1Kzx4eWX.6YoSyMUc25Tssz/o9BGjyS', 'admin', NULL, 'iVBV12mA1iESydxph5A33KhKM5wxjWWiRyiqh1vOuCa4c5GtuIDPsoRjSWN5', '2026-05-18 18:17:40', '2026-05-18 18:17:40'),
(5, 'ahmed.mohmed', 'ahmed.mohmed1699@gmail.com', NULL, '$2y$12$wBTUUOb82VsJJ.t/ggVJK.AtgE7YTnebwWbnj9foyP3R8.NsgoeCe', 'user', '[\"sheds\",\"suppliers\",\"items\",\"inventory\",\"purchase-invoices\",\"journal-entries\",\"annual-report\"]', NULL, '2026-05-23 14:49:05', '2026-05-23 14:49:05'),
(6, 'Mostafa Abdelghfour', 'damostafa84@gmail.com', NULL, '$2y$12$GEsPH6.LuRMsPqnVVGwIKe9C/4BIfW/4SlYHgiKcNOSe5iInt7ry6', 'user', '[\"sheds\",\"cycles\",\"inventory\",\"journal-entries\"]', 'VO56rfU30E6nbJMp3VHZL46rybmjlthCoSKj6fQObCj7tHTCVE4PyhrZpgls', '2026-05-30 22:28:48', '2026-05-30 22:36:10'),
(7, 'Mohamed Abdelhady', 'vet.mohamed600@gmail.com', NULL, '$2y$12$vL04epYuGuCO2gyGnocTX.kMuRlt5nGmhraPi9OnZ/Xct5.DltXWG', 'user', '[\"sheds\",\"cycles\",\"inventory\",\"journal-entries\"]', 'NWFL2cw4WLDAVU6xvcVQ5S9iemwfFk4lMFgYrjf3vqgQJfbhtibaGJp9BoSf', '2026-05-30 22:32:24', '2026-05-30 22:38:02');

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
  ADD KEY `financial_records_treasury_id_foreign` (`treasury_id`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cycles`
--
ALTER TABLE `cycles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cycle_dispensations`
--
ALTER TABLE `cycle_dispensations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_records`
--
ALTER TABLE `financial_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory_transfers`
--
ALTER TABLE `inventory_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `journal_entry_lines`
--
ALTER TABLE `journal_entry_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `mortality_records`
--
ALTER TABLE `mortality_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_invoice_items`
--
ALTER TABLE `purchase_invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sheds`
--
ALTER TABLE `sheds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shed_inventories`
--
ALTER TABLE `shed_inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

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
  ADD CONSTRAINT `financial_records_cycle_id_foreign` FOREIGN KEY (`cycle_id`) REFERENCES `cycles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `financial_records_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
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
-- Constraints for table `shed_inventories`
--
ALTER TABLE `shed_inventories`
  ADD CONSTRAINT `shed_inventories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shed_inventories_shed_id_foreign` FOREIGN KEY (`shed_id`) REFERENCES `sheds` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
