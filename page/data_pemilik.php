<?php
session_start();
// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Proses Hapus Data Pemilik
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM pemilik WHERE ID_Pemilik = '$id'";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data pemilik berhasil dihapus!'); window.location.href='data_pemilik.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pemilik - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="m-0 fw-bold text-primary"><i class="fa-solid fa-user-doctor me-2"></i>Kelola Data Dokter</h3>
            <a href="../index.php" class="btn btn-secondary fw-semibold shadow-sm" style="border-radius: 30px; font-size: 14px;">
                <i class="fa fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID Pemilik</th>
                        <th>Nama Pemilik</th>
                        <th>No Telepon</th>
                        <th>Alamat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($koneksi, "SELECT * FROM pemilik ORDER BY ID_Pemilik DESC");
                    if (mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            echo "<tr>
                                    <td class='fw-bold text-secondary'>PM-{$row['ID_Pemilik']}</td>
                                    <td class='fw-bold'>{$row['Nama_Pemilik']}</td>
                                    <td>{$row['No_Telepon']}</td>
                                    <td>{$row['Alamat']}</td>
                                    <td class='text-center'>
                                        <a href='edit_pemilik.php?id={$row['ID_Pemilik']}' class='btn btn-sm btn-warning px-3 me-1'>
                                            <i class='fa fa-edit'></i> Edit
                                        </a>
                                        <a href='data_pemilik.php?hapus={$row['ID_Pemilik']}' class='btn btn-sm btn-danger px-3' onclick='return confirm(\"PERINGATAN: Menghapus pemilik akan menghapus data hewannya. Yakin ingin menghapus?\")'>
                                            <i class='fa fa-trash'></i> Hapus
                                        </a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Belum ada data pemilik terdaftar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>