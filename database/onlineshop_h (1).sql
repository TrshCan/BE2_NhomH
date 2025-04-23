-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 23, 2025 lúc 06:51 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `onlineshop_h`
--
CREATE DATABASE IF NOT EXISTS `onlineshop_h` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `onlineshop_h`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `images_brand` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `brand`
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
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`) VALUES
(1, 'Điện thoại', 'Các dòng điện thoại mới nhất'),
(2, 'Laptop', 'Laptop phục vụ công việc và học tập'),
(3, 'Phụ kiện', 'Phụ kiện công nghệ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chats`
--

CREATE TABLE `chats` (
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` varchar(2000) NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chats`
--

INSERT INTO `chats` (`chat_id`, `sender_id`, `receiver_id`, `message`, `sent_at`) VALUES
(1, 4, 5, 'BJGUU', '2025-04-17 14:25:29'),
(2, 4, 5, 'njullhlhuhhilhlihi', '2025-04-17 14:26:35'),
(3, 5, 4, 'ngu', '2025-04-17 14:29:43'),
(4, 4, 5, 'gi', '2025-04-17 14:29:51'),
(8, 4, 5, 'dsf', '2025-04-17 14:31:10'),
(9, 5, 4, 'd', '2025-04-17 14:33:53'),
(10, 4, 4, 'wdw', '2025-04-17 14:39:24'),
(11, 4, 4, 'fnafiwa', '2025-04-17 14:44:43'),
(13, 5, 4, 'adwfawf', '2025-04-17 14:45:03'),
(14, 4, 4, 'gege', '2025-04-17 14:47:47'),
(15, 4, 4, 'fafw', '2025-04-17 14:47:49'),
(16, 4, 4, 'wfaw', '2025-04-17 14:49:19'),
(17, 4, 4, 'wafawf', '2025-04-17 14:49:20'),
(18, 4, 5, 'faafw', '2025-04-17 14:51:54'),
(19, 4, 5, 'afawfa', '2025-04-17 14:51:55'),
(20, 5, 4, 'eegeg', '2025-04-17 14:52:01'),
(21, 6, 5, 'smfimafaw', '2025-04-17 14:54:00'),
(22, 5, 6, 'eageage', '2025-04-17 14:54:10'),
(23, 5, 6, 'vvsv', '2025-04-17 14:54:20'),
(24, 6, 5, 'vvdvd', '2025-04-17 14:54:22'),
(25, 7, 5, 'chao', '2025-04-19 14:21:49'),
(26, 7, 5, 'chao con c', '2025-04-23 20:06:01'),
(31, 7, 5, 'chao', '2025-04-23 20:12:44'),
(33, 7, 5, 'chao', '2025-04-23 21:05:13'),
(34, 5, 7, 'chao cc', '2025-04-23 21:06:33'),
(35, 5, 6, 'dsfsdfs', '2025-04-23 21:18:52'),
(36, 5, 7, '11', '2025-04-23 21:19:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `discounts`
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
-- Cấu trúc bảng cho bảng `orders`
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
-- Cấu trúc bảng cho bảng `order_details`
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
-- Cấu trúc bảng cho bảng `products`
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
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock_quantity`, `category_id`, `brand_id`, `image_url`, `sales_count`) VALUES
(1, 'iPhone 15', 'iPhone 15 mang đến một bước tiến vượt bậc với thiết kế viền mỏng hơn, khung viền titan nhẹ và bền. Trang bị chip A16 Bionic siêu nhanh, tiết kiệm điện và hỗ trợ tốt các tác vụ nặng như chơi game, chỉnh sửa ảnh/video.\r\n\r\nCamera chính 48MP giúp bạn chụp ảnh sắc nét trong mọi điều kiện ánh sáng, cùng chế độ chân dung thông minh tự động phát hiện chủ thể. Ngoài ra, iPhone 15 còn có cổng USB-C, hỗ trợ sạc nhanh và truyền dữ liệu linh hoạt hơn.\r\n\r\nVới iOS 17, người dùng còn được trải nghiệm nhiều tính năng thông minh và bảo mật hàng đầu.', 25000000.00, 10, 1, 1, 'iphone15.jpg', 12),
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
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `promotions`
--

INSERT INTO `promotions` (`id`, `title`, `description`, `image_url`) VALUES
(1, 'Tech Accessories 2025', 'Up to 25% Off on Keyboards & Headsets', 'oneplus-keyboard.jpg'),
(2, 'New Arrivals 2025', 'Explore Top Headsets', 'labubu.png'),
(3, 'Gaming Laptops', 'Save Big This Season', 'AK_600.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reports`
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
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `review_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `product_id`, `rating`, `comment`, `image`, `review_date`) VALUES
(13, 7, 25, 5, 'sdfsfsdfsfsdf', 'anhtv.jpg', '2025-04-23 22:56:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
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
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'Nguyễn Văn A', 'a@gmail.com', '123456', '0901234567', 'Hà Nội', 'admin', '2025-04-09 14:49:44'),
(2, 'Trần Thị B', 'b@gmail.com', '123456', '0902345678', 'Hồ Chí Minh', 'user', '2025-04-09 14:49:44'),
(3, 'Lê Văn C', 'c@gmail.com', '123456', '0903456789', 'Đà Nẵng', 'user', '2025-04-09 14:49:44'),
(4, 'thach', 'thachdao582@gmail.com', '$2y$10$PLRGjqpGtTDlJxLpA3gJ9uc0K/yy.UHaMKnvHOsR6wLCWBjNHxX4e', '0866349819', 'dasdfghfghf', 'user', '2025-04-17 13:29:30'),
(5, 'wfafawfwa', 'a1@gmail.com', '$2y$10$pk.vW37B24zE83wNOZQ/g.wZgZFyTCj6dM2EfMnsFB0iYixyjDYnm', '12345678901', 'ehgluahlahl', 'admin', '2025-04-17 14:28:47'),
(6, 'NGuyenBeo', 'nguyenbeo123@gmail.com', '$2y$10$GkwqZzDdzOwn6AhSjmkrYO0sLz35q.KAFOUolbRwnJlC7V11qu/Jy', '12345678911', 'vavavavwavwvav', 'user', '2025-04-17 14:53:45'),
(7, 'mai thang', 'h@gmail.com', '$2y$10$TyfQw1QG9rG5wSJ.k.QvguaamaguoKbqplWEFP9fLQ4.e6HdZzg0q', '0817007558', 'sdfsdf', 'user', '2025-04-19 14:19:12'),
(8, 'cai quan', 'a2@gmail.com', '$2y$10$hdf5a84nE8nhjt8jdVoa.eUIK5FHsjIFwf1xFFiMDOU6sIzQNxhEK', '23423', 'dsfsdfsdfsdf', 'admin', '2025-04-23 21:16:10');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Chỉ mục cho bảng `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `top_selling_product` (`top_selling_product`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`);

--
-- Các ràng buộc cho bảng `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`top_selling_product`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
