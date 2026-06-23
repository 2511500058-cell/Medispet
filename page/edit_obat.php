<?php
session_start();
// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Ambil ID Obat dari URL (Contoh: edit_obat.php?id=1)
$id_obat = isset($_GET['id']) ? $_GET['id'] : 0;

// Tarik data yang sudah ada dari database
$query_cek = mysqli_query($koneksi, "SELECT * FROM obat WHERE ID_Obat = '$id_obat'");
$data = mysqli_fetch_assoc($query_cek);

// Jika ID obat tidak ditemukan
if (!$data) {
    header("Location: data_obat.php");
    exit();
}

// Proses Update Data
if (isset($_POST['update'])) {
    $nama_obat = mysqli_real_escape_string($koneksi, $_POST['nama_obat']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);

    $query_update = "UPDATE obat SET Nama_Obat='$nama_obat', Harga='$harga' WHERE ID_Obat='$id_obat'";
    
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data obat berhasil diperbarui!'); window.location.href='data_obat.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Obat - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 600px;">
        <h5 class="fw-bold mb-4"><i class="fa fa-edit me-2 text-warning"></i>Edit Data Obat</h5>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Nama Obat</label>
                <input type="text" name="nama_obat" class="form-control" value="<?= htmlspecialchars($data['Nama_Obat']); ?>" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="<?= htmlspecialchars($data['Harga']); ?>" required>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="data_obat.php" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" name="update" class="btn btn-warning px-4 fw-bold">Update Data</button>
            </div>
        </form>
    </div>
</body>
</html>