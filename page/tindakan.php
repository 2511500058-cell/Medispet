<?php
// 1. FITUR PELACAK ERROR (Memaksa PHP menampilkan pesan error asli jika ada salah ketik kode/tabel)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 2. PATH KONEKSI YANG BENAR: Karena file ini ada di dalam folder 'page/', 
// kita harus mundur 1 folder (../) untuk memanggil folder 'config/' yang berada di luar
include '../config/koneksi.php'; 

// Proteksi Halaman: Hanya boleh diakses oleh Dokter
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'dokter') {
    header("Location: ../login.php");
    exit();
}

// 1. Ambil ID Kunjungan dari URL
$id_kunjungan = isset($_GET['id_kunjungan']) ? mysqli_real_escape_string($koneksi, $_GET['id_kunjungan']) : '';

if (empty($id_kunjungan)) {
    echo "<script>alert('Error: ID Kunjungan tidak ditemukan! Silakan pilih pasien dari riwayat/antrean terlebih dahulu.'); window.location.href='../index.php';</script>";
    exit();
}

// 2. Ambil data detail pasien yang sedang diperiksa berdasarkan ID Kunjungan tersebut
$query_pasien = mysqli_query($koneksi, "SELECT k.*, h.Nama_Hewan, h.Spesies, h.Ras, p.Nama_Pemilik 
                                        FROM kunjungan k
                                        JOIN hewan h ON k.ID_Hewan = h.ID_Hewan
                                        JOIN pemilik p ON h.ID_Pemilik = p.ID_Pemilik
                                        WHERE k.ID_Kunjungan = '$id_kunjungan'");
$data_pasien = mysqli_fetch_assoc($query_pasien);

if (!$data_pasien) {
    echo "<script>alert('Data kunjungan pasien tidak ditemukan di database.'); window.location.href='../index.php';</script>";
    exit();
}

// 3. PROSES SIMPAN PEMERIKSAAN DAN TINDAKAN MEDIS
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $diagnosa = mysqli_real_escape_string($koneksi, $_POST['diagnosa']);
    $catatan_medis = mysqli_real_escape_string($koneksi, $_POST['catatan_medis']);
    $tindakan_dipilih = isset($_POST['id_tindakan']) ? $_POST['id_tindakan'] : []; // Array ID tindakan yang dicentang

    // Mulai Transaksi Database agar aman
    mysqli_begin_transaction($koneksi);

    try {
        // A. Update data diagnosa dan catatan medis pada tabel kunjungan
        $update_kunjungan = "UPDATE kunjungan SET 
                             Diagnosa = '$diagnosa', 
                             Catatan_Medis = '$catatan_medis' 
                             WHERE ID_Kunjungan = '$id_kunjungan'";
        mysqli_query($koneksi, $update_kunjungan);

        // B. Hapus detail tindakan lama jika sebelumnya pernah diisi (mencegah duplikasi data saat edit)
        mysqli_query($koneksi, "DELETE FROM detail_tindakan WHERE ID_Kunjungan = '$id_kunjungan'");

        // C. Simpan tindakan-tindakan baru yang dipilih ke dalam tabel detail_tindakan
        if (!empty($tindakan_dipilih)) {
            foreach ($tindakan_dipilih as $id_tindakan) {
                $id_tindakan = mysqli_real_escape_string($koneksi, $id_tindakan);
                $insert_detail = "INSERT INTO detail_tindakan (ID_Kunjungan, ID_Tindakan) VALUES ('$id_kunjungan', '$id_tindakan')";
                mysqli_query($koneksi, $insert_detail);
            }
        }

        // Jika semua sukses, commit data ke database
        mysqli_commit($koneksi);
        echo "<script>alert('Pemeriksaan pasien dan detail tindakan berhasil disimpan!'); window.location.href='../index.php';</script>";
        exit();

    } catch (Exception $e) {
        // Jika ada yang error, batalkan semua perubahan
        mysqli_rollback($koneksi);
        echo "<script>alert('Gagal menyimpan pemeriksaan medis: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan Medis & Tindakan Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light py-5">
    <div class="container">
        <div class="mb-4">
            <a href="../index.php" class="btn btn-outline-secondary px-4 fw-bold shadow-sm" style="border-radius: 10px;">
                <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-body bg-white p-4" style="border-radius: 15px;">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <span class="badge bg-primary px-3 py-2 mb-2 fw-semibold">ID Kunjungan: #KJ-<?= $data_pasien['ID_Kunjungan']; ?></span>
                        <h3 class="fw-bold text-dark m-0 mb-1"><i class="fa-solid fa-paw text-warning me-2"></i><?= htmlspecialchars($data_pasien['Nama_Hewan']); ?></h3>
                        <p class="text-secondary m-0">Spesies/Ras: <strong><?= htmlspecialchars($data_pasien['Spesies']); ?> (<?= htmlspecialchars($data_pasien['Ras']); ?>)</strong> | Pemilik: <strong><?= htmlspecialchars($data_pasien['Nama_Pemilik']); ?></strong></p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <p class="small text-muted mb-0">Tanggal Pendaftaran</p>
                        <h6 class="fw-bold text-secondary"><i class="fa-regular fa-calendar-check me-1"></i> <?= date('d M Y', strtotime($data_pasien['Tanggal_Kunjungan'])); ?></h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm border-0 p-4 bg-white" style="border-radius: 15px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-file-medical text-success me-2"></i>Form Input Pemeriksaan Dokter</h5>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Keluhan Awal Pasien (Dari Admin)</label>
                            <div class="p-3 bg-light border text-dark rounded-3 font-monospace small">
                                <?= htmlspecialchars($data_pasien['Keluhan']); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Diagnosa Dokter <span class="text-danger">*</span></label>
                            <textarea name="diagnosa" class="form-control" rows="3" placeholder="Tuliskan hasil analisa penyakit hewan pasien di sini..." style="border-radius: 10px;" required><?= htmlspecialchars($data_pasien['Diagnosa']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark d-block small">Pilih Tindakan Medis & Terapi <span class="text-danger">* (Bisa pilih lebih dari satu)</span></label>
                            <div class="p-3 bg-light border rounded-3" style="max-height: 200px; overflow-y: auto;">
                                <?php
                                // Ambil daftar semua tindakan dari tabel tindakan
                                $query_tindakan = mysqli_query($koneksi, "SELECT * FROM tindakan ORDER BY Nama_Tindakan ASC");
                                
                                // Ambil daftar tindakan yang sudah pernah dipilih sebelumnya untuk pasien ini
                                $tindakan_terpilih_sebelumnya = [];
                                $cek_tindakan_lama = mysqli_query($koneksi, "SELECT ID_Tindakan FROM detail_tindakan WHERE ID_Kunjungan = '$id_kunjungan'");
                                while($dt = mysqli_fetch_assoc($cek_tindakan_lama)){
                                    $tindakan_terpilih_sebelumnya[] = $dt['ID_Tindakan'];
                                }

                                if(mysqli_num_rows($query_tindakan) > 0) {
                                    while($tindakan = mysqli_fetch_assoc($query_tindakan)) {
                                        $checked = in_array($tindakan['ID_Tindakan'], $tindakan_terpilih_sebelumnya) ? "checked" : "";
                                        ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="id_tindakan[]" value="<?= $tindakan['ID_Tindakan']; ?>" id="tindakan_<?= $tindakan['ID_Tindakan']; ?>" <?= $checked; ?>>
                                            <label class="form-check-label text-dark small" for="tindakan_<?= $tindakan['ID_Tindakan']; ?>">
                                                <strong><?= htmlspecialchars($tindakan['Nama_Tindakan']); ?></strong> 
                                                <span class="text-muted">(Rp <?= number_format($tindakan['Biaya'], 0, ',', '.'); ?>)</span>
                                            </label>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo "<small class='text-danger d-block'>Belum ada master data tindakan medis di sistem. Silakan laporkan admin.</small>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small">Catatan Medis Tambahan / Resep Obat</label>
                            <textarea name="catatan_medis" class="form-control" rows="3" placeholder="Tulis instruksi obat, dosis perawatan rumah, atau catatan penunjang lainnya..." style="border-radius: 10px;"><?= htmlspecialchars($data_pasien['Catatan_Medis']); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 fs-6 fw-bold shadow-sm" style="border-radius: 12px;">
                            <i class="fa-solid fa-check-double me-2"></i> Simpan & Selesai Periksa
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 p-4 bg-white h-100" style="border-radius: 15px;">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-book-medical text-primary me-2"></i>Panduan Pemeriksaan</h5>
                    <p class="small text-muted">Sebagai dokter hewan penanggung jawab, mohon pastikan hal-hal berikut telah dilakukan sebelum menekan tombol simpan:</p>
                    <ul class="small text-secondary ps-3">
                        <li class="mb-2">Memeriksa tanda-tanda vital pasien (Suhu tubuh, berat badan, kondisi fisik).</li>
                        <li class="mb-2">Mencentang satu atau beberapa jenis tindakan medis yang diberikan kepada hewan pasien untuk kalkulasi tagihan oleh kasir/admin.</li>
                        <li class="mb-2">Mengisi kolom diagnosa dengan jelas guna keperluan <em>history tracking</em> kunjungan medis berikutnya.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>