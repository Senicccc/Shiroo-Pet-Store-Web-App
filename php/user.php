<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; 

$user_id = $_SESSION['user_id'];

$query_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query_user->bind_param("i", $user_id);
$query_user->execute();
$result_user = $query_user->get_result();
$user = $result_user->fetch_assoc();

if (!$user) {
    echo "User tidak ditemukan.";
    session_destroy(); 
    header("Location: login.php");
    exit();
}

$nama_user = $user['username'];
$email_user = $user['email'];

$booking_history = [];
$query_booking = $conn->prepare("SELECT id, order_id, tanggal_booking, harga, created_at
    FROM booking_transactions
    WHERE user_id = ?
    ORDER BY created_at DESC");
$query_booking->bind_param("i", $user_id);
$query_booking->execute();
$result_booking = $query_booking->get_result();
while ($row = $result_booking->fetch_assoc()) {
    $booking_history[] = $row;
}

$checkout_history = [];
$query_checkout = $conn->prepare("SELECT 
    co.jumlah, 
    co.total, 
    co.created_at, 
    p.name AS product_name, 
    p.image AS product_image
    FROM checkout_orders co
    JOIN products p ON co.product_id = p.id
    WHERE co.user_id = ?
    ORDER BY co.created_at DESC");
$query_checkout->bind_param("i", $user_id);
$query_checkout->execute();
$result_checkout = $query_checkout->get_result();
while ($row = $result_checkout->fetch_assoc()) {
    $checkout_history[] = $row;
}

$query_user->close();
$query_booking->close();
$query_checkout->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - Shiroo Pet Store</title>

    <link rel="stylesheet" href="../css/style.css?v=1.0">
    <link rel="stylesheet" href="../css/user.css?v=1.0" />
    <link rel="stylesheet" href="../css/profile.css?v=1.0" /> <link href="https://fonts.googleapis.com/css?family=Inria+Sans" rel="stylesheet" />
</head>

<body>
    <nav class="top-navbar">
        <div class="navbar-left">
            <img src="../img/logo-circle.png" alt="Shiroo Logo" class="logo" />
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        </div>
        <div class="navbar-right" id="navbarMenu">
            <a href="../index.php" class="navbar-item">Home</a>
            <a href="#" class="navbar-item">Chat</a>
            <a href="shop.php" class="navbar-item">Shop</a>
            <a href="user.php" class="navbar-item active">User</a> <a href="logout.php" class="navbar-item">Logout</a>
        </div>
    </nav>

    <main class="profile-container">
        <section class="user-info-section">
            <div class="profile-header">
                <img src="../img/ProfileShiroo.png" alt="User Profile" class="profile-picture">
                <h1>Halo, <?= htmlspecialchars($nama_user) ?>!</h1>
                <p>Selamat datang di halaman profil Anda.</p>
            </div>
            <div class="profile-details">
                <div class="detail-item">
                    <strong>Username Pengguna:</strong><br> 
                    <?= htmlspecialchars($nama_user) ?>
                </div>
                <div class="detail-item">
                    <strong>Email Pengguna:</strong><br>
                    <?= htmlspecialchars($email_user) ?>
                </div>
                </div>
        </section>

        <section class="transaction-history-section">
            <h2>Riwayat Transaksi</h2>

            <div class="tabs">
                <button class="tab-button active" onclick="openTab(event, 'BookingHistory')">Riwayat Booking</button>
                <button class="tab-button" onclick="openTab(event, 'CheckoutHistory')">Riwayat Pembelian Produk</button>
            </div>

            <div id="BookingHistory" class="tab-content active">
                <?php if (empty($booking_history)) : ?>
                    <p>Anda belum memiliki riwayat booking.</p>
                <?php else : ?>
                    <div class="history-list">
                        <?php foreach ($booking_history as $booking) : ?>
                            <div class="transaction-item booking-item">
                                <div class="item-header">
                                    <h3>Booking ID: <?= htmlspecialchars($booking['id']) ?></h3>
                                    <span class="transaction-date"><?= date('d M Y', strtotime($booking['tanggal_booking'])) ?></span>
                                </div>
                                <div class="item-details">
                                    <div class="item-info">
                                        <p><strong>Order ID:</strong> <?= htmlspecialchars($booking['order_id']) ?></p>
                                        <p><strong>Tanggal Booking:</strong> <?= htmlspecialchars($booking['tanggal_booking']) ?></p>
                                        <p><strong>Harga:</strong> Rp<?= number_format($booking['harga'], 0, ',', '.') ?></p>
                                        <p><strong>Waktu Pemesanan:</strong> <?= date('d M Y H:i', strtotime($booking['created_at'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (empty($checkout_history)) : ?>
                <p>Anda belum memiliki riwayat pembelian produk.</p>
                    <?php else : ?>
                        <div class="history-list">
                            <?php foreach ($checkout_history as $checkout) : ?>
                                <div class="transaction-item product-item">
                                    <div class="item-header">
                                        <h3><?= htmlspecialchars($checkout['product_name']) ?></h3>
                                        <span class="transaction-date"><?= date('d M Y H:i', strtotime($checkout['created_at'])) ?></span>
                                    </div>
                                    <div class="item-details">
                                        <img src="../img/<?= htmlspecialchars($checkout['product_name']) ?>" alt="<?= htmlspecialchars($checkout['product_image']) ?>" class="item-image">
                                        <div class="item-info">
                                            <p><strong>Jumlah:</strong> <?= htmlspecialchars($checkout['jumlah']) ?></p>
                                            <p class="price"><strong>Total Pembayaran:</strong> Rp<?= number_format($checkout['total'], 0, ',', '.') ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
            <?php endif; ?>

        </section>
    </main>