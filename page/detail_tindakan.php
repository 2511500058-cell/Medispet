<?php
session_start();

// Cek login admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Load koneksi dari folder config di luar folder ini
include '../config/koneksi.php';

// Ambil ID Kunjungan dari URL jika dikirim dari halaman data_kunjungan.php
$id_kunjungan_get = isset($_GET['id_kunjungan']) ? mysqli_real_escape_string($koneksi, $_GET['id_kunjungan']) : '';

// Proses Tambah Detail Tindakan
if (isset($_POST['tambah'])) {
    $id_kunjungan = mysqli_real_escape_string($koneksi, $_POST['id_kunjungan']);
    $id_tindakan  = mysqli_real_escape_string($koneksi, $_POST['id_tindakan']);

    $query = "INSERT INTO detail_tindakan (ID_Kunjungan, ID_Tindakan) VALUES ('$id_kunjungan', '$id_tindakan')";
    if (mysqli_query($koneksi, $query)) {
        // Disamakan menuju file ini sendiri: detail_tindakan.php
        echo "<script>alert('Tindakan medis berhasil ditambahkan!'); window.location.href='detail_tindakan.php?id_kunjungan=$id_kunjungan';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Proses Hapus Detail Tindakan
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $ret_kunjungan = isset($_GET['ret_kunjungan']) ? $_GET['ret_kunjungan'] : '';
    
    if (mysqli_query($koneksi, "DELETE FROM detail_tindakan WHERE ID_Detail = '$id_hapus'")) {
        echo "<script>alert('Tindakan medis berhasil dihapus!'); window.location.href='detail_tindakan.php?id_kunjungan=$ret_kunjungan';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus tindakan: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tindakan Medis - Medispet</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark m-0"><i class="fa fa-stethoscope text-info me-2"></i> Detail Tindakan Medis Pasien</h4>
            <a href="data_kunjungan.php" class="btn btn-secondary fw-bold px-3" style="border-radius: 20px;">
                <i class="fa fa-arrow-left me-1"></i> Kembali ke Kunjungan
            </a>
        </div>

        <div class="card mb-4 border-0 bg-light" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="" method="POST" class="row g-3 align-items-end">
                    
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-secondary">Pilih Pasien & Sesi Kunjungan</label>
                        <select name="id_kunjungan" class="form-select" required>
                            <?php if (empty($id_kunjungan_get)): ?>
                                <option value="">-- Pilih Sesi Kunjungan Pasien --</option>
                            <?php endif; ?>
                            
                            <?php
                            // Menggabungkan tabel kunjungan dan hewan agar tahu nama pasiennya
                            $qkunjungan = mysqli_query($koneksi, "SELECT k.ID_Kunjungan, k.Tanggal_Kunjungan, h.Nama_Hewan 
                                                                   FROM kunjungan k 
                                                                   JOIN hewan h ON k.ID_Hewan = h.ID_Hewan 
                                                                   ORDER BY k.ID_Kunjungan DESC");
                            while ($k = mysqli_fetch_assoc($qkunjungan)) {
                                // Otomatis menyeleksi (mengunci) pilihan jika ID dikirim dari halaman kunjungan sebelumnya
                                $selected = ($id_kunjungan_get == $k['ID_Kunjungan']) ? 'selected' : '';
                                $tgl = date('d M Y', strtotime($k['Tanggal_Kunjungan']));
                                echo "<option value='{$k['ID_Kunjungan']}' {$selected}>Pasien: {$k['Nama_Hewan']} (Kunjungan: #KJ-{$k['ID_Kunjungan']} - {$tgl})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-secondary">Jenis Tindakan Medis</label>
                        <select name="id_tindakan" class="form-select" required>
                            <option value="">-- Pilih Jenis Tindakan --</option>
                            <?php
                            $qtindakan = mysqli_query($koneksi, "SELECT * FROM tindakan ORDER BY Nama_Tindakan ASC");
                            while ($t = mysqli_fetch_assoc($qtindakan)) {
                                $harga = number_format($t['Biaya'], 0, ',', '.');
                                echo "<option value='{$t['ID_Tindakan']}'>{$t['Nama_Tindakan']} (Rp {$harga})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" name="tambah" class="btn btn-info w-100 fw-bold text-white" style="height: 38px;">
                            <i class="fa fa-plus me-1"></i> Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="80">ID Detail</th>
                        <th width="120">ID Kunjungan</th>
                        <th>Nama Pasien</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Tindakan Medis</th>
                        <th class="text-end" width="180">Biaya Tindakan</th>
                        <th class="text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil data detail tindakan digabung dengan kunjungan, hewan, dan tindakan medis asli
                    $sql = "SELECT dt.ID_Detail, dt.ID_Kunjungan, k.Tanggal_Kunjungan, h.Nama_Hewan, t.Nama_Tindakan, t.Biaya 
                            FROM detail_tindakan dt
                            JOIN kunjungan k ON dt.ID_Kunjungan = k.ID_Kunjungan
                            JOIN hewan h ON k.ID_Hewan = h.ID_Hewan
                            JOIN tindakan t ON dt.ID_Tindakan = t.ID_Tindakan ";

                    // Filter otomatis: jika diakses lewat tombol kunjungan, tampilkan tindakan pasien itu saja
                    if (!empty($id_kunjungan_get)) {
                        $sql .= " WHERE dt.ID_Kunjungan = '$id_kunjungan_get' ";
                    }

                    $sql .= " ORDER BY dt.ID_Detail DESC";
                    $res = mysqli_query($koneksi, $sql);

                    if (mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $biaya_format = number_format($row['Biaya'], 0, ',', '.');
                            $tgl_format = date('d M Y', strtotime($row['Tanggal_Kunjungan']));
                            echo "<tr>
                                    <td class='text-secondary fw-bold'>#DT-{$row['ID_Detail']}</td>
                                    <td class='fw-bold'>#KJ-{$row['ID_Kunjungan']}</td>
                                    <td class='fw-bold text-dark'>" . htmlspecialchars($row['Nama_Hewan']) . "</td>
                                    <td>{$tgl_format}</td>
                                    <td><span class='badge bg-light text-dark border border-secondary px-2.5 py-1.5 fw-semibold'>{$row['Nama_Tindakan']}</span></td>
                                    <td class='text-end fw-bold text-success'>Rp {$biaya_format}</td>
                                    <td class='text-center'>
                                        <a href='detail_tindakan.php?hapus={$row['ID_Detail']}&ret_kunjungan={$row['ID_Kunjungan']}' class='btn btn-sm btn-danger px-3' style='border-radius: 15px;' onclick='return confirm(\"Apakah Anda yakin ingin menghapus tindakan ini?\")'>
                                            <i class='fa fa-trash-can'></i> Hapus
                                        </a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-4 text-muted'><i class='fa fa-folder-open me-1'></i> Belum ada tindakan medis yang dicatat.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <?php if (!empty($id_kunjungan_get)): ?>
            <div class="text-center mt-3">
                <a href="detail_tindakan.php" class="btn btn-sm btn-outline-primary fw-semibold px-3" style="border-radius: 15px;">
                    <i class="fa fa-globe me-1"></i> Tampilkan Seluruh Tindakan Semua Pasien
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>