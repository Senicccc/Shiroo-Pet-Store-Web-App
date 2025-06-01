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

// Ambil data products
$result = $koneksi->query("SELECT id, name, `desc`, price, stock, category_id, image FROM products ORDER BY id DESC");
$products = $result->fetch_all(MYSQLI_ASSOC);
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
                <label for="add_password">Password</label>
                <input type="password" name="password" id="add_password" required />
                <input type="email" name="email" id="add_email" required />
                <button type="submit" name="add_user">Tambah Pengguna</button>
            </form>bel for="add_password">Password</label>
        </div>  <input type="password" name="password" id="add_password" required />
    </div>
                <button type="submit" name="add_user">Tambah Pengguna</button>
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeEditModal">&times;</span>
            <h2>Edit Pengguna</h2>
            <form method="post" action="kelola-pengguna.php" autocomplete="off">
                <input type="hidden" name="id" id="edit_id" />
            <span class="close" id="closeEditModal">&times;</span>
                <label for="edit_username">Username</label>
                <input type="text" name="username" id="edit_username" required />
                <input type="hidden" name="id" id="edit_id" />
                <label for="edit_email">Email</label>
                <input type="email" name="email" id="edit_email" required />
                <input type="text" name="username" id="edit_username" required />
                <label for="edit_password">Password <small>(Kosongkan jika tidak ingin mengganti)</small></label>
                <input type="password" name="password" id="edit_password" />
                <input type="email" name="email" id="edit_email" required />
                <button type="submit" name="edit_user">Simpan Perubahan</button>
            </form>bel for="edit_password">Password <small>(Kosongkan jika tidak ingin mengganti)</small></label>
        </div>  <input type="password" name="password" id="edit_password" />
    </div>
                <button type="submit" name="edit_user">Simpan Perubahan</button>
    <h1>Kelola Produk</h1></form>

    <button class="btn-add" id="openAddProductModalBtn">+ Tambah Produk</button>

    <table>
        <thead>    // Modal handling for Tambah
            <tr>
                <th>ID</th>tn');
                <th>Nama</th>    const closeAddModal = document.getElementById('closeAddModal');
                <th>Deskripsi</th>
                <th>Harga</th>= 'block';
                <th>Stok</th>
                <th>Kategori</th>
                <th>Gambar</th>
                <th>Aksi</th>    const editModal = document.getElementById('editModal');
            </tr>closeEditModal');
        </thead>
        <tbody>play = 'none';
            <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>.value = id;
                <td><?= htmlspecialchars($product['name']) ?></td>   document.getElementById('edit_username').value = username;
                <td><?= htmlspecialchars($product['desc']) ?></td>        document.getElementById('edit_email').value = email;
                <td><?= number_format($product['price'], 2) ?></td>';
                <td><?= $product['stock'] ?></td>ck';
                <td><?= $product['category_id'] ?></td>
                <td><img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="50"></td>
                <td>ose modals when user clicks outside modal content
                    <button class="btn-edit"
                        onclick="openEditModal(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['name'])) ?>', '<?= htmlspecialchars(addslashes($product['desc'])) ?>', <?= $product['price'] ?>, <?= $product['stock'] ?>, <?= $product['category_id'] ?>, '<?= htmlspecialchars(addslashes($product['image'])) ?>')">Edit</button>
                    <a href="kelola-produk.php?delete=<?= $product['id'] ?>"   addModal.style.display = "none";
                        onclick="return confirm('Yakin hapus produk ini?')" class="btn-delete">Hapus</a>   }
                </td>vent.target == editModal) {
            </tr>            editModal.style.display = "none";
            <?php endforeach; ?> }
            <?php else: ?>    }
            <tr>cript>














































































































        document.getElementById('edit_product_category').value = category_id;        document.getElementById('edit_product_stock').value = stock;        document.getElementById('edit_product_price').value = price;        document.getElementById('edit_product_desc').value = desc;        document.getElementById('edit_product_name').value = name;        document.getElementById('edit_product_id').value = id;    function openEditModal(id, name, desc, price, stock, category_id, image) {    // Function to open edit produk modal and populate form    closeEditProductModal.onclick = () => editProductModal.style.display = 'none';    const closeEditProductModal = document.getElementById('closeEditProductModal');    const editProductModal = document.getElementById('editProductModal');    // Modal handling for Edit Produk    closeAddProductModal.onclick = () => addProductModal.style.display = 'none';    openAddProductModalBtn.onclick = () => addProductModal.style.display = 'block';    const closeAddProductModal = document.getElementById('closeAddProductModal');    const openAddProductModalBtn = document.getElementById('openAddProductModalBtn');    const addProductModal = document.getElementById('addProductModal');    // Modal handling for Tambah Produk    }        editModal.style.display = 'block';        document.getElementById('edit_password').value = '';        document.getElementById('edit_email').value = email;        document.getElementById('edit_username').value = username;        document.getElementById('edit_id').value = id;    function openEditModal(id, username, email) {    // Function to open edit modal and populate form    closeEditModal.onclick = () => editModal.style.display = 'none';    const closeEditModal = document.getElementById('closeEditModal');    const editModal = document.getElementById('editModal');    // Modal handling for Edit    closeAddModal.onclick = () => addModal.style.display = 'none';    openAddModalBtn.onclick = () => addModal.style.display = 'block';    const closeAddModal = document.getElementById('closeAddModal');    const openAddModalBtn = document.getElementById('openAddModalBtn');    const addModal = document.getElementById('addModal');    // Modal handling for Tambah    <script>    </div>        </div>            </form>                <button type="submit" name="edit_product">Simpan Perubahan</button>                <input type="file" name="image" id="edit_product_image" accept="image/*" />                <label for="edit_product_image">Gambar Produk <small>(Kosongkan jika tidak ingin mengganti)</small></label>                <input type="text" name="category_id" id="edit_product_category" required />                <label for="edit_product_category">Kategori Produk</label>                <input type="number" name="stock" id="edit_product_stock" required />                <label for="edit_product_stock">Stok Produk</label>                <input type="number" name="price" id="edit_product_price" step="0.01" required />                <label for="edit_product_price">Harga Produk</label>                <textarea name="desc" id="edit_product_desc" required></textarea>                <label for="edit_product_desc">Deskripsi Produk</label>                <input type="text" name="name" id="edit_product_name" required />                <label for="edit_product_name">Nama Produk</label>                <input type="hidden" name="id" id="edit_product_id" />            <form method="post" action="kelola-produk.php" enctype="multipart/form-data" autocomplete="off">            <h2>Edit Produk</h2>            <span class="close" id="closeEditProductModal">&times;</span>        <div class="modal-content">    <div id="editProductModal" class="modal">    </div>        </div>            </form>                <button type="submit" name="add_product">Tambah Produk</button>                <input type="file" name="image" id="add_product_image" accept="image/*" required />                <label for="add_product_image">Gambar Produk</label>                <input type="text" name="category_id" id="add_product_category" required />                <label for="add_product_category">Kategori Produk</label>                <input type="number" name="stock" id="add_product_stock" required />                <label for="add_product_stock">Stok Produk</label>                <input type="number" name="price" id="add_product_price" step="0.01" required />                <label for="add_product_price">Harga Produk</label>                <textarea name="desc" id="add_product_desc" required></textarea>                <label for="add_product_desc">Deskripsi Produk</label>                <input type="text" name="name" id="add_product_name" required />                <label for="add_product_name">Nama Produk</label>            <form method="post" action="kelola-produk.php" enctype="multipart/form-data" autocomplete="off">            <h2>Tambah Produk Baru</h2>            <span class="close" id="closeAddProductModal">&times;</span>        <div class="modal-content">    <div id="addProductModal" class="modal">    </table>        </tbody>            <?php endif; ?>            </tr>                <td colspan="8" style="text-align:center;">Belum ada data produk.</td>
</body>

</html>




















</html></body>    </script>    }        }            editProductModal.style.display = "none";        if (event.target == editProductModal) {        }            addProductModal.style.display = "none";        if (event.target == addProductModal) {        }            editModal.style.display = "none";        if (event.target == editModal) {        }            addModal.style.display = "none";        if (event.target == addModal) {    window.onclick = function(event) {    // Close modals when user clicks outside modal content    }        editProductModal.style.display = 'block';        document.getElementById('edit_product_image').value = '';        document.getElementById('edit_product_category').value = category_id;        document.getElementById('edit_product_stock').value = stock;        document.getElementById('edit_product_price').value = price;        document.getElementById('edit_product_desc').value = desc;        document.getElementById('edit_product_name').value = name;        document.getElementById('edit_product_id').value = id;    function openEditModal(id, name, desc, price, stock, category_id, image) {    // Function to open edit produk modal and populate form    closeEditProductModal.onclick = () => editProductModal.style.display = 'none';    const closeEditProductModal = document.getElementById('closeEditProductModal');    const editProductModal = document.getElementById('editProductModal');    // Modal handling for Edit Produk    closeAddProductModal.onclick = () => addProductModal.style.display = 'none';    openAddProductModalBtn.onclick = () => addProductModal.style.display = 'block';    const closeAddProductModal = document.getElementById('closeAddProductModal');    const openAddProductModalBtn = document.getElementById('openAddProductModalBtn');    const addProductModal = document.getElementById('addProductModal');    // Modal handling for Tambah Produk    }        editModal.style.display = 'block';        document.getElementById('edit_password').value = '';        document.getElementById('edit_email').value = email;        document.getElementById('edit_username').value = username;        document.getElementById('edit_id').value = id;    function openEditModal(id, username, email) {    // Function to open edit modal and populate form    closeEditModal.onclick = () => editModal.style.display = 'none';    const closeEditModal = document.getElementById('closeEditModal');    const editModal = document.getElementById('editModal');    // Modal handling for Edit    closeAddModal.onclick = () => addModal.style.display = 'none';    openAddModalBtn.onclick = () => addModal.style.display = 'block';    const closeAddModal = document.getElementById('closeAddModal');    const openAddModalBtn = document.getElementById('openAddModalBtn');    const addModal = document.getElementById('addModal');    // Modal handling for Tambah    <script>    </div>        </div>            </form>                <button type="submit" name="edit_product">Simpan Perubahan</button>                <input type="file" name="image" id="edit_product_image" accept="image/*" />                <label for="edit_product_image">Gambar Produk <small>(Kosongkan jika tidak ingin mengganti)</small></label>                <input type="text" name="category_id" id="edit_product_category" required />                <label for="edit_product_category">Kategori Produk</label>                <input type="number" name="stock" id="edit_product_stock" required />                <label for="edit_product_stock">Stok Produk</label>                <input type="number" name="price" id="edit_product_price" step="0.01" required />                <label for="edit_product_price">Harga Produk</label>                <textarea name="desc" id="edit_product_desc" required></textarea>                <label for="edit_product_desc">Deskripsi Produk</label>                <input type="text" name="name" id="edit_product_name" required />                <label for="edit_product_name">Nama Produk</label>                <input type="hidden" name="id" id="edit_product_id" />            <form method="post" action="kelola-produk.php" enctype="multipart/form-data" autocomplete="off">            <h2>Edit Produk</h2>            <span class="close" id="closeEditProductModal">&times;</span>        <div class="modal-content">    <div id="editProductModal" class="modal">    </div>        </div>            </form>                <button type="submit" name="add_product">Tambah Produk</button>                <input type="file" name="image" id="add_product_image" accept="image/*" required />                <label for="add_product_image">Gambar Produk</label>                <input type="text" name="category_id" id="add_product_category" required />                <label for="add_product_category">Kategori Produk</label>                <input type="number" name="stock" id="add_product_stock" required />                <label for="add_product_stock">Stok Produk</label>                <input type="number" name="price" id="add_product_price" step="0.01" required />                <label for="add_product_price">Harga Produk</label>                <textarea name="desc" id="add_product_desc" required></textarea>                <label for="add_product_desc">Deskripsi Produk</label>                <input type="text" name="name" id="add_product_name" required />                <label for="add_product_name">Nama Produk</label>            <form method="post" action="kelola-produk.php" enctype="multipart/form-data" autocomplete="off">            <h2>Tambah Produk Baru</h2>            <span class="close" id="closeAddProductModal">&times;</span>        <div class="modal-content">    <div id="addProductModal" class="modal">    </table>        </tbody>            <?php endif; ?>            </tr>                <td colspan="8" style="text-align:center;">Belum ada data produk.</td>
</body>

</html>