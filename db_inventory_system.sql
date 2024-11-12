-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 09:22 AM
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
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `user_id` int(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`user_id`, `user_name`, `password`) VALUES
(1, 'admin', '$2y$10$rSMccFaYUvnxGgrtI0Q66.W7roAKMnB39.QiI/KN5CUNTm9HXzmPe');

-- --------------------------------------------------------

--
-- Table structure for table `tb_deleted_orders`
--

CREATE TABLE `tb_deleted_orders` (
  `order_id` int(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `order_date` date NOT NULL,
  `status` int(255) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1001, 'Tubular1', '100\r\n', 'Tubular', '1', '1x1', ''),
(1002, 'Tubular2', '110', 'Tubular', '30', '1x2', ''),
(1003, 'Tubular3', '120', 'Tubular', '50', '2x2', ''),
(1004, 'Tubular4', '130', 'Tubular', '50', '2x3', ''),
(1005, 'Tubular5', '140', 'Tubular', '29\r\n', '2x4', ''),
(1006, 'Tubular6', '150', 'Tubular', '40', '4x4\r\n', ''),
(2001, 'C-purlins1', '160', 'C-Purlins', '45', '2x3', ''),
(2002, 'C-purlins2', '170', 'C-Purlins', '15', '2x4\r\n', ''),
(2003, 'C-Purlins3', '150', 'C-Purlins', '20', '2x2', ''),
(3001, 'Wall Angles1', '180', 'Angle Bars', '15', '10x20', ''),
(3002, 'Angle Bar1', '190', 'Angle Bars', '17', '40x40', ''),
(4001, 'Gi-Pipes1', '230', 'Pipes', '40', '1/2\"-12\" (21.3-323.9mm)', ''),
(5001, 'Galvanized Steel 1', '240', 'Steel Sheet', '84', '26 Inches W/ 8 Feet L', ''),
(10047, 'Gi-pipes2', '132', 'Pipes', '14', '1', ''),
(10048, 'Gi-pipes3', '1243', 'Pipes', '133', '2', ''),
(10049, 'Gi-pipes4', '123', 'Pipes', '40', '11', ''),
(10051, 'nail', '30', 'nails', '50', '1', ''),
(10052, 'spiderman', '50', 'hero', '1', 'Large', 'uploads/spiderman.png'),
(10053, 'mario', '100', 'cartoon', '1', 'Large', 'uploads/mario.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_orders`
--

CREATE TABLE `tb_orders` (
  `order_id` int(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_orders`
--

INSERT INTO `tb_orders` (`order_id`, `customer_name`, `product_name`, `quantity`, `order_date`, `status`, `size`, `deleted_at`) VALUES
(70, 'john doe', 'Tubular1', '5', '2024-10-23', '0', '1x1', NULL),
(71, 'janedoe', 'Tubular1', '5', '2024-10-23', 'Completed', '1x1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_order_items`
--

CREATE TABLE `tb_order_items` (
  `item_id` int(255) NOT NULL,
  `order_id` int(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_settings`
--

CREATE TABLE `tb_settings` (
  `id` int(255) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tb_deleted_orders`
--
ALTER TABLE `tb_deleted_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tb_inventory`
--
ALTER TABLE `tb_inventory`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tb_orders`
--
ALTER TABLE `tb_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tb_order_items`
--
ALTER TABLE `tb_order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `tb_settings`
--
ALTER TABLE `tb_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_deleted_orders`
--
ALTER TABLE `tb_deleted_orders`
  MODIFY `order_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `tb_inventory`
--
ALTER TABLE `tb_inventory`
  MODIFY `product_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10054;

--
-- AUTO_INCREMENT for table `tb_orders`
--
ALTER TABLE `tb_orders`
  MODIFY `order_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `tb_order_items`
--
ALTER TABLE `tb_order_items`
  MODIFY `item_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_settings`
--
ALTER TABLE `tb_settings`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_order_items`
--
ALTER TABLE `tb_order_items`
  ADD CONSTRAINT `tb_order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tb_order_items` (`item_id`),
  ADD CONSTRAINT `tb_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tb_order_items` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
