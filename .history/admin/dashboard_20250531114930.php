<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "shiroo_db");
$admin_id = $_SESSION['admin_id'];
$query = $koneksi->prepare("SELECT username FROM admin WHERE id = ?");
$query->bind_param("i", $admin_id);
$query->execute();
$result = $query->get_result();
$admin = $result->fetch_assoc();
$username = $admin ? $admin['username'] : "Admin";

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin - Shiroo Pet Store</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
    form.logout-card {
        display: inline-block;
        margin-top: 20px;
    }

    form.logout-card button {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        color: inherit;
        padding: 0;
        font-family: inherit;
    }

    form.logout-card button .card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 150px;
        height: 100px;
        border: 1px solid #ccc;
        border-radius: 10px;
        text-decoration: none;
        color: black;
        transition: background-color 0.3s ease;
    }

    form.logout-card button .card:hover {
        background-color: #f5f5f5;
    }

    form.logout-card button .card-icon {
        font-size: 2.5rem;
        margin-bottom: 8px;
    }
    </style>
</head>

<body>
    <header>
        <h1>Shiroo Admin Panel</h1>
        <div>
            <form style="display:inline;" method="post" action="admin_logout.php">
                <button type="submit" class="logout-btn" name="logout">🔒 Logout</button>
            </form>
        </div>
    </header>

    <main>
        <h2>Dashboard</h2>
        <div class="cards">
            <a href="manage-users.php" class="card" title="Kelola Pengguna">
                <div class="card-icon">👥</div>
                Kelola Pengguna
            </a>
            <a href="manage-bookings.php" class="card" title="Kelola Pemesanan">
                <div class="card-icon">📅</div>
                Kelola Pemesanan
            </a>
            <a href="manage-products.php" class="card" title="Kelola Stok Barang">
                <div class="card-icon">📦</div>
                Kelola Stok Barang
            </a>
            <a href="manage-checkout-orders.php" class="card" title="Kelola Checkout Order">
                <div class="card-icon">🛒</div>
                Kelola Checkout Order
            </a>
            <a href="chat-customers.php" class="card" title="Chat dengan Pelanggan">
                <div class="card-icon">💬</div>
                Chat dengan Pelanggan
            </a>
        </div>

        <form class="logout-card" method="post" action="admin_logout.php">
            <button type="submit" name="logout">
                <div class="card" title="Logout">
                    <div class="card-icon">🔒</div>
                    Logout
                </div>
            </button>
        </form>
    </main>
</body>

</html>