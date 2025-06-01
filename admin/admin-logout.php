<?php
session_start();

if (isset($_POST['logout'])) {
    // Hapus semua data session admin
    session_unset();
    session_destroy();

    // Redirect ke halaman login
    header("Location: login.php");
    exit;
} else {
    // Jika akses langsung tanpa POST logout, arahkan juga ke login
    header("Location: login.php");
    exit;
}
?>