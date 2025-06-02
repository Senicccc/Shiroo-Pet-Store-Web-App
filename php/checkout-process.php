<?php
session_start();
include 'auth.php';
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];
    $jumlah = (int)$_POST['jumlah'];
    $user_id = $_SESSION['user_id'];

    // Ambil nama, harga, dan stok produk
    $stmt = $conn->prepare("SELECT name, price, stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($product_name, $product_price, $stock);
    $stmt->fetch();
    $stmt->close();
    
    if ($stock < $jumlah) {
        header("Location: checkout.php?error=stok_kurang&tersedia=$stock");
        exit;
    }


    $total = $product_price * $jumlah;
    $order_id = 'ORDER-' . time();

    // Update stok produk
    $new_stock = $stock - $jumlah;
    $stmt_update = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
    $stmt_update->bind_param("ii", $new_stock, $product_id);
    $stmt_update->execute();
    $stmt_update->close();

    $_SESSION['checkout_data'] = [
        'order_id' => $order_id,
        'product_id' => $product_id,
        'nama_pelanggan' => $nama,
        'no_telp' => $telepon,
        'alamat' => $alamat,
        'jumlah' => $jumlah,
        'harga' => $total,
        'product_name' => $product_name,
        'user_id' => $user_id,
    ];

    header("Location: payment-product.php");
    exit();
}
?>