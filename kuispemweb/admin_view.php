<?php
session_start();
include "db.php";

// Cek jika admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

// Ambil semua data user
$users = mysqli_query($conn, "SELECT * FROM users");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Kelola User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        img.profile-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ccc;
        }
    </style>
</head>
<body class="container mt-5">
    <h2 class="mb-4">Kelola Akun User</h2>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Foto</th>
                <th>Nama</th>
                <th>NPM</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = mysqli_fetch_assoc($users)) {
                $fotoFile = htmlspecialchars($user['foto']);
                $fotoPath = "uploads/" . $fotoFile;
                if (empty($fotoFile) || !file_exists($fotoPath)) {
                    $fotoPath = "uploads/default.png"; // gunakan default jika tidak ditemukan
                }
            ?>
            <tr>
                <td><img src="<?= htmlspecialchars($fotoPath) ?>" class="profile-thumb" alt="Foto User"></td>
                <td><?= htmlspecialchars($user['nama']) ?></td>
                <td><?= htmlspecialchars($user['npm']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="hapus.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="logout.php" class="btn btn-secondary">Logout</a>
</body>
</html>
