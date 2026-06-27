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
        echo "<script>alert('Gagal mendaftar: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Proses Hapus Data Kunjungan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (mysqli_query($koneksi, "DELETE FROM kunjungan WHERE ID_Kunjungan = '$id'")) {
        echo "<script>alert('Data kunjungan berhasil dihapus!'); window.location.href='data_kunjungan.php';</script>";
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
    <title>Pendaftaran Kunjungan - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark m-0"><i class="fa fa-list-alt d-block-md me-2 text-primary"></i> Pendaftaran Kunjungan</h4>
            <a href="index.php" class="btn btn-secondary fw-bold px-3" style="border-radius: 20px;">
                <i class="fa fa-arrow-left me-1"></i> Kembali ke Menu Utama
            </a>
        </div>

        <div class="card mb-4 border-0 bg-light" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="" method="POST" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Pilih Pasien (Hewan)</label>
                        <select name="id_hewan" class="form-select" required>
                            <option value="">-- Pilih Pasien --</option>
                            <?php
                            $qhewan = mysqli_query($koneksi, "SELECT * FROM hewan");
                            while ($h = mysqli_fetch_assoc($qhewan)) {
                                echo "<option value='{$h['ID_Hewan']}'>{$h['Nama_Hewan']} ({$h['Spesies']})</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Pilih Dokter</label>
                        <select name="id_dokter" class="form-select" required>
                            <option value="">-- Pilih Dokter --</option>
                            <?php
                            $qdokter = mysqli_query($koneksi, "SELECT * FROM dokter");
                            while ($d = mysqli_fetch_assoc($qdokter)) {
                                echo "<option value='{$d['ID_Dokter']}'>{$d['Nama_Dokter']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-secondary">Tanggal Kunjungan</label>
                        <input type="date" name="tanggal_kunjungan" class="form-control" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Keluhan Utama</label>
                        <input type="text" name="keluhan" class="form-control" placeholder="Contoh: Muntah, Demam" required>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" name="tambah" class="btn btn-primary w-100 fw-bold" style="height: 38px;"><i class="fa fa-plus"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Nama Pasien</th>
                        <th>Nama Dokter</th>
                        <th>Keluhan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_tampil = "SELECT k.*, h.Nama_Hewan, d.Nama_Dokter 
                                     FROM kunjungan k 
                                     JOIN hewan h ON k.ID_Hewan = h.ID_Hewan 
                                     JOIN dokter d ON k.ID_Dokter = d.ID_Dokter 
                                     ORDER BY k.ID_Kunjungan DESC";
                    
                    $res = mysqli_query($koneksi, $query_tampil);
                    
                    if (mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            // Cek status pemeriksaan dari ketersediaan isi kolom diagnosa
                            $status = (empty($row['Diagnosa'])) ? "<span class='badge bg-warning text-dark'>Menunggu Pemeriksaan</span>" : "<span class='badge bg-success'>Selesai Diperiksa</span>";
                            
                            echo "<tr>
                                    <td class='fw-bold text-secondary'>#KJ-{$row['ID_Kunjungan']}</td>
                                    <td>" . date('d M Y', strtotime($row['Tanggal_Kunjungan'])) . "</td>
                                    <td class='fw-bold'>" . htmlspecialchars($row['Nama_Hewan']) . "</td>
                                    <td>" . htmlspecialchars($row['Nama_Dokter']) . "</td>
                                    <td>" . htmlspecialchars($row['Keluhan']) . "</td>
                                    <td class='text-center'>{$status}</td>
                                    <td class='text-center'>
                                        
                                        <a href=' detail_tindakan.php?id_kunjungan={$row['ID_Kunjungan']}' class='btn btn-sm btn-info text-white fw-bold px-3 me-1' style='border-radius: 15px;'>
                                            <i class='fa fa-stethoscope me-1'></i> Detail Tindakan
                                        </a>

                                        <a href='data_kunjungan.php?hapus={$row['ID_Kunjungan']}' class='btn btn-sm btn-danger px-3' style='border-radius: 15px;' onclick='return confirm(\"Yakin ingin menghapus data kunjungan ini?\")'>
                                            <i class='fa fa-trash'></i> Hapus
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