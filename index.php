<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: php/login.php");
    exit();
}

include 'php/db.php';

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shiroo Pet Store - Homepage</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/homepage.css?v=1.0" />


    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Inria+Sans" rel="stylesheet" />
</head>

<body>
    <!-- Navbar -->
    <nav class="top-navbar">
        <div class="navbar-left">
            <img src="img/logo-circle.png" alt="Shiroo Logo" class="logo" />
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        </div>
        <div class="navbar-right" id="navbarMenu">
            <a href="" class="navbar-item">Home</a>
            <a href="php/coming-soon.php" class="navbar-item">Chat</a>
            <a href="php/shop.php" class="navbar-item">Shop</a>
            <a href="php/user.php" class="navbar-item">User</a>

        </div>
    </nav>

    <div class="mobile-name-navbar">
        Halo, <?php echo htmlspecialchars($user['username']); ?>
    </div>


    <!-- Menu Section -->
    <div class="menu-bg">
        <section class="menu">
            <div class="menu-buttons">
                <a href="php/booking-details.php?id=1" class="menu-button">
                    Bath Your Cat <img src="img/bath-icon.png" alt="Bath Icon" />
                </a>
                <a href="php/booking-details.php?id=2" class="menu-button">
                    Grooming Cat <img src="img/grooming-icon.png" alt="Grooming Icon" />
                </a>
                <a href="php/shop.php" class="menu-button">
                    Cat Equipment <img src="img/equipment-icon.png" alt="Equipment Icon" />
                </a>
                <a href="php/booking-details.php?id=3" class="menu-button">
                    Cat Hotel Care <img src="img/hotel-icon.png" alt="Hotel Icon" />
                </a>
            </div>
        </section>
    </div>


    <!-- Bottom Navbar for Mobile -->
    <footer class="bottom-navbar">
        <a href="index.php" class="bottom-nav-item">Home</a>
        <a href="php/coming-soon.php" class="bottom-nav-item">Chat</a>
        <a href="php/shop.php" class="bottom-nav-item">Shop</a>
        <a href="php/user.php" class="bottom-nav-item">User</a>
    </footer>
</body>

</html>