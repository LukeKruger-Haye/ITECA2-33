-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql309.infinityfree.com
-- Generation Time: Jun 30, 2025 at 12:04 PM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_39156532_marketplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`address_id`, `user_id`, `street`, `city`, `province`, `zip`) VALUES
(1, 1, '1 Apple Street', 'Durbanville', 'Western Cape', '7550');

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `card_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_number` varbinary(255) DEFAULT NULL,
  `expiry` varbinary(255) DEFAULT NULL,
  `cvv` varchar(255) DEFAULT NULL,
  `cardholder_name` varbinary(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`card_id`, `user_id`, `card_number`, `expiry`, `cvv`, `cardholder_name`, `created_at`, `updated_at`) VALUES
(1, 1, 0x386d746f41735a5436774a674b56375163574f792f3231594e6a6b354b30704d624549344e486844525449775331566852326c784b7a524451556474535570695a58493053324a4356546c4359546739, 0x75344b783475697844355668492b6438696e577169304a79576b706f627a4e554c325245566d6b76534868475554557a5258633950513d3d, 'M0xNm9LYPdaZ7FRNU8lKqjJ0eDhSSWo2UHdVdzZrNDJETFdiV0E9PQ==', 0x356a6334376e695758644f5976757077734d3737323342614e6a63316147686c627a566f526a526e53486c7651555a565554513461334e4362475a77556d4e7852323472543159344d57394c4e553039, '2025-06-30 14:30:32', '2025-06-30 14:35:28');

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `listing_id` int(11) NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`listing_id`, `seller_id`, `name`, `description`, `price`, `location`, `created_at`) VALUES
(3, 1, 'Oranges', 'Freshly harvested and locally sourced oranges', '15.00', NULL, '2025-06-03 14:05:57'),
(5, 1, 'Avocado', 'AVOCADOS', '899.00', NULL, '2025-06-03 14:22:58'),
(6, 1, 'Porsche 911 GT3 RS', 'gt3 my beloved category &lt;3', '1999999.00', NULL, '2025-06-03 14:24:14'),
(7, 2, 'Titleist T100 Irons', 'Titleist T100 Irons, tour level irons designed by Titleist.', '24999.00', NULL, '2025-06-03 14:42:53'),
(8, 1, 'Table', 'Table', '499.00', NULL, '2025-06-04 08:46:12'),
(9, 1, 'Fresh Grapes', 'Fresh grapes', '25.00', NULL, '2025-06-06 11:44:18'),
(14, 3, 'Couch', 'Couch to sit on', '5999.00', '', '2025-06-06 19:01:37'),
(15, 3, 'Red Couch', 'A RED couch to sit on', '8999.00', NULL, '2025-06-06 19:02:14'),
(16, 3, 'Lenovo Laptop', 'Windows 11 Home\r\n15.6-inch FHD display\r\n8GB RAM and 512GB SSD storage\r\nIntel® Core™ i5-12450H processor\r\nIntel® UHD graphics\r\nHD 720p with privacy shutter\r\nStereo speakers, 1.5W x2, Dolby® Audio™', '15999.00', NULL, '2025-06-06 19:03:41'),
(17, 3, 'Tennis Balls', 'Tennis balls', '199.00', NULL, '2025-06-06 19:05:48'),
(18, 3, 'Homemade dog toys', 'Homemade toys for dogs, made from locally sourced materials!', '499.00', NULL, '2025-06-06 19:06:57'),
(19, 3, 'Dell Monitor', 'Monitor from Dell', '3999.00', NULL, '2025-06-06 19:07:46'),
(20, 1, 'Vinyl Player', 'Vinyl player:\r\n\r\nDual Bluetooth Input &amp; Output for Versatile Connectivity\r\nPowerful 40W Hi-Fi Stereo Speakers for Rich Sound\r\nPre-mounted Moving Magnet Cartridge for Superior Fidelity\r\nBuilt-in Phono Preamp for Seamless Integration\r\nSmooth Belt Drive System for Reduced Vibration\r\nUSB-to-PC Connectivity for Digital Archiving\r\nElegant Design with Accessories Included', '2999.00', NULL, '2025-06-24 15:29:13'),
(22, 4, 'Apples', 'Freshly harvested apples from Tom Apple Apple Farms Inc. based in the Western Cape', '19.99', NULL, '2025-06-25 11:38:45'),
(23, 1, 'apples', ';laksjdfkl;jsadf', '250.00', NULL, '2025-06-27 12:48:59');

-- --------------------------------------------------------

--
-- Table structure for table `listing_media`
--

CREATE TABLE `listing_media` (
  `media_id` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` enum('image','video') DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listing_media`
--

INSERT INTO `listing_media` (`media_id`, `listing_id`, `file_path`, `file_type`, `display_order`) VALUES
(3, 3, '/uploads/listings/3/683f014566d35_1748959557.jpg', 'image', 1),
(4, 3, '/uploads/listings/3/683f014567b2e_1748959557.jpg', 'image', 2),
(5, 3, '/uploads/listings/3/683f0145688d2_1748959557.jpg', 'image', 3),
(6, 5, '/uploads/listings/5/683f0542a58bb_1748960578.jpg', 'image', 1),
(7, 6, '/uploads/listings/6/683f058e47c4b_1748960654.jpg', 'image', 1),
(8, 7, '/uploads/listings/7/683f09ed867d0_1748961773.png', 'image', 1),
(9, 8, '/uploads/listings/8/684007d43ba01_1749026772.jpg', 'image', 1),
(10, 9, '/home/vol6_5/infinityfree.com/if0_39156532/htdocs/new_summative/uploads/listings/9/6842d49279072_1749210258.jpg', 'image', 1),
(15, 14, '/uploads/listings/14/6842d8a132d6b_1749211297.jpg', 'image', 1),
(16, 15, '/uploads/listings/15/6842d8c6b3d58_1749211334.png', 'image', 1),
(17, 16, '/uploads/listings/16/6842d91de2ea8_1749211421.png', 'image', 1),
(18, 17, '/uploads/listings/17/6842d99cef4b6_1749211548.jpg', 'image', 1),
(19, 17, '/uploads/listings/17/6842d99cef7b0_1749211548.png', 'image', 2),
(20, 18, '/uploads/listings/18/6842d9e1ea34a_1749211617.jpg', 'image', 1),
(21, 19, '/uploads/listings/19/6842da127d009_1749211666.png', 'image', 1),
(22, 20, '/uploads/listings/20/685ac449784c9_1750778953.jpg', 'image', 1),
(24, 22, '/uploads/listings/22/685bdfc50a4fd_1750851525.jpg', 'image', 1),
(25, 22, '/uploads/listings/22/685bdfe86b77f_1750851560.jpg', 'image', 2),
(26, 23, '/uploads/listings/23/685e933bc9814_1751028539.jpg', 'image', 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `date_created`) VALUES
(1, 1, 4, 'Can I order a pickup at your place?', '2025-06-27 08:53:35'),
(2, 5, 1, 'This is a test', '2025-06-27 08:55:39'),
(3, 1, 5, 'I like your music', '2025-06-27 08:56:08'),
(4, 5, 1, 'Thanks', '2025-06-27 08:56:42'),
(5, 1, 2, 'Do you have T200 irons in stock?', '2025-06-27 12:14:40'),
(6, 1, 3, 'Yo do you have any red tennis balls?', '2025-06-27 12:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `listings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `listings`, `created_at`) VALUES
(1, 4, '27998.00', '[\"20\",\"7\"]', '2025-06-25 11:32:26'),
(2, 1, '15999.00', '[\"16\"]', '2025-06-26 18:30:30'),
(3, 1, '9018.99', '[\"22\",\"15\"]', '2025-06-26 18:31:27'),
(4, 1, '2009497.00', '[\"15\",\"6\",\"18\"]', '2025-06-26 18:35:15'),
(5, 1, '698.00', '[\"18\",\"17\"]', '2025-06-26 19:28:28'),
(6, 1, '698.00', '[\"18\",\"17\"]', '2025-06-26 19:39:04'),
(7, 1, '24999.00', '[\"7\"]', '2025-06-27 12:15:23'),
(8, 1, '199.00', '[\"17\"]', '2025-06-27 12:29:06'),
(9, 1, '499.00', '[\"18\"]', '2025-06-27 12:50:37');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `listing_id`, `rating`, `message`, `date_created`) VALUES
(1, 5, 6, 3, 'I prefer the BMW M3 GT3', '2025-06-26 13:14:06'),
(2, 1, 22, 4, 'Fresh apples', '2025-06-26 13:42:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `privileges` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `first_name`, `last_name`, `phone`, `created_at`, `privileges`) VALUES
(1, 'lukekrugerhaye@gmail.com', '$2y$10$XvhbvhbF2h/X/US.vQ.nveGfH491Z1BC/HRxkNiP.8V6S/q8Sz.UG', 'Luke', 'Kruger-Haye', '0810313816', '2025-05-26 20:19:16', 'admin'),
(2, 'ryan@gmail.com', '$2y$10$Kd1c182OAeOSUkusMImHweUhf7FGkl7p0hRW1i/urnNTj8WxHSHY6', 'Ryan', 'Ryan', '0728889595', '2025-06-03 14:40:14', 'user'),
(3, 'johndoe@gmail.com', '$2y$10$Ihla4jRlFqA3.p9C6IdYO.9DU20P9Xu8tkrd4qFNMu768r58GQJZW', 'John', 'Doe', NULL, '2025-06-06 18:35:17', 'user'),
(4, 'tomapple@gmail.com', '$2y$10$mGIUtEt0/4.erkONjqyoGOkiAlFxwUAM60YaKAiiEERntN2tLnFuq', 'Tom', 'Apple', NULL, '2025-06-25 11:30:48', 'user'),
(5, 'mj@gmail.com', '$2y$10$1D0LX/f9kuQ.nM1WbGsBiOs9AVUrWsBpflgvO55Y1E/de0Mx1cu2y', 'Michael', 'Jackson', NULL, '2025-06-26 13:13:30', 'user'),
(6, 'admin@admin.com', '$2y$10$8GQgTacxYL7hgoKxILPpceCJDbgjTrnv83G1VwLE1hbphNG57sxoi', 'admin', 'admin', '', '2025-06-30 15:03:01', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`card_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`listing_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `listing_media`
--
ALTER TABLE `listing_media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `listing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `listing_media`
--
ALTER TABLE `listing_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `listings`
--
ALTER TABLE `listings`
  ADD CONSTRAINT `listings_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `listing_media`
--
ALTER TABLE `listing_media`
  ADD CONSTRAINT `listing_media_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`listing_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
