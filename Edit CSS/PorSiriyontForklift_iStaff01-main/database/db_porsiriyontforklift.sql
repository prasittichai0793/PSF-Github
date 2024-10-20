-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 20, 2024 at 06:56 AM
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
  `hr_id` int(11) NOT NULL,
  `docs_status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(3, 'E_001', 'r', '2024-09-06', '0 ปี 1 เดือน 5 วัน', 2147483647, '909009786', '2024-07-19', 0, 'male', 'g', 'g', 1);

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
  `PLeave_docs` text,
  `PLeave_date` datetime DEFAULT NULL,
  `hr_id` int(11) NOT NULL,
  `PLeave_status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `resign_date` datetime DEFAULT NULL,
  `hr_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `resign_status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_timelog`
--

CREATE TABLE `tb_timelog` (
  `timelog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timelog_date` date DEFAULT NULL,
  `timelog_in` datetime DEFAULT NULL,
  `timelog_out` datetime DEFAULT NULL,
  `data_id` int(11) NOT NULL,
  `timelog_status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `user_age` int(11) DEFAULT NULL,
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
(1, 'A_001', 'user', '2020-11-21', '3 ปี 10 เดือน 12 วัน', 2147483647, '871164552', '1989-07-13', 35, '100 วัน', 'user', '123', 'female', 1),
(3, 'A_002', 'นายอภิศักดิ์ น้าวัฒนไพบูลย์', '2022-02-02', '2 ปี 8 เดือน 9 วัน', 2147483647, '451515515', '2021-07-06', 3, '69 วัน', 'a_aphisak', 'a12345678', 'ชาย', 1),
(5, 'B_001', 'นายสมประสงค์ แจ้งกระจ่าง', '2008-05-07', '16 ปี 4 เดือน 27 วัน', 124525245, '868324178', '1964-01-13', 60, '427 วัน', 'b_somprasong', '01131964', 'ชาย', 2),
(8, 'D_005', 'sdf', '2024-06-12', '0 ปี 3 เดือน 29 วัน', 2147483647, '0872164298', '2024-04-13', 0, '8 วัน', 'f', 'f', 'ชาย', 4),
(12, 'A_008', 'หปฟ', '2019-01-29', '5 ปี 8 เดือน 19 วัน', 2147483647, '0872164298', '2019-06-11', 5, '148 วัน', 'lllll', 'สาสส', 'ชาย', 1),
(13, 'A_009', 'สสส', '2018-06-04', '6 ปี 4 เดือน 14 วัน', 2147483647, '0814879788', '2024-07-11', 0, '166', 'pp', 'สวสว', 'male', 1);

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
  `hr_id` int(11) NOT NULL,
  `VLeave_status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_documents`
--
ALTER TABLE `tb_documents`
  MODIFY `docs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_hr`
--
ALTER TABLE `tb_hr`
  MODIFY `hr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_personalleave`
--
ALTER TABLE `tb_personalleave`
  MODIFY `PLeave_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_position`
--
ALTER TABLE `tb_position`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_resign`
--
ALTER TABLE `tb_resign`
  MODIFY `resign_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_timelog`
--
ALTER TABLE `tb_timelog`
  MODIFY `timelog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tb_vacationleave`
--
ALTER TABLE `tb_vacationleave`
  MODIFY `VLeave_id` int(11) NOT NULL AUTO_INCREMENT;

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
