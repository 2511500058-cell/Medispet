<?php
session_start();
// Menggunakan require_once dengan path disesuaikan keluar folder ke config
require_once '../config/koneksi.php'; 

// Proteksi Halaman: Hanya boleh diakses oleh Dokter
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'dokter') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['simpan_diagnosa'])) {
    // Silakan tambahkan logika pemrosesan query simpan Anda di sini jika diperlukan
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan & Diagnosa - Plotlist Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-light p-4">
    <form method="POST" action="">
        <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 800px;">
            <h4 class="fw-bold mb-4"><i class="fa-solid fa-stethoscope me-2"></i>Pemeriksaan & Diagnosa</h4>
            
            <div class="mb-3">
                <label class="form-label">Hasil Diagnosa Penyakit</label>
                <input type="text" name="diagnosa" class="form-control" placeholder="Contoh: Feline Panleukopenia Virus (FPV)" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Tindakan Medis / Pengobatan di Klinik</label>
                <textarea name="tindakan" class="form-control" rows="3" placeholder="Tindakan yang dilakukan hari ini (misal: Suntik antiemetik, infus cairan...)" required></textarea>
            </div>

            <hr class="text-muted mb-4">

            <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-pills me-2"></i>Resep Obat (Rawat Jalan)</h6>
            <div class="mb-4">
                <textarea name="resep_obat" class="form-control bg-light" rows="3" placeholder="Tuliskan nama obat, dosis, dan aturan pakai. (Contoh: Amoxicillin 2x sehari 0.5ml)"></textarea>
            </div>

            <div class="d-flex justify-content-end mt-2">
                <button type="submit" name="simpan_diagnosa" class="btn btn-success px-5 py-2 rounded-pill">
                    <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Rekam Medis
                </button>
            </div>
        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>