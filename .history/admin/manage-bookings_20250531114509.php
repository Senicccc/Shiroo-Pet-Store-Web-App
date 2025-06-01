<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "shiroo_db");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (isset($_POST['add_booking'])) {
    $order_id = trim($_POST['order_id']);
    $booking_id = intval($_POST['booking_id']);
    $tanggal_booking = $_POST['tanggal_booking'];
    $nama_pelanggan = trim($_POST['nama_pelanggan']);
    $no_telp = trim($_POST['no_telp']);
    $gender_kucing = $_POST['gender_kucing'];
    $harga = intval($_POST['harga']);
    $user_id = $_SESSION['admin_id'];

    if ($order_id && $booking_id && $tanggal_booking && $nama_pelanggan && $no_telp && $gender_kucing && $harga) {
        $stmt = $koneksi->prepare("INSERT INTO booking_transactions (user_id, order_id, booking_id, tanggal_booking, nama_pelanggan, no_telp, gender_kucing, harga) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isissssi", $user_id, $order_id, $booking_id, $tanggal_booking, $nama_pelanggan, $no_telp, $gender_kucing, $harga);
        $stmt->execute();
        $stmt->close();

        header("Location: manage-bookings.php");
        exit;
    } else {
        $error = "Semua field harus diisi!";
    }
}

if (isset($_POST['edit_booking'])) {
    $id = intval($_POST['id']);
    $order_id = trim($_POST['order_id']);
    $booking_id = intval($_POST['booking_id']);
    $tanggal_booking = $_POST['tanggal_booking'];
    $nama_pelanggan = trim($_POST['nama_pelanggan']);
    $no_telp = trim($_POST['no_telp']);
    $gender_kucing = $_POST['gender_kucing'];
    $harga = intval($_POST['harga']);

    if ($id && $order_id && $booking_id && $tanggal_booking && $nama_pelanggan && $no_telp && $gender_kucing && $harga) {
        $stmt = $koneksi->prepare("UPDATE booking_transactions SET order_id=?, booking_id=?, tanggal_booking=?, nama_pelanggan=?, no_telp=?, gender_kucing=?, harga=? WHERE id=?");
        $stmt->bind_param("sissssii", $order_id, $booking_id, $tanggal_booking, $nama_pelanggan, $no_telp, $gender_kucing, $harga, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: manage-bookings.php");
        exit;
    } else {
        $error = "Semua field harus diisi!";
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $koneksi->prepare("DELETE FROM booking_transactions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage-bookings.php");
    exit;
}

$result = $koneksi->query("SELECT * FROM booking_transactions ORDER BY created_at DESC");
$bookings = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Booking - Shiroo Pet Store</title>
    <link rel="stylesheet" href="css/manage-bookings.css">
</head>

<body>

    <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>
    <h1>Kelola Booking</h1>

    <?php if (!empty($error)) : ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <button class="btn-add" id="openAddModalBtn">+ Tambah Booking</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Booking ID</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>No. Telp</th>
                <th>Gender Kucing</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($bookings): ?>
            <?php foreach ($bookings as $b): ?>
            <tr>
                <td><?= $b['id'] ?></td>
                <td><?= htmlspecialchars($b['order_id']) ?></td>
                <td><?= $b['booking_id'] ?></td>
                <td><?= $b['tanggal_booking'] ?></td>
                <td><?= htmlspecialchars($b['nama_pelanggan']) ?></td>
                <td><?= htmlspecialchars($b['no_telp']) ?></td>
                <td><?= $b['gender_kucing'] ?></td>
                <td><?= $b['harga'] ?></td>
                <td>
                    <button class="btn-edit"
                        onclick="openEditModal(<?= $b['id'] ?>, '<?= addslashes($b['order_id']) ?>', <?= $b['booking_id'] ?>, '<?= $b['tanggal_booking'] ?>', '<?= addslashes($b['nama_pelanggan']) ?>', '<?= addslashes($b['no_telp']) ?>', '<?= $b['gender_kucing'] ?>', <?= $b['harga'] ?>)">Edit</button>
                    <a href="manage-bookings.php?delete=<?= $b['id'] ?>"
                        onclick="return confirm('Yakin hapus booking ini?')" class="btn-delete">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="9" style="text-align:center;">Belum ada data booking.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeAddModal">&times;</span>
            <h2>Tambah Booking</h2>
            <form method="post" action="manage-bookings.php">
                <label>Order ID</label><input type="text" name="order_id" required />
                <label>Booking ID</label><input type="number" name="booking_id" required />
                <label>Tanggal Booking</label><input type="date" name="tanggal_booking" required />
                <label>Nama Pelanggan</label><input type="text" name="nama_pelanggan" required />
                <label>No. Telepon</label><input type="text" name="no_telp" required />
                <label>Gender Kucing</label>
                <select name="gender_kucing" required>
                    <option value="">Pilih</option>
                    <option value="Jantan">Jantan</option>
                    <option value="Betina">Betina</option>
                </select>
                <label>Harga</label><input type="number" name="harga" required />
                <button type="submit" name="add_booking">Tambah</button>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeEditModal">&times;</span>
            <h2>Edit Booking</h2>
            <form method="post" action="manage-bookings.php">
                <input type="hidden" name="id" id="edit_id" />
                <label>Order ID</label><input type="text" name="order_id" id="edit_order_id" required />
                <label>Booking ID</label><input type="number" name="booking_id" id="edit_booking_id" required />
                <label>Tanggal Booking</label><input type="date" name="tanggal_booking" id="edit_tanggal_booking"
                    required />
                <label>Nama Pelanggan</label><input type="text" name="nama_pelanggan" id="edit_nama_pelanggan"
                    required />
                <label>No. Telepon</label><input type="text" name="no_telp" id="edit_no_telp" required />
                <label>Gender Kucing</label>
                <select name="gender_kucing" id="edit_gender_kucing" required>
                    <option value="Jantan">Jantan</option>
                    <option value="Betina">Betina</option>
                </select>
                <label>Harga</label><input type="number" name="harga" id="edit_harga" required />
                <button type="submit" name="edit_booking">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
    const addModal = document.getElementById('addModal');
    document.getElementById('openAddModalBtn').onclick = () => addModal.style.display = 'block';
    document.getElementById('closeAddModal').onclick = () => addModal.style.display = 'none';

    const editModal = document.getElementById('editModal');
    document.getElementById('closeEditModal').onclick = () => editModal.style.display = 'none';

    function openEditModal(id, order_id, booking_id, tanggal_booking, nama_pelanggan, no_telp, gender_kucing, harga) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_order_id').value = order_id;
        document.getElementById('edit_booking_id').value = booking_id;
        document.getElementById('edit_tanggal_booking').value = tanggal_booking;
        document.getElementById('edit_nama_pelanggan').value = nama_pelanggan;
        document.getElementById('edit_no_telp').value = no_telp;
        document.getElementById('edit_gender_kucing').value = gender_kucing;
        document.getElementById('edit_harga').value = harga;
        editModal.style.display = 'block';
    }

    window.onclick = function(event) {
        if (event.target == addModal) addModal.style.display = "none";
        if (event.target == editModal) editModal.style.display = "none";
    }
    </script>

</body>

</html>