-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2024 at 04:49 AM
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
-- Database: `db_inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_inventory`
--

CREATE TABLE `tb_inventory` (
  `product_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_inventory`
--

INSERT INTO `tb_inventory` (`product_id`, `name`, `price`, `category`, `quantity`, `size`, `image_url`) VALUES
(10060, 'Gi-pipes1', '260', 'GI-PIPES', '100', '½', 'uploads/pr5.jpg'),
(10061, 'Gi-pipes2', '360', 'GI-PIPES', '100', '¾', 'uploads/pr5.jpg'),
(10062, 'Gi-pipes3', '480', 'GI-PIPES', '100', '1', 'uploads/pr5.jpg'),
(10063, 'Gi-pipes4', '540', 'GI-PIPES', '100', '1 1⁄4', 'uploads/pr5.jpg'),
(10064, 'Gi-pipes5', '780', 'GI-PIPES', '100', '1 ½', 'uploads/pr5.jpg'),
(10065, 'Gi-pipes6', '1150', 'GI-PIPES', '110', '2', 'uploads/pr5.jpg'),
(10066, 'Flat Bar1', '250', 'FLAT BAR', '80', '1', 'uploads/flat4.jpg'),
(10067, 'Flat Bar2', '390', 'FLAT BAR', '70', '1 ½', 'uploads/flat4.jpg'),
(10068, 'Flat Bar3', '460', 'FLAT BAR', '60', '2', 'uploads/flat4.jpg'),
(10069, 'Angle Bar1', '350', 'ANGLE BAR', '50', '1x1', 'uploads/23.-SS-ANGLE-BAR.jpg'),
(10070, 'Angle Bar2', '480', 'ANGLE BAR', '60', '1½ x 1½', 'uploads/23.-SS-ANGLE-BAR.jpg'),
(10071, 'Angle Bar3', '590', 'ANGLE BAR', '75', '2x2', 'uploads/23.-SS-ANGLE-BAR.jpg'),
(10072, 'Angle Bar4 (Green)', '700', 'ANGLE BAR', '87', '2x2', 'uploads/images.jfif'),
(10073, 'Angle Bar5 (Green)', '420', 'ANGLE BAR', '100', '1x1', 'uploads/images.jfif'),
(10074, 'Angle Bar6 (Green)', '580', 'ANGLE BAR', '90', '1½ x 1½ ', 'uploads/images.jfif'),
(10075, 'Purlins1 (1.2)', '360', 'PURLINS', '100', '2X3', 'uploads/steel-purlins-min.jpg'),
(10076, 'Purlins2 (1.5)', '460', 'PURLINS', '50', '2x3', 'uploads/steel-purlins-min.jpg'),
(10077, 'Purlins3 (1.2)', '420', 'PURLINS', '40', '2x4', 'uploads/steel-purlins-min.jpg'),
(10078, 'Purlins4 (1.5)', '520', 'PURLINS', '30', '2x4', 'uploads/steel-purlins-min.jpg'),
(10079, 'Purlins5 (1.2)', '560', 'PURLINS', '45', '2x6', 'uploads/steel-purlins-min.jpg'),
(10080, 'Purlins6 (1.5)', '640', 'PURLINS', '56', '2x6', 'uploads/steel-purlins-min.jpg'),
(10081, 'Steel Matting1', '650', 'STEEL MATTING', '20', '6', 'uploads/images (1).jfif'),
(10082, 'Steel Matting2', '420', 'STEEL MATTING', '23', '4', 'uploads/images (1).jfif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_inventory`
--
ALTER TABLE `tb_inventory`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_inventory`
--
ALTER TABLE `tb_inventory`
  MODIFY `product_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10083;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
