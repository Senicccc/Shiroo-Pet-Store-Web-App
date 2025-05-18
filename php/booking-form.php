<?php
$koneksi = new mysqli("localhost", "root", "", "shiroo_db");
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $koneksi->prepare("SELECT * FROM booking_detail WHERE id = ?");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shiroo Pet Store - Booking Form</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css?v=1.0">
    <link rel="stylesheet" href="../css/booking-form.css?v=1.0" />


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
            <a href="homepage.php" class="navbar-item">Home</a>
            <a href="" class="navbar-item">Chat</a>
            <a href="" class="navbar-item">Shop</a>
            <a href="" class="navbar-item">User</a>
        </div>
    </nav>

    <!-- Name Navbar -->
    <div class="mobile-name-navbar">
    </div>

    <!-- Booking Form -->

    <div class="form-container">
        <form action="booking-process.php" method="POST">
            <input type="hidden" name="booking_id" value="<?= $data['id'] ?>">

            <div class="booking-info">
                <img src="../img/booking-img/<?= $data['image'] ?>" alt="<?= $data['name'] ?>">
                <p><strong><?= $data['name'] ?></strong></p>
            </div>

            <label>Tanggal Booking:</label><br>
            <input type="date" name="tanggal" min="<?= $tanggalHariIni ?>" required><br><br>

            <label>Nama Anda:</label><br>
            <input type="text" name="nama" required><br><br>

            <label>No. Telepon (Mobile):</label><br>
            <input type="tel" name="telp" required><br><br>

            <label>Gender Kucing:</label><br>
            <select name="gender_kucing" required>
                <option value="">-- Pilih --</option>
                <option value="Jantan">Jantan</option>
                <option value="Betina">Betina</option>
            </select><br><br>

            <label>Metode Pembayaran:</label>
            <div class="radio-group">
                <label><input type="radio" name="metode" value="Cash" required> Cash</label>
                <label><input type="radio" name="metode" value="Cashless" required> Cashless</label>
            </div>


            <button type="submit">Kirim Booking</button>
        </form>
    </div>

    <!-- Bottom Navbar for Mobile -->
    <footer class="bottom-navbar">
        <a href="homepage.php" class="bottom-nav-item">Home</a>
        <a href="#" class="bottom-nav-item">Chat</a>
        <a href="#" class="bottom-nav-item">Shop</a>
        <a href="#" class="bottom-nav-item">User</a>
    </footer>
</body>

</html>