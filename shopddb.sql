-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2024 at 10:40 AM
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
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_code`
--

CREATE TABLE `admin_code` (
  `admin_code` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_code`
--

INSERT INTO `admin_code` (`admin_code`) VALUES
('hassmartproject');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `banner_name` varchar(255) NOT NULL,
  `webpage_url` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `banner_name`, `webpage_url`, `image_path`) VALUES
(11, 'banner1', 'http://localhost/om/shop.php', 'uploads/Screenshot 2023-08-26 144618.png'),
(12, 'banner2', 'http://localhost/om/shop.php', 'uploads/Screenshot 2024-03-03 155736.png');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(67, 35, 31, 'HP 15s I5 12th gen (8GB/512GB)', 72999, 2, 'hp15s.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(9, 35, 'Rajput Hariom', 'om@gmail.com', '7704057576', 'Very nice website ');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(13, 35, 'RAJPUT HARIOM PRAMOD SINGH', '7704050505', 'om@gmail.com', 'cash on delivery', 'flat no. surat mm surat gujarat India - 395010', ', Vivo T2x ( 1 )', 15000, '17-Feb-2024', 'completed'),
(14, 35, 'Hariom', '7704057578', 'hariomsingh21113@gmail.com', 'paytm', 'flat no. 284,Rishinagar society,godadara godadara Surat Gujarat India - 395010', ', Apple I phone 15 pro max(256 GB) ( 1 )', 139990, '05-Mar-2024', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `details`, `price`, `image`, `image2`, `image3`, `image4`) VALUES
(29, 'Apple I phone 15 pro max(256 GB)', 'Electronic', 'Brand	Apple\r\nModel Name	iPhone 15\r\nNetwork Service Provider	Unlocked for All Carriers\r\nOperating System	iOS\r\nCellular Technology	5G\r\nAbout this item\r\nDYNAMIC ISLAND COMES TO IPHONE 15 — Dynamic Island bubbles up alerts and Live Activities — so you don’t miss them while you’re doing something else. You can see who’s calling, track your next ride, check your flight status, and so much more.\r\nINNOVATIVE DESIGN — iPhone 15 features a durable color-infused glass and aluminum design. It’s splash, wate', 139990, 'hwns4rp1.png', NULL, NULL, NULL),
(30, 'Samsung Galaxy Z Fold 5 (256 GB)', 'Electronic', '\r\nBrand	Samsung\r\nModel Name	Samsung Galaxy Fold5 5G\r\nNetwork Service Provider	Unlocked for All Carriers\r\nOperating System	Android 13.0\r\nCellular Technology	5G\r\nAbout this item\r\nStands out. Stands up. Unfolds. The Galaxy Z Fold5 does a lot in one hand with its 15.73 cm(6.2-inch) Cover Screen. Unfolded, the 19.21 cm(7.6-inch) Main Screen lets you really get into the zone. Pushed-back bezels and the Under Display Camera means there&#39;s more screen and no black dot getting between you and the brea', 159999, 'Samsung-Galaxy-Z-Fold-5.jpg', NULL, NULL, NULL),
(31, 'HP 15s I5 12th gen (8GB/512GB)', 'Electronic', '\r\nBrand	HP\r\nModel Name	15s\r\nScreen Size	15.6 Inches\r\nColour	Natural Silver\r\nHard Disk Size	512 GB\r\nCPU Model	Core i5\r\nRAM Memory Installed Size	8 GB\r\nOperating System	Windows 11 Home\r\nSpecial Feature	FHD, Micro-Edge Display\r\nGraphics Card Description	Integrated\r\nSee more\r\nAbout this item\r\nProcessor: Intel Core i5-1235U(up to 4.4 GHz with Intel Turbo Boost Technology(2g), 12 MB L3 cache, 10 cores, 12 threads)|Memory:8 GB DDR4-3200 MHz RAM (2 x 4 GB), Upto 16 GB DDR4-3200 MHz RAM (2 x 8 GB)| Stora', 72999, 'hp15s.jpg', NULL, NULL, NULL),
(33, 'Nike Jordan air 2 shoes', 'Clothes', 'Nike jordan shoes', 79999, 'shoess.png', '', '', ''),
(34, 'Samsung Galaxy S24 5G', 'Electronic', 'Samsung Mobile [hone are the best mobile phone in the world', 109999, 'Samsung-Galaxy-Z-Fold-5.jpg', 'hp15s.jpg', 'shoess.png', 'clothing.png');

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `myquotes` text NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `myquotes`, `author`) VALUES
(1, 'Well begun is Half done', 'Hariom Singh'),
(5, 'Sunset is the promise of a new Tomorrow', 'Hariom Singh'),
(6, 'Be a good person,but not waste your time show it.', 'Albert Balbournie'),
(7, 'Well begun is Half done', '--');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `image`) VALUES
(31, 'Hariom', 'hariom@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin', 'instagram.png'),
(32, 'wwe', 'wwe@gmail.com', '947af30fc5fd1aaf1e0d8899d5d5baee', 'admin', 'uk.png'),
(33, 'RAJPUT HARIOM', 'ipl@gamil.com', '58985d66ebff374b87a4c1a38529361a', 'user', 'durga-puja-wallpaper-preview.jpg'),
(34, 'ipl', 'ipl@gmail.com', '58985d66ebff374b87a4c1a38529361a', 'user', 'androidstuidologo.jpg'),
(35, 'Hariom', 'om@gmail.com', 'd58da82289939d8c4ec4f40689c2847e', 'user', 'IMG_20230818_232211.jpg'),
(36, 'Hariom Singh', 'hariomsingh3@gmail.com', '7bb060764a818184ebb1cc0d43d382aa', 'admin', 'IMG_20230818_161619.jpg'),
(37, 'Rajput Hariom', 'om1311@gmail.com', '7bb060764a818184ebb1cc0d43d382aa', 'admin', 'IMG_20230818_161619.jpg'),
(38, 'Rajput Hariom', 'om13x1@gmail.com', 'd58da82289939d8c4ec4f40689c2847e', 'admin', 'IMG_20231218_220406_038.jpg'),
(39, 'Hariom', 'ombaba@gmail.com', 'd58da82289939d8c4ec4f40689c2847e', 'admin', 'hassmartlogo.png'),
(40, 'siddharth tiwari', 'sidti@gmail.com', '25f9e794323b453885f5181f1b624d0b', 'user', 'Samsung-Galaxy-Z-Fold-5.jpg'),
(41, 'aarti', 'aartimam@gmail.com', 'b735b0c78e12553e91397a3ff19f8fd1', 'admin', 'hassmartglow.png'),
(42, 'RAJPUT HARIOM', 'hariomsingh211@gmail.com', 'd58da82289939d8c4ec4f40689c2847e', 'admin', 'clothing.png'),
(43, 'sid', 'sid123@gmail.com', 'f1b708bba17f1ce948dc979f4d7092bc', 'user', 'Electronic.png');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
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
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
