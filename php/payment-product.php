<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['checkout_data'])) {
    echo "Data checkout tidak ditemukan.";
    exit();
}

$data = $_SESSION['checkout_data'];
$user_id = $_SESSION['user_id'];

// Ambil data user
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nama_user, $email_user);
$stmt->fetch();
$stmt->close();

// Midtrans Snap API
$midtrans_payload = [
    'transaction_details' => [
        'order_id' => $data['order_id'],
        'gross_amount' => $data['harga']
    ],
    'customer_details' => [
        'first_name' => $data['nama_pelanggan'],
        'email' => $email_user ?: "user@example.com",
        'phone' => $data['no_telp']
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://app.sandbox.midtrans.com/snap/v1/transactions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($midtrans_payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic ' . base64_encode("SB-Mid-server-7jQtDeJZL6B85zJN_tTvRrGb" . ':')
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo "Curl Error: " . curl_error($ch);
    exit();
}
curl_close($ch);

$snap = json_decode($response, true);
if (!isset($snap['token'])) {
    echo "Gagal mendapatkan token Midtrans.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Produk</title>
    <link rel="stylesheet" href="../css/style.css?v=1.0">
    <link rel="stylesheet" href="../css/payment-booking.css?v=1.0">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-f0Im8VM77Rvj_yjA">
    </script>
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

    <!-- Main Content -->
    <div class="container">
        <h2>Pembayaran</h2>
        <p>Nama: <?= htmlspecialchars($data['nama_pelanggan']) ?></p>
        <p>Produk: <?= htmlspecialchars($data['product_name']) ?></p>
        <p>Jumlah: <?= $data['jumlah'] ?></p>
        <p>Total: <strong>Rp<?= number_format($data['harga'], 0, ',', '.') ?></strong></p>
        <button id="pay-button">Bayar Sekarang</button>
    </div>

    <!-- Midtrans Script -->
    <script type="text/javascript">
    document.getElementById('pay-button').onclick = function() {
        snap.pay("<?= $snap['token'] ?>", {
            onSuccess: function(result) {
                window.location.href = "checkout-success.php?order_id=" + encodeURIComponent(result
                    .order_id);
            },
            onPending: function(result) {
                alert("Transaksi masih pending.");
            },
            onError: function(result) {
                alert("Terjadi kesalahan saat pembayaran.");
            },
            onClose: function() {
                alert("Kamu menutup popup pembayaran.");
            }
        });
    };
    </script>

    <!-- Bottom Navbar -->
    <footer class="bottom-navbar">
        <a href="../index.php" class="bottom-nav-item">Home</a>
        <a href="coming-soon.php" class="bottom-nav-item">Chat</a>
        <a href="shop.php" class="bottom-nav-item">Shop</a>
        <a href="user.php" class="bottom-nav-item">User</a>
    </footer>
</body>

</html>