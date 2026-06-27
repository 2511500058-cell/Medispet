<?php
session_start();
// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Ambil ID dari URL
$id_dokter = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : 0;

// Tarik data yang sudah ada di tabel dokter
$query_cek = mysqli_query($koneksi, "SELECT * FROM dokter WHERE ID_Dokter = '$id_dokter'");
$data = mysqli_fetch_assoc($query_cek);

// Jika ID tidak ditemukan di tabel
if (!$data) {
    header("Location: data_dokter.php");
    exit();
}

// Format username lama berdasarkan nama di database sebelum di-edit
$username_lama = strtolower(str_replace([' ', '.', ','], '', $data['Nama_Dokter']));

// Cari password dari tabel medispet sebagai referensi
$query_akun = mysqli_query($koneksi, "SELECT password FROM medispet WHERE username = '$username_lama' AND role = 'dokter'");
$data_akun = mysqli_fetch_assoc($query_akun);
$password_saat_ini = $data_akun ? $data_akun['password'] : $data['Password'];

// Proses Update Data
if (isset($_POST['update'])) {
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama_dokter']);
    $password  = mysqli_real_escape_string($koneksi, $_POST['password_dokter']);

    // 1. Update Nama dan Password di tabel 'dokter'
    $query_update_dokter = "UPDATE dokter SET Nama_Dokter='$nama', Password='$password' WHERE ID_Dokter='$id_dokter'";
    
    if (mysqli_query($koneksi, $query_update_dokter)) {
        
        // 2. Buat username baru (karena jika nama berubah, username login otomatis berubah)
        $username = strtolower(str_replace([' ', '.', ','], '', $nama));
        
        // 3. Update Username dan Password di tabel 'medispet' berdasarkan $username
        $query_update_akun = "UPDATE medispet SET 
                                username = '$username', 
                                password = '$password' 
                              WHERE username = '$username_lama' AND role = 'dokter'";
                              
        mysqli_query($koneksi, $query_update_akun);

        echo "<script>
                alert('Data dan Password dokter berhasil diperbarui!'); 
                window.location.href='data_dokter.php';
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
    <title>Edit Dokter - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 600px; border-radius: 15px !important;">
        <h5 class="fw-bold mb-4"><i class="fa fa-edit me-2 text-warning"></i>Edit Data & Akun Dokter</h5>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Nama Dokter (Beserta Gelar)</label>
                <input type="text" name="nama_dokter" class="form-control" value="<?= htmlspecialchars($data['Nama_Dokter']); ?>" required>
                <small class="text-danger">*Perhatian: Mengubah nama akan otomatis merubah username login dokter tersebut.</small>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Kata Sandi / Password Login</label>
                <input type="text" name="password_dokter" class="form-control" value="<?= htmlspecialchars($password_saat_ini); ?>" required>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="data_dokter.php" class="btn btn-secondary px-4" style="border-radius: 10px;">Batal</a>
                <button type="submit" name="update" class="btn btn-warning px-4 fw-bold text-dark" style="border-radius: 10px;">Perbarui Data</button>
            </div>
        </form>
    </div>
</body>
</html>