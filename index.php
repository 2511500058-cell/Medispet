<?php
session_start();

// Cek apakah user sudah login lewat session, jika belum, lempar balik ke login.php
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: ../login.php"); // Diubah menuju root
    exit();
}

// Ambil role pengguna dari session untuk mengatur hak akses tampilan
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
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

    <title>Plot Listing HTML5 Website Template</title>

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
              <li><a href="index.php" class="active">Dashboard</a></li>
              
              <?php if ($role == 'admin') : ?>
                  <li><a href="index.php">Data Master</a></li>
                  <li><a href="index.php">Bantuan Layanan</a></li>
                  <li><div class="main-white-button"><a href="page/data_kunjungan.php"><i class="fa fa-plus"></i> Registrasi Kunjungan</a></div></li> 
              
              <?php elseif ($role == 'dokter') : ?>
                  <li><a href="index.php">Antrian Pasien</a></li>
                  <li><a href="index.php">Rekam Medis</a></li>
                  <li><div class="main-white-button"><a href="#"><i class="fa fa-stethoscope"></i> Mulai Pemeriksaan</a></div></li> 
              
              <?php elseif ($role == 'pasien') : ?>
                  <li><a href="index.php">Riwayat Kunjungan</a></li>
                  <li><a href="index.php">Bantuan Layanan</a></li>
                  <li><div class="main-white-button"><a href="#"><i class="fa fa-calendar"></i> Buat Janji Temu</a></div></li> 
              
              <?php else : ?>
                  <li><a href="index.php">Data Master</a></li>
                  <li><a href="index.php">Rekam Medis</a></li>
                  <li><a href="index.php">Bantuan Layanan</a></li>
                  <li><div class="main-white-button"><a href="#"><i class="fa fa-plus"></i> Registrasi Kunjungan</a></div></li> 
              <?php endif; ?>
              
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
            <h6>Over 36,500+ Terpercaya</h6>
            <h2>Rekam Medis Jalan Rawat Hewan</h2>
          </div>
        </div>
        
        <div class="col-lg-12">
          <form id="search-form" name="gs" method="submit" role="search" action="#">
            <div class="row">
              <div class="col-lg-3 align-self-center">
                  <fieldset>
                      <select name="area" class="form-select" aria-label="Area" id="chooseCategory">
                          <option selected>Dokter</option>
                          <option value="Asep Supriando">Asep Supriando</option>
                          <option value="Midun Spion">Midun Spion</option>
                          <option value="Candra Halimawan">Candra Halimawan</option>
                      </select>
                  </fieldset>
              </div>
              <div class="col-lg-3 align-self-center">
                  <fieldset>
                      <input type="address" name="address" class="searchText" placeholder="Cari Nama Hewan" autocomplete="on" required>
                  </fieldset>
              </div>
              <div class="col-lg-3 align-self-center">
                  <fieldset>
                      <select name="price" class="form-select" aria-label="Default select example" id="chooseCategory">
                          <option selected>Status Kunjungan</option>
                          <option value="Dalam Perawatan">Dalam Perawatan</option>
                          <option value="Selesai Perawatan">Selesai Perawatan</option>
                          <option value="Belum Diperiksa">Belum Diperiksa</option>
                      </select>
                  </fieldset>
              </div>
              <div class="col-lg-3">                        
                  <fieldset>
                      <button class="main-button"><i class="fa fa-search"></i> Cari Rekam Medis</button>
                  </fieldset>
              </div>
            </div>
          </form>
        </div>
        
        <div class="col-lg-10 offset-lg-1">
          <ul class="categories">
            <?php if ($role == 'admin') : ?>
                <li><a href="page/data_pemilik.php"><span class="icon"><img src="assets/images/search-icon-05.png" alt="Travel"></span> Kelola Pemilik</a></li>
                <li><a href="page/data_hewan.php"><span class="icon"><img src="assets/images/search-icon-01.png" alt="Home"></span> Data Pasien Hewan</a></li>
                <li><a href="page/data_dokter.php"><span class="icon"><img src="assets/images/search-icon-02.png" alt="Food"></span> Kelola Dokter</a></li>
                <li><a href="page/data_obat.php"><span class="icon"><img src="assets/images/search-icon-04.png" alt="Shopping"></span> Kelola Obat</a></li>
                
            <?php elseif ($role == 'dokter') : ?>
                <li><a href="index.php"><span class="icon"><img src="assets/images/search-icon-02.png" alt="Food"></span> Diagnosa </a></li>
                <li><a href="index.php"><span class="icon"><img src="assets/images/search-icon-03.png" alt="Vehicle"></span> Tindakan &amp; Lab</a></li>
                <li><a href="index.php"><span class="icon"><img src="assets/images/search-icon-04.png" alt="Shopping"></span> Resep &amp; Obat</a></li>

            <?php elseif ($role == 'pasien') : ?>
                <li><a href="page/data_pemilik.php"><span class="icon"><img src="assets/images/search-icon-01.png" alt="Home"></span> Profil Hewan</a></li>
                <li><a href="index.php"><span class="icon"><img src="assets/images/search-icon-05.png" alt="Travel"></span> Riwayat Kunjungan</a></li>
                <li><a href="index.php"><span class="icon"><img src="assets/images/search-icon-04.png" alt="Shopping"></span> Riwayat Resep</a></li> 
                
            <?php else : ?>
                <li><a href="index.php"><span class="icon"><img src="assets/images/search-icon-01.png" alt="Home"></span> Data Pasien Hewan</a></li>
                <li><a href="index.php"><span class="icon"><img src="assets/images/search-icon-02.png" alt="Food"></span> Pemeriksaan &amp; Diagnosa</a></li>
                <li><a href="index.php"><span class="icon"><img src="assets/images/search-icon-03.png" alt="Vehicle"></span> Tindakan &amp; Lab</a></li>
                <li><a href="index.php"><span class="icon"><img src="assets/images/search-icon-04.png" alt="Shopping"></span> Resep &amp; Obat</a></li> 
                <li><a href="index.php"><span class="icon"><img src="assets/images/search-icon-05.png" alt="Travel"></span> Riwayat Kunjungan</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

<footer>
    <div class="w-100 py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #eef2f5 100%); border-top: 1px solid #dee2e6; font-family: 'Montserrat', sans-serif;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-10">
                <div class="card border-0 shadow rounded-4" style="border-radius: 24px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.06) !important;">
                    <div class="p-4 text-center" style="background: linear-gradient(135deg, #212529 0%, #343a40 100%); color: white;">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2 shadow-sm" 
                              style="width: 60px; height: 60px; background: rgba(255,255,255,0.15); backdrop-filter: blur(5px);">
                            <?php if ($role == 'admin'): ?>
                                <i class="fa fa-user-shield text-success" style="font-size: 24px;"></i>
                            <?php else: ?>
                                <i class="fa fa-user-md text-info" style="font-size: 24px;"></i>
                            <?php endif; ?>
                        </span>
                        <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px;">Sesi Terautentikasi</h5>
                        <small class="opacity-75 small">Sistem Keamanan internal Medispet</small>
                    </div>
                    
                    <div class="card-body p-4 bg-white text-center">
                        <p class="text-muted small mb-1">Anda saat ini masuk sebagai:</p>
                        
                        <span class="badge <?php echo ($role == 'admin') ? 'bg-success' : 'bg-primary'; ?> text-white px-4 py-2 mb-3 fw-bold text-uppercase shadow-sm" 
                              style="border-radius: 30px; font-size: 11px; letter-spacing: 1px;">
                            <i class="fa fa-circle me-1 text-white" style="font-size: 7px;"></i> <?php echo htmlspecialchars($role); ?>
                        </span>
                        
                        <div class="p-2.5 mb-4 text-secondary small" style="border-radius: 12px; background-color: #f8f9fa; border: 1px solid #f1f3f5;">
                            ID Akun Terdaftar: <span class="fw-bold text-dark">@<?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </div>
                        
                        <div class="d-grid">
                            <a href="logout.php" class="btn btn-danger fw-bold py-2.5 shadow-sm" 
                               style="border-radius: 30px; font-size: 13px; letter-spacing: 0.5px; transition: all 0.3s ease;" 
                               onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem Medispet?')">
                                <i class="fa fa-sign-out me-2"></i> Keluar dari Aplikasi
                            </a>
                        </div>
                    </div>
                </div>
              </div>
        </div>
    </div>
</div>
</footer>


  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/custom.js"></script>

</body>
</html>