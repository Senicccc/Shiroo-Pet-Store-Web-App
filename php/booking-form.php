<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include 'db.php';  // file koneksi db

$user_id = $_SESSION['user_id'];

// Ambil data user
$queryUser = $conn->prepare("SELECT username FROM users WHERE id = ?");
$queryUser->bind_param("i", $user_id);
$queryUser->execute();
$resultUser = $queryUser->get_result();
$user = $resultUser->fetch_assoc();

// Ambil id dari parameter URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data layanan booking berdasarkan id
$stmt = $conn->prepare("SELECT * FROM booking_detail WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "Layanan tidak ditemukan.";
    exit;
}

$tanggalHariIni = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shiroo Pet Store - Booking Form</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css?v=1.0">
    <link rel="stylesheet" href="../css/booking-form.css?v=1.0" />
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
            <a href="homepage.php" class="navbar-item">Home</a>
            <a href="coming-soon.php" class="navbar-item">Chat</a>
            <a href="#" class="navbar-item">Shop</a>
            <a href="#" class="navbar-item">User</a>
        </div>
    </nav>

    <!-- Name Navbar -->
    <div class="mobile-name-navbar">
        Halo, <?= htmlspecialchars($user['username']) ?>
    </div>

    <!-- Booking Form -->
    <div class="form-container">
        <!-- FIXED: Ganti action ke booking-process.php -->
        <form action="booking-process.php" method="POST">
            <input type="hidden" name="booking_id" value="<?= htmlspecialchars($data['id']) ?>">

            <div class="booking-info">
                <img src="../img/booking-img/<?= htmlspecialchars($data['image']) ?>"
                    alt="<?= htmlspecialchars($data['name']) ?>">
                <p><strong><?= htmlspecialchars($data['name']) ?></strong></p>
            </div>

            <label for="tanggal">Tanggal Booking:</label><br>
            <input type="date" id="tanggal" name="tanggal" min="<?= $tanggalHariIni ?>" required><br><br>

            <label for="nama">Nama Anda:</label><br>
            <input type="text" id="nama" name="nama" required><br><br>

            <label for="telp">No. Telepon (Mobile):</label><br>
            <input type="tel" id="telp" name="telp" required><br><br>

            <label for="gender_kucing">Gender Kucing:</label><br>
            <select id="gender_kucing" name="gender_kucing" required>
                <option value="">-- Pilih --</option>
                <option value="Jantan">Jantan</option>
                <option value="Betina">Betina</option>
            </select><br><br>

            <button type="submit">Kirim Booking</button>
        </form>
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