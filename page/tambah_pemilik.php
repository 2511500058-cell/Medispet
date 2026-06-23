<?php
session_start();
// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Proses Simpan Data
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_pemilik']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);

    $query = "INSERT INTO pemilik (Nama_Pemilik, Alamat, No_Telepon) VALUES ('$nama', '$alamat', '$telepon')";
    if (mysqli_query($koneksi, $query)) {
        // Direct kembali ke data_pemilik.php
        echo "<script>alert('Data pemilik berhasil ditambahkan!'); window.location.href='data_pemilik.php';</script>";
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
    <title>Tambah Pemilik - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 600px;">
        <h5 class="fw-bold mb-4"><i class="fa fa-user-plus me-2 text-primary"></i>Tambah Pemilik Baru</h5>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Nama Lengkap Pemilik</label>
                <input type="text" name="nama_pemilik" class="form-control" placeholder="Masukkan nama pemilik" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Nomor Telepon (WhatsApp)</label>
                <input type="number" name="no_telepon" class="form-control" placeholder="Contoh: 081234567890" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Alamat Rumah</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="data_pemilik.php" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary px-4 fw-bold">Simpan Data</button>
            </div>
        </form>
    </div>
</body>
</html>