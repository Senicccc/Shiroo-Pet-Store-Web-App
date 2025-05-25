<?php
session_start();
if (isset($_POST['login'])) {
    $koneksi = new mysqli("localhost", "root", "", "shiroo_db");

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $koneksi->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id']; 
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiroo Pet Store - Login</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/index.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Inria+Sans" rel="stylesheet" />
</head>

<body>
    <div class="login-card">
        <div class="logo-login">
            <img src="../img/logo-circle.png" alt="Logo Shiroo" />
        </div>

        <form method="post" action="">
            <h2>Login</h2>

            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <button type="submit" name="login">Login</button>

            <p class="register-link">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
        </form>
    </div>
</body>

</html>