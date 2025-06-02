<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: ../index.php");
    exit();
}

include 'db.php'; // Pastikan 'db.php' sudah ada koneksi $conn seperti di homepage.php

// Ambil data user berdasarkan session
$user_id = $_SESSION['user_id'];
$queryUser = $conn->prepare("SELECT * FROM users WHERE id = ?");
$queryUser->bind_param("i", $user_id);
$queryUser->execute();
$resultUser = $queryUser->get_result();
$user = $resultUser->fetch_assoc();

// Ambil data booking detail berdasarkan id dari GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$queryBooking = $conn->prepare("SELECT * FROM booking_detail WHERE id = ?");
$queryBooking->bind_param("i", $id);
$queryBooking->execute();
$resultBooking = $queryBooking->get_result();
$data = $resultBooking->fetch_assoc();

if (!$data) {
    echo "Layanan tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($data['name']) ?> - Shiroo Pet Store - Booking Details</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css?v=1.0" />
    <link rel="stylesheet" href="../css/booking-details.css?v=1.0" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Inria+Sans" rel="stylesheet" />
</head>

<body>
    <!-- Navbar -->
    <nav class="top-navbar">
        <div class="navbar-left">
            <img src="../img/logo-circle.png" alt="Shiroo Logo" class="logo" />
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        </div>
        <div class="navbar-right" id="navbarMenu">
            <a href="../index.php" class="navbar-item">Home</a>
            <a href="coming-soon.php" class="navbar-item">Chat</a>
            <a href="shop.php" class="navbar-item">Shop</a>
            <a href="user.php" class="navbar-item">User</a>
        </div>
    </nav>

    <div class="mobile-name-navbar">
        Halo, <?= htmlspecialchars($user['username']) ?>
    </div>

    <main class="page-content">
        <!-- Booking Details -->
        <div class="booking-container">
            <img src="../img/booking-img/<?= htmlspecialchars($data['image']) ?>"
                alt="<?= htmlspecialchars($data['name']) ?>" />
            <h2><?= htmlspecialchars($data['name']) ?></h2>
            <div class="booking-desc">
                <p><?= nl2br(htmlspecialchars($data['description'])) ?></p>
            </div>
            <a href="booking-form.php?id=<?= $data['id'] ?>" class="booking-button">Booking Now</a>
        </div>
    </main>

    <!-- Bottom Navbar for Mobile -->
    <footer class="bottom-navbar">
        <a href="../index.php" class="bottom-nav-item">Home</a>
        <a href="coming-soon.php" class="bottom-nav-item">Chat</a>
        <a href="shop.php" class="bottom-nav-item">Shop</a>
        <a href="user.php" class="bottom-nav-item">User</a>
    </footer>
</body>

</html>