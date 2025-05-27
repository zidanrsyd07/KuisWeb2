<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $npm = $_POST['npm'];
    $password = $_POST['password'];

    // Admin login (opsional)
    if ($nama === "admin" && $npm === "admin123" && $password === "adminpass") {
        $_SESSION['admin'] = true;
        header("Location: admin_view.php");
        exit;
    }

    // Cek user biasa
    $query = "SELECT * FROM users WHERE nama=? AND npm=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $nama, $npm);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && !empty($user['password']) && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: user_profile.php");
        exit;
    } else {
        $error = "Login gagal! Nama, NPM, atau Password salah.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
    <form method="post">
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
        <button class="btn btn-primary">Login</button>
    </form>
    <a href="tambah.php">Daftar User Baru</a>
</body>
</html>
