<?php
session_start();
include 'db.php';  

// Ambil nama user jika login
$nama_user = $_SESSION['nama_user'] ?? 'User';

// Cek apakah order_id tersedia di URL
if (!isset($_GET['order_id'])) {
    echo "Order ID tidak ditemukan.";
    exit;
}

$order_id = $_GET['order_id'];

// Cek apakah transaksi dengan order_id ini sudah ada di database
$stmt = $conn->prepare("SELECT * FROM booking_transactions WHERE order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$transaction = $result->fetch_assoc();
$stmt->close();

// Jika transaksi belum ada, ambil dari session dan simpan ke database
if (!$transaction) {
    if (!isset($_SESSION['booking_data']) || $_SESSION['booking_data']['order_id'] !== $order_id) {
        echo "Data transaksi tidak ditemukan.";
        exit;
    }

    if (!isset($_SESSION['user_id'])) {
        echo "User tidak dikenali. Silakan login ulang.";
        exit;
    }

    $data = $_SESSION['booking_data'];
    $user_id = $_SESSION['user_id'];

    $stmt_insert = $conn->prepare("INSERT INTO booking_transactions (user_id, order_id, booking_id, tanggal_booking, nama_pelanggan, no_telp, gender_kucing, harga) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_insert->bind_param(
        "isissssi",
        $user_id,
        $data['order_id'],
        $data['booking_id'],
        $data['tanggal_booking'],
        $data['nama_pelanggan'],
        $data['no_telp'],
        $data['gender_kucing'],
        $data['harga']
    );

    if ($stmt_insert->execute()) {
        // Ambil kembali untuk ditampilkan
        $stmt = $conn->prepare("SELECT * FROM booking_transactions WHERE order_id = ?");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $transaction = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "Gagal menyimpan data transaksi.";
        exit;
    }

    $stmt_insert->close();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Booking Success - Shiroo Pet Store</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css?v=1.0">
    <link rel="stylesheet" href="../css/booking-success.css?v=1.0">

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

    <!-- Mobile Greeting -->
    <div class="mobile-name-navbar">
        Halo, <?= htmlspecialchars($nama_user) ?>!
    </div>

    <!-- Konten Utama -->
    <div class="container">
        <h1>Terima kasih, <?= htmlspecialchars($transaction['nama_pelanggan']) ?>!</h1>
        <p>Booking Anda telah berhasil diproses.</p>

        <table>
            <tr>
                <th>Order ID</th>
                <td><?= htmlspecialchars($transaction['order_id']) ?></td>
            </tr>
            <tr>
                <th>Booking ID</th>
                <td><?= htmlspecialchars($transaction['booking_id']) ?></td>
            </tr>
            <tr>
                <th>Tanggal Booking</th>
                <td><?= htmlspecialchars($transaction['tanggal_booking']) ?></td>
            </tr>
            <tr>
                <th>Nama Pelanggan</th>
                <td><?= htmlspecialchars($transaction['nama_pelanggan']) ?></td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td><?= htmlspecialchars($transaction['no_telp']) ?></td>
            </tr>
            <tr>
                <th>Gender Kucing</th>
                <td><?= htmlspecialchars($transaction['gender_kucing']) ?></td>
            </tr>
            <tr>
                <th>Harga</th>
                <td>Rp<?= number_format($transaction['harga'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Waktu Booking</th>
                <td><?= htmlspecialchars($transaction['created_at']) ?></td>
            </tr>
        </table>

        <p><a href="homepage.php">Kembali ke Beranda</a></p>
    </div>

    <!-- Bottom Navbar for Mobile -->
    <footer class="bottom-navbar">
        <a href="../index.php" class="bottom-nav-item">Home</a>
        <a href="coming-soon.php" class="bottom-nav-item">Chat</a>
        <a href="shop.php" class="bottom-nav-item">Shop</a>
        <a href="user.php" class="bottom-nav-item">User</a>
    </footer>
</body>

</html>