-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2021 at 07:32 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pocolocodb`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `BookingID` int(16) NOT NULL,
  `CustomerID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking detail`
--

CREATE TABLE `booking detail` (
  `BookingDatailID` int(16) NOT NULL,
  `BookingID` int(16) NOT NULL,
  `RoomID` int(4) NOT NULL,
  `CheckIn` date NOT NULL,
  `CheckOut` date NOT NULL,
  `GuestName` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `Status` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `DateTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(10) NOT NULL,
  `firstName` varchar(32) DEFAULT NULL,
  `lastName` varchar(32) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(256) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employeeID` int(6) NOT NULL,
  `department` int(2) NOT NULL,
  `roleID` int(2) NOT NULL,
  `startDate` date NOT NULL,
  `shift` tinyint(1) NOT NULL,
  `em_firstname` varchar(32) NOT NULL,
  `em_lastname` varchar(32) NOT NULL,
  `identification` varchar(13) NOT NULL,
  `DOB` date NOT NULL,
  `gender` char(1) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(800) NOT NULL,
  `workStatus` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employeeID`, `department`, `roleID`, `startDate`, `shift`, `em_firstname`, `em_lastname`, `identification`, `DOB`, `gender`, `phone`, `email`, `password`, `workStatus`) VALUES
(110001, 1, 11, '2021-04-02', 3, 'Supavadee', 'Phusanam', '1102900036159', '2001-08-19', 'F', '0820116484', 'supavadeeying@outlook.com', 'e10adc3949ba59abbe56e057f20f883e', 'Y'),
(120001, 1, 12, '2021-04-09', 2, 'Ceo', 'Supavadee', '1234567892323', '2021-03-30', 'F', '0845695623', 'owner@mail.com', '25f9e794323b453885f5181f1b624d0b', 'Y'),
(210001, 2, 21, '2021-04-02', 1, 'Pan', 'Pan', '1234567896541', '2021-02-18', 'F', '0256565356', 'panpan@mail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Y'),
(220001, 2, 22, '2021-04-02', 2, 'Ying', 'Ja', '1234567896541', '2021-01-05', 'F', '0820446595', 'ying@mail.com', '25f9e794323b453885f5181f1b624d0b', 'Y'),
(320001, 3, 32, '2021-04-02', 2, 'Nacha', 'Hirunyakarn', '1234567896541', '2000-10-01', 'F', '0866677855', 'nacha.junk@mail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Y'),
(410001, 4, 41, '2021-04-02', 1, 'ฺBeam', 'Kak', '1234567891234', '2021-03-28', 'M', '0856423156', 'beam@mail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `hotelroom`
--

CREATE TABLE `hotelroom` (
  `roomID` int(4) NOT NULL,
  `roomTypeID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hotelroom`
--

INSERT INTO `hotelroom` (`roomID`, `roomTypeID`) VALUES
(1201, 11),
(1202, 11),
(1203, 11),
(1204, 11),
(1205, 11),
(1206, 11),
(1207, 11),
(1208, 11),
(1209, 11),
(1210, 11),
(1211, 11),
(1212, 11),
(1213, 11),
(1214, 11),
(1215, 11),
(1216, 10),
(1217, 10),
(1218, 10),
(1219, 10),
(1220, 10),
(1221, 10),
(1222, 10),
(1223, 10),
(1224, 10),
(1225, 10),
(1301, 10),
(1302, 10),
(1303, 10),
(1304, 10),
(1305, 10),
(1306, 11),
(1307, 11),
(1308, 11),
(1309, 11),
(1310, 11),
(1311, 12),
(1312, 12),
(1313, 12),
(1314, 12),
(1315, 12),
(1316, 12),
(1317, 12),
(1318, 12),
(1319, 12),
(1320, 12),
(1321, 13),
(1322, 13),
(1323, 13),
(1324, 13),
(1325, 13),
(1401, 13),
(1402, 13),
(1403, 13),
(1404, 13),
(1405, 13),
(1406, 10),
(1407, 10),
(1408, 10),
(1409, 10),
(1410, 10),
(1411, 11),
(1412, 11),
(1413, 11),
(1414, 11),
(1415, 11),
(1416, 11),
(1417, 11),
(1418, 11),
(1419, 11),
(1420, 11),
(1421, 12),
(1422, 12),
(1423, 12),
(1424, 12),
(1425, 12),
(1501, 12),
(1502, 12),
(1503, 12),
(1504, 12),
(1505, 12),
(1506, 12),
(1507, 12),
(1508, 12),
(1509, 12),
(1510, 12),
(1511, 10),
(1512, 10),
(1513, 10),
(1514, 10),
(1515, 10),
(1516, 13),
(1517, 13),
(1518, 13),
(1519, 13),
(1520, 13),
(1521, 13),
(1522, 13),
(1523, 13),
(1524, 13),
(1525, 13),
(1601, 11),
(1602, 11),
(1603, 11),
(1604, 11),
(1605, 11),
(1606, 13),
(1607, 13),
(1608, 13),
(1609, 13),
(1610, 13),
(1611, 11),
(1612, 11),
(1613, 11),
(1614, 11),
(1615, 11),
(1616, 11),
(1617, 11),
(1618, 11),
(1619, 11),
(1620, 11),
(1621, 12),
(1622, 12),
(1623, 12),
(1624, 12),
(1625, 12),
(1701, 12),
(1702, 12),
(1703, 12),
(1704, 12),
(1705, 12),
(1706, 12),
(1707, 12),
(1708, 12),
(1709, 12),
(1710, 12),
(1711, 10),
(1712, 10),
(1713, 10),
(1714, 10),
(1715, 10),
(1716, 10),
(1717, 10),
(1718, 10),
(1719, 10),
(1720, 10),
(1721, 10),
(1722, 10),
(1723, 10),
(1724, 10),
(1725, 10),
(1801, 10),
(1802, 10),
(1803, 10),
(1804, 10),
(1805, 10),
(1806, 12),
(1807, 12),
(1808, 12),
(1809, 13),
(1810, 13),
(1811, 13),
(1812, 13),
(1813, 13),
(1814, 13),
(1815, 13),
(1816, 13),
(1817, 13),
(1818, 13),
(1819, 13),
(1820, 13),
(1821, 10),
(1822, 10),
(1823, 10),
(1824, 10),
(1825, 10),
(1901, 10),
(1902, 11),
(1903, 11),
(1904, 11),
(1905, 11),
(1906, 12),
(1907, 12),
(1908, 12),
(1909, 12),
(1910, 12),
(1911, 12),
(1912, 12),
(1913, 13),
(1914, 13),
(1915, 13),
(1916, 13),
(1917, 13),
(1918, 13),
(1919, 13),
(1920, 13),
(1921, 13),
(1922, 13),
(1923, 13),
(1924, 13),
(1925, 13),
(2201, 11),
(2202, 11),
(2203, 11),
(2204, 11),
(2205, 11),
(2206, 11),
(2207, 11),
(2208, 11),
(2209, 11),
(2210, 11),
(2211, 11),
(2212, 10),
(2213, 10),
(2214, 10),
(2215, 10),
(2216, 12),
(2217, 12),
(2218, 12),
(2219, 12),
(2220, 12),
(2221, 12),
(2222, 12),
(2223, 12),
(2224, 12),
(2225, 12),
(2301, 13),
(2302, 13),
(2303, 13),
(2304, 13),
(2305, 13),
(2306, 13),
(2307, 13),
(2308, 13),
(2309, 13),
(2310, 13),
(2311, 11),
(2312, 11),
(2313, 11),
(2314, 11),
(2315, 11),
(2316, 12),
(2317, 12),
(2318, 12),
(2319, 12),
(2320, 12),
(2321, 13),
(2322, 13),
(2323, 13),
(2324, 13),
(2325, 13);

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE `promotion` (
  `promotionID` int(8) NOT NULL,
  `seasonID` int(4) NOT NULL,
  `roomTypeID` int(2) NOT NULL,
  `promotionName` varchar(250) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `discount` float(4,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`promotionID`, `seasonID`, `roomTypeID`, `promotionName`, `startDate`, `endDate`, `discount`) VALUES
(1, 1, 1, 'Christmas', '2021-04-16', '2021-04-17', 5.400),
(2, 3, 5, 'Songkran', '2021-04-16', '2021-04-17', 5.400),
(3, 3, 2, 'Christmas', '2021-04-15', '2021-04-17', 2.600),
(4, 2, 1, 'Ffff', '2021-04-04', '2021-04-06', 9.999),
(5, 1, 2, 'Xxxx', '2021-04-14', '2021-04-05', 9.999);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `roleID` int(2) NOT NULL,
  `roleName` varchar(64) NOT NULL,
  `salary` int(8) NOT NULL,
  `bonusRate` float(3,2) NOT NULL,
  `departmentID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`roleID`, `roleName`, `salary`, `bonusRate`, `departmentID`) VALUES
(11, 'Admin', 100000, 1.20, 1),
(12, 'Owner', 100000, 1.30, 1),
(13, 'Timestamp', 15000, 1.00, 1),
(21, 'Manager', 100000, 1.30, 2),
(22, 'Receptionist', 100000, 1.30, 2),
(23, 'Ccc', 2000, 0.30, 2),
(24, 'Ccc', 2000, 0.30, 2),
(31, 'Kitchen Manager', 15000, 1.00, 3),
(32, 'Chef', 100000, 1.30, 3),
(41, 'Housekeeping Manager', 15000, 1.00, 4),
(42, 'Maid', 100000, 1.30, 4);

-- --------------------------------------------------------

--
-- Table structure for table `roomdescription`
--

CREATE TABLE `roomdescription` (
  `roomTypeID` int(2) NOT NULL,
  `roomType` varchar(32) NOT NULL,
  `roomPrice` int(8) NOT NULL,
  `capacity` int(4) NOT NULL,
  `size` float(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roomdescription`
--

INSERT INTO `roomdescription` (`roomTypeID`, `roomType`, `roomPrice`, `capacity`, `size`) VALUES
(5, 'Grand Ballrooms', 7000, 100, 820.20),
(6, 'Seminar', 5000, 80, 500.30),
(10, 'Standard', 2000, 2, 28.00),
(11, 'Superior', 3000, 2, 32.00),
(12, 'Deluxe', 4500, 2, 50.00),
(13, 'Suite', 5000, 4, 80.00);

-- --------------------------------------------------------

--
-- Table structure for table `season`
--

CREATE TABLE `season` (
  `seasonID` int(4) NOT NULL,
  `seasonName` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `season`
--

INSERT INTO `season` (`seasonID`, `seasonName`) VALUES
(1, 'summer'),
(2, 'autumn'),
(3, 'spring'),
(4, 'winter');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`BookingID`);

--
-- Indexes for table `booking detail`
--
ALTER TABLE `booking detail`
  ADD PRIMARY KEY (`BookingDatailID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employeeID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `hotelroom`
--
ALTER TABLE `hotelroom`
  ADD PRIMARY KEY (`roomID`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotionID`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleID`);

--
-- Indexes for table `roomdescription`
--
ALTER TABLE `roomdescription`
  ADD PRIMARY KEY (`roomTypeID`);

--
-- Indexes for table `season`
--
ALTER TABLE `season`
  ADD PRIMARY KEY (`seasonID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
