<?php
session_start();
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Ambil ID Hewan dari URL
$id_hewan = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : 0;

// Tarik data hewan yang sudah terdaftar sebelumnya
$query_cek = mysqli_query($koneksi, "SELECT * FROM hewan WHERE ID_Hewan = '$id_hewan'");
$data = mysqli_fetch_assoc($query_cek);

if (!$data) {
    header("Location: data_hewan.php");
    exit();
}

// Proses Update Data Hewan
if (isset($_POST['update'])) {
    $id_pemilik    = mysqli_real_escape_string($koneksi, $_POST['id_pemilik']);
    $nama_hewan    = mysqli_real_escape_string($koneksi, $_POST['nama_hewan']);
    $spesies       = mysqli_real_escape_string($koneksi, $_POST['spesies']);
    $ras           = mysqli_real_escape_string($koneksi, $_POST['ras']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);

    $query_update = "UPDATE hewan SET 
                        ID_Pemilik='$id_pemilik', 
                        Nama_Hewan='$nama_hewan', 
                        Spesies='$spesies', 
                        Ras='$ras', 
                        Jenis_Kelamin='$jenis_kelamin', 
                        Tanggal_Lahir='$tanggal_lahir' 
                     WHERE ID_Hewan='$id_hewan'";
                     
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data pasien berhasil diperbarui!'); window.location.href='data_hewan.php';</script>";
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
    <title>Edit Data Pasien - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 700px;">
        <h5 class="fw-bold mb-4"><i class="fa fa-edit me-2 text-warning"></i>Edit Data Pasien Peliharaan</h5>
        
        <form method="POST" action="" class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Nama Peliharaan / Hewan</label>
                <input type="text" name="nama_hewan" class="form-control" value="<?= htmlspecialchars($data['Nama_Hewan']); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Spesies (Jenis Hewan)</label>
                <input type="text" name="spesies" class="form-control" value="<?= htmlspecialchars($data['Spesies']); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Ras / Varietas</label>
                <input type="text" name="ras" class="form-control" value="<?= htmlspecialchars($data['Ras']); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($data['Tanggal_Lahir']); ?>" required>
            </div>
            <div class="col-md-12">
                <label class="form-label small fw-bold text-muted">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="Jantan" <?= ($data['Jenis_Kelamin'] == 'Jantan') ? 'selected' : ''; ?>>Jantan</option>
                    <option value="Betina" <?= ($data['Jenis_Kelamin'] == 'Betina') ? 'selected' : ''; ?>>Betina</option>
                </select>
            </div>
            
            <div class="col-md-12">
                <label class="form-label small fw-bold text-muted">Pemilik Pasien</label>
                <select name="id_pemilik" class="form-select" required>
                    <option value="">-- Pilih Pemilik Terdaftar --</option>
                    <?php
                    $p_res = mysqli_query($koneksi, "SELECT * FROM pemilik ORDER BY Nama_Pemilik ASC");
                    while($p_row = mysqli_fetch_assoc($p_res)) {
                        // Menandai secara otomatis pemilik lama yang terdata sebelumnya
                        $selected = ($p_row['ID_Pemilik'] == $data['ID_Pemilik']) ? 'selected' : '';
                        echo "<option value='{$p_row['ID_Pemilik']}' $selected>{$p_row['Nama_Pemilik']} (ID: PM-{$p_row['ID_Pemilik']})</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="col-12 mt-4 d-flex justify-content-between">
                <a href="data_hewan.php" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" name="update" class="btn btn-warning px-4 fw-bold">Perbarui Data</button>
            </div>
        </form>
    </div>
</body>
</html>