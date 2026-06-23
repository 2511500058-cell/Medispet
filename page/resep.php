<?php
session_start();
// Menggunakan require_once dengan path disesuaikan keluar folder ke config
require_once '../config/koneksi.php';

// Proteksi Halaman: Hanya boleh diakses oleh Apoteker
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'apoteker') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrean Resep & Obat - Plotlist Farmasi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Montserrat', sans-serif; color: #495057; }
    </style>
</head>
<body class="bg-light p-4">
<div class="container bg-white p-4 shadow-sm rounded" style="max-width: 800px;">
    <h4 class="fw-bold mb-4"><i class="fa-solid fa-prescription-bottle-medical me-2"></i>Antrean Resep & Obat</h4>
    
    <div class="table-responsive mb-3">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nama Obat</th>
                    <th>Jumlah / Aturan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Amoxicillin Drop</td>
                    <td>2 x Sehari 0.5ml</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <hr class="text-muted">
    
    <div class="mt-3">
        <p class="small text-muted fw-bold mb-1"><i class="fa-solid fa-pen-clip me-1"></i> Catatan Tambahan Dokter:</p>
        <p class="small text-dark font-italic mb-0">"Tolong berikan petunjuk penggunaan spuit (suntikan tanpa jarum) untuk pemberian obat oral kepada pemilik."</p>
    </div>
    
    <div class="mt-4 d-flex justify-content-between align-items-center bg-light p-3 rounded-3 border">
        <div>
            <h6 class="fw-bold mb-0">Total Tagihan Farmasi</h6>
            <h4 class="text-success fw-bold mb-0">Rp 65.000</h4>
        </div>
        <button class="btn btn-success px-4 py-2">
            <i class="fa-solid fa-box-open me-2"></i> Proses & Serahkan Obat
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>