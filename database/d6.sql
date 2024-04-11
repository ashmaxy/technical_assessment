-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2024 at 02:50 PM
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
-- Database: `d6`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `company_name` varchar(256) NOT NULL,
  `street_address` varchar(1000) NOT NULL,
  `city` varchar(256) NOT NULL,
  `state` varchar(256) NOT NULL,
  `zip_code` char(5) NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `company_name`, `street_address`, `city`, `state`, `zip_code`, `phone_number`) VALUES
(1, 'Bill Gates', 'Microsoft', 'NE 36th St', 'Redmond', 'Washington', '98052', '+1 425-882-8080'),
(2, 'Jeff Bezos', 'Amazon', '410 Terry Ave N', 'Seattle', 'Washington', '98109', '1-888-280-4331'),
(3, 'Steve Jobs', 'Apple', '1 Apple Park Way', 'Cupertino', 'California', '95014', '+1 408-996-1010');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_due_date` date NOT NULL,
  `invoice_number` varchar(256) NOT NULL,
  `tax_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `client_id`, `invoice_date`, `invoice_due_date`, `invoice_number`, `tax_rate`, `other_amount`) VALUES
(1, 2, '2024-04-11', '2024-05-11', '1234', 2.50, 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `invoice_item_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `invoice_item_default_id` int(11) NOT NULL,
  `taxed` tinyint(4) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`invoice_item_id`, `invoice_id`, `invoice_item_default_id`, `taxed`, `amount`) VALUES
(1, 1, 11, 1, 150.00),
(2, 1, 4, 0, 9.99),
(3, 1, 27, 0, 250.00);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_item_defaults`
--

CREATE TABLE `invoice_item_defaults` (
  `invoice_item_default_id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `taxed` tinyint(4) NOT NULL DEFAULT 0,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_item_defaults`
--

INSERT INTO `invoice_item_defaults` (`invoice_item_default_id`, `name`, `taxed`, `amount`) VALUES
(1, 'Arabica Coffee Beans', 1, 9.99),
(2, 'Robusta Coffee Beans', 0, 7.99),
(3, 'Espresso Roast', 0, 10.99),
(4, 'Espresso Blend', 0, 9.99),
(5, 'Regular Ground Coffee', 0, 8.99),
(6, 'Espresso Machine', 1, 155.00),
(7, 'Drip Coffee Maker', 1, 140.00),
(8, 'Coffee Grinder', 1, 159.00),
(9, 'French Press', 1, 150.00),
(10, 'Pour-Over Coffee Maker', 1, 200.00),
(11, 'AeroPress', 1, 150.00),
(12, 'Single-Serve Coffee Brewer', 1, 199.00),
(13, 'Earl Grey Bliss', 0, 9.99),
(14, 'Charmomile Serenity', 0, 9.99),
(15, 'Minty Fresh Green', 0, 9.99),
(16, 'Spiced Chat Delight', 0, 9.99),
(17, 'Berry Burst Herbal', 0, 9.99),
(18, 'Jasmine Blossom Elegance', 0, 9.99),
(19, 'English Breakfast Classic', 0, 9.99),
(20, 'Teapot', 1, 74.00),
(21, 'Tea Infuser', 1, 80.00),
(22, 'Tea Kettle', 1, 50.00),
(23, 'Tea Strainer', 1, 50.00),
(24, 'Tea Tubmler', 1, 50.00),
(25, 'Electric Tea Maker', 1, 10.00),
(26, 'Tea Ball Infuser', 1, 50.00),
(27, 'Shipping @ 250', 0, 250.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD KEY `client_name_index` (`name`),
  ADD KEY `company_name_index` (`company_name`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `fk_client_id` (`client_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`invoice_item_id`),
  ADD KEY `fk_invoice_id` (`invoice_id`),
  ADD KEY `fk_invoice_item_default_id` (`invoice_item_default_id`);

--
-- Indexes for table `invoice_item_defaults`
--
ALTER TABLE `invoice_item_defaults`
  ADD PRIMARY KEY (`invoice_item_default_id`),
  ADD KEY `defualt_item_name` (`invoice_item_default_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoice_item_defaults`
--
ALTER TABLE `invoice_item_defaults`
  MODIFY `invoice_item_default_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_client_id` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `fk_invoice_id` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`),
  ADD CONSTRAINT `fk_invoice_item_default_id` FOREIGN KEY (`invoice_item_default_id`) REFERENCES `invoice_item_defaults` (`invoice_item_default_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
