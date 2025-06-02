<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Jika user tidak ditemukan
if (!$user) {
    echo "User tidak ditemukan.";
    exit();
}

$nama_user = $user['username'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiroo Pet Store - Shop</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css?v=1.0">
    <link rel="stylesheet" href="../css/shop.css?v=1.0" />

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

    <!-- Shop Section -->
    <main class="shop-container">
        <?php
        $query = mysqli_query($conn, "SELECT * FROM products ORDER BY id ASC");
        while ($row = mysqli_fetch_assoc($query)) {
            $image = $row['image'];
            $product = $row['name'];
            $price = $row['price'];
        ?>
        <a href="checkout.php?id=<?= $row['id'] ?>" class="product-card">
            <img src="../img/product-img/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($product) ?>">
            <div class="product-info">
                <h3 class="product-title"><?= htmlspecialchars($product) ?></h3>
                <p class="product-price">Rp<?= number_format($price, 0, ',', '.') ?></p>
            </div>
        </a>
        <?php } ?>
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