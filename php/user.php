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
            <a href="coming-soon.php" class="navbar-item">Chat</a>
            <a href="shop.php" class="navbar-item">Shop</a>
            <a href="user.php" class="navbar-item active">User</a> 
            <a href="logout.php" class="navbar-item">Logout</a>
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
    <h2>Riwayat Booking</h2>
    <?php if (empty($booking_history)) : ?>
        <p class="empty-history">Anda belum memiliki riwayat booking.</p>
    <?php else : ?>
        <div class="table-wrapper">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Tanggal Booking</th>
                        <th>Harga</th>
                        <th>Waktu Pemesanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($booking_history as $index => $booking) : ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($booking['order_id']) ?></td>
                            <td><?= date('d M Y', strtotime($booking['tanggal_booking'])) ?></td>
                            <td>Rp<?= number_format($booking['harga'], 0, ',', '.') ?></td>
                            <td><?= date('d M Y H:i', strtotime($booking['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <h2>Riwayat Pembelian Produk</h2>
    <?php if (empty($checkout_history)) : ?>
        <p class="empty-history">Anda belum memiliki riwayat pembelian produk.</p>
    <?php else : ?>
        <div class="table-wrapper">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Total Pembayaran</th>
                        <th>Waktu Pembelian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($checkout_history as $index => $checkout) : ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($checkout['product_name']) ?></td>
                            <td><?= htmlspecialchars($checkout['jumlah']) ?></td>
                            <td>Rp<?= number_format($checkout['total'], 0, ',', '.') ?></td>
                            <td><?= date('d M Y H:i', strtotime($checkout['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
    </main>
    <!-- Bottom Navbar -->
    <footer class="bottom-navbar">
        <a href="../index.php" class="bottom-nav-item">Home</a>
        <a href="coming-soon.php" class="bottom-nav-item">Chat</a>
        <a href="shop.php" class="bottom-nav-item">Shop</a>
        <a href="#" class="bottom-nav-item">User</a>
    </footer>
    </body>