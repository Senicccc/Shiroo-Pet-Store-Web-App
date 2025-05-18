<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiroo Pet Store - Register</title>

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

        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="submit">Daftar</button>

            <p class="register-link">Sudah punya akun? <a href="../index.php">Login disini!</a></p>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            $conn = new mysqli("localhost", "root", "", "shiroo_db");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $username = $conn->real_escape_string($_POST['username']);
            $email = $conn->real_escape_string($_POST['email']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            if ($password !== $confirm) {
                echo "<p class='error'>Password tidak cocok.</p>";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Registrasi berhasil.'); location='index.php';</script>";
                } else {
                    echo "<p class='error'>Gagal mendaftar: " . $conn->error . "</p>";
                }
            }

            $conn->close();
        }
        ?>
    </div>
</body>

</html>