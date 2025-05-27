<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $npm = $_POST['npm'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Upload Foto
    $foto = '';
    if ($_FILES['foto']['name']) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = uniqid() . '.' . $ext;
        $target = "uploads/" . $foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $target);
    }

    // Simpan ke DB
    $stmt = mysqli_prepare($conn, "INSERT INTO users (nama, npm, password, foto) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $nama, $npm, $password, $foto);
    mysqli_stmt_execute($stmt);

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Daftar User Baru</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label>NPM</label>
            <input type="text" name="npm" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Upload Foto Profil</label>
            <input type="file" name="foto" class="form-control-file" accept="image/*" required>
        </div>
        <button class="btn btn-success">Daftar</button>
        <a href="login.php" class="btn btn-secondary">Kembali ke Login</a>
    </form>
</body>
</html>
