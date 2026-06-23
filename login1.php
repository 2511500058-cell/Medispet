<?php
  session_start();
  include "config/koneksi.php";

  if(isset($_POST['login'])) {
      $Username = $_POST['Username'];
      $Password = $_POST['Password'];

      if(empty($Username) || empty($Password)) {
          $error = "Data Tidak Boleh kosong!";
      } else {
          $query = mysqli_query($koneksi, "SELECT * FROM medispet WHERE username = '$Username' AND password = '$Password' ");
          $userquery = mysqli_fetch_array($query);
          
          if($userquery) {
              $_SESSION['role'] = strtolower($userquery['role']); 
              $_SESSION['username'] = $Username;

              if(($_SESSION['role'] == 'dokter' || $_SESSION['role'] == 'pasien') && $Password == '1234') {
                  header("location:index.php?page=ganti_password"); 
                  exit();
              } else {
                  header("location:index.php"); 
                  exit();
              } 

          } else {
              $error = "Login gagal. Username atau password salah!";
          }
      }
  }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rekam Medis Rawat Jalan Hewan</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #f4f6f9 0%, #e2e6eb 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        
        /* Efek Animasi Muncul */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            max-width: 900px;
            width: 100%;
            background-color: #ffffff;
            animation: fadeUp 0.8s ease-out forwards;
        }

        /* Sisi Kiri dengan Overlay Gradien */
        .login-bg-image {
            background: url('assets/images/kucing-bg.jpg') center/cover no-repeat;
            position: relative;
            min-height: 550px;
        }
        .login-bg-image::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(33, 37, 41, 0.85) 0%, rgba(181, 148, 115, 0.6) 100%); 
        }
        .login-sidebar-content {
            position: relative;
            z-index: 2;
            color: #ffffff;
            height: 100%;
            padding: 3.5rem;
        }

        /* Form Controls */
        .form-control {
            border-radius: 12px;
            padding: 14px 15px;
            border: 1px solid #e1e5eb;
            background-color: #f8f9fa;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .input-group-text {
            border-radius: 12px;
            border: 1px solid #e1e5eb;
            background-color: #f8f9fa;
            padding: 0 18px;
            transition: all 0.3s ease;
        }
        /* Menghilangkan border tengah agar menyatu */
        .input-group .form-control { border-left: none; }
        .input-group .input-group-text { border-right: none; }
        
        /* Efek Focus Input */
        .form-control:focus {
            box-shadow: none;
            border-color: #b59473;
            background-color: #ffffff;
        }
        .form-control:focus + .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: #b59473;
            background-color: #ffffff;
            color: #b59473 !important;
        }

        /* Tombol Custom */
        .btn-custom-login {
            background: linear-gradient(135deg, #b59473 0%, #9a7b5c 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-custom-login:hover {
            background: linear-gradient(135deg, #9a7b5c 0%, #7d6248 100%);
            color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(181, 148, 115, 0.4);
        }

        .alert-custom {
            border-radius: 12px;
            font-size: 0.9rem;
            border: none;
            background-color: #fff2f2;
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>

<div class="container p-3 p-md-5">
    <div class="card login-card mx-auto">
        <div class="row g-0">
            
            <!-- SISI KIRI: Visual Branding -->
            <div class="col-md-5 login-bg-image d-none d-md-block">
                <div class="login-sidebar-content d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge bg-light text-dark mb-3 px-3 py-2 rounded-pill shadow-sm" style="font-weight: 500;">
                            <i class="fa-solid fa-paw me-2" style="color: #b59473;"></i> Medispet Portal
                        </span>
                        <h4 class="fw-bold m-0"><i class="fa-solid fa-file-medical me-2"></i>Plotlist Vet</h4>
                    </div>
                    <div class="mt-auto mb-4">
                        <h2 class="fw-bold mb-3" style="font-size: 2.2rem; line-height: 1.2;">Care & <br>Protection</h2>
                        <p class="text-white-50 fs-6">Sistem Manajemen Integrasi Rekam Medis Rawat Jalan Hewan Kesayangan Anda.</p>
                    </div>
                    <div class="small text-white-50" style="font-size: 0.8rem;">
                        &copy; 2024 Plotlist Clinic. All rights reserved.
                    </div>
                </div>
            </div>
            
            <!-- SISI KANAN: Formulir Login -->
            <div class="col-md-7 bg-white p-4 p-md-5 d-flex flex-column justify-content-center">
                <div class="mb-4">
                    <h2 class="fw-bold text-dark mb-2">Selamat Datang 👋</h2>
                    <p class="text-muted">Silakan masuk menggunakan kredensial akun Anda.</p>
                </div>

                <!-- Notifikasi Error -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-custom alert-dismissible fade show mb-4" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Form Aksi ke Halaman Sendiri -->
                <form action="" method="POST">
                    
                    <!-- Input Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label text-secondary small fw-semibold">ID Pengguna</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan Username Anda" required autocomplete="off">
                        </div>
                    </div>
                    
                    <!-- Input Password -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label text-secondary small fw-semibold m-0">Kata Sandi</label>
                            <a href="#" class="text-decoration-none small fw-semibold" style="color: #b59473;">Lupa Sandi?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control border-end-0" placeholder="Masukkan Kata Sandi" required>
                            <span class="input-group-text text-muted bg-transparent border-start-0" style="cursor: pointer;" id="togglePassword">
                                <i class="fa-solid fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Remember Me Checklist -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" style="cursor: pointer;">
                        <label class="form-check-label text-muted small" for="rememberMe" style="cursor: pointer;">
                            Ingat sesi perangkat ini
                        </label>
                    </div>
                    
                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-custom-login w-100 shadow-sm mt-2">
                        Masuk Sistem <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                    </button>
                    
                </form>
            </div>
            
        </div>
    </div>
</div>

<!-- Script pendukung interaksi visual (Show/Hide Password) -->
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
        // Toggle tipe input
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Toggle ikon mata
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
        
        // Efek warna saat diklik
        this.style.color = type === 'text' ? '#b59473' : '';
    });
</script>
</body>
</html>