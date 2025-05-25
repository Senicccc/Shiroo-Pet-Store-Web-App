<?php
session_start();

// Cek apakah form dikirim dengan benar (via POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Akses tidak valid.";
    exit;
}

// Debug POST (opsional, bisa dihapus nanti)
var_dump($_POST);

// Ambil data dari form booking
$bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
$tanggal = $_POST['tanggal'] ?? '';
$nama = $_POST['nama'] ?? '';
$telp = $_POST['telp'] ?? '';
$gender_kucing = $_POST['gender_kucing'] ?? '';

// Validasi minimal
if ($bookingId === 0 || empty($tanggal) || empty($nama) || empty($telp) || empty($gender_kucing)) {
    echo "Data tidak lengkap. Silakan isi semua field.";
    exit;
}

// Koneksi database
$koneksi = new mysqli("localhost", "root", "", "shiroo_db");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil harga dari database berdasarkan booking_id
$stmt = $koneksi->prepare("SELECT harga FROM booking_detail WHERE id = ?");
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    $harga = (int) $data['harga'];
} else {
    echo "Layanan tidak ditemukan di database.";
    exit;
}

// Generate order_id unik
$order_id = uniqid('order_');

// Simpan semua data ke session
$_SESSION['booking_data'] = [
    'order_id'        => $order_id,
    'booking_id'      => $bookingId,
    'tanggal_booking' => $tanggal,
    'nama_pelanggan'  => $nama,
    'no_telp'         => $telp,
    'gender_kucing'   => $gender_kucing,
    'harga'           => $harga
];

// Redirect ke halaman pembayaran
header("Location: payment-booking.php");
exit;
?>