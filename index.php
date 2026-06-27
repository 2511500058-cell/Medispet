<?php
session_start();

// Cek apakah user sudah login lewat session, jika belum, lempar balik ke login.php
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: ../login.php"); 
    exit();
}

// Ambil role pengguna dari session untuk mengatur hak akses tampilan
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Hubungkan ke database
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
            // LOGIKA PEMBAGIAN PERAN (Sesuai dokumen Peran_Medispet)
            switch ($role) {
                case 'admin':
                    // ==================== TAMPILAN ROLE ADMIN ====================
                    ?>
                    <h5 class="fw-bold text-primary mb-4"><i class="fa fa-cogs me-2"></i>Manajemen Data Master & Administrasi</h5>
                    <p class="text-muted mb-4">Sebagai Administrator, Anda memiliki akses penuh untuk mengelola pengguna, pendaftaran antrean, obat, dan validasi tagihan tindakan klinik.</p>
                    <div class="row g-3 text-center">
                        <div class="col-md-3">
                            <a href="page/data_dokter.php" class="btn btn-outline-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 15px;">
                                <i class="fa fa-user-md d-block mb-2 fs-4"></i> Kelola Dokter
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="page/data_pemilik.php" class="btn btn-outline-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 15px;">
                                <i class="fa fa-users d-block mb-2 fs-4"></i> Kelola Pemilik & Hewan
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="page/data_obat.php" class="btn btn-outline-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 15px;">
                                <i class="fa fa-pills d-block mb-2 fs-4"></i> Kelola Data Obat
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="page/data_kunjungan.php" class="btn btn-primary w-100 py-3 fw-bold shadow-sm text-white" style="border-radius: 15px;">
                                <i class="fa fa-clipboard-list d-block mb-2 fs-4"></i> Pendaftaran Antrean
                            </a>
                        </div>
                    </div>
                    <?php
                    break;

                case 'dokter':
                    // ==================== TAMPILAN ROLE DOKTER ====================
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
                                $query_antrean = "SELECT k.*, h.Nama_Hewan FROM kunjungan k JOIN hewan h ON k.ID_Hewan = h.ID_Hewan ORDER BY k.ID_Kunjungan DESC";
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
                                                <td class='text-center'>";
                                        ?>
                                                <a href="tindakan.php?id_kunjungan=<?php echo $row['ID_Kunjungan']; ?>" class="btn btn-primary btn-sm px-3 fw-bold" style="border-radius: 15px;">
                                                   Periksa Pasien
                                                </a>
                                        <?php
                                        echo "   </td>
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
                    break;

                case 'pasien':
                    // ==================== TAMPILAN ROLE PASIEN ====================
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
                                // Mengambil riwayat kunjungan berdasarkan relasi data medis pasien
                                $query_pasien = "SELECT k.*, h.Nama_Hewan, d.Nama_Dokter 
                                                 FROM kunjungan k 
                                                 JOIN hewan h ON k.ID_Hewan = h.ID_Hewan 
                                                 LEFT JOIN dokter d ON k.ID_Dokter = d.ID_Dokter 
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
                    break;

                default:
                    echo "<div class='alert alert-danger mb-0'>Role akun Anda tidak dikenali oleh sistem. Hubungi administrator.</div>";
                    break;
            }
            ?>
          </div>
        </div>

      </div>
    </div>
  </div>
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="about-widget">
            <div class="logo"></div>
            <p>Sistem Informasi Rekam Medis Klinik Hewan - Medispet.</p>
          </div>
        </div>
        <div class="col-lg-4">
          </div>
        <div class="col-lg-4">
          <div class="about-widget">
                <div class="card border-0 shadow-sm p-4 bg-white text-dark" style="border-radius: 20px;">
                    <div class="card-body p-1 text-center">
                        <h6 class="fw-bold text-dark mb-2">Informasi Akun</h6>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 fw-bold mb-3 d-inline-block" style="font-size: 11px; border-radius: 30px; text-transform: uppercase;">
                            <i class="fa fa-circle me-1" style="font-size: 7px;"></i> <?php echo htmlspecialchars($role); ?>
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