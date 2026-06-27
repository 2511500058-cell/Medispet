<?php
// FITUR PELACAK ERROR
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Load koneksi
include '../config/koneksi.php';

// Ambil ID Kunjungan dari URL
$id_kunjungan = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : 0;

// Ambil data kunjungan lama
$query_cek = mysqli_query($koneksi, "SELECT * FROM kunjungan WHERE ID_Kunjungan = '$id_kunjungan'");
$data = mysqli_fetch_assoc($query_cek);

// Jika data tidak ditemukan, kembalikan ke halaman utama
if (!$data) {
    header("Location: data_kunjungan.php");
    exit();
}

// Proses Update Data Kunjungan
if (isset($_POST['update'])) {
    $id_hewan  = mysqli_real_escape_string($koneksi, $_POST['id_hewan']);
    $id_dokter = mysqli_real_escape_string($koneksi, $_POST['id_dokter']);
    $tanggal   = mysqli_real_escape_string($koneksi, $_POST['tanggal_kunjungan']);
    $keluhan   = mysqli_real_escape_string($koneksi, $_POST['keluhan']);

    // Update hanya data registrasi pendaftaran saja
    $query_update = "UPDATE kunjungan SET 
                        ID_Hewan = '$id_hewan', 
                        ID_Dokter = '$id_dokter', 
                        Tanggal_Kunjungan = '$tanggal', 
                        Keluhan = '$keluhan' 
                     WHERE ID_Kunjungan = '$id_kunjungan'";

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>
                alert('Data kunjungan berhasil diperbarui!'); 
                window.location.href='data_kunjungan.php';
              </script>";
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
    <title>Edit Kunjungan - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 600px; border-radius: 15px;">
        <h5 class="fw-bold mb-4"><i class="fa fa-edit me-2 text-warning"></i>Edit Data Kunjungan</h5>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">Pilih Pasien (Hewan)</label>
                <select name="id_hewan" class="form-select" required>
                    <?php
                    $qhewan = mysqli_query($koneksi, "SELECT * FROM hewan");
                    while ($h = mysqli_fetch_assoc($qhewan)) {
                        $selected = ($h['ID_Hewan'] == $data['ID_Hewan']) ? 'selected' : '';
                        echo "<option value='{$h['ID_Hewan']}' {$selected}>{$h['Nama_Hewan']} ({$h['Spesies']})</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">Pilih Dokter</label>
                <select name="id_dokter" class="form-select" required>
                    <?php
                    $qdokter = mysqli_query($koneksi, "SELECT * FROM dokter");
                    while ($d = mysqli_fetch_assoc($qdokter)) {
                        $selected = ($d['ID_Dokter'] == $data['ID_Dokter']) ? 'selected' : '';
                        echo "<option value='{$d['ID_Dokter']}' {$selected}>{$d['Nama_Dokter']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">Tanggal Kunjungan</label>
                <input type="date" name="tanggal_kunjungan" class="form-control" required value="<?= $data['Tanggal_Kunjungan'] ?>">
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">Keluhan Utama</label>
                <input type="text" name="keluhan" class="form-control" required value="<?= htmlspecialchars($data['Keluhan']) ?>">
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="data_kunjungan.php" class="btn btn-secondary px-4" style="border-radius: 10px;">Batal</a>
                <button type="submit" name="update" class="btn btn-warning fw-bold px-4 text-dark" style="border-radius: 10px;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>