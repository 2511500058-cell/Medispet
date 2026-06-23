<?php
session_start();

// Jika sudah login, kembalikan ke index
if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true) {
    header("Location: folder/index.php");
    exit;
}

// Panggil koneksi
include 'config/koneksi.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $konfirmasi_password = mysqli_real_escape_string($koneksi, $_POST['konfirmasi_password']);

    // Validasi Password
    if ($password !== $konfirmasi_password) {
        $error_message = "Kata sandi dan konfirmasi kata sandi tidak cocok!";
    } else {
        // Cek apakah username sudah ada
        $cek_username = mysqli_query($koneksi, "SELECT * FROM medispet WHERE username = '$username'");
        if (mysqli_num_rows($cek_username) > 0) {
            $error_message = "Username '$username' sudah terdaftar, silakan gunakan username lain.";
        } else {
            // Jika aman, masukkan ke tabel medispet dengan role otomatis 'pasien'
            $query_insert = "INSERT INTO medispet (username, password, role) VALUES ('$username', '$password', 'pasien')";
            if (mysqli_query($koneksi, $query_insert)) {
                // Buat session pesan sukses dan lempar ke login
                $_SESSION['success_msg'] = "Pendaftaran berhasil! Silakan masuk dengan akun baru Anda.";
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Gagal mendaftar, terjadi kesalahan pada database.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Medispet</title>
    
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
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .register-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            max-width: 900px;
            width: 100%;
            background-color: #ffffff;
            animation: fadeUp 0.8s ease-out forwards;
        }
        .register-bg-image {
            background: url('assets/images/kucing-bg.jpg') center/cover no-repeat;
            position: relative;
            min-height: 600px;
        }
        .register-bg-image::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(33, 37, 41, 0.85) 0%, rgba(103, 143, 116, 0.7) 100%); /* Beda warna sedikit untuk register */
        }
        .sidebar-content {
            position: relative;
            z-index: 2;
            color: #ffffff;
            height: 100%;
            padding: 3.5rem;
        }
        .form-control {
            border-radius: 12px;
            padding: 14px 15px;
            border: 1px solid #e1e5eb;
            background-color: #f8f9fa;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #678f74;
            background-color: #ffffff;
        }
        .btn-custom-register {
            background: linear-gradient(135deg, #678f74 0%, #4a6b54 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
        }
        .btn-custom-register:hover {
            transform: translateY(-2px);
            color: #fff;
            box-shadow: 0 8px 20px rgba(103, 143, 116, 0.4);
        }
        .alert-custom {
            border-radius: 12px;
            font-size: 0.9rem;
            background-color: #fff2f2;
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>

<div class="container p-3 p-md-5">
    <div class="card register-card mx-auto">
        <div class="row g-0 flex-row-reverse"> <div class="col-md-5 register-bg-image d-none d-md-block">
                <div class="sidebar-content d-flex flex-column justify-content-between">
                    <div class="text-end">
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-user-plus me-2" style="color: #678f74;"></i> Gabung Medispet</span>
                    </div>
                    <div class="mt-auto mb-4 text-end">
                        <h2 class="fw-bold mb-3" style="font-size: 2.2rem; line-height: 1.2;">Mulai<br>Langkahmu</h2>
                        <p class="text-white-50 fs-6">Daftarkan akun pasien untuk memantau rekam medis peliharaan Anda.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-7 bg-white p-4 p-md-5 d-flex flex-column justify-content-center">
                <div class="mb-4">
                    <a href="login.php" class="text-muted text-decoration-none small mb-3 d-block"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Login</a>
                    <h2 class="fw-bold text-dark mb-2">Buat Akun Baru 🐾</h2>
                    <p class="text-muted">Isi formulir di bawah ini untuk membuat kredensial login.</p>
                </div>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-custom mb-4 px-3 py-2">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Username Baru</label>
                        <input type="text" name="username" class="form-control" placeholder="Pilih username (huruf kecil, tanpa spasi)" required autocomplete="off">
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Kata Sandi</label>
                            <input type="password" name="password" class="form-control" placeholder="Buat kata sandi" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Konfirmasi Sandi</label>
                            <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi kata sandi" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-custom-register w-100 mt-2">
                        Daftar Sekarang <i class="fa-solid fa-user-check ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>