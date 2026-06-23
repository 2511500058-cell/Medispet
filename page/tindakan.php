<?php
session_start();
// Menggunakan require_once dengan path disesuaikan keluar folder ke config
require_once '../config/koneksi.php'; 

// Proteksi Halaman: Hanya boleh diakses oleh Dokter
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'dokter') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tindakan & Lab - Dokter | Plotlist</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Montserrat', sans-serif; color: #495057; }
    </style>
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 800px;">
        <h4 class="fw-bold mb-4"><i class="fa-solid fa-flask-vial me-2"></i>Tindakan & Lab Dokter</h4>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Pilih Obat</label>
                <select name="id_obat" class="form-select mb-2">
                    <option value="">-- Pilih Obat --</option>
                    <option value="1">Amoxicillin Drop</option>
                    <option value="2">Vitamax Syrup</option>
                    <option value="3">Obat Cacing Drontal</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Aturan Pakai / Dosis</label>
                <textarea name="aturan_pakai" class="form-control" rows="2" placeholder="Contoh: 2 x Sehari 1ml sesudah makan"></textarea>
            </div>

            <div class="alert alert-warning small border-0 bg-warning bg-opacity-10 mb-auto">
                <i class="fa-solid fa-circle-info me-1"></i> Resep yang disimpan akan otomatis masuk ke antrean Apoteker.
            </div>

            <hr class="my-4 text-muted">

            <button type="submit" class="btn btn-success w-100 py-3 fs-6">
                <i class="fa-solid fa-check-double me-2"></i> Selesaikan Pemeriksaan
            </button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>