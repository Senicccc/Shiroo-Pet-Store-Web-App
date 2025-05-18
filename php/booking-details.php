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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $data['name'] ?>Shiroo Pet Store - Booking Details</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css?v=1.0">
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
            <a href="homepage.php" class="navbar-item">Home</a>
            <a href="" class="navbar-item">Chat</a>
            <a href="" class="navbar-item">Shop</a>
            <a href="" class="navbar-item">User</a>
        </div>
    </nav>

    <!-- Name Navbar -->
    <div class="mobile-name-navbar">
    </div>

    <!-- Booking Details -->
    <div class="booking-container">
        <img src="../img/booking-img/<?= $data['image'] ?>" alt="<?= $data['name'] ?>">
        <h2><?= $data['name'] ?></h2>
        <div class="booking-desc">
            <p><?= $data['description'] ?></p>
        </div>
        <a href="booking-form.php?id=<?= $data['id'] ?>" class="booking-button">Booking Now</a>
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