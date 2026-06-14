-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 05:01 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospitalstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `allitems`
--

CREATE TABLE `allitems` (
  `id` int(11) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `storesection` varchar(50) NOT NULL,
  `quantity` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `allitems`
--

INSERT INTO `allitems` (`id`, `itemcode`, `itemname`, `category`, `storesection`, `quantity`) VALUES
(1, 'DTE 01.', 'DENTAL DENTURE FLASK', 'Current', 'labStore', 0),
(2, 'DTE 02', 'DENTAL DENTURE FLASK & CLAMP (SINGLE/DOUBLE)', 'Current', 'labStore', 0),
(3, 'DTE 03', 'TRIMMING MACHINE', 'Current', 'labStore', 0),
(4, 'DTE 04', 'LE CROWN CARVER', 'Current', 'labStore', 0),
(5, 'DTE 05', 'MIXING SPATULA', 'Current', 'labStore', 0),
(6, 'DTE 06', 'MATRIX CLAMPS', 'Current', 'labStore', 0),
(7, 'DTE 07', 'UPPER ROOT FORCEPS', 'Current', 'labStore', 0),
(8, 'DTE 08', 'METAL IMPRESSION TRAY (LARGE)', 'Current', 'labStore', 0),
(9, 'DTE 09', 'SCALER TIP', 'Current', 'labStore', 0),
(10, 'DTE 10', 'TOFFLEMIRE MATRIX BOND', 'Current', 'labStore', 0),
(11, 'DTE 11', 'SPATULA (6R, 11R)', 'Current', 'labStore', 0),
(12, 'DTE 12', 'UNIVERSAL SCALER', 'Current', 'labStore', 0),
(13, 'DTE 13', 'STICKLE SCALER', 'Current', 'labStore', 0),
(14, 'DTE 14', 'JOQUETTE SCALER', 'Current', 'labStore', 0),
(15, 'DTE 15', 'SPOON EXCAVATOR', 'Current', 'labStore', 0),
(16, 'DTE 16', 'HOE SCALER', 'Current', 'labStore', 0),
(17, 'DTE 17', 'DISTAL END (PLIERS/CUTTER)', 'Current', 'labStore', 0),
(18, 'DTE 18', 'HOOK CRIMPING PLIER', 'Current', 'labStore', 0),
(19, 'DTE 19', 'ADAMS PLIER', 'Current', 'labStore', 0),
(20, 'DTE 20', 'TWEED LOOP FOAMING PLIERS', 'Current', 'labStore', 0),
(21, 'DTE 21', 'WAX KNIFE LARGE', 'Current', 'labStore', 0),
(22, 'DTE 22', 'BURNISHER (BAR)', 'Current', 'labStore', 0),
(23, 'DTE 23', 'METAL DENTAL SYRINGE', 'Current', 'labStore', 0),
(24, 'DTE 24', 'AVERAGE VALUE ARTICULATOR', 'Current', 'labStore', 0),
(25, 'DTE 25', 'INSTRUMENT JAR', 'Current', 'labStore', 0),
(26, 'DTE 26', 'MIRROR HEADS & HANDLE', 'Current', 'labStore', 0),
(27, 'DTE 27', 'METAL POISON CUPBOARD', 'Current', 'labStore', 0),
(28, 'DTE 28', 'ARCH BAR', 'Current', 'labStore', 0),
(29, 'D01', 'COTTON WOOL', 'Current', 'labStore', 0),
(30, 'D02', 'PLASTER ROLL', 'Current', 'labStore', 0),
(31, 'D03', 'ABSORBENT GAUZE ROLL  ', 'Current', 'labStore', 0),
(32, 'D04', 'DISPOSABLE APRON  ', 'Current', 'labStore', 0),
(33, 'D05', 'HYDROGEN PEROXIDE                    ', 'Current', 'labStore', 0),
(34, 'RX01', 'X-Ray Film 35×43cm (17\"×14\")  ', 'Current', 'labStore', 0),
(35, 'RX02', 'X-Ray Film 35×35cm (14\"×14\")  ', 'Current', 'labStore', 0),
(36, 'RX03', 'X-Ray Film 24×30cm  ', 'Current', 'labStore', 0),
(37, 'RX04', 'X-Ray Film 30×40cm  ', 'Current', 'labStore', 0),
(38, 'RX05', ' X-Ray Film 18×24cm  ', 'Current', 'labStore', 0),
(39, 'RX06', 'X-Ray Developer  ', 'Current', 'labStore', 0),
(40, 'RX07', 'X-Ray Fixer  ', 'Current', 'labStore', 0),
(41, 'RX08', 'X-Ray Film Hanger 24×18cm  ', 'Current', 'labStore', 0),
(42, 'RX09', ' Urografin  ', 'Current', 'labStore', 0),
(43, 'RX10', 'Ultrasound Gel  ', 'Current', 'labStore', 0),
(44, 'RX11', 'Thermoluminescent Dosimeter (TLD) Badges  ', 'Current', 'labStore', 0),
(45, 'RX12', 'X-Ray Warning Light', 'Current', 'labStore', 0),
(46, 'LC1', 'Sodium Citrate (500)', 'Current', 'labStore', 0),
(47, 'LC2', 'Sulphuric Acid', 'Current', 'labStore', 0),
(48, 'LC3', 'Sodium Hydroxide', 'Current', 'labStore', 0),
(49, 'LC4', 'Hydrochloric Acid', 'Current', 'labStore', 0),
(50, 'LC5', 'Leishman Stain', 'Current', 'labStore', 0),
(51, 'LC6', 'Geimsa\'s Stain', 'Current', 'labStore', 0),
(52, 'LC7', 'Methylene Blue', 'Current', 'labStore', 0),
(53, 'LC8', 'Eosin Y', 'Current', 'labStore', 0),
(54, 'LC9', 'Sterile Swabs', 'Current', 'labStore', 0),
(55, 'LC10', 'Combi 3', 'Current', 'labStore', 0),
(56, 'LC11', 'Combi 2', 'Current', 'labStore', 0),
(57, 'LC12', 'Stat Pak HIV Kit', 'Current', 'labStore', 0),
(58, 'LC13', 'Centrifuge Tube Screw Cap', 'Current', 'labStore', 0),
(59, 'LC14', 'Microscope Glass Slide', 'Current', 'labStore', 0),
(60, 'LC15', 'Plastic Universal Container', 'Current', 'labStore', 0),
(61, 'LC16', 'Cover Slip 22 x 22 cm', 'Current', 'labStore', 0),
(62, 'LC17', 'Esbach Solution', 'Current', 'labStore', 0),
(63, 'LC18', 'Urea', 'Current', 'labStore', 0),
(64, 'LC19', 'Hepatitis B Kit', 'Current', 'labStore', 0),
(65, 'LC20', 'Vacutainer Tubes K2 EDTA', 'Current', 'labStore', 0),
(66, 'LC21', 'Hepatitis C Kit', 'Current', 'labStore', 0),
(67, 'LC22', 'Immersion Oil', 'Current', 'labStore', 0),
(68, 'LC23', 'Paraffin Wax', 'Current', 'labStore', 0),
(69, 'LC24', 'Grease Pencil (Marker)', 'Current', 'labStore', 0),
(70, 'LC25', 'Lactophenol Blue', 'Current', 'labStore', 0),
(71, 'LC26', 'Cover Slips 22 x 50 mm', 'Current', 'labStore', 0),
(72, 'LC27', 'Desoxycholate Citrate Agar (DCA)', 'Current', 'labStore', 0),
(73, 'LC28', 'Mueller Hinton Agar', 'Current', 'labStore', 0),
(74, 'LC29', 'CLED Agar', 'Current', 'labStore', 0),
(75, 'LC30', 'Thriglycollate Medium', 'Current', 'labStore', 0),
(76, 'LC31', 'Sucrose', 'Current', 'labStore', 0),
(77, 'LC32', 'Triple Sugar Iron Agar', 'Current', 'labStore', 0),
(78, 'LC33', 'Kligler\'s Iron Agar (KIA)', 'Current', 'labStore', 0),
(79, 'LC34', 'T.C.B.S', 'Current', 'labStore', 0),
(80, 'LC35', 'Potassium Permanganate', 'Current', 'labStore', 0),
(81, 'LC36', 'Sodium Sulphate', 'Current', 'labStore', 0),
(82, 'LC37', 'Sodium Chloride', 'Current', 'labStore', 0),
(83, 'LC38', 'Whatman Filter Paper', 'Current', 'labStore', 0),
(84, 'LC39', 'EP Printer Paper/Thermal Paper', 'Current', 'labStore', 0),
(85, 'LC40', 'Improved Neubauer Counting Chamber', 'Current', 'labStore', 0),
(86, 'LC41', 'Diacetyl Monoxime', 'Current', 'labStore', 0),
(87, 'LC42', 'Neutral Red', 'Current', 'labStore', 0),
(88, 'LC43', 'Contour Clucometer Strips', 'Current', 'labStore', 0),
(89, 'LC44', 'Periodic Acid', 'Current', 'labStore', 0),
(90, 'LC45', 'Formic Acid', 'Current', 'labStore', 0),
(91, 'LC46', 'Crystal Violet', 'Current', 'labStore', 0),
(92, 'LC47', 'Double Blood Bags', 'Current', 'labStore', 0),
(93, 'LC48', 'Single Blood Bags', 'Current', 'labStore', 0),
(94, 'LC49', 'EDTA Tubes (Bottles)', 'Current', 'labStore', 0),
(95, 'LC50', 'Plain Separating Bottles', 'Current', 'labStore', 0),
(96, 'LC 51', 'Fluoride Oxalate', 'Current', 'labStore', 0),
(97, 'LC 52', 'Ethanol', 'Current', 'labStore', 0),
(98, 'LC 53', 'Formalin', 'Current', 'labStore', 0),
(99, 'LC 54', 'Methanol', 'Current', 'labStore', 0),
(100, 'LC 55', 'Xylene', 'Current', 'labStore', 0),
(101, 'LC 56', 'Isopropanol (IPA)', 'Current', 'labStore', 0),
(102, 'LC 57', 'Slide Label', 'Current', 'labStore', 0),
(103, 'LC 58', 'Syphilis Screening Stain', 'Current', 'labStore', 0),
(104, 'LC 59', 'Diethyl Ether', 'Current', 'labStore', 0),
(105, 'LC 60', 'Brilliant Cresyl Blue', 'Current', 'labStore', 0),
(106, 'LC 61', 'Applicator/Orange Stick', 'Current', 'labStore', 0),
(107, 'LC 62', 'Peptone Water', 'Current', 'labStore', 0),
(108, 'LC 63', 'Blood Agar Base', 'Current', 'labStore', 0),
(109, 'LC 64', 'MacConkey Agar', 'Current', 'labStore', 0),
(110, 'LC 65', 'Petri Dish', 'Current', 'labStore', 0),
(111, 'LC 66', 'Stromatolyzer', 'Current', 'labStore', 0),
(112, 'LC 67', 'Blood Lancet', 'Current', 'labStore', 0),
(113, 'LC 68', 'Plain Capillary Tube', 'Current', 'labStore', 0),
(114, 'LC 69', 'HEPARINIZED CAPILLARY TUBE', 'Current', 'labStore', 0),
(115, 'LC 70', 'DISPETTE ESR TUBE', 'Current', 'labStore', 0),
(116, 'LC 71', 'CELLULOSE ACETATE STRIP', 'Current', 'labStore', 0),
(117, 'LC 72', 'DETERMINE HIV KIT', 'Current', 'labStore', 0),
(118, 'LC 73', 'GLUCOSE KIT', 'Current', 'labStore', 0),
(119, 'LC 74', 'LH KIT', 'Current', 'labStore', 0),
(120, 'LC 75', 'LDL/HL CHOLESTEROL KIT', 'Current', 'labStore', 0),
(121, 'LC 76', 'TOTAL T3 KIT', 'Current', 'labStore', 0),
(122, 'LC 77', 'TOTAL T4 KIT', 'Current', 'labStore', 0),
(123, 'LC 78', 'PROGESTERONE KIT', 'Current', 'labStore', 0),
(124, 'LC 79', 'TESTOSTERONE KIT', 'Current', 'labStore', 0),
(125, 'LC 80', 'ANTI HUMAN GLOBULIN (AHG)', 'Current', 'labStore', 0),
(126, 'LC 81', 'BOVINE ALBUMINE', 'Current', 'labStore', 0),
(127, 'LC 82', 'MULTODISK POSITIVE', 'Current', 'labStore', 0),
(128, 'LC 83', 'MULTODISK NEGATIVE', 'Current', 'labStore', 0),
(129, 'LC 84', 'HUMANTROL LOW CONTROL RANDOX', 'Current', 'labStore', 0),
(130, 'LC 85', 'HUMANTROL NORMAL CONTROL RANDOX', 'Current', 'labStore', 0),
(131, 'LC 86', 'HUMANTROL ELEVATED CONTROL RANDOX', 'Current', 'labStore', 0),
(132, 'LC 87', 'DAILY CLEANING SOLUTION', 'Current', 'labStore', 0),
(133, 'LC 88', 'ISE CONTROL (Na, K, a, Li)', 'Current', 'labStore', 0),
(134, 'LC 89', 'CEFTRIAZONE (CRO39)', 'Current', 'labStore', 0),
(135, 'LC 90', 'CEFOtaxime/CLAVULANIC ACID', 'Current', 'labStore', 0),
(136, 'LC 91', 'CETAZIDINE/CLAVULANIC ACID', 'Current', 'labStore', 0),
(137, 'LC 92', 'CEFPODOXIME (10ug)', 'Current', 'labStore', 0),
(138, 'LC 93', 'MEROPENEM HG', 'Current', 'labStore', 0),
(139, 'LC 94', 'VANCOMYCIN', 'Current', 'labStore', 0),
(140, 'LC 95', 'S.G.O.T (AST) KIT', 'Current', 'labStore', 0),
(141, 'LC 96', 'S.G.P.T (ALT) KIT', 'Current', 'labStore', 0),
(142, 'LC 97', 'ANTI A', 'Current', 'labStore', 0),
(143, 'LC 98', 'ANTI B', 'Current', 'labStore', 0),
(144, 'LC 99', 'ANTI A + B', 'Current', 'labStore', 0),
(145, 'LC 100', 'ANTI D', 'Current', 'labStore', 0),
(146, 'LC 101', 'APTT REAGENT', 'Current', 'labStore', 0),
(147, 'LC 102', 'PTT THROMBOPLASTIN REAGENT', 'Current', 'labStore', 0),
(148, 'LC 103', 'FREE T3 KIT', 'Current', 'labStore', 0),
(149, 'LC 104', 'FREE T4', 'Current', 'labStore', 0),
(150, 'LC 105', 'Inorganic Phosphorus Kit', 'Current', 'labStore', 0),
(151, 'LC 106', 'Alkaline Phosphatase Kit', 'Current', 'labStore', 0),
(152, 'LC 107', 'Uric Acid Kit', 'Current', 'labStore', 0),
(153, 'LC 108', 'Total Cholesterol Kit', 'Current', 'labStore', 0),
(154, 'LC 109', 'P.S.A Kit', 'Current', 'labStore', 0),
(155, 'LC 110', 'L.D.H Kit', 'Current', 'labStore', 0),
(156, 'LC 111', 'FSH Kit', 'Current', 'labStore', 0),
(157, 'LC 112', 'AFP Kit', 'Current', 'labStore', 0),
(158, 'LC 113', 'Oestradiol Kit', 'Current', 'labStore', 0),
(159, 'LC 114', 'TSH Kit', 'Current', 'labStore', 0),
(160, 'LC 115', 'Beta HCG Kit', 'Current', 'labStore', 0),
(161, 'LC 116', 'Prolactin Kit', 'Current', 'labStore', 0),
(162, 'LC 117', 'Calcium Kit', 'Current', 'labStore', 0),
(163, 'LC 118', 'Amylase Kit', 'Current', 'labStore', 0),
(164, 'LC 119', 'Triglyceride Kit', 'Current', 'labStore', 0),
(165, 'LC 120', 'Lithium Heparin', 'Current', 'labStore', 0),
(166, 'LC 121', 'Cassette/Lamb Processor Mould', 'Current', 'labStore', 0),
(167, 'LC 122', 'Methylated Spirit', 'Current', 'labStore', 0),
(168, 'LC 123', 'EDTA Plain Tubes', 'Current', 'labStore', 0),
(169, 'LC 124', 'Cell Clean', 'Current', 'labStore', 0),
(170, 'LC 125', 'Code Free Test Strip', 'Current', 'labStore', 0),
(171, 'LC 126', 'Lab Thermometer - Digital', 'Current', 'labStore', 0),
(172, 'LC 127', 'Reflotron Potassium', 'Current', 'labStore', 0),
(173, 'LC 128', 'Reflotron Creatinine', 'Current', 'labStore', 0),
(174, 'LC 129', 'Reflotron Urea', 'Current', 'labStore', 0),
(175, 'LC 130', 'Ungold HIV Kit', 'Current', 'labStore', 0),
(176, 'LC 131', 'Genscreen Ultra HIV Kit', 'Current', 'labStore', 0),
(177, 'LC 132', 'Iodine Crystal', 'Current', 'labStore', 0),
(178, 'LC 133', 'NI NI NI Tetramethyl Di-Hydrochloride (OXIDASE RGT)', 'Current', 'labStore', 0),
(179, 'LC 134', 'Boric Acid', 'Current', 'labStore', 0),
(180, 'LC 135', 'Tetraborate Borax (Sodium Tetraborate)', 'Current', 'labStore', 0),
(181, 'LC 136', 'Pregnancy Test Kit', 'Current', 'labStore', 0),
(182, 'LC 137', 'Disposable Microtone Blade (35)', 'Current', 'labStore', 0),
(183, 'LC 138', 'Cover Slip 22 x 40 mm', 'Current', 'labStore', 0),
(184, 'LC 139', 'Hematocylin', 'Current', 'labStore', 0),
(185, 'LC 140', 'Acid Phosphatase', 'Current', 'labStore', 0),
(186, 'LC 141', 'Manitol Salt Agar', 'Current', 'labStore', 0),
(187, 'LC 142', 'Tris Buffer Salt', 'Current', 'labStore', 0),
(188, 'LC 143', 'Plasticine', 'Current', 'labStore', 0),
(189, 'LC 144', 'Keg of Glycerol', 'Current', 'labStore', 0),
(190, 'LC 145', 'Cefoxitine (Fox 30)', 'Current', 'labStore', 0),
(191, 'LC 146', 'Imipenem (imi 10)', 'Current', 'labStore', 0),
(192, 'LC 147', 'Rheumatoid Factor (Teco)', 'Current', 'labStore', 0),
(193, 'LC 148', 'Reflotron Hdl Cholesterol', 'Current', 'labStore', 0),
(194, 'LC 149', 'One Step Multi HBV Device', 'Current', 'labStore', 0),
(195, 'LC 150', 'Differentral Cell Counter F', 'Current', 'labStore', 0),
(196, 'LC 151', 'Potassium iodide', 'Current', 'labStore', 0),
(197, 'LC 152', 'Sabraud Dextrose', 'Current', 'labStore', 0),
(198, 'LC 153', 'Multisix Strips', 'Current', 'labStore', 0),
(199, 'LC 154', 'Benedicts Solution', 'Current', 'labStore', 0),
(200, 'LC 155', 'Hbc 1gm', 'Current', 'labStore', 0),
(201, 'LC 156', 'HBb Ab', 'Current', 'labStore', 0),
(202, 'LC 157', 'Lactose Sugar', 'Current', 'labStore', 0),
(203, 'LC 158', 'Optochin', 'Current', 'labStore', 0),
(204, 'LC 159', 'Trytone Soya Broth', 'Current', 'labStore', 0),
(205, 'LC 160', 'Sodium Bisellentite', 'Current', 'labStore', 0),
(206, 'LC 161', 'Sellenite Broth Base', 'Current', 'labStore', 0),
(207, 'LC 162', 'Sodium Benzoate', 'Current', 'labStore', 0),
(208, 'LC 163', 'Reflotron Glucose', 'Current', 'labStore', 0),
(209, 'LC 164', 'Potassium Hydroxide', 'Current', 'labStore', 0),
(210, 'LC 165', 'Ammonium Feric Sulphate', 'Current', 'labStore', 0),
(211, 'LC 166', 'Hystomount Cell Path', 'Current', 'labStore', 0),
(212, 'LC 167', 'Potassium Dichromate', 'Current', 'labStore', 0),
(213, 'LC 168', 'G.C. Agar Base', 'Current', 'labStore', 0),
(214, 'LC 169', 'Ofloxacin (5x50 Disc)', 'Current', 'labStore', 0),
(215, 'LC 170', 'Azithromycin (5x50 Disc)', 'Current', 'labStore', 0),
(216, 'LC 171', 'Cefuroxime', 'Current', 'labStore', 0),
(217, 'LC 172', 'Acetic Acid', 'Current', 'labStore', 0),
(218, 'LC 173', 'E-Coli Antisera 1-4', 'Current', 'labStore', 0),
(219, 'LC 174', 'Sodium Hydroxide', 'Current', 'labStore', 0),
(220, 'LC 175', 'EA 65 (Papanicolaou stain)', 'Current', 'labStore', 0),
(221, 'LC 176', 'OG-6 (Orange G)', 'Current', 'labStore', 0),
(222, 'LC 177', 'HBe Ag 20 Test', 'Current', 'labStore', 0),
(223, 'LC 178', 'Salmonella Shigella Agar', 'Current', 'labStore', 0),
(224, 'LC 179', 'Reflotron GOT (AST)', 'Current', 'labStore', 0),
(225, 'LC 180', 'Reflotron GPT (ALT)', 'Current', 'labStore', 0),
(226, 'LC 181', 'Reflotron Triglyceride', 'Current', 'labStore', 0),
(227, 'LC 182', 'Reflotron Cholesterol', 'Current', 'labStore', 0),
(228, 'LC 183', 'Aluminum Ammonium Sulphate', 'Current', 'labStore', 0),
(229, 'LC 184', 'Ascorbic Acid', 'Current', 'labStore', 0),
(230, 'LC 185', 'Combi 10 Strips', 'Current', 'labStore', 0),
(231, 'LC 186', 'Paraplast', 'Current', 'labStore', 0),
(232, 'LC 187', 'Chromatography Paper', 'Current', 'labStore', 0),
(233, 'LC 188', 'EA 36', 'Current', 'labStore', 0),
(234, 'LC 189', 'Pot Sodium Tertante', 'Current', 'labStore', 0),
(235, 'LC 190', 'Silver Nitrate', 'Current', 'labStore', 0),
(236, 'LC 191', 'C-Reactive Protein Kit', 'Current', 'labStore', 0),
(237, 'LC 192', 'Glycated HB Kit', 'Current', 'labStore', 0),
(238, 'LC 193', 'Occult Blood', 'Current', 'labStore', 0),
(239, 'LC 194', 'Neprokin Solution', 'Current', 'labStore', 0),
(240, 'LC 195', 'Quantity Central Solution', 'Current', 'labStore', 0),
(241, 'LC 196', 'Cortisol Kit', 'Current', 'labStore', 0),
(242, 'LC 197', 'Sodium Tungstate', 'Current', 'labStore', 0),
(243, 'LC 198', 'Bactel 9050 Red Plus', 'Current', 'labStore', 0),
(244, 'LC 199', 'Bactel 9050 Aerobic', 'Current', 'labStore', 0),
(245, 'LC 200', 'Electrolyte Standard', 'Current', 'labStore', 0),
(246, 'LC 201', 'Sodium Acetate 500 GRM', 'Current', 'labStore', 0),
(247, 'LC 202', 'Sodium Nitrite', 'Current', 'labStore', 0),
(248, 'LC 203', 'Augmentin', 'Current', 'labStore', 0),
(249, 'LC 204', 'Ferric Chloride', 'Current', 'labStore', 0),
(250, 'LC 205', 'Manessium Chloride', 'Current', 'labStore', 0),
(251, 'LC 206', 'Potassium Nitrate', 'Current', 'labStore', 0),
(252, 'LC 207', 'Povidon Iodine (500ml)', 'Current', 'labStore', 0),
(253, 'LC 208', 'Reflotron Bilirubin', 'Current', 'labStore', 0),
(254, 'LC 209', 'Ammonium Oxalate', 'Current', 'labStore', 0),
(255, 'LC 210', 'Teponin', 'Current', 'labStore', 0),
(256, 'LC 211', 'Myoglobin', 'Current', 'labStore', 0),
(257, 'LC 212', 'D-Dinner', 'Current', 'labStore', 0),
(258, 'LC 213', 'Nt-Pro BNP', 'Current', 'labStore', 0),
(259, 'LC 214', 'Ck-MB', 'Current', 'labStore', 0),
(260, 'LC 215', 'Schiff\'s Reagent', 'Current', 'labStore', 0),
(261, 'LC 216', 'CEA', 'Current', 'labStore', 0),
(262, 'LC 217', 'Compliment (Standard Rabbit)', 'Current', 'labStore', 0),
(263, 'LC 218', 'Tris Borate Edta Butter', 'Current', 'labStore', 0),
(264, 'LC 219', '0.1 20UL TIP clear 1000pes', 'Current', 'labStore', 0),
(265, 'LC 220', 'Uric Acid (Reflotron)', 'Current', 'labStore', 0),
(266, 'LC 221', 'Simmons Citrate AGAR', 'Current', 'labStore', 0),
(267, 'LC 222', 'Magnesium Kit', 'Current', 'labStore', 0),
(268, 'LC 223', 'Total Protein Kit', 'Current', 'labStore', 0),
(269, 'LC 224', 'Albumin Kit', 'Current', 'labStore', 0),
(270, 'LC 225', '1-Chamber Kit', 'Current', 'labStore', 0),
(271, 'LC 226', 'Ciprofloxacin', 'Current', 'labStore', 0),
(272, 'LC 227', 'Reflotron GGT', 'Current', 'labStore', 0),
(273, 'LC 228', 'Cal pak Systrex', 'Current', 'labStore', 0),
(274, 'LC 229', 'Ferritin', 'Current', 'labStore', 0),
(275, 'LC 230', 'Safranin', 'Current', 'labStore', 0),
(276, 'LC 231', 'ISE 6000 2C standard', 'Current', 'labStore', 0),
(277, 'LC 232', 'Pic Active Solution', 'Current', 'labStore', 0),
(278, 'LC 233', 'Sodium D Hydrogen', 'Current', 'labStore', 0),
(279, 'LC 234', '125ml Ultraclean Diluent', 'Current', 'labStore', 0),
(280, 'LC 235', 'Phenol Crystal 500g', 'Current', 'labStore', 0),
(281, 'LC 236', 'Urea Kit', 'Current', 'labStore', 0),
(282, 'LC 237', 'Acid Fuchsin powder', 'Current', 'labStore', 0),
(283, 'LC 238', 'Erythromycin', 'Current', 'labStore', 0),
(284, 'LC 239', 'Streptomycin', 'Current', 'labStore', 0),
(285, 'LC 240', 'Gentamycin', 'Current', 'labStore', 0),
(286, 'LC 241', 'Amoxicillin', 'Current', 'labStore', 0),
(287, 'LC 242', 'Nitrofuratoin', 'Current', 'labStore', 0),
(288, 'LC 243', 'Levofloxacin', 'Current', 'labStore', 0),
(289, 'LC 244', 'EA 50', 'Current', 'labStore', 0),
(290, 'LC 245', 'Cloxaicillin', 'Current', 'labStore', 0),
(291, 'LC 246', 'pH paper (seman)', 'Current', 'labStore', 0),
(292, 'LC 247', 'Astreanam/Aztreoncin', 'Current', 'labStore', 0),
(293, 'LC 248', 'Bactracin', 'Current', 'labStore', 0),
(294, 'LC 249', 'GF-NACA Mii kit/Q/Ampliind Rava', 'Current', 'labStore', 0),
(295, 'LC 250', 'Piperacillin', 'Current', 'labStore', 0),
(296, 'LC 251', 'Diluent', 'Current', 'labStore', 0),
(297, 'LC 252', 'Lyse', 'Current', 'labStore', 0),
(298, 'LC 253', 'Cleanser', 'Current', 'labStore', 0),
(299, 'LC 254', 'RDT Kit for Malaria', 'Current', 'labStore', 0),
(300, 'LC 255', 'HCV ELISA Kit (Hepatitis C) Fridge', 'Current', 'labStore', 0),
(301, 'LC 256', 'Syphilis ELISA Kit (Fridge)', 'Current', 'labStore', 0),
(302, 'LC 257', 'Hepatitis B ELISA Kit', 'Current', 'labStore', 0),
(303, 'LC 258', 'HIV ELISA Kit', 'Current', 'labStore', 0),
(304, 'LC 259', 'Lin Zolid', 'Current', 'labStore', 0),
(305, 'LC 260', 'Clindamycin G', 'Current', 'labStore', 0),
(306, 'LC 261', 'Penicillin', 'Current', 'labStore', 0),
(307, 'LC 262', 'Septrin', 'Current', 'labStore', 0),
(308, 'LC 263', 'Sim (Sulphur Woodic)', 'Current', 'labStore', 0),
(309, 'LC 264', 'Standard F-200', 'Current', 'labStore', 0),
(310, 'LC 265', 'Café Prime', 'Current', 'labStore', 0),
(311, 'LC 266', 'Caffeine', 'Current', 'labStore', 0),
(312, 'LC 267', 'Elga Catd', 'Current', 'labStore', 0),
(313, 'LC 268', 'Estroglio', 'Current', 'labStore', 0),
(314, 'LC 269', 'Chemiluminescent substrate', 'Current', 'labStore', 0),
(315, 'LC 270', 'Wash Buffer', 'Current', 'labStore', 0),
(316, 'LC 271', 'Auto Lumo Sample Tube', 'Current', 'labStore', 0),
(317, 'LC 272', 'Noubactin', 'Current', 'labStore', 0),
(318, 'LC 273', 'Inositol Sugar', 'Current', 'labStore', 0),
(319, 'LC 274', 'H-Pylorus', 'Current', 'labStore', 0),
(320, 'LC 275', 'Assay Cup', 'Current', 'labStore', 0),
(321, 'LC 276', 'Sys Wash', 'Current', 'labStore', 0),
(322, 'LC 277', 'Vitamin A', 'Current', 'labStore', 0),
(323, 'LEQ01', 'KHAN TUBE', 'Current', 'labStore', 0),
(324, 'LEQ02', 'PRECIPITIN TUBES', 'Current', 'labStore', 0),
(325, 'LEQ03', 'TORNIQUE', 'Current', 'labStore', 0),
(326, 'LEQ04', 'TEST TUBE RACK', 'Current', 'labStore', 0),
(327, 'LEQ05', 'URINE JAR', 'Current', 'labStore', 0),
(328, 'LEQ06', 'FLAT BOTTOM FLASK PYREX (2L)', 'Current', 'labStore', 0),
(329, 'LEQ07', 'HAEMATOCRIT CENTRIFUGE READER', 'Current', 'labStore', 0),
(330, 'LEQ08', 'BIJOU BOTTLE', 'Current', 'labStore', 0),
(331, 'LEQ09', 'GLASS BLOOD CULTURE BOTTLE', 'Current', 'labStore', 0),
(332, 'LEQ10', 'STRAIGHT PIPETTES (5ML)', 'Current', 'labStore', 0),
(333, 'LEQ11', 'STRAIGHT PIPETTES (1ML)', 'Current', 'labStore', 0),
(334, 'LEQ12', 'BEAKER (500MLS)', 'Current', 'labStore', 0),
(335, 'LEQ13', 'BEAKER (250 MLS)', 'Current', 'labStore', 0),
(336, 'LEQ14', 'GLASS BEAKER (1000 MLS)', 'Current', 'labStore', 0),
(337, 'LEQ15', 'BUNSEN BURNER', 'Current', 'labStore', 0),
(338, 'LEQ16', 'WIRE GAUZE', 'Current', 'labStore', 0),
(339, 'LEQ17', 'PIPETTE PUMP', 'Current', 'labStore', 0),
(340, 'LEQ18', 'PLASTIC TEST TUBE', 'Current', 'labStore', 0),
(341, 'LEQ19', 'BRUSH', 'Current', 'labStore', 0),
(342, 'LEQ20', 'PIPETTES (50MLS)', 'Current', 'labStore', 0),
(343, 'LEQ21', 'AUTOMATIC MICRO PIPETTES (VARIOUS SIZES)', 'Current', 'labStore', 0),
(344, 'LEQ22', 'MEASURING CYLINDER (250MLS)', 'Current', 'labStore', 0),
(345, 'LEQ23', 'PLASTIC CENTRIFUGE TEST TUBE', 'Current', 'labStore', 0),
(346, 'LEQ24', 'STAIN DROPPING BOTTLE', 'Current', 'labStore', 0),
(347, 'LEQ25', 'AUTOMATIC PIPETTE TIP (DIFF. COLOUR)', 'Current', 'labStore', 0),
(348, 'LEQ26', 'GLASS DISTILLER HEATER & STAND', 'Current', 'labStore', 0),
(349, 'LEQ27', 'PASTEUR PIPETTE', 'Current', 'labStore', 0),
(350, 'LEQ28', 'AUTOMATIC PIPETTE TIP', 'Current', 'labStore', 0),
(351, 'LEQ29', 'TOP DISPENSER BOTTLE', 'Current', 'labStore', 0),
(352, 'LEQ30', 'WIRE LOOP HANDLE', 'Current', 'labStore', 0),
(353, 'LEQ31', 'SLIDE TRAY', 'Current', 'labStore', 0),
(354, 'LEQ32', 'TRIPOD STAND', 'Current', 'labStore', 0),
(355, 'LEQ33', 'PYREX TEST TUBE (STD)', 'Current', 'labStore', 0),
(356, 'LEQ34', 'PLASTIC WASH BOTTLE (500MLS)', 'Current', 'labStore', 0),
(357, 'LEQ35', 'PLASTIC WASH BOTTLE (250MLS)', 'Current', 'labStore', 0),
(358, 'LEQ36', 'SPIRIT LAMP', 'Current', 'labStore', 0),
(359, 'LEQ37', 'GLASS MEASURING CYLINDER (1000MLS)', 'Current', 'labStore', 0),
(360, 'LEQ38', 'GLASS MEASURING CYLINDER (500 MLS)', 'Current', 'labStore', 0),
(361, 'LEQ39', 'GLASS MEASURING CYLINDER (100MLS)', 'Current', 'labStore', 0),
(362, 'LEQ40', 'ASPIRATOR BOTTLE', 'Current', 'labStore', 0),
(363, 'LEQ41', '750 ML GLASS BEAKER', 'Current', 'labStore', 0),
(364, 'LEQ42', 'BINOCULAR MICROSCOPE', 'Current', 'labStore', 0),
(365, 'LEQ43', 'ELECTROLYTE ANALYZER (GE 300)', 'Current', 'labStore', 0),
(366, 'LEQ44', 'MICROPLATE READER /AUTOMATIC WASHER INCUBATOR', 'Current', 'labStore', 0),
(367, 'LEQ45', 'PLOGS FOR WEIGHING BALANCE', 'Current', 'labStore', 0),
(368, 'LEQ46', 'RODENT TEMP. THERMOMETER', 'Current', 'labStore', 0),
(369, 'LEQ47', 'pH METER', 'Current', 'labStore', 0),
(370, 'LEQ48', 'HAND LENS', 'Current', 'labStore', 0),
(371, 'LEQ49', 'PLASMA EXPRESS', 'Current', 'labStore', 0),
(372, 'LEQ50', '4V-VISIBLE SPECTROPHOTOMETER', 'Current', 'labStore', 0),
(373, 'LEQ51', 'TSL BOTTLES', 'Current', 'labStore', 0),
(374, 'LEQ52', 'RIQAS (PROGRAMMES)', 'Current', 'labStore', 0),
(375, 'LEQ53', 'TALLY COUNTER', 'Current', 'labStore', 0),
(376, 'LEQ54', 'SPATULA METAL', 'Current', 'labStore', 0),
(377, 'LEQ55', 'VORTEX MACHINE', 'Current', 'labStore', 0),
(378, 'LEQ56', 'CLINICAL MX', 'Current', 'labStore', 0),
(379, 'CM 01', 'PVC Pipe 4\"', 'Current', 'civilStore', 0),
(380, 'CM 02', 'PVC Elbow 4\"', 'Current', 'civilStore', 0),
(381, 'CM 03', 'Drawer Lock', 'Current', 'civilStore', 0),
(382, 'CM 04', 'Mortar Lock', 'Current', 'civilStore', 0),
(383, 'CM 05', '¾\" Nipple', 'Current', 'civilStore', 0),
(384, 'CM 06', '¾\" Union Connector', 'Current', 'civilStore', 0),
(385, 'CM 07', '½\" Gate Valve', 'Current', 'civilStore', 0),
(386, 'CM 08', 'Toilet Flush Handle', 'Current', 'civilStore', 0),
(387, 'CM 09', '¾\" Brass Tap', 'Current', 'civilStore', 0),
(388, 'CM 10', 'PVC Bend 6\" Big Elbow', 'Current', 'civilStore', 0),
(389, 'CM 11', '1½\" Plugs', 'Current', 'civilStore', 0),
(390, 'CM 12', '½\" Nipple', 'Current', 'civilStore', 0),
(391, 'CM 13', '3/4 x ½\" Tees', 'Current', 'civilStore', 0),
(392, 'CM 14', '¾\" Non Return Valve', 'Current', 'civilStore', 0),
(393, 'CM 15', '¾\" Gate Valve', 'Current', 'civilStore', 0),
(394, 'CM 16', '¾\" Plugs', 'Current', 'civilStore', 0),
(395, 'CM 17', '4\" Brass Hinges', 'Current', 'civilStore', 0),
(396, 'CM 18', '½\" x ¾\" Elbow', 'Current', 'civilStore', 0),
(397, 'CM 19', '1\" Elbow Copper', 'Current', 'civilStore', 0),
(398, 'CM 20', '1¼\" Tee Copper', 'Current', 'civilStore', 0),
(399, 'CM 21', '1¼\" Bottle Trap', 'Current', 'civilStore', 0),
(400, 'CM 22', '½\" Elbow', 'Current', 'civilStore', 0),
(401, 'CM 23', '2\" Nail', 'Current', 'civilStore', 0),
(402, 'CM 24', '1\" Plug', 'Current', 'civilStore', 0),
(403, 'CM 25', '2\" Socket', 'Current', 'civilStore', 0),
(404, 'CM 26', '½\" Union Connector', 'Current', 'civilStore', 0),
(405, 'CM 27', '½\" Nipple', 'Current', 'civilStore', 0),
(406, 'CM 28', '½\" Socket', 'Current', 'civilStore', 0),
(407, 'CM 29', '1½\" Tees', 'Current', 'civilStore', 0),
(408, 'CM 30', '½\" x 2\" Bush', 'Current', 'civilStore', 0),
(409, 'CM 31', '½\" Stop Cock', 'Current', 'civilStore', 0),
(410, 'CM 32', '½\" Ball Valve', 'Current', 'civilStore', 0),
(411, 'CM 33', '3/4 Bath Top', 'Current', 'civilStore', 0),
(412, 'CM 34', '1/2 Elbow', 'Current', 'civilStore', 0),
(413, 'CM 35', '1/2 Water Pipe Brass Top', 'Current', 'civilStore', 0),
(414, 'CM 36', 'W/E Cone Rubber Flushing', 'Current', 'civilStore', 0),
(415, 'CM 37', '1\" Non Return Valve', 'Current', 'civilStore', 0),
(416, 'CM 38', '1\" Union Connector', 'Current', 'civilStore', 0),
(417, 'CM 39', 'Nipplet', 'Current', 'civilStore', 0),
(418, 'CM 40', '2x3\" Paint Brush', 'Current', 'civilStore', 0),
(419, 'CM 41', '1\" Cut Vechi', 'Current', 'civilStore', 0),
(420, 'CM 42', '1/2\" Union Connector', 'Current', 'civilStore', 0),
(421, 'CM 43', '3/4\" Tees Copper Pressure', 'Current', 'civilStore', 0),
(422, 'CM 44', '2\" Tees', 'Current', 'civilStore', 0),
(423, 'CM 45', '1/2 Toilet Syphon', 'Current', 'civilStore', 0),
(424, 'CM 46', '3/4 Stop Cock', 'Current', 'civilStore', 0),
(425, 'CM 47', 'Nackle frame screw', 'Current', 'civilStore', 0),
(426, 'CM 48', 'Bags of White Cement', 'Current', 'civilStore', 0),
(427, 'CM 49', 'Poly Filter', 'Current', 'civilStore', 0),
(428, 'CM 50', '2\" Wire Nail Lag', 'Current', 'civilStore', 0),
(429, 'CM 51', '6x30\" Observe Rubber Glass', 'Current', 'civilStore', 0),
(430, 'CM 52', 'Roofing Nail', 'Current', 'civilStore', 0),
(431, 'CM 53', 'Blade Nacker Frame', 'Current', 'civilStore', 0),
(432, 'CM 54', 'Adaptiv', 'Current', 'civilStore', 0),
(433, 'CM 55', '16mm Square Rod', 'Current', 'civilStore', 0),
(434, 'CM 56', 'Measurement Tape', 'Current', 'civilStore', 0),
(435, 'CM 57', '2\" Plug', 'Current', 'civilStore', 0),
(436, 'CM 58', 'Tang it Gum', 'Current', 'civilStore', 0),
(437, 'CM 59', 'Global Padlock Big', 'Current', 'civilStore', 0),
(438, 'CM 60', 'White Chalks Durwyn Plant Plus', 'Current', 'civilStore', 0),
(439, 'CM 61', 'Complete W.C', 'Current', 'civilStore', 0),
(440, 'CM 62', 'N.W.T B Tape (10m)', 'Current', 'civilStore', 0),
(441, 'CM 63', 'White Emulsion', 'Current', 'civilStore', 0),
(442, 'CM 64', '2\" Hinges (Pairs)', 'Current', 'civilStore', 0),
(443, 'CM 65', '1/2 Kg Wire Nails', 'Current', 'civilStore', 0),
(444, 'CM 66', '2\" Gate pipe', 'Current', 'civilStore', 0),
(445, 'CM 67', 'Gallon of Block Glass', 'Current', 'civilStore', 0),
(446, 'CM 68', '1 x 3/4\" pressure Tee', 'Current', 'civilStore', 0),
(447, 'CM 69', '1 x 3/4\" pressure Elbow', 'Current', 'civilStore', 0),
(448, 'CM 70', 'Tinner', 'Current', 'civilStore', 0),
(449, 'CM 71', 'Evostic Gallon', 'Current', 'civilStore', 0),
(450, 'CM 72', 'Gluzet Lock', 'Current', 'civilStore', 0),
(451, 'CM 73', '½ Gate Valve', 'Current', 'civilStore', 0),
(452, 'CM 74', 'Drawer Handle', 'Current', 'civilStore', 0),
(453, 'CM 75', '4\" Wire Nail', 'Current', 'civilStore', 0),
(454, 'CM 76', 'Putty', 'Current', 'civilStore', 0),
(455, 'CM 77', '3\" Brass Bolt', 'Current', 'civilStore', 0),
(456, 'CM 78', 'Glass Gloss', 'Current', 'civilStore', 0),
(457, 'CM 79', '1½\" Bottle Trap', 'Current', 'civilStore', 0),
(458, 'CM 80', '2 x 10 wood screws', 'Current', 'civilStore', 0),
(459, 'CM 81', 'Iron files', 'Current', 'civilStore', 0),
(460, 'CM 82', 'Pacific Light Blue Emulsion', 'Current', 'civilStore', 0),
(461, 'CM 83', 'Rose Pacific Emulsion', 'Current', 'civilStore', 0),
(462, 'CM 84', '2 x 6 plain Glass', 'Current', 'civilStore', 0),
(463, 'CM 85', 'Dora Chain Glass', 'Current', 'civilStore', 0),
(464, 'CM 86', 'Brilliant Blue Glass', 'Current', 'civilStore', 0),
(465, 'CM 87', 'cream Gloss, Besser', 'Current', 'civilStore', 0),
(466, 'CM 88', '6 x 36 Obscure Glass', 'Current', 'civilStore', 0),
(467, 'CM 89', '24 x 6 Loure Glass Observe', 'Current', 'civilStore', 0),
(468, 'CM 90', 'P.V.C pipe 4\"', 'Current', 'civilStore', 0),
(469, 'CM 91', '1/2 plywood 18mm', 'Current', 'civilStore', 0),
(470, 'CM 92', 'Butt Hinges 12\"', 'Current', 'civilStore', 0),
(471, 'CM 93', 'Plywood', 'Current', 'civilStore', 0),
(472, 'CM 94', '1/4\" Socket', 'Current', 'civilStore', 0),
(473, 'CM 95', '1\" pressure pipe', 'Current', 'civilStore', 0),
(474, 'CM 96', '1/2 G.I. pipe', 'Current', 'civilStore', 0),
(475, 'CM 97', 'Double Door 50 x 75', 'Current', 'civilStore', 0),
(476, 'CM 98', '1½\" Union waste', 'Current', 'civilStore', 0),
(477, 'CM 99', 'Hack Saw Blade', 'Current', 'civilStore', 0),
(478, 'CM 100', '3\" Awon Nail', 'Current', 'civilStore', 0),
(479, 'CM 101', 'Cream Emulsion', 'Current', 'civilStore', 0),
(480, 'CM 102', 'W.C. flushing Cistern Tangle', 'Current', 'civilStore', 0),
(481, 'CM 103', 'Pcidio Cement', 'Current', 'civilStore', 0),
(482, 'CM 104', '3/4 x 1/2 Reduction Bush', 'Current', 'civilStore', 0),
(483, 'CM 105', '24 x 36 plain Glass', 'Current', 'civilStore', 0),
(484, 'CM 106', '18 x 9 x 9 floor Tile Grey', 'Current', 'civilStore', 0),
(485, 'CM 107', 'Roofing felt', 'Current', 'civilStore', 0),
(486, 'CM 108', '4 x 4 Asbestos sheet', 'Current', 'civilStore', 0),
(487, 'CM 109', 'Nacca frame 10 Blade', 'Current', 'civilStore', 0),
(488, 'CM 110', 'Red Oxide Gloss', 'Current', 'civilStore', 0),
(489, 'CM 111', '3/4 G.I. pipe', 'Current', 'civilStore', 0),
(490, 'CM 112', '1/2 Socket', 'Current', 'civilStore', 0),
(491, 'CM 113', 'Weld Mash', 'Current', 'civilStore', 0),
(492, 'CM 114', '2 1/2 Hinges', 'Current', 'civilStore', 0),
(493, 'CM 115', '3/4 x 4 Screw', 'Current', 'civilStore', 0),
(494, 'CM 116', '4 ft Wire Netting Aluminium', 'Current', 'civilStore', 0),
(495, 'CM 117', 'Wire Nail 1\"', 'Current', 'civilStore', 0),
(496, 'CM 118', 'Table Vice 4\"', 'Current', 'civilStore', 0),
(497, 'CM 119', '5\" Wire Nail', 'Current', 'civilStore', 0),
(498, 'CM 120', 'White Formica', 'Current', 'civilStore', 0),
(499, 'CM 121', '3/4 Plywood', 'Current', 'civilStore', 0),
(500, 'CM 122', 'Soft Sand', 'Current', 'civilStore', 0),
(501, 'CM 123', '18 x 9 x 9 Floor Tiles Red Colour', 'Current', 'civilStore', 0),
(502, 'CM 124', '3 Blade Louvar Frame', 'Current', 'civilStore', 0),
(503, 'CM 125', 'Sink WHB stopper with Chain', 'Current', 'civilStore', 0),
(504, 'CM 126', 'Leaves Green Emulsion', 'Current', 'civilStore', 0),
(505, 'CM 127', 'Rose Pink Gloss', 'Current', 'civilStore', 0),
(506, 'CM 128', '2\" Elbow', 'Current', 'civilStore', 0),
(507, 'CM 129', '2\" Ciste Valve', 'Current', 'civilStore', 0),
(508, 'CM 130', '2\" Non Return Valve', 'Current', 'civilStore', 0),
(509, 'CM 131', '1\" Galve Pipe', 'Current', 'civilStore', 0),
(510, 'CM 132', '¼ Rod', 'Current', 'civilStore', 0),
(511, 'CM 133', 'Hose Clip, 3½', 'Current', 'civilStore', 0),
(512, 'CM 134', 'Bolt & Nut Shack & Disters', 'Current', 'civilStore', 0),
(513, 'CM 135', 'Hacksaw', 'Current', 'civilStore', 0),
(514, 'CM 136', '2000 Liters Crepe water paint', 'Current', 'civilStore', 0),
(515, 'CM 137', 'Black Paint Emulsion', 'Current', 'civilStore', 0),
(516, 'CM 138', 'Rubber Hose', 'Current', 'civilStore', 0),
(517, 'CM 139', '2\" Union Connector', 'Current', 'civilStore', 0),
(518, 'CM 140', '1\" Tee Copper', 'Current', 'civilStore', 0),
(519, 'CM 141', '1\"¾ Elbow', 'Current', 'civilStore', 0),
(520, 'CM 142', '1 x ¾\" Tee', 'Current', 'civilStore', 0),
(521, 'CM 143', '¾ Elbow', 'Current', 'civilStore', 0),
(522, 'CM 144', '1½\" Union Waste', 'Current', 'civilStore', 0),
(523, 'CM 145', '½ Tee', 'Current', 'civilStore', 0),
(524, 'CM 146', 'Shovel', 'Current', 'civilStore', 0),
(525, 'CM 147', 'Hope & Staple', 'Current', 'civilStore', 0),
(526, 'CM 148', 'Trade Nail', 'Current', 'civilStore', 0),
(527, 'CM 149', 'Panel Door (83x33)', 'Current', 'civilStore', 0),
(528, 'CM 150', 'Small Padlock', 'Current', 'civilStore', 0),
(529, 'CM 151', 'Sandpaper', 'Current', 'civilStore', 0),
(530, 'CM 152', '½\" Wire Nail', 'Current', 'civilStore', 0),
(531, 'CM 153', '1\" Wire Nail', 'Current', 'civilStore', 0),
(532, 'CM 154', '2\" PVC Tee', 'Current', 'civilStore', 0),
(533, 'CM 155', '8\" Blender Nacro Frames', 'Current', 'civilStore', 0),
(534, 'CM 156', '½ PVC Tee', 'Current', 'civilStore', 0),
(535, 'CM 157', '4\" Bracket', 'Current', 'civilStore', 0),
(536, 'CM 158', '½\" Brass Plug', 'Current', 'civilStore', 0),
(537, 'CM 159', '2\" Apple', 'Current', 'civilStore', 0),
(538, 'CM 160', 'W.C. Seat with Cover', 'Current', 'civilStore', 0),
(539, 'CM 161', '1½x1¼ Reducing Bush', 'Current', 'civilStore', 0),
(540, 'CM 162', '¼\" Union Connector', 'Current', 'civilStore', 0),
(541, 'CM 163', '1¼\" Tee PVC Plastic', 'Current', 'civilStore', 0),
(542, 'CM 164', '1¼\" Bend PVC', 'Current', 'civilStore', 0),
(543, 'CM 165', '4\" Bolt (Pos)', 'Current', 'civilStore', 0),
(544, 'CM 166', 'Tower Bolt 6\"', 'Current', 'civilStore', 0),
(545, 'CM 167', 'Black - Pogonium Leather', 'Current', 'civilStore', 0),
(546, 'CM 168', '3\" Bolt & Nuts', 'Current', 'civilStore', 0),
(547, 'CM 169', '¼\" Glow', 'Current', 'civilStore', 0),
(548, 'CM 170', 'Chocolate Gloss', 'Current', 'civilStore', 0),
(549, 'CM 171', '½\" Shower Rose', 'Current', 'civilStore', 0),
(550, 'CM 172', '½\" Butt Hinges', 'Current', 'civilStore', 0),
(551, 'CM 173', '1\" Ball Valve', 'Current', 'civilStore', 0),
(552, 'CM 174', '1\" Socket', 'Current', 'civilStore', 0),
(553, 'CM 175', '1 x ¾\" Bushing', 'Current', 'civilStore', 0),
(554, 'CM 176', '1½\" PVC Pipe', 'Current', 'civilStore', 0),
(555, 'CM 177', 'Balloon Wire Vent Cap', 'Current', 'civilStore', 0),
(556, 'CM 178', '1\" Foot Valve', 'Current', 'civilStore', 0),
(557, 'CM 179', '1½\"x1\" Bushing', 'Current', 'civilStore', 0),
(558, 'CM 180', '½\" Stop Cock', 'Current', 'civilStore', 0),
(559, 'CM 181', 'Grinding Disc', 'Current', 'civilStore', 0),
(560, 'CM 182', '18\" Gai Sheets', 'Current', 'civilStore', 0),
(561, 'CM 183', 'Hinges 3\"', 'Current', 'civilStore', 0),
(562, 'CM 184', 'Op Cement', 'Current', 'civilStore', 0),
(563, 'CM 185', '2x2x12 Omni Plant', 'Current', 'civilStore', 0),
(564, 'CM 186', 'Tie Rod: ½x2x2', 'Current', 'civilStore', 0),
(565, 'CM 187', '2x6x12 Planed Mahogany', 'Current', 'civilStore', 0),
(566, 'CM 188', '1½x12 Mahogany Plant', 'Current', 'civilStore', 0),
(567, 'CM 189', 'Path Rock', 'Current', 'civilStore', 0),
(568, 'CM 190', '¾\" Round Clips Dozen', 'Current', 'civilStore', 0),
(569, 'CM 191', 'Iron Square', 'Current', 'civilStore', 0),
(570, 'CM 192', '2x3x12 Mahogany', 'Current', 'civilStore', 0),
(571, 'CM 193', '193  3x4x12 Idlyb0', 'Current', 'civilStore', 0),
(572, 'CM 194', 'CM 194  Binding Wire (Reel)  ', 'Current', 'civilStore', 0),
(573, 'CM 195', 'CM 195  Orbit Water, Tanks 1000 Litres  ', 'Current', 'civilStore', 0),
(574, 'CM 196', 'CM 196  2\" x 4\" x 12\" Iroko Plan  ', 'Current', 'civilStore', 0),
(575, 'CM 197', 'CM 197  ½\" PVC Bend  ', 'Current', 'civilStore', 0),
(576, 'CM 198', 'CM 198  Sharp Saw  ', 'Current', 'civilStore', 0),
(577, 'CM 199', 'CM 199  ¾\" PVC Pipe  ', 'Current', 'civilStore', 0),
(578, 'CM 200', 'CM 200  9\" x 9\" x 18\" Block  ', 'Current', 'civilStore', 0),
(579, 'CM 201', 'CM 201  ½ x 12 x 12  Omo Plain  ', 'Current', 'civilStore', 0),
(580, 'CM 202', 'CM 202  1\" x 3/4\" Red Socket  ', 'Current', 'civilStore', 0),
(581, 'CM 203', 'CM 203  1½\" Non Return Valve  ', 'Current', 'civilStore', 0),
(582, 'CM 204', 'CM 204  Grout Washers  ', 'Current', 'civilStore', 0),
(583, 'CM 205', 'CM 205  ¾ x ½ Socket', 'Current', 'civilStore', 0),
(584, 'CM 206', 'CM 206  Hand Set  ', 'Current', 'civilStore', 0),
(585, 'CM 207', 'CM 207  6\" x 9\" x 18\" Gasket Block  ', 'Current', 'civilStore', 0),
(586, 'CM 208', 'CM 208  2\" x ¼\" Flat Bar  ', 'Current', 'civilStore', 0),
(587, 'CM 209', 'CM 209  Tower Bolt 5\"  ', 'Current', 'civilStore', 0),
(588, 'CM 210', 'CM 210  1¼\" x 2\" Tee  ', 'Current', 'civilStore', 0),
(589, 'CM 211', 'CM 211  1\" x 1\" Elbow  ', 'Current', 'civilStore', 0),
(590, 'CM 212', 'CM 212  1\" x 1\" Pins  ', 'Current', 'civilStore', 0),
(591, 'CM 213', 'CM 213  Small Hoop and Staple  ', 'Current', 'civilStore', 0),
(592, 'CM 214', 'CM 214  2\" PVC Bend  ', 'Current', 'civilStore', 0),
(593, 'CM 215', 'CM 215  1½\" Socket  ', 'Current', 'civilStore', 0),
(594, 'CM 216', 'CM 216  Butumen (Drums)', 'Current', 'civilStore', 0),
(595, 'CM 217', 'CM 217  Crey Emulsion', 'Current', 'civilStore', 0),
(596, 'CM 218', 'CM 218  2½\" Wire Nail  ', 'Current', 'civilStore', 0),
(597, 'CM 219', 'CM 219  Complete W.C. Flow Mistre  ', 'Current', 'civilStore', 0),
(598, 'CM 220', 'CM 220  2\" Bracket  ', 'Current', 'civilStore', 0),
(599, 'CM 221', 'CM 221  ½\" Flexible conector', 'Current', 'civilStore', 0),
(600, 'CM 222', 'CM 222  Sink Union  Waste  ', 'Current', 'civilStore', 0),
(601, 'CM 223', 'CM 223  3½\" Chicken Nut  ', 'Current', 'civilStore', 0),
(602, 'CM 224', 'CM 224  Male and Female Pressure Socket  ', 'Current', 'civilStore', 0),
(603, 'CM 225', 'CM 225  ½\" Air Valve', 'Current', 'civilStore', 0),
(604, 'CM 226', 'CM 226  Adapter  ', 'Current', 'civilStore', 0),
(605, 'CM 227', 'CM 227  ¾\" pressure pipe  ', 'Current', 'civilStore', 0),
(606, 'CM 228', 'CM 228  ¾  pressure socket  ', 'Current', 'civilStore', 0),
(607, 'CM 229', 'CM 229  1 ½\" pressure adapter  ', 'Current', 'civilStore', 0),
(608, 'CM 230', 'CM 230 ¼ \" pressure socket  ', 'Current', 'civilStore', 0),
(609, 'CM 231', 'CM 231  1\" x ¾\" Red Socket  ', 'Current', 'civilStore', 0),
(610, 'CM 232', 'CM 232   1 Pressure Tee  ', 'Current', 'civilStore', 0),
(611, 'CM 233', 'CM 233  1½ x 2 Tiger Bush  ', 'Current', 'civilStore', 0),
(612, 'CM 234', 'CM 234  1½\" Tiger Iron Connector Threaded  ', 'Current', 'civilStore', 0),
(613, 'CM 235', 'CM 235  1½\" Tiger Socket Threaded  ', 'Current', 'civilStore', 0),
(614, 'CM 236', 'CM 236  ¾\" pressure Air Valve  ', 'Current', 'civilStore', 0),
(615, 'CM 237', 'CM 237  ½ Bracket  ', 'Current', 'civilStore', 0),
(616, 'CM 238', 'CM 238  1\" x ¾\" Reel Bush (Elbow)  ', 'Current', 'civilStore', 0),
(617, 'CM 239', 'CM 239  1\" Iron Clip (Original)  ', 'Current', 'civilStore', 0),
(618, 'CM 240', 'CM 240  Air Valve pressure  ', 'Current', 'civilStore', 0),
(619, 'CM 241', 'CM 241  ¾\" G.I. socket  ', 'Current', 'civilStore', 0),
(620, 'CM 242', 'CM 242  12mm Rod  ', 'Current', 'civilStore', 0),
(621, 'CM 243', 'CM 243  1\" Angel Iron  ', 'Current', 'civilStore', 0),
(622, 'CM 244', 'CM 244  Solgihim Original  ', 'Current', 'civilStore', 0),
(623, 'CM 245', 'CM 245  Line Yard  ', 'Current', 'civilStore', 0),
(624, 'CM 246', 'CM 246  Top Bond Glue  ', 'Current', 'civilStore', 0),
(625, 'CM 247', 'CM 247  Complete Ash Hand Basin  ', 'Current', 'civilStore', 0),
(626, 'CM 248', 'CM 248  2 x 1 Square pipe Nickle  ', 'Current', 'civilStore', 0),
(627, 'CM 249', 'CM 249  1 x 1 Square pipe Nickle  ', 'Current', 'civilStore', 0),
(628, 'CM 250', 'CM 250  Long Span Aluminum sheet  ', 'Current', 'civilStore', 0),
(629, 'CM 251', 'CM 251  4\" pan Connector (long type)  ', 'Current', 'civilStore', 0),
(630, 'CM 252', 'CM 252  2\" flat bar  ', 'Current', 'civilStore', 0),
(631, 'CM 253', 'CM 253  1\" flat bar  ', 'Current', 'civilStore', 0),
(632, 'CM 254', 'CM 254  Hard Core Stone  ', 'Current', 'civilStore', 0),
(633, 'CM 255', 'CM 255  Wall Belt  ', 'Current', 'civilStore', 0),
(634, 'CM 256', 'CM 256  Latrite', 'Current', 'civilStore', 0),
(635, 'CM 257', 'CM 258  Chocolate Emulsion  ', 'Current', 'civilStore', 0),
(636, 'CM 258', 'CM 259  6\" PVC pipe (long)  ', 'Current', 'civilStore', 0),
(637, 'CM 259', 'CM 260  3\" PVC pipe (long)  ', 'Current', 'civilStore', 0),
(638, 'CM 260', 'CM 261  ½ Pressure Air Valve  ', 'Current', 'civilStore', 0),
(639, 'CM 261', 'CM 262  ½ m/f Elbow  ', 'Current', 'civilStore', 0),
(640, 'CM 262', 'CM 263  1\" Tiger Back Nuts Short  ', 'Current', 'civilStore', 0),
(641, 'CM 263', 'CM 264  5000 Lts Storage Tank  ', 'Current', 'civilStore', 0),
(642, 'CM 264', 'CM 265  1% Inch Black pipe  ', 'Current', 'civilStore', 0),
(643, 'CM 265', 'CM 266  2½\" Gate Valve  ', 'Current', 'civilStore', 0),
(644, 'CM 266', 'CM 267  1,500 Lts water Tank  ', 'Current', 'civilStore', 0),
(645, 'CM 267', 'CM 268  4,000 Lts plastic storage Tank  ', 'Current', 'civilStore', 0),
(646, 'CM 268', 'CM 269  4\"  Bolt', 'Current', 'civilStore', 0),
(647, 'CM 269', 'CM 270  Towel Stainless Steel  ', 'Current', 'civilStore', 0),
(648, 'CM 270', 'CM 271  1¼\" Gate Valve  ', 'Current', 'civilStore', 0),
(649, 'CM 271', 'CM 272  1¼\" Nipple  ', 'Current', 'civilStore', 0),
(650, 'CM 272', 'CM 273  2\"x2\" Square pipe Thick  ', 'Current', 'civilStore', 0),
(651, 'CM 273', 'CM 274  1½ x 1½ Square pipe Thick  ', 'Current', 'civilStore', 0),
(652, 'CM 274', 'CM 275  16mm M-S Rod  ', 'Current', 'civilStore', 0),
(653, 'CM 275', 'CM 276  W.C. Seat  ', 'Current', 'civilStore', 0),
(654, 'CM 276', 'CM 277  Tile Red Emulsion  ', 'Current', 'civilStore', 0),
(655, 'CM 277', 'CM 278  ¼\" Non Return Valve  ', 'Current', 'civilStore', 0),
(656, 'CM 278', 'CM 279  W.C. Seat Cover  ', 'Current', 'civilStore', 0),
(657, 'CM 279', 'CM 280  Tornado Nail  ', 'Current', 'civilStore', 0),
(658, 'CM 280', 'CM 281  ¼\" screw  ', 'Current', 'civilStore', 0),
(659, 'CM 281', 'CM 282  Big Tread Tape  ', 'Current', 'civilStore', 0),
(660, 'CM 282', 'CM 283  Iron Roofing Sheet  ', 'Current', 'civilStore', 0),
(661, 'CM 283', 'CM 284  Cutlass', 'Current', 'civilStore', 0),
(662, 'CM 284', 'CM 285    3\"x1½ Sq. pipe  ', 'Current', 'civilStore', 0),
(663, 'CM 285', 'CM 286  Plastic Hammer  ', 'Current', 'civilStore', 0),
(664, 'CM 286', 'CM 287  Pvc Ceiling  ', 'Current', 'civilStore', 0),
(665, 'CM 287', 'CM 288  50mm Galvanize pipe  ', 'Current', 'civilStore', 0),
(666, 'CM 288', 'CM 289  Door frame', 'Current', 'civilStore', 0),
(667, 'CM 289', 'CM 290  Fenced Wire  ', 'Current', 'civilStore', 0),
(668, 'CM 290', 'CM 291  3.5mm Bit  ', 'Current', 'civilStore', 0),
(669, 'CM 291', 'CM 292  4mm Bit  ', 'Current', 'civilStore', 0),
(670, 'CM 292', 'CM 293  5mm Bit  ', 'Current', 'civilStore', 0),
(671, 'CM 293', 'CM 294  higher Door Handle  ', 'Current', 'civilStore', 0),
(672, 'CM 294', 'CM 295  9.9 Aluminum Cream Tower  ', 'Current', 'civilStore', 0),
(673, 'CM 295', 'CM 296  9.5 Aluminum Cream Tower  ', 'Current', 'civilStore', 0),
(674, 'CM 296', 'CM 297  11227 Bead Aluminum cream Tower  Flat Hinges  ', 'Current', 'civilStore', 0),
(675, 'CM 297', 'CM 298  Flat Hurges', 'Current', 'civilStore', 0),
(676, 'CM 298', 'CM 299  0.6 Rubber', 'Current', 'civilStore', 0),
(677, 'CM 299', 'CM 300  0.6  Blue Reflection Glass', 'Current', 'civilStore', 0),
(678, 'CM 300', 'CM 301  5mm/7mm Screw  ', 'Current', 'civilStore', 0),
(679, 'CM 301', 'CM 302  Drilling Glass Bit  ', 'Current', 'civilStore', 0),
(680, 'CM 302', 'CM 303  Main Runner  ', 'Current', 'civilStore', 0),
(681, 'CM 303', 'CM 304  Suspension Wire  ', 'Current', 'civilStore', 0),
(682, 'CM 304', 'CM 305  2 Ft Circle  ', 'Current', 'civilStore', 0),
(683, 'CM 305', 'CM 306  Wall Angels  ', 'Current', 'civilStore', 0),
(684, 'CM 306', 'CM 307  Aluminum Tower (Cream)  ', 'Current', 'civilStore', 0),
(685, 'CM 307', 'CM 308  Aluminum Tower (Vanilla)  ', 'Current', 'civilStore', 0),
(686, 'CM 308', 'CM 309  Latch Key  ', 'Current', 'civilStore', 0),
(687, 'CM 309', 'CM 310  Unico Board  ', 'Current', 'civilStore', 0),
(688, 'CM 310', 'CM 311  Roller  ', 'Current', 'civilStore', 0),
(689, 'CM 311', 'CM 312  Installation Clip  ', 'Current', 'civilStore', 0),
(690, 'CM 312', 'CM 313  45x90 Angle  ', 'Current', 'civilStore', 0),
(691, 'CM 313', 'CM 314  90x90 Angle  ', 'Current', 'civilStore', 0),
(692, 'CM 314', 'CM 315  Floor Drain  ', 'Current', 'civilStore', 0),
(693, 'CM 315', 'CM 316  Aluminum Class  ', 'Current', 'civilStore', 0),
(694, 'CM 316', 'CM 317  Paint Brush/Roller Various Sizes  ', 'Current', 'civilStore', 0),
(695, 'CM 317', 'CM 318  ½ Pressure Pipe  ', 'Current', 'civilStore', 0),
(696, 'CM 318', 'CM 319  Silicon Sealant  ', 'Current', 'civilStore', 0),
(697, 'CM 319', 'CM 310  U-Channel  ', 'Current', 'civilStore', 0),
(698, 'CM 320', 'CM 320  Hegle Connector  ', 'Current', 'civilStore', 0),
(699, 'CM 321', 'CM 321  Cylinder Door Lock', 'Current', 'civilStore', 0),
(700, 'CM 322', 'Cm 323  4X8x2mm Sheet Metal  ', 'Current', 'civilStore', 0),
(701, 'CM 323', 'Cm 324  Rivet Pin  ', 'Current', 'civilStore', 0),
(702, 'CM 324', 'Cm 325  Boko Avam Net  ', 'Current', 'civilStore', 0),
(703, 'CM 325', 'Cm 326  Screw Machine  ', 'Current', 'civilStore', 0),
(704, 'CM 326', 'Cm 327  Car Paint', 'Current', 'civilStore', 0),
(705, 'VWB 1', ' Bolt and Nuts and 12\" Bolt and Nuts  ', 'Current', 'electricalStore', 0),
(706, 'VWB 2', ' 17\" Bolt and Nuts  ', 'Current', 'electricalStore', 0),
(707, 'VWB 3', ' 14\" Bolt and Nuts    ', 'Current', 'electricalStore', 0),
(708, 'MT1 ', '  Tyre  700 X 16  ', 'Current', 'electricalStore', 0),
(709, 'MT2', 'Tyre  750 X 16  ', 'Current', 'electricalStore', 0),
(710, 'MT3', 'Tyre  195/65 R 15  ', 'Current', 'electricalStore', 0),
(711, 'MT4', 'Tyre  195 X 15  ', 'Current', 'electricalStore', 0),
(712, 'MT5', 'Tyre  Ream  900 X 20  ', 'Current', 'electricalStore', 0),
(713, 'MT6', 'Tyre  255 X 70 X 15 R  ', 'Current', 'electricalStore', 0),
(714, 'MT7', 'Tyre  205 X 16  ', 'Current', 'electricalStore', 0),
(715, 'MT8', 'Tyre  10.00/20  ', 'Current', 'electricalStore', 0),
(716, 'TC1', 'Timing Chain Cover', 'Current', 'electricalStore', 0),
(717, 'TC2', 'Clutch Disc N/ model R22', 'Current', 'electricalStore', 0),
(718, 'TC3', 'Clutch Pedal old model', 'Current', 'electricalStore', 0),
(719, 'TC4', 'Kick Starter', 'Current', 'electricalStore', 0),
(720, 'TC5', 'Oil Filter', 'Current', 'electricalStore', 0),
(721, 'TC6', 'Release Bearing (Clutch)', 'Current', 'electricalStore', 0),
(722, 'TC7', 'Oil Pump', 'Current', 'electricalStore', 0),
(723, 'TC8', 'Water Radiator', 'Current', 'electricalStore', 0),
(724, 'TC9', 'Clutch Release Bearing 0lm', 'Current', 'electricalStore', 0),
(725, 'TC10', 'Rotor', 'Current', 'electricalStore', 0),
(726, 'TC11', 'Inlet Exhaust Valves 0lm', 'Current', 'electricalStore', 0),
(727, 'TC12', 'Timing Chain Adjuster', 'Current', 'electricalStore', 0),
(728, 'TC13', 'Fuel Pump! model 32100-24031', 'Current', 'electricalStore', 0),
(729, 'TC14', 'Inlet Exhaust Valve N/mSet', 'Current', 'electricalStore', 0),
(730, 'TC15', 'Complete Kick Starter', 'Current', 'electricalStore', 0),
(731, 'TC16', 'Clutch Disc N/m', 'Current', 'electricalStore', 0),
(732, 'TC17', 'Complete Gasket', 'Current', 'electricalStore', 0),
(733, 'TC18', 'Rear Shock Absorber', 'Current', 'electricalStore', 0),
(734, 'TC19', 'Piston Ring 0.10', 'Current', 'electricalStore', 0),
(735, 'TC20', 'Clutch Plate Disc Set', 'Current', 'electricalStore', 0),
(736, 'TC21', 'Clutch Repair Kit uppers/lower', 'Current', 'electricalStore', 0),
(737, 'TC22', 'Servo Brake R02 Coaster', 'Current', 'electricalStore', 0),
(738, 'TC23', 'Front Wheel Bearing', 'Current', 'electricalStore', 0),
(739, 'TC24', 'Clutch Release Bearing N/m', 'Current', 'electricalStore', 0),
(740, 'TC25', 'Cylinder Head Gasket', 'Current', 'electricalStore', 0),
(741, 'TC26', 'Brake Lining Rear', 'Current', 'electricalStore', 0),
(742, 'TC27', 'Gear Lower Bearing', 'Current', 'electricalStore', 0),
(743, 'TC28', 'Complete Master Brake', 'Current', 'electricalStore', 0),
(744, 'TC29', 'Brake Lining Front', 'Current', 'electricalStore', 0),
(745, 'TC30', 'Fan Bolt', 'Current', 'electricalStore', 0),
(746, 'TC31', 'Complete Ignition with Distribution', 'Current', 'electricalStore', 0),
(747, 'TC32', 'Complete Carburetor', 'Current', 'electricalStore', 0),
(748, 'TC33', 'Rain Guard (0.20 Set)', 'Current', 'electricalStore', 0),
(749, 'TC34', 'Valve Oil Seal', 'Current', 'electricalStore', 0),
(750, 'TC35', 'Engine Seat', 'Current', 'electricalStore', 0),
(751, 'TC36', 'Gear Seat', 'Current', 'electricalStore', 0),
(752, 'TC37', 'Wheel Spanner', 'Current', 'electricalStore', 0),
(753, 'TC38', 'Distribution Wire', 'Current', 'electricalStore', 0),
(754, 'TC39', 'Throttle Cable', 'Current', 'electricalStore', 0),
(755, 'TC40', 'Shock Absorber Rubber', 'Current', 'electricalStore', 0),
(756, 'TC41', 'Stabilizer Rubber', 'Current', 'electricalStore', 0),
(757, 'TC42', 'Complete Set Tie Rod and Repair Kit', 'Current', 'electricalStore', 0),
(758, 'TC43', 'Thrust Washer', 'Current', 'electricalStore', 0),
(759, 'TC44', 'Timing Chain and Adjuster', 'Current', 'electricalStore', 0),
(760, 'TC45', 'Timing Chain Damper', 'Current', 'electricalStore', 0),
(761, 'TC46', 'Brake Hose Pipe', 'Current', 'electricalStore', 0),
(762, 'TC47', 'Packing Ring', 'Current', 'electricalStore', 0),
(763, 'TC48', 'Front Kicking Pin', 'Current', 'electricalStore', 0),
(764, 'TC49', 'Rear Wheel Bearing', 'Current', 'electricalStore', 0),
(765, 'TC50', 'Speedometer Driving Gear', 'Current', 'electricalStore', 0),
(766, 'TC51', 'Propeller Universal Joint', 'Current', 'electricalStore', 0),
(767, 'TC52', 'Contact Set (etc)', 'Current', 'electricalStore', 0),
(768, 'TC53', 'Rear Brake Rubber Cap', 'Current', 'electricalStore', 0),
(769, 'TC54', 'Pistons 0.20', 'Current', 'electricalStore', 0),
(770, 'TC55', 'Brake Pad Toyota Carina', 'Current', 'electricalStore', 0),
(771, 'TC56', 'Water Pump', 'Current', 'electricalStore', 0),
(772, 'TC57', 'Front Shock Absorber Nissan', 'Current', 'electricalStore', 0),
(773, 'TC58', 'Rear Shock Absorber Nissan', 'Current', 'electricalStore', 0),
(774, 'TC59', 'Complete Tie Rod', 'Current', 'electricalStore', 0),
(775, 'TC60', 'Power Steering Engine', 'Current', 'electricalStore', 0),
(776, 'TC61', 'stablizer bushing', 'Current', 'electricalStore', 0),
(777, 'TC62', 'Gear Seat', 'Current', 'electricalStore', 0),
(778, 'TC63', 'Air Filter Coradia N/M', 'Current', 'electricalStore', 0);
INSERT INTO `allitems` (`id`, `itemcode`, `itemname`, `category`, `storesection`, `quantity`) VALUES
(779, 'TC64', 'Rear Hub with Bearing', 'Current', 'electricalStore', 0),
(780, 'TC65', 'Complete Wheel Shaft', 'Current', 'electricalStore', 0),
(781, 'TC66', 'Dust Rubber', 'Current', 'electricalStore', 0),
(782, 'TC67', 'Gear Box', 'Current', 'electricalStore', 0),
(783, 'TC68', 'Complete Tokumbo Engine', 'Current', 'electricalStore', 0),
(784, 'TC69', 'Clutch Cable', 'Current', 'electricalStore', 0),
(785, 'TC70', 'Lower Arms (Sienna) L/R', 'Current', 'electricalStore', 0),
(786, 'TC71', 'Ball Joint', 'Current', 'electricalStore', 0),
(787, 'TC72', 'Front Shock Absorber Pad', 'Current', 'electricalStore', 0),
(788, 'TC73', 'Rear Shock Absorber Pad', 'Current', 'electricalStore', 0),
(789, 'TC74', 'Stabilizer Arm Bushing', 'Current', 'electricalStore', 0),
(790, 'TC75', 'Steering Rack with Tie Rod', 'Current', 'electricalStore', 0),
(791, 'TC76', 'Steering Power Hose', 'Current', 'electricalStore', 0),
(792, 'TC77', 'Wheel Chaft', 'Current', 'electricalStore', 0),
(793, 'TC78', 'Front Linkages', 'Current', 'electricalStore', 0),
(794, 'TC79', 'Nozzle', 'Current', 'electricalStore', 0),
(795, 'TC80', 'Upper Arm Left/Right', 'Current', 'electricalStore', 0),
(796, 'TC81', 'Complete Upper Arm L/R', 'Current', 'electricalStore', 0),
(797, 'TC82', 'front right protector', 'Current', 'electricalStore', 0),
(798, 'TC83', 'Brake Pot', 'Current', 'electricalStore', 0),
(799, 'TC84', 'Clutch Finger', 'Current', 'electricalStore', 0),
(800, 'TC85', 'Electric Steering Knuckle', 'Current', 'electricalStore', 0),
(801, 'TC86', 'Fan Blade', 'Current', 'electricalStore', 0),
(802, 'TC87', 'Radiator Fan', 'Current', 'electricalStore', 0),
(803, 'TH1', 'Gear Teeth', 'Current', 'electricalStore', 0),
(804, 'TH2', 'The Rod', 'Current', 'electricalStore', 0),
(805, 'TH3', 'Shock Absorber (front)', 'Current', 'electricalStore', 0),
(806, 'TH4', 'Brake Shoe', 'Current', 'electricalStore', 0),
(807, 'TH5', 'Side Mirror', 'Current', 'electricalStore', 0),
(808, 'TH6', 'Siren &', 'Current', 'electricalStore', 0),
(809, 'TH7', 'Cylinder Head Gasket', 'Current', 'electricalStore', 0),
(810, 'TH8', 'Oil Pump', 'Current', 'electricalStore', 0),
(811, 'TH9', 'Pressing Horn', 'Current', 'electricalStore', 0),
(812, 'TH10', 'Water Pump', 'Current', 'electricalStore', 0),
(813, 'TH11', 'Brake Limp (rear)', 'Current', 'electricalStore', 0),
(814, 'TH12', 'Complete Master Brake', 'Current', 'electricalStore', 0),
(815, 'TH13', 'Ignition Coil', 'Current', 'electricalStore', 0),
(816, 'R/A1', 'Copper Tubes 3/8', 'Current', 'electricalStore', 0),
(817, 'R/A2', 'Flowing Semi Brass', 'Current', 'electricalStore', 0),
(818, 'R/A3', 'Compound Gauge', 'Current', 'electricalStore', 0),
(819, 'R/A4', 'Silpos Rod', 'Current', 'electricalStore', 0),
(820, 'R/A5', 'Condenser Gauge', 'Current', 'electricalStore', 0),
(821, 'R/A6', 'Brass Rod', 'Current', 'electricalStore', 0),
(822, 'R/A7', '22 Refrigerant 13.6kg', 'Current', 'electricalStore', 0),
(823, 'R/A8', 'Flow Powder', 'Current', 'electricalStore', 0),
(824, 'R/A9', 'Reset Switch Voltage', 'Current', 'electricalStore', 0),
(825, 'R/A10', 'Running Capacitor', 'Current', 'electricalStore', 0),
(826, 'R/A11', 'Time Relay Delay', 'Current', 'electricalStore', 0),
(827, 'R/A12', 'Regulator', 'Current', 'electricalStore', 0),
(828, 'R/A13', 'RT Timmy Thermostat', 'Current', 'electricalStore', 0),
(829, 'R/A14', 'Condenser Fan Motor 27131', 'Current', 'electricalStore', 0),
(830, 'R/A15', '1/4 Copper Tube Pipe', 'Current', 'electricalStore', 0),
(831, 'R/A16', '2HP Compressor', 'Current', 'electricalStore', 0),
(832, 'R/A17', 'Thermocool T.300', 'Current', 'electricalStore', 0),
(833, 'R/A18', 'Deep Freezer', 'Current', 'electricalStore', 0),
(834, 'R/A19', '115 Compressor (Mech)', 'Current', 'electricalStore', 0),
(835, 'R/A20', 'Condenser Fan Motor 99-22.0', 'Current', 'electricalStore', 0),
(836, 'R/A21', 'Capacitor Tubes', 'Current', 'electricalStore', 0),
(837, 'R/A22', 'Rivet Pin', 'Current', 'electricalStore', 0),
(838, 'R/A23', 'Stabilizer', 'Current', 'electricalStore', 0),
(839, 'R/A24', '2HP Window Type Air Conditioner', 'Current', 'electricalStore', 0),
(840, 'R/A25', 'Tube Cutter Blade', 'Current', 'electricalStore', 0),
(841, 'R/A26', 'Discharge Hose', 'Current', 'electricalStore', 0),
(842, 'R/A27', 'Compressor Starting Capacitor', 'Current', 'electricalStore', 0),
(843, 'R/A28', '2HP Split Air Conditioner (New Clime)', 'Current', 'electricalStore', 0),
(844, 'R/A29', '1 1/2HP Air Conditioner Carrier', 'Current', 'electricalStore', 0),
(845, 'R/A30', 'Heater for 1HP Evaporator', 'Current', 'electricalStore', 0),
(846, 'R/A31', 'Refrigerant Driers', 'Current', 'electricalStore', 0),
(847, 'R/A32', '16HP Folded Evaporator', 'Current', 'electricalStore', 0),
(848, 'R/A33', '1HP Midea Air Conditioner', 'Current', 'electricalStore', 0),
(849, 'R/A34', 'Storm Relay', 'Current', 'electricalStore', 0),
(850, 'R/A35', 'Charging Valves', 'Current', 'electricalStore', 0),
(851, 'R/A36', 'Cooler Fan Motor', 'Current', 'electricalStore', 0),
(852, 'R/A37', 'Duck Tape', 'Current', 'electricalStore', 0),
(853, 'R/A38', 'Running Capacitor', 'Current', 'electricalStore', 0),
(854, 'R/A39', 'Gas Cooker', 'Current', 'electricalStore', 0),
(855, 'R/A40', '1/2 Compressor', 'Current', 'electricalStore', 0),
(856, 'R/A41', '13.6kg R-12 Gas Capillary Oil', 'Current', 'electricalStore', 0),
(857, 'R/A42', 'Auto Expansion Valve B R-12, 6.5kg B-22 Refrigerant', 'Current', 'electricalStore', 0),
(858, 'R/A43', 'Zen Compressor 50AA', 'Current', 'electricalStore', 0),
(859, 'R/A44', 'R-600A Gas', 'Current', 'electricalStore', 0),
(860, 'R/A45', 'R-600A Oil', 'Current', 'electricalStore', 0),
(861, 'R/A46', '13kg R-12 Gas', 'Current', 'electricalStore', 0),
(862, 'R/A47', 'Expansion Valve R-12', 'Current', 'electricalStore', 0),
(863, 'R/A48', 'Air Conditioner Hanger', 'Current', 'electricalStore', 0),
(864, 'R/A49', '13.6kg R-134A', 'Current', 'electricalStore', 0),
(865, 'R/A50', 'Treading Tape', 'Current', 'electricalStore', 0),
(866, 'R/A51', '3HP Carrier New Compressor', 'Current', 'electricalStore', 0),
(867, 'R/A52', 'Mortuary Driers', 'Current', 'electricalStore', 0),
(868, 'R/A53', 'Copper Tubing 5/16', 'Current', 'electricalStore', 0),
(869, 'R/A54', 'Clips', 'Current', 'electricalStore', 0),
(870, 'R/A55', '1HP Rotary Compressor', 'Current', 'electricalStore', 0),
(871, 'R/A56', '2HP Panasonic Evaporation Power Control', 'Current', 'electricalStore', 0),
(872, 'R/A57', 'Copper Tubing 1/2', 'Current', 'electricalStore', 0),
(873, 'R/A58', '3 Phase Contractor 100 Amps', 'Current', 'electricalStore', 0),
(874, 'R/A59', 'Standing LG', 'Current', 'electricalStore', 0),
(875, 'R/A60', 'Charging Hose', 'Current', 'electricalStore', 0),
(876, 'R/A61', 'R-410 Gas', 'Current', 'electricalStore', 0),
(877, 'R/A62', 'R-134 Oil', 'Current', 'electricalStore', 0),
(878, 'R/A63', '1/2 Anaflex Insulator', 'Current', 'electricalStore', 0),
(879, 'R/A64', '¼ Amperfor Insulator', 'Current', 'electricalStore', 0),
(880, 'R/A65', '2000 Stabilizer', 'Current', 'electricalStore', 0),
(881, 'R/A66', 'Overload Protector', 'Current', 'electricalStore', 0),
(882, 'R/A67', 'Stabilizer Relay', 'Current', 'electricalStore', 0),
(883, 'R/A68', '2.5UF Condenser Fan Capacitor', 'Current', 'electricalStore', 0),
(884, 'R/A69', 'Extractor Fan', 'Current', 'electricalStore', 0),
(885, 'R/A70', '5000 VA Stabilizer', 'Current', 'electricalStore', 0),
(886, 'R/A71', 'Contractor for Air Conditioner', 'Current', 'electricalStore', 0),
(887, 'R/A72', 'Gas Hose', 'Current', 'electricalStore', 0),
(888, 'R/A73', 'Condenser', 'Current', 'electricalStore', 0),
(889, 'LT1', 'Brake Fluid', 'Current', 'electricalStore', 0),
(890, 'LT2', 'Engine Oil Drum S.40 (Rubia XT)', 'Current', 'electricalStore', 0),
(891, 'LT3', 'Engine Oil Drum S.30 (Rubia S)', 'Current', 'electricalStore', 0),
(892, 'LT4', 'Gear Oil Sea.90', 'Current', 'electricalStore', 0),
(893, 'LT5', 'Oil in Gallon S.40', 'Current', 'electricalStore', 0),
(894, 'LT6', 'Battery 12 75AMP', 'Current', 'electricalStore', 0),
(895, 'LT7', 'Grease 1x 50KG', 'Current', 'electricalStore', 0),
(896, 'LT8', 'Radiator Flush G. and E.', 'Current', 'electricalStore', 0),
(897, 'LT9', 'Grease in Drum', 'Current', 'electricalStore', 0),
(898, 'LT10', 'Battery 24 charger 220/250 volts', 'Current', 'electricalStore', 0),
(899, 'LT11', 'Hydraulic Oil', 'Current', 'electricalStore', 0),
(900, 'LT12', 'R.M. Oil (Engine) Gallon', 'Current', 'electricalStore', 0),
(901, 'LT13', 'Hydraulic Oil Gallon (4 Litres)', 'Current', 'electricalStore', 0),
(902, 'LT14', 'Radiator Sealant', 'Current', 'electricalStore', 0),
(903, 'LT15', '150Amps Battery Drycell', 'Current', 'electricalStore', 0),
(904, 'LT16', '200 Amps Battery', 'Current', 'electricalStore', 0),
(905, 'LT17', '4mm Sq. Rubber Hose', 'Current', 'electricalStore', 0),
(906, 'LT18', 'Petrol Fuel Injector Cleaner', 'Current', 'electricalStore', 0),
(907, 'LT19', '100 Amps Rocket Battery', 'Current', 'electricalStore', 0),
(908, 'LT20', 'Engine Flusher', 'Current', 'electricalStore', 0),
(909, 'LT21', 'Quartz 100 Litres', 'Current', 'electricalStore', 0),
(910, 'LT22', 'Battery Terminal', 'Current', 'electricalStore', 0),
(911, 'LM1', 'Cutter Bar 279', 'Current', 'electricalStore', 0),
(912, 'LM2', 'Lawn Mower Blancer', 'Current', 'electricalStore', 0),
(913, 'LM3', 'Cutting Blade', 'Current', 'electricalStore', 0),
(914, 'LM4', 'Pistons & Rings', 'Current', 'electricalStore', 0),
(915, 'LM5', 'Trimming Line', 'Current', 'electricalStore', 0),
(916, 'LM6', 'Block for Brush Cutter', 'Current', 'electricalStore', 0),
(917, 'LM7', 'Cutting Head for Brush Cutters', 'Current', 'electricalStore', 0),
(918, 'PG1', 'Blue Gun', 'Current', 'electricalStore', 0),
(919, 'PG2', 'Oil Filter', 'Current', 'electricalStore', 0),
(920, 'PG3', 'A/c Fan Belt', 'Current', 'electricalStore', 0),
(921, 'PG4', 'Plug', 'Current', 'electricalStore', 0),
(922, 'PG5', 'Side Mirror', 'Current', 'electricalStore', 0),
(923, 'PG6', 'Clutch Plate & Disc', 'Current', 'electricalStore', 0),
(924, 'PG7', 'Middle Bush', 'Current', 'electricalStore', 0),
(925, 'PG8', 'Rear Shock Absorber', 'Current', 'electricalStore', 0),
(926, 'PG9', 'Front Shock Absorber', 'Current', 'electricalStore', 0),
(927, 'PG10', 'Master Clutch Repair Upper/Lower', 'Current', 'electricalStore', 0),
(928, 'PG11', 'Steering Wheel', 'Current', 'electricalStore', 0),
(929, 'PG12', 'Head Light Bulb', 'Current', 'electricalStore', 0),
(930, 'PG13', 'Main & Side Bearing', 'Current', 'electricalStore', 0),
(931, 'PG14', 'Clutch Finger', 'Current', 'electricalStore', 0),
(932, 'PG15', 'Complete Steering Rack Rod', 'Current', 'electricalStore', 0),
(933, 'PG16', 'Wind Screen', 'Current', 'electricalStore', 0),
(934, 'PG17', 'Condenser', 'Current', 'electricalStore', 0),
(935, 'PG18', 'Triangle Head Sign', 'Current', 'electricalStore', 0),
(936, 'PG19', 'Crank Shaft', 'Current', 'electricalStore', 0),
(937, 'PG20', 'Jack', 'Current', 'electricalStore', 0),
(938, 'PG21', 'Dowel Rubber', 'Current', 'electricalStore', 0),
(939, 'PG22', 'Oil Seal', 'Current', 'electricalStore', 0),
(940, 'PG23', 'Brake Calipers', 'Current', 'electricalStore', 0),
(941, 'PG24', 'Front Wheel Brake', 'Current', 'electricalStore', 0),
(942, 'PG25', 'Master Servo Brake', 'Current', 'electricalStore', 0),
(943, 'PG26', 'Shock Absorber Pad', 'Current', 'electricalStore', 0),
(944, 'PG27', 'Propeller Universal Joint', 'Current', 'electricalStore', 0),
(945, 'PG28', 'Big Bush', 'Current', 'electricalStore', 0),
(946, 'GP1', 'Oil Filter 500KVA', 'Current', 'electricalStore', 0),
(947, 'GP2', 'Fuel Filter 500KVA', 'Current', 'electricalStore', 0),
(948, 'GP3', 'Oil Filter 250KVA', 'Current', 'electricalStore', 0),
(949, 'GP4', 'Fuel Filter 250KVA', 'Current', 'electricalStore', 0),
(950, 'GP5', 'Oil Filter 80 KVA', 'Current', 'electricalStore', 0),
(951, 'GP6', 'Fuel Filter 80 KVA', 'Current', 'electricalStore', 0),
(952, 'GP7', 'Ignition Coil', 'Current', 'electricalStore', 0),
(953, 'GP8', 'Fan Belt 250KVA', 'Current', 'electricalStore', 0),
(954, 'GP9', 'Fan Belt 750KVA', 'Current', 'electricalStore', 0),
(955, 'GP10', 'Fan Belt 500KVA', 'Current', 'electricalStore', 0),
(956, 'GP12', 'Oil Filter 27KVA', 'Current', 'electricalStore', 0),
(957, 'GP13', 'Fuel Filter 27KVA', 'Current', 'electricalStore', 0),
(958, 'GP14', 'Oil Filter 30KVA', 'Current', 'electricalStore', 0),
(959, 'GP15', 'Fuel Filter 30KVA', 'Current', 'electricalStore', 0),
(960, 'GP16', 'Oil Filter 60KVA', 'Current', 'electricalStore', 0),
(961, 'GP17', 'Fuel Filter 60KVA', 'Current', 'electricalStore', 0),
(962, 'GP18', 'Oil Filter 110KVA', 'Current', 'electricalStore', 0),
(963, 'GP19', 'Fuel Filter 110KVA', 'Current', 'electricalStore', 0),
(964, 'GP20', 'Injector Nozzles', 'Current', 'electricalStore', 0),
(965, 'GP21', 'Oil Filter 1000KVA', 'Current', 'electricalStore', 0),
(966, 'GP22', 'Fuel Filter 1000KVA', 'Current', 'electricalStore', 0),
(967, 'GP23', 'Air Filter', 'Current', 'electricalStore', 0),
(968, 'GP24', 'Oil Filter 150KVA', 'Current', 'electricalStore', 0),
(969, 'GP25', 'Fuel Filter 150KVA', 'Current', 'electricalStore', 0),
(970, 'GP26', 'Oil Filter 100KVA', 'Current', 'electricalStore', 0),
(971, 'GP27', 'Fuel Filter 100KVA', 'Current', 'electricalStore', 0),
(972, 'GP28', 'Fan Belt Different Size', 'Current', 'electricalStore', 0),
(973, 'EG1', 'Throlley Wheel Caster 1\" 1/2\"', 'Current', 'electricalStore', 0),
(974, 'EG2', 'Throlley Wheel Caster 2\"', 'Current', 'electricalStore', 0),
(975, 'EG3', 'Throlley Wheel Caster 3\"', 'Current', 'electricalStore', 0),
(976, 'EG4', 'Throlley Wheel Caster 4\"', 'Current', 'electricalStore', 0),
(977, 'EG5', 'Washing Machine Belt', 'Current', 'electricalStore', 0),
(978, 'EG6', 'Brase Comling 3\"', 'Current', 'electricalStore', 0),
(979, 'EG7', 'Clip 2\"/3\" Hose Clip', 'Current', 'electricalStore', 0),
(980, 'EG8', 'Capacitors 3\"/ Hose Clip', 'Current', 'electricalStore', 0),
(981, 'EG9', 'Folding Sheet', 'Current', 'electricalStore', 0),
(982, 'EG10', 'Complete Tool Box (mechanical)', 'Current', 'electricalStore', 0),
(983, 'EG11', 'Pump Jack Soft', 'Current', 'electricalStore', 0),
(984, 'EG12', 'Centrifugal Water Pump 50 inlet & nut', 'Current', 'electricalStore', 0),
(985, 'EG13', '20 Seater Buren Bus', 'Current', 'electricalStore', 0),
(986, 'EG14', 'Hand Machine Grinding (wedding)', 'Current', 'electricalStore', 0),
(987, 'EG15', 'Grinding Machine (Blender)', 'Current', 'electricalStore', 0),
(988, 'EG16', 'Cutting Machine', 'Current', 'electricalStore', 0),
(989, 'EG17', 'Water Pump Retaining Seal', 'Current', 'electricalStore', 0),
(990, 'EG18', 'Wire Brush', 'Current', 'electricalStore', 0),
(991, 'EG19', 'Pliers', 'Current', 'electricalStore', 0),
(992, 'EG20', 'Ring Spanner', 'Current', 'electricalStore', 0),
(993, 'EG21', 'Pliers (BJ) Type', 'Current', 'electricalStore', 0),
(994, 'EG22', 'Sakle with Pins', 'Current', 'electricalStore', 0),
(995, 'EG23', 'Hose Clip', 'Current', 'electricalStore', 0),
(996, 'EG24', 'Bending Machine', 'Current', 'electricalStore', 0),
(997, 'EG25', 'Wafers Hose 25 meter', 'Current', 'electricalStore', 0),
(998, 'EG26', 'Screw Driver', 'Current', 'electricalStore', 0),
(999, 'EG27', 'Drilling Machine', 'Current', 'electricalStore', 0),
(1000, 'EG28', 'G21 Electrode', 'Current', 'electricalStore', 0),
(1001, 'EG29', '002 5kg Fire Extinguishers', 'Current', 'electricalStore', 0),
(1002, 'EG30', '1000kg Heater', 'Current', 'electricalStore', 0),
(1003, 'EG31', 'Delivery Hose (2\" x 75)', 'Current', 'electricalStore', 0),
(1004, 'EG32', 'Water Pump Machine 0.5HP', 'Current', 'electricalStore', 0),
(1005, 'EG33', 'Water Pump Machine (Tanker)', 'Current', 'electricalStore', 0),
(1006, 'E.1', 'Ceiling Fan Regulator', 'Current', 'electricalStore', 0),
(1007, 'E.2', 'Saddle Clip', 'Current', 'electricalStore', 0),
(1008, 'E.3', 'Pin Insulator', 'Current', 'electricalStore', 0),
(1009, 'E.4', '15 Amps Top Plug', 'Current', 'electricalStore', 0),
(1010, 'E.5', '2 x 10 Screw', 'Current', 'electricalStore', 0),
(1011, 'E.6', 'Male Push', 'Current', 'electricalStore', 0),
(1012, 'E.7', '100 Watts Bulb', 'Current', 'electricalStore', 0),
(1013, 'E.8', 'Deep Patress', 'Current', 'electricalStore', 0),
(1014, 'E.9', 'Rechargeable Lantern', 'Current', 'electricalStore', 0),
(1015, 'E.10', '1.6mm² PVC Aluminum Cable', 'Current', 'electricalStore', 0),
(1016, 'E.11', '2 Way Surface Switch', 'Current', 'electricalStore', 0),
(1017, 'E.12', 'Pressing Iron Electric', 'Current', 'electricalStore', 0),
(1018, 'E.13', 'Gantry Switch', 'Current', 'electricalStore', 0),
(1019, 'E.14', 'Ceiling Rose', 'Current', 'electricalStore', 0),
(1020, 'E.15', '30 Amps Cut Out Cartridge Fuse', 'Current', 'electricalStore', 0),
(1021, 'E.16', 'Kettle Element', 'Current', 'electricalStore', 0),
(1022, 'E.17', 'Bound for Ceiling', 'Current', 'electricalStore', 0),
(1023, 'E.18', '2ft Fluorescent Tube', 'Current', 'electricalStore', 0),
(1024, 'E.19', 'Starter', 'Current', 'electricalStore', 0),
(1025, 'E.20', 'Conduit Pipe', 'Current', 'electricalStore', 0),
(1026, 'E.21', '15 Amps Socket Outlet Patress', 'Current', 'electricalStore', 0),
(1027, 'E.22', 'Clips', 'Current', 'electricalStore', 0),
(1028, 'E.23', 'Brass Nail', 'Current', 'electricalStore', 0),
(1029, 'E.24', '4ft Fluorescent Chalk', 'Current', 'electricalStore', 0),
(1030, 'E.25', '13 Amps Top Plug', 'Current', 'electricalStore', 0),
(1031, 'E.26', 'Junction Box', 'Current', 'electricalStore', 0),
(1032, 'E.27', 'Wall Socket', 'Current', 'electricalStore', 0),
(1033, 'E.28', 'Lamp Holder', 'Current', 'electricalStore', 0),
(1034, 'E.29', '13 Amps Socket Without Patress', 'Current', 'electricalStore', 0),
(1035, 'E.30', 'Rubbing Hammer', 'Current', 'electricalStore', 0),
(1036, 'E.31', 'Rubbing Hammer Medium', 'Current', 'electricalStore', 0),
(1037, 'E.32', 'Industrial Tape (Red & Black)', 'Current', 'electricalStore', 0),
(1038, 'E.33', '25mm² 3 Core Cable', 'Current', 'electricalStore', 0),
(1039, 'E.34', 'Clips (medium)', 'Current', 'electricalStore', 0),
(1040, 'E.35', '2 Way Gamy Switch', 'Current', 'electricalStore', 0),
(1041, 'E.36', '4mm² Single Core Cable', 'Current', 'electricalStore', 0),
(1042, 'E.37', '1.5mm Single Cable (Red)', 'Current', 'electricalStore', 0),
(1043, 'E.38', '1.5mm Single Cable (Black)', 'Current', 'electricalStore', 0),
(1044, 'E.39', '3 Phase 100 A Switch', 'Current', 'electricalStore', 0),
(1045, 'E.40', '18 Watt Energy Bulb', 'Current', 'electricalStore', 0),
(1046, 'E.41', '1.00mm Core Flexible Cable', 'Current', 'electricalStore', 0),
(1047, 'E.42', '60 A Gear Switch 3 Phase', 'Current', 'electricalStore', 0),
(1048, 'E.43', '12 Ways 3 Phase Consumer Fuse', 'Current', 'electricalStore', 0),
(1049, 'E.44', 'Ceiling Fan', 'Current', 'electricalStore', 0),
(1050, 'E.45', '3HP Submersible Pump', 'Current', 'electricalStore', 0),
(1051, 'E.46', '2HP Submersible Pump', 'Current', 'electricalStore', 0),
(1052, 'E.47', '100 Amp C.P.C Change Over Switch', 'Current', 'electricalStore', 0),
(1053, 'E.48', '1.0mm² Core Cable Roll', 'Current', 'electricalStore', 0),
(1054, 'E.49', '6mm² Copper Cable', 'Current', 'electricalStore', 0),
(1055, 'E.50', '30 Amps Gear Switch', 'Current', 'electricalStore', 0),
(1056, 'E.51', 'Round Flexible Wire (Yards)', 'Current', 'electricalStore', 0),
(1057, 'E.52', 'Telephone Microphone (Condenser Mouth Piece)', 'Current', 'electricalStore', 0),
(1058, 'E.53', '2 / 7 Screw (1 x 7)', 'Current', 'electricalStore', 0),
(1059, 'E.54', 'Insulated Connector', 'Current', 'electricalStore', 0),
(1060, 'E.55', '16mm² Single Copper Wire Coil', 'Current', 'electricalStore', 0),
(1061, 'E.56', '250ld Halogen Bulb', 'Current', 'electricalStore', 0),
(1062, 'E.57', 'Ceiling Bell', 'Current', 'electricalStore', 0),
(1063, 'E.58', 'Electric Kettle', 'Current', 'electricalStore', 0),
(1064, 'E.59', 'Motor Driving Van Belt Small', 'Current', 'electricalStore', 0),
(1065, 'E.60', 'Motor Driving Van Belt Large', 'Current', 'electricalStore', 0),
(1066, 'E.61', '3 Gang Control Switch', 'Current', 'electricalStore', 0),
(1067, 'E.62', '30 Amps Gear Switch', 'Current', 'electricalStore', 0),
(1068, 'E.63', 'Ravol Plug Set', 'Current', 'electricalStore', 0),
(1069, 'E.64', '2.75 Clip with Nail', 'Current', 'electricalStore', 0),
(1070, 'E.65', '15mm² 3 Core Cable', 'Current', 'electricalStore', 0),
(1071, 'E.66', '25mm² Single Core Cable (Red)', 'Current', 'electricalStore', 0),
(1072, 'E.67', '25mm² Single Core Cable (Black)', 'Current', 'electricalStore', 0),
(1073, 'E.68', '15mm 2 Core Cable metre', 'Current', 'electricalStore', 0),
(1074, 'E.69', '13 Amp Double Socket Fuse', 'Current', 'electricalStore', 0),
(1075, 'E.70', 'Straight Batten Holder', 'Current', 'electricalStore', 0),
(1076, 'E.71', '20 Amps Control Switch', 'Current', 'electricalStore', 0),
(1077, 'E.72', 'Maintenance Circuit Breaker 30 Amps', 'Current', 'electricalStore', 0),
(1078, 'E.73', '1.5mm² Core White Flexible Cable Coil', 'Current', 'electricalStore', 0),
(1079, 'E.74', '12.0mm Telephone Clip with Nail', 'Current', 'electricalStore', 0),
(1080, 'E.75', '¾ x 6 Screw', 'Current', 'electricalStore', 0),
(1081, 'E.76', 'Heater 230 V/50 6kW', 'Current', 'electricalStore', 0),
(1082, 'E.77', '2 pairs of Telephone Cable Metres', 'Current', 'electricalStore', 0),
(1083, 'E.78', 'Standing Fan', 'Current', 'electricalStore', 0),
(1084, 'E.79', '1.5mm² Core Cable', 'Current', 'electricalStore', 0),
(1085, 'E.80', 'Telephone Clip', 'Current', 'electricalStore', 0),
(1086, 'E.81', '3.5 Ceiling Fan Capacitor', 'Current', 'electricalStore', 0),
(1087, 'E.80A', 'Cussan Fan Meter for AC', 'Current', 'electricalStore', 0),
(1088, 'E.81A', '27mm Telephone Clip with Nail', 'Current', 'electricalStore', 0),
(1089, 'E.82', 'Bending Machine', 'Current', 'electricalStore', 0),
(1090, 'E.83', 'Sledge Hammer', 'Current', 'electricalStore', 0),
(1091, 'E.84', 'Cold Chisel', 'Current', 'electricalStore', 0),
(1092, 'E.85', 'Crocodile Clips', 'Current', 'electricalStore', 0),
(1093, 'E.86', 'HRC Fuse', 'Current', 'electricalStore', 0),
(1094, 'E.87', '6mm¹ Core PVC Aluminum Cable', 'Current', 'electricalStore', 0),
(1095, 'E.88', '18\" Coaxial Cable', 'Current', 'electricalStore', 0),
(1096, 'E.89', '60 Amps Control Switch Cartridge', 'Current', 'electricalStore', 0),
(1097, 'E.90', '100 Amp 3 Phase Circuit Breaker', 'Current', 'electricalStore', 0),
(1098, 'E.91', '2 x 2.5mm Cable 3 Core White Round Textable Cable', 'Current', 'electricalStore', 0),
(1099, 'E.92', 'Cartridge Fuse', 'Current', 'electricalStore', 0),
(1100, 'E.93', 'Dark Room Bulb 220V-1500W', 'Current', 'electricalStore', 0),
(1101, 'E.94', '2.5 Core Cable', 'Current', 'electricalStore', 0),
(1102, 'E.95', 'Neon Tube Lamp', 'Current', 'electricalStore', 0),
(1103, 'E.96', 'Panel Starter for 5 HP Control', 'Current', 'electricalStore', 0),
(1104, 'E.97', 'Bending Machine', 'Current', 'electricalStore', 0),
(1105, 'E.98', '160W Mercury Bulb Pin Type', 'Current', 'electricalStore', 0),
(1106, 'E.99', 'Colour Television', 'Current', 'electricalStore', 0),
(1107, 'E.100', 'Telephone Stay Wire', 'Current', 'electricalStore', 0),
(1108, 'E.101', '20mm² Plastic Pipe', 'Current', 'electricalStore', 0),
(1109, 'E.102', 'Soldering Leg', 'Current', 'electricalStore', 0),
(1110, 'E.103', 'Overload Relay', 'Current', 'electricalStore', 0),
(1111, 'E.104', '1.5 HP Submersible Pump', 'Current', 'electricalStore', 0),
(1112, 'E.105', '5 HP Motor', 'Current', 'electricalStore', 0),
(1113, 'E.106', '4.5µF Capacitor', 'Current', 'electricalStore', 0),
(1114, 'E.107', 'KDK Bush Straight', 'Current', 'electricalStore', 0),
(1115, 'E.108', 'External Retinal Rubber Straight', 'Current', 'electricalStore', 0),
(1116, 'E.109', 'Complete Rotating Gear for K0K', 'Current', 'electricalStore', 0),
(1117, 'E.110', '100 Amps Industrial Cutout', 'Current', 'electricalStore', 0),
(1118, 'E.111', 'Meter Board', 'Current', 'electricalStore', 0),
(1119, 'E.112', '1½ x 7 Wooden Screw', 'Current', 'electricalStore', 0),
(1120, 'E.113', '2 x 10 Wooden Screw', 'Current', 'electricalStore', 0),
(1121, 'E.114', 'Timing Relay', 'Current', 'electricalStore', 0),
(1122, 'E.115', 'Terminal Switch', 'Current', 'electricalStore', 0),
(1123, 'E.116', 'Carbon Brush', 'Current', 'electricalStore', 0),
(1124, 'E.117', '1½ x 8 Wooden Screw', 'Current', 'electricalStore', 0),
(1125, 'E.118', 'Battlen Holder', 'Current', 'electricalStore', 0),
(1126, 'E.119', '2bA Screw', 'Current', 'electricalStore', 0),
(1127, 'E.120', '12 Way MEB', 'Current', 'electricalStore', 0),
(1128, 'E.121', 'Compiling', 'Current', 'electricalStore', 0),
(1129, 'E.122', 'HBA Screw', 'Current', 'electricalStore', 0),
(1130, 'E.123', 'Contractor Telecome Global 4041', 'Current', 'electricalStore', 0),
(1131, 'E.124', 'Fixing Tape Electrical', 'Current', 'electricalStore', 0),
(1132, 'E.125', '400 Amps Gear Switch', 'Current', 'electricalStore', 0),
(1133, 'E.126', 'Street Light', 'Current', 'electricalStore', 0),
(1134, 'E.127', 'Glass Shade for Street Light', 'Current', 'electricalStore', 0),
(1135, 'E.128', 'Reading Lamp', 'Current', 'electricalStore', 0),
(1136, 'E.129', '85 Watts', 'Current', 'electricalStore', 0),
(1137, 'E.130', '26 Watts Energy Saving Bulb', 'Current', 'electricalStore', 0),
(1138, 'E.131', 'LG DVD Player', 'Current', 'electricalStore', 0),
(1139, 'E.132', '400 A Staking Standard', 'Current', 'electricalStore', 0),
(1140, 'E.133', '5 x 50mm Digital Trucking App', 'Current', 'electricalStore', 0),
(1141, 'E.134', '0.5 Submersible Pump', 'Current', 'electricalStore', 0),
(1142, 'E.135', '100 A Cutout Original', 'Current', 'electricalStore', 0),
(1143, 'E.136', '100 A Single Phase Control Main Switch', 'Current', 'electricalStore', 0),
(1144, 'E.137', '18 Way Distribution Fuse Board 3 Phase A & B Type', 'Current', 'electricalStore', 0),
(1145, 'E.138', 'Multi Socket Wooden Type/Essential Socket', 'Current', 'electricalStore', 0),
(1146, 'E.139', '15 A Socket', 'Current', 'electricalStore', 0),
(1147, 'E.140', '25μF, 50Hz 400/450 Vac Capacitor', 'Current', 'electricalStore', 0),
(1148, 'E.141', '40μF Capacitor', 'Current', 'electricalStore', 0),
(1149, 'E.142', '8 Ways Single Phase DIB', 'Current', 'electricalStore', 0),
(1150, 'E.143', 'Fishing Tape', 'Current', 'electricalStore', 0),
(1151, 'E.144', 'Fan Hook', 'Current', 'electricalStore', 0),
(1152, 'E.145', '3 Phase Connector', 'Current', 'electricalStore', 0),
(1153, 'E.146', '12 Ring Mini Unit (RMU) Fuse 63 Amps', 'Current', 'electricalStore', 0),
(1154, 'E.147', '4 Ways DFB', 'Current', 'electricalStore', 0),
(1155, 'E.148', '1 HP Submersible Pump', 'Current', 'electricalStore', 0),
(1156, 'E.149', 'Tornado Nail', 'Current', 'electricalStore', 0),
(1157, 'E.150', '200 Amps Fuse', 'Current', 'electricalStore', 0),
(1158, 'E.151', '½ Harmflex Insulator', 'Current', 'electricalStore', 0),
(1159, 'E.152', '¼ Harmflex Insulator', 'Current', 'electricalStore', 0),
(1160, 'E.153', '30 Watt LED Flood Lamps', 'Current', 'electricalStore', 0),
(1161, 'E.154', '34 HP Control Box', 'Current', 'electricalStore', 0),
(1162, 'E.155', '2HP Control Box', 'Current', 'electricalStore', 0),
(1163, 'E.156', '1 HP Control Box', 'Current', 'electricalStore', 0),
(1164, 'E.157', '18 A Thermal Switch', 'Current', 'electricalStore', 0),
(1165, 'E.158', '16 A Thermal Switch', 'Current', 'electricalStore', 0),
(1166, 'E.159', '5 A Thermal Switch', 'Current', 'electricalStore', 0),
(1167, 'E.160', '46 A Water Heater Switch', 'Current', 'electricalStore', 0),
(1168, 'E.161', 'Water Dispenser', 'Current', 'electricalStore', 0),
(1169, 'E.162', '100 A 3 Phase Changeover Switch', 'Current', 'electricalStore', 0),
(1170, 'E.163', '200 A 3 Phase Changeover Switch', 'Current', 'electricalStore', 0),
(1171, 'E.164', 'Reclin Cable 50mm Square', 'Current', 'electricalStore', 0),
(1172, 'E.165', 'Complete D-Inn', 'Current', 'electricalStore', 0),
(1173, 'E.166', '100W Solar Street Light', 'Current', 'electricalStore', 0),
(1174, 'E.167', 'Inverter', 'Current', 'electricalStore', 0),
(1175, 'E.168', 'Heating Element 5KW', 'Current', 'electricalStore', 0),
(1176, 'E.169', 'Monitor', 'Current', 'electricalStore', 0),
(1177, 'E.170', '120 Feet Mast', 'Current', 'electricalStore', 0),
(1178, 'E.171', 'UBI Quit PBE-SAC Gen Power Beam', 'Current', 'electricalStore', 0),
(1179, 'E.172', 'Various Size/16 Ports Gigabit D-Link Switch', 'Current', 'electricalStore', 0),
(1180, 'E.173', 'Cat 6 Pure Copper D-Link', 'Current', 'electricalStore', 0),
(1181, 'E.174', 'D-Link Face Plates', 'Current', 'electricalStore', 0),
(1182, 'E.175', '10ft Foldable Aluminium Step Ladder', 'Current', 'electricalStore', 0),
(1183, 'E.176', 'Electric Hand Air Blower', 'Current', 'electricalStore', 0),
(1184, 'E.177', 'Networking Crimping Tool R-145', 'Current', 'electricalStore', 0),
(1185, 'E.178', 'Electrical Tools Box Bit', 'Current', 'electricalStore', 0),
(1186, 'E.179', 'RJ 45 Punch Down Tool', 'Current', 'electricalStore', 0),
(1187, 'E.180', 'Bosch Electrical Drilling', 'Current', 'electricalStore', 0),
(1188, 'E.181', 'Topwin Piping Bolt Set', 'Current', 'electricalStore', 0),
(1189, 'E.182', 'Inglo Specs Screw Driver Set', 'Current', 'electricalStore', 0),
(1190, 'E.183', 'T Spanner Socket Type', 'Current', 'electricalStore', 0),
(1191, 'E.184', 'Rechargeable Standing Fan', 'Current', 'electricalStore', 0),
(1192, 'E.185', 'Safety Helmet', 'Current', 'electricalStore', 0),
(1193, 'E.186', 'RJ 45 (1000) Pieces', 'Current', 'electricalStore', 0),
(1194, 'E.187', '4U Rack Totem Product', 'Current', 'electricalStore', 0),
(1195, 'E.188', '17mm Bolts, Nuts, and Washer', 'Current', 'electricalStore', 0),
(1196, 'E.189', 'Rubber Tie of Size 7mm', 'Current', 'electricalStore', 0),
(1197, 'E.190', '2.5mm Cable Saddle Clip with Nails', 'Current', 'electricalStore', 0),
(1198, 'E.191', '6ft Copper Earth Rod', 'Current', 'electricalStore', 0),
(1199, 'E.192', 'Earth Clamp', 'Current', 'electricalStore', 0),
(1200, 'E.193', 'D. Surge Breaker (150 KVA)', 'Current', 'electricalStore', 0),
(1201, 'E.194', '100A Knife Switch', 'Current', 'electricalStore', 0),
(1202, 'E.195', 'Respiratory-Industrial Nose Mask', 'Current', 'electricalStore', 0),
(1203, 'E.196', 'Solar Panel', 'Current', 'electricalStore', 0),
(1204, 'E.197', 'MPPT Charge Controller', 'Current', 'electricalStore', 0),
(1205, 'E.198', 'Electrical Device', 'Current', 'electricalStore', 0),
(1206, 'MTL 1 HR 65', 'Child Health Charts', 'None Current', 'healthStationeryStore', 0),
(1207, 'MTL 2 HR 22', 'Outpatient Cards', 'None Current', 'healthStationeryStore', 0),
(1208, 'MTL 3 HR 01', 'Case folder', 'None Current', 'healthStationeryStore', 0),
(1209, 'MTL 4 HR 42', 'Blood Donor sheets', 'None Current', 'healthStationeryStore', 0),
(1210, 'MTL 5 HR 5A', 'Consultation Request form', 'None Current', 'healthStationeryStore', 0),
(1211, 'MTL 6 HR 6', 'Student Clinical Notes', 'None Current', 'healthStationeryStore', 0),
(1212, 'MTL 7HR 9', 'In-patient Prescription sheets', 'None Current', 'healthStationeryStore', 0),
(1213, 'MTL8 HR 10', 'Fluid Balance sheet', 'None Current', 'healthStationeryStore', 0),
(1214, 'MTL 9 HR 12', 'T.P.R. Charts', 'None Current', 'healthStationeryStore', 0),
(1215, 'MTL 10 HR 15', 'Operating Lists', 'None Current', 'healthStationeryStore', 0),
(1216, 'MTL 11 HR 24', 'Outpatient Prescription pad', 'None Current', 'healthStationeryStore', 0),
(1217, 'MTL 12 HR 25', 'Radiology Request', 'None Current', 'healthStationeryStore', 0),
(1218, 'MTL 13 HR 31', 'X-Ray Name label pads of 100 sheets', 'None Current', 'healthStationeryStore', 0),
(1219, 'MTL 14 HR 44A', 'Chemical Pathology Reg. form', 'None Current', 'healthStationeryStore', 0),
(1220, 'MTL 15 HR 44B', 'Chemical Pathology Request/Report form', 'None Current', 'healthStationeryStore', 0),
(1221, 'MTL 16HR 55', 'Bed Statement', 'None Current', 'healthStationeryStore', 0),
(1222, 'MTL 17 HR 95', 'Pre-Operative Check list for theatre', 'None Current', 'healthStationeryStore', 0),
(1223, 'MTL 18 HR 101', 'Referral form for physiotherapy', 'None Current', 'healthStationeryStore', 0),
(1224, 'MTL 19 HR 03', 'Name Index Card', 'None Current', 'healthStationeryStore', 0),
(1225, 'MTL 20 HR36', 'Haematology Request/Report form', 'None Current', 'healthStationeryStore', 0),
(1226, 'MTL 21 HR71', 'Immunization Attendance card', 'None Current', 'healthStationeryStore', 0),
(1227, 'MTL 22 HR78', 'Discharge Against Medical Advice form', 'None Current', 'healthStationeryStore', 0),
(1228, 'MTL 23 HR108', 'Cover page', 'None Current', 'healthStationeryStore', 0),
(1229, 'MTL 24 HR108A', 'Inner page 1', 'None Current', 'healthStationeryStore', 0),
(1230, 'MTL 25 HR108B', 'Inner page 2', 'None Current', 'healthStationeryStore', 0),
(1231, 'MTL 26HR 108C', 'Inner page 3', 'None Current', 'healthStationeryStore', 0),
(1232, 'MTL 27HR108D', 'Inner page 4', 'None Current', 'healthStationeryStore', 0),
(1233, 'MTL 28 HR 39', 'Blood Grouping and Cross matching Request form', 'None Current', 'healthStationeryStore', 0),
(1234, 'MTL 29 HR4', 'Patient Preference Cards', 'None Current', 'healthStationeryStore', 0),
(1235, 'MTL 30 HR21A', 'G.O.P.D card changed to outpatient card', 'None Current', 'healthStationeryStore', 0),
(1236, 'MTL 31HR21B', 'G.O.P.D Card', 'None Current', 'healthStationeryStore', 0),
(1237, 'MTL 32 HR 02', 'Tracer card', 'None Current', 'healthStationeryStore', 0),
(1238, 'MTL 33 HR20', 'A & E Card', 'None Current', 'healthStationeryStore', 0),
(1239, 'MTL 34 HR102', 'Ultrasound Request form', 'None Current', 'healthStationeryStore', 0),
(1240, 'MTL 35 HR26', 'Radiology Report', 'None Current', 'healthStationeryStore', 0),
(1241, 'MTL 36 HR53', 'Diseases Diagnostic Index Card', 'None Current', 'healthStationeryStore', 0),
(1242, 'MTL 37 HR 73', 'Excuse Duty Certificate', 'None Current', 'healthStationeryStore', 0),
(1243, 'MTL 38 HR23', 'Treatment Card', 'None Current', 'healthStationeryStore', 0),
(1244, 'MTL 39 HR43', 'Microbiology and Parasitology Request/Report', 'None Current', 'healthStationeryStore', 0),
(1245, 'MTL 40 HR54', 'Surgical Operation Index Card', 'None Current', 'healthStationeryStore', 0),
(1246, 'MTL 41 HR18', 'Perinatal Record', 'None Current', 'healthStationeryStore', 0),
(1247, 'MTL 42 HR51', 'Out Patient Clinic List', 'None Current', 'healthStationeryStore', 0),
(1248, 'MTL 43 HR27', 'X-Ray Envelopes', 'None Current', 'healthStationeryStore', 0),
(1249, 'MTL 44 HR66', 'Child Treatment Card', 'None Current', 'healthStationeryStore', 0),
(1250, 'MTL 45 HR32', 'Electro-Diagnostic Request/Report form', 'None Current', 'healthStationeryStore', 0),
(1251, 'MTL 46 HR96', 'Breast Feeding Chart', 'None Current', 'healthStationeryStore', 0),
(1252, 'MTL 47 HR56', 'Hospital Fees Assessment', 'None Current', 'healthStationeryStore', 0),
(1253, 'MTL 48 HR6', 'Clinical Notes', 'None Current', 'healthStationeryStore', 0),
(1254, 'MTL 49 HR28', 'X-Ray Name Index', 'None Current', 'healthStationeryStore', 0),
(1255, 'MTL 50 HR89', 'Blood Bag Label Yellow', 'None Current', 'healthStationeryStore', 0),
(1256, 'MTL 51 HR89', 'Blood Bag Label Blue', 'None Current', 'healthStationeryStore', 0),
(1257, 'MTL 52 HR89', 'Blood Bag Label White', 'None Current', 'healthStationeryStore', 0),
(1258, 'MTL 53 HR89', 'Blood Bag Label Pink', 'None Current', 'healthStationeryStore', 0),
(1259, 'MTL 54 HR97', 'Birth Notification Blue', 'None Current', 'healthStationeryStore', 0),
(1260, 'MTL 55 HR97', 'Birth Notification Pink', 'None Current', 'healthStationeryStore', 0),
(1261, 'MTL 56 ', 'Petrographic', 'None Current', 'healthStationeryStore', 0),
(1262, 'MTL 57', 'Hemoglobin Genotype Sheet', 'None Current', 'healthStationeryStore', 0),
(1263, 'MTL 58 HR13', 'Permission for Operation Form', 'None Current', 'healthStationeryStore', 0),
(1264, 'MTL 59 HR80', 'Hematology Log Book', 'None Current', 'healthStationeryStore', 0),
(1265, 'MTL 60 HR72', 'Maternity Leave Certificate', 'None Current', 'healthStationeryStore', 0),
(1266, 'MTL 61 HR121', 'Audiology Form', 'None Current', 'healthStationeryStore', 0),
(1267, 'MTL 62 HR 14', 'Operating Note', 'None Current', 'healthStationeryStore', 0),
(1268, 'MTL 63 HR113', 'Laboratory Surveillance Form', 'None Current', 'healthStationeryStore', 0),
(1269, 'MTL 64 HR 147A', 'NHIS Pharmacy Card', 'None Current', 'healthStationeryStore', 0),
(1270, 'MTL 65 114', 'Laboratory Surveillance Report', 'None Current', 'healthStationeryStore', 0),
(1271, 'MTL 66 147B', 'Patient NHIS Pharmacy Card', 'None Current', 'healthStationeryStore', 0),
(1272, 'MTL 67 HR 46', 'Histopathology Request Form', 'None Current', 'healthStationeryStore', 0),
(1273, 'MTL 68 HR 75', 'Medical Certificate', 'None Current', 'healthStationeryStore', 0),
(1274, 'MTL 69H R87', 'Health Centre Service (GOPD)', 'None Current', 'healthStationeryStore', 0),
(1275, 'MTL 70 HR109', 'Bed Summary Sheet (IHU)', 'None Current', 'healthStationeryStore', 0),
(1276, 'MTL 71 HR40', 'Blood Grouping and Cross matching Report form', 'None Current', 'healthStationeryStore', 0),
(1277, 'MTL 72 HR16', 'Anaesthesia Records', 'None Current', 'healthStationeryStore', 0),
(1278, 'MTL 73 HR41', 'Blood Grouping Index card', 'None Current', 'healthStationeryStore', 0),
(1279, 'MTL 74 HR49', 'Permission for Autopsy (first & post mortem report)', 'None Current', 'healthStationeryStore', 0),
(1280, 'MTL 75 HR90', 'Coma Observation Chart', 'None Current', 'healthStationeryStore', 0),
(1281, 'MTL 76 HR115', 'Control of Hospital Infection (medical & surgical infect)', 'None Current', 'healthStationeryStore', 0),
(1282, 'MTL 77 HR117', 'Control of Hospital Infection (blood culture forms)', 'None Current', 'healthStationeryStore', 0),
(1283, 'MTL 78 HR116', 'Control of Hospital Infection (urine forms)', 'None Current', 'healthStationeryStore', 0),
(1284, 'MTL 79 HR 94', 'Refraction Prescription form', 'None Current', 'healthStationeryStore', 0),
(1285, 'MTL 80', 'Physiotherapy Oxygen card', 'None Current', 'healthStationeryStore', 0),
(1286, 'MTL 81', 'Maternal Immunization card', 'None Current', 'healthStationeryStore', 0),
(1287, 'MTL 82 HR107', 'Haematology Clinic Chart', 'None Current', 'healthStationeryStore', 0),
(1288, 'MTL 83 HR122', 'Kalamazoo Register Sheet (G.O.P.D)', 'None Current', 'healthStationeryStore', 0),
(1289, 'MTL 84 103', 'Staff Medical Report form', 'None Current', 'healthStationeryStore', 0),
(1290, 'MTL 85 HR34A', 'Dental Treatment Combination Notes', 'None Current', 'healthStationeryStore', 0),
(1291, 'MTL 86 HR85', 'General Microbiology Laboratory Reception Reg.', 'None Current', 'healthStationeryStore', 0),
(1292, 'MTL 87 HR33', 'Dental Treatment file', 'None Current', 'healthStationeryStore', 0),
(1293, 'MTL 88 HR29', 'X-Ray Tracer card', 'None Current', 'healthStationeryStore', 0),
(1294, 'MTL 89 HR43', 'Dental Treatment card', 'None Current', 'healthStationeryStore', 0),
(1295, 'MTL 90 HR119A', 'Case/Art form', 'None Current', 'healthStationeryStore', 0),
(1296, 'MTL 91 HR100', 'Delivery Register', 'None Current', 'healthStationeryStore', 0),
(1297, 'MTL 92 HR123', 'Operating Register', 'None Current', 'healthStationeryStore', 0),
(1298, 'MTL 93 HR120', 'General Ante Natal Register', 'None Current', 'healthStationeryStore', 0),
(1299, 'MTL 94 HR61', 'A & D Register Sheets', 'None Current', 'healthStationeryStore', 0),
(1300, 'MTL 95 HR11', 'Surgical Caution Chart/surgical', 'None Current', 'healthStationeryStore', 0),
(1301, 'MTL 96 HR86', 'Mortuary Register', 'None Current', 'healthStationeryStore', 0),
(1302, 'MTL 97 HR30', 'Radiology Register form', 'None Current', 'healthStationeryStore', 0),
(1303, 'MTL 98 HR35', 'Dental Laboratory card', 'None Current', 'healthStationeryStore', 0),
(1304, 'MTL 99 HR7B', 'Discharge summary sheets', 'None Current', 'healthStationeryStore', 0),
(1305, 'MTL 100 HR93', 'Medical Examination', 'None Current', 'healthStationeryStore', 0),
(1306, 'MTL 101HR131', 'Cervical cancer screening card', 'None Current', 'healthStationeryStore', 0),
(1307, 'MTL 102 H63', 'A&E Register', 'None Current', 'healthStationeryStore', 0),
(1308, 'MTL 103 HR132', 'Laparoscopic Surgeons Revolving fund forms', 'None Current', 'healthStationeryStore', 0),
(1309, 'MTL 104 HR 135', 'Plastic & burns Rev. fundship', 'None Current', 'healthStationeryStore', 0),
(1310, 'MTL 105 HR63A', 'Road Traffic Account register', 'None Current', 'healthStationeryStore', 0),
(1311, 'MTL 106 HR137', 'Documentation of Occupational therapy service', 'None Current', 'healthStationeryStore', 0),
(1312, 'MTL 107 HR138', 'Occupational therapy Notes', 'None Current', 'healthStationeryStore', 0),
(1313, 'MTL 108 HR139', 'Occupational Notes Initial Assessment', 'None Current', 'healthStationeryStore', 0),
(1314, 'MTL 109 HR140', 'Occupational Therapy Assessment', 'None Current', 'healthStationeryStore', 0),
(1315, 'MTL 110 HR141', 'Evaluation of Muscle Strenght', 'None Current', 'healthStationeryStore', 0),
(1316, 'MTL 111 HR143', 'Family Planning Record', 'None Current', 'healthStationeryStore', 0),
(1317, 'MTL 112 HR79', 'Blood Transfusion Log Book', 'None Current', 'healthStationeryStore', 0),
(1318, 'MTL 113 HR98', 'Medical Social Service Report forms', 'None Current', 'healthStationeryStore', 0),
(1319, 'MTL 114 HR60', 'G.O.P.D Register (Staff Clinic)', 'None Current', 'healthStationeryStore', 0),
(1320, 'MTL 115 HR92', 'Sleep Pattern Chair', 'None Current', 'healthStationeryStore', 0),
(1321, 'MTL 116 HR1e', 'Occupational Therapy case folder', 'None Current', 'healthStationeryStore', 0),
(1322, 'MTL 117 HR144B', 'Whole Human Blood', 'None Current', 'healthStationeryStore', 0),
(1323, 'MTL 118 HR144C', 'Whole Human Blood', 'None Current', 'healthStationeryStore', 0),
(1324, 'MTL 119 HR145', 'Menu Sheet', 'None Current', 'healthStationeryStore', 0),
(1325, 'MTL 120 HR8', 'Special Investigation', 'None Current', 'healthStationeryStore', 0),
(1326, 'MTL 121 HR1', 'Anaesthesia Record Book', 'None Current', 'healthStationeryStore', 0),
(1327, 'MTL 122 HR47', 'Pathology Report', 'None Current', 'healthStationeryStore', 0),
(1328, 'MTL 123 HR38', 'Haematology Day sheet', 'None Current', 'healthStationeryStore', 0),
(1329, 'MTL 124 HR45', 'Chemical Pathology Day sheet', 'None Current', 'healthStationeryStore', 0),
(1330, 'MTL 125 HR146', 'Laboratory Day Sheet', 'None Current', 'healthStationeryStore', 0),
(1331, 'MTL 126 HR148B', 'Clinical Documentation', 'None Current', 'healthStationeryStore', 0),
(1332, 'MTL 127 HR149', 'Patient Registration form', 'None Current', 'healthStationeryStore', 0),
(1333, 'MTL 128 HR148A', 'Geriatrics Case Folder', 'None Current', 'healthStationeryStore', 0),
(1334, 'MTL 129 HR9B', 'Geriatric In Patient', 'None Current', 'healthStationeryStore', 0),
(1335, 'MTL 130 HR14B', 'Geriatric Operation', 'None Current', 'healthStationeryStore', 0),
(1336, 'MTL 131 HR150', 'Note Child Development', 'None Current', 'healthStationeryStore', 0),
(1337, 'MTL 132 HR2B', 'Treasurer Card', 'None Current', 'healthStationeryStore', 0),
(1338, 'MTL 133 HR3B', 'Geriatric Name Index Card', 'None Current', 'healthStationeryStore', 0),
(1339, 'MTL 134', 'Geriatric Radiology Request', 'None Current', 'healthStationeryStore', 0),
(1340, 'MTL 135 HR44C', 'Geriatric Chemical Path, form C', 'None Current', 'healthStationeryStore', 0),
(1341, 'MTL 136 HR44D', 'Geriatric Chemical Path, form F', 'None Current', 'healthStationeryStore', 0),
(1342, 'MTL 137 HR46B', 'Geriatric Histopathology Log Book', 'None Current', 'healthStationeryStore', 0),
(1343, 'MTL 138 HR79B', 'Blood Transfusion', 'None Current', 'healthStationeryStore', 0),
(1344, 'MTL 139 HR87B', 'Geriatric Outpatient Register', 'None Current', 'healthStationeryStore', 0),
(1345, 'MTL 140 HR94B', 'Geriatric Spectacle Prescription', 'None Current', 'healthStationeryStore', 0),
(1346, 'MTL 141 HR101B', 'Geriatric Referral form', 'None Current', 'healthStationeryStore', 0),
(1347, 'MTL 142 H152', 'Geriatric Next of Kin Register', 'None Current', 'healthStationeryStore', 0),
(1348, 'MTL 143 HR 153', 'Geriatric Nurse ward Report', 'None Current', 'healthStationeryStore', 0),
(1349, 'MTL 144 HR156', 'Ward Control Drugs Act Record', 'None Current', 'healthStationeryStore', 0),
(1350, 'MTL 145 HR151', 'Staff Health Service Registration', 'None Current', 'healthStationeryStore', 0),
(1351, 'MTL 146 HR5B', 'Geriatrics Consultation Request form', 'None Current', 'healthStationeryStore', 0),
(1352, 'MTL 147 HR10B', 'Geriatrics fluid balance chart', 'None Current', 'healthStationeryStore', 0),
(1353, 'MTL 148 HR24B', 'Geriatrics outpatient pite pad', 'None Current', 'healthStationeryStore', 0),
(1354, 'MTL 149 HR25B', 'Geriatric Red Pag. form', 'None Current', 'healthStationeryStore', 0),
(1355, 'MTL 150 HR34C', 'Dental Continuation card', 'None Current', 'healthStationeryStore', 0),
(1356, 'MTL 151 HR36B', 'Geriatrics Haematology Request form', 'None Current', 'healthStationeryStore', 0),
(1357, 'MTL 152 HR102B', 'Geriatrics Ultrasound Request form', 'None Current', 'healthStationeryStore', 0),
(1358, 'MTL 153 HR108F', 'Geriatrics Inner page 1', 'None Current', 'healthStationeryStore', 0),
(1359, 'MTL 154 HR108G', 'Geriatrics Inner page 2', 'None Current', 'healthStationeryStore', 0),
(1360, 'MTL 155 HR148B', 'Geriatrics Clinical Documentation sheets', 'None Current', 'healthStationeryStore', 0),
(1361, 'MTL 156 HR148C', 'Geriatrics patient Follow up card', 'None Current', 'healthStationeryStore', 0),
(1362, 'MTL 157 HR16B', 'Geriatrics Anaesthesia Record', 'None Current', 'healthStationeryStore', 0),
(1363, 'MTL 158 HR158', 'Consultative out patient pads', 'None Current', 'healthStationeryStore', 0),
(1364, 'MTL 159 HR21C', 'G.O.P.D cards', 'None Current', 'healthStationeryStore', 0),
(1365, 'MTL 160 HR21D', 'G.O.P.D. Consultation', 'None Current', 'healthStationeryStore', 0),
(1366, 'MTL 161 H160A', 'Nigeria cancer', 'None Current', 'healthStationeryStore', 0),
(1367, 'MTL 162 HR160B', 'Notification card', 'None Current', 'healthStationeryStore', 0),
(1368, 'MTL 163 HR12B', 'Geriatric T.P.R chart', 'None Current', 'healthStationeryStore', 0),
(1369, 'MTL 164 H27B', 'Geriatric X-Ray jacket', 'None Current', 'healthStationeryStore', 0),
(1370, 'MTL 165 HR32B', 'Geriatric Electro Diagnosis', 'None Current', 'healthStationeryStore', 0),
(1371, 'MTL 166 HR34B', 'Geriatric Dental card', 'None Current', 'healthStationeryStore', 0),
(1372, 'MTL 167 HR39B', 'Geriatric Blood grouping & cross matching', 'None Current', 'healthStationeryStore', 0),
(1373, 'MTL 168 HR42B', 'Geriatric Blood Donor', 'None Current', 'healthStationeryStore', 0),
(1374, 'MTL 169 HR43B', 'Geriatric Micro & Para: Request Report form', 'None Current', 'healthStationeryStore', 0),
(1375, 'MTL 170 HR51B', 'Geriatric Outpatient Clinic list', 'None Current', 'healthStationeryStore', 0),
(1376, 'MTL 171 HR78B', 'Geriatric DAMA form', 'None Current', 'healthStationeryStore', 0),
(1377, 'MTL 172 HR108E', 'Geriatric Nursing process', 'None Current', 'healthStationeryStore', 0),
(1378, ' MTL 173 HR108H', 'Geriatric Inner page 3', 'None Current', 'healthStationeryStore', 0),
(1379, 'MTL174 HRB108i', 'Geriatric Inner page 4', 'None Current', 'healthStationeryStore', 0),
(1380, 'S1', 'A4 Letter Head Paper', 'Current', 'generalStationeryStore', 0),
(1381, 'S2', 'A2 Paper 80 grams', 'Current', 'generalStationeryStore', 0),
(1382, 'S3', 'Internal Memo F/C', 'Current', 'generalStationeryStore', 0),
(1383, 'S4', 'Staple Pen Remover', 'Current', 'generalStationeryStore', 0),
(1384, 'S5', 'File Tags', 'Current', 'generalStationeryStore', 0),
(1385, 'S6', 'Internal Memo Quarto', 'Current', 'generalStationeryStore', 0),
(1386, 'S7', 'Quotation Analysis', 'Current', 'generalStationeryStore', 0),
(1387, 'S8', 'Duty Roster (Weekly)', 'Current', 'generalStationeryStore', 0),
(1388, 'S9', 'Duty Roster (Allowance Sheet - Monthly)', 'Current', 'generalStationeryStore', 0),
(1389, 'S10', 'Store Requisition (S.I.V)', 'Current', 'generalStationeryStore', 0),
(1390, 'S11', 'Purchase Requisition (PR)', 'Current', 'generalStationeryStore', 0),
(1391, 'S12', 'Shorthand Note Book', 'Current', 'generalStationeryStore', 0),
(1392, 'S13', 'Maintenance Requisition (MR)', 'Current', 'generalStationeryStore', 0),
(1393, 'S14', 'Invitation  To Inspect', 'Current', 'generalStationeryStore', 0),
(1394, 'S15', 'Stamp Pad', 'Current', 'generalStationeryStore', 0),
(1395, 'S16', '12V 200 A4 Blue Ato Batteries', 'Current', 'generalStationeryStore', 0),
(1396, 'S17', 'Gum (Large)', 'Current', 'generalStationeryStore', 0),
(1397, 'S18', 'Perforator (Small)', 'Current', 'generalStationeryStore', 0),
(1398, 'S19', 'Dispatched Book', 'Current', 'generalStationeryStore', 0),
(1399, 'S20', 'Paper Clips', 'Current', 'generalStationeryStore', 0),
(1400, 'S21', 'Higher Education', 'Current', 'generalStationeryStore', 0),
(1401, 'S22', 'Store Receipt Voucher (SRV)', 'Current', 'generalStationeryStore', 0),
(1402, 'S23', 'Hard Cover', 'Current', 'generalStationeryStore', 0),
(1403, 'S24', 'ChemicalPathology Bench Book', 'Current', 'generalStationeryStore', 0),
(1404, 'S25', 'Diet Requisition', 'Current', 'generalStationeryStore', 0),
(1405, 'S26', 'Bin Card', 'Current', 'generalStationeryStore', 0),
(1406, 'S27', 'Staple Machine', 'Current', 'generalStationeryStore', 0),
(1407, 'S28', 'Typewriter Eraser with Brush', 'Current', 'generalStationeryStore', 0),
(1408, 'S29', 'Petrol  Requisition Booklet', 'Current', 'generalStationeryStore', 0),
(1409, 'S30', 'Pencil Erazer', 'Current', 'generalStationeryStore', 0),
(1410, 'S31', 'Lead Pencil', 'Current', 'generalStationeryStore', 0),
(1411, 'S32', 'Delivery Note Booklet', 'Current', 'generalStationeryStore', 0),
(1412, 'S33', 'Official Envelope', 'Current', 'generalStationeryStore', 0),
(1413, 'S34', 'Procurement Rocbk', 'Current', 'generalStationeryStore', 0),
(1414, 'S35', 'Office Pin', 'Current', 'generalStationeryStore', 0),
(1415, 'S36', 'Endorsing Ink', 'Current', 'generalStationeryStore', 0),
(1416, 'S37', 'Debit Note', 'Current', 'generalStationeryStore', 0),
(1417, 'S38', 'Drug not Imm Avail', 'Current', 'generalStationeryStore', 0),
(1418, 'S39', 'Plastic Ruler', 'Current', 'generalStationeryStore', 0),
(1419, 'S40', 'Pin Tray', 'Current', 'generalStationeryStore', 0),
(1420, 'S41', 'Furniture/Building Inventory Sheet', 'Current', 'generalStationeryStore', 0),
(1421, 'S42', 'Tipex', 'Current', 'generalStationeryStore', 0),
(1422, 'S43', 'Paper Tape', 'Current', 'generalStationeryStore', 0),
(1423, 'S44', 'Flat File', 'Current', 'generalStationeryStore', 0),
(1424, 'S45', 'Black Board Ruler', 'Current', 'generalStationeryStore', 0),
(1425, 'S46', 'Red Biro', 'Current', 'generalStationeryStore', 0),
(1426, 'S47', 'Blue Biro', 'Current', 'generalStationeryStore', 0),
(1427, 'S48', 'Black Biro', 'Current', 'generalStationeryStore', 0),
(1428, 'S49', 'Carbon Paper Fls', 'Current', 'generalStationeryStore', 0),
(1429, 'S50', '19 A Toner', 'Current', 'generalStationeryStore', 0);
INSERT INTO `allitems` (`id`, `itemcode`, `itemname`, `category`, `storesection`, `quantity`) VALUES
(1430, 'S51', 'Permanent Marker', 'Current', 'generalStationeryStore', 0),
(1431, 'S52', '17A HP Toner', 'Current', 'generalStationeryStore', 0),
(1432, 'S53', 'Manual Ribbon', 'Current', 'generalStationeryStore', 0),
(1433, 'S54', 'MX Sharp Developer', 'Current', 'generalStationeryStore', 0),
(1434, 'S55', 'Sharp Drum', 'Current', 'generalStationeryStore', 0),
(1435, 'S56', '1.5 KVA UPS', 'Current', 'generalStationeryStore', 0),
(1436, 'S57', 'Record of Service Form', 'Current', 'generalStationeryStore', 0),
(1437, 'S58', 'Twine', 'Current', 'generalStationeryStore', 0),
(1438, 'S59', 'Rubber Band', 'Current', 'generalStationeryStore', 0),
(1439, 'S60', 'P.O. Specimen', 'Current', 'generalStationeryStore', 0),
(1440, 'S61', 'Giant Staple Pin', 'Current', 'generalStationeryStore', 0),
(1441, 'S62', 'Local Purchase Order (L.P.O)', 'Current', 'generalStationeryStore', 0),
(1442, 'S63', 'Thermal Paper', 'Current', 'generalStationeryStore', 0),
(1443, 'S64', 'Cleaning Blade', 'Current', 'generalStationeryStore', 0),
(1444, 'S65', 'Payment Receipt Cashbook', 'Current', 'generalStationeryStore', 0),
(1445, 'S66', 'Annual Confidential Staff Performance Form', 'Current', 'generalStationeryStore', 0),
(1446, 'S67', 'Rubber Stamp', 'Current', 'generalStationeryStore', 0),
(1447, 'S68', 'Dated Stamp', 'Current', 'generalStationeryStore', 0),
(1448, 'S69', 'Motor Vehicle Diary', 'Current', 'generalStationeryStore', 0),
(1449, 'S70', 'Leave Approval Form', 'Current', 'generalStationeryStore', 0),
(1450, 'S71', 'Application for Leave Form', 'Current', 'generalStationeryStore', 0),
(1451, 'S72', 'Authority of Payment', 'Current', 'generalStationeryStore', 0),
(1452, 'S73', 'Sharpeners', 'Current', 'generalStationeryStore', 0),
(1453, 'S74', '35A Toner (Computer)', 'Current', 'generalStationeryStore', 0),
(1454, 'S75', 'Large Envelope', 'Current', 'generalStationeryStore', 0),
(1455, 'S76', 'Envelope 8 X 10 (Small)', 'Current', 'generalStationeryStore', 0),
(1456, 'S77', 'Gate Pass', 'Current', 'generalStationeryStore', 0),
(1457, 'S78', 'Patient Property Book', 'Current', 'generalStationeryStore', 0),
(1458, 'S79', 'Ledger Sheet', 'Current', 'generalStationeryStore', 0),
(1459, 'S80', 'Laundry Book', 'Current', 'generalStationeryStore', 0),
(1460, 'S81', 'Medical Examination Certificate Form', 'Current', 'generalStationeryStore', 0),
(1461, 'S82', 'Store Issue Voucher Dispatch Sheet', 'Current', 'generalStationeryStore', 0),
(1462, 'S83', 'Stock Replenishment Sheet', 'Current', 'generalStationeryStore', 0),
(1463, 'S84', 'Chemical Pathology Day Book Sheet', 'Current', 'generalStationeryStore', 0),
(1464, 'S85', 'NHS Clearance Form', 'Current', 'generalStationeryStore', 0),
(1465, 'S86', 'NHS Prescription Sheet', 'Current', 'generalStationeryStore', 0),
(1466, 'S87', 'NHS Referred Form', 'Current', 'generalStationeryStore', 0),
(1467, 'S88', '05A Computer Toner', 'Current', 'generalStationeryStore', 0),
(1468, 'S89', 'Payment Voucher', 'Current', 'generalStationeryStore', 0),
(1469, 'S90', 'Flash Drive', 'Current', 'generalStationeryStore', 0),
(1470, 'S91', 'USB Cord with Mouse', 'Current', 'generalStationeryStore', 0),
(1471, 'S92', 'Nurses Ward Report', 'Current', 'generalStationeryStore', 0),
(1472, 'S93', '53A Computer Toner', 'Current', 'generalStationeryStore', 0),
(1473, 'S94', '15A Computer Toner', 'Current', 'generalStationeryStore', 0),
(1474, 'S95', '49A Computer Toner', 'Current', 'generalStationeryStore', 0),
(1475, 'S96', 'Letter Headed Paper PLS', 'Current', 'generalStationeryStore', 0),
(1476, 'S97', 'Carbonized Revenue Receipt', 'Current', 'generalStationeryStore', 0),
(1477, 'S98', 'Purchasing Assistant Ledger', 'Current', 'generalStationeryStore', 0),
(1478, 'S99', 'Brown Paper', 'Current', 'generalStationeryStore', 0),
(1479, 'S100', 'Computer', 'Current', 'generalStationeryStore', 0),
(1480, 'S101', '1.2 KVA UPS (Korner Stone)', 'Current', 'generalStationeryStore', 0),
(1481, 'S102', 'A4 Photocopy Paper', 'Current', 'generalStationeryStore', 0),
(1482, 'S103', 'Analysis Revenue Cash Book', 'Current', 'generalStationeryStore', 0),
(1483, 'S104', 'Analysis Revolving Cash Book', 'Current', 'generalStationeryStore', 0),
(1484, 'S105', 'Revolving Fund Receipt & Payment Cash Book', 'Current', 'generalStationeryStore', 0),
(1485, 'S106', 'Filing Cabinet', 'Current', 'generalStationeryStore', 0),
(1486, 'S107', 'Laminated Plastic I.D Card with Lanyard', 'Current', 'generalStationeryStore', 0),
(1487, 'S108', 'Reico File', 'Current', 'generalStationeryStore', 0),
(1488, 'S109', 'Staple Pin No. 56', 'Current', 'generalStationeryStore', 0),
(1489, 'S110', 'Printer HP 1102', 'Current', 'generalStationeryStore', 0),
(1490, 'S111', 'Photocopy Toner AR270F9', 'Current', 'generalStationeryStore', 0),
(1491, 'S112', '12A Toner', 'Current', 'generalStationeryStore', 0),
(1492, 'S113', '85A Toner', 'Current', 'generalStationeryStore', 0),
(1493, 'S114', 'Dialysis chart', 'Current', 'generalStationeryStore', 0),
(1494, 'S115', 'Hand Set (M OKIA 1280)', 'Current', 'generalStationeryStore', 0),
(1495, 'S116', 'Kaolic Calculator Digit KD 3388', 'Current', 'generalStationeryStore', 0),
(1496, 'S117', 'Giant Perforator', 'Current', 'generalStationeryStore', 0),
(1497, 'S118', 'Giant Stapler', 'Current', 'generalStationeryStore', 0),
(1498, 'S119', 'Nylon Cellotape 3\"', 'Current', 'generalStationeryStore', 0),
(1499, 'S120', '2PLY 132 Column', 'Current', 'generalStationeryStore', 0),
(1500, 'S121', '3000 APC UPS', 'Current', 'generalStationeryStore', 0),
(1501, 'S122', 'Clear Bag', 'Current', 'generalStationeryStore', 0),
(1502, 'S123', 'Brown Envelope', 'Current', 'generalStationeryStore', 0),
(1503, 'S124', '60 Leaves Notebook', 'Current', 'generalStationeryStore', 0),
(1504, 'S125', 'Digitalized Sharp Photocopier Machine', 'Current', 'generalStationeryStore', 0),
(1505, 'S126', 'Ruled Sheet', 'Current', 'generalStationeryStore', 0),
(1506, 'S127', 'Zinox Pro Laptop', 'Current', 'generalStationeryStore', 0),
(1507, 'S128', 'Executive Laptop Bag', 'Current', 'generalStationeryStore', 0),
(1508, 'S129', 'Wooden Ruler', 'Current', 'generalStationeryStore', 0),
(1509, 'S130', 'USB Keyboard', 'Current', 'generalStationeryStore', 0),
(1510, 'S131', 'Photocopier Developer', 'Current', 'generalStationeryStore', 0),
(1511, 'S132', 'F/S Photocopy paper', 'Current', 'generalStationeryStore', 0),
(1512, 'S133', 'DVEA  Booklets', 'Current', 'generalStationeryStore', 0),
(1513, 'S134', 'Cardboard Paper', 'Current', 'generalStationeryStore', 0),
(1514, 'S135', '80A Computer Toner', 'Current', 'generalStationeryStore', 0),
(1515, 'S136', 'Interactive White Board 80\"', 'Current', 'generalStationeryStore', 0),
(1516, 'S137', '80 Leaves Note Book', 'Current', 'generalStationeryStore', 0),
(1517, 'S138', '36A Toner', 'Current', 'generalStationeryStore', 0),
(1518, 'S139', 'Green File', 'Current', 'generalStationeryStore', 0),
(1519, 'S140', '59A Toner', 'Current', 'generalStationeryStore', 0),
(1520, 'S141', 'Printer Cable', 'Current', 'generalStationeryStore', 0),
(1521, 'S142', 'Mouse', 'Current', 'generalStationeryStore', 0),
(1522, 'S143', '26A Toner', 'Current', 'generalStationeryStore', 0),
(1523, 'S144', 'Photocopiers Toner cartridge', 'Current', 'generalStationeryStore', 0),
(1524, 'S145', 'Carbonized Delivery Note', 'Current', 'generalStationeryStore', 0),
(1525, 'S146', 'Carbonized Oxygen Request Form', 'Current', 'generalStationeryStore', 0),
(1526, 'S147', 'Released Note', 'Current', 'generalStationeryStore', 0),
(1527, 'S148', 'Carbonized Stores Disposal Transfer Form', 'Current', 'generalStationeryStore', 0),
(1528, 'S149', 'Desktop Computer', 'Current', 'generalStationeryStore', 0),
(1529, 'S150', 'Microsoft Webcam', 'Current', 'generalStationeryStore', 0),
(1530, 'S151', 'Digital Persona U', 'Current', 'generalStationeryStore', 0),
(1531, 'S152', 'Zinox Battery', 'Current', 'generalStationeryStore', 0),
(1532, 'S153', 'Computer Monitor', 'Current', 'generalStationeryStore', 0),
(1533, 'S154', 'Camera', 'Current', 'generalStationeryStore', 0),
(1534, 'S155', 'Hard Drive', 'Current', 'generalStationeryStore', 0),
(1535, 'S156', '106A Toner', 'Current', 'generalStationeryStore', 0),
(1536, 'S157', 'HPe VL380 G10 4210R 2800W Power with Operating', 'Current', 'generalStationeryStore', 0),
(1537, 'S158', 'Mikrotik Long Range Dual Band Access Point', 'Current', 'generalStationeryStore', 0),
(1538, 'S159', 'Mikrotik CCR1036-12G-4S Printer', 'Current', 'generalStationeryStore', 0),
(1539, 'S160', 'Access Point TP Link', 'Current', 'generalStationeryStore', 0),
(1540, 'S161', 'Samsung Tab', 'Current', 'generalStationeryStore', 0),
(1541, 'S162', 'Mikrotik Router Server', 'Current', 'generalStationeryStore', 0),
(1542, 'S163', 'Computer Accessories', 'Current', 'generalStationeryStore', 0),
(1543, 'S164', 'Blood Donor Card', 'Current', 'generalStationeryStore', 0),
(1544, 'HC001', '   Omo in Bags', 'Current', 'hardwareStore', 0),
(1545, 'HC002', 'Biohazard safety Box', 'Current', 'hardwareStore', 0),
(1546, 'HC003', 'Ceilling Brush', 'Current', 'hardwareStore', 0),
(1547, 'HC004', 'Sweeping Brush small/Big', 'Current', 'hardwareStore', 0),
(1548, 'HC005', 'Hand washing liquid soap', 'Current', 'hardwareStore', 0),
(1549, 'HC006', 'Toilet Rolls', 'Current', 'hardwareStore', 0),
(1550, 'HC007', 'Moringard Germicidal gallon', 'Current', 'hardwareStore', 0),
(1551, 'HC008', 'Robbin Blue', 'Current', 'hardwareStore', 0),
(1552, 'HC009', 'Gutter Brush', 'Current', 'hardwareStore', 0),
(1553, 'HC010', 'Bleach (25 ltrs)', 'Current', 'hardwareStore', 0),
(1554, 'HC011', 'Toilet Soap', 'Current', 'hardwareStore', 0),
(1555, 'HC012', 'Bleach (5 ltrs)', 'Current', 'hardwareStore', 0),
(1556, 'HC013', 'Stain Remover (5 ltrs)', 'Current', 'hardwareStore', 0),
(1557, 'HC014', 'Liquid Detergent (5 ltrs)', 'Current', 'hardwareStore', 0),
(1558, 'HC015', 'Sodium Hydrosulphate (50kg)', 'Current', 'hardwareStore', 0),
(1559, 'HC016', ' Harpic Bottle', 'Current', 'hardwareStore', 0),
(1560, 'HC017', 'Vim', 'Current', 'hardwareStore', 0),
(1561, 'HC018', 'Small plastic Bowls', 'Current', 'hardwareStore', 0),
(1562, 'HC019', 'Rain Boot', 'Current', 'hardwareStore', 0),
(1563, 'HC020', 'Rain Coat', 'Current', 'hardwareStore', 0),
(1564, 'HC021', 'Curtain Rod Accessory', 'Current', 'hardwareStore', 0),
(1565, 'HC022', 'Hand Brush/Scrubbing Brush/Stick', 'Current', 'hardwareStore', 0),
(1566, 'HC023', 'Mopping Pail', 'Current', 'hardwareStore', 0),
(1567, 'HC024', 'Termex', 'Current', 'hardwareStore', 0),
(1568, 'HC025', 'Duracell Battery (R4)', 'Current', 'hardwareStore', 0),
(1569, 'HC026', 'Biohazard Bag', 'Current', 'hardwareStore', 0),
(1570, 'HC027', 'Leather Hand Glove', 'Current', 'hardwareStore', 0),
(1571, 'HC028', 'Knapsack spraying machine', 'Current', 'hardwareStore', 0),
(1572, 'HC029', 'Packer', 'Current', 'hardwareStore', 0),
(1573, 'HC030', 'Forceup Herbicide', 'Current', 'hardwareStore', 0),
(1574, 'HC031', 'Big Umbrella', 'Current', 'hardwareStore', 0),
(1575, 'HC032', 'Ziplock', 'Current', 'hardwareStore', 0),
(1576, 'HC033', 'Tooth Paste', 'Current', 'hardwareStore', 0),
(1577, 'HC034', 'Dusting powder', 'Current', 'hardwareStore', 0),
(1578, 'HC035', 'Bottle Brush large', 'Current', 'hardwareStore', 0),
(1579, 'HC36', 'Bottle Brush, Small', 'Current', 'hardwareStore', 0),
(1580, 'HC37', 'Toilet Brush', 'Current', 'hardwareStore', 0),
(1581, 'HC38', 'Plastic Sieve', 'Current', 'hardwareStore', 0),
(1582, 'HC39', 'Morigard Lyzol', 'Current', 'hardwareStore', 0),
(1583, 'HC40', 'Aquadana Table Water', 'NoneCurrent', 'hardwareStore', 0),
(1584, 'HC41', '12.5kg Gas', 'NoneCurrent', 'hardwareStore', 0),
(1585, 'HC42', '50kg Gas Refill', 'Current', 'hardwareStore', 0),
(1586, 'HC43', 'Morigad Germicidal (25 litres)', 'Current', 'hardwareStore', 0),
(1587, 'HC44', 'B/D. Heavily Soiled Liner (25 litres)', 'Current', 'hardwareStore', 0),
(1588, 'HC45', 'Sodium Carbonate Soda Ash 50kg', 'Current', 'hardwareStore', 0),
(1589, 'HC46', 'Sodium Hydroxide Caustic Soda', 'Current', 'hardwareStore', 0),
(1590, 'HC47', 'Chlorine', 'Current', 'hardwareStore', 0),
(1591, 'HC48', 'Wall Clock', 'Current', 'hardwareStore', 0),
(1592, 'HC49', 'Fan Rack', 'Current', 'hardwareStore', 0),
(1593, 'HC50', 'Mop Head', 'Current', 'hardwareStore', 0),
(1594, 'HC51', 'Pedal Dustbin', 'Current', 'hardwareStore', 0),
(1595, 'HC52', 'Big Plastic Bucket', 'Current', 'hardwareStore', 0),
(1596, 'HC53', 'Original Grounding Stone', 'NoneCurrent', 'hardwareStore', 0),
(1597, 'HC54', 'Big Soup Spoon', 'NoneCurrent', 'hardwareStore', 0),
(1598, 'HC55', 'LPD Cooking Gas', 'NoneCurrent', 'hardwareStore', 0),
(1599, 'HC56', 'Jik Disinfectant (1 litre/500ml)', 'Current', 'hardwareStore', 0),
(1600, 'HC57', 'Local Broom', 'Current', 'hardwareStore', 0),
(1601, 'HC58', 'Foot Mat', 'Current', 'hardwareStore', 0),
(1602, 'HC59', 'Tooth Pick / Meat Pick', 'Current', 'hardwareStore', 0),
(1603, 'HC60', 'Air Freshener / Air Spray', 'Current', 'hardwareStore', 0),
(1604, 'HC61', 'Aluminium Cooking Pot', 'NoneCurrent', 'hardwareStore', 0),
(1605, 'HC62', 'Plastic Jug', 'Current', 'hardwareStore', 0),
(1606, 'HC63', 'Heavy Duty Refuse Sacks', 'Current', 'hardwareStore', 0),
(1607, 'HC64', 'Table Salt', 'Current', 'hardwareStore', 0),
(1608, 'HC65', 'Knives', 'NoneCurrent', 'hardwareStore', 0),
(1609, 'HC66', 'Turning Stick (Big/Small)', 'Current', 'hardwareStore', 0),
(1610, 'HC67', 'Cooler (5 litres & 5 litres)', 'NoneCurrent', 'hardwareStore', 0),
(1611, 'HC68', 'Basin Big', 'Current', 'hardwareStore', 0),
(1612, 'HC69', 'Table Fork', 'NoneCurrent', 'hardwareStore', 0),
(1613, 'HC70', 'Plate', 'NoneCurrent', 'hardwareStore', 0),
(1614, 'HC71', 'Plastic', 'Current', 'hardwareStore', 0),
(1615, 'HC72', 'Carboxylic Acid', 'Current', 'hardwareStore', 0),
(1616, 'HC73', 'Ethanol', 'Current', 'hardwareStore', 0),
(1617, 'HC74', 'Gel', 'Current', 'hardwareStore', 0),
(1618, 'HC75', 'Sulphuric Acid', 'Current', 'hardwareStore', 0),
(1619, 'HC76', 'Nitrolsol', 'Current', 'hardwareStore', 0),
(1620, 'HC77', 'Colour', 'Current', 'hardwareStore', 0),
(1621, 'HC78', 'TEA', 'Current', 'hardwareStore', 0),
(1622, 'HC79', 'Carbonpol', 'Current', 'hardwareStore', 0),
(1623, 'HC80', 'Safety Helmet/Transparent Protective Glass/Googles/Earmuffs', 'Current', 'hardwareStore', 0),
(1624, 'HC81', 'Texapoh', 'Current', 'hardwareStore', 0),
(1625, 'HC82', 'Phenol', 'Current', 'hardwareStore', 0),
(1626, 'HC83', 'Pine Oil', 'Current', 'hardwareStore', 0),
(1627, 'HC84', 'Whitener', 'Current', 'hardwareStore', 0),
(1628, 'HC85', 'Glycerol', 'Current', 'hardwareStore', 0),
(1629, 'HC86', 'Hydrogen Peroxide', 'Current', 'hardwareStore', 0),
(1630, 'HC87', 'Perfume Lemon', 'Current', 'hardwareStore', 0),
(1631, 'HC88', 'Foaming Boaster', 'Current', 'hardwareStore', 0),
(1632, 'HC89', 'Formalin', 'Current', 'hardwareStore', 0),
(1633, 'HC90', 'Glycerin', 'Current', 'hardwareStore', 0),
(1634, 'HC91', 'Hypochloride', 'Current', 'hardwareStore', 0),
(1635, 'HC92', 'Pruning Secators', 'Current', 'hardwareStore', 0),
(1636, 'HC93', 'Trimming Scissors', 'Current', 'hardwareStore', 0),
(1637, 'HC94', 'Chlorozyml', 'Current', 'hardwareStore', 0),
(1638, 'HC95', 'Candle', 'Current', 'hardwareStore', 0),
(1639, 'HC96', 'Rubber Slippers', 'Current', 'hardwareStore', 0),
(1640, 'HC97', 'Kitchen Weighing Scale', 'NoneCurrent', 'hardwareStore', 0),
(1641, 'HC98', 'Table Spoon', 'NoneCurrent', 'hardwareStore', 0),
(1642, 'F 01', 'Tumor Executive Chair with Arms', 'NoneCurrent', 'hardwareStore', 0),
(1643, 'F02', 'Plastic Chair', 'NoneCurrent', 'hardwareStore', 0),
(1644, 'F03', 'Senior Executive Table with Drawers', 'NoneCurrent', 'hardwareStore', 0),
(1645, 'F04', 'Pillow', 'NoneCurrent', 'hardwareStore', 0),
(1646, 'F05', '3 1/3x6 Bed', 'NoneCurrent', 'hardwareStore', 0),
(1647, 'F06', 'Junior Executive Chair without Arms', 'NoneCurrent', 'hardwareStore', 0),
(1648, 'F07', 'Padded Airport Chair', 'NoneCurrent', 'hardwareStore', 0),
(1649, 'F08', 'Single Easy Chair', 'NoneCurrent', 'hardwareStore', 0),
(1650, 'F09', 'Swivel Chair', 'NoneCurrent', 'hardwareStore', 0),
(1651, 'F10', 'Anaesthetist Chair/Laboratory Seats', 'NoneCurrent', 'hardwareStore', 0),
(1652, 'F11', 'Acada Chair', 'NoneCurrent', 'hardwareStore', 0),
(1653, 'F12', 'Writing Table/Tumor Executive Table', 'NoneCurrent', 'hardwareStore', 0),
(1654, 'F13', 'Centre Table', 'NoneCurrent', 'hardwareStore', 0),
(1655, 'F14', 'Plastic Table', 'NoneCurrent', 'hardwareStore', 0),
(1656, 'F15', '2½x6 Mattress', 'NoneCurrent', 'hardwareStore', 0),
(1657, 'F16', '3¾x6 Mattress', 'NoneCurrent', 'hardwareStore', 0),
(1658, 'F17', '4½x6 Mattress', 'NoneCurrent', 'hardwareStore', 0),
(1659, 'F18', 'Patient Mattress 3½x6', 'NoneCurrent', 'hardwareStore', 0),
(1660, 'F19', '4½x6 Wooden Bed', 'NoneCurrent', 'hardwareStore', 0),
(1661, 'F20', 'Executive Chair Senior', 'NoneCurrent', 'hardwareStore', 0),
(1662, 'F21', 'Easy Chair Double Seater', 'NoneCurrent', 'hardwareStore', 0),
(1663, 'F22', 'Padded Wooden Long Bench', 'NoneCurrent', 'hardwareStore', 0),
(1664, 'BLU01', 'White Bed Sheet Printed (pcs)', 'Current', 'hardwareStore', 0),
(1665, 'BLU02', 'Pillow Case', 'Current', 'hardwareStore', 0),
(1666, 'BLU03', 'Curtain Materials (Window Blind)', 'Current', 'hardwareStore', 0),
(1667, 'BLU04', 'Children Cot Bed Sheet Printed', 'Current', 'hardwareStore', 0),
(1668, 'BLU05', 'Infant Cot Bed Sheet Printed', 'Current', 'hardwareStore', 0),
(1669, 'BLU06', 'Trouser & Conductor', 'Current', 'hardwareStore', 0),
(1670, 'BLU07', 'Blanket', 'Current', 'hardwareStore', 0),
(1671, 'BLU08', 'Face Towel', 'Current', 'hardwareStore', 0),
(1672, 'BLU09', 'Bathroom Towel', 'Current', 'hardwareStore', 0),
(1673, 'BLU10', 'Bed Sheet ½ Colour', 'Current', 'hardwareStore', 0),
(1674, 'BLU11', 'Mosquito Net', 'Current', 'hardwareStore', 0),
(1675, 'BLU12', 'Draw Sheet', 'Current', 'hardwareStore', 0),
(1676, 'BLU13', 'Yellow Teregal Yards', 'Current', 'hardwareStore', 0),
(1677, 'BLU14', 'Calico Baft Yards', 'Current', 'hardwareStore', 0),
(1678, 'BLU15', 'National Flag', 'Current', 'hardwareStore', 0),
(1679, 'BLU16', 'Green Materials Check', 'Current', 'hardwareStore', 0),
(1680, 'BLU17', 'Crown Thread White', 'Current', 'hardwareStore', 0),
(1681, 'BLU18', 'Red Thread', 'Current', 'hardwareStore', 0),
(1682, 'BLU19', 'Big Button for Coat', 'Current', 'hardwareStore', 0),
(1683, 'BLU20', 'Brown Tregals', 'Current', 'hardwareStore', 0),
(1684, 'BLU21', 'Drivers Uniform', 'Current', 'hardwareStore', 0),
(1685, 'BLU22', 'Mechanics Uniform', 'Current', 'hardwareStore', 0),
(1686, 'BLU23', 'Curtains Spring with Hook', 'Current', 'hardwareStore', 0),
(1687, 'BLU24', 'Apron with Logo/Dresses Overall/Baby Gown', 'Current', 'hardwareStore', 0),
(1688, 'BLU25', 'Blue Cloth Material', 'Current', 'hardwareStore', 0),
(1689, 'BLU26', 'Overall', 'Current', 'hardwareStore', 0),
(1690, 'BLU27', 'Curtain Tape', 'Current', 'hardwareStore', 0),
(1691, 'BLU28', 'Machine Accessory', 'Current', 'hardwareStore', 0),
(1692, 'MS 001', 'Sputum mug cup stainless', 'Current', 'medicalStore', 0),
(1693, 'MS002', 'Chest Holder', 'Current', 'medicalStore', 0),
(1694, 'MS003', 'Humidifier oxygen concentrator', 'Current', 'medicalStore', 0),
(1695, 'MS004', 'Desuction Sphygmomanometer', 'Current', 'medicalStore', 0),
(1696, 'MS005', 'Medicine Cup', 'Current', 'medicalStore', 0),
(1697, 'MS006', 'Flowmeter', 'Current', 'medicalStore', 0),
(1698, 'MS007', 'Portable Monitor', 'Current', 'medicalStore', 0),
(1699, 'MS008', 'Laryngoscope set with Blade', 'Current', 'medicalStore', 0),
(1700, 'MS009', 'Urinal', 'Current', 'medicalStore', 0),
(1701, 'MS010', 'Hospital Bed', 'Current', 'medicalStore', 0),
(1702, 'MS011', 'Hospital Mattresses', 'Current', 'medicalStore', 0),
(1703, 'MS012', 'Bed Side Locker', 'Current', 'medicalStore', 0),
(1704, 'MS013', 'Instrument Trolley', 'Current', 'medicalStore', 0),
(1705, 'MS014', 'Portable Screen', 'Current', 'medicalStore', 0),
(1706, 'MS015', 'Pure Test Free Sanitizer Dispenser', 'Current', 'medicalStore', 0),
(1707, 'MS016', 'Oxygen Head Complete', 'Current', 'medicalStore', 0),
(1708, 'MS017', 'Multiparameter Patient Monitor', 'Current', 'medicalStore', 0),
(1709, 'MS018', 'Diagnostic Set', 'Current', 'medicalStore', 0),
(1710, 'MS019', 'Nebulizer for Oxygen', 'Current', 'medicalStore', 0),
(1711, 'MS020', 'Clinical Thermometer', 'Current', 'medicalStore', 0),
(1712, 'MS021', 'Medium Hospital Mattresses', 'Current', 'medicalStore', 0),
(1713, 'MS022', 'Mini Baby Cot Mattresses', 'Current', 'medicalStore', 0),
(1714, 'MS023', 'Swing Baby Cot Mattresses', 'Current', 'medicalStore', 0),
(1715, 'MS024', 'Hospital Wheel Chair', 'Current', 'medicalStore', 0),
(1716, 'MS025', 'Kidney Dish', 'Current', 'medicalStore', 0),
(1717, 'MS026', 'Bowl Flawless Various Sizes', 'Current', 'medicalStore', 0),
(1718, 'MS027', 'Ambu Bag Children size', 'Current', 'medicalStore', 0),
(1719, 'MS028', 'Surgical Shadowless Lamp Bulb', 'Current', 'medicalStore', 0),
(1720, 'MS029', 'X-ray Viewing Box', 'Current', 'medicalStore', 0),
(1721, 'MS030', 'Intravenous Infusion Pump', 'Current', 'medicalStore', 0),
(1722, 'MS031', 'Hand Dryer Machine', 'Current', 'medicalStore', 0),
(1723, 'MS032', 'Cut down set', 'Current', 'medicalStore', 0),
(1724, 'MS033', 'Sterilizing Drum', 'Current', 'medicalStore', 0),
(1725, 'MS034', 'Glucometer with Strip', 'Current', 'medicalStore', 0),
(1726, 'MS035', 'Ultra Sound Machine & Transducer', 'Current', 'medicalStore', 0),
(1727, 'MS036', 'Infrared Thermometer', 'Current', 'medicalStore', 0),
(1728, 'MS037', 'Pulse oximeter', 'Current', 'medicalStore', 0),
(1729, 'MS038', 'Ventilator with Accessories', 'Current', 'medicalStore', 0),
(1730, 'MS039', 'Oxygen Concentrator', 'Current', 'medicalStore', 0),
(1731, 'MS040', 'E C G Machine & Electrocardiogram', 'Current', 'medicalStore', 0),
(1732, 'MCD001', 'Sterile Gloves Size 7½', 'Current', 'medicalStore', 0),
(1733, 'MCD002', 'Sterile Gloves Size 8', 'Current', 'medicalStore', 0),
(1734, 'MCD003', 'Surgical Blade Size 12', 'Current', 'medicalStore', 0),
(1735, 'MCD004', 'Surgical Blade Size 14', 'Current', 'medicalStore', 0),
(1736, 'MCD005', 'EC G Paper', 'Current', 'medicalStore', 0),
(1737, 'MCD006', 'Sterile Gloves Size 7', 'Current', 'medicalStore', 0),
(1738, 'MCD007', 'Surgical Disposable Gown', 'Current', 'medicalStore', 0),
(1739, 'MCD008', 'Surgical Blade Size 24', 'Current', 'medicalStore', 0),
(1740, 'MCD009', 'Surgical Blade Size 21', 'Current', 'medicalStore', 0),
(1741, 'MCD010', 'Surgical Blade Size 22', 'Current', 'medicalStore', 0),
(1742, 'MCD011', '5ML Needle & Syringe', 'Current', 'medicalStore', 0),
(1743, 'MCD012', '10ML Needle & Syringe', 'Current', 'medicalStore', 0),
(1744, 'MCD013', 'Latex Gloves', 'Current', 'medicalStore', 0),
(1745, 'MCD014', '2ML Needle & Syringe', 'Current', 'medicalStore', 0),
(1746, 'MCD015', 'Pure II Sanitizer Gel', 'Current', 'medicalStore', 0),
(1747, 'MCD016', 'Oxygen Face Mask Adult', 'Current', 'medicalStore', 0),
(1748, 'MCD017', '20 ML Needle & Syringe', 'Current', 'medicalStore', 0),
(1749, 'MCD018', 'EC G Monitoring Electrode', 'Current', 'medicalStore', 0),
(1750, 'MCD019', 'Reusable Airways 3/4', 'Current', 'medicalStore', 0),
(1751, 'MCD020', 'Disposable Vaginal Speculum', 'Current', 'medicalStore', 0),
(1752, 'MCD021', 'Electrolyte Tube', 'Current', 'medicalStore', 0),
(1753, 'MCD022', 'Water Resistant Gown (Tyvek)', 'Current', 'medicalStore', 0),
(1754, 'MCD023', 'Water Resistant Boots Shoe', 'Current', 'medicalStore', 0),
(1755, 'MCD024', 'Water Resistant Goggle', 'Current', 'medicalStore', 0),
(1756, 'MCD025', 'Nose Cover / Face Mask', 'Current', 'medicalStore', 0),
(1757, 'MCD026', 'Disposable Elbows Hand Gloves', 'Current', 'medicalStore', 0),
(1758, 'MCD027', 'Infant Cannula Tube', 'Current', 'medicalStore', 0),
(1759, 'MCD028', 'Medical Tornique', 'Current', 'medicalStore', 0),
(1760, 'MCD029', 'Cannula Blue & Yellow', 'Current', 'medicalStore', 0),
(1761, 'MCD030', 'Surgical Blade Size 20', 'Current', 'medicalStore', 0),
(1762, 'MCD031', 'Nose Cover (KN95)', 'Current', 'medicalStore', 0),
(1763, 'MCD032', 'Disposable Apron (Long Sleeve)', 'Current', 'medicalStore', 0),
(1764, 'MCD033', 'Body Bag', 'Current', 'medicalStore', 0),
(1765, 'MCD034', 'Disposable Nurse\'s Cap', 'Current', 'medicalStore', 0),
(1766, 'MCD035', '100 MLs Hand Sanitizer', 'Current', 'medicalStore', 0),
(1767, 'MCD036', '500MLs Hand Sanitizer', 'Current', 'medicalStore', 0),
(1768, 'MCD037', 'Non Cuffed Endotracheal Tube', 'Current', 'medicalStore', 0),
(1769, 'MCD038', 'Ten 20 ECG Conductive Paste', 'Current', 'medicalStore', 0),
(1770, 'MCD039', 'Shoe Cover (Pairs)', 'Current', 'medicalStore', 0),
(1771, 'MCD040', 'Patient Gown Cotton', 'Current', 'medicalStore', 0),
(1772, 'MCD041', 'Head Cover Cotton', 'Current', 'medicalStore', 0),
(1773, 'MCD042', 'Scrub', 'Current', 'medicalStore', 0),
(1774, 'MCD043', 'Face Shield', 'Current', 'medicalStore', 0),
(1775, 'DTC 01', 'Purit Disinfectant', 'Current', 'medicalStore', 0),
(1776, 'DTC 02', 'Leader Liquid Hand Wash', 'Current', 'medicalStore', 0),
(1777, 'DTC 03', 'Dental Stone', 'Current', 'medicalStore', 0),
(1778, 'DTC 04', 'Dental Cotton Wool', 'Current', 'medicalStore', 0),
(1779, 'DTC 05', 'Normal Saline', 'Current', 'medicalStore', 0),
(1780, 'DTC 06', 'Dental Latex Gloves', 'Current', 'medicalStore', 0),
(1781, 'DTC 07', 'Dental Cotton Wool (Roll)', 'Current', 'medicalStore', 0),
(1782, 'DTC 08', 'Face Mask', 'Current', 'medicalStore', 0),
(1783, 'DTC 09', 'Disposable Needles and Syringe (Bdm)', 'Current', 'medicalStore', 0),
(1784, 'DTC 10', 'Disposable Needle & Syringe (10 ml)', 'Current', 'medicalStore', 0),
(1785, 'DTC 11', 'Tooth Coloured Acrylic Powder (Cured)', 'Current', 'medicalStore', 0),
(1786, 'DTC 12              ', 'Rubber base impression materials', 'Current', 'medicalStore', 0),
(1787, 'DTC 13', 'Chlorohexidine Gluconate Solution', 'Current', 'medicalStore', 0),
(1788, 'DTC 14', 'Surgical Blade No 15', 'Current', 'medicalStore', 0),
(1789, 'DTC 15', 'Glass Ionomer Cement (GIC)', 'Current', 'medicalStore', 0),
(1790, 'DTC 16', 'Heat Cure Pink Acrylic (4L)', 'Current', 'medicalStore', 0),
(1791, 'DTC 17', 'Dental Needle (Short)', 'Current', 'medicalStore', 0),
(1792, 'DTC 18', 'Dental Needle (Long)', 'Current', 'medicalStore', 0),
(1793, 'DTC 19', 'Amalgam Capsule', 'Current', 'medicalStore', 0),
(1794, 'DTC 20', 'Stick & Sticky Wax', 'Current', 'medicalStore', 0),
(1795, 'DTC 21', 'Stainless Steel Wire', 'Current', 'medicalStore', 0),
(1796, 'DTC 22', 'Wax Clasp Profile', 'Current', 'medicalStore', 0),
(1797, 'DTC 23', 'Preparation Wax', 'Current', 'medicalStore', 0),
(1798, 'DTC 24', 'wironut Wire Round (0.9mm)', 'Current', 'medicalStore', 0),
(1799, 'DTC 25', 'Surgical Blade No 12', 'Current', 'medicalStore', 0),
(1800, 'DTC 26', 'Dental Gauze Roll', 'Current', 'medicalStore', 0),
(1801, 'DTC 27', 'Xylocaine Injection', 'Current', 'medicalStore', 0),
(1802, 'DTC 28', 'Dental Plaster', 'Current', 'medicalStore', 0),
(1803, 'DTC 29', 'Methylated Spirit', 'Current', 'medicalStore', 0),
(1804, 'DTC 30', 'Modelling Wax 500g Powder', 'Current', 'medicalStore', 0),
(1805, 'DTC 31', 'Alginate Impression Material', 'Current', 'medicalStore', 0),
(1806, 'DTC 32', 'X-Ray Dental Film (Periapical)', 'Current', 'medicalStore', 0),
(1807, 'DTC 33', 'Cold Cure Acrylic powder/liquid', 'Current', 'medicalStore', 0),
(1808, 'DTC 34', 'Acrylic Containing and Finishing Kit', 'Current', 'medicalStore', 0),
(1809, 'DTC 35', '', 'Current', 'medicalStore', 0),
(1810, 'DTC 36', 'Surgical Glove Size 8', 'Current', 'medicalStore', 0),
(1811, 'DTC 37', 'Sterilization pouch', 'Current', 'medicalStore', 0),
(1812, 'DTC 38', 'NG Tube Size 16', 'Current', 'medicalStore', 0),
(1813, 'DTC 39', 'Medilux Teeth Upper Anterior/Interior (Shade A1/A3)', 'Current', 'medicalStore', 0),
(1814, 'DTC 40', 'Medilux Teeth Upper Anterior/Interior (Shade B1-B3)', 'Current', 'medicalStore', 0),
(1815, 'DTC 41', 'Medilux Teeth Upper Anterior/Interior (Shade C1-C3)', 'Current', 'medicalStore', 0),
(1816, 'DTC 42', 'Fluoride Gel', 'Current', 'medicalStore', 0),
(1817, 'DTC 43', 'Reamers 15-40 (N.Ti.) (Nickel Titanium)', 'Current', 'medicalStore', 0),
(1818, 'DTC 44', 'Astra Xylocaine Spray 50ml', 'Current', 'medicalStore', 0),
(1819, 'DTC 45', 'Formacresol', 'Current', 'medicalStore', 0),
(1820, 'DTC 46', 'Prophylaxis paste kemdent strawberry & original  200G', 'Current', 'medicalStore', 0),
(1821, 'DTC 47', 'Paper Point Sizes 45-80', 'Current', 'medicalStore', 0),
(1822, 'DTC 48', 'Paper Point 15-40', 'Current', 'medicalStore', 0),
(1823, 'DTC 49', 'Light Cure Composite', 'Current', 'medicalStore', 0),
(1824, 'DTC 50', 'Developer (Powder Type)', 'Current', 'medicalStore', 0),
(1825, 'DTC 51', 'Fixer (Powder Type) primax', 'Current', 'medicalStore', 0),
(1826, 'DTC 52', 'Mouthwash Tablets', 'Current', 'medicalStore', 0),
(1827, 'DTC 53', 'Reamers 45-80', 'Current', 'medicalStore', 0),
(1828, 'DTC 54', '0.5 Stainless Hire hard', 'Current', 'medicalStore', 0),
(1829, 'DTC 55', 'Cold Mould Seal', 'Current', 'medicalStore', 0),
(1830, 'DTC 56', 'Lower Anterior Teeth A1', 'Current', 'medicalStore', 0),
(1831, 'DTC 57', 'Lower Posterior', 'Current', 'medicalStore', 0),
(1832, 'DTC 58', 'Dental Bonding Agent', 'Current', 'medicalStore', 0),
(1833, 'DTC 59', 'Cold Cure Liquid Monomer', 'Current', 'medicalStore', 0),
(1834, 'DTC 60', 'Special Burs (Surgical Burs)', 'Current', 'medicalStore', 0),
(1835, 'DTC 61', 'Dycal Base + Catalyst', 'Current', 'medicalStore', 0),
(1836, 'DTC 62', 'Zinc Phosphate cement', 'Current', 'medicalStore', 0),
(1837, 'DTC 63', 'Inter Arch Elastic (Red)', 'Current', 'medicalStore', 0),
(1838, 'DTC 64', 'Personal Elastic Ligature Clear', 'Current', 'medicalStore', 0),
(1839, 'DTC 65', 'Memory Chain Clear (Open & Closed)', 'Current', 'medicalStore', 0),
(1840, 'DTC 66', 'Gold Chain', 'Current', 'medicalStore', 0),
(1841, 'DTC 67', 'Medilux Teeth Upper posterior shade B1, B2, B3', 'Current', 'medicalStore', 0),
(1842, 'DTC 68', 'Medilux Teeth Lower posterior B1, B2, B3', 'Current', 'medicalStore', 0),
(1843, 'DTC 69', 'Medilux Teeth Lower Anterior B1, B2, B3', 'Current', 'medicalStore', 0),
(1844, 'DTC 70', 'Surgical Blade Size 11', 'Current', 'medicalStore', 0),
(1845, 'DTC 71', 'Purest Phosphate Bonded Investment Material', 'Current', 'medicalStore', 0),
(1846, 'DTC 72', 'File 15-40', 'Current', 'medicalStore', 0),
(1847, 'DTC 73', 'Sheet Casting Way', 'Current', 'medicalStore', 0),
(1848, 'DTC 74', 'Composite Ethant (Matric Cabrod)', 'Current', 'medicalStore', 0),
(1849, 'DTC 75', 'Breachwood Cresote', 'Current', 'medicalStore', 0),
(1850, 'DTC 76', 'Nitinol Wire Upper 012-020', 'Current', 'medicalStore', 0),
(1851, 'DTC 77', 'Nitinol Wire Lower 012-020', 'Current', 'medicalStore', 0),
(1852, 'DTC 78', 'Reverse Nitinol Lower 014-028', 'Current', 'medicalStore', 0),
(1853, 'DTC 79', 'Reverse Nitinol Upper (0.025) 016-019', 'Current', 'medicalStore', 0),
(1854, 'DTC 80', 'Reverse Nitinol Lower  016-019', 'Current', 'medicalStore', 0),
(1855, 'DTC 81', 'Nitinol Upper 016-019', 'Current', 'medicalStore', 0),
(1856, 'DTC 82', 'Stainless Steel Upper 016-021', 'Current', 'medicalStore', 0),
(1857, 'DTC 83', 'Stainless Steel Lower 016-021', 'Current', 'medicalStore', 0),
(1858, 'DTC 84', 'Flowable Composite', 'Current', 'medicalStore', 0),
(1859, 'DTC 85', 'Elastic Separator', 'Current', 'medicalStore', 0),
(1860, 'DTC 86', 'Nitinol Closed Coil-Spring', 'Current', 'medicalStore', 0),
(1861, 'DTC 87', 'Green Stick Impression Compound', 'Current', 'medicalStore', 0),
(1862, 'DTC 88', 'Premolar Band Upper/Lower', 'Current', 'medicalStore', 0),
(1863, 'DTC 89', 'Molar Band Upper/Lower', 'Current', 'medicalStore', 0),
(1864, 'DTC 90', '0.7mm Hard Drawn Wire', 'Current', 'medicalStore', 0),
(1865, 'DTC 91', 'Wilcock Stainless Steel Wire 0.04-0.020', 'Current', 'medicalStore', 0),
(1866, 'DTC 92', 'Upper Posted Arch Wire 0.018-0.047 x 0.025  various', 'Current', 'medicalStore', 0),
(1867, 'DTC 93', 'Ligatine Wire Ties (Ligate)', 'Current', 'medicalStore', 0),
(1868, 'DTC 94', 'Light Bond Composite', 'Current', 'medicalStore', 0),
(1869, 'DTC 95', '3.0 IVORY with Cutting Needle', 'Current', 'medicalStore', 0),
(1870, 'DTC 96', '0.022 MBT Brackets for Upper/Lower  incisors', 'Current', 'medicalStore', 0),
(1871, 'DTC 97', 'Essix Plastic (Dentsply Raintree Essix)', 'Current', 'medicalStore', 0),
(1872, 'DTC 98', 'Ultrabond Lock (Blue Kit)', 'Current', 'medicalStore', 0),
(1873, 'DTC 99', 'Medilux Teeth Posterior Upper/Lower B1,B2', 'Current', 'medicalStore', 0),
(1874, 'DTC 100', 'Medilux Teeth Posterior Upper/Lower C3', 'Current', 'medicalStore', 0),
(1875, 'DTC 101', 'PSS Wire', 'Current', 'medicalStore', 0),
(1876, 'DTC 102', 'ZOO-PK 50 ZE 3/16 HVY Kangaroo', 'Current', 'medicalStore', 0),
(1877, 'DTC 103', 'Molded Blue Separator', 'Current', 'medicalStore', 0),
(1878, 'DTC 104', 'AH Plus Endodontics Sealer', 'Current', 'medicalStore', 0),
(1879, 'DTC 105', 'Local Anesthetics 1A', 'Current', 'medicalStore', 0),
(1880, 'DTC 106', 'Gutta Percha Size (15-40)', 'Current', 'medicalStore', 0),
(1881, 'DTC 107', 'Gutta Percha Size (45-80)', 'Current', 'medicalStore', 0),
(1882, 'DTC 108', 'Zinc Oxide Eugenol', 'Current', 'medicalStore', 0);

-- --------------------------------------------------------

--
-- Table structure for table `audit`
--

CREATE TABLE `audit` (
  `id` int(11) NOT NULL,
  `itemname` varchar(255) NOT NULL,
  `itemcode` varchar(50) NOT NULL,
  `category` varchar(100) NOT NULL,
  `storesection` varchar(100) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `quantity_supplied` int(11) NOT NULL,
  `unitofmeasurement` varchar(50) NOT NULL,
  `deliverydate` date NOT NULL,
  `manufacturedate` date NOT NULL,
  `expirydate` date NOT NULL,
  `reservedquantity` int(11) DEFAULT 0,
  `reservedfordept` varchar(255) DEFAULT NULL,
  `remainingquantity` int(11) DEFAULT 0,
  `totalremainingquantity` int(20) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `request_status` varchar(100) NOT NULL,
  `source` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `civilconfirm`
--

CREATE TABLE `civilconfirm` (
  `id` int(11) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `quantityreleased` int(20) NOT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `department` varchar(20) NOT NULL,
  `officerincharge` varchar(50) NOT NULL,
  `request_status` varchar(30) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `previous_balance` int(11) NOT NULL,
  `current_balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `civilstore`
--

CREATE TABLE `civilstore` (
  `id` int(10) NOT NULL,
  `itemname` varchar(50) NOT NULL,
  `itemcode` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `supplier` varchar(20) NOT NULL,
  `quantity_supplied` int(11) NOT NULL,
  `expirydate` varchar(50) NOT NULL,
  `manufacturedate` varchar(50) NOT NULL,
  `deliverydate` varchar(50) NOT NULL,
  `reservedquantity` int(11) NOT NULL,
  `quantityreleased` int(11) NOT NULL,
  `reservedfordept` varchar(50) NOT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `officerincharge` varchar(50) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `collectedreserved` int(25) NOT NULL,
  `remainingreserved` int(25) NOT NULL,
  `totalremainingquantity` int(5) NOT NULL,
  `reserved_released` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `codingunit`
--

CREATE TABLE `codingunit` (
  `id` int(11) NOT NULL,
  `itemid` int(5) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `itemcode` varchar(30) NOT NULL,
  `storesection` varchar(100) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `department` varchar(50) NOT NULL,
  `quantityrequested` int(5) NOT NULL,
  `quantityreleased` int(5) NOT NULL,
  `request_status` varchar(20) NOT NULL,
  `release_date` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dept`
--

CREATE TABLE `dept` (
  `id` int(11) NOT NULL,
  `dept_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dept`
--

INSERT INTO `dept` (`id`, `dept_name`) VALUES
(1, 'CMD office'),
(2, 'CMAC office'),
(3, 'DA Office'),
(4, 'Board Chairman office'),
(5, 'Establishment'),
(6, 'Establishment (Records)'),
(7, 'Establishment (Registry)'),
(8, 'General Administration'),
(9, 'DNS office'),
(10, 'Internal Audit'),
(11, 'DFA office'),
(12, 'Stock Control (Account)'),
(13, 'Revenue Monitoring Unit'),
(14, 'Expenditure Control'),
(15, 'Treasury Unit'),
(16, 'Fixed Assets Unit'),
(17, 'Final Account'),
(18, 'Procurement'),
(19, 'Gynae Oncology'),
(20, 'Clinical Services & Training'),
(21, 'GOPD'),
(22, 'MOPD'),
(23, 'Unit Head Nursing Ph 1'),
(24, 'Laundry Unit'),
(25, 'UA office Ph 1'),
(26, 'Catering'),
(27, 'Dietetics'),
(28, 'Renal Ward/Hemodialysis'),
(29, 'Staff clinic'),
(30, 'Security Unit'),
(31, 'Chemical Pathology'),
(32, 'Point of Care'),
(33, 'Haematology & Blood Transfusion'),
(34, 'Morbid Anatomy & Forensic Medicine'),
(35, 'Microbiology & Parasitology'),
(36, 'Male Ortho Ward'),
(37, 'Children Ortho Ward'),
(38, 'Female Surgical Ward 1'),
(39, 'Female Surgical Ward II'),
(40, 'Gyne Ward'),
(41, 'Male Medical Ward'),
(42, 'Male Medical Extension'),
(43, 'Post Natal Ward'),
(44, 'Ante Natal Ward'),
(45, 'Labour Ward'),
(46, 'Ante Natal Clinic'),
(47, 'Labour Ward Theatre'),
(48, 'Children Ward 1A'),
(49, 'Children Ward 1B'),
(50, 'Children Ward II'),
(51, 'Paediatric Surgical Ward'),
(52, 'Cardiac Care Unit'),
(53, 'Mental Health'),
(54, 'Main ICU'),
(55, 'New ICU'),
(56, 'Orthopaedic Theatre'),
(57, 'Female Ortho Ward'),
(58, 'College of Nursing'),
(59, 'Endoscopy Suite'),
(60, 'Salary Unit'),
(61, 'Burns Unit'),
(62, 'Gynae Clinic'),
(63, 'Unit Head Nursing Ph IVA'),
(64, 'Unit Head Nursing Ph IVB'),
(65, 'Oncology Day Ward'),
(66, 'Ige Ward/Male Surgical Ward'),
(67, 'Budget Division'),
(68, 'Family Planning'),
(69, 'Accident & Emergency Male Ward'),
(70, 'Accident & Emergency Female Ward'),
(71, 'Medical Micro & Parasitology'),
(72, 'Tissue Typing'),
(73, 'Unit Head nursing Ph II'),
(74, 'UA office ph II'),
(75, 'ICT'),
(76, 'Medical Records'),
(77, 'Radiology'),
(78, 'Main theatre Ph II'),
(79, 'Day case theatre'),
(80, 'Chest clinic'),
(81, 'SCOPD'),
(82, 'Stores & Supplies'),
(83, 'DMS office'),
(84, 'Civil & Maintenance'),
(85, 'Plumbing section'),
(86, 'Masonry Section'),
(87, 'Carpentry Section'),
(88, 'Water Section'),
(89, 'Electrical department'),
(90, 'Power house Ph II'),
(91, 'Power house Ph IV'),
(92, 'Power house Eleyele'),
(93, 'Power house WGH'),
(94, 'Virology'),
(95, 'Biomedical Engineering'),
(96, 'Oxygen plant'),
(97, 'Oxygen Room'),
(98, 'Accident & Emergency Triage'),
(99, 'Casualty Theatre'),
(100, 'Estate unit'),
(101, 'Mechanical departmest'),
(102, 'Weldry section'),
(103, 'R & A section'),
(104, 'Auto mechanic'),
(105, 'Transport section'),
(106, 'NHIA IHU'),
(107, 'Infection Control'),
(108, 'Wound care'),
(109, 'Pharmacy department'),
(110, 'Servicom'),
(111, 'Pension'),
(112, 'Planning & Development'),
(113, 'Environtal Health'),
(114, 'Dental store'),
(115, 'UCHC store'),
(116, 'INGH Store'),
(117, 'Geriatric Store'),
(118, 'RCHC Stare'),
(119, 'Female Medical Ward I'),
(120, 'Female Medical Ward II'),
(121, 'Tailoring unit'),
(122, 'UA office ph III'),
(123, 'UA office ph IV'),
(124, 'Corporate Services'),
(125, 'Endocrinology'),
(126, 'Male Surgical Ward II'),
(127, 'CSSD Ph II'),
(128, 'CSSD Ph IV'),
(129, 'Orthopedic clinic'),
(130, 'Eye Care Center/ophtalmology'),
(131, 'Neurology Ward'),
(132, 'ENT clinic'),
(133, 'ENT Ward'),
(134, 'Pediatric Cardiology clinic'),
(135, 'Anesthesia Ph II'),
(136, 'EEG unit'),
(137, 'Revenue Pay point Unit'),
(138, 'Legal unit'),
(139, 'Monicular Laboratory'),
(140, 'Neo natal Inland'),
(141, 'Assessment unit'),
(142, 'Physiotherapy Department');

-- --------------------------------------------------------

--
-- Table structure for table `electricalconfirm`
--

CREATE TABLE `electricalconfirm` (
  `id` int(11) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `quantityreleased` int(20) NOT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `department` varchar(20) NOT NULL,
  `officerincharge` varchar(50) NOT NULL,
  `request_status` varchar(50) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `previous_balance` int(11) NOT NULL,
  `current_balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `electricalstore`
--

CREATE TABLE `electricalstore` (
  `id` int(11) NOT NULL,
  `itemname` varchar(50) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `category` varchar(20) NOT NULL,
  `supplier` varchar(50) NOT NULL,
  `quantity_supplied` int(5) NOT NULL,
  `expirydate` varchar(20) NOT NULL,
  `manufacturedate` varchar(20) NOT NULL,
  `deliverydate` varchar(20) NOT NULL,
  `reservedquantity` int(5) NOT NULL,
  `quantityreleased` int(5) NOT NULL,
  `reservedfordept` varchar(50) NOT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` int(25) NOT NULL,
  `officerincharge` varchar(50) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `collectedreserved` int(25) NOT NULL,
  `remainingreserved` int(25) NOT NULL,
  `totalremainingquantity` int(50) NOT NULL,
  `reserved_released` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hardwareconfirm`
--

CREATE TABLE `hardwareconfirm` (
  `id` int(11) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `quantityreleased` int(20) NOT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `department` varchar(20) NOT NULL,
  `officerincharge` varchar(50) NOT NULL,
  `request_status` varchar(50) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `previous_balance` int(50) NOT NULL,
  `current_balance` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hardwarestore`
--

CREATE TABLE `hardwarestore` (
  `id` int(50) NOT NULL,
  `itemname` varchar(50) NOT NULL,
  `itemcode` varchar(25) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `supplier` varchar(50) NOT NULL,
  `quantity_supplied` int(11) DEFAULT NULL,
  `expirydate` varchar(50) NOT NULL,
  `manufacturedate` varchar(50) NOT NULL,
  `deliverydate` varchar(50) NOT NULL,
  `reservedquantity` int(11) DEFAULT NULL,
  `quantityreleased` int(11) NOT NULL,
  `reservedfordept` varchar(255) DEFAULT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `officerincharge` varchar(50) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `collectedreserved` int(25) NOT NULL,
  `remainingreserved` int(30) NOT NULL,
  `totalremainingquantity` int(5) NOT NULL,
  `reserved_released` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `healthconfirm`
--

CREATE TABLE `healthconfirm` (
  `id` int(11) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `quantityreleased` int(20) NOT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `department` varchar(20) NOT NULL,
  `officerincharge` varchar(50) NOT NULL,
  `request_status` varchar(50) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `previous_balance` int(11) NOT NULL,
  `current_balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `healthstore`
--

CREATE TABLE `healthstore` (
  `id` int(11) NOT NULL,
  `itemname` varchar(50) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `category` varchar(20) NOT NULL,
  `supplier` varchar(50) NOT NULL,
  `quantity_supplied` int(11) NOT NULL,
  `expirydate` varchar(20) NOT NULL,
  `manufacturedate` varchar(20) NOT NULL,
  `deliverydate` varchar(20) NOT NULL,
  `reservedquantity` int(11) NOT NULL,
  `quantityreleased` int(11) NOT NULL,
  `reservedfordept` varchar(50) NOT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `officerincharge` varchar(50) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `collectedreserved` int(25) NOT NULL,
  `remainingreserved` int(30) NOT NULL,
  `totalremainingquantity` int(5) NOT NULL,
  `reserved_released` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `item_locks`
--

CREATE TABLE `item_locks` (
  `id` int(11) NOT NULL,
  `itemcode` varchar(255) NOT NULL,
  `lock_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `labconfirm`
--

CREATE TABLE `labconfirm` (
  `id` int(11) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `quantityreleased` int(20) NOT NULL,
  `remainingquantity` int(11) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `department` varchar(50) NOT NULL,
  `officerincharge` varchar(100) NOT NULL,
  `request_status` varchar(100) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `previous_balance` int(11) DEFAULT 0,
  `current_balance` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `labstore`
--

CREATE TABLE `labstore` (
  `id` int(10) NOT NULL,
  `itemname` varchar(50) NOT NULL,
  `itemcode` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `supplier` varchar(50) NOT NULL,
  `quantity_supplied` int(11) DEFAULT NULL,
  `expirydate` varchar(50) NOT NULL,
  `manufacturedate` varchar(50) NOT NULL,
  `deliverydate` varchar(50) NOT NULL,
  `reservedquantity` int(11) DEFAULT NULL,
  `quantityreleased` int(11) NOT NULL,
  `reservedfordept` varchar(30) DEFAULT NULL,
  `remainingquantity` int(11) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `officerincharge` varchar(100) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `collectedreserved` int(25) NOT NULL,
  `remainingreserved` int(30) NOT NULL,
  `totalremainingquantity` int(5) NOT NULL,
  `reserved_released` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `medconfirm`
--

CREATE TABLE `medconfirm` (
  `id` int(11) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `quantityreleased` int(11) NOT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `department` varchar(20) NOT NULL,
  `officerincharge` varchar(50) NOT NULL,
  `request_status` varchar(50) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `previous_balance` int(11) NOT NULL,
  `current_balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `medstore`
--

CREATE TABLE `medstore` (
  `id` int(10) NOT NULL,
  `itemname` varchar(50) NOT NULL,
  `itemcode` varchar(50) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `supplier` varchar(50) NOT NULL,
  `quantity_supplied` int(11) DEFAULT NULL,
  `expirydate` varchar(50) NOT NULL,
  `manufacturedate` varchar(50) NOT NULL,
  `deliverydate` varchar(50) NOT NULL,
  `reservedquantity` int(11) DEFAULT NULL,
  `quantityreleased` int(11) NOT NULL,
  `reservedfordept` varchar(255) DEFAULT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `officerincharge` varchar(50) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `collectedreserved` int(25) NOT NULL,
  `remainingreserved` int(30) NOT NULL,
  `totalremainingquantity` int(5) NOT NULL,
  `reserved_released` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `storesection` varchar(255) NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `recipient` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `receivingbay`
--

CREATE TABLE `receivingbay` (
  `id` int(11) NOT NULL,
  `itemname` varchar(200) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `category` varchar(40) NOT NULL,
  `quantity_supplied` int(100) NOT NULL,
  `storesection` varchar(255) NOT NULL,
  `supplier` varchar(50) NOT NULL,
  `unitofmeasurement` varchar(50) NOT NULL,
  `deliverydate` varchar(20) NOT NULL,
  `manufacturedate` varchar(20) NOT NULL,
  `expirydate` varchar(20) NOT NULL,
  `reservedquantity` int(11) NOT NULL,
  `reservedfordept` varchar(30) DEFAULT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `collectedreserved` int(5) NOT NULL,
  `remainingreserved` int(5) NOT NULL,
  `totalremainingquantity` int(5) NOT NULL,
  `reserved_released` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `receivingbaynewitems`
--

CREATE TABLE `receivingbaynewitems` (
  `id` int(11) NOT NULL,
  `itemname` varchar(50) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `category` varchar(20) NOT NULL,
  `quantity_supplied` int(5) NOT NULL,
  `storesection` varchar(50) NOT NULL,
  `supplier` varchar(50) NOT NULL,
  `unitofmeasurement` varchar(10) NOT NULL,
  `deliverydate` varchar(20) NOT NULL,
  `manufacturedate` varchar(20) NOT NULL,
  `expirydate` varchar(20) NOT NULL,
  `reservedquantity` int(5) NOT NULL,
  `reservedfordept` varchar(30) DEFAULT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `collectedreserved` int(5) NOT NULL,
  `remainingreserved` int(5) NOT NULL,
  `totalremainingquantity` int(5) NOT NULL,
  `reserved_released` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `store_section` varchar(50) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `status` int(2) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `fullname`, `email`, `password`, `store_section`, `role`, `status`, `is_active`) VALUES
(1, 'Omowumi', 'omowumi@gmail.com', '$2y$10$JwlR070/O32OH76EvrcePu8TJMf/dWD1as.5veEb2imOVWML7Ltte', 'receivingBay', 'user', 1, 1),
(2, 'Anyabolu', 'gee@gmail.com', '$2y$10$6Ii9XwSvwhCTYRysExCDs.1IULa0UNJIZDzgqkYoFf5jAVGJBCRHO', 'hod', 'user', 1, 1),
(3, 'Rose', 'rose@gmail.com', '$2y$10$eofkUnC.RVoITDJnMmFUo.f23zt3PY8kq9daM4VkB3tlg3Nv.lcpG', 'hardwareStore', 'user', 1, 1),
(4, 'Adediran', 'ade@gmail.com', '$2y$10$z/hw5wdOb/O/SnzVaehzAu/DpTshUY5GjOzVOvFGnByh/INMOMGnq', 'labStore', 'user', 1, 1),
(5, 'Alo', 'alo@gmail.com', '$2y$10$BmNB3gfhQOMalxXXDnwkgusK/d.69M/jX7oil.Hh2Z/nn7Z/UsHjq', 'electricalStore', 'user', 1, 1),
(6, 'Tayo', 'tayo@gmail.com', '$2y$10$o8EkYqqYYeEhwGTz3SV9/uxAJO9RleKe55YAh1fHWWR9K.SGdYnXe', 'civilStore', 'user', 1, 1),
(7, 'Afeez', 'afeez@gmail.com', '$2y$10$4ADX6kFGNHj8oHxDKB1W6ufUYfIATZ./BUxC5XP9zpNxziUtgm0QW', 'generalStationeryStore', 'user', 1, 1),
(8, 'Kenny', 'kenny@gmail.com', '$2y$10$EFaMfKno0OOCuj1SyjQ3/uSAvsYpTTmnxYK.P.D/9Ih.llyRia1Ry', 'controlunit', 'user', 1, 1),
(10, 'Samuel', 'sam@gmail.com', '$2y$10$bJaQY51kw0MiOeFZDeCzaen6F6QC4.ypdLyx88jV4RHedq0P4kS7y', 'medicalStore', 'user', 1, 1),
(11, 'Adedeji Aderonke', 'deji@gmail.com', '$2y$10$T0gBZocqW5ElXJB5iCsgw.JOc.sSoVAQjYtZ2zfeKDF8eB6wTT6Ca', 'receivingBay', 'user', 1, 1),
(12, 'Sambo', 'sambo@gmail.com', '$2y$10$C53s/yHNMCLDONGsMmaR/ukvpg7N6tQBnaVpA.pVVCe4.UCVTMCKW', 'labStore', 'user', 1, 1),
(13, 'Ibrahim DUka', 'Ibb@gmail.com', '$2y$10$XURvXmTYouRO1Y3C6Ah/EezTq5SBCGBetvX5cBJrFuzRHomN3vO26', 'medicalStore', 'user', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `itemname` varchar(200) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `category` varchar(20) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantityrequested` int(5) NOT NULL,
  `requisitionformcode` varchar(100) NOT NULL,
  `itemrequestformcode` varchar(30) NOT NULL,
  `department` varchar(50) NOT NULL,
  `employeeid` varchar(10) NOT NULL,
  `collectedby` varchar(20) NOT NULL,
  `storesection` varchar(30) NOT NULL,
  `issuedby` varchar(20) NOT NULL,
  `quantityreleased` int(11) NOT NULL,
  `remainingquantity` int(11) NOT NULL,
  `reservedquantity` varchar(30) NOT NULL,
  `request_date` varchar(20) NOT NULL,
  `request_status` varchar(15) NOT NULL,
  `collectedreserved` int(5) NOT NULL,
  `remainingreserved` int(5) NOT NULL,
  `totalremainingquantity` int(5) NOT NULL,
  `pending_quantity` int(5) NOT NULL,
  `createdon` varchar(30) NOT NULL,
  `request_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reserved`
--

CREATE TABLE `reserved` (
  `id` int(11) NOT NULL,
  `itemcode` varchar(50) NOT NULL,
  `reservedquantity` int(11) NOT NULL,
  `reservedfordept` varchar(100) NOT NULL,
  `reserved_released` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stationeryconfirm`
--

CREATE TABLE `stationeryconfirm` (
  `id` int(11) NOT NULL,
  `itemcode` varchar(20) NOT NULL,
  `itemname` varchar(100) NOT NULL,
  `quantityreleased` int(20) NOT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `department` varchar(20) NOT NULL,
  `officerincharge` varchar(50) NOT NULL,
  `request_status` varchar(100) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `previous_balance` int(11) DEFAULT 0,
  `current_balance` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stationerystore`
--

CREATE TABLE `stationerystore` (
  `id` int(10) NOT NULL,
  `itemname` varchar(50) NOT NULL,
  `itemcode` varchar(50) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `supplier` varchar(50) NOT NULL,
  `quantity_supplied` int(11) DEFAULT NULL,
  `expirydate` varchar(50) NOT NULL,
  `manufacturedate` varchar(50) NOT NULL,
  `deliverydate` varchar(50) NOT NULL,
  `reservedquantity` int(11) DEFAULT NULL,
  `quantityreleased` int(11) NOT NULL,
  `reservedfordept` varchar(255) DEFAULT NULL,
  `remainingquantity` int(5) NOT NULL,
  `initialprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currentprice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `officerincharge` varchar(50) NOT NULL,
  `createdon` varchar(50) NOT NULL,
  `collectedreserved` int(20) NOT NULL,
  `remainingreserved` int(25) NOT NULL,
  `totalremainingquantity` int(5) NOT NULL,
  `reserved_released` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `id` int(5) NOT NULL,
  `storesection` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`id`, `storesection`) VALUES
(1, 'hardwareStore'),
(2, 'medicalStore'),
(3, 'labStore'),
(4, 'electricalStore'),
(5, 'civilStore'),
(6, 'generalStationeryStore'),
(7, 'controlunit'),
(8, 'receivingBay'),
(9, 'hod');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_phone` varchar(50) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `supplier`, `company_name`, `contact_phone`, `contact_email`, `address`) VALUES
(1, 'Famous', 'Famous', '0913367483', 'stevej@gmail.com', 'Eleyeye bust stop'),
(2, 'Steve A.J ', 'Steve & Co', '0913367483', 'stevej@gmail.com', 'Eleyeye bust stop'),
(3, 'Felix A.M', 'Felix Enterprise', '08078345833', 'felix@gmail.com', 'Ibadan Road');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `unitname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unitname`) VALUES
(1, 'pieces'),
(2, 'box'),
(3, 'carton'),
(4, 'pack'),
(5, 'bottle'),
(6, 'litre (L) / ml'),
(7, 'kg / g'),
(8, 'meter / roll'),
(9, 'set'),
(10, 'can'),
(11, 'bag'),
(12, 'sachet'),
(13, 'ream'),
(14, 'tin'),
(15, 'drum'),
(16, 'kit'),
(17, 'dozen'),
(18, 'pair'),
(19, 'set'),
(20, 'canister'),
(21, 'sheet'),
(22, 'bar'),
(23, 'bunch'),
(24, 'coil'),
(25, 'spray'),
(26, 'plate'),
(27, 'length');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allitems`
--
ALTER TABLE `allitems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `civilconfirm`
--
ALTER TABLE `civilconfirm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `civilstore`
--
ALTER TABLE `civilstore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `codingunit`
--
ALTER TABLE `codingunit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dept`
--
ALTER TABLE `dept`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `electricalconfirm`
--
ALTER TABLE `electricalconfirm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `electricalstore`
--
ALTER TABLE `electricalstore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hardwareconfirm`
--
ALTER TABLE `hardwareconfirm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hardwarestore`
--
ALTER TABLE `hardwarestore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `healthconfirm`
--
ALTER TABLE `healthconfirm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `healthstore`
--
ALTER TABLE `healthstore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_locks`
--
ALTER TABLE `item_locks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `itemcode` (`itemcode`);

--
-- Indexes for table `labconfirm`
--
ALTER TABLE `labconfirm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labstore`
--
ALTER TABLE `labstore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medconfirm`
--
ALTER TABLE `medconfirm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medstore`
--
ALTER TABLE `medstore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receivingbay`
--
ALTER TABLE `receivingbay`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receivingbaynewitems`
--
ALTER TABLE `receivingbaynewitems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reserved`
--
ALTER TABLE `reserved`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stationeryconfirm`
--
ALTER TABLE `stationeryconfirm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stationerystore`
--
ALTER TABLE `stationerystore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `civilconfirm`
--
ALTER TABLE `civilconfirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `civilstore`
--
ALTER TABLE `civilstore`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `codingunit`
--
ALTER TABLE `codingunit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dept`
--
ALTER TABLE `dept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `electricalconfirm`
--
ALTER TABLE `electricalconfirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `electricalstore`
--
ALTER TABLE `electricalstore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hardwareconfirm`
--
ALTER TABLE `hardwareconfirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hardwarestore`
--
ALTER TABLE `hardwarestore`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `healthconfirm`
--
ALTER TABLE `healthconfirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `healthstore`
--
ALTER TABLE `healthstore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_locks`
--
ALTER TABLE `item_locks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labconfirm`
--
ALTER TABLE `labconfirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labstore`
--
ALTER TABLE `labstore`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medconfirm`
--
ALTER TABLE `medconfirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medstore`
--
ALTER TABLE `medstore`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receivingbay`
--
ALTER TABLE `receivingbay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receivingbaynewitems`
--
ALTER TABLE `receivingbaynewitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reserved`
--
ALTER TABLE `reserved`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stationeryconfirm`
--
ALTER TABLE `stationeryconfirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stationerystore`
--
ALTER TABLE `stationerystore`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
