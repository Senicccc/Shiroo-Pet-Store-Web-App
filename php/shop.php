<?php
$koneksi = new mysqli("localhost", "root", "", "shiroo_db");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiroo Pet Store - Shop</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">
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
            <a href="homepage.php" class="navbar-item">Home</a>
            <a href="" class="navbar-item">Chat</a>
            <a href="" class="navbar-item">Shop</a>
            <a href="" class="navbar-item">User</a>
        </div>
    </nav>
    
    <!-- Shop Section -->
<main class="shop-container">
    <!-- Contoh 1 produk -->
    <?php
          $query = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id ASC");
            while ($row = mysqli_fetch_assoc($query)) {
            $image = $row['image'];
            $product = $row['name'];
            $price = $row['price'];
        ?>
    <a href="checkout.php?id=<?= $row['id'] ?>" class="product-card">
        <img src="../img/product-img/<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
        <div class="product-info">
            <h3 class="product-title"><?php echo htmlspecialchars($product); ?></h3>
            <p class="product-price"><?php echo htmlspecialchars($price); ?></p>
        </div><?php } ?>
    </a>

    
</main>


    <!-- Bottom Navbar for Mobile -->
    <footer class="bottom-navbar">
        <a href="homepage.php" class="bottom-nav-item">Home</a>
        <a href="#" class="bottom-nav-item">Chat</a>
        <a href="#" class="bottom-nav-item">Shop</a>
        <a href="#" class="bottom-nav-item">User</a>
    </footer>
</body>
</html>