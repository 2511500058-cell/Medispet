<?php
// FITUR PELACAK ERROR (Akan memunculkan teks merah jika ada yang salah)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Load koneksi dari folder config di luar folder ini
include '../config/koneksi.php';

// Proses Tambah Data Kunjungan
if (isset($_POST['tambah'])) {
    $id_hewan = mysqli_real_escape_string($koneksi, $_POST['id_hewan']);
    $id_dokter = mysqli_real_escape_string($koneksi, $_POST['id_dokter']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal_kunjungan']);
    $keluhan = mysqli_real_escape_string($koneksi, $_POST['keluhan']);

    // Diagnosa dan Catatan Medis dikosongkan dulu saat pendaftaran
    $query = "INSERT INTO kunjungan (ID_Hewan, ID_Dokter, Tanggal_Kunjungan, Keluhan, Diagnosa, Catatan_Medis) 
              VALUES ('$id_hewan', '$id_dokter', '$tanggal', '$keluhan', '', '')";
              
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Pendaftaran kunjungan berhasil!'); window.location.href='data_kunjungan.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menyimpan data: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Proses Hapus Data Kunjungan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (mysqli_query($koneksi, "DELETE FROM kunjungan WHERE ID_Kunjungan = '$id'")) {
        echo "<script>alert('Data kunjungan berhasil dihapus!'); window.location.href='data_kunjungan.php';</script>";
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
    <title>Data Kunjungan - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fa fa-calendar-check me-2 text-primary"></i>Kelola Data Kunjungan</h4>
            <a href="../index.php" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left me-1"></i>Kembali</a>
        </div>

        <div class="card bg-light border-0 p-3 mb-4">
            <h6 class="fw-bold mb-3">Daftarkan Kunjungan Baru</h6>
            <form method="POST" action="" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Pilih Pasien (Hewan)</label>
                    <select name="id_hewan" class="form-select" required>
                        <option value="">-- Pilih Pasien Terdaftar --</option>
                        <?php
                        $h_res = mysqli_query($koneksi, "SELECT h.ID_Hewan, h.Nama_Hewan, p.Nama_Pemilik FROM hewan h LEFT JOIN pemilik p ON h.ID_Pemilik = p.ID_Pemilik");
                        if ($h_res) {
                            while($h_row = mysqli_fetch_assoc($h_res)) {
                                echo "<option value='{$h_row['ID_Hewan']}'>{$h_row['Nama_Hewan']} (Milik: {$h_row['Nama_Pemilik']})</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Dokter Pemeriksa</label>
                    <select name="id_dokter" class="form-select" required>
                        <option value="">-- Pilih Dokter Jaga --</option>
                        <?php
                        $d_res = mysqli_query($koneksi, "SELECT * FROM dokter");
                        if ($d_res) {
                            while($d_row = mysqli_fetch_assoc($d_res)) {
                                echo "<option value='{$d_row['ID_Dokter']}'>{$d_row['Nama_Dokter']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Tanggal Kunjungan</label>
                    <input type="date" name="tanggal_kunjungan" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-10">
                    <label class="form-label small fw-bold text-muted">Keluhan Awal</label>
                    <input type="text" name="keluhan" class="form-control" placeholder="Contoh: Muntah, lemas, tidak mau makan..." required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="tambah" class="btn btn-primary w-100 fw-bold">Daftarkan</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Nama Pasien</th>
                        <th>Dokter Pemeriksa</th>
                        <th>Keluhan</th>
                        <th class="text-center">Status Diagnosa</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_kunjungan = "SELECT k.*, h.Nama_Hewan, d.Nama_Dokter 
                                        FROM kunjungan k 
                                        LEFT JOIN hewan h ON k.ID_Hewan = h.ID_Hewan 
                                        LEFT JOIN dokter d ON k.ID_Dokter = d.ID_Dokter 
                                        ORDER BY k.ID_Kunjungan DESC";
                    $res = mysqli_query($koneksi, $query_kunjungan);
                    
                    if ($res && mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            $status = empty($row['Diagnosa']) ? "<span class='badge bg-warning text-dark'>Menunggu Pemeriksaan</span>" : "<span class='badge bg-success'>Selesai Diperiksa</span>";
                            
                            echo "<tr>
                                    <td class='fw-bold text-secondary'>#KJ-{$row['ID_Kunjungan']}</td>
                                    <td>" . date('d M Y', strtotime($row['Tanggal_Kunjungan'])) . "</td>
                                    <td class='fw-bold'>" . htmlspecialchars($row['Nama_Hewan']) . "</td>
                                    <td>" . htmlspecialchars($row['Nama_Dokter']) . "</td>
                                    <td>" . htmlspecialchars($row['Keluhan']) . "</td>
                                    <td class='text-center'>{$status}</td>
                                    <td class='text-center'>
                                        <a href='data_kunjungan.php?hapus={$row['ID_Kunjungan']}' class='btn btn-sm btn-danger px-3' onclick='return confirm(\"Yakin ingin menghapus data kunjungan ini?\")'>
                                            Hapus
                                        </a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-4 text-muted'>Belum ada data kunjungan terdaftar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>