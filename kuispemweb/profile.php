<?php
session_start();
include "db.php";

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($result);

// Update profil jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $npm = mysqli_real_escape_string($conn, $_POST['npm']);

    // Cek upload foto baru
    if (!empty($_FILES['foto']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fotoType = $_FILES['foto']['type'];

        if (in_array($fotoType, $allowedTypes)) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $newName = time() . "_" . uniqid() . "." . $ext;
            $tmp = $_FILES['foto']['tmp_name'];

            // Buat folder uploads jika belum ada
            if (!file_exists('uploads')) {
                mkdir('uploads', 0755, true);
            }

            move_uploaded_file($tmp, "uploads/" . $newName);
            $query = "UPDATE users SET nama='$nama', npm='$npm', foto='$newName' WHERE id='$id'";
        } else {
            // Jika tipe file tidak diizinkan
            echo "<script>alert('Format foto tidak valid. Hanya JPG/PNG diperbolehkan.');</script>";
            $query = "UPDATE users SET nama='$nama', npm='$npm' WHERE id='$id'";
        }
    } else {
        $query = "UPDATE users SET nama='$nama', npm='$npm' WHERE id='$id'";
    }

    mysqli_query($conn, $query);
    header("Location: profile.php");
    exit;
}

// Path foto profil
$fotopath = "uploads/" . $user['foto'];
if (empty($user['foto']) || !file_exists($fotopath)) {
    $fotopath = "uploads/default.png";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Saya</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .profile-pic {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="container mt-5">
    <h2 class="mb-4">Profil Saya</h2>

    <img src="<?= htmlspecialchars($fotopath) ?>" alt="Foto Profil" class="profile-pic"><br>
    <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama']) ?></p>
    <p><strong>NPM:</strong> <?= htmlspecialchars($user['npm']) ?></p>

    <hr>
    <h4>Edit Profil</h4>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>NPM</label>
            <input type="text" name="npm" value="<?= htmlspecialchars($user['npm']) ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Foto Profil (baru)</label>
            <input type="file" name="foto" class="form-control-file">
            <small class="form-text text-muted">Format diperbolehkan: .jpg, .jpeg, .png</small>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>

    <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
</body>
</html>
