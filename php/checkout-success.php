<?php
session_start();
include 'db.php';

$nama_user = $_SESSION['nama_user'] ?? 'User';

if (!isset($_GET['order_id'])) {
    echo "ID transaksi tidak ditemukan.";
    exit;
}

$order_id = $_GET['order_id'];

// Cari transaksi berdasar order_id
$stmt = $conn->prepare("SELECT * FROM checkout_orders WHERE order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$transaction = $result->fetch_assoc();
$stmt->close();

if (!$transaction) {
    // Jika transaksi belum ada, simpan dari session checkout_data
    if (!isset($_SESSION['checkout_data']) || $_SESSION['checkout_data']['order_id'] !== $order_id) {
        echo "Data transaksi tidak ditemukan.";
        exit;
    }

    if (!isset($_SESSION['user_id'])) {
        echo "User tidak dikenali. Silakan login ulang.";
        exit;
    }

    $data = $_SESSION['checkout_data'];
    $user_id = $_SESSION['user_id'];

    $stmt_insert = $conn->prepare("INSERT INTO checkout_orders (order_id, user_id, product_id, nama, telepon, alamat, jumlah, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt_insert->bind_param(
        "siisssii",
        $data['order_id'],
        $user_id,
        $data['product_id'],
        $data['nama_pelanggan'],
        $data['no_telp'],
        $data['alamat'],
        $data['jumlah'],
        $data['harga']
    );

    if ($stmt_insert->execute()) {
        $stmt = $conn->prepare("SELECT * FROM checkout_orders WHERE order_id = ?");
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
    <title>Checkout Success - Shiroo Pet Store</title>

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
        <h1>Terima kasih, <?= htmlspecialchars($transaction['nama']) ?>!</h1>
        <p>Pembelian produk Anda telah berhasil diproses.</p>

        <table>
            <tr>
                <th>ID Transaksi</th>
                <td><?= htmlspecialchars($transaction['id']) ?></td>
            </tr>
            <tr>
                <th>Produk ID</th>
                <td><?= htmlspecialchars($transaction['product_id']) ?></td>
            </tr>
            <tr>
                <th>Nama Pelanggan</th>
                <td><?= htmlspecialchars($transaction['nama']) ?></td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td><?= htmlspecialchars($transaction['telepon']) ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?= htmlspecialchars($transaction['alamat']) ?></td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td><?= htmlspecialchars($transaction['jumlah']) ?></td>
            </tr>
            <tr>
                <th>Harga Total</th>
                <td>Rp<?= number_format($transaction['total'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Waktu Pembelian</th>
                <td><?= htmlspecialchars($transaction['created_at']) ?></td>
            </tr>
        </table>

        <p><a href="../index.php">Kembali ke Beranda</a></p>
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