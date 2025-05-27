<?php
include "db.php";
$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($result);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $npm = $_POST['npm'];
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        move_uploaded_file($tmp, "uploads/" . $foto);
        $query = "UPDATE users SET nama='$nama', npm='$npm', foto='$foto' WHERE id='$id'";
    } else {
        $query = "UPDATE users SET nama='$nama', npm='$npm' WHERE id='$id'";
    }
    mysqli_query($conn, $query);
    header("Location: admin_view.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Edit User</title>
</head>
<body class="container mt-5">
    <h2>Edit User</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="<?= $user['nama'] ?>" required>
        </div>
        <div class="form-group">
            <label>NPM</label>
            <input type="text" name="npm" class="form-control" value="<?= $user['npm'] ?>" required>
        </div>
        <div class="form-group">
            <label>Foto</label>
            <input type="file" name="foto" class="form-control">
        </div>
        <button class="btn btn-success">Update</button>
    </form>
</body>
</html>