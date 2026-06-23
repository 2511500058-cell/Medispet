<?php
session_start();
// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Proses Simpan Data Hewan
if (isset($_POST['simpan'])) {
    $id_pemilik    = mysqli_real_escape_string($koneksi, $_POST['id_pemilik']);
    $nama_hewan    = mysqli_real_escape_string($koneksi, $_POST['nama_hewan']);
    $spesies       = mysqli_real_escape_string($koneksi, $_POST['spesies']);
    $ras           = mysqli_real_escape_string($koneksi, $_POST['ras']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);

    $query = "INSERT INTO hewan (ID_Pemilik, Nama_Hewan, Spesies, Ras, Jenis_Kelamin, Tanggal_Lahir) 
              VALUES ('$id_pemilik', '$nama_hewan', '$spesies', '$ras', '$jenis_kelamin', '$tanggal_lahir')";
              
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data hewan berhasil didaftarkan!'); window.location.href='data_hewan.php';</script>";
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
    <title>Tambah Pasien Hewan - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 800px;">
        <h5 class="fw-bold mb-4"><i class="fa fa-plus-circle me-2 text-primary"></i>Daftarkan Pasien Hewan Baru</h5>
        
        <form method="POST" action="" class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Nama Hewan</label>
                <input type="text" name="nama_hewan" class="form-control" placeholder="Contoh: Milo" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" required>
            </div>
            
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Spesies (Jenis)</label>
                <input type="text" name="spesies" class="form-control" placeholder="Kucing, Anjing, dll" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Ras</label>
                <input type="text" name="ras" class="form-control" placeholder="Persia, Bulldog, dll" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="">-- Pilih Kelamin --</option>
                    <option value="Jantan">Jantan</option>
                    <option value="Betina">Betina</option>
                </select>
            </div>
            
            <div class="col-md-12 mt-4">
                <label class="form-label small fw-bold text-muted">Pemilik Pasien</label>
                <select name="id_pemilik" class="form-select" required>
                    <option value="">-- Pilih Pemilik Terdaftar --</option>
                    <?php
                    $p_res = mysqli_query($koneksi, "SELECT * FROM pemilik");
                    while($p_row = mysqli_fetch_assoc($p_res)) {
                        echo "<option value='{$p_row['ID_Pemilik']}'>{$p_row['Nama_Pemilik']} (ID: {$p_row['ID_Pemilik']})</option>";
                    }
                    ?>
                </select>
                <small class="text-danger">*Pastikan pemilik sudah didaftarkan sebelumnya.</small>
            </div>
            
            <div class="col-12 mt-5 d-flex justify-content-between">
                <a href="data_hewan.php" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary px-4 fw-bold">Simpan Pasien</button>
            </div>
        </form>
    </div>
</body>
</html>