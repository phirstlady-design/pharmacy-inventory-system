-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2026 at 09:27 PM
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
-- Database: `pharmacy_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity` text DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `backups`
--

CREATE TABLE `backups` (
  `id` int(11) NOT NULL,
  `backup_file` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(150) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `created_at`) VALUES
(1, 'dasdf', '2026-05-21 15:05:33');

-- --------------------------------------------------------

--
-- Table structure for table `cashier_shifts`
--

CREATE TABLE `cashier_shifts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `opening_balance` decimal(10,2) DEFAULT 0.00,
  `closing_balance` decimal(10,2) DEFAULT 0.00,
  `total_sales` decimal(10,2) DEFAULT 0.00,
  `opened_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `closed_at` datetime DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`) VALUES
(1, 'final', '2026-05-21 15:04:11');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_name` varchar(150) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ict_staff`
--

CREATE TABLE `ict_staff` (
  `id` int(11) NOT NULL,
  `staff_name` varchar(150) NOT NULL,
  `email` varchar(500) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `conhess` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `sign_date` date DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ict_staff`
--

INSERT INTO `ict_staff` (`id`, `staff_name`, `email`, `designation`, `conhess`, `location`, `sign_date`, `birthday`, `phone_no`, `created_at`) VALUES
(1, 'Ajayi, Oluwadare Joseph', 'oluwadare.ajayi@oauthc.gov.ng, deejoeng@yahoo.com', 'Deputy Director ICT', '14/9', 'IHU', NULL, '2026-05-21', '08033567502', '2026-05-21 15:26:00'),
(2, 'Shote-Osunleke, Eniola A.', 'eniolashote2@gmail.com, eniola.shote-osunleke@oauthc.gov.ng', 'Deputy Director ICT', '14/8', 'IHU', NULL, '2026-05-22', '08037127621', '2026-05-21 15:26:00'),
(3, 'Adegbile, Victor Tunde', 'tundestinyaugust@gmail.com, tunde.adegbile@oauthc.gov.ng', 'Senior Data Processing Officer', '8/3', 'IHU', NULL, '2026-05-23', '08135001676', '2026-05-21 15:26:00'),
(4, 'Adeniran, Modinat Yetunde', 'adeniranmodinat85@gmail.com, modinat.adeniran@oauthc.gov.ng', 'Senior Data Processing Officer', '8/3', 'IHU', NULL, '2026-05-24', '08079778021', '2026-05-21 15:26:00'),
(5, 'Awe, Kehinde Oluwatomi', 'adeboye.kehindetomi@gmail.com, awe.kehinde@oauthc.gov.ng', 'Senior Computer Analyst', '8/3', 'IHU', NULL, '2026-05-25', '07060533511', '2026-05-21 15:26:00'),
(6, 'Babalola, Samuel Aderemi', '', 'Senior Engineer', '9/5', 'IHU', NULL, '2026-05-26', NULL, '2026-05-21 15:26:00'),
(7, 'Awe, Toluwalase Emmanuel', 'awetoluwalase@gmail.com, toluwalase.awe@oauthc.gov.ng', 'Senior Data Processing Officer', '8/3', 'IHU', NULL, '2026-05-27', '07067897331', '2026-05-21 15:26:00'),
(8, 'Ibrahim, Umar Zakari', 'umarizakari@gmail.com, umar.ibrahim@oauthc.gov.ng', 'Senior Computer Analyst', '8/3', 'IHU', NULL, '2026-05-28', '08033665451', '2026-05-21 15:26:00'),
(9, 'Adediran, Adedayo David', 'adediranadedayo@gmail.com, adedayo.adediran@oauthc.gov.ng', 'Senior Data Processing Officer', '8/3', 'IHU', NULL, '2026-05-29', '08109064046', '2026-05-21 15:26:00'),
(10, 'Anyabolu, Ogochukwu Glory', 'ganyabolu@gmail.com, ogochukwu.anyabolu@oauthc.gov.ng', 'Senior Computer Analyst', '8/3', 'IHU', NULL, '0000-05-30', '08064768111', '2026-05-21 15:30:57'),
(11, 'Sambo, Abdulsamad', 'samboabdussamadusman1994@gmail.com, abdulsamad.sambo@oauthc.gov.ng', 'Senior Computer Analyst', '8/3', 'IHU', NULL, '0000-05-31', '08101869755', '2026-05-21 15:30:57'),
(12, 'Essong, Akpama Essong', 'essongakpama@gmail.com, akpama.essong@oauthc.gov.ng', 'Senior Computer Analyst', '8/3', 'IHU', NULL, '0000-05-21', '08030608641', '2026-05-21 15:30:57'),
(13, 'Adelani, Funmilola Mary', 'funmymary1@gmail.com, mary.adelani@oauthc.gov.ng', 'Senior Data Processing Officer', '8/3', 'IHU', NULL, '0000-05-22', '08067033510', '2026-05-21 15:30:57'),
(14, 'Adediwura, Julianah Aanu', 'adediwurajulianah@gmail.com, julianah.adediwura@oauthc.gov.ng', 'Senior Data Processing Officer', '8/3', 'IHU', NULL, '0000-05-23', '07032851086', '2026-05-21 15:30:57'),
(15, 'Omowumi, Fiponmile Mary', 'ponmileomowumi@gmail.com, fiponmile.omowumi@oauthc.gov.ng', 'Senior Computer Analyst', '8/3', 'IHU', NULL, '0000-05-24', '08065182627', '2026-05-21 15:30:57'),
(16, 'Adeyeye, Evelyn Egbekauwa', 'mailevely@gmail.com, evelyn.adeyeye@oauthc.gov.ng', 'Senior Computer Analyst', '8/3', 'IHU', NULL, '0000-05-25', '07039694422', '2026-05-21 15:30:57'),
(17, 'Adio, Segun James', 'hydrogenman7@gmail.com, segun.adio@oauthc.gov.ng', 'Senior Data Processing Officer', '8/3', 'IHU', NULL, '0000-05-26', '08064241662', '2026-05-21 15:30:57'),
(18, 'Obinna, Rosemary Nwadiuto', 'rosemary.obinna@oauthc.gov.ng, bonav827@gmail.com', 'Senior Data Processing Officer', '8/3', 'IHU', NULL, '0000-05-27', '07035771138', '2026-05-21 15:30:57'),
(19, 'Adeosun Aderonke A.', 'adeosunaderonke2024@gmail.com, aderonke.adeosun@oauthc.gov.ng', 'Senior Data Processing Officer', '8/3', 'IHU', NULL, '0000-05-28', '08062562612', '2026-05-21 15:30:57'),
(20, 'Alao Akintayo Victor', 'akintayoalao11@gmail.com, akintayo.alao@oauthc.gov.ng', 'Data Processing Officer', '7/5', 'IHU', NULL, '0000-05-29', '07060676718', '2026-05-21 15:30:57'),
(21, 'Ilutanmi Ibukunoluwa', 'ibukunilutanmi@gmail.com, ibukunoluwa.ilutanmi@oauthc.gov.ng', 'Computer Analyst', '7/5', 'IHU', NULL, '0000-05-30', '08063026873', '2026-05-21 15:30:57'),
(22, 'Omoyayi Opeyemi Roseline', 'omoyayiopeyemi2020@gmail.com, opeyemi.omoyayi@oauthc.gov.ng', 'Data Processing Officer', '7/3', 'IHU', NULL, '0000-05-31', '08028379943', '2026-05-21 15:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `movement_type` enum('stock_in','stock_out','adjustment','transfer') DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
  `id` int(11) NOT NULL,
  `manufacturer_name` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manufacturers`
--

INSERT INTO `manufacturers` (`id`, `manufacturer_name`, `phone`, `email`, `address`, `created_at`) VALUES
(1, 'Emzor', NULL, NULL, NULL, '2026-05-21 15:01:02'),
(2, 'PZ Cussons Nigeria Plc', NULL, NULL, NULL, '2026-05-21 15:07:10');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('read','unread') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `patient_no` varchar(100) DEFAULT NULL,
  `fullname` varchar(150) NOT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `payment_type` enum('sale','purchase','expense') DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `payment_method` enum('cash','transfer','pos','wallet') DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `received_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_name` varchar(150) DEFAULT NULL,
  `prescription_file` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `refill_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `product_name` varchar(200) NOT NULL,
  `nafdacno` varchar(100) NOT NULL,
  `generic_name` varchar(200) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `dosage_form` varchar(100) DEFAULT NULL,
  `strength` varchar(100) DEFAULT NULL,
  `batch_number` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT 0.00,
  `selling_price` decimal(10,2) DEFAULT 0.00,
  `quantity` int(11) DEFAULT 0,
  `reorder_level` int(11) DEFAULT 10,
  `location` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `barcode`, `product_name`, `nafdacno`, `generic_name`, `category_id`, `brand_id`, `unit_id`, `manufacturer_id`, `dosage_form`, `strength`, `batch_number`, `expiry_date`, `cost_price`, `selling_price`, `quantity`, `reorder_level`, `location`, `image`, `created_at`) VALUES
(2, '011', 'Paracetamol', '', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '2027-12-19', 550.00, 700.00, 6, 10, NULL, NULL, '2026-05-19 18:24:30'),
(3, 'M001', 'Mistmag', '', NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, '2027-04-30', 750.00, 900.00, 25, 10, NULL, NULL, '2026-05-20 12:41:37'),
(4, 'P001', 'Piritin', '', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '2026-06-21', 1200.00, 1300.00, 22, 10, NULL, NULL, '2026-05-21 09:12:33'),
(5, '01', 'Chloroquine', 'CL101', NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, '2027-05-21', 1000.00, 1200.00, 14, 10, NULL, NULL, '2026-05-21 14:23:13'),
(6, '6008879066688', 'ROBB Inhaler', '04-0315', NULL, 1, 1, 2, 2, NULL, NULL, NULL, '2027-01-31', 450.00, 550.00, 74, 10, NULL, NULL, '2026-05-21 15:08:37');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 6, 24, 750.00, 18000.00),
(2, 2, 4, 20, 345.00, 6900.00),
(3, 2, 3, 10, 800.00, 8000.00);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `payment_status` enum('paid','pending','partial') DEFAULT 'pending',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `supplier_id`, `invoice_number`, `total_amount`, `payment_status`, `created_by`, `created_at`) VALUES
(1, 1, 'F10ER2', 18000.00, 'paid', 1, '2026-05-22 09:37:29'),
(2, 4, 'INV-2026-001', 14900.00, 'partial', 1, '2026-05-22 11:10:34');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `permissions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `permissions`, `created_at`) VALUES
(1, 'Admin', NULL, '2026-05-19 17:31:24'),
(2, 'Pharmacist', NULL, '2026-05-19 17:31:24'),
(3, 'Cashier', NULL, '2026-05-19 17:31:24'),
(4, 'Store Keeper', NULL, '2026-05-19 17:31:24'),
(5, 'Accountant', NULL, '2026-05-19 17:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(100) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `cashier_id` int(11) DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `payment_method` enum('cash','transfer','pos','wallet','insurance') DEFAULT NULL,
  `payment_status` enum('paid','pending','partial') DEFAULT 'paid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `change_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_no`, `patient_id`, `cashier_id`, `total_amount`, `discount`, `payment_method`, `payment_status`, `created_at`, `amount_paid`, `change_amount`) VALUES
(1, 'INV-1779217180', NULL, NULL, 1400.00, 0.00, 'cash', 'paid', '2026-05-19 18:59:40', NULL, NULL),
(2, 'INV-1779218385', NULL, NULL, 4200.00, 0.00, 'cash', 'paid', '2026-05-19 19:19:45', NULL, NULL),
(3, 'INV-1779285824', NULL, NULL, 2100.00, 0.00, 'cash', 'paid', '2026-05-20 14:03:44', NULL, NULL),
(4, 'INV-1779285897', NULL, NULL, 3600.00, 0.00, 'cash', 'paid', '2026-05-20 14:04:57', NULL, NULL),
(5, 'INV-20260522-9910', NULL, NULL, 4800.00, 0.00, '', 'paid', '2026-05-22 13:54:31', NULL, NULL),
(6, 'INV-20260522-9531', NULL, NULL, 1400.00, 0.00, '', 'paid', '2026-05-22 14:17:04', 1400.00, 0.00),
(7, 'INV-20260522-7948', NULL, NULL, 700.00, 0.00, '', 'paid', '2026-05-22 14:22:59', 700.00, 0.00),
(8, 'INV-20260522-9408', NULL, NULL, 2400.00, 0.00, '', 'paid', '2026-05-22 14:28:03', 2400.00, 0.00),
(9, 'INV-20260522-9580', NULL, NULL, 900.00, 0.00, 'pos', 'paid', '2026-05-22 14:28:56', 900.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 2, 2, 700.00, 1400.00),
(2, 2, 2, 6, 700.00, 4200.00),
(3, 3, 2, 3, 700.00, 2100.00),
(4, 4, 3, 4, 900.00, 3600.00),
(5, 5, 5, 4, 1200.00, 4800.00),
(6, 6, 2, 2, 700.00, 1400.00),
(7, 7, 2, 1, 700.00, 700.00),
(8, 8, 5, 2, 1200.00, 2400.00),
(9, 9, 3, 1, 900.00, 900.00);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `company_email` varchar(150) DEFAULT NULL,
  `company_phone` varchar(20) DEFAULT NULL,
  `company_address` text DEFAULT NULL,
  `currency` varchar(20) DEFAULT NULL,
  `tax_percentage` decimal(5,2) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `company_name`, `company_email`, `company_phone`, `company_address`, `currency`, `tax_percentage`, `logo`, `created_at`) VALUES
(1, 'My Pharmacy', 'info@mypharmacy.com', '08000000000', NULL, 'NGN', 7.50, NULL, '2026-05-19 17:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `movement_type` enum('IN','OUT','SALE','DAMAGE','EXPIRED','ADJUSTMENT','TRANSFER') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `from_branch` int(11) DEFAULT NULL,
  `to_branch` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `transferred_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(150) NOT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_name`, `contact_person`, `phone`, `email`, `address`, `status`, `created_at`) VALUES
(1, 'Amadi Obinna', 'Bomboy Amadi', '09122345566', 'amadi@gmail.com', '5, Igwe crescent, Abia State', 'active', '2026-05-22 09:25:05'),
(2, 'Obinna Rose', 'Obinna Rose', '09122345562', 'bonav827@gmail.com', 'Eleyele, Ile-Ife', 'active', '2026-05-22 10:32:37'),
(4, 'Awe Kehinde Tomi', 'Awe Ayinla', '09122345590', 'awekenny@gmail.com', 'London', 'active', '2026-05-22 10:36:22');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(25) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`id`, `unit_name`, `created_at`) VALUES
(1, 'Carton', '2026-05-21 09:28:43'),
(2, 'Box', '2026-05-21 09:36:36'),
(3, 'Pieces', '2026-05-21 14:15:03'),
(4, 'Satchet', '2026-05-21 14:15:15'),
(5, 'Pack', '2026-05-21 14:15:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fileno` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `phone`, `fileno`, `password`, `role_id`, `branch_id`, `status`, `last_login`, `created_at`) VALUES
(1, 'Administrator', 'admin', 'admin@gmail.com', NULL, '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NULL, 'active', NULL, '2026-05-19 17:46:59'),
(2, 'Fiponmile Omowumi', 'Phirstlady01', 'fiponmileomowumi1@gmail.com', '08065182627', 'P17683', '$2y$10$B.MiKA/LS5Aj/ASF/b00yeEUTzT4ZrBMD5TqirGhAt2XpOPAufPui', 2, NULL, 'active', NULL, '2026-05-20 09:27:49'),
(3, 'Omowumi Fiponmile Mary', 'Phirstlady1', 'phirstlady1.fm@gmail.com', '+2348152208244', 'P17683', '$2y$10$MOJe4a/kBXmRp.q2ZyMHAOJl2tA5nXuC.58EQEVy/yZj/poSeRpHy', 2, NULL, 'active', NULL, '2026-06-06 18:57:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashier_shifts`
--
ALTER TABLE `cashier_shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ict_staff`
--
ALTER TABLE `ict_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_no` (`patient_no`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `received_by` (`received_by`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `fk_products_unit` (`unit_id`),
  ADD KEY `fk_category` (`category_id`),
  ADD KEY `fk_brand` (`brand_id`),
  ADD KEY `fk_manufacturer` (`manufacturer_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_id` (`purchase_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `cashier_id` (`cashier_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `from_branch` (`from_branch`),
  ADD KEY `to_branch` (`to_branch`),
  ADD KEY `transferred_by` (`transferred_by`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backups`
--
ALTER TABLE `backups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cashier_shifts`
--
ALTER TABLE `cashier_shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ict_staff`
--
ALTER TABLE `ict_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `backups`
--
ALTER TABLE `backups`
  ADD CONSTRAINT `backups_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `inventory_movements_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_manufacturer` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturers` (`id`),
  ADD CONSTRAINT `fk_products_unit` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturers` (`id`);

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchase_orders` (`id`),
  ADD CONSTRAINT `purchase_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `stock_transfers_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stock_transfers_ibfk_2` FOREIGN KEY (`from_branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `stock_transfers_ibfk_3` FOREIGN KEY (`to_branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `stock_transfers_ibfk_4` FOREIGN KEY (`transferred_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
