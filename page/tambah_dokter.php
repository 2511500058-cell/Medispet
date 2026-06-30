<?php
session_start();

if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';


if (isset($_POST['simpan'])) {
    $nama_dokter = mysqli_real_escape_string($koneksi, $_POST['nama_dokter']);
    $password_dokter = mysqli_real_escape_string($koneksi, $_POST['password_dokter']);
    

    $query = "INSERT INTO dokter (Nama_Dokter, Password) VALUES ('$nama_dokter', '$password_dokter')";
    if (mysqli_query($koneksi, $query)) {
        

        $username_login = strtolower(str_replace([' ', '.', ','], '', $nama_dokter)); 
        

        $query_akun = "INSERT INTO medispet (username, password, role) VALUES ('$username_login', '$password_dokter', 'dokter')";
        mysqli_query($koneksi, $query_akun);

        echo "<script>
                alert('Data dokter berhasil ditambahkan!\\n\\nAKUN DOKTER OTOMATIS DIBUAT:\\nUsername: $username_login\\nPassword: $password_dokter'); 
                window.location.href='data_dokter.php';
              </script>";
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
    <title>Tambah Dokter - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 600px; border-radius: 15px !important;">
        <h5 class="fw-bold mb-4"><i class="fa fa-user-plus me-2 text-primary"></i>Tambah Dokter Baru</h5>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">Nama Dokter (Beserta Gelar)</label>
                <input type="text" name="nama_dokter" class="form-control" placeholder="Contoh: drh. Asep Supriando" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">Buat Kata Sandi Login Dokter</label>
                <input type="text" name="password_dokter" class="form-control" placeholder="Contoh: asep123" required>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="data_dokter.php" class="btn btn-secondary px-4" style="border-radius: 10px;">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary px-4 fw-bold" style="border-radius: 10px;">Simpan Data</button>
            </div>
        </form>
    </div>
</body>
</html>