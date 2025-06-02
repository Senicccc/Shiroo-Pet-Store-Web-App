<?php
session_start();
if (isset($_POST['login'])) {
    $koneksi = new mysqli("localhost", "root", "", "shiroo_db");

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $koneksi->prepare("SELECT * FROM admin WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id']; 
        header("Location: dashboard.php"); 
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shiroo Pet Store - Admin Login</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/index.css" />
</head>

<body>
    <div class="login-card">
        <h2>Admin Login</h2>

        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form method="post" action="">
            <label for="username">Username</label>
            <input type="text" name="username" required />

            <label for="password">Password</label>
            <input type="password" name="password" required />

            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>

</html>