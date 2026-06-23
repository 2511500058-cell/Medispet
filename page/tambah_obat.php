<?php
session_start();
// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Proses Simpan Data Obat
if (isset($_POST['simpan'])) {
    $nama_obat = mysqli_real_escape_string($koneksi, $_POST['nama_obat']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    
    $query = "INSERT INTO obat (Nama_Obat, Harga) VALUES ('$nama_obat', '$harga')";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data obat berhasil ditambahkan!'); window.location.href='data_obat.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Obat - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 600px;">
        <h5 class="fw-bold mb-4"><i class="fa fa-plus-circle me-2 text-primary"></i>Tambah Obat Baru</h5>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Nama Obat</label>
                <input type="text" name="nama_obat" class="form-control" placeholder="Contoh: Paracetamol Hewan, Vaksin Rabies..." required>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" placeholder="Contoh: 50000 (tanpa titik)" required>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="data_obat.php" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary px-4 fw-bold">Simpan Data</button>
            </div>
        </form>
    </div>
</body>
</html>