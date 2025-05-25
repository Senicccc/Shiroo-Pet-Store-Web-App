-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2025 at 02:47 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
-- Table structure for table `booking_detail`
--

CREATE TABLE `booking_detail` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_detail`
--

INSERT INTO `booking_detail` (`id`, `name`, `description`, `image`) VALUES
(1, 'Bath Your Cat', 'Kami menyediakan layanan pemandian kucing profesional untuk menjaga kebersihan dan kesehatan kucing kesayangan Anda. Dengan menggunakan produk khusus yang aman untuk kulit dan bulu kucing, kami memastikan setiap sesi mandi memberikan kenyamanan maksimal. Tim kami yang berpengalaman akan menangani kucing dengan penuh perhatian dan kelembutan, termasuk pembersihan telinga, pemotongan kuku, serta pengeringan yang aman. Jadikan kucing Anda lebih segar, sehat, dan bebas dari kotoran dengan layanan kami!Tim kami terdiri dari tenaga ahli berpengalaman dalam perawatan hewan peliharaan, yang terlatih dalam menangani berbagai jenis kucing dengan penuh perhatian dan kelembutan. Selain pemandian, layanan kami mencakup pembersihan telinga, pemotongan kuku, serta pengeringan yang aman dan nyaman.', 'bath-cat.png'),
(2, 'Grooming Cat', 'Kami menyediakan layanan cat grooming profesional untuk menjaga kebersihan, kesehatan, dan kenyamanan kucing kesayangan Anda. Grooming bukan hanya sekadar memandikan, tetapi juga mencakup perawatan menyeluruh agar kucing tetap sehat dan tampil menawan.Tim kami yang berpengalaman dan terlatih dalam perawatan hewan akan menangani setiap kucing dengan penuh kelembutan dan kehati-hatian. Layanan grooming kami mencakup:? ? Menyikat dan merapikan bulu untuk mencegah kerontokan.? ? Pemotongan kuku agar kucing tetap nyaman ? ? Pembersihan telinga dan mata untuk mencegah infeksi Kami berkomitmen untuk memberikan pengalaman yang nyaman bagi kucing Anda dengan perawatan yang profesional dan penuh kasih sayang. Dengan layanan kami, kucing Anda akan terlihat lebih segar, sehat, dan bahagia!', 'grooming-cat.png'),
(3, 'Cat Hotel Care', 'Tinggalkan kucing kesayangan Anda dengan tenang saat bepergian! Kami menyediakan layanan Cat Hotel Care yang nyaman, aman, dan penuh kasih sayang. Dengan fasilitas terbaik dan perawatan profesional, kami memastikan kucing Anda tetap bahagia dan sehat selama menginap.? Mengapa memilih layanan kami? ?? ?? Kenyamanan & Keamanan - Ruangan bersih, nyaman, dan ber-AC untuk memastikan kucing merasa seperti di rumah sendiri.? ??? Makanan & Minuman Terjamin - Pemberian makanan sesuai kebutuhan kucing, dengan pilihan premium atau sesuai permintaan pemilik.? ??? Area Bermain & Istirahat - Ruang bermain yang aman serta tempat tidur yang nyaman untuk beristirahat.? ?? Perawatan Harian - Pembersihan kandang rutin, pemberian makanan sesuai jadwal, dan pemantauan kesehatan kucing.', 'cat-hotel.png');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `category_id` int NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `desc`, `price`, `stock`, `category_id`, `image`) VALUES
(1, 'Royal Canin - Kitten 1Kg', 'Royal Canin Kitten adalah makanan untuk anak kucing dalam fase pertumbuhan pertama (sampai 4 bulan), Royal Canin mempertahankan bahwa diet kucing harus memperhitungkan semua parameter kehidupan seperti usia, ras, gaya hidup, dan keadaan individual.', '150000.00', 20, 1, 'royal-canin.png'),
(2, 'Whiskas Tuna Flavour Loaf - adult 1.2Kg', 'Whiskas® Tuna Flavour Loaf adalah makanan kucing basah yang menggugah selera dan seimbang, memenuhi 100% kebutuhan gizi harian kucing dewasa Anda. Tuna untuk kucing ini sangat baik untuk kucing karena:\r\n\r\nTerbuat dari bahan berkualitas tinggi dari tuna asli untuk kucing, Mendukung sistem kekebalan tubuh yang kuat, Mengandung semua vitamin dan mineral yang diperlukan, Membantu menjaga kulit dan bulu, Mengandung jumlah natrium yang optimal untuk kucing', '70000.00', 12, 1, 'WhiskasTunaFlavourLoaf-adult.png'),
(3, 'Tas hewan Kapasitas Maks 10Kg', '🐾 Tas Ransel Hewan Nyaman | Tas Kucing Astronot Backpack 🐾\r\n \r\nIngin bepergian bersama hewan kesayangan Anda tanpa repot? Tas Ransel Hewan Nyaman adalah pilihan sempurna untuk membawa anabul Anda dengan nyaman dan aman. Dirancang dengan ventilasi maksimal dan desain full-frame transparan, tas ini memastikan hewan kesayangan Anda tetap rileks dan bahagia selama perjalanan.', '125000.00', 18, 2, 'tasranselhewan.png'),
(4, 'Kalung kucing - Bisa Custom Nama', 'Kalung kucing Berbahan Dasar Kulit Sintetis , kuat dan nyaman ketika di pakai anabul\r\n', '35000.00', 45, 3, 'kalungkucing.png'),
(5, 'Bak Pasir Kucing - Medium', 'Cat Litter Box Kuning - 49cm x 35cm x 22cm.\r\n\r\nPet Litter Box M19 adalah pet litter dengan ukuran besar, bisa digunakan untuk hewan dewasa ataupun hewan kecil. Dengan desain terbuka dan luas, serta pembatas yang tinggi sehingga membuat anabul akan leluasa bergerak di dalamnya. Pintu litter box dibuat lebih rendah sehingga memudahkan anabul untuk keluar dan masuk, dan juga memudahkan ketika Anda akan membersihkan litter box.', '55000.00', 33, 2, 'bakpasir.png'),
(6, 'Baju Import Kucing Thailand - Semua Ukuran', 'Import dari Thailand - Kualitas Premium\r\nMaterial: Premium Polyester\r\nWarna : Maroon, Green, Grey, Mocha, Blue, Latte, Khaki\r\nSize: S - XXXL', '95000.00', 29, 3, 'bajuimport.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'test', 'test@gmail.com', '$2y$10$daZB1ZFDg8HNEWVP0yrKteQ/TjRNxqtsee2SUkZsdk6ttnF9bepz.', '2025-05-09 00:12:29'),
(2, 'ichsan', 'test2@gmail.com', '$2y$10$g8epmqFFEpxRGthc2HPifug6D0C9elrj2uPKZf8KQL3nYqwaHjMym', '2025-05-16 03:21:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_detail`
--
ALTER TABLE `booking_detail`
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_detail`
--
ALTER TABLE `booking_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
