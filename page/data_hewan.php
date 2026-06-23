<?php
session_start();
// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Proses Hapus Data Hewan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (mysqli_query($koneksi, "DELETE FROM hewan WHERE ID_Hewan = '$id'")) {
        echo "<script>alert('Data pasien (hewan) berhasil dihapus!'); window.location.href='data_hewan.php';</script>";
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
    <title>Kelola Data Pasien Hewan - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fa fa-paw me-2 text-primary"></i>Kelola Data Pasien Hewan</h4>
            <div>
                <a href="tambah_hewan.php" class="btn btn-sm btn-primary fw-bold me-2"><i class="fa fa-plus me-1"></i>Tambah Pasien</a>
                <a href="../index.php" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-arrow-left me-1"></i>Kembali</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID Hewan</th>
                        <th>Nama Hewan</th>
                        <th>Spesies & Ras</th>
                        <th>Gender</th>
                        <th>Umur</th>
                        <th>Nama Pemilik</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menggabungkan Data Hewan dan Pemilik
                    $query_hewan = "SELECT h.*, p.Nama_Pemilik FROM hewan h JOIN pemilik p ON h.ID_Pemilik = p.ID_Pemilik ORDER BY h.ID_Hewan DESC";
                    $res = mysqli_query($koneksi, $query_hewan);
                    if ($res && mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            // Menghitung umur otomatis berdasarkan tanggal lahir
                            $tgl_lahir = new DateTime($row['Tanggal_Lahir']);
                            $sekarang = new DateTime('today');
                            $umur_bulan = $tgl_lahir->diff($sekarang)->m + ($tgl_lahir->diff($sekarang)->y * 12);

                            echo "<tr>
                                    <td class='fw-bold text-secondary'>HWN-{$row['ID_Hewan']}</td>
                                    <td class='fw-bold text-primary'>" . htmlspecialchars($row['Nama_Hewan']) . "</td>
                                    <td>" . htmlspecialchars($row['Spesies']) . " - " . htmlspecialchars($row['Ras']) . "</td>
                                    <td>{$row['Jenis_Kelamin']}</td>
                                    <td>{$umur_bulan} Bulan</td>
                                    <td>" . htmlspecialchars($row['Nama_Pemilik']) . "</td>
                                    <td class='text-center'>
                                        <a href='edit_hewan.php?id={$row['ID_Hewan']}' class='btn btn-sm btn-warning px-3 me-1'>
                                            <i class='fa fa-edit'></i> Edit
                                        </a>
                                        <a href='data_hewan.php?hapus={$row['ID_Hewan']}' class='btn btn-sm btn-danger px-3' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data pasien ini?\")'>
                                            <i class='fa fa-trash'></i> Hapus
                                        </a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-4 text-muted'>Belum ada data pasien terdaftar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>