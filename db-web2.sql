
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 04:40 PM
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
-- Database: `db_web2`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`username`, `password`, `role_id`, `status`, `email`) VALUES
('admin', '$2y$10$496hMq30QBeRcWUOBWwT9OIiadH9yATeAmh0Kd85B1qCwTpcCA6Iq', 1, 1, 'admin@gmail.com'),
('customer', '$2y$10$EvqvVH183r/F347XOsPXvOvMbJyy.lOtDoN3UioCJWhP0ylF7hYtW', 3, 1, 'customer@gmail.com'),
('minhne04', '$2y$10$kj/i/S9lDmsK9Br44rWMOeDRiPkLYsbxsk.gA/xpzJbArwW2u834G', 3, 1, 'minhne04@gmail.com'),
('staff', '$2y$10$8BrxqGno5iZoDhTzhlLb5u/6QzGhGZgIcxFeqyZiJV2rOcafvr/Va', 2, 1, 'staff@gmail.com'),
('triwjbu1212', '$2y$10$UZxqa5DNDv.7M06aU6EB3.wpKWiS.Ny4lL.FN0x8wEXLhwa1bgr42', 3, 1, 'echosans57@gmail.com'),
('triwjbu13', '$2y$10$qzmvOZuUtohyTfB1ro6vwetlx2MCLWVOlWpkTD7.aWb0/E6v//FEO', 3, 1, 'minhtriqt04@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`, `email`, `status`) VALUES
(1, 'CHOU MU-TZU', 'CHOUMU-TZU@gmail.com', 1),
(2, 'LEE JIN SONG', 'LEEJINSONG@gmail.com', 1),
(3, 'PHAN KHẢI VĂN', 'PHANKHẢIVĂN@gmail.com', 1),
(4, 'STEVE HARVEY', 'STEVEHARVEY@gmail.com', 1),
(5, 'KRISTIN NEFF', 'KRISTINNEFF@gmail.com', 1),
(6, 'CHRISTOPHER GERMER', 'CHRISTOPHERGERMER@gmail.com', 1),
(7, 'CARL GUSTAV JUNG', 'CARLGUSTAVJUNG@gmail.com', 1),
(8, 'ILSE SAND', 'ILSESAND@gmail.com', 1),
(9, 'CHANDRA GHOSH IPPEN', 'CHANDRAGHOSHIPPEN@gmail.com', 1),
(10, ' SIGMUND FREUD', 'SIGMUNDFREUD@gmail.com', 1),
(11, 'ANNE ROONEY', 'ANNEROONEY@gmail.com', 1),
(12, 'DIÊU NGHIÊU', 'DIÊUNGHIÊU@gmail.com', 1),
(13, 'MATT HAIG', 'MATTHAIG@gmail.com', 1),
(14, 'CHRISTONPHE ANDRÉ', 'CHRISTONPHEANDRÉ@gmail.com', 1),
(15, 'DIANE MUSHO HAMILTON', 'DIANEMUSHOHAMILTON@gmail.com', 1),
(16, 'POMNYUN', 'POMNYUN@gmail.com', 1),
(17, 'UYÊN BÙI', 'UYÊNBÙI@gmail.com', 1),
(18, 'VALENTINE VŨ', 'VALENTINEVŨ@gmail.com', 1),
(19, 'TRẦN LANG', 'TRẦNLANG@gmail.com', 1),
(20, 'SYLVIA BROWNE', 'SYLVIABROWNE@gmail.com', 1),
(21, 'GUSTAVE DUMOUTIER', 'GUSTAVEDUMOUTIER@gmail.com', 1),
(22, 'TÂM BÙI', 'TÂMBÙI@gmail.com', 1),
(23, 'NGUYỄN QUANG LẬP', 'NGUYỄNQUANGLẬP@gmail.com', 1),
(24, 'GEORGES COULET', 'GEORGESCOULET@gmail.com', 1),
(25, 'PUAL GIRAN', 'PUALGIRAN@gmail.com', 1),
(26, 'HAYDEN CHERRY', 'HAYDENCHERRY@gmail.com', 1),
(27, 'Lạc Bạch Mai', 'LạcBạchMai@gmail.com', 1),
(28, 'Khương Chi Ngư', 'KhươngChiNgư@gmail.com', 1),
(29, 'Diệp Lạc Vô Tâm', 'DiệpLạcVôTâm@gmail.com', 1),
(30, 'Túy Hậu Ngư Ca', 'TúyHậuNgưCa@gmail.com', 1),
(31, 'Thương Thái Vi', 'ThươngTháiVi@gmail.com', 1),
(32, 'Lâu Vũ Tình', 'LâuVũTình@gmail.com', 1),
(33, 'Nghê Đa Hỉ', 'NghêĐaHỉ@gmail.com', 1),
(34, 'Cố Tây Tước', 'CốTâyTước@gmail.com', 1),
(35, 'Cố Mạn', 'CốMạn@gmail.com', 1),
(36, 'KRISTI', '@gmail.com', 1),
(37, 'NguyenVanA', 'abc@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `author_details`
--

CREATE TABLE `author_details` (
  `product_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author_details`
--

INSERT INTO `author_details` (`product_id`, `author_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(5, 6),
(6, 7),
(7, 8),
(8, 9),
(10, 11),
(11, 12),
(12, 13),
(13, 14),
(14, 15),
(15, 16),
(16, 17),
(16, 18),
(17, 19),
(18, 20),
(19, 21),
(20, 22),
(21, 23),
(22, 24),
(27, 25),
(39, 27),
(40, 28),
(41, 29),
(42, 30),
(43, 31),
(44, 32),
(45, 33),
(46, 34),
(47, 35),
(48, 29),
(49, 29),
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(5, 6),
(6, 7),
(7, 8),
(8, 9),
(10, 11),
(11, 12),
(12, 13),
(13, 14),
(14, 15),
(15, 16),
(16, 17),
(16, 18),
(17, 19),
(18, 20),
(19, 21),
(20, 22),
(21, 23),
(22, 24),
(27, 25),
(39, 27),
(40, 28),
(41, 29),
(42, 30),
(43, 31),
(44, 32),
(45, 33),
(46, 34),
(47, 35),
(48, 29),
(49, 29);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `delete_date` date DEFAULT NULL,
  `create_date` date NOT NULL,
  `update_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `delete_date`, `create_date`, `update_date`) VALUES
(1, ' Tâm lý học ', 1, NULL, '2025-04-27', '2025-05-09'),
(2, ' Tâm linh - tôn giáo ', 1, NULL, '2025-04-27', '2025-05-09'),
(3, ' Lịch sử Việt Nam ', 1, NULL, '2025-04-27', '2025-05-09'),
(4, '  Ngôn tình ', 1, NULL, '2025-04-27', '2025-05-09');

-- --------------------------------------------------------

--
-- Table structure for table `category_details`
--

CREATE TABLE `category_details` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_details`
--

INSERT INTO `category_details` (`product_id`, `category_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 3),
(22, 3),
(23, 3),
(27, 3),
(39, 4),
(40, 4),
(41, 4),
(42, 4),
(43, 4),
(44, 4),
(45, 4),
(46, 4),
(47, 4),
(48, 4),
(49, 4),
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 3),
(22, 3),
(23, 3),
(27, 3),
(39, 4),
(40, 4),
(41, 4),
(42, 4),
(43, 4),
(44, 4),
(45, 4),
(46, 4),
(47, 4),
(48, 4),
(49, 4);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_infoes`
--

CREATE TABLE `delivery_infoes` (
  `user_info_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `city` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `ward` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_infoes`
--

INSERT INTO `delivery_infoes` (`user_info_id`, `user_id`, `fullname`, `phone_number`, `address`, `city`, `district`, `ward`) VALUES
(1, 'customer', 'Lữ Quang Minhaaa', '0931814480', '280 An Dương Vương', 'TP. Hồ Chí Minh', 'Quận 5', 'Phường 03'),
(2, 'customer', 'Tèo Ú aaa', '0931814480', '273 An Dương Vương', 'TP. Hồ Chí Minh', 'Quận 5', 'Phường 03'),
(3, 'customer', 'Dương Chươnggg', '0911311312', '280 An Dương Vương', 'TP. Hồ Chí Minh', 'Quận 5', 'Phường 03'),
(4, 'minhne04', 'Lữ Quang Minh', '0931814480', '528 Hưng Phú', 'TP. Hồ Chí Minh', 'Quận 8', 'Phường 09'),
(5, 'admin', 'Lữ Quang Minh', '0931814480', '528 Hưng Phú', 'TP. Hồ Chí Minh', 'Quận 8', 'Phường 09'),
(6, 'staff', 'Nguyễn Minh Trí', '0983479999', '200 Phạm Văn Đồng', 'TP. Hồ Chí Minh', 'Quận Gò Vấp', 'Phường 01'),
(7, 'customer', 'Nguyễn Thế Ngọc', '0377927824', '20 Lê Minh Xuân', 'TP. Hồ Chí Minh', 'Quận Bình Tân', 'Phường Bình Hưng Hòa'),
(8, 'minhne04', 'Lữ Quang Minh', '0931814480', '502 Hưng Phú', 'TP. Hồ Chí Minh', 'Quận 8', 'Phường 09'),
(10, 'triwjbu13', 'Nguyễn Minh Trí', '0394080644', '115 Mạc Đĩnh Chi', 'Vĩnh Phúc', 'Vĩnh Yên', 'Phường Tích Sơn'),
(11, 'triwjbu1212', 'Nguyễn Minh Trí', '0394080644', '115 Mạc Đĩnh Chi', 'Bắc Giang', 'Bắc Giang', 'Phường Thọ Xương');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discount_code` varchar(10) NOT NULL,
  `discount_value` int(11) NOT NULL,
  `type` varchar(2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `delete_date` date DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`discount_code`, `discount_value`, `type`, `start_date`, `end_date`, `status`, `delete_date`, `create_date`, `update_date`) VALUES
('SALE50K', 50000, 'AR', '2025-04-01', '2030-04-01', 1, NULL, '2025-05-09', '2025-05-09'),
('SALENUAGIA', 50, 'PR', '2025-04-01', '2031-04-01', 1, NULL, '2025-05-09', '2025-05-09');

-- --------------------------------------------------------

--
-- Table structure for table `functions`
--

CREATE TABLE `functions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `delete_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `functions`
--

INSERT INTO `functions` (`id`, `name`, `status`, `delete_date`, `update_date`) VALUES
(1, 'Thống kê và báo cáo', 1, NULL, NULL),
(2, 'Quản lý sản phẩm', 1, NULL, NULL),
(3, 'Quản lý đơn hàng', 1, NULL, NULL),
(4, 'Quản lý tài khoản', 1, NULL, NULL),
(5, 'Quản lý nhà xuất bản', 1, NULL, NULL),
(6, 'Quản lý tác giả', 1, NULL, NULL),
(7, 'Quản lý thể loại', 1, NULL, NULL),
(8, 'Quản lý nhà cung cấp', 1, NULL, NULL),
(9, 'Quản lý nhập hàng', 1, NULL, NULL),
(10, 'Quản lý phân quyền', 1, NULL, NULL),
(11, 'Quản lý khuyển mãi', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `function_details`
--

CREATE TABLE `function_details` (
  `function_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `action` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `function_details`
--

INSERT INTO `function_details` (`function_id`, `role_id`, `action`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 1, 1),
(7, 1, 1),
(8, 1, 1),
(9, 1, 1),
(10, 1, 1),
(1, 2, 0),
(2, 2, 1),
(3, 2, 1),
(4, 2, 0),
(5, 2, 1),
(6, 2, 1),
(7, 2, 1),
(8, 2, 1),
(9, 2, 1),
(10, 2, 0),
(11, 1, 1),
(11, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `goodsreceipts`
--

CREATE TABLE `goodsreceipts` (
  `id` int(11) NOT NULL,
  `staff_id` varchar(50) NOT NULL,
  `total_price` double NOT NULL,
  `date_create` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goodsreceipt_details`
--

CREATE TABLE `goodsreceipt_details` (
  `product_id` int(11) NOT NULL,
  `goodsreceipt_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `input_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `staff_id` varchar(50) DEFAULT NULL,
  `delivery_info_id` int(11) NOT NULL,
  `date_create` date NOT NULL,
  `total_price` double NOT NULL,
  `status_id` int(11) NOT NULL,
  `discount_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_statuses`
--

CREATE TABLE `order_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_statuses`
--

INSERT INTO `order_statuses` (`id`, `name`) VALUES
(1, 'Chờ duyệt'),
(2, 'Đã duyệt'),
(3, 'Đã huỷ'),
(4, 'Đang giao'),
(5, 'Đã giao');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `create_date` date NOT NULL,
  `update_date` date NOT NULL,
  `price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `publisher_id`, `image_path`, `create_date`, `update_date`, `price`, `quantity`, `supplier_id`, `status`) VALUES
(1, 'THAO TÚNG CẢM XÚC: LÀM SAO THOÁT KHỎI CHIẾC BẪY VÔ', 3, 'assets/images/product/image_1.jpg', '2025-04-01', '2025-04-01', 109000, 0, 1, 1),
(2, 'TỰ DO KHÔNG YÊU ĐƯƠNG', 4, 'assets/images/product/image_2.jpg', '2025-04-01', '2025-04-01', 169000, 0, 1, 1),
(3, 'ĐỪNG THÁCH THỨC NHÂN TÍNH', 5, 'assets/images/product/image_3.jpg', '2025-04-01', '2025-04-01', 196000, 0, 1, 1),
(4, 'NÓI LUÔN CHO NÓ VUÔNG', 6, 'assets/images/product/image_4.jpg', '2025-04-01', '2025-04-01', 108000, 0, 1, 1),
(5, 'TRẮC ẨN VỚI CHÍNH MÌNH', 7, 'assets/images/product/image_5.jpg', '2025-04-01', '2025-04-01', 158000, 0, 1, 1),
(6, 'CON NGƯỜI VÀ BIỂU TƯỢNG', 6, 'assets/images/product/image_6.jpg', '2025-04-01', '2025-04-01', 450000, 0, 1, 1),
(7, 'DÁM SỐNG HƯỚNG NỘI VÀ CỰC KỲ NHẠY CẢM', 6, 'assets/images/product/image_7.jpg', '2025-04-01', '2025-04-01', 99000, 0, 1, 1),
(8, 'TỚ ĐÃ TỪNG SỢ HÃI - LỜI KHUYÊN TỪ CHUYÊN GIA TÂM L', 5, 'assets/images/product/image_8.jpg', '2025-04-01', '2025-04-01', 75000, 0, 1, 1),
(9, 'NGHIÊN CỨU PHÂN TÂM HỌC', 6, 'assets/images/product/image_9.jpg', '2025-04-01', '2025-04-01', 135000, 0, 1, 1),
(10, 'TƯ DUY NHƯ NHÀ TÂM LÝ HỌC', 6, 'assets/images/product/image_10.jpg', '2025-04-01', '2025-04-01', 139000, 0, 1, 1),
(11, 'CON QUÁI VẬT TRONG TÂM TRÍ – NHỮNG CA BỆNH TÂM LÝ ', 3, 'assets/images/product/image_11.jpg', '2025-04-01', '2025-04-01', 148000, 0, 1, 1),
(12, 'LÝ DO ĐỂ SỐNG TIẾP', 8, 'assets/images/product/image_12.jpg', '2025-04-01', '2025-04-01', 115000, 0, 1, 1),
(13, 'THIỀN ĐỊNH MỖI NGÀY', 5, 'assets/images/product/image_13.jpg', '2025-04-01', '2025-04-01', 118000, 0, 1, 1),
(14, 'MỌI VIỆC ĐỀU CÓ THỂ GIẢI QUYẾT - THÁO GỠ KHÓ KHĂN ', 5, 'assets/images/product/image_14.jpg', '2025-04-01', '2025-04-01', 138000, 0, 1, 1),
(15, 'LÀM SAO HỌC HẾT ĐƯỢC NHÂN SINH', 5, 'assets/images/product/image_15.jpg', '2025-04-01', '2025-04-01', 169000, 0, 1, 1),
(16, 'CHIÊM TINH PHÙ THỦY - ÚM BA LA ... SOI RA TÍNH CÁC', 6, 'assets/images/product/image_16.jpg', '2025-04-01', '2025-04-01', 216000, 0, 1, 1),
(17, 'BÙA CHÚ - GIẢI THÍCH CÁC TRÒ MẸO VÀ PHÉP BÍ THUẬT ', 6, 'assets/images/product/image_17.jpg', '2025-04-01', '2025-04-01', 59000, 0, 1, 1),
(18, 'NGÀY TẬN THẾ - LỜI TIÊN TRI VỀ TƯƠNG LAI VÀ THẾ GI', 9, 'assets/images/product/image_18.jpg', '2025-04-01', '2025-04-01', 189000, 0, 1, 1),
(19, 'TANG LỄ CỦA NGƯỜI AN NAM (BÌA CỨNG)', 6, 'assets/images/product/image_19.jpg', '2025-04-01', '2025-04-01', 289000, 0, 1, 1),
(20, 'CÁ HỒI - HÀNH TRÌNH TỈNH THỨC', 4, 'assets/images/product/image_20.jpg', '2025-04-01', '2025-04-01', 99000, 0, 1, 1),
(21, 'Ba Đồn mạn thuật', 2, 'assets/images/product/image_21.jpg', '2025-04-01', '2025-04-01', 545000, 0, 2, 1),
(22, 'Bộ Sách Hội Kín', 2, 'assets/images/product/image_22.jpg', '2025-04-01', '2025-04-01', 149000, 0, 2, 1),
(23, 'Chìm nổi ở Sài Gòn – Những cảnh đời bần cùng ở một', 2, 'assets/images/product/image_23.jpg', '2025-04-01', '2025-04-01', 259000, 0, 2, 1),
(27, 'Tâm lý Dân Tộc An Nam', 2, 'assets/images/product/image_27.jpg', '2025-04-01', '2025-04-01', 149000, 0, 2, 1),
(39, 'Năm Tháng Tĩnh Lặng, Kiếp Này Bình Yên', 10, 'assets/images/product/image_39.jpg', '2025-04-01', '2025-04-01', 138000, 0, 3, 1),
(40, 'Eo Thon Nhỏ', 11, 'assets/images/product/image_40.jpg', '2025-04-01', '2025-04-01', 239000, 0, 3, 1),
(41, 'Mãi Mãi Là Bao Xa', 11, 'assets/images/product/image_41.jpg', '2025-04-01', '2025-04-01', 135000, 0, 3, 1),
(42, 'Chỉ Muốn Thương Anh, Chiều Anh, Nuôi Anh', 8, 'assets/images/product/image_42.jpg', '2025-04-01', '2025-04-01', 259000, 0, 3, 1),
(43, 'Bến Xe', 8, 'assets/images/product/image_43.jpg', '2025-04-01', '2025-04-01', 76000, 0, 3, 1),
(44, 'Thất Tịch Không Mưa', 4, 'assets/images/product/image_44.jpg', '2025-04-01', '2025-04-01', 86000, 0, 3, 1),
(45, 'Rung Động Chỉ Vì Em', 3, 'assets/images/product/image_45.jpg', '2025-04-01', '2025-04-01', 189000, 0, 3, 1),
(46, 'All In Love - Ngập Tràn Yêu Thương', 4, 'assets/images/product/image_46.jpg', '2025-04-01', '2025-04-01', 119000, 0, 3, 1),
(47, 'Yêu Em Từ Cái Nhìn Đầu Tiên', 8, 'assets/images/product/image_47.jpg', '2025-04-01', '2025-04-01', 179000, 0, 3, 1),
(48, 'Em Vốn Thích Cô Độc, Cho Đến Khi Có Anh', 8, 'assets/images/product/image_48.jpg', '2025-04-01', '2025-04-01', 108000, 0, 3, 1),
(49, 'Chờ Em Lớn Nhé Được Không?', 8, 'assets/images/product/image_49.jpg', '2025-04-01', '2025-04-01', 99000, 0, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE `publishers` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`id`, `name`, `email`, `status`) VALUES
(2, 'Nhà Xuất Bản Hội Nhà Văn', 'nxbhoinhavan@gmail.com', 1),
(3, 'Nhà Xuất Bản Hà Nội', 'nxbhanoi@gmail.com', 1),
(4, 'Nhà Xuât Bản Phụ Nữ', 'nxbphunu@gmail.com', 1),
(5, 'Nhà Xuất Bản Dân Trí', 'nxbdantri@gmail.com', 1),
(6, 'Nhà Xuất Bản Thế Giới', 'nxbthegioi@gmail.com', 1),
(7, 'Nhà Xuất Bản Tổng Hợp Thành phố Hồ Chí Minh', 'nxbtonghoptphcm@gmail.com', 1),
(8, 'Nhà Xuất Bản Văn Học', 'nxbvanhoc@gmail.com', 1),
(9, 'Nhà Xuất Bản Thông Tấn', 'nxbthongtan@gmail.com', 1),
(10, 'Nhà Xuất Bản Lao Động', 'nxblaodong@gmail.com', 1),
(11, 'Nhà Xuất Bản Thanh Niên', 'nxbthanhnien@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'staff'),
(3, 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number_phone` varchar(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `delete_date` date DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `email`, `number_phone`, `status`, `delete_date`, `create_date`, `update_date`) VALUES
(1, ' Nhã Nam ', ' info@nhanam.com ', ' 0324253224', 1, NULL, '2025-05-09', '2025-05-09'),
(2, ' Omega Plus ', ' info@omegaplus.vn ', ' 0932329922', 1, NULL, '2025-05-09', '2025-05-09'),
(3, 'Minh Quang Books', 'info@minhquangbooks.com', '0975225265', 1, NULL, '2025-05-09', '2025-05-09');

-- --------------------------------------------------------

--
-- Table structure for table `verify_code`
--

CREATE TABLE `verify_code` (
  `email` varchar(50) NOT NULL,
  `code` varchar(10) NOT NULL,
  `time_send` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verify_code`
--

INSERT INTO `verify_code` (`email`, `code`, `time_send`) VALUES
('admin@gmail.com', '890678', '2025-05-03 09:21:29'),
('customer@gmail.com', '325786', '2025-05-15 04:22:03'),
('echosans57@gmail.com', '631292', '2025-05-16 09:37:03'),
('minhne04@gmail.com', '325786', '2025-05-15 04:22:03'),
('minhtriqt04@gmail.com', '482407', '2025-05-16 09:10:10'),
('staff@gmail.com', '325786', '2025-05-15 04:22:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`username`),
  ADD KEY `fk_role_id` (`role_id`),
  ADD KEY `fk_email` (`email`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `author_details`
--
ALTER TABLE `author_details`
  ADD KEY `fk_product_id_author_details` (`product_id`),
  ADD KEY `fk_author_id_author_details` (`author_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_details`
--
ALTER TABLE `category_details`
  ADD KEY `fk_product_id_category_details` (`product_id`),
  ADD KEY `fk_category_id_category_details` (`category_id`);

--
-- Indexes for table `delivery_infoes`
--
ALTER TABLE `delivery_infoes`
  ADD PRIMARY KEY (`user_info_id`),
  ADD KEY `fk_user_id_user_info` (`user_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_code`);

--
-- Indexes for table `functions`
--
ALTER TABLE `functions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `function_details`
--
ALTER TABLE `function_details`
  ADD KEY `fk_function_id` (`function_id`),
  ADD KEY `fk_role_id_function` (`role_id`);

--
-- Indexes for table `goodsreceipts`
--
ALTER TABLE `goodsreceipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_staff_id_goodsreceipts` (`staff_id`);

--
-- Indexes for table `goodsreceipt_details`
--
ALTER TABLE `goodsreceipt_details`
  ADD KEY `fk_product_id_goodsreceipt_details` (`product_id`),
  ADD KEY `fk_goodsreceipt_id_goodsreceipt_details` (`goodsreceipt_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_status_id_order` (`status_id`),
  ADD KEY `fk_staff_id_order` (`staff_id`),
  ADD KEY `fk_discount_code_order` (`discount_code`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD KEY `fk_product_id_order_details` (`product_id`),
  ADD KEY `fk_order_id_order_details` (`order_id`);

--
-- Indexes for table `order_statuses`
--
ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_publisher_id_product` (`publisher_id`),
  ADD KEY `fk_supplier_id_product` (`supplier_id`);

--
-- Indexes for table `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verify_code`
--
ALTER TABLE `verify_code`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `delivery_infoes`
--
ALTER TABLE `delivery_infoes`
  MODIFY `user_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `functions`
--
ALTER TABLE `functions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `goodsreceipts`
--
ALTER TABLE `goodsreceipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `publishers`
--
ALTER TABLE `publishers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `fk_email` FOREIGN KEY (`email`) REFERENCES `verify_code` (`email`),
  ADD CONSTRAINT `fk_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `author_details`
--
ALTER TABLE `author_details`
  ADD CONSTRAINT `fk_author_id_author_details` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `fk_product_id_author_details` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `category_details`
--
ALTER TABLE `category_details`
  ADD CONSTRAINT `fk_category_id_category_details` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_product_id_category_details` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `delivery_infoes`
--
ALTER TABLE `delivery_infoes`
  ADD CONSTRAINT `fk_user_info_delivery_infoes` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`username`);

--
-- Constraints for table `function_details`
--
ALTER TABLE `function_details`
  ADD CONSTRAINT `fk_function_id` FOREIGN KEY (`function_id`) REFERENCES `functions` (`id`),
  ADD CONSTRAINT `fk_role_id_function` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `goodsreceipts`
--
ALTER TABLE `goodsreceipts`
  ADD CONSTRAINT `fk_staff_id_goodsreceipts` FOREIGN KEY (`staff_id`) REFERENCES `accounts` (`username`);

--
-- Constraints for table `goodsreceipt_details`
--
ALTER TABLE `goodsreceipt_details`
  ADD CONSTRAINT `fk_goodsreceipt_id_goodsreceipt_details` FOREIGN KEY (`goodsreceipt_id`) REFERENCES `goodsreceipts` (`id`),
  ADD CONSTRAINT `fk_product_id_goodsreceipt_details` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_discount_code_order` FOREIGN KEY (`discount_code`) REFERENCES `discounts` (`discount_code`),
  ADD CONSTRAINT `fk_staff_id_order` FOREIGN KEY (`staff_id`) REFERENCES `accounts` (`username`),
  ADD CONSTRAINT `fk_status_id_order` FOREIGN KEY (`status_id`) REFERENCES `order_statuses` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_id_order_details` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `fk_product_id_order_details` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_publisher_id_product` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`),
  ADD CONSTRAINT `fk_supplier_id_product` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
