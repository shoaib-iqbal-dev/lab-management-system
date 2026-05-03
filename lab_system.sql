-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2026 at 02:12 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `albumin`
--

CREATE TABLE `albumin` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `albumin` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `albumin`
--

INSERT INTO `albumin` (`id`, `receipt_id`, `albumin`, `reporting_datetime`) VALUES
(1, 268, '567', '2025-06-18 20:27:14');

-- --------------------------------------------------------

--
-- Table structure for table `alt`
--

CREATE TABLE `alt` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_alt` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `alt`
--

INSERT INTO `alt` (`id`, `receipt_id`, `serum_alt`, `reporting_datetime`) VALUES
(1, 263, '53', '2025-06-16 22:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `amylase`
--

CREATE TABLE `amylase` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `amylase` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `amylase`
--

INSERT INTO `amylase` (`id`, `receipt_id`, `amylase`, `reporting_datetime`) VALUES
(1, 268, '98', '2025-06-18 22:47:21');

-- --------------------------------------------------------

--
-- Table structure for table `aptt`
--

CREATE TABLE `aptt` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `aptt` float DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL,
  `pt` varchar(100) DEFAULT NULL,
  `pt_control` varchar(100) DEFAULT NULL,
  `aptt_control` varchar(100) DEFAULT NULL,
  `inr` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `asot`
--

CREATE TABLE `asot` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `asot` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `asot`
--

INSERT INTO `asot` (`id`, `receipt_id`, `asot`, `reporting_datetime`) VALUES
(1, 270, '65', '2025-06-21 23:15:50');

-- --------------------------------------------------------

--
-- Table structure for table `available_tests`
--

CREATE TABLE `available_tests` (
  `id` int(11) NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `price` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `blood_grouping`
--

CREATE TABLE `blood_grouping` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `abo_group` varchar(10) DEFAULT NULL,
  `rh_d` varchar(10) DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blood_grouping`
--

INSERT INTO `blood_grouping` (`id`, `receipt_id`, `abo_group`, `rh_d`, `reporting_datetime`) VALUES
(7, 245, 'O', 'Negative', '2025-06-13 00:50:49');

-- --------------------------------------------------------

--
-- Table structure for table `bsf`
--

CREATE TABLE `bsf` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `bsf` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL,
  `bsr` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bsf`
--

INSERT INTO `bsf` (`id`, `receipt_id`, `bsf`, `reporting_datetime`, `bsr`) VALUES
(7, 260, '0', '2025-06-13 08:48:57', '0');

-- --------------------------------------------------------

--
-- Table structure for table `bsr`
--

CREATE TABLE `bsr` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `bsr` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL,
  `bsf` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bsr`
--

INSERT INTO `bsr` (`id`, `receipt_id`, `bsr`, `reporting_datetime`, `bsf`) VALUES
(8, 260, '0', '2025-06-13 08:48:50', '0');

-- --------------------------------------------------------

--
-- Table structure for table `btct`
--

CREATE TABLE `btct` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `bt` varchar(100) DEFAULT NULL,
  `ct` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `btct`
--

INSERT INTO `btct` (`id`, `receipt_id`, `bt`, `ct`, `reporting_datetime`) VALUES
(1, 268, '18', '19', '2025-06-18 20:37:27');

-- --------------------------------------------------------

--
-- Table structure for table `calcium`
--

CREATE TABLE `calcium` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `calcium` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `calcium`
--

INSERT INTO `calcium` (`id`, `receipt_id`, `calcium`, `reporting_datetime`) VALUES
(1, 268, '8', '2025-06-22 18:47:44');

-- --------------------------------------------------------

--
-- Table structure for table `cbc_profile`
--

CREATE TABLE `cbc_profile` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `haemoglobin` varchar(100) DEFAULT NULL,
  `rbc` varchar(100) DEFAULT NULL,
  `haematocrit` varchar(100) DEFAULT NULL,
  `mcv` varchar(100) DEFAULT NULL,
  `mch` varchar(100) DEFAULT NULL,
  `mchc` varchar(100) DEFAULT NULL,
  `white_cells` varchar(100) DEFAULT NULL,
  `neutrophils` varchar(100) DEFAULT NULL,
  `lymphocytes` varchar(100) DEFAULT NULL,
  `monocytes` varchar(100) DEFAULT NULL,
  `eosinophils` varchar(100) DEFAULT NULL,
  `basophils` varchar(100) DEFAULT NULL,
  `platelet_count` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cbc_profile`
--

INSERT INTO `cbc_profile` (`id`, `receipt_id`, `haemoglobin`, `rbc`, `haematocrit`, `mcv`, `mch`, `mchc`, `white_cells`, `neutrophils`, `lymphocytes`, `monocytes`, `eosinophils`, `basophils`, `platelet_count`, `reporting_datetime`) VALUES
(33, 251, '13.3', '4.17', '38', '92', '31', '34', '15.5', '75', '19', '4', '2', '0', '249', '2025-06-12 23:53:40'),
(34, 273, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '2025-06-28 10:35:13'),
(35, 277, '9', '9', '9', '0', '9', '8', '0', '9', '0', '0', '0', '9', '9', '2025-07-22 19:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `cholesterol`
--

CREATE TABLE `cholesterol` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_total_cholesterol` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cholesterol`
--

INSERT INTO `cholesterol` (`id`, `receipt_id`, `serum_total_cholesterol`, `reporting_datetime`) VALUES
(1, 267, '190', '2025-06-22 17:06:18');

-- --------------------------------------------------------

--
-- Table structure for table `creatinine`
--

CREATE TABLE `creatinine` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_creatinine` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `creatinine`
--

INSERT INTO `creatinine` (`id`, `receipt_id`, `serum_creatinine`, `reporting_datetime`) VALUES
(1, 264, '1.25', '2025-06-22 14:22:37');

-- --------------------------------------------------------

--
-- Table structure for table `crp`
--

CREATE TABLE `crp` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `crp` varchar(255) DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dengue_ns1`
--

CREATE TABLE `dengue_ns1` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `dengue_ns1` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dengue_ns1`
--

INSERT INTO `dengue_ns1` (`id`, `receipt_id`, `dengue_ns1`, `reporting_datetime`) VALUES
(1, 254, 'Postive', '2025-06-22 23:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `dengue_serology`
--

CREATE TABLE `dengue_serology` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `igg` varchar(100) DEFAULT NULL,
  `igm` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dengue_serology`
--

INSERT INTO `dengue_serology` (`id`, `receipt_id`, `igg`, `igm`, `reporting_datetime`) VALUES
(1, 268, 'Negative', 'Positive', '2025-06-22 23:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `commission_percentage` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_commissions`
--

CREATE TABLE `doctor_commissions` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `receipt_id` int(11) DEFAULT NULL,
  `commission_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `earnings`
--

CREATE TABLE `earnings` (
  `id` int(11) NOT NULL,
  `mr_no` varchar(50) DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `discount_percent` float DEFAULT NULL,
  `final_amount` float DEFAULT NULL,
  `referred_by` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `electrolytes`
--

CREATE TABLE `electrolytes` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_sodium` float DEFAULT NULL,
  `serum_potassium` float DEFAULT NULL,
  `serum_chloride` float DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL,
  `serum_bicarbonate` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `electrolytes`
--

INSERT INTO `electrolytes` (`id`, `receipt_id`, `serum_sodium`, `serum_potassium`, `serum_chloride`, `reporting_datetime`, `serum_bicarbonate`) VALUES
(8, 239, 0, 0, 0, '2025-06-04 14:49:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `esr`
--

CREATE TABLE `esr` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `esr` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `esr`
--

INSERT INTO `esr` (`id`, `receipt_id`, `esr`, `reporting_datetime`) VALUES
(1, 268, '54', '2025-06-18 20:55:21');

-- --------------------------------------------------------

--
-- Table structure for table `globulin`
--

CREATE TABLE `globulin` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `globulin` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hba1c`
--

CREATE TABLE `hba1c` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `hba1c` float DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hdl`
--

CREATE TABLE `hdl` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_hdl_cholesterol` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hdl`
--

INSERT INTO `hdl` (`id`, `receipt_id`, `serum_hdl_cholesterol`, `reporting_datetime`) VALUES
(1, 265, '35', '2025-06-22 15:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `hep_b`
--

CREATE TABLE `hep_b` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `hep_b` varchar(100) NOT NULL,
  `reporting_datetime` datetime NOT NULL,
  `hep_c` varchar(100) DEFAULT NULL,
  `hiv` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hep_c`
--

CREATE TABLE `hep_c` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `hep_c` varchar(255) DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL,
  `hep_b` varchar(100) DEFAULT NULL,
  `hiv` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hep_c`
--

INSERT INTO `hep_c` (`id`, `receipt_id`, `hep_c`, `reporting_datetime`, `hep_b`, `hiv`) VALUES
(4, 247, 'NON REACTIVE', '2025-06-12 23:56:58', '-', '-'),
(5, 244, 'k', '2025-06-28 10:51:50', 'k', '');

-- --------------------------------------------------------

--
-- Table structure for table `hiv`
--

CREATE TABLE `hiv` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `hiv` varchar(100) NOT NULL,
  `reporting_datetime` datetime NOT NULL,
  `hep_b` varchar(100) DEFAULT NULL,
  `hep_c` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `h_pylori`
--

CREATE TABLE `h_pylori` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `h_pylori` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `h_pylori`
--

INSERT INTO `h_pylori` (`id`, `receipt_id`, `h_pylori`, `reporting_datetime`) VALUES
(1, 268, 'Positive', '2025-06-25 10:26:16');

-- --------------------------------------------------------

--
-- Table structure for table `lab_expenses`
--

CREATE TABLE `lab_expenses` (
  `id` int(11) NOT NULL,
  `expense_name` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lab_expenses`
--

INSERT INTO `lab_expenses` (`id`, `expense_name`, `amount`, `expense_date`, `created_by`) VALUES
(41, 'salary', '2000.00', '2025-06-04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ldl`
--

CREATE TABLE `ldl` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_ldl_cholesterol` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ldl`
--

INSERT INTO `ldl` (`id`, `receipt_id`, `serum_ldl_cholesterol`, `reporting_datetime`) VALUES
(1, 265, '170', '2025-06-22 15:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `lft`
--

CREATE TABLE `lft` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_total_bilirubin` varchar(100) DEFAULT NULL,
  `serum_conjugated_bilirubin` varchar(100) DEFAULT NULL,
  `serum_unconjugated_bilirubin` varchar(100) DEFAULT NULL,
  `serum_alt` varchar(100) DEFAULT NULL,
  `serum_ast` varchar(100) DEFAULT NULL,
  `serum_alkaline_phosphate` varchar(100) DEFAULT NULL,
  `serum_gamma_gt` varchar(100) DEFAULT NULL,
  `serum_total_protein` varchar(100) DEFAULT NULL,
  `serum_albumin` varchar(100) DEFAULT NULL,
  `serum_globulins` varchar(100) DEFAULT NULL,
  `ag_ratio` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lft`
--

INSERT INTO `lft` (`id`, `receipt_id`, `serum_total_bilirubin`, `serum_conjugated_bilirubin`, `serum_unconjugated_bilirubin`, `serum_alt`, `serum_ast`, `serum_alkaline_phosphate`, `serum_gamma_gt`, `serum_total_protein`, `serum_albumin`, `serum_globulins`, `ag_ratio`, `reporting_datetime`) VALUES
(7, 271, '9', '9', '9', '9', '9', '9', NULL, NULL, NULL, NULL, NULL, '2025-06-22 13:30:25');

-- --------------------------------------------------------

--
-- Table structure for table `lipase`
--

CREATE TABLE `lipase` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `lipase` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lipid_profile`
--

CREATE TABLE `lipid_profile` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_total_cholesterol` varchar(100) DEFAULT NULL,
  `serum_triglycerides` varchar(100) DEFAULT NULL,
  `serum_hdl_cholesterol` varchar(100) DEFAULT NULL,
  `serum_ldl_cholesterol` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lipid_profile`
--

INSERT INTO `lipid_profile` (`id`, `receipt_id`, `serum_total_cholesterol`, `serum_triglycerides`, `serum_hdl_cholesterol`, `serum_ldl_cholesterol`, `reporting_datetime`) VALUES
(5, 258, '-', '-', '-', '-', '2025-06-13 01:07:27'),
(6, 262, '250', '501', '34', '180', '2025-06-22 16:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `malaria_parasite`
--

CREATE TABLE `malaria_parasite` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `malaria_parasite_pv` varchar(100) DEFAULT NULL,
  `malaria_parasite_pf` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `malaria_parasite`
--

INSERT INTO `malaria_parasite` (`id`, `receipt_id`, `malaria_parasite_pv`, `malaria_parasite_pf`, `reporting_datetime`) VALUES
(1, 270, 'PV', 'PF', '2025-06-18 23:00:02'),
(2, 275, 'Negative', 'Negative', '2025-06-28 10:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `mr_no` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `age` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `referred_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `mr_no`, `name`, `age`, `gender`, `address`, `phone`, `referred_by`, `created_at`) VALUES
(198, '2506001', 'ALI  AHMED', '25 Months', 'Male', '', '03000000000', 'DR BILAL', '2025-06-04 04:11:59'),
(199, '2506002', 'ayesha  -', '34 Years', 'Female', '', '03000000000', 'dr laiba', '2025-06-04 09:56:25'),
(200, '2506003', 'ahtesham  shahid', '90 Years', 'Male', '', '03000000000', 'dr haris', '2025-06-04 09:57:21'),
(201, '2506004', 'muneeb  khameed', '12 Years', 'Male', '', '03000000000', 'dr anjum bilal', '2025-06-04 10:40:34'),
(202, '2506005', 'laiba  hameed', '23 Months', 'Female', '', '03000000000', 'dr afarooq', '2025-06-04 10:46:58'),
(203, '2506006', 'ahsan  hameed', '24 Days', 'Male', '', '03000000000', 'Dr Arslan', '2025-06-05 05:47:09'),
(204, '2506007', 'TEST  -', '22 Days', 'Female', '-', '03000000000', 'dr test', '2025-06-28 06:10:10'),
(205, '2507001', 'shoaib  iqbal', '10 Years', 'Male', '..', '03000000000', 'Dr ahmed', '2025-07-22 14:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `mr_no` varchar(50) NOT NULL,
  `total_fee` decimal(10,2) NOT NULL,
  `receipt_no` varchar(50) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `phosphate`
--

CREATE TABLE `phosphate` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `phosphate` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `phosphate`
--

INSERT INTO `phosphate` (`id`, `receipt_id`, `phosphate`, `reporting_datetime`) VALUES
(1, 272, '4.9', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pt`
--

CREATE TABLE `pt` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `pt` varchar(100) DEFAULT NULL,
  `inr` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL,
  `aptt` varchar(100) DEFAULT NULL,
  `pt_control` varchar(100) DEFAULT NULL,
  `aptt_control` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ra_factor`
--

CREATE TABLE `ra_factor` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `ra_factor` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ra_factor`
--

INSERT INTO `ra_factor` (`id`, `receipt_id`, `ra_factor`, `reporting_datetime`) VALUES
(1, 268, '6', '2025-06-22 17:16:58');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `receipt_id` int(11) NOT NULL,
  `receipt_no` varchar(20) NOT NULL,
  `mr_no` varchar(20) NOT NULL,
  `actual_fee` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `patient_name` varchar(100) NOT NULL,
  `total_fee` float NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `gender` varchar(10) DEFAULT NULL,
  `age` varchar(20) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `billed_by` varchar(255) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `referred_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`receipt_id`, `receipt_no`, `mr_no`, `actual_fee`, `discount`, `patient_name`, `total_fee`, `payment_date`, `gender`, `age`, `contact_no`, `billed_by`, `doctor_id`, `referred_by`) VALUES
(239, '25640001', '2506001', '1500.00', '0.00', 'ALI  AHMED', 1500, '2025-06-04 14:48:30', 'Male', '25 Months', '03000000000', '7', 0, NULL),
(240, '25640002', '2506002', '500.00', '0.00', 'ayesha  -', 0, '2025-06-04 14:56:32', 'Female', '34 Years', '03000000000', '7', 0, NULL),
(241, '25640003', '2506003', '200.00', '0.00', 'ahtesham  shahid', 200, '2025-06-04 14:57:28', 'Male', '90 Years', '03000000000', '7', 0, NULL),
(242, '25640004', '2506001', '500.00', '0.00', 'ALI  AHMED', 500, '2025-06-04 15:22:16', 'Male', '25 Months', '03000000000', '7', 0, NULL),
(243, '25640005', '2506001', '500.00', '0.00', 'ALI  AHMED', 500, '2025-06-04 15:28:21', 'Male', '25 Months', '03000000000', '7', 0, ''),
(244, '25640006', '2506001', '300.00', '0.00', 'ALI  AHMED', 300, '2025-06-04 15:34:31', 'Male', '25 Months', '03000000000', '7', 0, ''),
(245, '25640007', '2506001', '200.00', '0.00', 'ALI  AHMED', 200, '2025-06-04 15:35:55', 'Male', '25 Months', '03000000000', '7', 0, 'DR BILAL'),
(246, '25640008', '2506004', '700.00', '0.00', 'muneeb  khameed', 700, '2025-06-04 15:40:43', 'Male', '12 Years', '03000000000', '7', 0, 'dr anjum bilal'),
(247, '25640009', '2506005', '300.00', '0.00', 'laiba  hameed', 300, '2025-06-04 15:47:03', 'Female', '23 Months', '03000000000', '12', 0, 'dr afarooq'),
(248, '25640010', '2506002', '2400.00', '0.00', 'ayesha  -', 2400, '2025-06-04 17:58:56', 'Female', '34 Years', '03000000000', '12', 0, 'dr laiba'),
(249, '25640011', '2506002', '1000.00', '200.00', 'ayesha  -', 800, '2025-06-04 17:59:34', 'Female', '34 Years', '03000000000', '12', 0, 'dr laiba'),
(250, '25640012', '2506005', '500.00', '0.00', 'laiba  hameed', 500, '2025-06-04 18:05:20', 'Female', '23 Months', '03000000000', '12', 0, 'dr afarooq'),
(251, '25640013', '2506005', '500.00', '0.00', 'laiba  hameed', 500, '2025-06-04 18:10:18', 'Female', '23 Months', '03000000000', '7', 0, 'dr afarooq'),
(252, '25640014', '2506005', '500.00', '100.00', 'laiba  hameed', 400, '2025-06-04 18:16:57', 'Female', '23 Months', '03000000000', '7', 0, 'dr afarooq'),
(253, '25640015', '2506001', '200.00', '0.00', 'ALI  AHMED', 200, '2025-06-04 19:07:14', 'Male', '25 Months', '03000000000', '12', 0, 'DR BILAL'),
(254, '25650001', '2506006', '500.00', '150.00', 'ahsan  hameed', 350, '2025-06-05 10:47:21', 'Male', '24 Days', '03000000000', '12', 0, 'Dr Arslan'),
(255, '256120001', '2506005', '6200.00', '0.00', 'laiba  hameed', 6200, '2025-06-12 23:44:23', 'Female', '23 Months', '03000000000', '7', 0, 'dr afarooq'),
(256, '256120002', '2506005', '900.00', '150.00', 'laiba  hameed', 750, '2025-06-13 00:03:25', 'Female', '23 Months', '03000000000', '7', 0, 'dr afarooq'),
(257, '256120003', '2506005', '900.00', '150.00', 'laiba  hameed', 750, '2025-06-13 00:06:16', 'Female', '23 Months', '03000000000', '7', 0, 'dr afarooq'),
(258, '256120004', '2506002', '500.00', '0.00', 'ayesha  -', 500, '2025-06-13 00:51:59', 'Female', '34 Years', '03000000000', '7', 0, 'dr laiba'),
(259, '256120005', '2506002', '500.00', '0.00', 'ayesha  -', 500, '2025-06-13 00:52:51', 'Female', '34 Years', '03000000000', '7', 0, 'dr laiba'),
(260, '256120006', '2506006', '400.00', '0.00', 'ahsan  hameed', 400, '2025-06-13 01:04:31', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(261, '256130001', '2506005', '600.00', '0.00', 'laiba  hameed', 600, '2025-06-13 10:45:34', 'Female', '23 Months', '03000000000', '7', 0, 'dr afarooq'),
(262, '256130002', '2506006', '500.00', '0.00', 'ahsan  hameed', 500, '2025-06-13 18:13:46', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(263, '256140001', '2506006', '500.00', '0.00', 'ahsan  hameed', 500, '2025-06-14 11:57:39', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(264, '256160001', '2506006', '1000.00', '0.00', 'ahsan  hameed', 1000, '2025-06-16 20:55:27', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(265, '256160002', '2506006', '1500.00', '0.00', 'ahsan  hameed', 1500, '2025-06-16 21:48:41', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(266, '256160003', '2506006', '500.00', '0.00', 'ahsan  hameed', 500, '2025-06-16 22:03:50', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(267, '256160004', '2506006', '500.00', '0.00', 'ahsan  hameed', 500, '2025-06-16 22:46:37', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(268, '256180001', '2506006', '6200.00', '0.00', 'ahsan  hameed', 6200, '2025-06-18 20:19:45', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(269, '256180002', '2506006', '500.00', '0.00', 'ahsan  hameed', 500, '2025-06-18 22:33:55', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(270, '256180003', '2506006', '800.00', '0.00', 'ahsan  hameed', 800, '2025-06-18 22:53:38', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(271, '256220001', '2506006', '1100.00', '0.00', 'ahsan  hameed', 1100, '2025-06-22 13:30:07', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(272, '256220002', '2506006', '300.00', '0.00', 'ahsan  hameed', 300, '2025-06-22 18:49:11', 'Male', '24 Days', '03000000000', '7', 0, 'Dr Arslan'),
(273, '256280001', '2506001', '500.00', '0.00', 'ALI  AHMED', 500, '2025-06-28 10:34:36', 'Male', '25 Months', '03000000000', '7', 0, 'DR BILAL'),
(274, '256280002', '2506001', '500.00', '0.00', 'ALI  AHMED', 500, '2025-06-28 10:34:37', 'Male', '25 Months', '03000000000', '7', 0, 'DR BILAL'),
(275, '256280003', '2506001', '1500.00', '0.00', 'ALI  AHMED', 1500, '2025-06-28 10:42:11', 'Male', '25 Months', '03000000000', '7', 0, 'DR BILAL'),
(276, '256280004', '2506007', '28200.00', '1200.00', 'TEST  -', 27000, '2025-06-28 11:18:22', 'Female', '22 Days', '03000000000', '7', 0, 'dr test'),
(277, '257220001', '2507001', '500.00', '100.00', 'shoaib  iqbal', 400, '2025-07-22 19:20:48', 'Male', '10 Years', '03000000000', '7', 0, 'Dr ahmed'),
(278, '26530001', '2507001', '500.00', '250.00', 'shoaib  iqbal', 0, '2026-05-03 17:07:59', 'Male', '10 Years', '03000000000', '7', 0, 'Dr ahmed'),
(279, '26530002', '2507001', '500.00', '0.00', 'shoaib  iqbal', 500, '2026-05-03 17:09:01', 'Male', '10 Years', '03000000000', '7', 0, 'Dr ahmed');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_tests`
--

CREATE TABLE `receipt_tests` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `test_name` varchar(100) NOT NULL,
  `test_fee` float NOT NULL,
  `billed_fee` decimal(10,2) DEFAULT NULL,
  `test_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `receipt_no` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `receipt_tests`
--

INSERT INTO `receipt_tests` (`id`, `receipt_id`, `test_name`, `test_fee`, `billed_fee`, `test_discount`, `receipt_no`) VALUES
(421, 239, 'DENGUE NS1', 500, '500.00', '0.00', '25640001'),
(422, 239, 'ELECTROLYTES', 1000, '1000.00', '0.00', '25640001'),
(424, 241, 'UPT', 200, '200.00', '0.00', '25640003'),
(425, 242, 'Albumin', 500, '500.00', '0.00', '25640004'),
(426, 243, 'RFT', 500, '500.00', '0.00', '25640005'),
(427, 244, 'HEP C', 300, '300.00', '0.00', '25640006'),
(428, 245, 'BLOOD GROUPING', 200, '200.00', '0.00', '25640007'),
(429, 246, 'BSF', 200, '200.00', '0.00', '25640008'),
(430, 246, 'RFT', 500, '500.00', '0.00', '25640008'),
(431, 247, 'HEP C', 300, '300.00', '0.00', '25640009'),
(432, 248, 'THYROID PROFILE', 2400, '2400.00', '0.00', '25640010'),
(433, 249, 'SERUM FSH', 1000, '800.00', '200.00', '25640011'),
(434, 250, 'DENGUE SEROLOGY', 500, '500.00', '0.00', '25640012'),
(435, 251, 'CBC Profile', 500, '500.00', '0.00', '25640013'),
(436, 252, 'Albumin', 500, '400.00', '100.00', '25640014'),
(437, 253, 'URINE RE', 200, '200.00', '0.00', '25640015'),
(438, 254, 'DENGUE NS1', 500, '350.00', '150.00', '25650001'),
(439, 255, 'RA FACTOR', 400, '400.00', '0.00', '256120001'),
(440, 255, 'Albumin', 500, '500.00', '0.00', '256120001'),
(441, 255, 'CALCIUM', 500, '500.00', '0.00', '256120001'),
(442, 255, 'BTCT', 400, '400.00', '0.00', '256120001'),
(443, 255, 'LIPASE', 500, '500.00', '0.00', '256120001'),
(444, 255, 'ESR', 200, '200.00', '0.00', '256120001'),
(445, 255, 'DENGUE SEROLOGY', 500, '500.00', '0.00', '256120001'),
(446, 255, 'DENGUE NS1', 500, '500.00', '0.00', '256120001'),
(447, 255, 'H PYLORI', 500, '500.00', '0.00', '256120001'),
(448, 255, 'STOOL FOR H PYLORI', 500, '500.00', '0.00', '256120001'),
(449, 255, 'TYPHOID', 500, '500.00', '0.00', '256120001'),
(450, 255, 'SEMEN ANALYSIS', 500, '500.00', '0.00', '256120001'),
(451, 255, 'UPT', 200, '200.00', '0.00', '256120001'),
(452, 255, 'AMYLASE', 500, '500.00', '0.00', '256120001'),
(453, 256, 'HEP B', 300, '250.00', '50.00', '256120002'),
(454, 256, 'HEP C', 300, '250.00', '50.00', '256120002'),
(455, 256, 'HIV', 300, '250.00', '50.00', '256120002'),
(456, 257, 'HEP B', 300, '250.00', '50.00', '256120003'),
(457, 257, 'HEP C', 300, '250.00', '50.00', '256120003'),
(458, 257, 'HIV', 300, '250.00', '50.00', '256120003'),
(459, 258, 'LIPID PROFILE', 500, '500.00', '0.00', '256120004'),
(460, 259, 'LIPID PROFILE', 500, '500.00', '0.00', '256120005'),
(461, 260, 'BSR', 200, '200.00', '0.00', '256120006'),
(462, 260, 'BSF', 200, '200.00', '0.00', '256120006'),
(463, 261, 'HEP B', 300, '300.00', '0.00', '256130001'),
(464, 261, 'HEP C', 300, '300.00', '0.00', '256130001'),
(465, 262, 'LIPID PROFILE', 500, '500.00', '0.00', '256130002'),
(466, 263, 'ALT', 500, '500.00', '0.00', '256140001'),
(467, 264, 'UREA', 500, '500.00', '0.00', '256160001'),
(468, 264, 'CREATININE', 500, '500.00', '0.00', '256160001'),
(469, 265, 'HDL', 500, '500.00', '0.00', '256160002'),
(470, 265, 'LDL', 500, '500.00', '0.00', '256160002'),
(471, 265, 'TRIGLYCERIDES', 500, '500.00', '0.00', '256160002'),
(472, 266, 'TG CHOLESTEROL', 500, '500.00', '0.00', '256160003'),
(473, 267, 'CHOLESTEROL', 500, '500.00', '0.00', '256160004'),
(474, 268, 'RA FACTOR', 400, '400.00', '0.00', '256180001'),
(475, 268, 'Albumin', 500, '500.00', '0.00', '256180001'),
(476, 268, 'CALCIUM', 500, '500.00', '0.00', '256180001'),
(477, 268, 'BTCT', 400, '400.00', '0.00', '256180001'),
(478, 268, 'LIPASE', 500, '500.00', '0.00', '256180001'),
(479, 268, 'ESR', 200, '200.00', '0.00', '256180001'),
(480, 268, 'DENGUE SEROLOGY', 500, '500.00', '0.00', '256180001'),
(481, 268, 'DENGUE NS1', 500, '500.00', '0.00', '256180001'),
(482, 268, 'H PYLORI', 500, '500.00', '0.00', '256180001'),
(483, 268, 'STOOL FOR H PYLORI', 500, '500.00', '0.00', '256180001'),
(484, 268, 'TYPHOID', 500, '500.00', '0.00', '256180001'),
(485, 268, 'SEMEN ANALYSIS', 500, '500.00', '0.00', '256180001'),
(486, 268, 'UPT', 200, '200.00', '0.00', '256180001'),
(487, 268, 'AMYLASE', 500, '500.00', '0.00', '256180001'),
(488, 269, 'STOOL H PYLORI', 500, '500.00', '0.00', '256180002'),
(489, 270, 'ASOT', 300, '300.00', '0.00', '256180003'),
(490, 270, 'MALARIA PARASITE', 500, '500.00', '0.00', '256180003'),
(491, 271, 'LFT', 600, '600.00', '0.00', '256220001'),
(492, 271, 'RFT', 500, '500.00', '0.00', '256220001'),
(493, 272, 'PHOSPHATE', 300, '300.00', '0.00', '256220002'),
(494, 273, 'CBC Profile', 500, '500.00', '0.00', '256280001'),
(495, 274, 'CBC Profile', 500, '500.00', '0.00', '256280002'),
(496, 275, 'DENGUE SEROLOGY', 500, '500.00', '0.00', '256280003'),
(497, 275, 'DENGUE NS1', 500, '500.00', '0.00', '256280003'),
(498, 275, 'MALARIA PARASITE', 500, '500.00', '0.00', '256280003'),
(499, 276, 'CBC Profile', 500, '478.72', '21.28', '256280004'),
(500, 276, 'BLOOD GROUPING', 200, '191.49', '8.51', '256280004'),
(501, 276, 'PT', 250, '239.36', '10.64', '256280004'),
(502, 276, 'APTT', 250, '239.36', '10.64', '256280004'),
(503, 276, 'HEP B', 300, '287.23', '12.77', '256280004'),
(504, 276, 'HEP C', 300, '287.23', '12.77', '256280004'),
(505, 276, 'HIV', 300, '287.23', '12.77', '256280004'),
(506, 276, 'HBA1C', 1500, '1436.17', '63.83', '256280004'),
(507, 276, 'BSR', 200, '191.49', '8.51', '256280004'),
(508, 276, 'BSF', 200, '191.49', '8.51', '256280004'),
(509, 276, 'CRP', 500, '478.72', '21.28', '256280004'),
(510, 276, 'ELECTROLYTES', 1000, '957.45', '42.55', '256280004'),
(511, 276, 'Serum Vitamin B12', 500, '478.72', '21.28', '256280004'),
(512, 276, 'LIPID PROFILE', 500, '478.72', '21.28', '256280004'),
(513, 276, 'RFT', 500, '478.72', '21.28', '256280004'),
(514, 276, 'LFT', 600, '574.47', '25.53', '256280004'),
(515, 276, 'URIC ACID', 300, '287.23', '12.77', '256280004'),
(516, 276, 'Serum Vitamin D', 2500, '2393.62', '106.38', '256280004'),
(517, 276, 'SERUM AMH', 400, '382.98', '17.02', '256280004'),
(518, 276, 'SERUM PROLACTIN', 400, '382.98', '17.02', '256280004'),
(519, 276, 'SERUM FERRITIN', 1600, '1531.91', '68.09', '256280004'),
(520, 276, 'SERUM LH', 1000, '957.45', '42.55', '256280004'),
(521, 276, 'SERUM FSH', 1000, '957.45', '42.55', '256280004'),
(522, 276, 'THYROID PROFILE', 2400, '2297.87', '102.13', '256280004'),
(523, 276, 'URINE RE', 200, '191.49', '8.51', '256280004'),
(524, 276, 'ALT', 500, '478.72', '21.28', '256280004'),
(525, 276, 'UREA', 500, '478.72', '21.28', '256280004'),
(526, 276, 'CREATININE', 500, '478.72', '21.28', '256280004'),
(527, 276, 'HDL', 500, '478.72', '21.28', '256280004'),
(528, 276, 'LDL', 500, '478.72', '21.28', '256280004'),
(529, 276, 'TRIGLYCERIDES', 500, '478.72', '21.28', '256280004'),
(530, 276, 'CHOLESTEROL', 500, '478.72', '21.28', '256280004'),
(531, 276, 'TG CHOLESTEROL', 500, '478.72', '21.28', '256280004'),
(532, 276, 'RA FACTOR', 400, '382.98', '17.02', '256280004'),
(533, 276, 'Albumin', 500, '478.72', '21.28', '256280004'),
(534, 276, 'CALCIUM', 500, '478.72', '21.28', '256280004'),
(535, 276, 'PHOSPHATE', 300, '287.23', '12.77', '256280004'),
(536, 276, 'BTCT', 400, '382.98', '17.02', '256280004'),
(537, 276, 'LIPASE', 500, '478.72', '21.28', '256280004'),
(538, 276, 'ESR', 200, '191.49', '8.51', '256280004'),
(539, 276, 'DENGUE SEROLOGY', 500, '478.72', '21.28', '256280004'),
(540, 276, 'DENGUE NS1', 500, '478.72', '21.28', '256280004'),
(541, 276, 'H PYLORI', 500, '478.72', '21.28', '256280004'),
(542, 276, 'STOOL H PYLORI', 500, '478.72', '21.28', '256280004'),
(543, 276, 'ASOT', 300, '287.23', '12.77', '256280004'),
(544, 276, 'SEMEN ANALYSIS', 500, '478.72', '21.28', '256280004'),
(545, 276, 'UPT', 200, '191.49', '8.51', '256280004'),
(546, 276, 'AMYLASE', 500, '478.72', '21.28', '256280004'),
(547, 276, 'MALARIA PARASITE', 500, '478.72', '21.28', '256280004'),
(548, 277, 'CBC Profile', 500, '400.00', '100.00', '257220001'),
(550, 279, 'CBC Profile', 500, '500.00', '0.00', '26530002');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `receipt_no` varchar(50) DEFAULT NULL,
  `test_name` varchar(255) DEFAULT NULL,
  `billed_fee` decimal(10,2) DEFAULT NULL,
  `original_fee` int(11) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `returned_by` int(11) DEFAULT NULL,
  `return_date` datetime DEFAULT current_timestamp(),
  `reason` text DEFAULT NULL,
  `mr_no` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`id`, `receipt_no`, `test_name`, `billed_fee`, `original_fee`, `discount`, `returned_by`, `return_date`, `reason`, `mr_no`) VALUES
(16, '25640002', 'AMYLASE', '500.00', 500, 0, 7, '2025-06-04 17:37:38', 'no electricity', '2506002'),
(17, '26530001', 'CBC Profile', '250.00', 500, 250, 7, '2026-05-03 17:09:38', 'no electtricity', '2507001');

-- --------------------------------------------------------

--
-- Table structure for table `rft`
--

CREATE TABLE `rft` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_urea` varchar(100) NOT NULL,
  `serum_bun` varchar(100) NOT NULL,
  `serum_creatinine` varchar(100) NOT NULL,
  `egfr` varchar(100) NOT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rft`
--

INSERT INTO `rft` (`id`, `receipt_id`, `serum_urea`, `serum_bun`, `serum_creatinine`, `egfr`, `reporting_datetime`) VALUES
(4, 246, '20', '', '0.93', '', '2025-06-04 17:29:25'),
(6, 271, '25', '', '1.4', '', '2025-06-22 13:50:55');

-- --------------------------------------------------------

--
-- Table structure for table `semen_analysis`
--

CREATE TABLE `semen_analysis` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `colour` varchar(100) DEFAULT NULL,
  `quantity` varchar(100) DEFAULT NULL,
  `consistancy` varchar(100) DEFAULT NULL,
  `ph` varchar(100) DEFAULT NULL,
  `liquification_time` varchar(100) DEFAULT NULL,
  `total_sperm_count` varchar(100) DEFAULT NULL,
  `active_motile` varchar(100) DEFAULT NULL,
  `motile` varchar(100) DEFAULT NULL,
  `non_motile` varchar(100) DEFAULT NULL,
  `wbc_cells` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `semen_analysis`
--

INSERT INTO `semen_analysis` (`id`, `receipt_id`, `colour`, `quantity`, `consistancy`, `ph`, `liquification_time`, `total_sperm_count`, `active_motile`, `motile`, `non_motile`, `wbc_cells`, `reporting_datetime`) VALUES
(1, 268, 'grayish white', '2.0 ml', 'thin', '7.5', '15', '80', '65', '20', '15', '02---04', '2025-06-21 23:44:58');

-- --------------------------------------------------------

--
-- Table structure for table `serum_amh`
--

CREATE TABLE `serum_amh` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_amh` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `serum_ferritin`
--

CREATE TABLE `serum_ferritin` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_ferritin` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `serum_fsh`
--

CREATE TABLE `serum_fsh` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_fsh` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `serum_lh`
--

CREATE TABLE `serum_lh` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_lh` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `serum_monomeric_prolactin`
--

CREATE TABLE `serum_monomeric_prolactin` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_monomeric_prolactin` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `serum_prolactin`
--

CREATE TABLE `serum_prolactin` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_prolactin` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `serum_vitamin_b12`
--

CREATE TABLE `serum_vitamin_b12` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_vitamin_b12` varchar(100) NOT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `serum_vitamin_d`
--

CREATE TABLE `serum_vitamin_d` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_vitamin_d` varchar(100) NOT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stool_h_pylori`
--

CREATE TABLE `stool_h_pylori` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `stool_h_pylori` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stool_h_pylori`
--

INSERT INTO `stool_h_pylori` (`id`, `receipt_id`, `stool_h_pylori`, `reporting_datetime`) VALUES
(1, 269, 'Negative', '2025-06-25 10:31:50');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `table_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `test_name`, `fee`, `table_name`) VALUES
(2, 'Albumin', '500.00', 'albumin'),
(5, 'CBC Profile', '500.00', 'cpc_profile'),
(6, 'LFT', '600.00', 'lft'),
(7, 'RFT', '500.00', 'rft'),
(8, 'BSR', '200.00', 'bsr'),
(9, 'URIC ACID', '300.00', 'uric_acid'),
(10, 'ESR', '200.00', 'esr'),
(23, 'CRP', '500.00', NULL),
(24, 'BSF', '200.00', NULL),
(25, 'HEP B', '300.00', NULL),
(26, 'HEP C', '300.00', NULL),
(27, 'HIV', '300.00', NULL),
(28, 'URINE RE', '200.00', NULL),
(29, 'BLOOD GROUPING', '200.00', NULL),
(30, 'UPT', '200.00', NULL),
(31, 'ELECTROLYTES', '1000.00', NULL),
(32, 'AMYLASE', '500.00', NULL),
(33, 'DENGUE SEROLOGY', '500.00', NULL),
(34, 'DENGUE NS1', '500.00', NULL),
(35, 'H PYLORI', '500.00', NULL),
(36, 'RA FACTOR', '400.00', NULL),
(37, 'ASOT', '300.00', NULL),
(38, 'CALCIUM', '500.00', NULL),
(39, 'PHOSPHATE', '300.00', NULL),
(40, 'CARDIAC ENZYME', '400.00', NULL),
(41, 'BTCT', '400.00', NULL),
(42, 'PT', '250.00', NULL),
(43, 'APTT', '250.00', NULL),
(45, 'LIPID PROFILE', '500.00', NULL),
(48, 'HBA1C', '1500.00', NULL),
(49, 'Serum Vitamin B12', '500.00', NULL),
(50, 'Serum Vitamin D', '2500.00', NULL),
(51, 'SERUM AMH', '400.00', NULL),
(52, 'SERUM PROLACTIN', '400.00', NULL),
(53, 'SERUM MONOMERIC PROLACTIN', '400.00', NULL),
(54, 'SERUM FERRITIN', '1600.00', NULL),
(55, 'SERUM LH', '1000.00', NULL),
(56, 'SERUM FSH', '1000.00', NULL),
(57, 'THYROID PROFILE', '2400.00', NULL),
(61, 'LIPASE', '500.00', NULL),
(62, 'STOOL H PYLORI', '500.00', NULL),
(63, 'TYPHOID', '500.00', NULL),
(64, 'SEMEN ANALYSIS', '500.00', NULL),
(65, 'ALT', '500.00', NULL),
(66, 'UREA', '500.00', NULL),
(67, 'CREATININE', '500.00', NULL),
(68, 'HDL', '500.00', NULL),
(69, 'LDL', '500.00', NULL),
(70, 'TRIGLYCERIDES', '500.00', NULL),
(71, 'CHOLESTEROL', '500.00', NULL),
(72, 'TG CHOLESTEROL', '500.00', NULL),
(73, 'MALARIA PARASITE', '500.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test_records`
--

CREATE TABLE `test_records` (
  `id` int(11) NOT NULL,
  `mr_no` varchar(50) NOT NULL,
  `test_name` varchar(100) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `test_results`
--

CREATE TABLE `test_results` (
  `id` int(11) NOT NULL,
  `receipt_test_id` int(11) NOT NULL,
  `result` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tg_cholesterol`
--

CREATE TABLE `tg_cholesterol` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_triglycerides` varchar(100) DEFAULT NULL,
  `serum_total_cholesterol` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tg_cholesterol`
--

INSERT INTO `tg_cholesterol` (`id`, `receipt_id`, `serum_triglycerides`, `serum_total_cholesterol`, `reporting_datetime`) VALUES
(1, 266, '510', '245', '2025-06-22 16:51:01');

-- --------------------------------------------------------

--
-- Table structure for table `thyroid_profile`
--

CREATE TABLE `thyroid_profile` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_total_t4` varchar(100) DEFAULT NULL,
  `serum_total_t3` varchar(100) DEFAULT NULL,
  `serum_tsh` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `triglycerides`
--

CREATE TABLE `triglycerides` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_triglycerides` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `triglycerides`
--

INSERT INTO `triglycerides` (`id`, `receipt_id`, `serum_triglycerides`, `reporting_datetime`) VALUES
(1, 265, '130', '2025-06-22 16:37:30');

-- --------------------------------------------------------

--
-- Table structure for table `upt`
--

CREATE TABLE `upt` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `upt` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `upt`
--

INSERT INTO `upt` (`id`, `receipt_id`, `upt`, `reporting_datetime`) VALUES
(1, 268, 'NAGITIVE', '2025-06-28 10:24:46');

-- --------------------------------------------------------

--
-- Table structure for table `urea`
--

CREATE TABLE `urea` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `serum_urea` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `urea`
--

INSERT INTO `urea` (`id`, `receipt_id`, `serum_urea`, `reporting_datetime`) VALUES
(1, 264, '45', '2025-06-22 13:26:54');

-- --------------------------------------------------------

--
-- Table structure for table `uric_acid`
--

CREATE TABLE `uric_acid` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `uric_acid` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `urine_re`
--

CREATE TABLE `urine_re` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `colour` varchar(100) DEFAULT NULL,
  `turbidity` varchar(100) DEFAULT NULL,
  `sp_gravity` varchar(100) DEFAULT NULL,
  `ph` varchar(100) DEFAULT NULL,
  `leukocyte` varchar(100) DEFAULT NULL,
  `nitrite` varchar(100) DEFAULT NULL,
  `protein` varchar(100) DEFAULT NULL,
  `sugar` varchar(100) DEFAULT NULL,
  `ketones` varchar(100) DEFAULT NULL,
  `urobilinogen` varchar(100) DEFAULT NULL,
  `bilirubin` varchar(100) DEFAULT NULL,
  `heamoglobin` varchar(100) DEFAULT NULL,
  `pus_cells` varchar(100) DEFAULT NULL,
  `rbc` varchar(100) DEFAULT NULL,
  `epithelial` varchar(100) DEFAULT NULL,
  `amorphous` varchar(100) DEFAULT NULL,
  `calcium_oxalate` varchar(100) DEFAULT NULL,
  `yeast_cells` varchar(100) DEFAULT NULL,
  `dead_sperms` varchar(100) DEFAULT NULL,
  `misc` varchar(100) DEFAULT NULL,
  `granular_cast` varchar(100) DEFAULT NULL,
  `tyrosine_crystal` varchar(100) DEFAULT NULL,
  `reporting_datetime` datetime NOT NULL,
  `quantity` varchar(100) DEFAULT NULL,
  `appearance` varchar(100) DEFAULT NULL,
  `hyaline_cast` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `urine_re`
--

INSERT INTO `urine_re` (`id`, `receipt_id`, `colour`, `turbidity`, `sp_gravity`, `ph`, `leukocyte`, `nitrite`, `protein`, `sugar`, `ketones`, `urobilinogen`, `bilirubin`, `heamoglobin`, `pus_cells`, `rbc`, `epithelial`, `amorphous`, `calcium_oxalate`, `yeast_cells`, `dead_sperms`, `misc`, `granular_cast`, `tyrosine_crystal`, `reporting_datetime`, `quantity`, `appearance`, `hyaline_cast`) VALUES
(5, 253, 'PALE YELLO', 'CLEAR', '1.015', '5.0', 'NIL', 'NIL', 'TRACE', 'NIL', 'NIL', 'NIL', 'NIL', 'NIL', '4-6', '2-3', 'A FEW', '+', '++', 'NIL', 'NIL', 'NIL', 'NIL', 'NIL', '2025-06-04 19:11:14', '30 ML', 'CLEAR', 'NIL');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`) VALUES
(7, 'Admin', '1234', 'admin', 'admin@celllab.com'),
(12, 'ali', '1234', 'user', 'ali@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albumin`
--
ALTER TABLE `albumin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `alt`
--
ALTER TABLE `alt`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `amylase`
--
ALTER TABLE `amylase`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `aptt`
--
ALTER TABLE `aptt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asot`
--
ALTER TABLE `asot`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `available_tests`
--
ALTER TABLE `available_tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blood_grouping`
--
ALTER TABLE `blood_grouping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bsf`
--
ALTER TABLE `bsf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bsr`
--
ALTER TABLE `bsr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `btct`
--
ALTER TABLE `btct`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `calcium`
--
ALTER TABLE `calcium`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `cbc_profile`
--
ALTER TABLE `cbc_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `cholesterol`
--
ALTER TABLE `cholesterol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `creatinine`
--
ALTER TABLE `creatinine`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `crp`
--
ALTER TABLE `crp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dengue_ns1`
--
ALTER TABLE `dengue_ns1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `dengue_serology`
--
ALTER TABLE `dengue_serology`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_commissions`
--
ALTER TABLE `doctor_commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `earnings`
--
ALTER TABLE `earnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `electrolytes`
--
ALTER TABLE `electrolytes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `esr`
--
ALTER TABLE `esr`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `globulin`
--
ALTER TABLE `globulin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `hba1c`
--
ALTER TABLE `hba1c`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hdl`
--
ALTER TABLE `hdl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `hep_b`
--
ALTER TABLE `hep_b`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `hep_c`
--
ALTER TABLE `hep_c`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hiv`
--
ALTER TABLE `hiv`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `h_pylori`
--
ALTER TABLE `h_pylori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `lab_expenses`
--
ALTER TABLE `lab_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ldl`
--
ALTER TABLE `ldl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `lft`
--
ALTER TABLE `lft`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `lipase`
--
ALTER TABLE `lipase`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `lipid_profile`
--
ALTER TABLE `lipid_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `malaria_parasite`
--
ALTER TABLE `malaria_parasite`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mr_no` (`mr_no`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_no` (`receipt_no`);

--
-- Indexes for table `phosphate`
--
ALTER TABLE `phosphate`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `pt`
--
ALTER TABLE `pt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ra_factor`
--
ALTER TABLE `ra_factor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`receipt_id`),
  ADD UNIQUE KEY `receipt_no` (`receipt_no`);

--
-- Indexes for table `receipt_tests`
--
ALTER TABLE `receipt_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_receipt_id` (`receipt_id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rft`
--
ALTER TABLE `rft`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `semen_analysis`
--
ALTER TABLE `semen_analysis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `serum_amh`
--
ALTER TABLE `serum_amh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `serum_ferritin`
--
ALTER TABLE `serum_ferritin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `serum_fsh`
--
ALTER TABLE `serum_fsh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `serum_lh`
--
ALTER TABLE `serum_lh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `serum_monomeric_prolactin`
--
ALTER TABLE `serum_monomeric_prolactin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `serum_prolactin`
--
ALTER TABLE `serum_prolactin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `serum_vitamin_b12`
--
ALTER TABLE `serum_vitamin_b12`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `serum_vitamin_d`
--
ALTER TABLE `serum_vitamin_d`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `stool_h_pylori`
--
ALTER TABLE `stool_h_pylori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_records`
--
ALTER TABLE `test_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_test_id` (`receipt_test_id`);

--
-- Indexes for table `tg_cholesterol`
--
ALTER TABLE `tg_cholesterol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `thyroid_profile`
--
ALTER TABLE `thyroid_profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `triglycerides`
--
ALTER TABLE `triglycerides`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `upt`
--
ALTER TABLE `upt`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `urea`
--
ALTER TABLE `urea`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `uric_acid`
--
ALTER TABLE `uric_acid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `urine_re`
--
ALTER TABLE `urine_re`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albumin`
--
ALTER TABLE `albumin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `alt`
--
ALTER TABLE `alt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `amylase`
--
ALTER TABLE `amylase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `aptt`
--
ALTER TABLE `aptt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `asot`
--
ALTER TABLE `asot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `available_tests`
--
ALTER TABLE `available_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blood_grouping`
--
ALTER TABLE `blood_grouping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bsf`
--
ALTER TABLE `bsf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bsr`
--
ALTER TABLE `bsr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `btct`
--
ALTER TABLE `btct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `calcium`
--
ALTER TABLE `calcium`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cbc_profile`
--
ALTER TABLE `cbc_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `cholesterol`
--
ALTER TABLE `cholesterol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `creatinine`
--
ALTER TABLE `creatinine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `crp`
--
ALTER TABLE `crp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dengue_ns1`
--
ALTER TABLE `dengue_ns1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dengue_serology`
--
ALTER TABLE `dengue_serology`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `doctor_commissions`
--
ALTER TABLE `doctor_commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `earnings`
--
ALTER TABLE `earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `electrolytes`
--
ALTER TABLE `electrolytes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `esr`
--
ALTER TABLE `esr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `globulin`
--
ALTER TABLE `globulin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hba1c`
--
ALTER TABLE `hba1c`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hdl`
--
ALTER TABLE `hdl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hep_b`
--
ALTER TABLE `hep_b`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hep_c`
--
ALTER TABLE `hep_c`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hiv`
--
ALTER TABLE `hiv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `h_pylori`
--
ALTER TABLE `h_pylori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lab_expenses`
--
ALTER TABLE `lab_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `ldl`
--
ALTER TABLE `ldl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lft`
--
ALTER TABLE `lft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `lipase`
--
ALTER TABLE `lipase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lipid_profile`
--
ALTER TABLE `lipid_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `malaria_parasite`
--
ALTER TABLE `malaria_parasite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `phosphate`
--
ALTER TABLE `phosphate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pt`
--
ALTER TABLE `pt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ra_factor`
--
ALTER TABLE `ra_factor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- AUTO_INCREMENT for table `receipt_tests`
--
ALTER TABLE `receipt_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=551;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `rft`
--
ALTER TABLE `rft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `semen_analysis`
--
ALTER TABLE `semen_analysis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `serum_amh`
--
ALTER TABLE `serum_amh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `serum_ferritin`
--
ALTER TABLE `serum_ferritin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `serum_fsh`
--
ALTER TABLE `serum_fsh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `serum_lh`
--
ALTER TABLE `serum_lh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `serum_monomeric_prolactin`
--
ALTER TABLE `serum_monomeric_prolactin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `serum_prolactin`
--
ALTER TABLE `serum_prolactin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `serum_vitamin_b12`
--
ALTER TABLE `serum_vitamin_b12`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `serum_vitamin_d`
--
ALTER TABLE `serum_vitamin_d`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stool_h_pylori`
--
ALTER TABLE `stool_h_pylori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `test_records`
--
ALTER TABLE `test_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tg_cholesterol`
--
ALTER TABLE `tg_cholesterol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `thyroid_profile`
--
ALTER TABLE `thyroid_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `triglycerides`
--
ALTER TABLE `triglycerides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `upt`
--
ALTER TABLE `upt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `urea`
--
ALTER TABLE `urea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `uric_acid`
--
ALTER TABLE `uric_acid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `urine_re`
--
ALTER TABLE `urine_re`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cbc_profile`
--
ALTER TABLE `cbc_profile`
  ADD CONSTRAINT `cbc_profile_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`receipt_id`);

--
-- Constraints for table `doctor_commissions`
--
ALTER TABLE `doctor_commissions`
  ADD CONSTRAINT `doctor_commissions_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `doctor_commissions_ibfk_2` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`receipt_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receipt_tests`
--
ALTER TABLE `receipt_tests`
  ADD CONSTRAINT `fk_receipt_id` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`receipt_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receipt_tests_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`receipt_id`) ON DELETE CASCADE;

--
-- Constraints for table `test_results`
--
ALTER TABLE `test_results`
  ADD CONSTRAINT `test_results_ibfk_1` FOREIGN KEY (`receipt_test_id`) REFERENCES `receipt_tests` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
