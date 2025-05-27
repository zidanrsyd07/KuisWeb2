<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $npm = mysqli_real_escape_string($conn, $_POST['npm']);
    $fotoName = $user['foto']; // default, tidak berubah

    // Jika ada file diupload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['foto']['type'], $allowedTypes)) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $fotoName = time() . "_" . uniqid() . "." . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $fotoName);
        }
    }

    mysqli_query($conn, "UPDATE users SET nama='$nama', npm='$npm', foto='$fotoName' WHERE id='$id'");
    header("Location: user_profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Edit Profil</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" required>
                </div>
                <div class="form-group">
                    <label>NPM</label>
                    <input type="text" name="npm" class="form-control" value="<?= htmlspecialchars($user['npm']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" name="foto" class="form-control-file">
                    <?php if (!empty($user['foto']) && file_exists("uploads/" . $user['foto'])): ?>
                        <small>Foto saat ini: <br><img src="uploads/<?= htmlspecialchars($user['foto']) ?>" width="100" class="mt-2 rounded"></small>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="profile.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>
