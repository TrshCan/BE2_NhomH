-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2025 at 07:34 AM
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
-- Database: `onlineshop_h`
--
CREATE DATABASE IF NOT EXISTS `onlineshop_h` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `onlineshop_h`;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `images_brand` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`brand_id`, `brand_name`, `images_brand`) VALUES
(1, 'Apple', 'OIP.jpg'),
(2, 'Samsung', 'samsung.jpg'),
(3, 'Dell', 'dell.jpg'),
(4, 'Asus', 'Asus.jpg'),
(5, 'AKG', 'akg.jpg'),
(6, 'JBL', 'JBL.jpg'),
(7, 'Boat', 'Boat.jpg'),
(8, 'Canon', 'Canon.jpg'),
(9, 'Herman Miller', 'Herman Miller.png'),
(10, 'DJI', 'DJI2.jpg'),
(11, 'Nike', 'Nike.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`) VALUES
(1, 'Điện thoại', 'Các dòng điện thoại mới nhất'),
(2, 'Laptop', 'Laptop phục vụ công việc và học tập'),
(3, 'Phụ kiện', 'Phụ kiện công nghệ');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` varchar(2000) NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discount_id` int(11) NOT NULL,
  `discount_code` varchar(50) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `shipping_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `sales_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock_quantity`, `category_id`, `brand_id`, `image_url`, `sales_count`) VALUES
(1, 'iPhone 15', 'Điện thoại Apple mới nhất', 25000000.00, 10, 1, 1, 'iphone15.jpg', 12),
(2, 'Galaxy S23', 'Điện thoại Samsung cao cấp', 20000000.00, 15, 1, 2, 's23.jpg', 453),
(3, 'Dell XPS 13', 'Laptop Dell cao cấp', 30000000.00, 8, 2, 3, 'xps13.jpg', 90),
(4, 'Tai nghe Airpods', 'Tai nghe không dây Apple', 5000000.00, 20, 3, 1, 'airpods.jpg', 100),
(25, 'AK-600 Gaming Keyboard', 'RGB mechanical keyboard with anti-ghosting', 89.99, 50, 3, 5, 'AK_600.jpg', 5),
(26, 'Bluetooth Headset Blue', 'Wireless headset with noise cancellation', 49.99, 100, 3, 6, 'bluetoothheadset_1.png', 43),
(27, 'Bluetooth Headset Red', 'Wireless headset with long battery life', 59.99, 80, 3, 7, 'bluetoothheadset_2.png', 11),
(28, 'Professional Camera', 'High-resolution DSLR camera with 24MP', 799.99, 20, 3, 8, 'camera.png', 999),
(29, 'Camera with Zoom Lens', 'DSLR camera with 18-55mm zoom lens', 899.99, 15, 3, 8, 'camera_1.png', 789),
(30, 'Ergonomic Office Chair', 'Adjustable office chair with lumbar support', 129.99, 40, 3, 9, 'chair.png', 4322),
(31, 'Blue Office Chair', 'Comfortable chair with mesh back', 149.99, 30, 3, 9, 'chair_1.png', 312),
(32, 'Aerial Drone', '4K camera drone with 30-minute flight time', 499.99, 25, 3, 10, 'drone.png', 876),
(33, 'White Drone', 'Compact drone with 1080p camera', 399.99, 30, 3, 10, 'drone_1.png', 765),
(34, 'White Jacket', 'Stylish white jacket for casual wear', 79.99, 60, 3, 4, 'jacket.png', 756),
(35, 'Brown Leather Jacket', 'Premium leather jacket for men', 199.99, 20, 3, 4, 'jacket_1.png', 6575),
(36, 'RGB Gaming Keyboard', 'Mechanical keyboard with customizable lighting', 99.99, 45, 3, 5, 'keyboard.png', 1),
(37, 'RGB Keyboard with Mouse', 'Gaming keyboard and mouse combo', 119.99, 35, 3, 5, 'keyboard_1.png', 863),
(38, 'Gaming Laptop', 'High-performance laptop with RTX graphics', 1499.99, 10, 2, 3, 'laptop.png', 23),
(39, 'Sleek Blue Laptop', 'Lightweight laptop with 16GB RAM', 999.99, 15, 2, 4, 'laptop_1.png', 232),
(40, 'Smartphone Blue', '', 699.99, 50, 1, 1, 'mobile.png', 0),
(41, 'Smartphone Purple', 'Flagship smartphone with 256GB storage', 799.99, 40, 1, 2, 'mobile_1.png', 45),
(42, 'Running Shoes Blue', 'Comfortable running shoes with cushioning', 89.99, 70, 3, 11, 'shoes.png', 464),
(43, 'Yellow Sneakers', 'Stylish sneakers for everyday use', 99.99, 60, 3, 11, 'shoes_1.png', 7657),
(44, 'Phone Stand', 'Adjustable phone stand for desk use', 19.99, 200, 3, 4, 'stand.png', 42);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `title`, `description`, `image_url`) VALUES
(1, 'Tech Accessories 2025', 'Up to 25% Off on Keyboards & Headsets', 'oneplus-keyboard.jpg'),
(2, 'New Arrivals 2025', 'Explore Top Headsets', 'labubu.png'),
(3, 'Gaming Laptops', 'Save Big This Season', 'AK_600.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `report_type` varchar(100) NOT NULL,
  `report_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_revenue` decimal(15,2) NOT NULL,
  `top_selling_product` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `review_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'Nguyễn Văn A', 'a@gmail.com', '123456', '0901234567', 'Hà Nội', 'admin', '2025-04-09 14:49:44'),
(2, 'Trần Thị B', 'b@gmail.com', '123456', '0902345678', 'Hồ Chí Minh', 'user', '2025-04-09 14:49:44'),
(3, 'Lê Văn C', 'c@gmail.com', '123456', '0903456789', 'Đà Nẵng', 'user', '2025-04-09 14:49:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `top_selling_product` (`top_selling_product`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`top_selling_product`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
