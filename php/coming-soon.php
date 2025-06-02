<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: php/login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fitur Dalam Pengembangan</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css?v=1.0" />
    <link rel="stylesheet" href="../css/coming-soon.css?v=1.0" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Inria+Sans" rel="stylesheet" />
</head>
<body>
    <div class="under-construction-wrapper">
        <h1 class="uc-icon">🚧</h1>
        <h2 class="uc-title">Fitur Sedang Dalam Pengembangan</h2>
        <p class="uc-message">
            Hai, <?php echo htmlspecialchars($user['username']); ?>!<br>
            Fitur ini belum tersedia saat ini. Kami sedang mengembangkannya untukmu 🛠️
        </p>
        <a href="../index.php" class="menu-button uc-button">Kembali ke Beranda</a>
    </div>
</body>
</html>
