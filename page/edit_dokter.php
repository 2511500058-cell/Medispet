<?php
session_start();
// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Ambil ID dari URL (Contoh: edit_dokter.php?id=1)
$id_dokter = isset($_GET['id']) ? $_GET['id'] : 0;

// Tarik data yang sudah ada
$query_cek = mysqli_query($koneksi, "SELECT * FROM dokter WHERE ID_Dokter = '$id_dokter'");
$data = mysqli_fetch_assoc($query_cek);

// Jika ID tidak ditemukan di tabel
if (!$data) {
    header("Location: data_dokter.php");
    exit();
}

// Proses Update Data
if (isset($_POST['update'])) {
    $nama_dokter = mysqli_real_escape_string($koneksi, $_POST['nama_dokter']);

    $query_update = "UPDATE dokter SET Nama_Dokter='$nama_dokter' WHERE ID_Dokter='$id_dokter'";
    
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data dokter berhasil diperbarui!'); window.location.href='data_dokter.php';</script>";
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
    <title>Edit Dokter - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 600px;">
        <h5 class="fw-bold mb-4"><i class="fa fa-edit me-2 text-warning"></i>Edit Data Dokter</h5>
        
        <form method="POST" action="">
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Nama Dokter (Beserta Gelar)</label>
                <input type="text" name="nama_dokter" class="form-control" value="<?= htmlspecialchars($data['Nama_Dokter']); ?>" required>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="data_dokter.php" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" name="update" class="btn btn-warning px-4 fw-bold">Update Data</button>
            </div>
        </form>
    </div>
</body>
</html>