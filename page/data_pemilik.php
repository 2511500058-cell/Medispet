<?php
session_start();
// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
// Load koneksi dari folder config di luar folder ini
include '../config/koneksi.php';

// Proses Hapus Data Dokter & Akun Loginnya
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Cari nama dokter dulu untuk hapus akun loginnya di tabel medispet
    $cek_nama = mysqli_query($koneksi, "SELECT Nama_Pemilik FROM pemilik WHERE ID_Pemilik = '$id'");
    if ($row = mysqli_fetch_assoc($cek_nama)) {
        $username_hapus = strtolower(str_replace([' ', '.', ','], '', $row['Nama_Pemilik']));
        mysqli_query($koneksi, "DELETE FROM medispet WHERE username = '$username_hapus' AND role='pemilik'");
    }

    if (mysqli_query($koneksi, "DELETE FROM pemilik WHERE ID_Pemilik = '$id'")) {
        echo "<script>alert('Data pemilik beserta akun loginnya berhasil dihapus!'); window.location.href='data_pemilik.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kelola Pemilik - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fa fa-user me-2 text-primary"></i>Kelola Data Pemilik</h4>
            <div>
                <a href="tambah_pemilik.php" class="btn btn-sm btn-primary fw-bold me-2"><i class="fa fa-plus me-1"></i>Tambah Pemilik</a>
                <a href="../index.php" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-arrow-left me-1"></i>Kembali</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID Pemilik</th>
                        <th>Nama Pemilik</th>
                        <th>Nomor Telepon</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($koneksi, "SELECT * FROM pemilik");
                    if (mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            // Menampilkan preview username login (tanpa titik & spasi)
                            $username_tampil = strtolower(str_replace([' ', '.', ','], '', $row['Nama_Pemilik']));
                            
                            echo "<tr>
                                    <td class='fw-bold text-secondary'>PML-{$row['ID_Pemilik']}</td>
                                    <td class='fw-bold'>
                                        " . htmlspecialchars($row['Nama_Pemilik']) . " <br>
                                    </td>
                                    <td class='text-center'>
                                        " . htmlspecialchars($row['No_Telepon']) . " <br>
                                    </td>
                                    <td class='text-center'>
                                        <a href='edit_pemilik.php?id={$row['ID_Pemilik']}' class='btn btn-sm btn-warning px-3 me-1'>
                                            <i class='fa fa-edit'></i> Edit
                                        </a>
                                        <a href='data_pemilik.php?hapus={$row['ID_Pemilik']}' class='btn btn-sm btn-danger px-3' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data pemilik ini beserta akun loginnya?\")'>
                                            <i class='fa fa-trash'></i> Hapus
                                        </a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center py-4 text-muted'>Belum ada data pemilik terdaftar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>