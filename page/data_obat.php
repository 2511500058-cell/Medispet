<?php
session_start();
// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
// Load koneksi dari folder config di luar folder ini
include '../config/koneksi.php';

// Proses Hapus Data Obat
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (mysqli_query($koneksi, "DELETE FROM obat WHERE ID_Obat = '$id'")) {
        echo "<script>alert('Data obat berhasil dihapus!'); window.location.href='data_obat.php';</script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Obat - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fa fa-pills me-2 text-primary"></i>Kelola Data Obat</h4>
            <div>
                <a href="tambah_obat.php" class="btn btn-sm btn-primary fw-bold me-2"><i class="fa fa-plus me-1"></i>Tambah Obat</a>
                <a href="../index.php" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-arrow-left me-1"></i>Kembali</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID Obat</th>
                        <th>Nama Obat</th>
                        <th>Harga (Rp)</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY ID_Obat DESC");
                    if (mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            echo "<tr>
                                    <td class='fw-bold text-secondary'>OBT-{$row['ID_Obat']}</td>
                                    <td class='fw-bold text-primary'>" . htmlspecialchars($row['Nama_Obat']) . "</td>
                                    <td class='fw-bold'>Rp " . number_format($row['Harga'], 0, ',', '.') . "</td>
                                    <td class='text-center'>
                                        <a href='edit_obat.php?id={$row['ID_Obat']}' class='btn btn-sm btn-warning px-3 me-1'>
                                            <i class='fa fa-edit'></i> Edit
                                        </a>
                                        <a href='data_obat.php?hapus={$row['ID_Obat']}' class='btn btn-sm btn-danger px-3' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data obat ini?\")'>
                                            <i class='fa fa-trash'></i> Hapus
                                        </a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center py-4 text-muted'><i class='fa-solid fa-folder-open me-1'></i> Belum ada data obat terdaftar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>