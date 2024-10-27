-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 26, 2024 at 10:52 PM
-- Server version: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_porsiriyontforklift`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_no` varchar(45) DEFAULT NULL,
  `admin_name` varchar(255) DEFAULT NULL,
  `admin_username` varchar(45) DEFAULT NULL,
  `admin_password` varchar(45) DEFAULT NULL,
  `position_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`admin_id`, `admin_no`, `admin_name`, `admin_username`, `admin_password`, `position_id`) VALUES
(2, 'G_001', 'admin', 'admin', '123', 7);

-- --------------------------------------------------------

--
-- Table structure for table `tb_data`
--

CREATE TABLE `tb_data` (
  `data_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_no` varchar(45) DEFAULT NULL,
  `data_macAddress` varchar(45) DEFAULT NULL,
  `data_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_data`
--

INSERT INTO `tb_data` (`data_id`, `user_id`, `user_no`, `data_macAddress`, `data_name`) VALUES
(1, 1, 'A_001', 'fe80::ca78:33d8:4903:e854%17', 'suthada'),
(2, 22, 'F_001', '38-D5-7A-12-71-B1', 'LAPTOP-UUE2562O');

-- --------------------------------------------------------

--
-- Table structure for table `tb_documents`
--

CREATE TABLE `tb_documents` (
  `docs_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `docs_type` varchar(45) DEFAULT NULL,
  `docs_files` text,
  `docs_date` datetime DEFAULT NULL,
  `hr_id` int(11) DEFAULT NULL,
  `docs_status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_documents`
--

INSERT INTO `tb_documents` (`docs_id`, `user_id`, `docs_type`, `docs_files`, `docs_date`, `hr_id`, `docs_status`) VALUES
(1, 1, 'เปลี่ยน ที่อยู่', 'A_001_20241022_235931.png', '2024-10-22 23:59:31', 2, 'อนุมัติ'),
(2, 1, 'เปลี่ยน ชื่อ/นามสกุล', 'A_001_20241023_000225.png', '2024-10-23 00:02:25', 2, 'อนุมัติ'),
(3, 1, 'เปลี่ยน ชื่อ/นามสกุล', 'A_001_20241023_001115.png', '2024-10-23 00:11:15', 2, 'ไม่อนุมัติ');

-- --------------------------------------------------------

--
-- Table structure for table `tb_hr`
--

CREATE TABLE `tb_hr` (
  `hr_id` int(11) NOT NULL,
  `hr_no` varchar(45) DEFAULT NULL,
  `hr_name` varchar(255) DEFAULT NULL,
  `hr_startDate` varchar(255) DEFAULT NULL,
  `hr_exp` varchar(255) DEFAULT NULL,
  `hr_idNumber` int(11) DEFAULT NULL,
  `hr_phoneNumber` varchar(11) DEFAULT NULL,
  `hr_Date` varchar(255) DEFAULT NULL,
  `hr_age` int(11) DEFAULT NULL,
  `hr_gender` varchar(45) DEFAULT NULL,
  `hr_username` varchar(45) DEFAULT NULL,
  `hr_password` varchar(45) DEFAULT NULL,
  `position_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_hr`
--

INSERT INTO `tb_hr` (`hr_id`, `hr_no`, `hr_name`, `hr_startDate`, `hr_exp`, `hr_idNumber`, `hr_phoneNumber`, `hr_Date`, `hr_age`, `hr_gender`, `hr_username`, `hr_password`, `position_id`) VALUES
(2, 'E_001', 'hr', '2017-11-22', '0000-00-00', 2147483647, '202020202', '2001-05-07', 23, 'female', 'hr', '123', 5),
(3, 'E_001', 'r', '2024-09-06', '0 ปี 1 เดือน 5 วัน', 2147483647, '909009786', '2024-07-19', 0, 'male', 'g', 'g', 1),
(4, 'E_001', 'นางสาวสุธาดา เสนามงคล', '2023-06-14', '1 ปี 4 เดือน 11 วัน', 2147483647, '0872164298', '2003-02-21', 21, 'female', 'e_suthada', '123', 5),
(5, 'E_001', 'ewr', '2024-10-08', '0 ปี 0 เดือน 17 วัน', 2147483647, '5000005615', '2024-10-08', 0, 'male', 'f', 'f', 5),
(6, 'E_002', 'sadsadsad', '2024-10-01', '0 ปี 0 เดือน 24 วัน', 2147483647, '0025202155', '2024-07-04', 0, 'male', 'r', 'r', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tb_personalleave`
--

CREATE TABLE `tb_personalleave` (
  `PLeave_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `PLeave_dateStart` date DEFAULT NULL,
  `PLeave_dateEnd` date DEFAULT NULL,
  `PLeave_detail` text,
  `PLeave_docs` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PLeave_date` datetime DEFAULT NULL,
  `hr_id` int(11) DEFAULT NULL,
  `PLeave_status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_personalleave`
--

INSERT INTO `tb_personalleave` (`PLeave_id`, `user_id`, `PLeave_dateStart`, `PLeave_dateEnd`, `PLeave_detail`, `PLeave_docs`, `PLeave_date`, `hr_id`, `PLeave_status`) VALUES
(38, 1, '2567-10-09', '2567-10-24', 'หฟกหฟกหฟกฟหก', 'A_001_20241020_161228.png', '2024-10-20 00:00:00', 2, 'อนุมัติ'),
(39, 1, '2567-10-09', '2567-10-30', 'หฟกฟหกฟหกฟหก', 'Screenshot 2023-12-20 092429.png', '2024-10-20 00:00:00', 2, 'ไม่อนุมัติ'),
(40, 1, '2567-10-01', '2567-10-07', 'หฟกหฟกหฟก', 'A_001_20241020_161711.png', '2024-10-20 00:00:00', 2, 'ไม่อนุมัติ'),
(41, 1, '2567-10-02', '2567-10-22', 'ฟหกหฟกฟหกฟหก', 'A_001_20241020_161919.png', '2024-10-20 00:00:00', 2, 'อนุมัติ'),
(42, 1, '2567-10-09', '2567-10-24', 'หฟกหฟกฟหก', 'A_001_20241020_162348.png', '2024-10-20 00:00:00', 2, 'ไม่อนุมัติ'),
(43, 1, '2567-10-01', '2567-10-14', 'หกดฟหก', 'A_001_20241022_200258.png', '2024-10-22 20:02:58', 3, 'อนุมัติ'),
(44, 1, '2567-10-01', '2567-10-24', 'sadsadasdasd', 'A_001_20241022_205618.png', '2024-10-22 20:56:18', 3, 'อนุมัติ'),
(45, 1, '2567-10-08', '2567-10-17', 'sdfsdfsdfdsfsdf', 'A_001_20241022_205633.png', '2024-10-22 20:56:33', 3, 'ไม่อนุมัติ'),
(46, 1, '2567-10-08', '2567-10-11', 'มมมม', NULL, '2024-10-23 22:49:47', 2, 'อนุมัติ'),
(47, 1, '2567-10-08', '2567-10-11', 'sdcscs', 'A_001_20241027_003116.png', '2024-10-27 00:31:16', NULL, 'กำลังดำเนินการ');

-- --------------------------------------------------------

--
-- Table structure for table `tb_position`
--

CREATE TABLE `tb_position` (
  `position_id` int(11) NOT NULL,
  `position_type` varchar(45) DEFAULT NULL,
  `position_name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_position`
--

INSERT INTO `tb_position` (`position_id`, `position_type`, `position_name`) VALUES
(1, 'A', 'พนักงานขับรถเครน'),
(2, 'B', 'พนักงานขับรถขนย้าย'),
(3, 'C', 'พนักงานขับรถโฟล์คลิฟ'),
(4, 'D', 'ช่าง'),
(5, 'E', 'hr'),
(6, 'F', 'แม่บ้าน'),
(7, 'G', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tb_resign`
--

CREATE TABLE `tb_resign` (
  `resign_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resign_logdate` datetime DEFAULT NULL,
  `resign_detail` text,
  `resign_docs` text,
  `resign_date` date DEFAULT NULL,
  `hr_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `resign_status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_resign`
--

INSERT INTO `tb_resign` (`resign_id`, `user_id`, `resign_logdate`, `resign_detail`, `resign_docs`, `resign_date`, `hr_id`, `admin_id`, `resign_status`) VALUES
(30, 1, '2567-10-07 21:49:19', 'ewfwef', NULL, '2567-10-22', 2, 2, 'อนุมัติ'),
(31, 1, '2567-10-23 21:49:24', 'dfwefewf', NULL, '2567-11-07', 2, 2, 'ไม่อนุมัติ'),
(32, 1, '2567-10-07 21:49:29', 'wefwefwef', NULL, '2567-10-22', 2, 2, 'อนุมัติ'),
(33, 1, '2567-10-13 21:49:34', 'ewfwefewfwef', NULL, '2567-10-28', 2, 2, 'อนุมัติ'),
(34, 1, '2567-10-08 22:14:59', 'asdasdasdasd', NULL, '2567-10-23', NULL, 2, 'ไม่อนุมัติ'),
(35, 1, '2567-10-01 22:15:04', 'sadasdasd', NULL, '2567-10-16', NULL, 2, 'อนุมัติ');

-- --------------------------------------------------------

--
-- Table structure for table `tb_timelog`
--

CREATE TABLE `tb_timelog` (
  `timelog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timelog_date` date DEFAULT NULL,
  `timelog_in` varchar(45) DEFAULT NULL,
  `timelog_out` varchar(45) DEFAULT NULL,
  `data_id` int(11) NOT NULL,
  `timelog_status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_timelog`
--

INSERT INTO `tb_timelog` (`timelog_id`, `user_id`, `timelog_date`, `timelog_in`, `timelog_out`, `data_id`, `timelog_status`) VALUES
(21, 1, '2024-10-27', '04:20:35', NULL, 1, 'เข้างาน'),
(22, 22, '2024-10-27', '05:06:59', '2024-10-27 05:47:19', 2, 'ออกงานแล้ว');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL,
  `user_no` varchar(45) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_startDate` date DEFAULT NULL,
  `user_exp` varchar(50) DEFAULT NULL,
  `user_idNumber` int(11) DEFAULT NULL,
  `user_phoneNumber` varchar(11) DEFAULT NULL,
  `user_Date` date DEFAULT NULL,
  `user_age` varchar(255) DEFAULT NULL,
  `user_day` varchar(45) DEFAULT NULL,
  `user_username` varchar(45) DEFAULT NULL,
  `user_password` varchar(45) DEFAULT NULL,
  `user_gender` varchar(45) DEFAULT NULL,
  `position_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `user_no`, `user_name`, `user_startDate`, `user_exp`, `user_idNumber`, `user_phoneNumber`, `user_Date`, `user_age`, `user_day`, `user_username`, `user_password`, `user_gender`, `position_id`) VALUES
(1, 'A_001', 'user', '2020-11-21', '3 ปี 10 เดือน 12 วัน', 2147483647, '871164552', '1989-07-13', '35', '88', 'user', '123', 'female', 1),
(3, 'A_002', 'นายอภิศักดิ์ น้าวัฒนไพบูลย์', '2022-02-02', '2 ปี 8 เดือน 9 วัน', 2147483647, '451515515', '2021-07-06', '3', '69 วัน', 'a_aphisak', 'a12345678', 'ชาย', 1),
(5, 'B_001', 'นายสมประสงค์ แจ้งกระจ่าง', '2008-05-07', '16 ปี 4 เดือน 27 วัน', 124525245, '868324178', '1964-01-13', '60', '427 วัน', 'b_somprasong', '01131964', 'ชาย', 2),
(8, 'D_005', 'sdf', '2024-06-12', '0 ปี 3 เดือน 29 วัน', 2147483647, '0872164298', '2024-04-13', '0', '8 วัน', 'f', 'f', 'ชาย', 4),
(12, 'A_008', 'หปฟ', '2019-01-29', '5 ปี 8 เดือน 19 วัน', 2147483647, '0872164298', '2019-06-11', '5', '148 วัน', 'lllll', 'สาสส', 'ชาย', 1),
(13, 'A_009', 'สสส', '2018-06-04', '6 ปี 4 เดือน 14 วัน', 2147483647, '0814879788', '2024-07-11', '0', '166', 'pp', 'สวสว', 'male', 1),
(16, 'A_010', 'sdfdsfsdfsdf', '2024-03-13', '0 ปี 7 เดือน 11 วัน', 2147483647, '9809808908', '1984-07-11', '0', '15 วัน', 'yy', 'yy', 'male', 1),
(17, 'A_011', 'sddsfdsf', '2024-10-03', '0 ปี 0 เดือน 21 วัน', 2147483647, '0872164298', '1965-04-02', '0', '1 วัน', 'dfsd', 'sdffsdf', 'male', 1),
(20, 'A_012', 'ssss', '2023-07-06', '1 ปี 3 เดือน 20 วัน', 2147483647, '0872164298', '1995-03-30', '29 ปี 6 เดือน 26 วัน', '33 วัน', 'er', 'wer', 'ชาย', 1),
(22, 'F_001', 'นางสาวสุธาดา เสนามงคล', '2024-02-13', '0 ปี 8 เดือน 13 วัน', 2147483647, '0872164298', '2003-02-21', '21 ปี 8 เดือน 6 วัน', '18', 'f_suthada', '123', 'female', 6);

-- --------------------------------------------------------

--
-- Table structure for table `tb_vacationleave`
--

CREATE TABLE `tb_vacationleave` (
  `VLeave_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `VLeave_dateStart` date DEFAULT NULL,
  `VLeave_dateEnd` date DEFAULT NULL,
  `VLeave_detail` text,
  `VLeave_docs` text,
  `VLeave_date` datetime DEFAULT NULL,
  `hr_id` int(11) DEFAULT NULL,
  `VLeave_status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_vacationleave`
--

INSERT INTO `tb_vacationleave` (`VLeave_id`, `user_id`, `VLeave_dateStart`, `VLeave_dateEnd`, `VLeave_detail`, `VLeave_docs`, `VLeave_date`, `hr_id`, `VLeave_status`) VALUES
(9, 1, '2567-10-09', '2567-10-10', 'werwerwer', 'A_001_20241022_124348.png', '2024-10-22 12:43:48', 2, 'ไม่อนุมัติ'),
(10, 1, '2567-10-02', '2567-10-07', 'หฟกฟหกฟกหก', 'A_001_20241022_194538.png', '2024-10-22 19:45:38', 3, 'อนุมัติ'),
(11, 1, '2567-10-08', '2567-10-22', 'sdadasdasd', NULL, '2024-10-22 23:22:17', NULL, 'กำลังดำเนินการ'),
(12, 1, '2567-10-08', '2567-10-10', 'asdasdasdsadasd', NULL, '2024-10-24 17:02:06', 2, 'อนุมัติ'),
(13, 1, '2567-10-25', '2567-10-26', 'sdfsdfdsfdsf', NULL, '2024-10-24 17:04:10', 2, 'อนุมัติ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `fk_tb_admin_tb_position1_idx` (`position_id`);

--
-- Indexes for table `tb_data`
--
ALTER TABLE `tb_data`
  ADD PRIMARY KEY (`data_id`),
  ADD KEY `fk_tb_data_tb_user1_idx` (`user_id`);

--
-- Indexes for table `tb_documents`
--
ALTER TABLE `tb_documents`
  ADD PRIMARY KEY (`docs_id`),
  ADD KEY `fk_tb_documents_tb_user1_idx` (`user_id`),
  ADD KEY `fk_tb_documents_tb_hr1_idx` (`hr_id`);

--
-- Indexes for table `tb_hr`
--
ALTER TABLE `tb_hr`
  ADD PRIMARY KEY (`hr_id`),
  ADD KEY `fk_tb_hr_tb_position1_idx` (`position_id`);

--
-- Indexes for table `tb_personalleave`
--
ALTER TABLE `tb_personalleave`
  ADD PRIMARY KEY (`PLeave_id`),
  ADD KEY `fk_tb_personalLeave_tb_user1_idx` (`user_id`),
  ADD KEY `fk_tb_personalLeave_tb_hr1_idx` (`hr_id`);

--
-- Indexes for table `tb_position`
--
ALTER TABLE `tb_position`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `tb_resign`
--
ALTER TABLE `tb_resign`
  ADD PRIMARY KEY (`resign_id`),
  ADD KEY `fk_tb_personalLeave_tb_user1_idx` (`user_id`),
  ADD KEY `fk_tb_personalLeave_tb_hr1_idx` (`hr_id`),
  ADD KEY `fk_tb_resign_tb_admin1_idx` (`admin_id`);

--
-- Indexes for table `tb_timelog`
--
ALTER TABLE `tb_timelog`
  ADD PRIMARY KEY (`timelog_id`),
  ADD KEY `fk_tb_timelog_tb_user_idx` (`user_id`),
  ADD KEY `fk_tb_timelog_tb_data1_idx` (`data_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_tb_user_tb_position1_idx` (`position_id`);

--
-- Indexes for table `tb_vacationleave`
--
ALTER TABLE `tb_vacationleave`
  ADD PRIMARY KEY (`VLeave_id`),
  ADD KEY `fk_tb_personalLeave_tb_user1_idx` (`user_id`),
  ADD KEY `fk_tb_personalLeave_tb_hr1_idx` (`hr_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_documents`
--
ALTER TABLE `tb_documents`
  MODIFY `docs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_hr`
--
ALTER TABLE `tb_hr`
  MODIFY `hr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_personalleave`
--
ALTER TABLE `tb_personalleave`
  MODIFY `PLeave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tb_position`
--
ALTER TABLE `tb_position`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_resign`
--
ALTER TABLE `tb_resign`
  MODIFY `resign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tb_timelog`
--
ALTER TABLE `tb_timelog`
  MODIFY `timelog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tb_vacationleave`
--
ALTER TABLE `tb_vacationleave`
  MODIFY `VLeave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD CONSTRAINT `fk_tb_admin_tb_position1` FOREIGN KEY (`position_id`) REFERENCES `tb_position` (`position_id`);

--
-- Constraints for table `tb_data`
--
ALTER TABLE `tb_data`
  ADD CONSTRAINT `fk_tb_data_tb_user1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);

--
-- Constraints for table `tb_documents`
--
ALTER TABLE `tb_documents`
  ADD CONSTRAINT `fk_tb_documents_tb_hr1` FOREIGN KEY (`hr_id`) REFERENCES `tb_hr` (`hr_id`),
  ADD CONSTRAINT `fk_tb_documents_tb_user1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);

--
-- Constraints for table `tb_hr`
--
ALTER TABLE `tb_hr`
  ADD CONSTRAINT `fk_tb_hr_tb_position1` FOREIGN KEY (`position_id`) REFERENCES `tb_position` (`position_id`);

--
-- Constraints for table `tb_personalleave`
--
ALTER TABLE `tb_personalleave`
  ADD CONSTRAINT `fk_tb_personalLeave_tb_hr1` FOREIGN KEY (`hr_id`) REFERENCES `tb_hr` (`hr_id`),
  ADD CONSTRAINT `fk_tb_personalLeave_tb_user1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);

--
-- Constraints for table `tb_resign`
--
ALTER TABLE `tb_resign`
  ADD CONSTRAINT `fk_tb_personalLeave_tb_hr100` FOREIGN KEY (`hr_id`) REFERENCES `tb_hr` (`hr_id`),
  ADD CONSTRAINT `fk_tb_personalLeave_tb_user100` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`),
  ADD CONSTRAINT `fk_tb_resign_tb_admin1` FOREIGN KEY (`admin_id`) REFERENCES `tb_admin` (`admin_id`);

--
-- Constraints for table `tb_timelog`
--
ALTER TABLE `tb_timelog`
  ADD CONSTRAINT `fk_tb_timelog_tb_data1` FOREIGN KEY (`data_id`) REFERENCES `tb_data` (`data_id`),
  ADD CONSTRAINT `fk_tb_timelog_tb_user` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `fk_tb_user_tb_position1` FOREIGN KEY (`position_id`) REFERENCES `tb_position` (`position_id`);

--
-- Constraints for table `tb_vacationleave`
--
ALTER TABLE `tb_vacationleave`
  ADD CONSTRAINT `fk_tb_personalLeave_tb_hr10` FOREIGN KEY (`hr_id`) REFERENCES `tb_hr` (`hr_id`),
  ADD CONSTRAINT `fk_tb_personalLeave_tb_user10` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
