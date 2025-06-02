<?php
include 'auth.php'; 

$koneksi = new mysqli("localhost", "root", "", "shiroo_db");

// Validasi ID produk
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID produk tidak ditemukan atau tidak valid.'); window.location.href = 'shop.php';</script>";
    exit;
}

$id = (int)$_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = $id");

if (!$query || mysqli_num_rows($query) === 0) {
    echo "<script>alert('Produk tidak ditemukan.'); window.location.href = 'shop.php';</script>";
    exit;
}

$product = mysqli_fetch_assoc($query);

// Cek jika ada error stok
if (isset($_GET['error']) && $_GET['error'] === 'stok_kurang') {
    $tersedia = isset($_GET['tersedia']) ? (int)$_GET['tersedia'] : 0;
    echo "<script>alert('Stok produk tidak mencukupi. Stok tersedia: $tersedia');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout - <?= htmlspecialchars($product['name']) ?></title>

    <!-- Style bawaan -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- Style khusus checkout -->
    <link rel="stylesheet" href="../css/checkout.css?v=1.0" />

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Inria+Sans" rel="stylesheet" />
</head>

<body>

    <!-- Top Navbar -->
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

    <!-- Mobile Name Navbar -->
    <div class="mobile-name-navbar">Checkout</div>

    <!-- Konten checkout -->
    <main class="checkout-wrapper">
        <!-- KIRI: INFO PRODUK -->
        <div class="product-details">
            <img src="../img/product-img/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p><strong>Harga Satuan:</strong> Rp<?= number_format($product['price'], 0, ',', '.') ?></p>
            <p><strong>Deskripsi:</strong></p>
            <p><?= nl2br(htmlspecialchars($product['desc'] ?? 'Tidak ada deskripsi.')) ?></p>
        </div>

        <!-- KANAN: FORM CHECKOUT -->
        <div class="checkout-form">
            <form action="checkout-process.php" method="POST" onsubmit="return konfirmasiCheckout();">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" id="harga" value="<?= $product['price'] ?>">

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" name="nama" required>
                </div>

                <div class="form-group">
                    <label for="telepon">Nomor Telepon</label>
                    <input type="text" name="telepon" required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah Beli</label>
                    <input type="number" name="jumlah" id="jumlah" value="1" min="1" required oninput="hitungTotal()">
                </div>

                <p class="total">Total: Rp<span id="total"><?= number_format($product['price'], 0, ',', '.') ?></span>
                </p>

                <button type="submit">Checkout Sekarang</button>
            </form>
        </div>
    </main>

    <!-- Bottom Navbar -->
    <footer class="bottom-navbar">
        <a href="homepage.php" class="bottom-nav-item">Home</a>
        <a href="coming-soon.php" class="bottom-nav-item">Chat</a>
        <a href="shop.php" class="bottom-nav-item">Shop</a>
        <a href="user.php" class="bottom-nav-item">User</a>
    </footer>

    <script>
    function hitungTotal() {
        const harga = parseInt(document.getElementById("harga").value);
        const jumlah = parseInt(document.getElementById("jumlah").value);
        const total = harga * jumlah;
        document.getElementById("total").innerText = total.toLocaleString('id-ID');
    }

    function konfirmasiCheckout() {
        return confirm("Apakah Anda yakin ingin melanjutkan transaksi?");
    }
    </script>
</body>

</html>