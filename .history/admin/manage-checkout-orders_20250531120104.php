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

// Fungsi untuk cek apakah user_id ada di tabel users
function isValidUser($conn, $user_id) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Fungsi untuk cek apakah product_id ada di tabel products
function isValidProduct($conn, $product_id) {
    $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Tambah data
if (isset($_POST['add_order'])) {
    $order_id = trim($_POST['order_id']);
    $user_id = intval($_POST['user_id']);
    $product_id = intval($_POST['product_id']);
    $nama = trim($_POST['nama']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $jumlah = intval($_POST['jumlah']);
    $total = intval($_POST['total']);

    if ($order_id && $user_id && $product_id && $nama && $telepon && $alamat && $jumlah > 0 && $total > 0) {
        if (!isValidUser($koneksi, $user_id)) {
            $error = "User ID tidak valid.";
        } elseif (!isValidProduct($koneksi, $product_id)) {
            $error = "Product ID tidak valid.";
        } else {
            $stmt = $koneksi->prepare("INSERT INTO checkout_orders (order_id, user_id, product_id, nama, telepon, alamat, jumlah, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siisssii", $order_id, $user_id, $product_id, $nama, $telepon, $alamat, $jumlah, $total);
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: manage-checkout-orders.php");
                exit;
            } else {
                $error = "Gagal menambah data: " . $stmt->error;
            }
        }
    } else {
        $error = "Semua field harus diisi dengan benar!";
    }
}

// Edit data
if (isset($_POST['edit_order'])) {
    $id = intval($_POST['id']);
    $order_id = trim($_POST['order_id']);
    $user_id = intval($_POST['user_id']);
    $product_id = intval($_POST['product_id']);
    $nama = trim($_POST['nama']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $jumlah = intval($_POST['jumlah']);
    $total = intval($_POST['total']);

    if ($id && $order_id && $user_id && $product_id && $nama && $telepon && $alamat && $jumlah > 0 && $total > 0) {
        if (!isValidUser($koneksi, $user_id)) {
            $error = "User ID tidak valid.";
        } elseif (!isValidProduct($koneksi, $product_id)) {
            $error = "Product ID tidak valid.";
        } else {
            $stmt = $koneksi->prepare("UPDATE checkout_orders SET order_id=?, user_id=?, product_id=?, nama=?, telepon=?, alamat=?, jumlah=?, total=? WHERE id=?");
            $stmt->bind_param("siisssiii", $order_id, $user_id, $product_id, $nama, $telepon, $alamat, $jumlah, $total, $id);
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: manage-checkout-orders.php");
                exit;
            } else {
                $error = "Gagal mengubah data: " . $stmt->error;
            }
        }
    } else {
        $error = "Semua field harus diisi dengan benar!";
    }
}

// Hapus data
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $koneksi->prepare("DELETE FROM checkout_orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage-checkout-orders.php");
    exit;
}

// Ambil data checkout_orders
$result = $koneksi->query("SELECT * FROM checkout_orders ORDER BY created_at DESC");
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Checkout Orders - Shiroo Pet Store</title>
    <link rel="stylesheet" href="css/manage-checkout-orders.css" />
</head>

<body>

    <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>

    <h1>Kelola Checkout Orders</h1>

    <?php if (!empty($error)) : ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <button class="btn btn-add" id="openAddModalBtn">+ Tambah Checkout Order</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Product ID</th>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Created At</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders): ?>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= $order['user_id'] ?></td>
                <td><?= $order['product_id'] ?></td>
                <td><?= htmlspecialchars($order['nama']) ?></td>
                <td><?= htmlspecialchars($order['telepon']) ?></td>
                <td><?= nl2br(htmlspecialchars($order['alamat'])) ?></td>
                <td><?= $order['jumlah'] ?></td>
                <td><?= $order['total'] ?></td>
                <td><?= $order['created_at'] ?></td>
                <td>
                    <button class="btn btn-edit" onclick="openEditModal(
                                        <?= $order['id'] ?>,
                                        '<?= htmlspecialchars(addslashes($order['order_id'])) ?>',
                                        <?= $order['user_id'] ?>,
                                        <?= $order['product_id'] ?>,
                                        '<?= htmlspecialchars(addslashes($order['nama'])) ?>',
                                        '<?= htmlspecialchars(addslashes($order['telepon'])) ?>',
                                        '<?= htmlspecialchars(addslashes($order['alamat'])) ?>',
                                        <?= $order['jumlah'] ?>,
                                        <?= $order['total'] ?>
                                    )">Edit</button>
                    <a href="manage-checkout-orders.php?delete=<?= $order['id'] ?>"
                        onclick="return confirm('Yakin hapus order ini?')" class="btn-delete">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="11" style="text-align:center;">Belum ada data checkout order.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Modal Tambah -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeAddModal">&times;</span>
            <h2>Tambah Checkout Order Baru</h2>
            <form method="post" action="manage-checkout-orders.php" autocomplete="off">
                <label for="add_order_id">Order ID</label>
                <input type="text" name="order_id" id="add_order_id" required />

                <label for="add_user_id">User ID</label>
                <input type="number" name="user_id" id="add_user_id" required />

                <label for="add_product_id">Product ID</label>
                <input type="number" name="product_id" id="add_product_id" required />

                <label for="add_nama">Nama</label>
                <input type="text" name="nama" id="add_nama" required />

                <label for="add_telepon">Telepon</label>
                <input type="text" name="telepon" id="add_telepon" required />

                <label for="add_alamat">Alamat</label>
                <textarea name="alamat" id="add_alamat" rows="3" required></textarea>

                <label for="add_jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="add_jumlah" min="1" required />

                <label for="add_total">Total</label>
                <input type="number" name="total" id="add_total" min="1" required />

                <button type="submit" name="add_order">Tambah Checkout Order</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeEditModal">&times;</span>
            <h2>Edit Checkout Order</h2>
            <form method="post" action="manage-checkout-orders.php" autocomplete="off">
                <input type="hidden" name="id" id="edit_id" />

                <label for="edit_order_id">Order ID</label>
                <input type="text" name="order_id" id="edit_order_id" required />

                <label for="edit_user_id">User ID</label>
                <input type="number" name="user_id" id="edit_user_id" required />

                <label for="edit_product_id">Product ID</label>
                <input type="number" name="product_id" id="edit_product_id" required />

                <label for="edit_nama">Nama</label>
                <input type="text" name="nama" id="edit_nama" required />

                <label for="edit_telepon">Telepon</label>
                <input type="text" name="telepon" id="edit_telepon" required />

                <label for="edit_alamat">Alamat</label>
                <textarea name="alamat" id="edit_alamat" rows="3" required></textarea>

                <label for="edit_jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="edit_jumlah" min="1" required />

                <label for="edit_total">Total</label>
                <input type="number" name="total" id="edit_total" min="1" required />

                <button type="submit" name="edit_order">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
    // Modal control
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    const openAddModalBtn = document.getElementById('openAddModalBtn');
    const closeAddModal = document.getElementById('closeAddModal');
    const closeEditModal = document.getElementById('closeEditModal');

    openAddModalBtn.onclick = () => {
        addModal.style.display = 'block';
    }
    closeAddModal.onclick = () => {
        addModal.style.display = 'none';
    }
    closeEditModal.onclick = () => {
        editModal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == addModal) {
            addModal.style.display = "none";
        }
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    }

    // Isi modal edit dengan data order yang dipilih
    function openEditModal(id, order_id, user_id, product_id, nama, telepon, alamat, jumlah, total) {
        editModal.style.display = 'block';

        document.getElementById('edit_id').value = id;
        document.getElementById('edit_order_id').value = order_id;
        document.getElementById('edit_user_id').value = user_id;
        document.getElementById('edit_product_id').value = product_id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_telepon').value = telepon;
        document.getElementById('edit_alamat').value = alamat;
        document.getElementById('edit_jumlah').value = jumlah;
        document.getElementById('edit_total').value = total;
    }
    </script>

</body>

</html>