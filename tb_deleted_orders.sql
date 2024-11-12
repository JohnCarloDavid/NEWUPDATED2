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

--
-- Dumping data for table `tb_deleted_orders`
--

INSERT INTO `tb_deleted_orders` (`order_id`, `customer_name`, `product_name`, `size`, `quantity`, `order_date`, `status`, `deleted_at`) VALUES
(82, 'john doe', 'Tubular1', '1x3', 1, '2024-10-25', 0, '2024-10-27 01:59:28'),
(83, 'john david', 'Tubular2', '1x4', 3, '2024-10-25', 0, '2024-10-27 01:59:29'),
(85, 'john carlo', 'Tubular3\r\n', '1x3', 12, '2024-10-25', 0, '2024-10-27 01:59:29'),
(90, 'john doe', 'Gi-pipes1', '½', 5, '2024-11-02', 0, '2024-11-02 01:58:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_deleted_orders`
--
ALTER TABLE `tb_deleted_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_deleted_orders`
--
ALTER TABLE `tb_deleted_orders`
  MODIFY `order_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
