<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Ambil ID dari URL
$id_pemilik = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : 0;

// Tarik data lama
$query_cek = mysqli_query($koneksi, "SELECT * FROM pemilik WHERE ID_Pemilik = '$id_pemilik'");
$data = mysqli_fetch_assoc($query_cek);

if (!$data) {
    header("Location: data_pemilik.php");
    exit();
}

// Proses Update Data
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_pemilik']);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $query_update = "UPDATE pemilik SET Nama_Pemilik='$nama', Alamat='$alamat', No_Telepon='$telepon' WHERE ID_Pemilik='$id_pemilik'";
    
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data pemilik berhasil diperbarui!'); window.location.href='data_pemilik.php';</script>";
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
    <title>Edit Pemilik - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 600px;">
        <h5 class="fw-bold mb-4"><i class="fa fa-edit me-2 text-warning"></i>Edit Data Pemilik</h5>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Nama Lengkap Pemilik</label>
                <input type="text" name=\"nama_pemilik\" class="form-control" value="<?= htmlspecialchars($data['Nama_Pemilik']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Nomor Telepon (WhatsApp)</label>
                <input type="number" name=\"no_telepon\" class="form-control" value="<?= htmlspecialchars($data['No_Telepon']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Alamat Rumah</label>
                <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($data['Alamat']); ?></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="data_pemilik.php" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" name="update" class="btn btn-warning px-4 fw-bold">Perbarui Data</button>
            </div>
        </form>
    </div>
</body>
</html>