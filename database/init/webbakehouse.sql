-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 26, 2026 lúc 12:09 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `webbakehouse` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `webbakehouse`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `webbakehouse`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(190) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `title`, `image_path`, `link_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Nguyên liệu làm bánh chất lượng', 'uploads/banners/c8949d38150ff3c21e0cdf17bc80e2dd.jpg', '/products', 1, '2026-11-22 18:00:40', '2026-11-22 18:40:54'),
(2, 'Ưu đãi dụng cụ làm bánh', 'uploads/banners/7a3e7d72240c7d247fd24f7eb223d8a8.jpg', '/products?category=dung-cu-lam-banh', 1, '2026-11-22 18:00:40', '2026-11-22 18:40:47'),
(3, 'Bơ & kem tươi nhập khẩu', 'uploads/banners/6c975a256db470d6ee5a692a6a1c0da7.jpg', '/products?category=chat-beo', 1, '2026-11-22 18:00:40', '2026-11-22 18:40:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Meizan', 'meizan', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(2, 'Anchor', 'anchor', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(3, 'Tường An', 'tuong-an', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(4, 'Biên Hòa', 'bien-hoa', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(5, 'Wilton', 'wilton', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(6, 'Cái Lân', 'cai-lan', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(7, 'Nielsen-Massey', 'nielsen-massey', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(8, 'Vinamilk', 'vinamilk', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(9, 'Dr. Oetker', 'dr-oetker', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(10, 'Callebaut', 'callebaut', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(11, 'Mauri', 'mauri', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(12, 'Philadelphia', 'philadelphia', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(13, 'Président', 'president', '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(14, 'Agar', 'agar', '2026-11-26 06:54:25', '2026-11-26 06:54:34'),
(15, 'Ewald', 'ewald', '2026-11-26 06:55:51', '2026-11-26 06:55:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 2, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(3, 3, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(4, 4, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(5, 5, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(6, 6, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(7, 7, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(8, 8, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(9, 9, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(10, 10, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(11, 11, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(12, 12, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(13, 13, '2026-11-22 18:38:34', '2026-11-22 18:38:34'),
(14, 1, '2026-11-26 08:54:58', '2026-11-26 08:54:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(4, 2, 47, 1, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(5, 2, 50, 2, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(6, 6, 63, 1, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(7, 6, 2, 2, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(8, 6, 36, 1, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(9, 9, 40, 1, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(10, 9, 54, 1, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(19, 13, 50, 1, '2026-11-22 19:58:26', '2026-11-22 19:58:36'),
(23, 13, 53, 3, '2026-11-22 20:00:38', '2026-11-22 20:00:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image_path`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bột & Ngũ cốc', 'bot-ngu-coc', 'Các loại bột mì, bột bắp, yến mạch và ngũ cốc làm bánh.', 'uploads/categories/5e4084af5e691e7b1363add55aa05999.jpg', 1, '2026-11-22 18:00:40', '2026-11-22 18:09:12'),
(2, 'Chất tạo ngọt', 'chat-tao-ngot', 'Đường, mật ong, siro và các chất tạo ngọt.', 'uploads/categories/2001f20bbb2e9c5c603357df55559dc1.jpg', 1, '2026-11-22 18:00:40', '2026-11-22 18:09:06'),
(3, 'Chất béo', 'chat-beo', 'Bơ, dầu, shortening và margarine.', 'uploads/categories/1b384c9f0e7b1b4e68ec896d5c5cbe41.jpg', 1, '2026-11-22 18:00:40', '2026-11-22 18:08:58'),
(4, 'Dụng cụ làm bánh', 'dung-cu-lam-banh', 'Khuôn, cân, phới, cọ và dụng cụ cơ bản.', 'uploads/categories/93b146d0a0e590857f63cc43a48ee0c5.jpg', 1, '2026-11-22 18:00:40', '2026-11-22 18:08:52'),
(5, 'Hương liệu & Màu thực phẩm', 'huong-lieu-mau-thuc-pham', 'Vani, cacao, bột trà xanh và màu thực phẩm.', 'uploads/categories/f6baeb48e6e6e35c6ac5d8d1336052b3.jpg', 1, '2026-11-22 18:00:40', '2026-11-22 18:08:43'),
(6, 'Sữa, Kem & Trứng', 'sua-kem-trung', 'Kem tươi, sữa, phô mai kem và trứng cho bánh kem, bánh mousse.', 'uploads/categories/33c016d42bbe41f7da0aa925b01367d8.jpg', 1, '2026-11-22 18:00:40', '2026-11-22 18:08:37'),
(7, 'Socola, Hạt & Trái cây sấy', 'socola-hat-trai-cay-say', 'Socola, các loại hạt và trái cây sấy để trang trí và làm nhân bánh.', 'uploads/categories/9ab43b0bcac4b768288eccbe7d10f81c.jpg', 1, '2026-11-22 18:00:40', '2026-11-22 18:08:23'),
(8, 'Phụ gia & Chất tạo nở', 'phu-gia-chat-tao-no', 'Men, bột nở, gelatin và các chất hỗ trợ kết cấu bánh.', 'uploads/categories/bfd2dde153fe2df13f2364412e359760.jpg', 1, '2026-11-22 18:00:40', '2026-11-22 18:01:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied') NOT NULL DEFAULT 'new',
  `admin_reply` text DEFAULT NULL,
  `admin_replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `user_id`, `full_name`, `email`, `phone`, `message`, `status`, `admin_reply`, `admin_replied_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Phạm Thị Lan', 'lan.pham@gmail.com', '0912345678', 'Shop có bán sỉ bột mì và bơ cho tiệm bánh nhỏ không ạ? Mình cần khoảng 20kg mỗi tuần.', 'replied', 'Chào bạn, BakeHouse có chính sách giá sỉ từ 10kg/tuần. Bạn vui lòng để lại số điện thoại, nhân viên sẽ gọi báo giá trong ngày nhé.', '2026-03-12 03:20:00', '2026-03-11 14:05:00', '2026-03-12 03:20:00'),
(2, NULL, 'Ngô Thị Mai', 'mai.ngo@gmail.com', '0936778899', 'Mình muốn hỏi phô mai mascarpone khi nào có hàng lại vậy shop?', 'replied', 'Cảm ơn bạn, mascarpone dự kiến về hàng trong tuần sau. Shop sẽ báo ngay khi có hàng ạ.', '2026-08-04 02:00:00', '2026-08-03 11:40:00', '2026-08-04 02:00:00'),
(3, 4, 'Trần Quang Minh', 'minh.tran@gmail.com', '0933456789', 'Đơn hàng của mình giao đến bị móp hộp bột nở, shop hỗ trợ đổi giúp mình được không?', 'replied', 'BakeHouse xin lỗi bạn về sự cố. Bạn gửi ảnh sản phẩm qua email để shop đổi hàng mới miễn phí nhé.', '2026-05-22 07:10:00', '2026-05-21 13:15:00', '2026-05-22 07:10:00'),
(4, NULL, 'Lý Thanh Tùng', 'tung.ly@gmail.com', NULL, 'Mình ở Đà Lạt, shop có giao hàng đến đây không và phí ship thế nào?', 'read', NULL, NULL, '2026-09-15 01:30:00', '2026-09-15 01:30:00'),
(5, 9, 'Nguyễn Phương Thảo', 'thao.nguyen@gmail.com', '0961445566', 'Shop tư vấn giúp mình bộ dụng cụ làm cupcake cho người mới bắt đầu với ngân sách khoảng 500k.', 'read', NULL, NULL, '2026-10-02 12:45:00', '2026-10-02 12:45:00'),
(6, NULL, 'Đinh Văn Long', NULL, '0977112233', 'Cho mình hỏi hóa đơn VAT cho đơn hàng công ty thì liên hệ thế nào?', 'new', NULL, NULL, '2026-11-12 04:20:00', '2026-11-12 04:20:00'),
(7, 6, 'Võ Anh Tuấn', 'tuan.vo@gmail.com', '0905112233', 'Cân điện tử mình mua bị lệch khoảng 3g so với quả cân chuẩn, shop kiểm tra giúp mình với.', 'new', NULL, NULL, '2026-11-16 15:05:00', '2026-11-16 15:05:00'),
(8, NULL, 'Phan Kim Chi', 'chi.phan@gmail.com', '0944223311', 'Shop có chương trình ưu đãi cho khách mua nhiều trong dịp Noel không ạ?', 'new', NULL, NULL, '2026-11-18 08:40:00', '2026-11-18 08:40:00'),
(9, 1, 'Nguyễn thị ngọc', 'nkt171000@gmail.com', NULL, 'baoh shop nhập bánh gato', 'replied', 'mai nha', '2026-11-22 15:38:07', '2026-11-22 21:37:35', '2026-11-22 21:38:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(40) NOT NULL,
  `title` varchar(190) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `is_read`, `created_at`) VALUES
(1, 13, 'order_placed', 'Đặt hàng thành công', 'Đơn BH48 đã được tiếp nhận, chúng tôi sẽ sớm xử lý.', '/orders/BH48', 1, '2026-11-22 19:50:06'),
(2, 1, 'contact_replied', 'Liên hệ của bạn đã được phản hồi', 'mai nha', '/contacts', 1, '2026-11-22 21:38:07'),
(3, 3, 'order_placed', 'Đặt hàng thành công', 'Đơn BH49 đã được tiếp nhận, chúng tôi sẽ sớm xử lý.', '/orders/BH49', 1, '2026-11-26 08:52:44'),
(4, 3, 'order_processing', 'Đơn hàng đã được xác nhận', 'Đơn BH49 đang được chuẩn bị.', '/orders/BH49', 1, '2026-11-26 08:57:57'),
(5, 3, 'order_shipped', 'Đơn hàng đang được giao', 'Đơn BH49 đang trên đường đến bạn.', '/orders/BH49', 1, '2026-11-26 08:57:58'),
(6, 3, 'order_delivered', 'Đơn hàng đã hoàn tất', 'Đơn BH49 đã giao thành công.', '/orders/BH49', 1, '2026-11-26 08:57:58'),
(7, 3, 'review_reminder', 'Hãy đánh giá sản phẩm bạn vừa mua', 'Đơn BH49 đã hoàn tất — chia sẻ cảm nhận của bạn để giúp người mua sau nhé!', '/orders/BH49', 1, '2026-11-26 08:57:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(30) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_name` varchar(150) NOT NULL,
  `shipping_phone` varchar(20) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `note` varchar(500) DEFAULT NULL,
  `payment_method` enum('cod','vietqr') NOT NULL DEFAULT 'cod',
  `payment_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `status` enum('pending','processing','shipped','delivered') NOT NULL DEFAULT 'pending',
  `shipping_fee` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_amount` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `recipient_name`, `shipping_phone`, `shipping_address`, `note`, `payment_method`, `payment_status`, `status`, `shipping_fee`, `total_amount`, `created_at`, `updated_at`) VALUES
(1, 'BH01', 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', 'Đóng gói kỹ giúp mình, hàng làm quà tặng.', 'cod', 'paid', 'delivered', 30000, 691000, '2025-12-14 05:22:00', '2025-12-18 01:22:00'),
(2, 'BH02', 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', NULL, 'cod', 'paid', 'delivered', 30000, 54000, '2025-12-16 12:15:00', '2025-12-19 15:15:00'),
(3, 'BH03', 4, 'Trần Quang Minh', '0933456789', '88 Nguyễn Trãi, Thanh Xuân, Hà Nội', NULL, 'cod', 'paid', 'delivered', 30000, 883000, '2025-12-18 07:07:00', '2025-12-21 14:07:00'),
(4, 'BH04', 4, 'Trần Quang Minh', '0933456789', '88 Nguyễn Trãi, Thanh Xuân, Hà Nội', NULL, 'cod', 'paid', 'delivered', 30000, 434000, '2025-12-20 07:15:00', '2025-12-22 23:15:00'),
(5, 'BH05', 3, 'Nguyễn Văn A', '0901234567', '12 Trần Hưng Đạo, Quận 5, TP.HCM', 'Đóng gói kỹ giúp mình, hàng làm quà tặng.', 'vietqr', 'paid', 'delivered', 30000, 300000, '2026-01-05 08:40:00', '2026-01-08 03:40:00'),
(6, 'BH06', 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', NULL, 'vietqr', 'paid', 'delivered', 30000, 382000, '2026-01-07 14:40:00', '2026-01-11 02:40:00'),
(7, 'BH07', 6, 'Võ Anh Tuấn', '0905112233', '156 Điện Biên Phủ, Bình Thạnh, TP.HCM', 'Giao buổi tối sau 18h.', 'cod', 'paid', 'delivered', 30000, 252000, '2026-01-10 03:31:00', '2026-01-13 17:31:00'),
(8, 'BH08', 3, 'Nguyễn Văn A', '0901234567', '12 Trần Hưng Đạo, Quận 5, TP.HCM', NULL, 'cod', 'paid', 'delivered', 30000, 708000, '2026-01-28 02:31:00', '2026-01-30 19:31:00'),
(9, 'BH09', 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', 'Gọi trước khi giao, cảm ơn shop!', 'vietqr', 'paid', 'delivered', 30000, 630000, '2026-02-02 13:48:00', '2026-02-05 22:48:00'),
(10, 'BH10', 5, 'Lê Thu Hương', '0987654321', '23 Hoàng Diệu, Hải Châu, Đà Nẵng', NULL, 'cod', 'paid', 'delivered', 30000, 435000, '2026-02-04 06:40:00', '2026-02-08 03:40:00'),
(11, 'BH11', 6, 'Võ Anh Tuấn', '0905112233', '156 Điện Biên Phủ, Bình Thạnh, TP.HCM', 'Giao giờ hành chính giúp mình nhé.', 'vietqr', 'paid', 'delivered', 30000, 356000, '2026-02-12 07:40:00', '2026-02-15 21:40:00'),
(12, 'BH12', 5, 'Lê Thu Hương', '0987654321', '23 Hoàng Diệu, Hải Châu, Đà Nẵng', NULL, 'cod', 'paid', 'delivered', 30000, 444000, '2026-02-19 06:00:00', '2026-02-21 18:00:00'),
(13, 'BH13', 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', 'Giao buổi tối sau 18h.', 'vietqr', 'paid', 'delivered', 30000, 207000, '2026-02-23 13:22:00', '2026-02-27 21:22:00'),
(14, 'BH14', 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', 'Gọi trước khi giao, cảm ơn shop!', 'vietqr', 'paid', 'delivered', 30000, 378000, '2026-03-08 08:00:00', '2026-03-11 05:00:00'),
(15, 'BH15', 6, 'Võ Anh Tuấn', '0905112233', '156 Điện Biên Phủ, Bình Thạnh, TP.HCM', 'Giao buổi tối sau 18h.', 'vietqr', 'paid', 'delivered', 30000, 395000, '2026-03-12 12:48:00', '2026-03-16 16:48:00'),
(16, 'BH16', 7, 'Bùi Bích Ngọc', '0978223344', '9 Trần Phú, Ninh Kiều, Cần Thơ', 'Gọi trước khi giao, cảm ơn shop!', 'cod', 'paid', 'delivered', 30000, 292000, '2026-03-14 06:00:00', '2026-03-17 15:00:00'),
(17, 'BH17', 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', 'Giao giờ hành chính giúp mình nhé.', 'cod', 'paid', 'delivered', 30000, 248000, '2026-04-17 09:40:00', '2026-04-21 03:40:00'),
(18, 'BH18', 6, 'Võ Anh Tuấn', '0905112233', '156 Điện Biên Phủ, Bình Thạnh, TP.HCM', 'Giao giờ hành chính giúp mình nhé.', 'vietqr', 'paid', 'delivered', 30000, 149000, '2026-04-20 14:55:00', '2026-04-24 12:55:00'),
(19, 'BH19', 4, 'Trần Quang Minh', '0933456789', '88 Nguyễn Trãi, Thanh Xuân, Hà Nội', NULL, 'vietqr', 'paid', 'delivered', 30000, 575000, '2026-04-28 08:55:00', '2026-05-01 12:55:00'),
(20, 'BH20', 6, 'Võ Anh Tuấn', '0905112233', '156 Điện Biên Phủ, Bình Thạnh, TP.HCM', 'Đóng gói kỹ giúp mình, hàng làm quà tặng.', 'cod', 'paid', 'delivered', 30000, 268000, '2026-05-09 06:00:00', '2026-05-12 06:00:00'),
(21, 'BH21', 4, 'Trần Quang Minh', '0933456789', '88 Nguyễn Trãi, Thanh Xuân, Hà Nội', 'Giao giờ hành chính giúp mình nhé.', 'cod', 'paid', 'delivered', 30000, 408000, '2026-05-13 12:55:00', '2026-05-16 10:55:00'),
(22, 'BH22', 3, 'Nguyễn Văn A', '0901234567', '12 Trần Hưng Đạo, Quận 5, TP.HCM', NULL, 'vietqr', 'paid', 'delivered', 30000, 112000, '2026-05-21 01:31:00', '2026-05-24 04:31:00'),
(23, 'BH23', 8, 'Đặng Đăng Khoa', '0919334455', '71 Phan Chu Trinh, Hoàn Kiếm, Hà Nội', NULL, 'vietqr', 'paid', 'delivered', 30000, 489000, '2026-06-04 10:15:00', '2026-06-08 09:15:00'),
(24, 'BH24', 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', NULL, 'vietqr', 'paid', 'delivered', 30000, 519000, '2026-06-07 13:07:00', '2026-06-10 13:07:00'),
(25, 'BH25', 9, 'Nguyễn Phương Thảo', '0961445566', '202 Nguyễn Thị Minh Khai, Quận 3, TP.HCM', 'Gọi trước khi giao, cảm ơn shop!', 'vietqr', 'paid', 'delivered', 30000, 421000, '2026-06-22 03:40:00', '2026-06-25 08:40:00'),
(26, 'BH26', 6, 'Võ Anh Tuấn', '0905112233', '156 Điện Biên Phủ, Bình Thạnh, TP.HCM', 'Giao buổi tối sau 18h.', 'cod', 'paid', 'delivered', 30000, 215000, '2026-07-10 07:15:00', '2026-07-13 10:15:00'),
(27, 'BH27', 6, 'Võ Anh Tuấn', '0905112233', '156 Điện Biên Phủ, Bình Thạnh, TP.HCM', NULL, 'cod', 'paid', 'delivered', 30000, 125000, '2026-07-11 01:00:00', '2026-07-14 12:00:00'),
(28, 'BH28', 3, 'Nguyễn Văn A', '0901234567', '12 Trần Hưng Đạo, Quận 5, TP.HCM', NULL, 'cod', 'paid', 'delivered', 30000, 607000, '2026-07-24 13:55:00', '2026-07-27 20:55:00'),
(29, 'BH29', 4, 'Trần Quang Minh', '0933456789', '88 Nguyễn Trãi, Thanh Xuân, Hà Nội', NULL, 'cod', 'paid', 'delivered', 30000, 956000, '2026-08-01 02:15:00', '2026-08-03 13:15:00'),
(30, 'BH30', 9, 'Nguyễn Phương Thảo', '0961445566', '202 Nguyễn Thị Minh Khai, Quận 3, TP.HCM', NULL, 'vietqr', 'paid', 'delivered', 30000, 182000, '2026-08-09 12:00:00', '2026-08-12 01:00:00'),
(31, 'BH31', 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', NULL, 'vietqr', 'paid', 'delivered', 30000, 1062000, '2026-08-23 02:00:00', '2026-08-26 08:00:00'),
(32, 'BH32', 9, 'Nguyễn Phương Thảo', '0961445566', '202 Nguyễn Thị Minh Khai, Quận 3, TP.HCM', NULL, 'cod', 'paid', 'delivered', 30000, 490000, '2026-09-08 14:31:00', '2026-09-11 11:31:00'),
(33, 'BH33', 8, 'Đặng Đăng Khoa', '0919334455', '71 Phan Chu Trinh, Hoàn Kiếm, Hà Nội', NULL, 'cod', 'paid', 'delivered', 30000, 315000, '2026-09-09 01:40:00', '2026-09-12 04:40:00'),
(34, 'BH34', 3, 'Nguyễn Văn A', '0901234567', '12 Trần Hưng Đạo, Quận 5, TP.HCM', NULL, 'cod', 'paid', 'delivered', 30000, 516000, '2026-09-13 09:00:00', '2026-09-16 10:00:00'),
(35, 'BH35', 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', 'Đóng gói kỹ giúp mình, hàng làm quà tặng.', 'cod', 'paid', 'delivered', 30000, 561000, '2026-09-24 03:07:00', '2026-09-26 10:07:00'),
(36, 'BH36', 4, 'Trần Quang Minh', '0933456789', '88 Nguyễn Trãi, Thanh Xuân, Hà Nội', 'Giao giờ hành chính giúp mình nhé.', 'cod', 'paid', 'delivered', 30000, 459000, '2026-10-01 09:00:00', '2026-10-04 02:00:00'),
(37, 'BH37', 5, 'Lê Thu Hương', '0987654321', '23 Hoàng Diệu, Hải Châu, Đà Nẵng', NULL, 'cod', 'paid', 'delivered', 30000, 357000, '2026-10-02 08:48:00', '2026-10-05 13:48:00'),
(38, 'BH38', 4, 'Trần Quang Minh', '0933456789', '88 Nguyễn Trãi, Thanh Xuân, Hà Nội', NULL, 'vietqr', 'paid', 'delivered', 30000, 914000, '2026-10-06 11:31:00', '2026-10-10 11:31:00'),
(39, 'BH39', 4, 'Trần Quang Minh', '0933456789', '88 Nguyễn Trãi, Thanh Xuân, Hà Nội', NULL, 'cod', 'paid', 'delivered', 30000, 711000, '2026-10-07 09:48:00', '2026-10-10 21:48:00'),
(40, 'BH40', 4, 'Trần Quang Minh', '0933456789', '88 Nguyễn Trãi, Thanh Xuân, Hà Nội', NULL, 'cod', 'paid', 'delivered', 30000, 217000, '2026-10-12 09:40:00', '2026-10-15 01:40:00'),
(41, 'BH41', 8, 'Đặng Đăng Khoa', '0919334455', '71 Phan Chu Trinh, Hoàn Kiếm, Hà Nội', 'Đóng gói kỹ giúp mình, hàng làm quà tặng.', 'vietqr', 'paid', 'delivered', 30000, 412000, '2026-11-02 02:40:00', '2026-11-05 00:40:00'),
(42, 'BH42', 3, 'Nguyễn Văn A', '0901234567', '12 Trần Hưng Đạo, Quận 5, TP.HCM', NULL, 'cod', 'unpaid', 'shipped', 30000, 179000, '2026-11-09 07:55:00', '2026-11-10 20:55:00'),
(43, 'BH43', 6, 'Võ Anh Tuấn', '0905112233', '156 Điện Biên Phủ, Bình Thạnh, TP.HCM', NULL, 'vietqr', 'paid', 'delivered', 30000, 601000, '2026-11-11 04:40:00', '2026-11-14 18:40:00'),
(44, 'BH44', 8, 'Đặng Đăng Khoa', '0919334455', '71 Phan Chu Trinh, Hoàn Kiếm, Hà Nội', 'Gọi trước khi giao, cảm ơn shop!', 'vietqr', 'paid', 'processing', 30000, 632000, '2026-11-14 03:00:00', '2026-11-14 09:00:00'),
(45, 'BH45', 8, 'Đặng Đăng Khoa', '0919334455', '71 Phan Chu Trinh, Hoàn Kiếm, Hà Nội', 'Giao buổi tối sau 18h.', 'cod', 'unpaid', 'pending', 30000, 411000, '2026-11-15 14:40:00', '2026-11-15 14:40:00'),
(46, 'BH46', 3, 'Nguyễn Văn A', '0901234567', '12 Trần Hưng Đạo, Quận 5, TP.HCM', 'Giao giờ hành chính giúp mình nhé.', 'vietqr', 'paid', 'processing', 30000, 198000, '2026-11-16 08:48:00', '2026-11-16 18:48:00'),
(47, 'BH47', 3, 'Nguyễn Văn A', '0901234567', '12 Trần Hưng Đạo, Quận 5, TP.HCM', NULL, 'cod', 'unpaid', 'pending', 30000, 150000, '2026-11-17 05:40:00', '2026-11-17 05:40:00'),
(48, 'BH48', 13, 'ngoc hong', '0334583507', 'nn', NULL, 'vietqr', 'unpaid', 'pending', 30000, 1608000, '2026-11-22 19:50:06', '2026-11-22 19:50:06'),
(49, 'BH49', 3, 'Nguyễn Văn A', '0901234567', '12 Trần Hưng Đạo, Quận 5, TP.HCM', NULL, 'cod', 'paid', 'delivered', 30000, 198000, '2026-11-26 08:52:44', '2026-11-26 08:57:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(190) NOT NULL,
  `unit_price` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','processing','shipped','delivered') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 35, 'Máy Đánh Trứng Cầm Tay 5 Tốc Độ', 420000, 1, 'delivered', '2025-12-14 05:22:00', '2025-12-18 01:22:00'),
(2, 1, 27, 'Cân Điện Tử Nhà Bếp 5kg', 175000, 1, 'delivered', '2025-12-14 05:22:00', '2025-12-18 01:22:00'),
(3, 1, 61, 'Bột Nở Baking Powder', 22000, 3, 'delivered', '2025-12-14 05:22:00', '2025-12-18 01:22:00'),
(4, 2, 49, 'Sữa Đặc Có Đường Ông Thọ', 24000, 1, 'delivered', '2025-12-16 12:15:00', '2025-12-19 15:15:00'),
(5, 3, 35, 'Máy Đánh Trứng Cầm Tay 5 Tốc Độ', 420000, 1, 'delivered', '2025-12-18 07:07:00', '2025-12-21 14:07:00'),
(6, 3, 19, 'Bơ Lạt Anchor', 65000, 1, 'delivered', '2025-12-18 07:07:00', '2025-12-21 14:07:00'),
(7, 3, 21, 'Bơ Lạt Président', 78000, 2, 'delivered', '2025-12-18 07:07:00', '2025-12-21 14:07:00'),
(8, 3, 44, 'Bột Quế', 38000, 1, 'delivered', '2025-12-18 07:07:00', '2025-12-21 14:07:00'),
(9, 3, 23, 'Bơ Thực Vật Margarine', 58000, 3, 'delivered', '2025-12-18 07:07:00', '2025-12-21 14:07:00'),
(10, 4, 8, 'Bột Hạnh Nhân', 98000, 3, 'delivered', '2025-12-20 07:15:00', '2025-12-22 23:15:00'),
(11, 4, 6, 'Bột Gạo Nếp', 19000, 2, 'delivered', '2025-12-20 07:15:00', '2025-12-22 23:15:00'),
(12, 4, 49, 'Sữa Đặc Có Đường Ông Thọ', 24000, 3, 'delivered', '2025-12-20 07:15:00', '2025-12-22 23:15:00'),
(13, 5, 2, 'Bột Mì Số 11 (Bread Flour)', 32000, 4, 'delivered', '2026-01-05 08:40:00', '2026-01-08 03:40:00'),
(14, 5, 14, 'Đường Caster Hạt Mịn', 26000, 1, 'delivered', '2026-01-05 08:40:00', '2026-01-08 03:40:00'),
(15, 5, 23, 'Bơ Thực Vật Margarine', 58000, 2, 'delivered', '2026-01-05 08:40:00', '2026-01-08 03:40:00'),
(16, 6, 32, 'Cọ Phết Bơ Silicon', 29000, 2, 'delivered', '2026-01-07 14:40:00', '2026-01-11 02:40:00'),
(17, 6, 56, 'Hạnh Nhân Lát', 89000, 2, 'delivered', '2026-01-07 14:40:00', '2026-01-11 02:40:00'),
(18, 6, 23, 'Bơ Thực Vật Margarine', 58000, 2, 'delivered', '2026-01-07 14:40:00', '2026-01-11 02:40:00'),
(19, 7, 11, 'Đường Cát Trắng', 24000, 1, 'delivered', '2026-01-10 03:31:00', '2026-01-13 17:31:00'),
(20, 7, 15, 'Mật Ong Hoa Nhãn', 120000, 1, 'delivered', '2026-01-10 03:31:00', '2026-01-13 17:31:00'),
(21, 7, 63, 'Men Khô Instant Mauri', 45000, 1, 'delivered', '2026-01-10 03:31:00', '2026-01-13 17:31:00'),
(22, 7, 48, 'Sữa Tươi Tiệt Trùng Không Đường Vinamilk', 33000, 1, 'delivered', '2026-01-10 03:31:00', '2026-01-13 17:31:00'),
(23, 8, 36, 'Khuôn Bánh Mì Hình Chữ Nhật', 98000, 1, 'delivered', '2026-01-28 02:31:00', '2026-01-30 19:31:00'),
(24, 8, 47, 'Kem Tươi Whipping Cream Anchor', 135000, 3, 'delivered', '2026-01-28 02:31:00', '2026-01-30 19:31:00'),
(25, 8, 27, 'Cân Điện Tử Nhà Bếp 5kg', 175000, 1, 'delivered', '2026-01-28 02:31:00', '2026-01-30 19:31:00'),
(26, 9, 47, 'Kem Tươi Whipping Cream Anchor', 135000, 2, 'delivered', '2026-02-02 13:48:00', '2026-02-05 22:48:00'),
(27, 9, 37, 'Bộ Túi Bắt Kem Và Đui Inox 12 Món Wilton', 185000, 1, 'delivered', '2026-02-02 13:48:00', '2026-02-05 22:48:00'),
(28, 9, 26, 'Khuôn Bánh Tròn Wilton 20cm', 145000, 1, 'delivered', '2026-02-02 13:48:00', '2026-02-05 22:48:00'),
(29, 10, 20, 'Bơ Mặn Anchor', 66000, 1, 'delivered', '2026-02-04 06:40:00', '2026-02-08 03:40:00'),
(30, 10, 14, 'Đường Caster Hạt Mịn', 26000, 2, 'delivered', '2026-02-04 06:40:00', '2026-02-08 03:40:00'),
(31, 10, 10, 'Granola Trộn Hạt Và Mật Ong', 85000, 3, 'delivered', '2026-02-04 06:40:00', '2026-02-08 03:40:00'),
(32, 10, 7, 'Bột Năng (Tapioca Starch)', 16000, 2, 'delivered', '2026-02-04 06:40:00', '2026-02-08 03:40:00'),
(33, 11, 36, 'Khuôn Bánh Mì Hình Chữ Nhật', 98000, 3, 'delivered', '2026-02-12 07:40:00', '2026-02-15 21:40:00'),
(34, 11, 29, 'Giấy Nến Nướng Bánh', 32000, 1, 'delivered', '2026-02-12 07:40:00', '2026-02-15 21:40:00'),
(35, 12, 13, 'Đường Bột (Icing Sugar)', 22000, 4, 'delivered', '2026-02-19 06:00:00', '2026-02-21 18:00:00'),
(36, 12, 50, 'Phô Mai Kem Philadelphia', 79000, 2, 'delivered', '2026-02-19 06:00:00', '2026-02-21 18:00:00'),
(37, 12, 9, 'Bột Mì Nguyên Cám (Whole Wheat)', 42000, 4, 'delivered', '2026-02-19 06:00:00', '2026-02-21 18:00:00'),
(38, 13, 33, 'Khuôn Cupcake 12 Ly Wilton', 135000, 1, 'delivered', '2026-02-23 13:22:00', '2026-02-27 21:22:00'),
(39, 13, 34, 'Phới Dẹt Silicon (Spatula)', 42000, 1, 'delivered', '2026-02-23 13:22:00', '2026-02-27 21:22:00'),
(40, 14, 17, 'Mạch Nha (Malt Syrup)', 48000, 2, 'delivered', '2026-03-08 08:00:00', '2026-03-11 05:00:00'),
(41, 14, 7, 'Bột Năng (Tapioca Starch)', 16000, 2, 'delivered', '2026-03-08 08:00:00', '2026-03-11 05:00:00'),
(42, 14, 58, 'Nho Khô Không Hạt', 55000, 4, 'delivered', '2026-03-08 08:00:00', '2026-03-11 05:00:00'),
(43, 15, 25, 'Dầu Dừa Nguyên Chất', 85000, 2, 'delivered', '2026-03-12 12:48:00', '2026-03-16 16:48:00'),
(44, 15, 23, 'Bơ Thực Vật Margarine', 58000, 2, 'delivered', '2026-03-12 12:48:00', '2026-03-16 16:48:00'),
(45, 15, 50, 'Phô Mai Kem Philadelphia', 79000, 1, 'delivered', '2026-03-12 12:48:00', '2026-03-16 16:48:00'),
(46, 16, 23, 'Bơ Thực Vật Margarine', 58000, 1, 'delivered', '2026-03-14 06:00:00', '2026-03-17 15:00:00'),
(47, 16, 53, 'Socola Chip Bán Ngọt', 68000, 3, 'delivered', '2026-03-14 06:00:00', '2026-03-17 15:00:00'),
(48, 17, 53, 'Socola Chip Bán Ngọt', 68000, 1, 'delivered', '2026-04-17 09:40:00', '2026-04-21 03:40:00'),
(49, 17, 41, 'Bột Cacao Nguyên Chất', 75000, 2, 'delivered', '2026-04-17 09:40:00', '2026-04-21 03:40:00'),
(50, 18, 13, 'Đường Bột (Icing Sugar)', 22000, 2, 'delivered', '2026-04-20 14:55:00', '2026-04-24 12:55:00'),
(51, 18, 41, 'Bột Cacao Nguyên Chất', 75000, 1, 'delivered', '2026-04-20 14:55:00', '2026-04-24 12:55:00'),
(52, 19, 18, 'Siro Đường Vàng (Golden Syrup)', 79000, 4, 'delivered', '2026-04-28 08:55:00', '2026-05-01 12:55:00'),
(53, 19, 41, 'Bột Cacao Nguyên Chất', 75000, 1, 'delivered', '2026-04-28 08:55:00', '2026-05-01 12:55:00'),
(54, 19, 32, 'Cọ Phết Bơ Silicon', 29000, 4, 'delivered', '2026-04-28 08:55:00', '2026-05-01 12:55:00'),
(55, 19, 52, 'Trứng Gà Ta', 38000, 1, 'delivered', '2026-04-28 08:55:00', '2026-05-01 12:55:00'),
(56, 20, 57, 'Óc Chó (Walnut) Nhập Khẩu', 98000, 1, 'delivered', '2026-05-09 06:00:00', '2026-05-12 06:00:00'),
(57, 20, 58, 'Nho Khô Không Hạt', 55000, 1, 'delivered', '2026-05-09 06:00:00', '2026-05-12 06:00:00'),
(58, 20, 39, 'Tô Trộn Inox 24cm', 85000, 1, 'delivered', '2026-05-09 06:00:00', '2026-05-12 06:00:00'),
(59, 21, 31, 'Rây Bột Cầm Tay', 48000, 2, 'delivered', '2026-05-13 12:55:00', '2026-05-16 10:55:00'),
(60, 21, 54, 'Socola Đen Callebaut 54% (Callets)', 195000, 1, 'delivered', '2026-05-13 12:55:00', '2026-05-16 10:55:00'),
(61, 21, 23, 'Bơ Thực Vật Margarine', 58000, 1, 'delivered', '2026-05-13 12:55:00', '2026-05-16 10:55:00'),
(62, 21, 32, 'Cọ Phết Bơ Silicon', 29000, 1, 'delivered', '2026-05-13 12:55:00', '2026-05-16 10:55:00'),
(63, 22, 2, 'Bột Mì Số 11 (Bread Flour)', 32000, 2, 'delivered', '2026-05-21 01:31:00', '2026-05-24 04:31:00'),
(64, 22, 4, 'Bột Bắp Biên Hòa', 18000, 1, 'delivered', '2026-05-21 01:31:00', '2026-05-24 04:31:00'),
(65, 23, 60, 'Nam Việt Quất Sấy', 89000, 2, 'delivered', '2026-06-04 10:15:00', '2026-06-08 09:15:00'),
(66, 23, 23, 'Bơ Thực Vật Margarine', 58000, 1, 'delivered', '2026-06-04 10:15:00', '2026-06-08 09:15:00'),
(67, 23, 26, 'Khuôn Bánh Tròn Wilton 20cm', 145000, 1, 'delivered', '2026-06-04 10:15:00', '2026-06-08 09:15:00'),
(68, 23, 21, 'Bơ Lạt Président', 78000, 1, 'delivered', '2026-06-04 10:15:00', '2026-06-08 09:15:00'),
(69, 24, 65, 'Bột Rau Câu Dẻo', 14000, 2, 'delivered', '2026-06-07 13:07:00', '2026-06-10 13:07:00'),
(70, 24, 21, 'Bơ Lạt Président', 78000, 3, 'delivered', '2026-06-07 13:07:00', '2026-06-10 13:07:00'),
(71, 24, 41, 'Bột Cacao Nguyên Chất', 75000, 1, 'delivered', '2026-06-07 13:07:00', '2026-06-10 13:07:00'),
(72, 24, 53, 'Socola Chip Bán Ngọt', 68000, 2, 'delivered', '2026-06-07 13:07:00', '2026-06-10 13:07:00'),
(73, 24, 7, 'Bột Năng (Tapioca Starch)', 16000, 1, 'delivered', '2026-06-07 13:07:00', '2026-06-10 13:07:00'),
(74, 25, 17, 'Mạch Nha (Malt Syrup)', 48000, 1, 'delivered', '2026-06-22 03:40:00', '2026-06-25 08:40:00'),
(75, 25, 9, 'Bột Mì Nguyên Cám (Whole Wheat)', 42000, 2, 'delivered', '2026-06-22 03:40:00', '2026-06-25 08:40:00'),
(76, 25, 64, 'Gelatin Lá', 38000, 2, 'delivered', '2026-06-22 03:40:00', '2026-06-25 08:40:00'),
(77, 25, 33, 'Khuôn Cupcake 12 Ly Wilton', 135000, 1, 'delivered', '2026-06-22 03:40:00', '2026-06-25 08:40:00'),
(78, 25, 7, 'Bột Năng (Tapioca Starch)', 16000, 3, 'delivered', '2026-06-22 03:40:00', '2026-06-25 08:40:00'),
(79, 26, 37, 'Bộ Túi Bắt Kem Và Đui Inox 12 Món Wilton', 185000, 1, 'delivered', '2026-07-10 07:15:00', '2026-07-13 10:15:00'),
(80, 27, 24, 'Shortening (Mỡ Thực Vật Làm Bánh)', 95000, 1, 'delivered', '2026-07-11 01:00:00', '2026-07-14 12:00:00'),
(81, 28, 26, 'Khuôn Bánh Tròn Wilton 20cm', 145000, 1, 'delivered', '2026-07-24 13:55:00', '2026-07-27 20:55:00'),
(82, 28, 34, 'Phới Dẹt Silicon (Spatula)', 42000, 1, 'delivered', '2026-07-24 13:55:00', '2026-07-27 20:55:00'),
(83, 28, 55, 'Socola Trắng Callebaut (Callets)', 195000, 2, 'delivered', '2026-07-24 13:55:00', '2026-07-27 20:55:00'),
(84, 29, 47, 'Kem Tươi Whipping Cream Anchor', 135000, 3, 'delivered', '2026-08-01 02:15:00', '2026-08-03 13:15:00'),
(85, 29, 42, 'Bột Trà Xanh Matcha', 150000, 1, 'delivered', '2026-08-01 02:15:00', '2026-08-03 13:15:00'),
(86, 29, 22, 'Dầu Ăn Thực Vật Tường An', 52000, 2, 'delivered', '2026-08-01 02:15:00', '2026-08-03 13:15:00'),
(87, 29, 56, 'Hạnh Nhân Lát', 89000, 3, 'delivered', '2026-08-01 02:15:00', '2026-08-03 13:15:00'),
(88, 30, 53, 'Socola Chip Bán Ngọt', 68000, 1, 'delivered', '2026-08-09 12:00:00', '2026-08-12 01:00:00'),
(89, 30, 22, 'Dầu Ăn Thực Vật Tường An', 52000, 1, 'delivered', '2026-08-09 12:00:00', '2026-08-12 01:00:00'),
(90, 30, 29, 'Giấy Nến Nướng Bánh', 32000, 1, 'delivered', '2026-08-09 12:00:00', '2026-08-12 01:00:00'),
(91, 31, 40, 'Tinh Chất Vani Nguyên Chất', 220000, 3, 'delivered', '2026-08-23 02:00:00', '2026-08-26 08:00:00'),
(92, 31, 18, 'Siro Đường Vàng (Golden Syrup)', 79000, 3, 'delivered', '2026-08-23 02:00:00', '2026-08-26 08:00:00'),
(93, 31, 47, 'Kem Tươi Whipping Cream Anchor', 135000, 1, 'delivered', '2026-08-23 02:00:00', '2026-08-26 08:00:00'),
(94, 32, 30, 'Cán Bột Gỗ', 68000, 1, 'delivered', '2026-09-08 14:31:00', '2026-09-11 11:31:00'),
(95, 32, 8, 'Bột Hạnh Nhân', 98000, 4, 'delivered', '2026-09-08 14:31:00', '2026-09-11 11:31:00'),
(96, 33, 56, 'Hạnh Nhân Lát', 89000, 1, 'delivered', '2026-09-09 01:40:00', '2026-09-12 04:40:00'),
(97, 33, 57, 'Óc Chó (Walnut) Nhập Khẩu', 98000, 2, 'delivered', '2026-09-09 01:40:00', '2026-09-12 04:40:00'),
(98, 34, 54, 'Socola Đen Callebaut 54% (Callets)', 195000, 2, 'delivered', '2026-09-13 09:00:00', '2026-09-16 10:00:00'),
(99, 34, 17, 'Mạch Nha (Malt Syrup)', 48000, 2, 'delivered', '2026-09-13 09:00:00', '2026-09-16 10:00:00'),
(100, 35, 49, 'Sữa Đặc Có Đường Ông Thọ', 24000, 4, 'delivered', '2026-09-24 03:07:00', '2026-09-26 10:07:00'),
(101, 35, 33, 'Khuôn Cupcake 12 Ly Wilton', 135000, 1, 'delivered', '2026-09-24 03:07:00', '2026-09-26 10:07:00'),
(102, 35, 17, 'Mạch Nha (Malt Syrup)', 48000, 3, 'delivered', '2026-09-24 03:07:00', '2026-09-26 10:07:00'),
(103, 35, 21, 'Bơ Lạt Président', 78000, 2, 'delivered', '2026-09-24 03:07:00', '2026-09-26 10:07:00'),
(104, 36, 37, 'Bộ Túi Bắt Kem Và Đui Inox 12 Món Wilton', 185000, 1, 'delivered', '2026-10-01 09:00:00', '2026-10-04 02:00:00'),
(105, 36, 3, 'Bột Mì Số 8 (Cake Flour)', 34000, 1, 'delivered', '2026-10-01 09:00:00', '2026-10-04 02:00:00'),
(106, 36, 29, 'Giấy Nến Nướng Bánh', 32000, 1, 'delivered', '2026-10-01 09:00:00', '2026-10-04 02:00:00'),
(107, 36, 38, 'Nhiệt Kế Lò Nướng', 89000, 2, 'delivered', '2026-10-01 09:00:00', '2026-10-04 02:00:00'),
(108, 37, 33, 'Khuôn Cupcake 12 Ly Wilton', 135000, 1, 'delivered', '2026-10-02 08:48:00', '2026-10-05 13:48:00'),
(109, 37, 3, 'Bột Mì Số 8 (Cake Flour)', 34000, 1, 'delivered', '2026-10-02 08:48:00', '2026-10-05 13:48:00'),
(110, 37, 18, 'Siro Đường Vàng (Golden Syrup)', 79000, 2, 'delivered', '2026-10-02 08:48:00', '2026-10-05 13:48:00'),
(111, 38, 33, 'Khuôn Cupcake 12 Ly Wilton', 135000, 1, 'delivered', '2026-10-06 11:31:00', '2026-10-10 11:31:00'),
(112, 38, 16, 'Siro Phong (Maple Syrup)', 165000, 4, 'delivered', '2026-10-06 11:31:00', '2026-10-10 11:31:00'),
(113, 38, 38, 'Nhiệt Kế Lò Nướng', 89000, 1, 'delivered', '2026-10-06 11:31:00', '2026-10-10 11:31:00'),
(114, 39, 5, 'Yến Mạch Cán Dẹt', 45000, 2, 'delivered', '2026-10-07 09:48:00', '2026-10-10 21:48:00'),
(115, 39, 47, 'Kem Tươi Whipping Cream Anchor', 135000, 3, 'delivered', '2026-10-07 09:48:00', '2026-10-10 21:48:00'),
(116, 39, 52, 'Trứng Gà Ta', 38000, 2, 'delivered', '2026-10-07 09:48:00', '2026-10-10 21:48:00'),
(117, 39, 58, 'Nho Khô Không Hạt', 55000, 2, 'delivered', '2026-10-07 09:48:00', '2026-10-10 21:48:00'),
(118, 40, 9, 'Bột Mì Nguyên Cám (Whole Wheat)', 42000, 1, 'delivered', '2026-10-12 09:40:00', '2026-10-15 01:40:00'),
(119, 40, 26, 'Khuôn Bánh Tròn Wilton 20cm', 145000, 1, 'delivered', '2026-10-12 09:40:00', '2026-10-15 01:40:00'),
(120, 41, 25, 'Dầu Dừa Nguyên Chất', 85000, 2, 'delivered', '2026-11-02 02:40:00', '2026-11-05 00:40:00'),
(121, 41, 59, 'Dừa Sấy Sợi', 42000, 2, 'delivered', '2026-11-02 02:40:00', '2026-11-05 00:40:00'),
(122, 41, 4, 'Bột Bắp Biên Hòa', 18000, 2, 'delivered', '2026-11-02 02:40:00', '2026-11-05 00:40:00'),
(123, 41, 7, 'Bột Năng (Tapioca Starch)', 16000, 1, 'delivered', '2026-11-02 02:40:00', '2026-11-05 00:40:00'),
(124, 41, 52, 'Trứng Gà Ta', 38000, 2, 'delivered', '2026-11-02 02:40:00', '2026-11-05 00:40:00'),
(125, 42, 48, 'Sữa Tươi Tiệt Trùng Không Đường Vinamilk', 33000, 1, 'shipped', '2026-11-09 07:55:00', '2026-11-10 20:55:00'),
(126, 42, 3, 'Bột Mì Số 8 (Cake Flour)', 34000, 3, 'shipped', '2026-11-09 07:55:00', '2026-11-10 20:55:00'),
(127, 42, 65, 'Bột Rau Câu Dẻo', 14000, 1, 'shipped', '2026-11-09 07:55:00', '2026-11-10 20:55:00'),
(128, 43, 38, 'Nhiệt Kế Lò Nướng', 89000, 1, 'delivered', '2026-11-11 04:40:00', '2026-11-14 18:40:00'),
(129, 43, 24, 'Shortening (Mỡ Thực Vật Làm Bánh)', 95000, 2, 'delivered', '2026-11-11 04:40:00', '2026-11-14 18:40:00'),
(130, 43, 49, 'Sữa Đặc Có Đường Ông Thọ', 24000, 3, 'delivered', '2026-11-11 04:40:00', '2026-11-14 18:40:00'),
(131, 43, 40, 'Tinh Chất Vani Nguyên Chất', 220000, 1, 'delivered', '2026-11-11 04:40:00', '2026-11-14 18:40:00'),
(132, 44, 50, 'Phô Mai Kem Philadelphia', 79000, 2, 'processing', '2026-11-14 03:00:00', '2026-11-14 09:00:00'),
(133, 44, 20, 'Bơ Mặn Anchor', 66000, 2, 'processing', '2026-11-14 03:00:00', '2026-11-14 09:00:00'),
(134, 44, 34, 'Phới Dẹt Silicon (Spatula)', 42000, 1, 'processing', '2026-11-14 03:00:00', '2026-11-14 09:00:00'),
(135, 44, 25, 'Dầu Dừa Nguyên Chất', 85000, 1, 'processing', '2026-11-14 03:00:00', '2026-11-14 09:00:00'),
(136, 44, 37, 'Bộ Túi Bắt Kem Và Đui Inox 12 Món Wilton', 185000, 1, 'processing', '2026-11-14 03:00:00', '2026-11-14 09:00:00'),
(137, 45, 37, 'Bộ Túi Bắt Kem Và Đui Inox 12 Món Wilton', 185000, 1, 'pending', '2026-11-15 14:40:00', '2026-11-15 14:40:00'),
(138, 45, 20, 'Bơ Mặn Anchor', 66000, 2, 'pending', '2026-11-15 14:40:00', '2026-11-15 14:40:00'),
(139, 45, 2, 'Bột Mì Số 11 (Bread Flour)', 32000, 2, 'pending', '2026-11-15 14:40:00', '2026-11-15 14:40:00'),
(140, 46, 29, 'Giấy Nến Nướng Bánh', 32000, 2, 'processing', '2026-11-16 08:48:00', '2026-11-16 18:48:00'),
(141, 46, 14, 'Đường Caster Hạt Mịn', 26000, 2, 'processing', '2026-11-16 08:48:00', '2026-11-16 18:48:00'),
(142, 46, 22, 'Dầu Ăn Thực Vật Tường An', 52000, 1, 'processing', '2026-11-16 08:48:00', '2026-11-16 18:48:00'),
(143, 47, 15, 'Mật Ong Hoa Nhãn', 120000, 1, 'pending', '2026-11-17 05:40:00', '2026-11-17 05:40:00'),
(144, 48, 18, 'Siro Đường Vàng (Golden Syrup)', 79000, 18, 'pending', '2026-11-22 19:50:06', '2026-11-22 19:50:06'),
(145, 48, 66, 'Kem Tartar (Cream of Tartar)', 52000, 3, 'pending', '2026-11-22 19:50:06', '2026-11-22 19:50:06'),
(146, 49, 9, 'Bột Mì Nguyên Cám (Whole Wheat)', 42000, 4, 'delivered', '2026-11-26 08:52:44', '2026-11-26 08:57:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_status_histories`
--

CREATE TABLE `order_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `from_status` enum('pending','processing','shipped','delivered') DEFAULT NULL,
  `to_status` enum('pending','processing','shipped','delivered') NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_status_histories`
--

INSERT INTO `order_status_histories` (`id`, `order_id`, `from_status`, `to_status`, `changed_by`, `created_at`) VALUES
(1, 1, NULL, 'pending', 2, '2025-12-14 05:22:00'),
(2, 1, 'pending', 'processing', 1, '2025-12-14 22:22:00'),
(3, 1, 'processing', 'shipped', 1, '2025-12-15 23:22:00'),
(4, 1, 'shipped', 'delivered', 1, '2025-12-18 01:22:00'),
(5, 2, NULL, 'pending', 2, '2025-12-16 12:15:00'),
(6, 2, 'pending', 'processing', 1, '2025-12-16 15:15:00'),
(7, 2, 'processing', 'shipped', 1, '2025-12-17 16:15:00'),
(8, 2, 'shipped', 'delivered', 1, '2025-12-19 15:15:00'),
(9, 3, NULL, 'pending', 4, '2025-12-18 07:07:00'),
(10, 3, 'pending', 'processing', 1, '2025-12-18 23:07:00'),
(11, 3, 'processing', 'shipped', 1, '2025-12-19 22:07:00'),
(12, 3, 'shipped', 'delivered', 1, '2025-12-21 14:07:00'),
(13, 4, NULL, 'pending', 4, '2025-12-20 07:15:00'),
(14, 4, 'pending', 'processing', 1, '2025-12-21 01:15:00'),
(15, 4, 'processing', 'shipped', 1, '2025-12-21 17:15:00'),
(16, 4, 'shipped', 'delivered', 1, '2025-12-22 23:15:00'),
(17, 5, NULL, 'pending', 3, '2026-01-05 08:40:00'),
(18, 5, 'pending', 'processing', 1, '2026-01-06 03:40:00'),
(19, 5, 'processing', 'shipped', 1, '2026-01-06 19:40:00'),
(20, 5, 'shipped', 'delivered', 1, '2026-01-08 03:40:00'),
(21, 6, NULL, 'pending', 2, '2026-01-07 14:40:00'),
(22, 6, 'pending', 'processing', 1, '2026-01-08 09:40:00'),
(23, 6, 'processing', 'shipped', 1, '2026-01-09 10:40:00'),
(24, 6, 'shipped', 'delivered', 1, '2026-01-11 02:40:00'),
(25, 7, NULL, 'pending', 6, '2026-01-10 03:31:00'),
(26, 7, 'pending', 'processing', 1, '2026-01-10 20:31:00'),
(27, 7, 'processing', 'shipped', 1, '2026-01-11 13:31:00'),
(28, 7, 'shipped', 'delivered', 1, '2026-01-13 17:31:00'),
(29, 8, NULL, 'pending', 3, '2026-01-28 02:31:00'),
(30, 8, 'pending', 'processing', 1, '2026-01-28 09:31:00'),
(31, 8, 'processing', 'shipped', 1, '2026-01-29 02:31:00'),
(32, 8, 'shipped', 'delivered', 1, '2026-01-30 19:31:00'),
(33, 9, NULL, 'pending', 2, '2026-02-02 13:48:00'),
(34, 9, 'pending', 'processing', 1, '2026-02-03 01:48:00'),
(35, 9, 'processing', 'shipped', 1, '2026-02-04 07:48:00'),
(36, 9, 'shipped', 'delivered', 1, '2026-02-05 22:48:00'),
(37, 10, NULL, 'pending', 5, '2026-02-04 06:40:00'),
(38, 10, 'pending', 'processing', 1, '2026-02-04 21:40:00'),
(39, 10, 'processing', 'shipped', 1, '2026-02-05 22:40:00'),
(40, 10, 'shipped', 'delivered', 1, '2026-02-08 03:40:00'),
(41, 11, NULL, 'pending', 6, '2026-02-12 07:40:00'),
(42, 11, 'pending', 'processing', 1, '2026-02-12 20:40:00'),
(43, 11, 'processing', 'shipped', 1, '2026-02-13 18:40:00'),
(44, 11, 'shipped', 'delivered', 1, '2026-02-15 21:40:00'),
(45, 12, NULL, 'pending', 5, '2026-02-19 06:00:00'),
(46, 12, 'pending', 'processing', 1, '2026-02-19 12:00:00'),
(47, 12, 'processing', 'shipped', 1, '2026-02-20 07:00:00'),
(48, 12, 'shipped', 'delivered', 1, '2026-02-21 18:00:00'),
(49, 13, NULL, 'pending', 2, '2026-02-23 13:22:00'),
(50, 13, 'pending', 'processing', 1, '2026-02-24 06:22:00'),
(51, 13, 'processing', 'shipped', 1, '2026-02-25 09:22:00'),
(52, 13, 'shipped', 'delivered', 1, '2026-02-27 21:22:00'),
(53, 14, NULL, 'pending', 2, '2026-03-08 08:00:00'),
(54, 14, 'pending', 'processing', 1, '2026-03-08 15:00:00'),
(55, 14, 'processing', 'shipped', 1, '2026-03-09 19:00:00'),
(56, 14, 'shipped', 'delivered', 1, '2026-03-11 05:00:00'),
(57, 15, NULL, 'pending', 6, '2026-03-12 12:48:00'),
(58, 15, 'pending', 'processing', 1, '2026-03-13 00:48:00'),
(59, 15, 'processing', 'shipped', 1, '2026-03-14 04:48:00'),
(60, 15, 'shipped', 'delivered', 1, '2026-03-16 16:48:00'),
(61, 16, NULL, 'pending', 7, '2026-03-14 06:00:00'),
(62, 16, 'pending', 'processing', 1, '2026-03-14 10:00:00'),
(63, 16, 'processing', 'shipped', 1, '2026-03-15 10:00:00'),
(64, 16, 'shipped', 'delivered', 1, '2026-03-17 15:00:00'),
(65, 17, NULL, 'pending', 2, '2026-04-17 09:40:00'),
(66, 17, 'pending', 'processing', 1, '2026-04-18 02:40:00'),
(67, 17, 'processing', 'shipped', 1, '2026-04-19 06:40:00'),
(68, 17, 'shipped', 'delivered', 1, '2026-04-21 03:40:00'),
(69, 18, NULL, 'pending', 6, '2026-04-20 14:55:00'),
(70, 18, 'pending', 'processing', 1, '2026-04-21 06:55:00'),
(71, 18, 'processing', 'shipped', 1, '2026-04-22 06:55:00'),
(72, 18, 'shipped', 'delivered', 1, '2026-04-24 12:55:00'),
(73, 19, NULL, 'pending', 4, '2026-04-28 08:55:00'),
(74, 19, 'pending', 'processing', 1, '2026-04-28 18:55:00'),
(75, 19, 'processing', 'shipped', 1, '2026-04-29 12:55:00'),
(76, 19, 'shipped', 'delivered', 1, '2026-05-01 12:55:00'),
(77, 20, NULL, 'pending', 6, '2026-05-09 06:00:00'),
(78, 20, 'pending', 'processing', 1, '2026-05-09 10:00:00'),
(79, 20, 'processing', 'shipped', 1, '2026-05-10 07:00:00'),
(80, 20, 'shipped', 'delivered', 1, '2026-05-12 06:00:00'),
(81, 21, NULL, 'pending', 4, '2026-05-13 12:55:00'),
(82, 21, 'pending', 'processing', 1, '2026-05-14 01:55:00'),
(83, 21, 'processing', 'shipped', 1, '2026-05-14 17:55:00'),
(84, 21, 'shipped', 'delivered', 1, '2026-05-16 10:55:00'),
(85, 22, NULL, 'pending', 3, '2026-05-21 01:31:00'),
(86, 22, 'pending', 'processing', 1, '2026-05-21 12:31:00'),
(87, 22, 'processing', 'shipped', 1, '2026-05-22 13:31:00'),
(88, 22, 'shipped', 'delivered', 1, '2026-05-24 04:31:00'),
(89, 23, NULL, 'pending', 8, '2026-06-04 10:15:00'),
(90, 23, 'pending', 'processing', 1, '2026-06-05 04:15:00'),
(91, 23, 'processing', 'shipped', 1, '2026-06-06 07:15:00'),
(92, 23, 'shipped', 'delivered', 1, '2026-06-08 09:15:00'),
(93, 24, NULL, 'pending', 2, '2026-06-07 13:07:00'),
(94, 24, 'pending', 'processing', 1, '2026-06-08 09:07:00'),
(95, 24, 'processing', 'shipped', 1, '2026-06-09 04:07:00'),
(96, 24, 'shipped', 'delivered', 1, '2026-06-10 13:07:00'),
(97, 25, NULL, 'pending', 9, '2026-06-22 03:40:00'),
(98, 25, 'pending', 'processing', 1, '2026-06-22 07:40:00'),
(99, 25, 'processing', 'shipped', 1, '2026-06-23 07:40:00'),
(100, 25, 'shipped', 'delivered', 1, '2026-06-25 08:40:00'),
(101, 26, NULL, 'pending', 6, '2026-07-10 07:15:00'),
(102, 26, 'pending', 'processing', 1, '2026-07-11 00:15:00'),
(103, 26, 'processing', 'shipped', 1, '2026-07-11 16:15:00'),
(104, 26, 'shipped', 'delivered', 1, '2026-07-13 10:15:00'),
(105, 27, NULL, 'pending', 6, '2026-07-11 01:00:00'),
(106, 27, 'pending', 'processing', 1, '2026-07-11 04:00:00'),
(107, 27, 'processing', 'shipped', 1, '2026-07-12 10:00:00'),
(108, 27, 'shipped', 'delivered', 1, '2026-07-14 12:00:00'),
(109, 28, NULL, 'pending', 3, '2026-07-24 13:55:00'),
(110, 28, 'pending', 'processing', 1, '2026-07-25 07:55:00'),
(111, 28, 'processing', 'shipped', 1, '2026-07-26 12:55:00'),
(112, 28, 'shipped', 'delivered', 1, '2026-07-27 20:55:00'),
(113, 29, NULL, 'pending', 4, '2026-08-01 02:15:00'),
(114, 29, 'pending', 'processing', 1, '2026-08-01 05:15:00'),
(115, 29, 'processing', 'shipped', 1, '2026-08-02 04:15:00'),
(116, 29, 'shipped', 'delivered', 1, '2026-08-03 13:15:00'),
(117, 30, NULL, 'pending', 9, '2026-08-09 12:00:00'),
(118, 30, 'pending', 'processing', 1, '2026-08-09 17:00:00'),
(119, 30, 'processing', 'shipped', 1, '2026-08-10 14:00:00'),
(120, 30, 'shipped', 'delivered', 1, '2026-08-12 01:00:00'),
(121, 31, NULL, 'pending', 2, '2026-08-23 02:00:00'),
(122, 31, 'pending', 'processing', 1, '2026-08-23 06:00:00'),
(123, 31, 'processing', 'shipped', 1, '2026-08-24 07:00:00'),
(124, 31, 'shipped', 'delivered', 1, '2026-08-26 08:00:00'),
(125, 32, NULL, 'pending', 9, '2026-09-08 14:31:00'),
(126, 32, 'pending', 'processing', 1, '2026-09-09 00:31:00'),
(127, 32, 'processing', 'shipped', 1, '2026-09-09 20:31:00'),
(128, 32, 'shipped', 'delivered', 1, '2026-09-11 11:31:00'),
(129, 33, NULL, 'pending', 8, '2026-09-09 01:40:00'),
(130, 33, 'pending', 'processing', 1, '2026-09-09 06:40:00'),
(131, 33, 'processing', 'shipped', 1, '2026-09-10 12:40:00'),
(132, 33, 'shipped', 'delivered', 1, '2026-09-12 04:40:00'),
(133, 34, NULL, 'pending', 3, '2026-09-13 09:00:00'),
(134, 34, 'pending', 'processing', 1, '2026-09-13 20:00:00'),
(135, 34, 'processing', 'shipped', 1, '2026-09-15 02:00:00'),
(136, 34, 'shipped', 'delivered', 1, '2026-09-16 10:00:00'),
(137, 35, NULL, 'pending', 2, '2026-09-24 03:07:00'),
(138, 35, 'pending', 'processing', 1, '2026-09-24 06:07:00'),
(139, 35, 'processing', 'shipped', 1, '2026-09-25 04:07:00'),
(140, 35, 'shipped', 'delivered', 1, '2026-09-26 10:07:00'),
(141, 36, NULL, 'pending', 4, '2026-10-01 09:00:00'),
(142, 36, 'pending', 'processing', 1, '2026-10-01 17:00:00'),
(143, 36, 'processing', 'shipped', 1, '2026-10-02 20:00:00'),
(144, 36, 'shipped', 'delivered', 1, '2026-10-04 02:00:00'),
(145, 37, NULL, 'pending', 5, '2026-10-02 08:48:00'),
(146, 37, 'pending', 'processing', 1, '2026-10-02 16:48:00'),
(147, 37, 'processing', 'shipped', 1, '2026-10-03 16:48:00'),
(148, 37, 'shipped', 'delivered', 1, '2026-10-05 13:48:00'),
(149, 38, NULL, 'pending', 4, '2026-10-06 11:31:00'),
(150, 38, 'pending', 'processing', 1, '2026-10-07 00:31:00'),
(151, 38, 'processing', 'shipped', 1, '2026-10-08 02:31:00'),
(152, 38, 'shipped', 'delivered', 1, '2026-10-10 11:31:00'),
(153, 39, NULL, 'pending', 4, '2026-10-07 09:48:00'),
(154, 39, 'pending', 'processing', 1, '2026-10-08 05:48:00'),
(155, 39, 'processing', 'shipped', 1, '2026-10-09 03:48:00'),
(156, 39, 'shipped', 'delivered', 1, '2026-10-10 21:48:00'),
(157, 40, NULL, 'pending', 4, '2026-10-12 09:40:00'),
(158, 40, 'pending', 'processing', 1, '2026-10-12 12:40:00'),
(159, 40, 'processing', 'shipped', 1, '2026-10-13 05:40:00'),
(160, 40, 'shipped', 'delivered', 1, '2026-10-15 01:40:00'),
(161, 41, NULL, 'pending', 8, '2026-11-02 02:40:00'),
(162, 41, 'pending', 'processing', 1, '2026-11-02 15:40:00'),
(163, 41, 'processing', 'shipped', 1, '2026-11-03 09:40:00'),
(164, 41, 'shipped', 'delivered', 1, '2026-11-05 00:40:00'),
(165, 42, NULL, 'pending', 3, '2026-11-09 07:55:00'),
(166, 42, 'pending', 'processing', 1, '2026-11-09 19:55:00'),
(167, 42, 'processing', 'shipped', 1, '2026-11-10 20:55:00'),
(168, 43, NULL, 'pending', 6, '2026-11-11 04:40:00'),
(169, 43, 'pending', 'processing', 1, '2026-11-11 10:40:00'),
(170, 43, 'processing', 'shipped', 1, '2026-11-12 10:40:00'),
(171, 43, 'shipped', 'delivered', 1, '2026-11-14 18:40:00'),
(172, 44, NULL, 'pending', 8, '2026-11-14 03:00:00'),
(173, 44, 'pending', 'processing', 1, '2026-11-14 09:00:00'),
(174, 45, NULL, 'pending', 8, '2026-11-15 14:40:00'),
(175, 46, NULL, 'pending', 3, '2026-11-16 08:48:00'),
(176, 46, 'pending', 'processing', 1, '2026-11-16 18:48:00'),
(177, 47, NULL, 'pending', 3, '2026-11-17 05:40:00'),
(178, 48, NULL, 'pending', 13, '2026-11-22 19:50:06'),
(179, 49, NULL, 'pending', 3, '2026-11-26 08:52:44'),
(180, 49, 'pending', 'processing', 1, '2026-11-26 08:57:57'),
(181, 49, 'processing', 'shipped', 1, '2026-11-26 08:57:58'),
(182, 49, 'shipped', 'delivered', 1, '2026-11-26 08:57:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(190) NOT NULL,
  `slug` varchar(210) NOT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(30) NOT NULL DEFAULT 'gói',
  `price` int(10) UNSIGNED NOT NULL,
  `stock_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `rating_avg` decimal(3,2) NOT NULL DEFAULT 0.00,
  `rating_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `slug`, `description`, `unit`, `price`, `stock_quantity`, `image_path`, `is_featured`, `is_active`, `rating_avg`, `rating_count`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Bột Mì Đa Dụng (All-purpose Flour)', 'bot-mi-da-dung', 'Bột mì đa dụng phù hợp làm bánh quy, bánh bông lan và bánh muffin. Độ đạm vừa phải, dễ sử dụng cho người mới.', 'gói 1kg', 28000, 180, 'uploads/products/bot-mi-da-dung.jpg', 1, 1, 0.00, 0, '2025-11-05 02:00:00', '2025-11-05 02:00:00'),
(2, 1, 1, 'Bột Mì Số 11 (Bread Flour)', 'bot-mi-so-11', 'Bột mì hàm lượng protein cao, chuyên làm bánh mì, pizza và bánh bao cho độ dai ngon.', 'gói 1kg', 32000, 140, 'uploads/products/bot-mi-so-11.jpg', 1, 1, 4.00, 1, '2025-11-05 02:00:00', '2025-11-05 02:00:00'),
(3, 1, 1, 'Bột Mì Số 8 (Cake Flour)', 'bot-mi-so-8', 'Bột mì đạm thấp, mịn nhẹ, cho ruột bánh bông lan và chiffon mềm xốp.', 'gói 1kg', 34000, 95, 'uploads/products/ca309d047c4a32e8c2a68ee3cadab308.jpg', 0, 1, 5.00, 1, '2025-11-08 02:00:00', '2026-11-26 07:05:22'),
(4, 1, 4, 'Bột Bắp Biên Hòa', 'bot-bap-bien-hoa', 'Bột bắp dùng làm đặc sốt, làm mềm bánh quy và pha bột chiên.', 'hộp 400g', 18000, 150, 'uploads/products/16227f73e35ff90e567b2867be6f7438.jpg', 0, 1, 4.00, 1, '2025-11-08 02:00:00', '2026-11-26 11:07:08'),
(5, 1, NULL, 'Yến Mạch Cán Dẹt', 'yen-mach-can-det', 'Yến mạch nguyên hạt cán dẹt, dùng làm cookie, granola và bánh ăn kiêng.', 'gói 500g', 45000, 70, 'uploads/products/yen-mach-can-det.jpg', 0, 1, 5.00, 1, '2025-11-10 02:00:00', '2025-11-10 02:00:00'),
(6, 1, NULL, 'Bột Gạo Nếp', 'bot-gao-nep', 'Bột gạo nếp xay mịn, làm bánh dẻo, bánh trôi, mochi và bánh giầy.', 'gói 400g', 19000, 85, 'uploads/products/bot-gao-nep.jpg', 0, 1, 5.00, 1, '2025-11-12 02:00:00', '2025-11-12 02:00:00'),
(7, 1, NULL, 'Bột Năng (Tapioca Starch)', 'bot-nang', 'Bột năng tinh khiết, tạo độ trong và dai cho bánh bột lọc, trân châu, bánh flan.', 'gói 400g', 16000, 110, 'uploads/products/e2be25f91cc6766fd92fdf6327589463.webp', 0, 1, 4.33, 3, '2025-11-12 02:00:00', '2026-11-26 07:04:50'),
(8, 1, NULL, 'Bột Hạnh Nhân', 'bot-hanh-nhan', 'Bột hạnh nhân xay mịn, nguyên liệu chính của macaron, financier và bánh không gluten.', 'gói 200g', 98000, 38, 'uploads/products/0e69c910289d2a49366c52d0d2e7db80.jpg', 1, 1, 3.00, 2, '2025-11-15 02:00:00', '2026-11-26 07:04:26'),
(9, 1, 1, 'Bột Mì Nguyên Cám (Whole Wheat)', 'bot-mi-nguyen-cam', 'Bột mì nguyên cám giàu chất xơ, thích hợp làm bánh mì đen và bánh quy ăn kiêng.', 'gói 1kg', 42000, 2, 'uploads/products/bot-mi-nguyen-cam.jpg', 0, 1, 4.75, 4, '2026-01-10 02:00:00', '2026-11-26 09:00:07'),
(10, 1, NULL, 'Granola Trộn Hạt Và Mật Ong', 'granola-mix', 'Granola giòn nướng với yến mạch, hạt và mật ong, ăn kèm sữa chua hoặc rắc lên bánh.', 'gói 400g', 85000, 42, 'uploads/products/granola-mix.jpg', 0, 1, 5.00, 1, '2026-02-03 02:00:00', '2026-02-03 02:00:00'),
(11, 2, 6, 'Đường Cát Trắng', 'duong-cat-trang', 'Đường cát trắng tinh luyện, hạt mịn dễ tan.', 'gói 1kg', 24000, 220, 'uploads/products/duong-cat-trang.jpg', 0, 1, 3.00, 1, '2025-11-05 02:00:00', '2025-11-05 02:00:00'),
(12, 2, 6, 'Đường Nâu Muscovado', 'duong-nau-muscovado', 'Đường nâu giàu mật mía, hương caramel đậm, cho cookie và bánh gừng thơm hơn.', 'gói 500g', 38000, 100, 'uploads/products/duong-nau-muscovado.jpg', 0, 1, 0.00, 0, '2025-11-06 02:00:00', '2025-11-06 02:00:00'),
(13, 2, 6, 'Đường Bột (Icing Sugar)', 'duong-bot', 'Đường xay mịn có pha bột bắp, dùng làm kem bơ, glaze và phủ mặt bánh.', 'gói 500g', 22000, 130, 'uploads/products/f21ebd64c48906006a538e2d7336c568.jpg', 0, 1, 0.00, 0, '2025-11-06 02:00:00', '2026-11-26 07:04:01'),
(14, 2, 6, 'Đường Caster Hạt Mịn', 'duong-caster', 'Đường hạt siêu mịn tan nhanh, lý tưởng để đánh bông trứng và làm meringue.', 'gói 500g', 26000, 90, 'uploads/products/169d92bbbc6a0d136be34f98aa0e714b.jpg', 0, 1, 5.00, 2, '2025-11-10 02:00:00', '2026-11-26 06:41:08'),
(15, 2, NULL, 'Mật Ong Hoa Nhãn', 'mat-ong-hoa-nhan', 'Mật ong nguyên chất, dùng làm bánh, ướp thịt và pha đồ uống.', 'chai 500ml', 120000, 40, 'uploads/products/mat-ong-hoa-nhan.jpg', 0, 1, 4.00, 1, '2025-11-12 02:00:00', '2026-11-26 09:40:32'),
(16, 2, NULL, 'Siro Phong (Maple Syrup)', 'siro-phong', 'Siro cây phong nguyên chất nhập khẩu Canada, rưới pancake, waffle và làm bánh.', 'chai 250ml', 165000, 0, 'uploads/products/siro-phong.jpg', 0, 1, 3.00, 1, '2026-02-14 02:00:00', '2026-02-14 02:00:00'),
(17, 2, NULL, 'Mạch Nha (Malt Syrup)', 'mach-nha', 'Mạch nha đặc, giúp bánh mì có màu đẹp và vỏ bóng, bánh quy giữ ẩm lâu.', 'lọ 400g', 48000, 24, 'uploads/products/mach-nha.jpg', 0, 1, 3.50, 2, '2026-03-01 02:00:00', '2026-03-01 02:00:00'),
(18, 2, NULL, 'Siro Đường Vàng (Golden Syrup)', 'golden-syrup', 'Siro đường vàng sánh mịn, vị caramel nhẹ, dùng cho bánh flapjack, bánh gừng và kẹo.', 'chai 454g', 79000, 0, 'uploads/products/5a57080098940fcfd491db6b46813004.jpg', 0, 1, 4.50, 2, '2026-04-02 02:00:00', '2026-11-26 11:06:06'),
(19, 3, 2, 'Bơ Lạt Anchor', 'bo-lat-anchor', 'Bơ lạt nhập khẩu New Zealand, béo thơm tự nhiên, làm bánh quy và kem bơ.', 'cục 227g', 65000, 120, 'uploads/products/bo-lat-anchor.jpg', 1, 1, 5.00, 1, '2025-11-05 02:00:00', '2025-11-05 02:00:00'),
(20, 3, 2, 'Bơ Mặn Anchor', 'bo-man-anchor', 'Bơ mặn dùng phết bánh mì, nấu ăn và làm bánh quy bơ mặn.', 'cục 227g', 66000, 70, 'uploads/products/bo-man-anchor.jpg', 0, 1, 0.00, 0, '2025-11-06 02:00:00', '2025-11-06 02:00:00'),
(21, 3, 13, 'Bơ Lạt Président', 'bo-lat-president', 'Bơ lạt kiểu Pháp 82% béo, cho croissant và bánh puff nhiều lớp thơm ngậy.', 'cục 200g', 78000, 55, 'uploads/products/482702528b76323e45352d947de993f9.jpg', 1, 1, 4.33, 3, '2025-12-02 02:00:00', '2026-11-26 07:03:12'),
(22, 3, 3, 'Dầu Ăn Thực Vật Tường An', 'dau-an-tuong-an', 'Dầu ăn tinh luyện, không mùi, dùng cho bánh bông lan, chiffon và cupcake.', 'chai 1L', 52000, 80, 'uploads/products/cb392e1004f310a8714542950f866a8a.webp', 0, 1, 5.00, 1, '2025-11-06 02:00:00', '2026-11-26 07:03:32'),
(23, 3, NULL, 'Bơ Thực Vật Margarine', 'margarine', 'Margarine mềm dẻo, giá tốt, phù hợp làm bánh mì ngọt, bánh quy và phủ khuôn.', 'hộp 500g', 58000, 48, 'uploads/products/margarine.jpg', 0, 1, 4.50, 2, '2025-12-05 02:00:00', '2025-12-05 02:00:00'),
(24, 3, NULL, 'Shortening (Mỡ Thực Vật Làm Bánh)', 'shortening', 'Shortening trắng không mùi, cho bánh quy xốp, vỏ bánh pie giòn và kem bơ bền dáng.', 'hộp 1kg', 95000, 22, 'uploads/products/shortening.jpg', 0, 1, 4.00, 1, '2026-01-15 02:00:00', '2026-01-15 02:00:00'),
(25, 3, NULL, 'Dầu Dừa Nguyên Chất', 'dau-dua', 'Dầu dừa ép lạnh thơm nhẹ, thay thế bơ trong bánh thuần chay và phủ socola.', 'chai 500ml', 85000, 31, 'uploads/products/dau-dua.jpg', 0, 1, 0.00, 0, '2026-02-20 02:00:00', '2026-02-20 02:00:00'),
(26, 4, 5, 'Khuôn Bánh Tròn Wilton 20cm', 'khuon-banh-tron-wilton', 'Khuôn nhôm chống dính, đường kính 20cm, cao 7cm, dùng cho bánh bông lan và bánh kem.', 'cái', 145000, 25, 'uploads/products/2b2f2e8d3e38ebb1cc22d2932de781bb.webp', 1, 1, 3.67, 3, '2025-11-07 02:00:00', '2026-11-26 07:02:39'),
(27, 4, NULL, 'Cân Điện Tử Nhà Bếp 5kg', 'can-dien-tu-5kg', 'Cân điện tử độ chính xác 1g, tối đa 5kg, có chức năng trừ bì.', 'cái', 175000, 30, 'uploads/products/can-dien-tu-5kg.jpg', 1, 1, 5.00, 1, '2025-11-07 02:00:00', '2025-11-07 02:00:00'),
(28, 4, 5, 'Phới Đánh Trứng Inox', 'phoi-danh-trung-inox', 'Phới lồng inox 304 cán dài, bền chắc, đánh trứng và kem tươi nhanh.', 'cái', 55000, 60, 'uploads/products/phoi-danh-trung-inox.jpg', 0, 1, 0.00, 0, '2025-11-08 02:00:00', '2025-11-08 02:00:00'),
(29, 4, NULL, 'Giấy Nến Nướng Bánh', 'giay-nen-nuong-banh', 'Giấy nến chống dính, chịu nhiệt đến 220°C, lót khay nướng và khuôn.', 'cuộn 5m', 32000, 100, 'uploads/products/giay-nen-nuong-banh.jpg', 0, 1, 5.00, 1, '2025-11-08 02:00:00', '2025-11-08 02:00:00'),
(30, 4, NULL, 'Cán Bột Gỗ', 'can-bot-go', 'Cán bột bằng gỗ nguyên khối, trơn láng, cán đều bột bánh quy và bánh mì.', 'cái', 68000, 40, 'uploads/products/can-bot-go.jpg', 0, 1, 0.00, 0, '2025-11-10 02:00:00', '2025-11-10 02:00:00'),
(31, 4, NULL, 'Rây Bột Cầm Tay', 'ray-bot-cam-tay', 'Rây inox mịn, lọc bột và đường tơi xốp, loại bỏ vón cục.', 'cái', 48000, 55, 'uploads/products/ray-bot-cam-tay.jpg', 0, 1, 0.00, 0, '2025-11-10 02:00:00', '2025-11-10 02:00:00'),
(32, 4, NULL, 'Cọ Phết Bơ Silicon', 'co-phet-bo-silicon', 'Cọ silicon mềm, chịu nhiệt, phết bơ, trứng và siro lên bánh.', 'cái', 29000, 90, 'uploads/products/co-phet-bo-silicon.jpg', 0, 1, 2.50, 2, '2025-11-12 02:00:00', '2025-11-12 02:00:00'),
(33, 4, 5, 'Khuôn Cupcake 12 Ly Wilton', 'khuon-cupcake-12-ly', 'Khay 12 ly cupcake/muffin chống dính, dùng được trong lò nướng đến 230°C.', 'cái', 135000, 28, 'uploads/products/khuon-cupcake-12-ly.jpg', 0, 1, 5.00, 2, '2025-12-01 02:00:00', '2025-12-01 02:00:00'),
(34, 4, NULL, 'Phới Dẹt Silicon (Spatula)', 'spatula-silicon', 'Spatula silicon chịu nhiệt, vét sạch tô, trộn bột nhẹ tay.', 'cái', 42000, 75, 'uploads/products/cdee5df693f6a6b4753903881dc1b218.webp', 0, 1, 5.00, 1, '2025-12-01 02:00:00', '2026-11-26 11:05:15'),
(35, 4, NULL, 'Máy Đánh Trứng Cầm Tay 5 Tốc Độ', 'may-danh-trung-cam-tay', 'Máy đánh trứng cầm tay công suất 300W, 5 tốc độ, kèm 2 que đánh và 2 que nhồi bột.', 'cái', 420000, 14, 'uploads/products/may-danh-trung-cam-tay.jpg', 1, 1, 4.00, 1, '2025-12-10 02:00:00', '2025-12-10 02:00:00'),
(36, 4, NULL, 'Khuôn Bánh Mì Hình Chữ Nhật', 'khuon-banh-mi-hop', 'Khuôn nướng bánh mì sandwich thép carbon phủ chống dính, kích thước 25x12cm.', 'cái', 98000, 33, 'uploads/products/khuon-banh-mi-hop.jpg', 0, 1, 4.00, 2, '2026-01-05 02:00:00', '2026-01-05 02:00:00'),
(37, 4, 5, 'Bộ Túi Bắt Kem Và Đui Inox 12 Món Wilton', 'tui-bat-kem-bo-12', 'Bộ 8 đui inox, 2 đầu nối, 2 túi bắt kem tái sử dụng để trang trí bánh kem, cupcake.', 'bộ', 185000, 20, 'uploads/products/f1453246ef7c6343c1b9d80405aeb514.jpg', 0, 1, 5.00, 3, '2026-01-20 02:00:00', '2026-11-26 07:02:17'),
(38, 4, NULL, 'Nhiệt Kế Lò Nướng', 'nhiet-ke-lo-nuong', 'Nhiệt kế treo lò, thang đo 0-300°C, giúp kiểm soát nhiệt độ nướng chính xác.', 'cái', 89000, 4, 'uploads/products/nhiet-ke-lo-nuong.jpg', 0, 1, 5.00, 1, '2026-03-10 02:00:00', '2026-03-10 02:00:00'),
(39, 4, NULL, 'Tô Trộn Inox 24cm', 'to-tron-inox-24', 'Tô inox đáy chống trượt, dùng trộn bột, đánh trứng và ủ bột bánh mì.', 'cái', 85000, 45, 'uploads/products/to-tron-inox-24.jpg', 0, 1, 4.00, 1, '2026-04-12 02:00:00', '2026-04-12 02:00:00'),
(40, 5, 7, 'Tinh Chất Vani Nguyên Chất', 'tinh-chat-vani', 'Vani nguyên chất từ Madagascar, hương thơm sâu, dùng cho kem, custard và bánh.', 'chai 60ml', 220000, 20, 'uploads/products/a2de8c42d7ef2cf74786c42650c73b9d.webp', 1, 1, 3.00, 1, '2025-11-15 02:00:00', '2026-11-26 10:45:12'),
(41, 5, NULL, 'Bột Cacao Nguyên Chất', 'bot-cacao-nguyen-chat', 'Bột cacao không đường, vị đắng đậm đà, làm bánh brownie, mousse và đồ uống.', 'gói 250g', 75000, 55, 'uploads/products/bot-cacao-nguyen-chat.jpg', 0, 1, 2.67, 3, '2025-11-15 02:00:00', '2025-11-15 02:00:00'),
(42, 5, NULL, 'Bột Trà Xanh Matcha', 'bot-tra-xanh-matcha', 'Bột matcha Nhật Bản, màu xanh tươi, dùng làm bánh, kem và đồ uống.', 'gói 100g', 150000, 45, 'uploads/products/bot-tra-xanh-matcha.jpg', 1, 1, 0.00, 0, '2025-11-20 02:00:00', '2025-11-20 02:00:00'),
(43, 5, 5, 'Màu Thực Phẩm Dạng Gel Wilton (Bộ 4 Màu)', 'mau-thuc-pham-gel', 'Bộ 4 màu gel đậm đặc (đỏ, vàng, xanh dương, xanh lá), chỉ cần vài giọt, không làm loãng kem.', 'bộ', 120000, 26, 'uploads/products/mau-thuc-pham-gel.jpg', 0, 1, 0.00, 0, '2025-12-08 02:00:00', '2025-12-08 02:00:00'),
(44, 5, NULL, 'Bột Quế', 'bot-que', 'Bột quế Việt Nam thơm nồng, dùng cho bánh táo, cinnamon roll và đồ uống.', 'gói 50g', 38000, 65, 'uploads/products/bot-que.jpg', 0, 1, 0.00, 0, '2025-12-15 02:00:00', '2025-12-15 02:00:00'),
(45, 5, 9, 'Đường Vani Dr. Oetker', 'duong-vani-dr-oetker', 'Đường vani gói nhỏ tiện dụng, tạo hương vani nhẹ cho bánh quy, pancake và custard.', 'hộp 3 gói', 28000, 90, 'uploads/products/d9d357fd29383c6efff8d1db1db3d888.jpg', 0, 1, 0.00, 0, '2026-02-08 02:00:00', '2026-11-26 07:01:49'),
(46, 5, NULL, 'Rượu Rum Làm Bánh', 'ruou-rum-lam-banh', 'Rượu rum nâu dùng ngâm trái cây khô và làm bánh trái cây, tiramisu. Sản phẩm đã ngừng nhập.', 'chai 100ml', 72000, 8, 'uploads/products/c0d4c0323243455aad87ed9027ca62ce.jpg', 0, 0, 0.00, 0, '2025-12-20 02:00:00', '2026-11-26 07:01:17'),
(47, 6, 2, 'Kem Tươi Whipping Cream Anchor', 'kem-tuoi-whipping-cream', 'Whipping cream 35% béo, đánh bông ổn định, làm kem phủ bánh và mousse.', 'hộp 1L', 135000, 35, 'uploads/products/kem-tuoi-whipping-cream.jpg', 1, 1, 4.67, 3, '2025-11-15 02:00:00', '2025-11-15 02:00:00'),
(48, 6, 8, 'Sữa Tươi Tiệt Trùng Không Đường Vinamilk', 'sua-tuoi-vinamilk', 'Sữa tươi 100% không đường, dùng làm bánh, pudding và pha chế.', 'hộp 1L', 33000, 160, 'uploads/products/65c16b11124adb4cc2d7972857aac8b6.png', 0, 1, 4.00, 1, '2025-11-15 02:00:00', '2026-11-26 07:00:41'),
(49, 6, 8, 'Sữa Đặc Có Đường Ông Thọ', 'sua-dac-ong-tho', 'Sữa đặc có đường, vị béo ngọt, dùng làm bánh flan, bánh bông lan sữa và cà phê.', 'lon 380g', 24000, 120, 'uploads/products/1eae17ac1ebcac1693698beee77b2635.jpg', 0, 1, 5.00, 2, '2025-11-20 02:00:00', '2026-11-26 07:00:13'),
(50, 6, 12, 'Phô Mai Kem Philadelphia', 'phomai-kem-philadelphia', 'Cream cheese mịn béo, dùng cho cheesecake, frosting và bánh cuộn.', 'hộp 200g', 79000, 52, 'uploads/products/phomai-kem-philadelphia.jpg', 1, 1, 4.00, 2, '2025-12-02 02:00:00', '2025-12-02 02:00:00'),
(51, 6, NULL, 'Phô Mai Mascarpone', 'phomai-mascarpone', 'Mascarpone Ý béo mịn, nguyên liệu chính của tiramisu và các loại kem mousse.', 'hộp 250g', 95000, 0, 'uploads/products/phomai-mascarpone.jpg', 0, 1, 0.00, 0, '2026-01-12 02:00:00', '2026-01-12 02:00:00'),
(52, 6, NULL, 'Trứng Gà Ta', 'trung-ga-ta', 'Trứng gà ta tuyển chọn, lòng đỏ đậm, làm bánh bông lan, custard và bánh flan.', 'vỉ 10 quả', 38000, 75, 'uploads/products/trung-ga-ta.jpg', 0, 1, 4.00, 1, '2025-11-15 02:00:00', '2025-11-15 02:00:00'),
(53, 7, NULL, 'Socola Chip Bán Ngọt', 'socola-chip-ban-ngot', 'Socola viên nhỏ chịu nhiệt, rắc cookie, muffin và brownie.', 'gói 250g', 68000, 80, 'uploads/products/ab346ade57144545230f54c37e432553.jpg', 0, 1, 4.33, 3, '2025-11-18 02:00:00', '2026-11-26 06:59:32'),
(54, 7, 10, 'Socola Đen Callebaut 54% (Callets)', 'socola-den-callebaut', 'Socola đen Bỉ dạng viên dễ tan chảy, dùng làm ganache, mousse và phủ bánh.', 'gói 400g', 195000, 22, 'uploads/products/socola-den-callebaut.jpg', 1, 1, 5.00, 1, '2025-12-03 02:00:00', '2025-12-03 02:00:00'),
(55, 7, 10, 'Socola Trắng Callebaut (Callets)', 'socola-trang-callebaut', 'Socola trắng Bỉ béo ngọt, dùng làm ganache trắng, mousse và trang trí.', 'gói 400g', 195000, 0, 'uploads/products/55f661f229f664dba9dfb3d290d0c398.jpg', 0, 1, 0.00, 0, '2025-12-03 02:00:00', '2026-11-26 06:58:24'),
(56, 7, NULL, 'Hạnh Nhân Lát', 'hanh-nhan-lat', 'Hạnh nhân cắt lát mỏng, trang trí và tăng độ giòn cho bánh.', 'gói 200g', 89000, 50, 'uploads/products/45784238df52ce680978ab5097a29b12.jpg', 0, 1, 3.67, 3, '2025-11-18 02:00:00', '2026-11-26 06:58:01'),
(57, 7, NULL, 'Óc Chó (Walnut) Nhập Khẩu', 'oc-cho', 'Nhân óc chó giòn bùi, làm brownie, bánh chuối và bánh mì hạt.', 'gói 200g', 98000, 36, 'uploads/products/3974052113542a77111d75879b150f6d.webp', 0, 1, 5.00, 2, '2025-12-10 02:00:00', '2026-11-26 06:57:33'),
(58, 7, NULL, 'Nho Khô Không Hạt', 'nho-kho', 'Nho khô mềm ngọt, dùng cho bánh mì nho, cookie yến mạch và bánh trái cây.', 'gói 250g', 55000, 64, 'uploads/products/nho-kho.jpg', 0, 1, 0.00, 0, '2025-12-12 02:00:00', '2025-12-12 02:00:00'),
(59, 7, NULL, 'Dừa Sấy Sợi', 'dua-say-soi', 'Dừa nạo sấy khô giòn thơm, dùng làm bánh dừa, cookie và trang trí.', 'gói 200g', 42000, 58, 'uploads/products/9d0edb7441f467133f638b8b717f648e.jpg', 0, 1, 0.00, 0, '2026-01-08 02:00:00', '2026-11-26 11:08:02'),
(60, 7, NULL, 'Nam Việt Quất Sấy', 'nam-viet-quat-say', 'Cranberry sấy chua ngọt, dùng cho cookie, scone và bánh mì hạt.', 'gói 250g', 89000, 29, 'uploads/products/nam-viet-quat-say.jpg', 0, 1, 5.00, 1, '2026-03-18 02:00:00', '2026-03-18 02:00:00'),
(61, 8, 1, 'Bột Nở Baking Powder', 'bot-no-baking-powder', 'Bột nở không nhôm, giúp bánh nở xốp đều.', 'hộp 100g', 22000, 140, 'uploads/products/8823e8028bac7bdf85767f9578099d58.jpg', 0, 1, 5.00, 1, '2025-11-06 02:00:00', '2026-11-26 06:57:11'),
(62, 8, NULL, 'Baking Soda (Muối Nở)', 'baking-soda', 'Baking soda dùng cho bánh quy giòn, bánh gừng và pancake nở xốp.', 'gói 100g', 12000, 170, 'uploads/products/bd227362d171342237f47ee4cacb4901.jpg', 0, 1, 0.00, 0, '2025-11-06 02:00:00', '2026-11-26 06:56:29'),
(63, 8, 11, 'Men Khô Instant Mauri', 'men-kho-mauri', 'Men khô hoạt động nhanh, không cần ủ trước, làm bánh mì, pizza và bánh bao.', 'gói 100g', 45000, 88, 'uploads/products/c9e08dc6dbe3084e08591d8a9a092646.jpg', 1, 1, 2.00, 1, '2025-11-10 02:00:00', '2026-11-26 06:51:13'),
(64, 8, 15, 'Gelatin Lá', 'gelatin-la', 'Gelatin dạng lá trong, không mùi, dùng làm mousse, panna cotta và bánh lạnh.', 'hộp 10 lá', 38000, 47, 'uploads/products/98693642a644c3dbc4c8ef09dfe8fa1c.jpg', 0, 1, 0.00, 0, '2025-12-15 02:00:00', '2026-11-26 06:56:01'),
(65, 8, 14, 'Bột Rau Câu Dẻo AGAR', 'bot-rau-cau-deo', 'Bột rau câu dẻo (agar) tạo thạch trong, giòn dai cho rau câu và bánh trung thu.', 'gói 25g', 14000, 100, 'uploads/products/b75d647db3aff34fbda616fe89a42328.jpg', 0, 1, 4.00, 1, '2026-01-25 02:00:00', '2026-11-26 06:54:41'),
(66, 8, NULL, 'Kem Tartar (Cream of Tartar)', 'kem-tartar', 'Chất ổn định lòng trắng trứng, giúp meringue và bánh chiffon lên bông bền.', 'hũ 50g', 52000, 6, 'uploads/products/fc09ec677e1078cc111bd68d68391ed1.jpg', 0, 1, 0.00, 0, '2026-04-20 02:00:00', '2026-11-26 07:57:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `created_at`) VALUES
(1, 1, 'uploads/products/bot-mi-da-dung.jpg', 1, '2026-11-22 18:00:40'),
(2, 2, 'uploads/products/bot-mi-so-11.jpg', 1, '2026-11-22 18:00:40'),
(4, 5, 'uploads/products/yen-mach-can-det.jpg', 1, '2026-11-22 18:00:40'),
(5, 6, 'uploads/products/bot-gao-nep.jpg', 1, '2026-11-22 18:00:40'),
(6, 9, 'uploads/products/bot-mi-nguyen-cam.jpg', 1, '2026-11-22 18:00:40'),
(7, 10, 'uploads/products/granola-mix.jpg', 1, '2026-11-22 18:00:40'),
(8, 11, 'uploads/products/duong-cat-trang.jpg', 1, '2026-11-22 18:00:40'),
(9, 12, 'uploads/products/duong-nau-muscovado.jpg', 1, '2026-11-22 18:00:40'),
(10, 15, 'uploads/products/mat-ong-hoa-nhan.jpg', 1, '2026-11-22 18:00:40'),
(11, 16, 'uploads/products/siro-phong.jpg', 1, '2026-11-22 18:00:40'),
(12, 17, 'uploads/products/mach-nha.jpg', 1, '2026-11-22 18:00:40'),
(14, 19, 'uploads/products/bo-lat-anchor.jpg', 1, '2026-11-22 18:00:40'),
(15, 20, 'uploads/products/bo-man-anchor.jpg', 1, '2026-11-22 18:00:40'),
(16, 23, 'uploads/products/margarine.jpg', 1, '2026-11-22 18:00:40'),
(17, 24, 'uploads/products/shortening.jpg', 1, '2026-11-22 18:00:40'),
(18, 25, 'uploads/products/dau-dua.jpg', 1, '2026-11-22 18:00:40'),
(19, 27, 'uploads/products/can-dien-tu-5kg.jpg', 1, '2026-11-22 18:00:40'),
(20, 28, 'uploads/products/phoi-danh-trung-inox.jpg', 1, '2026-11-22 18:00:40'),
(21, 29, 'uploads/products/giay-nen-nuong-banh.jpg', 1, '2026-11-22 18:00:40'),
(22, 30, 'uploads/products/can-bot-go.jpg', 1, '2026-11-22 18:00:40'),
(23, 31, 'uploads/products/ray-bot-cam-tay.jpg', 1, '2026-11-22 18:00:40'),
(24, 32, 'uploads/products/co-phet-bo-silicon.jpg', 1, '2026-11-22 18:00:40'),
(25, 33, 'uploads/products/khuon-cupcake-12-ly.jpg', 1, '2026-11-22 18:00:40'),
(26, 35, 'uploads/products/may-danh-trung-cam-tay.jpg', 1, '2026-11-22 18:00:40'),
(27, 36, 'uploads/products/khuon-banh-mi-hop.jpg', 1, '2026-11-22 18:00:40'),
(28, 38, 'uploads/products/nhiet-ke-lo-nuong.jpg', 1, '2026-11-22 18:00:40'),
(29, 39, 'uploads/products/to-tron-inox-24.jpg', 1, '2026-11-22 18:00:40'),
(30, 40, 'uploads/products/tinh-chat-vani.jpg', 1, '2026-11-22 18:00:40'),
(31, 41, 'uploads/products/bot-cacao-nguyen-chat.jpg', 1, '2026-11-22 18:00:40'),
(32, 42, 'uploads/products/bot-tra-xanh-matcha.jpg', 1, '2026-11-22 18:00:40'),
(33, 43, 'uploads/products/mau-thuc-pham-gel.jpg', 1, '2026-11-22 18:00:40'),
(34, 44, 'uploads/products/bot-que.jpg', 1, '2026-11-22 18:00:40'),
(35, 47, 'uploads/products/kem-tuoi-whipping-cream.jpg', 1, '2026-11-22 18:00:40'),
(36, 50, 'uploads/products/phomai-kem-philadelphia.jpg', 1, '2026-11-22 18:00:40'),
(37, 51, 'uploads/products/phomai-mascarpone.jpg', 1, '2026-11-22 18:00:40'),
(38, 52, 'uploads/products/trung-ga-ta.jpg', 1, '2026-11-22 18:00:40'),
(40, 54, 'uploads/products/socola-den-callebaut.jpg', 1, '2026-11-22 18:00:40'),
(42, 58, 'uploads/products/nho-kho.jpg', 1, '2026-11-22 18:00:40'),
(44, 60, 'uploads/products/nam-viet-quat-say.jpg', 1, '2026-11-22 18:00:40'),
(48, 19, 'uploads/products/bo-lat-anchor-2.jpg', 0, '2026-11-22 18:00:40'),
(49, 29, 'uploads/products/giay-nen-nuong-banh-2.jpg', 0, '2026-11-22 18:00:40'),
(50, 53, 'uploads/products/socola-chip-ban-ngot-2.jpg', 0, '2026-11-22 18:00:40'),
(51, 14, 'uploads/products/7c60c9ef48025a89ed7af29dfdae1b2c.jpg', 0, '2026-11-26 06:41:08'),
(53, 14, 'uploads/products/788903736f2f860783cc15af7db73bd1.jpg', 0, '2026-11-26 06:41:08'),
(54, 14, 'uploads/products/cf35a75beac8efebf7c3da88c13df7ed.jpg', 0, '2026-11-26 06:41:34'),
(55, 63, 'uploads/products/fe03313b996b1736d68e522b83da16d3.jpg', 0, '2026-11-26 06:51:13'),
(56, 63, 'uploads/products/c0e3e17458f4f0bbdeec91a4563c904b.jpg', 0, '2026-11-26 06:51:13'),
(57, 63, 'uploads/products/0be87368e4c01d22d705655607c41a25.webp', 0, '2026-11-26 06:51:13'),
(58, 66, 'uploads/products/bb58d985cefe626f7710383581072311.jpg', 0, '2026-11-26 06:52:43'),
(59, 66, 'uploads/products/f1849ec7090949135986e9847de43bb7.jpg', 0, '2026-11-26 06:52:43'),
(60, 66, 'uploads/products/0644d70c0decccd25049c74f0e31cf04.jpg', 0, '2026-11-26 06:52:43'),
(61, 65, 'uploads/products/fc8614d21ec921329d0aff80421a0aa4.webp', 0, '2026-11-26 06:54:11'),
(62, 65, 'uploads/products/782a5b943c9dae1b2ca18315e20d56cc.jpg', 0, '2026-11-26 06:54:11'),
(63, 64, 'uploads/products/aab8774e67d82df604a38a01ab52e1c7.jpg', 0, '2026-11-26 06:55:32'),
(64, 64, 'uploads/products/80082918c61b3b65cf76b7389787843f.jpg', 0, '2026-11-26 06:55:32'),
(65, 64, 'uploads/products/1834bbabccd25e573ef022ca5329aa68.jpg', 0, '2026-11-26 06:55:32'),
(66, 18, 'uploads/products/0ff6b7bf856cc73f28c55b6df40ee023.jpg', 0, '2026-11-26 11:06:06'),
(67, 18, 'uploads/products/0ad20ef30d206aa63f24741c72e0088b.jpg', 0, '2026-11-26 11:06:06'),
(68, 59, 'uploads/products/3140a811f8d1dbf3a5be08f13f539b7d.jpg', 0, '2026-11-26 11:08:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0,
  `admin_reply` text DEFAULT NULL,
  `admin_replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `is_hidden`, `admin_reply`, `admin_replied_at`, `created_at`, `updated_at`) VALUES
(1, 2, 35, 4, 'Chất liệu tốt, dễ vệ sinh, đúng như mô tả.', 0, NULL, NULL, '2025-12-20 07:22:00', '2025-12-20 07:22:00'),
(2, 2, 61, 5, 'Đúng loại cần dùng, giao nhanh.', 0, NULL, NULL, '2025-12-22 09:22:00', '2025-12-22 09:22:00'),
(3, 2, 49, 5, 'Đánh bông rất bền, làm bánh kem ổn định.', 0, NULL, NULL, '2025-12-22 15:15:00', '2025-12-22 15:15:00'),
(4, 4, 19, 5, 'Béo thơm, làm bánh quy bơ rất đã.', 0, NULL, NULL, '2025-12-22 18:07:00', '2025-12-22 18:07:00'),
(5, 4, 8, 2, 'Chất lượng không như mong đợi, hơi thất vọng.', 0, 'BakeHouse xin lỗi vì trải nghiệm chưa tốt. Bạn vui lòng liên hệ hotline để được hỗ trợ đổi/trả nhé.', '2025-12-26 07:15:00', '2025-12-25 23:15:00', '2025-12-25 23:15:00'),
(6, 4, 49, 5, 'Vị béo mịn, rất hợp làm tráng miệng.', 0, NULL, NULL, '2025-12-27 08:15:00', '2025-12-27 08:15:00'),
(7, 4, 21, 5, 'Chất lượng tốt, đúng hàng nhập khẩu.', 0, NULL, NULL, '2025-12-27 14:07:00', '2025-12-27 14:07:00'),
(8, 4, 23, 5, 'Dùng làm kem bơ rất mịn, rất đáng mua.', 0, NULL, NULL, '2025-12-28 21:07:00', '2025-12-28 21:07:00'),
(9, 4, 6, 5, 'Chất lượng ổn định, mình mua lại nhiều lần.', 0, 'Cảm ơn bạn đã ủng hộ BakeHouse! Chúc bạn làm bánh thật thành công.', '2026-01-02 04:15:00', '2026-01-01 08:15:00', '2026-01-01 08:15:00'),
(10, 2, 32, 4, 'Mua về dùng thấy rất hài lòng, giá hợp lý.', 0, NULL, NULL, '2026-01-12 08:40:00', '2026-01-12 08:40:00'),
(11, 6, 15, 4, 'Đường tơi, dễ tan, vị ngọt thanh.', 0, NULL, NULL, '2026-01-14 21:31:00', '2026-01-14 21:31:00'),
(12, 3, 2, 4, 'Bột mịn, làm bánh rất xốp và lên đều.', 0, NULL, NULL, '2026-01-15 06:40:00', '2026-01-15 06:40:00'),
(13, 6, 63, 2, 'Đóng gói chưa kỹ, hộp bị móp khi nhận.', 0, 'BakeHouse xin lỗi vì trải nghiệm chưa tốt. Bạn vui lòng liên hệ hotline để được hỗ trợ đổi/trả nhé.', '2026-01-16 03:31:00', '2026-01-15 17:31:00', '2026-01-15 17:31:00'),
(14, 3, 14, 5, 'Đường tơi, dễ tan, vị ngọt thanh.', 0, 'Cảm ơn bạn đã ủng hộ BakeHouse! Chúc bạn làm bánh thật thành công.', '2026-01-17 18:40:00', '2026-01-17 05:40:00', '2026-01-17 05:40:00'),
(15, 6, 48, 4, 'Đánh bông rất bền, làm bánh kem ổn định.', 0, NULL, NULL, '2026-01-19 02:31:00', '2026-01-19 02:31:00'),
(16, 2, 56, 4, 'Hạt giòn, tươi, không bị ẩm.', 0, NULL, NULL, '2026-01-20 03:40:00', '2026-01-20 03:40:00'),
(17, 6, 11, 3, 'Tạm ổn, đúng mô tả nhưng giá hơi cao.', 0, NULL, NULL, '2026-01-21 19:31:00', '2026-01-21 19:31:00'),
(18, 3, 27, 5, 'Mua về dùng thấy rất hài lòng, giá hợp lý.', 0, 'Cảm ơn bạn đã ủng hộ BakeHouse! Chúc bạn làm bánh thật thành công.', '2026-02-07 09:31:00', '2026-02-06 22:31:00', '2026-02-06 22:31:00'),
(19, 3, 36, 5, 'Mua về dùng thấy rất hài lòng, giá hợp lý.', 0, NULL, NULL, '2026-02-08 01:31:00', '2026-02-08 01:31:00'),
(20, 3, 47, 5, 'Vị béo mịn, rất hợp làm tráng miệng.', 0, NULL, NULL, '2026-02-09 00:31:00', '2026-02-09 00:31:00'),
(21, 2, 37, 5, 'Mua về dùng thấy rất hài lòng, giá hợp lý.', 0, NULL, NULL, '2026-02-10 00:48:00', '2026-02-10 00:48:00'),
(22, 5, 10, 5, 'Bột mịn, làm bánh rất xốp và lên đều.', 0, NULL, NULL, '2026-02-14 10:40:00', '2026-02-14 10:40:00'),
(23, 5, 14, 5, 'Giá hợp lý, chất lượng tốt cho làm bánh.', 0, NULL, NULL, '2026-02-17 07:40:00', '2026-02-17 07:40:00'),
(24, 6, 29, 5, 'Dụng cụ chắc chắn, dùng rất tiện.', 0, NULL, NULL, '2026-02-18 03:40:00', '2026-02-18 03:40:00'),
(25, 6, 36, 3, 'Sản phẩm bình thường, dùng được.', 0, NULL, NULL, '2026-02-22 07:40:00', '2026-02-22 07:40:00'),
(26, 5, 9, 5, 'Bột mịn, làm bánh rất xốp và lên đều.', 0, NULL, NULL, '2026-02-26 01:00:00', '2026-02-26 01:00:00'),
(27, 5, 50, 4, 'Vị béo mịn, rất hợp làm tráng miệng.', 0, NULL, NULL, '2026-02-27 22:00:00', '2026-02-27 22:00:00'),
(28, 2, 34, 5, 'Mua về dùng thấy rất hài lòng, giá hợp lý.', 0, NULL, NULL, '2026-02-28 21:22:00', '2026-02-28 21:22:00'),
(29, 2, 33, 5, 'Dụng cụ chắc chắn, dùng rất tiện.', 0, NULL, NULL, '2026-03-09 01:22:00', '2026-03-09 01:22:00'),
(30, 2, 7, 3, 'Tạm ổn, đúng mô tả nhưng giá hơi cao.', 0, NULL, NULL, '2026-03-12 14:00:00', '2026-03-12 14:00:00'),
(31, 7, 53, 4, 'Chất lượng tốt, đóng gói kín.', 0, NULL, NULL, '2026-03-19 20:00:00', '2026-03-19 20:00:00'),
(32, 6, 50, 4, 'Đánh bông rất bền, làm bánh kem ổn định.', 0, NULL, NULL, '2026-03-20 00:48:00', '2026-03-20 00:48:00'),
(33, 2, 53, 5, 'Chất lượng tốt, đóng gói kín.', 0, NULL, NULL, '2026-04-22 09:40:00', '2026-04-22 09:40:00'),
(34, 2, 41, 2, 'Hàng có mùi lạ, mình chưa dùng được.', 0, 'BakeHouse xin lỗi vì trải nghiệm chưa tốt. Bạn vui lòng liên hệ hotline để được hỗ trợ đổi/trả nhé.', '2026-05-01 06:40:00', '2026-04-30 10:40:00', '2026-04-30 10:40:00'),
(35, 6, 41, 2, 'Hàng có mùi lạ, mình chưa dùng được.', 0, 'BakeHouse xin lỗi vì trải nghiệm chưa tốt. Bạn vui lòng liên hệ hotline để được hỗ trợ đổi/trả nhé.', '2026-05-05 09:55:00', '2026-05-03 22:55:00', '2026-05-03 22:55:00'),
(36, 4, 41, 4, 'Màu lên đẹp, chất lượng tốt.', 0, NULL, NULL, '2026-05-04 16:55:00', '2026-05-04 16:55:00'),
(37, 4, 18, 4, 'Dùng làm bánh rất ổn, giao hàng nhanh.', 0, NULL, NULL, '2026-05-04 18:55:00', '2026-05-04 18:55:00'),
(38, 6, 57, 5, 'Chất lượng tốt, đóng gói kín.', 0, NULL, NULL, '2026-05-15 07:00:00', '2026-05-15 07:00:00'),
(39, 6, 39, 4, 'Mua về dùng thấy rất hài lòng, giá hợp lý.', 0, NULL, NULL, '2026-05-21 12:00:00', '2026-05-21 12:00:00'),
(40, 4, 32, 1, 'Hàng có mùi lạ, mình chưa dùng được.', 0, 'BakeHouse xin lỗi vì trải nghiệm chưa tốt. Bạn vui lòng liên hệ hotline để được hỗ trợ đổi/trả nhé.', '2026-05-21 20:55:00', '2026-05-21 13:55:00', '2026-05-21 13:55:00'),
(41, 3, 4, 4, 'Bột mịn, làm bánh rất xốp và lên đều.', 0, 'Cảm ơn bạn đã ủng hộ BakeHouse! Chúc bạn làm bánh thật thành công.', '2026-05-25 18:31:00', '2026-05-25 10:31:00', '2026-05-25 10:31:00'),
(42, 8, 60, 5, 'Socola thơm, tan mịn, làm ganache đẹp.', 0, NULL, NULL, '2026-06-10 10:15:00', '2026-06-10 10:15:00'),
(43, 8, 21, 5, 'Chất lượng tốt, đúng hàng nhập khẩu.', 0, NULL, NULL, '2026-06-10 17:15:00', '2026-06-10 17:15:00'),
(44, 8, 23, 4, 'Béo thơm, làm bánh quy bơ rất đã.', 0, NULL, NULL, '2026-06-12 17:15:00', '2026-06-12 17:15:00'),
(45, 8, 26, 1, 'Chất lượng không như mong đợi, hơi thất vọng.', 0, 'BakeHouse xin lỗi vì trải nghiệm chưa tốt. Bạn vui lòng liên hệ hotline để được hỗ trợ đổi/trả nhé.', '2026-06-13 23:15:00', '2026-06-13 19:15:00', '2026-06-13 19:15:00'),
(46, 2, 65, 4, 'Bánh nở xốp hơn hẳn khi dùng loại này.', 0, NULL, NULL, '2026-06-17 16:07:00', '2026-06-17 16:07:00'),
(47, 9, 17, 4, 'Dùng làm bánh rất ổn, giao hàng nhanh.', 0, NULL, NULL, '2026-06-26 14:40:00', '2026-06-26 14:40:00'),
(48, 9, 9, 5, 'Đóng gói chắc chắn, bột trắng mịn không vón cục.', 0, NULL, NULL, '2026-07-03 09:40:00', '2026-07-03 09:40:00'),
(49, 9, 7, 5, 'Đóng gói chắc chắn, bột trắng mịn không vón cục.', 0, NULL, NULL, '2026-07-03 11:40:00', '2026-07-03 11:40:00'),
(50, 6, 24, 4, 'Chất lượng tốt, đúng hàng nhập khẩu.', 0, 'Cảm ơn bạn đã ủng hộ BakeHouse! Chúc bạn làm bánh thật thành công.', '2026-07-18 19:00:00', '2026-07-17 14:00:00', '2026-07-17 14:00:00'),
(51, 6, 37, 5, 'Dụng cụ chắc chắn, dùng rất tiện.', 0, 'Cảm ơn bạn đã ủng hộ BakeHouse! Chúc bạn làm bánh thật thành công.', '2026-07-18 07:15:00', '2026-07-17 16:15:00', '2026-07-17 16:15:00'),
(52, 3, 26, 5, 'Chất liệu tốt, dễ vệ sinh, đúng như mô tả.', 0, NULL, NULL, '2026-08-03 22:55:00', '2026-08-03 22:55:00'),
(53, 4, 47, 4, 'Hàng tươi, hạn dùng dài, đóng gói cẩn thận.', 0, NULL, NULL, '2026-08-10 22:15:00', '2026-08-10 22:15:00'),
(54, 4, 56, 5, 'Hạt giòn, tươi, không bị ẩm.', 0, NULL, NULL, '2026-08-11 22:15:00', '2026-08-11 22:15:00'),
(55, 9, 53, 4, 'Socola thơm, tan mịn, làm ganache đẹp.', 0, NULL, NULL, '2026-08-13 03:00:00', '2026-08-13 03:00:00'),
(56, 9, 22, 5, 'Dùng làm kem bơ rất mịn, rất đáng mua.', 0, NULL, NULL, '2026-08-21 07:00:00', '2026-08-21 07:00:00'),
(57, 2, 47, 5, 'Hàng tươi, hạn dùng dài, đóng gói cẩn thận.', 0, NULL, NULL, '2026-08-28 18:00:00', '2026-08-28 18:00:00'),
(58, 2, 40, 3, 'Sản phẩm bình thường, dùng được.', 0, NULL, NULL, '2026-09-03 10:00:00', '2026-09-03 10:00:00'),
(59, 8, 57, 5, 'Hạt giòn, tươi, không bị ẩm.', 0, NULL, NULL, '2026-09-13 12:40:00', '2026-09-13 12:40:00'),
(60, 9, 8, 4, 'Bột mịn, làm bánh rất xốp và lên đều.', 0, NULL, NULL, '2026-09-14 18:31:00', '2026-09-14 18:31:00'),
(61, 8, 56, 2, 'Chất lượng không như mong đợi, hơi thất vọng.', 0, 'BakeHouse xin lỗi vì trải nghiệm chưa tốt. Bạn vui lòng liên hệ hotline để được hỗ trợ đổi/trả nhé.', '2026-09-17 02:40:00', '2026-09-15 11:40:00', '2026-09-15 11:40:00'),
(62, 3, 54, 5, 'Socola thơm, tan mịn, làm ganache đẹp.', 0, 'Cảm ơn bạn đã ủng hộ BakeHouse! Chúc bạn làm bánh thật thành công.', '2026-09-18 22:00:00', '2026-09-17 16:00:00', '2026-09-17 16:00:00'),
(63, 2, 17, 3, 'Tạm ổn, đúng mô tả nhưng giá hơi cao.', 0, NULL, NULL, '2026-10-01 10:07:00', '2026-10-01 10:07:00'),
(64, 2, 21, 3, 'Sản phẩm bình thường, dùng được.', 0, NULL, NULL, '2026-10-04 18:07:00', '2026-10-04 18:07:00'),
(65, 4, 37, 5, 'Mua về dùng thấy rất hài lòng, giá hợp lý.', 0, NULL, NULL, '2026-10-06 02:00:00', '2026-10-06 02:00:00'),
(66, 4, 3, 5, 'Đóng gói chắc chắn, bột trắng mịn không vón cục.', 0, NULL, NULL, '2026-10-06 12:00:00', '2026-10-06 12:00:00'),
(67, 5, 18, 5, 'Giá hợp lý, chất lượng tốt cho làm bánh.', 0, NULL, NULL, '2026-10-09 13:48:00', '2026-10-09 13:48:00'),
(68, 5, 33, 5, 'Mua về dùng thấy rất hài lòng, giá hợp lý.', 0, NULL, NULL, '2026-10-12 20:48:00', '2026-10-12 20:48:00'),
(69, 4, 16, 3, 'Sản phẩm bình thường, dùng được.', 0, NULL, NULL, '2026-10-14 18:31:00', '2026-10-14 18:31:00'),
(70, 4, 5, 5, 'Đóng gói chắc chắn, bột trắng mịn không vón cục.', 0, NULL, NULL, '2026-10-15 03:48:00', '2026-10-15 03:48:00'),
(71, 4, 38, 5, 'Chất liệu tốt, dễ vệ sinh, đúng như mô tả.', 0, NULL, NULL, '2026-10-16 11:31:00', '2026-10-16 11:31:00'),
(72, 4, 26, 5, 'Dụng cụ chắc chắn, dùng rất tiện.', 0, NULL, NULL, '2026-10-19 08:40:00', '2026-10-19 08:40:00'),
(73, 4, 9, 5, 'Chất lượng ổn định, mình mua lại nhiều lần.', 0, NULL, NULL, '2026-10-23 02:40:00', '2026-10-23 02:40:00'),
(74, 8, 52, 4, 'Hàng tươi, hạn dùng dài, đóng gói cẩn thận.', 0, NULL, NULL, '2026-11-07 04:40:00', '2026-11-07 04:40:00'),
(75, 8, 7, 5, 'Đóng gói chắc chắn, bột trắng mịn không vón cục.', 0, NULL, NULL, '2026-11-14 04:40:00', '2026-11-14 04:40:00'),
(76, 3, 9, 4, 'Mấy lần trước đặt thì nhiều mà lần này mình đặt nhiều sản phẩm cùng lúc thì đơn to không đảm bảo có bị vỡ ở thùng hàng may hàng bên trong không bị sao', 0, NULL, NULL, '2026-11-26 09:00:07', '2026-11-26 09:00:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `phone`, `password_hash`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin@bakehouse.vn', NULL, '$2y$10$cZ6PIYrutJLVS3qZMXx2SuH3izGxt71.fHYa0UZbJHbZRzBDC1zky', 'admin', 1, '2025-11-01 02:00:00', '2026-11-22 18:00:40'),
(2, 'lan.pham@gmail.com', '0912345678', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 1, '2025-11-20 02:00:00', '2026-11-22 18:00:40'),
(3, 'user@bakehouse.vn', '0901234567', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 1, '2025-11-25 02:00:00', '2026-11-22 18:00:40'),
(4, 'minh.tran@gmail.com', '0933456789', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 1, '2025-12-05 02:00:00', '2026-11-22 18:00:40'),
(5, 'huong.le@outlook.com', '0987654321', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 1, '2025-12-18 02:00:00', '2026-11-22 18:00:40'),
(6, 'tuan.vo@gmail.com', '0905112233', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 1, '2026-01-09 02:00:00', '2026-11-22 18:00:40'),
(7, 'ngoc.bui@yahoo.com', '0978223344', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 1, '2026-01-22 02:00:00', '2026-11-22 18:00:40'),
(8, 'khoa.dang@gmail.com', '0919334455', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 1, '2026-02-14 02:00:00', '2026-11-22 18:00:40'),
(9, 'thao.nguyen@gmail.com', '0961445566', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 1, '2026-03-03 02:00:00', '2026-11-22 18:00:40'),
(10, 'hieu.hoang@gmail.com', '0932556677', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 1, '2026-04-11 02:00:00', '2026-11-22 18:00:40'),
(11, 'yen.truong@gmail.com', '0945667788', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 1, '2026-06-02 02:00:00', '2026-11-22 18:00:40'),
(12, 'nam.do@gmail.com', '0908778899', '$2y$10$ffNvtCJk5E9am/8wVcEYkegU33Bl0EweSesmfTMTYVhNlCK2zI1Ke', 'user', 0, '2026-07-19 02:00:00', '2026-11-22 18:00:40'),
(13, NULL, '0334583507', '$2y$10$ju7mrG2o6njbXGB4.abl4urc3ZUMWT186fOjPP9nGHUld9HwmW9wW', 'user', 1, '2026-11-22 18:38:34', '2026-11-22 18:38:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(150) NOT NULL DEFAULT '',
  `shipping_phone` varchar(20) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `full_name`, `shipping_phone`, `shipping_address`, `avatar_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'Quản trị viên', '0900000001', '1 Nguyễn Huệ, Quận 1, TP.HCM', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(2, 2, 'Phạm Thị Lan', '0912345678', '45 Lê Văn Sỹ, Quận 3, TP.HCM', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(3, 3, 'Nguyễn Văn A', '0901234567', '12 Trần Hưng Đạo, Quận 5, TP.HCM', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(4, 4, 'Trần Quang Minh', '0933456789', '88 Nguyễn Trãi, Thanh Xuân, Hà Nội', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(5, 5, 'Lê Thu Hương', '0987654321', '23 Hoàng Diệu, Hải Châu, Đà Nẵng', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(6, 6, 'Võ Anh Tuấn', '0905112233', '156 Điện Biên Phủ, Bình Thạnh, TP.HCM', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(7, 7, 'Bùi Bích Ngọc', '0978223344', '9 Trần Phú, Ninh Kiều, Cần Thơ', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(8, 8, 'Đặng Đăng Khoa', '0919334455', '71 Phan Chu Trinh, Hoàn Kiếm, Hà Nội', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(9, 9, 'Nguyễn Phương Thảo', '0961445566', '202 Nguyễn Thị Minh Khai, Quận 3, TP.HCM', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(10, 10, 'Hoàng Trung Hiếu', '0932556677', '34 Lê Lợi, TP. Huế', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(11, 11, 'Trương Hải Yến', '0945667788', '17 Pasteur, Quận 1, TP.HCM', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(12, 12, 'Đỗ Hoài Nam', '0908778899', '5 Hùng Vương, Biên Hòa, Đồng Nai', NULL, '2026-11-22 18:00:40', '2026-11-22 18:00:40'),
(13, 13, 'ngoc hong', '0334583507', NULL, NULL, '2026-11-22 18:38:34', '2026-11-22 18:38:34');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_brands_slug` (`slug`);

--
-- Chỉ mục cho bảng `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_carts_user_id` (`user_id`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cart_items_cart_product` (`cart_id`,`product_id`),
  ADD KEY `idx_cart_items_product` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_categories_slug` (`slug`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contacts_status` (`status`),
  ADD KEY `idx_contacts_user` (`user_id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifications_user` (`user_id`,`is_read`,`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_orders_code` (`order_code`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_items_order` (`order_id`),
  ADD KEY `idx_order_items_product` (`product_id`),
  ADD KEY `idx_order_items_status` (`status`);

--
-- Chỉ mục cho bảng `order_status_histories`
--
ALTER TABLE `order_status_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_histories_order` (`order_id`),
  ADD KEY `fk_order_histories_user` (`changed_by`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_password_resets_token` (`token_hash`),
  ADD KEY `fk_password_resets_user` (`user_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_products_slug` (`slug`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_brand` (`brand_id`),
  ADD KEY `idx_products_active_created` (`is_active`,`created_at`);
ALTER TABLE `products` ADD FULLTEXT KEY `ft_products_name` (`name`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_images_product` (`product_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_reviews_user_product` (`user_id`,`product_id`),
  ADD KEY `idx_reviews_product` (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD UNIQUE KEY `uq_users_phone` (`phone`),
  ADD KEY `idx_users_role` (`role`);

--
-- Chỉ mục cho bảng `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_profiles_user_id` (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT cho bảng `order_status_histories`
--
ALTER TABLE `order_status_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `fk_contacts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `order_status_histories`
--
ALTER TABLE `order_status_histories`
  ADD CONSTRAINT `fk_order_histories_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_histories_user` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk_password_resets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
