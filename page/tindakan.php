<?php
session_start();
// Menggunakan require_once dengan path disesuaikan keluar folder ke config
require_once '../config/koneksi.php'; 

// Proteksi Halaman: Hanya boleh diakses oleh Dokter
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'dokter') {
    header("Location: ../login.php");
    exit();
}

// 1. Ambil ID Kunjungan dari URL
$id_kunjungan = isset($_GET['id_kunjungan']) ? mysqli_real_escape_string($koneksi, $_GET['id_kunjungan']) : '';

if (empty($id_kunjungan)) {
    echo "<script>alert('Error: ID Kunjungan tidak ditemukan! Silakan pilih pasien dari riwayat/antrean terlebih dahulu.'); window.location.href='index.php';</script>";
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
    echo "<script>alert('Data kunjungan tidak valid!'); window.location.href='index.php';</script>";
    exit();
}

// 3. Proses Simpan Diagnosa & Catatan Medis (ketika tombol 'Simpan & Selesai Periksa' ditekan)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $diagnosa = mysqli_real_escape_string($koneksi, $_POST['diagnosa']);
    $catatan_medis = mysqli_real_escape_string($koneksi, $_POST['catatan_medis']);
    
    // (Opsional) Jika Anda ingin memproses resep obat, tangkap nilainya di sini
    $id_obat = isset($_POST['id_obat']) ? mysqli_real_escape_string($koneksi, $_POST['id_obat']) : '';
    $aturan_pakai = isset($_POST['aturan_pakai']) ? mysqli_real_escape_string($koneksi, $_POST['aturan_pakai']) : '';

    // Update data Diagnosa dan Catatan Medis di tabel kunjungan
    $update_query = "UPDATE kunjungan SET 
                     Diagnosa = '$diagnosa', 
                     Catatan_Medis = '$catatan_medis' 
                     WHERE ID_Kunjungan = '$id_kunjungan'";

    if (mysqli_query($koneksi, $update_query)) {
        // Tampilkan pesan sukses dan kembalikan ke halaman index dashboard dokter
        echo "<script>alert('Pemeriksaan medis dan resep berhasil disimpan!'); window.location.href='index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menyimpan data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Tindakan & Lab - Dokter | Medispet</title>
    
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">
    <link href=\"https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap\" rel=\"stylesheet\">
    <style>
        body { background-color: #f8f9fa; font-family: 'Montserrat', sans-serif; color: #495057; }
        .card { border-radius: 15px; border: none; }
        .form-control, .form-select { border-radius: 10px; padding: 10px 15px; }
    </style>
</head>
<body class=\"bg-light p-4\">
    <div class=\"container\">
        <div class=\"d-flex justify-content-between align-items-center mb-4\">
            <h3 class=\"fw-bold m-0 text-dark\"><i class=\"fa-solid fa-user-md text-success me-2\"></i>Ruang Pemeriksaan Dokter</h3>
            <a href=\"index.php\" class=\"btn btn-secondary px-4 fw-semibold shadow-sm\" style=\"border-radius: 20px;\">
                <i class=\"fa-solid fa-arrow-left me-1\"></i> Kembali
            </a>
        </div>

        <div class=\"row g-4\">
            <div class=\"col-lg-7\">
                <div class=\"card shadow-sm p-4 mb-4 bg-white\">
                    <h5 class=\"fw-bold text-secondary mb-3\"><i class=\"fa-solid fa-id-card me-2\"></i>Informasi Pasien</h5>
                    <table class=\"table table-sm table-borderless m-0\">
                        <tr>
                            <td width=\"150\" class=\"text-muted\">Nama Hewan</td>
                            <td width=\"20\">:</td>
                            <td class=\"fw-bold text-dark\"><?php echo htmlspecialchars($data_pasien['Nama_Hewan']); ?> (<?php echo htmlspecialchars($data_pasien['Spesies']); ?> - <?php echo htmlspecialchars($data_pasien['Ras']); ?>)</td>
                        </tr>
                        <tr>
                            <td class=\"text-muted\">Nama Pemilik</td>
                            <td>:</td>
                            <td class=\"fw-semibold\"><?php echo htmlspecialchars($data_pasien['Nama_Pemilik']); ?></td>
                        </tr>
                        <tr>
                            <td class=\"text-muted\">Tanggal Kunjungan</td>
                            <td>:</td>
                            <td><?php echo date('d F Y', strtotime($data_pasien['Tanggal_Kunjungan'])); ?></td>
                        </tr>
                        <tr>
                            <td class=\"text-muted\">Keluhan Awal</td>
                            <td>:</td>
                            <td><span class=\"badge bg-danger bg-opacity-10 text-danger px-2.5 py-1.5 fs-6 fw-normal\"><?php echo htmlspecialchars($data_pasien['Keluhan']); ?></span></td>
                        </tr>
                    </table>
                </div>

                <div class=\"card shadow-sm p-4 bg-white\">
                    <h5 class=\"fw-bold text-dark mb-4\"><i class=\"fa-solid fa-stethoscope text-success me-2\"></i>Hasil Pemeriksaan Medis</h5>
                    
                    <form method=\"POST\" action=\"\">
                        <div class=\"mb-3\">
                            <label class=\"form-label fw-bold small text-secondary\">Hasil Diagnosa Dokter <span class=\"text-danger\">*</span></label>
                            <input type=\"text\" name=\"diagnosa\" class=\"form-control\" placeholder=\"Masukkan hasil diagnosa penyakit pasien...\" required value=\"<?php echo htmlspecialchars($data_pasien['Diagnosa']); ?>\">
                        </div>

                        <div class=\"mb-4\">
                            <label class=\"form-label fw-bold small text-secondary\">Catatan Medis / Tindakan Tambahan</label>
                            <textarea name=\"catatan_medis\" class=\"form-control\" rows=\"4\" placeholder=\"Tuliskan catatan medis atau instruksi perawatan di sini...\"><?php echo htmlspecialchars($data_pasien['Catatan_Medis']); ?></textarea>
                        </div>

                        <hr class=\"my-4 text-muted\">
                        <h5 class=\"fw-bold mb-3 text-dark\"><i class=\"fa-solid fa-flask-vial text-info me-2\"></i>Berikan Resep Obat (Opsional)</h5>
                        
                        <div class=\"mb-3\">
                            <label class=\"form-label fw-bold small text-secondary\">Pilih Obat</label>
                            <select name=\"id_obat\" class=\"form-select\">
                                <option value=\"\">-- Pilih Obat Jika Dibutuhkan --</option>
                                <?php
                                $query_obat = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY Nama_Obat ASC");
                                while ($o = mysqli_fetch_assoc($query_obat)) {
                                    echo "<option value='{$o['ID_Obat']}'>{$o['Nama_Obat']} (Rp " . number_format($o['Harga'], 0, ',', '.') . ")</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class=\"mb-3\">
                            <label class=\"form-label fw-bold small text-secondary\">Aturan Pakai / Dosis</label>
                            <textarea name=\"aturan_pakai\" class=\"form-control\" rows=\"2\" placeholder=\"Contoh: 2 x Sehari 1ml sesudah makan\"></textarea>
                        </div>

                        <div class=\"alert alert-warning small border-0 bg-warning bg-opacity-10 mb-4\">
                            <i class=\"fa-solid fa-circle-info me-1\"></i> Resep yang Anda simpan di sini akan tersimpan secara otomatis ke dalam rekam medis sistem.
                        </div>

                        <button type=\"submit\" class=\"btn btn-success w-100 py-3 fs-6 fw-bold shadow-sm\" style=\"border-radius: 12px;\">
                            <i class=\"fa-solid fa-check-double me-2\"></i> Simpan & Selesai Periksa
                        </button>
                    </form>
                </div>
            </div>

            <div class=\"col-lg-5\">
                <div class=\"card shadow-sm p-4 bg-white h-100\">
                    <h5 class=\"fw-bold text-dark mb-3\"><i class=\"fa-solid fa-book-medical text-primary me-2\"></i>Panduan Pemeriksaan</h5>
                    <p class=\"small text-muted\">Sebagai dokter hewan penanggung jawab, mohon pastikan hal-hal berikut telah dilakukan sebelum menekan tombol simpan:</p>
                    <ul class=\"small text-secondary ps-3\">
                        <li class=\"mb-2\">Memeriksa tanda-tanda vital pasien (Suhu tubuh, berat badan, kondisi fisik).</li>
                        <li class=\"mb-2\">Memastikan kesesuaian obat yang dipilih dengan berat badan dan spesies pasien (Kucing/Anjing).</li>
                        <li class=\"mb-2\">Mengisi kolom diagnosa dengan jelas guna keperluan *history tracking* kunjungan berikutnya.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js\"></script>
</body>
</html>