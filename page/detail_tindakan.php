<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Proteksi Halaman Admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config/koneksi.php';

$id_kunjungan = isset($_GET['id_kunjungan']) ? mysqli_real_escape_string($koneksi, $_GET['id_kunjungan']) : '';
if (empty($id_kunjungan)) {
    echo "<script>alert('Error: ID Kunjungan tidak ditemukan!'); window.location.href='data_kunjungan.php';</script>";
    exit();
}

// ================= PROSES TAMBAH OBAT KE KERANJANG KASIR =================
if (isset($_POST['simpan_obat'])) {
    $id_obat = mysqli_real_escape_string($koneksi, $_POST['id_obat']);
    $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah']);

    // Ambil harga satuan obat dari database
    $q_harga = mysqli_query($koneksi, "SELECT Harga FROM obat WHERE ID_Obat = '$id_obat'");
    if ($row = mysqli_fetch_assoc($q_harga)) {
        $total_harga = $row['Harga'] * $jumlah;
        // Simpan ke detail kunjungan
        mysqli_query($koneksi, "INSERT INTO detail_obat (ID_Kunjungan, ID_Obat, Jumlah, Total_Harga) VALUES ('$id_kunjungan', '$id_obat', '$jumlah', '$total_harga')");
    }
    header("Location: detail_tindakan.php?id_kunjungan=$id_kunjungan");
    exit();
}

// ================= PROSES HAPUS OBAT DARI KERANJANG =================
if (isset($_GET['hapus_obat'])) {
    $id_do = mysqli_real_escape_string($koneksi, $_GET['hapus_obat']);
    mysqli_query($koneksi, "DELETE FROM detail_obat WHERE ID_Detail_Obat = '$id_do'");
    header("Location: detail_tindakan.php?id_kunjungan=$id_kunjungan");
    exit();
}

// AMBIL DATA KUNJUNGAN UTAMA
$query_medis = mysqli_query($koneksi, "SELECT k.*, h.Nama_Hewan, h.Spesies, h.Ras, p.Nama_Pemilik, d.Nama_Dokter 
                                       FROM kunjungan k
                                       JOIN hewan h ON k.ID_Hewan = h.ID_Hewan
                                       JOIN pemilik p ON h.ID_Pemilik = p.ID_Pemilik
                                       JOIN dokter d ON k.ID_Dokter = d.ID_Dokter
                                       WHERE k.ID_Kunjungan = '$id_kunjungan'");
$data_medis = mysqli_fetch_assoc($query_medis);

if (!$data_medis) {
    echo "<script>alert('Data pemeriksaan medis tidak ditemukan.'); window.location.href='data_kunjungan.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruksi Apotek & Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .card { border-radius: 15px; border: none; }
        .bg-gradient-admin { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }
        @media print {
            .no-print { display: none !important; }
            body { background-color: #fff; }
            .card { box-shadow: none !important; border: 1px solid #ddd; }
        }
    </style>
</head>
<body class="py-5">

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="data_kunjungan.php" class="btn btn-outline-secondary px-4 fw-bold shadow-sm" style="border-radius: 10px;">
                <i class="fa-solid fa-arrow-left me-2"></i>Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary px-4 fw-bold shadow-sm" style="border-radius: 10px;">
                <i class="fa-solid fa-print me-2"></i>Cetak Struk Kasir
            </button>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body bg-gradient-admin text-white p-4" style="border-radius: 15px;">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <span class="badge bg-warning text-dark px-3 py-2 mb-2 fw-bold">NO. TRX: #KJ-<?= $data_medis['ID_Kunjungan']; ?></span>
                        <h2 class="fw-bold m-0"><i class="fa-solid fa-paw me-2"></i><?= htmlspecialchars($data_medis['Nama_Hewan']); ?></h2>
                        <p class="m-0 mt-1 opacity-75">
                            Pemilik: <strong><?= htmlspecialchars($data_medis['Nama_Pemilik']); ?></strong>
                        </p>
                    </div>
                    <div class="col-md-5 text-md-end mt-3 mt-md-0 border-start border-light border-opacity-25 ps-md-4">
                        <p class="small mb-1 opacity-75">Dokter Pemeriksa:</p>
                        <h5 class="fw-bold m-0 mb-2"><i class="fa-solid fa-user-doctor me-1"></i> <?= htmlspecialchars($data_medis['Nama_Dokter']); ?></h5>
                        <p class="small mb-0 opacity-75"><i class="fa-regular fa-calendar me-1"></i> Tgl: <?= date('d M Y', strtotime($data_medis['Tanggal_Kunjungan'])); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7 mb-4">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">
                            <i class="fa-solid fa-user-md me-2"></i>Instruksi & Resep Dokter
                        </h5>
                        <div class="p-3 bg-light rounded-3 text-dark mb-3">
                            <span class="fw-bold small text-muted d-block mb-1">Hasil Diagnosa:</span>
                            <?= empty($data_medis['Diagnosa']) ? '<em class="text-danger">Belum ada diagnosa</em>' : htmlspecialchars($data_medis['Diagnosa']); ?>
                        </div>
                        <div class="p-3 bg-light border border-danger border-opacity-25 rounded-3">
                            <span class="fw-bold small text-muted d-block mb-1">R/ Racikan Obat:</span>
                            <p class="text-dark font-monospace m-0" style="white-space: pre-line; line-height: 1.6;">
                                <?= empty($data_medis['Catatan_Medis']) ? '<em class="text-secondary">Tidak ada resep obat</em>' : htmlspecialchars($data_medis['Catatan_Medis']); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 no-print" style="background-color: #e9f7ef;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-pills me-2"></i>Apoteker: Masukkan Obat Pasien</h6>
                        <form method="POST" action="" class="row g-2 align-items-end">
                            <div class="col-md-7">
                                <label class="form-label small fw-bold">Pilih Obat (Dari Master Data)</label>
                                <select name="id_obat" class="form-select" required>
                                    <option value="">-- Pilih Obat --</option>
                                    <?php
                                    $q_obat = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY Nama_Obat ASC");
                                    while ($ob = mysqli_fetch_assoc($q_obat)) {
                                        echo "<option value='{$ob['ID_Obat']}'>{$ob['Nama_Obat']} - Rp " . number_format($ob['Harga'], 0, ',', '.') . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Jumlah (Qty)</label>
                                <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="simpan_obat" class="btn btn-success w-100 fw-bold"><i class="fa fa-plus"></i> Add</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4 bg-white" style="border-radius: 15px;">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fa-solid fa-receipt text-primary me-2"></i>Rincian Tagihan Kasir
                        </h5>

                        <h6 class="small fw-bold text-muted mb-2">1. Tindakan Medis</h6>
                        <table class="table table-sm table-borderless mb-3">
                            <tbody>
                                <?php
                                $total_tindakan = 0;
                                $qtindakan = mysqli_query($koneksi, "SELECT t.Nama_Tindakan, t.Biaya FROM detail_tindakan dt JOIN tindakan t ON dt.ID_Tindakan = t.ID_Tindakan WHERE dt.ID_Kunjungan = '$id_kunjungan'");
                                if (mysqli_num_rows($qtindakan) > 0) {
                                    while ($t = mysqli_fetch_assoc($qtindakan)) {
                                        $total_tindakan += $t['Biaya'];
                                        echo "<tr>
                                                <td class='small'>- {$t['Nama_Tindakan']}</td>
                                                <td class='small text-end fw-semibold'>Rp " . number_format($t['Biaya'], 0, ',', '.') . "</td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td class='small text-muted italic'>Tidak ada tindakan medis berbayar.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>

                        <h6 class="small fw-bold text-muted mb-2">2. Resep Obat</h6>
                        <table class="table table-sm table-borderless mb-4 border-bottom pb-2">
                            <tbody>
                                <?php
                                $total_obat = 0;
                                $qdetail_obat = mysqli_query($koneksi, "SELECT do.ID_Detail_Obat, do.Jumlah, do.Total_Harga, o.Nama_Obat FROM detail_obat do JOIN obat o ON do.ID_Obat = o.ID_Obat WHERE do.ID_Kunjungan = '$id_kunjungan'");
                                if (mysqli_num_rows($qdetail_obat) > 0) {
                                    while ($do = mysqli_fetch_assoc($qdetail_obat)) {
                                        $total_obat += $do['Total_Harga'];
                                        echo "<tr>
                                                <td class='small'>- {$do['Nama_Obat']} (x{$do['Jumlah']})</td>
                                                <td class='small text-end fw-semibold'>Rp " . number_format($do['Total_Harga'], 0, ',', '.') . "</td>
                                                <td class='text-end no-print' style='width: 30px;'>
                                                    <a href='detail_tindakan.php?id_kunjungan={$id_kunjungan}&hapus_obat={$do['ID_Detail_Obat']}' class='text-danger'><i class='fa fa-times-circle'></i></a>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td class='small text-muted italic'>Belum ada obat yang dimasukkan.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>

                        <?php $grand_total = $total_tindakan + $total_obat; ?>
                        <div class="p-3 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-25 d-flex justify-content-between align-items-center mt-auto">
                            <span class="fw-bold text-primary">GRAND TOTAL:</span>
                            <h4 class="fw-extrabold text-primary m-0">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h4>
                        </div>
                        <p class="text-center text-muted mt-3 small italic no-print">
                            <i class="fa-solid fa-check-circle me-1"></i>Pastikan obat yang diinput sudah sesuai dengan instruksi dokter.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>