<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shiroo Pet Store - Homepage</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/homepage.css?v=1.0" />


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
            <a href="" class="navbar-item">Home</a>
            <a href="" class="navbar-item">Chat</a>
            <a href="shop.php" class="navbar-item">Shop</a>
            <a href="" class="navbar-item">User</a>
        </div>
    </nav>

    <!-- Name Navbar -->
    <div class="mobile-name-navbar">
        Halo, Ichsan
    </div>

    <!-- Menu Section -->
    <div class="menu-bg">
        <section class="menu">
            <div class="menu-banner">
                <!-- <img src="../img/banner-1.png" alt="banner" /> -->
            </div>
            <div class="menu-buttons">
                <a href="booking-details.php?id=1" class="menu-button">
                    Bath Your Cat <img src="../img/bath-icon.png" alt="Bath Icon" />
                </a>
                <a href="booking-details.php?id=2" class="menu-button">
                    Grooming Cat <img src="../img/grooming-icon.png" alt="Grooming Icon" />
                </a>
                <a href="#" class="menu-button">
                    Cat Equipment <img src="../img/equipment-icon.png" alt="Equipment Icon" />
                </a>
                <a href="booking-details.php?id=3" class="menu-button">
                    Cat Hotel Care <img src="../img/hotel-icon.png" alt="Hotel Icon" />
                </a>
            </div>
        </section>
    </div>


    <!-- Bottom Navbar for Mobile -->
    <footer class="bottom-navbar">
        <a href="#" class="bottom-nav-item">Home</a>
        <a href="#" class="bottom-nav-item">Chat</a>
        <a href="shop.php" class="bottom-nav-item">Shop</a>
        <a href="#" class="bottom-nav-item">User</a>
    </footer>
</body>

</html>