<?php
session_start();
include "db.php";

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data user
$id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($query);

// Siapkan path foto
$fotoPath = "uploads/" . $user['foto'];
$foto = (!empty($user['foto']) && file_exists($fotoPath)) ? $fotoPath : "default.png";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil Saya</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #007bff;
        }
        .btn-group {
            gap: 10px;
        }
    </style>
</head>
<body class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4">
                <div class="text-center">
                    <img src="<?= htmlspecialchars($foto) ?>" class="profile-pic mb-3" alt="Foto Profil">
                    <h3><?= htmlspecialchars($user['nama']) ?></h3>
                    <p class="text-muted">NPM: <?= htmlspecialchars($user['npm']) ?></p>
                </div>
                <div class="text-center mt-4 btn-group d-flex justify-content-center">
                    <a href="user_edit.php" class="btn btn-outline-primary">Edit Profil</a>
                    <a href="logout.php" class="btn btn-outline-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
