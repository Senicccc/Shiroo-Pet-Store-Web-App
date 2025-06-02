-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 08:23 AM
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
-- Database: `shiroo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`) VALUES
(3, 'admin1', '$2y$10$DvjQ0TnpRM0Nd2cQGxQeuO4P1sy9uBzMZy1mJX/yPAGq3gXHgRGv2', '2025-05-23 12:44:47'),
(5, 'admin123', '$2y$10$vDY1nisAwHj9MGi6deHIIO3nO0smMllEeEn4CNrydTCfxK.oMlwsq', '2025-05-23 12:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `booking_detail`
--

CREATE TABLE `booking_detail` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_detail`
--

INSERT INTO `booking_detail` (`id`, `name`, `description`, `image`, `harga`) VALUES
(1, 'Bath Your Cat', 'Kami menyediakan layanan pemandian kucing profesional untuk menjaga kebersihan dan kesehatan kucing kesayangan Anda. Dengan menggunakan produk khusus yang aman untuk kulit dan bulu kucing, kami memastikan setiap sesi mandi memberikan kenyamanan maksimal. Tim kami yang berpengalaman akan menangani kucing dengan penuh perhatian dan kelembutan, termasuk pembersihan telinga, pemotongan kuku, serta pengeringan yang aman. Jadikan kucing Anda lebih segar, sehat, dan bebas dari kotoran dengan layanan kami!Tim kami terdiri dari tenaga ahli berpengalaman dalam perawatan hewan peliharaan, yang terlatih dalam menangani berbagai jenis kucing dengan penuh perhatian dan kelembutan. Selain pemandian, layanan kami mencakup pembersihan telinga, pemotongan kuku, serta pengeringan yang aman dan nyaman.', 'bath-cat.png', 150000),
(2, 'Grooming Cat', 'Kami menyediakan layanan cat grooming profesional untuk menjaga kebersihan, kesehatan, dan kenyamanan kucing kesayangan Anda. Grooming bukan hanya sekadar memandikan, tetapi juga mencakup perawatan menyeluruh agar kucing tetap sehat dan tampil menawan.Tim kami yang berpengalaman dan terlatih dalam perawatan hewan akan menangani setiap kucing dengan penuh kelembutan dan kehati-hatian. Layanan grooming kami mencakup:? ? Menyikat dan merapikan bulu untuk mencegah kerontokan.? ? Pemotongan kuku agar kucing tetap nyaman ? ? Pembersihan telinga dan mata untuk mencegah infeksi Kami berkomitmen untuk memberikan pengalaman yang nyaman bagi kucing Anda dengan perawatan yang profesional dan penuh kasih sayang. Dengan layanan kami, kucing Anda akan terlihat lebih segar, sehat, dan bahagia!', 'grooming-cat.png', 200000),
(3, 'Cat Hotel Care', 'Tinggalkan kucing kesayangan Anda dengan tenang saat bepergian! Kami menyediakan layanan Cat Hotel Care yang nyaman, aman, dan penuh kasih sayang. Dengan fasilitas terbaik dan perawatan profesional, kami memastikan kucing Anda tetap bahagia dan sehat selama menginap.? Mengapa memilih layanan kami? ?? ?? Kenyamanan & Keamanan - Ruangan bersih, nyaman, dan ber-AC untuk memastikan kucing merasa seperti di rumah sendiri.? ??? Makanan & Minuman Terjamin - Pemberian makanan sesuai kebutuhan kucing, dengan pilihan premium atau sesuai permintaan pemilik.? ??? Area Bermain & Istirahat - Ruang bermain yang aman serta tempat tidur yang nyaman untuk beristirahat.? ?? Perawatan Harian - Pembersihan kandang rutin, pemberian makanan sesuai jadwal, dan pemantauan kesehatan kucing.', 'cat-hotel.png', 300000);

-- --------------------------------------------------------

--
-- Table structure for table `booking_transactions`
--

CREATE TABLE `booking_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` varchar(50) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `gender_kucing` enum('Jantan','Betina') NOT NULL,
  `harga` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_transactions`
--

INSERT INTO `booking_transactions` (`id`, `user_id`, `order_id`, `booking_id`, `tanggal_booking`, `nama_pelanggan`, `no_telp`, `gender_kucing`, `harga`, `created_at`) VALUES
(1, 1, 'order_68328c9dd2d8f', 1, '2025-05-26', 'test', '12345678', 'Betina', 150000, '2025-05-25 03:21:33'),
(2, 1, 'order_68328cf5d30d8', 3, '2025-05-26', 'test', '12345678', 'Jantan', 300000, '2025-05-25 03:47:31'),
(3, 1, 'order_68328fccc0e3d', 1, '2025-05-30', 'John Doe', '123', 'Betina', 150000, '2025-05-25 03:50:47'),
(4, 1, 'order_6832962e103b9', 2, '2025-05-28', 'test', '213113', 'Betina', 200000, '2025-05-25 04:03:03'),
(5, 1, 'order_683296f506e22', 3, '2025-05-28', 'John Doe', '123', 'Betina', 300000, '2025-05-25 04:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `checkout_orders`
--

CREATE TABLE `checkout_orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `jumlah` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkout_orders`
--

INSERT INTO `checkout_orders` (`id`, `order_id`, `user_id`, `product_id`, `nama`, `telepon`, `alamat`, `jumlah`, `total`, `created_at`) VALUES
(1, 'ORDER-1748150478', 1, 1, 'test', '1234', 'test', 1, 150000, '2025-05-25 05:21:46'),
(2, 'ORDER-1748153355', 13, 5, 'John Doe', '08123456789', 'Jalan Pengetesan Website Ini, Nomor gatau, Kabupaten Ngetes, Tes Barat', 4, 220000, '2025-05-25 06:10:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `desc`, `price`, `stock`, `category_id`, `image`) VALUES
(1, 'Royal Canin - Kitten 1Kg', 'Royal Canin Kitten adalah makanan untuk anak kucing dalam fase pertumbuhan pertama (sampai 4 bulan), Royal Canin mempertahankan bahwa diet kucing harus memperhitungkan semua parameter kehidupan seperti usia, ras, gaya hidup, dan keadaan individual.', 150000.00, 19, 1, 'royal-canin.png'),
(2, 'Whiskas Tuna Flavour Loaf - adult 1.2Kg', 'Whiskasr Tuna Flavour Loaf adalah makanan kucing basah yang menggugah selera dan seimbang, memenuhi 100% kebutuhan gizi harian kucing dewasa Anda. Tuna untuk kucing ini sangat baik untuk kucing karena:\r\n\r\nTerbuat dari bahan berkualitas tinggi dari tuna asli untuk kucing, Mendukung sistem kekebalan tubuh yang kuat, Mengandung semua vitamin dan mineral yang diperlukan, Membantu menjaga kulit dan bulu, Mengandung jumlah natrium yang optimal untuk kucing', 70000.00, 11, 1, 'WhiskasTunaFlavourLoaf-adult.png'),
(3, 'Tas hewan Kapasitas Maks 10Kg', '?? Tas Ransel Hewan Nyaman | Tas Kucing Astronot Backpack ??\r\n \r\nIngin bepergian bersama hewan kesayangan Anda tanpa repot? Tas Ransel Hewan Nyaman adalah pilihan sempurna untuk membawa anabul Anda dengan nyaman dan aman. Dirancang dengan ventilasi maksimal dan desain full-frame transparan, tas ini memastikan hewan kesayangan Anda tetap rileks dan bahagia selama perjalanan.', 125000.00, 15, 2, 'tasranselhewan.png'),
(4, 'Kalung kucing - Bisa Custom Nama', 'Kalung kucing Berbahan Dasar Kulit Sintetis , kuat dan nyaman ketika di pakai anabul\r\n', 35000.00, 45, 3, 'kalungkucing.png'),
(5, 'Bak Pasir Kucing - Medium', 'Cat Litter Box Kuning - 49cm x 35cm x 22cm.\r\n\r\nPet Litter Box M19 adalah pet litter dengan ukuran besar, bisa digunakan untuk hewan dewasa ataupun hewan kecil. Dengan desain terbuka dan luas, serta pembatas yang tinggi sehingga membuat anabul akan leluasa bergerak di dalamnya. Pintu litter box dibuat lebih rendah sehingga memudahkan anabul untuk keluar dan masuk, dan juga memudahkan ketika Anda akan membersihkan litter box.', 55000.00, 29, 2, 'bakpasir.png'),
(6, 'Baju Import Kucing Thailand - Semua Ukuran', 'Import dari Thailand - Kualitas Premium\r\nMaterial: Premium Polyester\r\nWarna : Maroon, Green, Grey, Mocha, Blue, Latte, Khaki\r\nSize: S - XXXL', 95000.00, 29, 3, 'bajuimport.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'test', 'test@gmail.com', '$2y$10$daZB1ZFDg8HNEWVP0yrKteQ/TjRNxqtsee2SUkZsdk6ttnF9bepz.', '2025-05-08 17:12:29'),
(2, 'ichsan', 'test2@gmail.com', '$2y$10$g8epmqFFEpxRGthc2HPifug6D0C9elrj2uPKZf8KQL3nYqwaHjMym', '2025-05-15 20:21:57'),
(3, 'test', '123@1', '$2y$10$OtrvOGNhOxjea.NzucObyOp7ZMaEyXyzOw3MA9DSxO0CEaXywKbam', '2025-05-23 08:26:58'),
(5, 'nicho', 'nicho@nicho.com', '$2y$10$w.k5kMerKF.BQTF3kAzyXOnCcFU7/3O1gD8tM9GhOt6aDkBkELLMG', '2025-05-23 08:27:51'),
(6, 'adminA', 'a@a.a', 'a', '2025-05-23 08:29:39'),
(10, 'a', 'a@a.aa', '$2y$10$zXPRTsb0wm/143VUPNx0o.MtvxoSEJcqN/JIpqH0dUgVphd42.b.K', '2025-05-23 08:31:56'),
(11, 'testnambah', 'testnambah@test.com', '$2y$10$1yPJsvKTMLGeuiNh8dRaZ.6OwmtwyG84.IZonXsb2UeKfkrQOt1wi', '2025-05-23 13:07:09'),
(12, 'nicholas', 'nicholas@nicholas.com', '$2y$10$HM2yFh5ooM3L6LxUa0llBekE0gVFqLTAfoF11Pz05DgKNxd2WWDo.', '2025-05-25 06:03:57'),
(13, 'JohnDoe', 'johndoe@gmail.com', '$2y$10$yDRkGkf9Jn461OS6SWmtUuwZ0/luda554EBUMjQ3qw4Dc1q24mbf.', '2025-05-25 06:07:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `booking_detail`
--
ALTER TABLE `booking_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_transactions`
--
ALTER TABLE `booking_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `fk_user_booking` (`user_id`);

--
-- Indexes for table `checkout_orders`
--
ALTER TABLE `checkout_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking_detail`
--
ALTER TABLE `booking_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_transactions`
--
ALTER TABLE `booking_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `checkout_orders`
--
ALTER TABLE `checkout_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_transactions`
--
ALTER TABLE `booking_transactions`
  ADD CONSTRAINT `fk_user_booking` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `checkout_orders`
--
ALTER TABLE `checkout_orders`
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
