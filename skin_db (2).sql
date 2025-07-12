-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2025 at 09:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`) VALUES
(1, 'Prachi S Kalekar', 'prachisk', 'tuffy@28');

-- --------------------------------------------------------

--
-- Table structure for table `faq_concerns`
--

CREATE TABLE `faq_concerns` (
  `id` int(11) NOT NULL,
  `concern_text` text NOT NULL,
  `frequency` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq_concerns`
--

INSERT INTO `faq_concerns` (`id`, `concern_text`, `frequency`) VALUES
(1, 'Slightly oily at the nose area and dry near cheeks', 1),
(6, 'Dry around cheeks', 1),
(9, 'Frequent breakouts near the forehead', 1),
(10, 'Frequent breakouts near the nose.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `totalAmount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `description` text NOT NULL,
  `tags` varchar(50) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `tags`, `brand`, `image`) VALUES
(1, 'Sunscreen', 499, 'Mineral sunscreen with SPF 50 \r\nFormulated for sensitive skin', 'Sunscreen', 'CETAPHIL', 'uploads/sunscreen.jpg'),
(2, 'Moisturizer', 699, 'Moisturizing Lotion for dry to normal skin', 'Moisturizer', 'CETAPHIL', 'uploads/moisturizer.jpg'),
(3, 'Toner', 499, 'Healthy radiance\r\nBrightness Refresh Toner\r\nHydrates,Refreshes & Brightens skin', 'Toner', 'CETAPHIL', 'uploads/Toner.jpg'),
(4, 'Toner', 499, 'Healthy radiance\r\nHydrating Toner', 'Toner', 'CETAPHIL', 'uploads/Cetaphil Canada.jpeg'),
(5, 'Serum', 599, 'Aqualogica Hydrate+ Hyaluronic Acid Face Serum For Dewy & Plump Skin (30ml)', 'Serum', 'AQUALOGICA', 'uploads/Aqualogica Hydrate+ Hyaluronic Acid Face Serum For Dewy & Plump Skin (30ml).jpeg'),
(6, 'Serum', 499, 'Niacinamide 10% +Zinc 1%', 'Serum', 'ORDINARY', 'uploads/The Ordinary Products Have Landed At Nordstrom — Here\'s What We\'re Buying.jpeg'),
(7, 'Gentle Skin Cleanser', 399, 'Gentle skin cleanser\r\n', 'Cleanser', 'DOT & KEY', 'uploads/CleanserC.jpg'),
(8, 'Moisturizer', 799, 'Ceramides & Hyaluronic Skin barrier repair face cream', 'Moisturizer', 'DOT & KEY', 'uploads/MoisturizerC.jpg'),
(9, 'Serum', 399, 'Healthy radiance\r\nBrightening C serum', 'Serum', 'CETAPHIL', 'uploads/C Serum.jpg'),
(10, 'Exfoliator Scrub', 499, 'Papaya & Vitamin C face scrub', 'Exfoliator', 'AQUALOGICA', 'uploads/ExfoliatorB.jpg'),
(11, 'Serum', 499, 'Glycolic Acid 7% Toning Solution\r\npH 3.6', 'Serum', 'ORDINARY', 'uploads/The ordinary glycolic acid.jpeg'),
(12, 'Sunscreen', 499, 'Wild Berries & Alpha Arbutin \r\nSPF 50+', 'Sunscreen', 'AQUALOGICA', 'uploads/Sunscreen2B.jpg'),
(13, 'Toner', 399, 'Saccharomyces Ferment 30% MilkyToner', 'Toner', 'ORDINARY', 'uploads/TonerD.jpg'),
(14, 'Skin Cleanser', 499, 'Dry to normal ,Sensitive skin', 'Cleanser', 'CETAPHIL', 'uploads/Cleanser.jpg'),
(15, 'Extra gentle daily scrub', 399, 'Gently exfoliates with micro fine particles, Cleanses without over drying, non-irritating', 'Exfoliator', 'CETAPHIL', 'uploads/Exfoliator scrub.jpg'),
(16, 'Suncare ', 599, 'Mineral UV Filters SPF 30 with Antioxidants', 'Sunscreen', 'ORDINARY', 'uploads/sunscreenD.jpg'),
(17, 'Moisturizer', 699, 'Natural Moisturizing Factors + PhytoCeramides\r\nRich Surface Hydration formula', 'Moisturizer', 'ORDINARY', 'uploads/MoisturizerD.jpg'),
(18, 'Toning Mist', 399, 'Coconut Water & Hyaluronic Acid', 'Toner', 'AQUALOGICA', 'uploads/TonerB.jpg'),
(19, 'Face Serum', 399, 'Green tea & Salicylic Acid', 'Serum', 'AQUALOGICA', 'uploads/SerumB.jpg'),
(20, 'Foam Cleanser', 399, 'Hydrating Foam Cleanser\r\nPapaya & Vitamin C', 'Cleanser', 'AQUALOGICA', 'uploads/CleanserB.jpg'),
(21, 'Toner', 499, 'Cica Calming skin clarifying toner\r\nGreen tea & Niacinamide', 'Toner', 'DOT & KEY', 'uploads/TonerC.jpg'),
(22, 'Sunscreen', 499, 'Dewy sunscreen\r\nWatermelon & Niacinamide \r\nSPF 50+', 'Sunscreen', 'AQUALOGICA', 'uploads/SunscreenB.jpg'),
(23, 'Skin clearing serum', 499, 'Cica & Cactus 2% Salicylic + Zinc\r\nFor acne and oil control', 'Serum', 'DOT & KEY', 'uploads/serumC.jpg'),
(24, 'Sunscreen', 599, 'Cica Calming Niacinamide Sunscreen\r\nSPF 50 PA+++', 'Sunscreen', 'DOT & KEY', 'uploads/SunscreenC.jpg'),
(25, 'Vitamin C Sheet Mask', 70, 'Vitamin C + Hyaluronic Acid sheet mask\r\nDull, Uneven Skin', 'Sheet Mask', 'GARNIER', 'uploads/Vitamin C Sheet Mask for Glowing & Smooth Skin _ Garnier UK.jpeg'),
(26, 'Nutri Bomb Sheet Mask', 70, 'Milky Tissue Mask \r\nAlmond Milk + Hyaluronic Acid', 'Sheet Mask', 'GARNIER', 'uploads/An Explosion Of Nourishment _ Garnier Nutri Bomb Almond & Hyaluronic Acid Tissue Mask - Luke Sam Sowden.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `userdetails`
--

CREATE TABLE `userdetails` (
  `id` int(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` int(10) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userdetails`
--

INSERT INTO `userdetails` (`id`, `name`, `username`, `email`, `phone`, `password`) VALUES
(1, 'Prachi Kalekar', 'prachisk', 'kalekarprachi31@gmail.com', 2147483647, '$2y$10$9M4GtRxMd/C8/mX1RtqoqOJHdiBKjnfR4qMQ7EcMNabVWg1hzMx5O');

-- --------------------------------------------------------

--
-- Table structure for table `user_concerns`
--

CREATE TABLE `user_concerns` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `concern_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_concerns`
--

INSERT INTO `user_concerns` (`id`, `user_id`, `concern_text`, `created_at`) VALUES
(1, 1, 'Slightly oily at the nose area and dry near cheeks', '2025-07-10 06:21:08'),
(6, 1, 'Dry around cheeks', '2025-07-10 06:41:16'),
(7, 1, 'Frequent breakouts near the forehead', '2025-07-11 15:28:34'),
(10, 1, 'Frequent breakouts near the nose.', '2025-07-11 15:34:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq_concerns`
--
ALTER TABLE `faq_concerns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userdetails`
--
ALTER TABLE `userdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_concerns`
--
ALTER TABLE `user_concerns`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faq_concerns`
--
ALTER TABLE `faq_concerns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `userdetails`
--
ALTER TABLE `userdetails`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_concerns`
--
ALTER TABLE `user_concerns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
