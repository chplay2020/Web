-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2022 at 03:43 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Database: `shop_db`
--

-- --------------------------------------------------------
--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `quantity` int(100) NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `image` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 51;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 8;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 12;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 24;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 31;
--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 50;
--
-- Dumping data for table `users`
--

INSERT INTO users (id, name, email, password, user_type, image)
VALUES (
    56,
    'Admin',
    'admin3@gmail.com',
    'e10adc3949ba59abbe56e057f20f883e',
    'admin',
    'default.jpg'
  ),
  (
    57,
    'Admin',
    'admin@gmail.com',
    'e10adc3949ba59abbe56e057f20f883e',
    'admin',
    'default.jpg'
  ),
  (
    66,
    'haha',
    'haha@gmail.com',
    'e10adc3949ba59abbe56e057f20f883e',
    'user',
    'default.jpg'
  ),
  (
    77,
    'haha2',
    'haha2@gmail.com',
    'e10adc3949ba59abbe56e057f20f883e',
    'user',
    'default.jpg'
  );
COMMIT;
--
-- Dumping data for table `products`
--

INSERT INTO `products` (
    `id`,
    `name`,
    `category`,
    `details`,
    `price`,
    `image`,
    `quantity`
  )
VALUES (
    1,
    'Fresh Apple',
    'fruits',
    'Sweet and crispy red apples from local farms',
    3,
    'apple.png',
    100
  ),
  (
    2,
    'Banana Bundle',
    'fruits',
    'Fresh yellow bananas rich in potassium',
    2,
    'banana.png',
    120
  ),
  (
    3,
    'Green Broccoli',
    'vegitables',
    'Fresh organic broccoli packed with nutrients',
    4,
    'broccoli.png',
    80
  ),
  (
    4,
    'Fresh Chicken',
    'meat',
    'Premium quality fresh chicken meat',
    8,
    'chicken.png',
    50
  ),
  (
    5,
    'Salmon Fish',
    'fish',
    'Fresh salmon fish rich in omega-3',
    12,
    'salmon fish.png',
    60
  ),
  (
    6,
    'Red Tomato',
    'vegitables',
    'Juicy red tomatoes perfect for cooking',
    3,
    'tomato.png',
    90
  ),
  (
    7,
    'Orange Carrot',
    'vegitables',
    'Fresh carrots loaded with vitamin A',
    2,
    'carrot.png',
    70
  ),
  (
    8,
    'Green Grapes',
    'fruits',
    'Sweet seedless green grapes',
    5,
    'green grapes.png',
    110
  ),
  (
    9,
    'Beef Steak',
    'meat',
    'Premium quality beef steak',
    15,
    'beaf steak.png',
    40
  ),
  (
    10,
    'Fresh Strawberry',
    'fruits',
    'Sweet and juicy strawberries',
    6,
    'strawberry.png',
    95
  );
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;