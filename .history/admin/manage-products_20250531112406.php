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

// Handle tambah user
if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($username && $email && $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $koneksi->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwordHash);
        $stmt->execute();
        $stmt->close();

        header("Location: kelola-pengguna.php");
        exit;
    } else {
        $error = "Semua field harus diisi!";
    }
}

// Handle edit user
if (isset($_POST['edit_user'])) {
    $id = intval($_POST['id']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; 

    if ($username && $email && $id) {
        if ($password) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("UPDATE users SET username=?, email=?, password=? WHERE id=?");
            $stmt->bind_param("sssi", $username, $email, $passwordHash, $id);
        } else {
            $stmt = $koneksi->prepare("UPDATE users SET username=?, email=? WHERE id=?");
            $stmt->bind_param("ssi", $username, $email, $id);
        }
        $stmt->execute();
        $stmt->close();

        header("Location: kelola-pengguna.php");
        exit;
    } else {
        $error = "Field username dan email harus diisi!";
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: kelola-pengguna.php");
    exit;
}

// Ambil data users
$result = $koneksi->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Pengguna - Shiroo Pet Store</title>
    <link rel="stylesheet" href="css/kelola-pengguna.css">
</head>

<body>

    <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>

    <h1>Kelola Pengguna</h1>

    <?php if (!empty($error)) : ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <button class="btn-add" id="openAddModalBtn">+ Tambah Pengguna</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Terdaftar Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($users): ?>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['created_at'] ?></td>
                <td>
                    <button class="btn-edit"
                        onclick="openEditModal(<?= $user['id'] ?>, '<?= htmlspecialchars(addslashes($user['username'])) ?>', '<?= htmlspecialchars(addslashes($user['email'])) ?>')">Edit</button>
                    <a href="kelola-pengguna.php?delete=<?= $user['id'] ?>"
                        onclick="return confirm('Yakin hapus user ini?')" class="btn-delete">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center;">Belum ada data pengguna.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeAddModal">&times;</span>
            <h2>Tambah Pengguna Baru</h2>
            <form method="post" action="kelola-pengguna.php" autocomplete="off">
                <label for="add_username">Username</label>
                <input type="text" name="username" id="add_username" required />

                <label for="add_email">Email</label>
                <input type="email" name="email" id="add_email" required />

                <label for="add_password">Password</label>
                <input type="password" name="password" id="add_password" required />

                <button type="submit" name="add_user">Tambah Pengguna</button>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeEditModal">&times;</span>
            <h2>Edit Pengguna</h2>
            <form method="post" action="kelola-pengguna.php" autocomplete="off">
                <input type="hidden" name="id" id="edit_id" />

                <label for="edit_username">Username</label>
                <input type="text" name="username" id="edit_username" required />

                <label for="edit_email">Email</label>
                <input type="email" name="email" id="edit_email" required />

                <label for="edit_password">Password <small>(Kosongkan jika tidak ingin mengganti)</small></label>
                <input type="password" name="password" id="edit_password" />

                <button type="submit" name="edit_user">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
    // Modal handling for Tambah
    const addModal = document.getElementById('addModal');
    const openAddModalBtn = document.getElementById('openAddModalBtn');
    const closeAddModal = document.getElementById('closeAddModal');

    openAddModalBtn.onclick = () => addModal.style.display = 'block';
    closeAddModal.onclick = () => addModal.style.display = 'none';

    // Modal handling for Edit
    const editModal = document.getElementById('editModal');
    const closeEditModal = document.getElementById('closeEditModal');

    closeEditModal.onclick = () => editModal.style.display = 'none';

    // Function to open edit modal and populate form
    function openEditModal(id, username, email) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_password').value = '';
        editModal.style.display = 'block';
    }

    // Close modals when user clicks outside modal content
    window.onclick = function(event) {
        if (event.target == addModal) {
            addModal.style.display = "none";
        }
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    }
    </script>

</body>

</html>