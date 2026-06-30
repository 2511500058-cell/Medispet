<?php
session_start();


if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: ../login.php"); 
    exit();
}


$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';


include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>Dashboard Medispet</title>

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-plot-listing.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <link rel="stylesheet" href="assets/css/owl.css">

  </head>

<body>

  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <a href="index.php" class="logo">
            </a>
            <ul class="nav">
              <li><a href="index.php" class="active">Home</a></li>
              <li><a href="category.html">Category</a></li>
              <li><a href="listing.html">Listing</a></li>
              <li><a href="contact.html">Contact</a></li> 
              <li><div class="main-white-button"><a href="logout.php"><i class="fa fa-sign-out"></i> Log Out</a></div></li> 
            </ul>        
            <a class='menu-trigger'>
                <span>Menu</span>
            </a>
            </nav>
        </div>
      </div>
    </div>
  </header>
  <div class="main-banner">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="top-text header-text">
            <h6>Selamat Datang kembali, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h6>
            
            <?php if ($role === 'admin'): ?>
                <h2>Panel Kendali Administrator</h2>
            <?php elseif ($role === 'dokter'): ?>
                <h2>Daftar Antrean Pasien Hari Ini</h2>
            <?php elseif ($role === 'pasien'): ?>
                <h2>Riwayat Pemeriksaan Hewan Anda</h2>
            <?php else: ?>
                <h2>Dashboard Medispet</h2>
            <?php endif; ?>
          </div>
        </div>
        
        <div class="col-lg-12 mt-5">
          <div class="card border-0 shadow-sm p-4 bg-white text-dark" style="border-radius: 20px;">
            
          <?php 
          
          
          if ($role == 'admin') { 
          ?>
              <h5 class="fw-bold text-primary mb-4"><i class="fa fa-cogs me-2"></i>Manajemen Data Master & Administrasi</h5>
              <p class="text-muted mb-4">Sebagai Administrator, Anda memiliki akses penuh untuk mengelola pengguna, pendaftaran antrean, obat, dan validasi tagihan tindakan klinik.</p>
              
              <div class="row g-3 text-center justify-content-center align-items-stretch">
                  <div class="col-md-4 d-flex">
                      <a href="page/data_dokter.php" class="btn btn-outline-primary w-100 py-3 fw-bold shadow-sm h-100 d-flex flex-column justify-content-center align-items-center" style="border-radius: 15px;">
                          <i class="fa fa-user-md d-block mb-2" style="font-size: 2rem;"></i> 
                          Kelola Dokter
                      </a>
                  </div>
                  <div class="col-md-4 d-flex">
                      <a href="page/data_pemilik.php" class="btn btn-outline-primary w-100 py-3 fw-bold shadow-sm h-100 d-flex flex-column justify-content-center align-items-center" style="border-radius: 15px;">
                          <i class="fa fa-user d-block mb-2" style="font-size: 2rem;"></i> 
                          Kelola Pemilik
                      </a>
                  </div>
                  <div class="col-md-4 d-flex">
                      <a href="page/data_hewan.php" class="btn btn-outline-primary w-100 py-3 fw-bold shadow-sm h-100 d-flex flex-column justify-content-center align-items-center" style="border-radius: 15px;">
                          <i class="fa fa-paw d-block mb-2" style="font-size: 2rem;"></i> 
                          Kelola Hewan
                      </a>
                  </div>

                  <div class="col-md-4 d-flex">
                      <a href="page/data_obat.php" class="btn btn-outline-primary w-100 py-3 fw-bold shadow-sm h-100 d-flex flex-column justify-content-center align-items-center" style="border-radius: 15px;">
                          <i class="fa fa-medkit d-block mb-2" style="font-size: 2rem;"></i> 
                          Kelola Data Obat
                      </a>
                  </div>
                  <div class="col-md-4 d-flex">
                      <a href="page/data_kunjungan.php" class="btn btn-outline-primary w-100 py-3 fw-bold shadow-sm h-100 d-flex flex-column justify-content-center align-items-center" style="border-radius: 15px;">
                          <i class="fa fa-list-alt d-block mb-2" style="font-size: 2rem;"></i> 
                          Pendaftaran Kunjungan
                      </a>
                  </div>
              </div>
          <?php 
          } 
          ?>

          <?php 
          if ($role == 'dokter') { 
              
              $username_login = mysqli_real_escape_string($koneksi, $_SESSION['username']);
          ?>
              <div class="table-responsive">
                  <table class="table table-hover align-middle m-0">
                      <thead class="table-light">
                          <tr>
                              <th>ID Kunjungan</th>
                              <th>Tanggal</th>
                              <th>Nama Hewan Pasien</th>
                              <th>Keluhan Awal</th>
                              <th>Status Medis</th>
                              <th class="text-center">Aksi Medis</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                          
                          $query_antrean = "SELECT k.*, h.Nama_Hewan 
                                            FROM kunjungan k 
                                            JOIN hewan h ON k.ID_Hewan = h.ID_Hewan 
                                            JOIN dokter d ON k.ID_Dokter = d.ID_Dokter
                                            WHERE LOWER(REPLACE(REPLACE(REPLACE(d.Nama_Dokter, ' ', ''), '.', ''), ',', '')) = LOWER('$username_login')
                                            ORDER BY k.ID_Kunjungan DESC";
                                            
                          $res_dokter = mysqli_query($koneksi, $query_antrean);

                          if (mysqli_num_rows($res_dokter) > 0) {
                              while($row = mysqli_fetch_assoc($res_dokter)) {
                                  $status = empty($row['Diagnosa']) ? "<span class='badge bg-warning text-dark px-3 py-2'>Menunggu Pemeriksaan</span>" : "<span class='badge bg-success px-3 py-2'>Selesai Diperiksa</span>";
                                  echo "<tr>
                                          <td class='fw-bold text-secondary'>#KJ-{$row['ID_Kunjungan']}</td>
                                          <td>" . date('d M Y', strtotime($row['Tanggal_Kunjungan'])) . "</td>
                                          <td class='fw-bold text-dark'>" . htmlspecialchars($row['Nama_Hewan']) . "</td>
                                          <td>" . htmlspecialchars($row['Keluhan']) . "</td>
                                          <td>{$status}</td>
                                          <td class='text-center'>
                                              <a href='page/tindakan.php?id_kunjungan={$row['ID_Kunjungan']}' class='btn btn-primary btn-sm px-3 fw-bold' style='border-radius: 15px;'>
                                                 Periksa Pasien
                                              </a>
                                          </td>
                                        </tr>";
                              }
                          } else {
                              echo "<tr><td colspan='6' class='text-center py-4 text-muted'><i class='fa fa-folder-open me-1'></i> Belum ada pasien masuk hari ini.</td></tr>";
                          }
                          ?>
                      </tbody>
                  </table>
              </div>
          <?php 
          } 
          ?>

          <?php 
          if ($role == 'pasien') { 
              
              $username_login = mysqli_real_escape_string($koneksi, $_SESSION['username']);
          ?>
              <h5 class="fw-bold text-success mb-3"><i class="fa fa-paw me-2"></i>Rekam Medis Hewan Kesayangan Anda</h5>
              <p class="text-muted mb-4 small">Di bawah ini merupakan riwayat lengkap diagnosis medis, resep obat, serta catatan penanganan dari dokter untuk hewan peliharaan Anda.</p>
              
              <div class="table-responsive">
                  <table class="table table-hover align-middle m-0">
                      <thead class="table-light">
                          <tr>
                              <th>Tanggal Periksa</th>
                              <th>Nama Hewan</th>
                              <th>Dokter Pemeriksa</th>
                              <th>Keluhan Anda</th>
                              <th>Hasil Diagnosa Dokter</th>
                              <th>Catatan Perawatan</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                          
                          $query_pasien = "SELECT k.*, h.Nama_Hewan, d.Nama_Dokter 
                                           FROM kunjungan k 
                                           JOIN hewan h ON k.ID_Hewan = h.ID_Hewan 
                                           JOIN pemilik p ON h.ID_Pemilik = p.ID_Pemilik 
                                           LEFT JOIN dokter d ON k.ID_Dokter = d.ID_Dokter 
                                           WHERE LOWER(REPLACE(REPLACE(REPLACE(p.Nama_Pemilik, ' ', ''), '.', ''), ',', '')) = LOWER('$username_login')
                                           ORDER BY k.ID_Kunjungan DESC";
                          $res_pasien = mysqli_query($koneksi, $query_pasien);

                          if (mysqli_num_rows($res_pasien) > 0) {
                              while($row = mysqli_fetch_assoc($res_pasien)) {
                                  $diagnosa = empty($row['Diagnosa']) ? "<em class='text-muted small'>Sedang diproses...</em>" : "<strong>" . htmlspecialchars($row['Diagnosa']) . "</strong>";
                                  $catatan = empty($row['Catatan_Medis']) ? "<em class='text-muted small'>Tidak ada catatan</em>" : htmlspecialchars($row['Catatan_Medis']);
                                  
                                  echo "<tr>
                                          <td>" . date('d M Y', strtotime($row['Tanggal_Kunjungan'])) . "</td>
                                          <td class='fw-bold text-success'>" . htmlspecialchars($row['Nama_Hewan']) . "</td>
                                          <td>" . htmlspecialchars($row['Nama_Dokter'] ?? 'Belum Ditentukan') . "</td>
                                          <td>" . htmlspecialchars($row['Keluhan']) . "</td>
                                          <td>{$diagnosa}</td>
                                          <td>{$catatan}</td>
                                        </tr>";
                              }
                          } else {
                              echo "<tr><td colspan='6' class='text-center py-4 text-muted'><i class='fa fa-folder-open me-1'></i> Anda belum memiliki riwayat rekam medis hewan.</td></tr>";
                          }
                          ?>
                      </tbody>
                  </table>
              </div>
          <?php 
          } 
          ?>

          </div>
        </div>
      </div>
    </div>
  </div>

<footer class="footer mt-auto py-5 bg-light border-top">
    <div class="container">
        <div class="row gy-4">
            
            <div class="col-lg-4 col-md-6">
                <div class="about-widget pe-lg-4">
                    <h5 class="fw-bold mb-3" style="color: #2c3e50;">
                        <i class="fa fa-paw me-2 text-primary"></i>Medispet
                    </h5>
                    <p class="text-secondary small lh-lg mb-4">
                        Sistem Informasi Rekam Medis Klinik Hewan. Kami memberikan kemudahan dalam mengelola data pasien, riwayat kesehatan, dan administrasi klinik secara terintegrasi.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary hover-primary"><i class="fa-brands fa-whatsapp fs-5"></i></a>
                        <a href="#" class="text-secondary hover-primary"><i class="fa-brands fa-instagram fs-5"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="ps-lg-3">
                    <h6 class="fw-bold text-dark mb-3">Info Klinik</h6>
                    <ul class="list-unstyled text-secondary small lh-lg">
                        <li><i class="fa fa-clock me-2 text-warning"></i> Senin - Jumat: 08:00 - 20:00</li>
                        <li><i class="fa fa-clock me-2 text-warning"></i> Sabtu - Minggu: 09:00 - 15:00</li>
                        <li class="mt-2"><i class="fa fa-map-marker-alt me-2 text-danger"></i> Jl. Satwa Sehat No. 123</li>
                    </ul>
                </div>
            </div> 

            <div class="col-lg-4 col-md-12">
                <div class="about-widget">
                    <div class="card border-0 shadow-sm bg-white hover-card" style="border-radius: 16px;">
                        <div class="card-body p-4 text-center">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fa fa-user-circle me-1 text-primary"></i> Sesi Aktif
                            </h6>
                            
                            <span class="badge bg-success px-3 py-2 fw-bold mb-3 d-inline-block text-white" style="font-size: 11px; border-radius: 30px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fa fa-circle me-1 text-warning" style="font-size: 7px; vertical-align: middle;"></i> 
                                <?php echo isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'ADMIN'; ?>
                            </span>
                            
                            <div class="p-2 mb-4 text-secondary small" style="border-radius: 10px; background-color: #f8f9fa; border: 1px dashed #dee2e6;">
                                Username: <span class="fw-bold text-primary">@<?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            </div>
                            
                            <div class="d-grid">
                                <a href="logout.php" class="btn btn-outline-danger fw-bold py-2 shadow-sm btn-logout" 
                                   style="border-radius: 10px; font-size: 13px; transition: all 0.3s ease;" 
                                   onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem Medispet?')">
                                    <i class="fa fa-sign-out-alt me-2"></i> Keluar Aplikasi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5 pt-4 border-top">
            <div class="col-12 text-center">
                <p class="text-secondary small mb-0">
                    &copy; <?php echo date('Y'); ?> <strong>Medispet</strong>. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>
                    
<style>
    .hover-primary:hover { color: #0d6efd !important; transition: 0.3s ease; }
    .hover-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .btn-logout:hover { background-color: #dc3545; color: white !important; }
</style>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/custom.js"></script>

</body>
</html>