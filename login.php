<?php
session_start();


if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true) {
    header("Location: index.php"); 
    exit;
}


include 'config/koneksi.php'; 

$error_message = "";
$success_message = isset($_SESSION['success_msg']) ? $_SESSION['success_msg'] : "";
unset($_SESSION['success_msg']); 

// Proses Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    
    $query = "SELECT * FROM medispet WHERE username='$username' AND password='$password'";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil && mysqli_num_rows($hasil) > 0) {
        $data_user = mysqli_fetch_assoc($hasil);
        
        $_SESSION['status_login'] = true;
        $_SESSION['id_user']      = $data_user['id_user'];
        $_SESSION['username']     = $data_user['username'];
        $_SESSION['role']         = $data_user['role']; 

        header("Location: index.php"); 
        exit;
    } else {

        $query_pemilik = "SELECT * FROM pemilik WHERE (Nama_Pemilik='$username' OR ID_Pemilik='$username') AND No_Telepon='$password'";
        $hasil_pemilik = mysqli_query($koneksi, $query_pemilik);

        if ($hasil_pemilik && mysqli_num_rows($hasil_pemilik) > 0) {
            $data_pasien = mysqli_fetch_assoc($hasil_pemilik);
            
            // Daftarkan ke session sebagai role 'pasien'
            $_SESSION['status_login'] = true;
            $_SESSION['id_user']      = $data_pasien['ID_Pemilik'];
            $_SESSION['username']     = $data_pasien['Nama_Pemilik'];
            $_SESSION['role']         = 'pasien'; 

            header("Location: index.php"); 
            exit;
        } else {
            // Jika di kedua tabel tidak ditemukan, tampilkan error
            $error_message = "Kredensial tidak valid. Silakan periksa kembali Username dan Password Anda.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal - Medispet</title>
    
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
        .form-control {
            border-radius: 12px;
            padding: 14px 15px;
            border: 1px solid #e1e5eb;
            background-color: #f8f9fa;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #b59473;
            background-color: #ffffff;
        }
        .btn-custom-login {
            background: linear-gradient(135deg, #b59473 0%, #9a7b5c 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
        }
        .btn-custom-login:hover {
            transform: translateY(-2px);
            color: #fff;
            box-shadow: 0 8px 20px rgba(181, 148, 115, 0.4);
        }
        .alert-custom {
            border-radius: 12px;
            font-size: 0.9rem;
            background-color: #fff2f2;
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }
        .alert-success-custom {
            border-radius: 12px;
            font-size: 0.9rem;
            background-color: #f0fdf4;
            color: #15803d;
            border-left: 4px solid #16a34a;
        }
    </style>
</head>
<body>

<div class="container p-3 p-md-5">
    <div class="card login-card mx-auto">
        <div class="row g-0">
            <div class="col-md-5 login-bg-image d-none d-md-block">
                <div class="login-sidebar-content d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-paw me-2" style="color: #b59473;"></i> Medispet</span>
                    </div>
                    <div class="mt-auto mb-4">
                        <h2 class="fw-bold mb-3" style="font-size: 2.2rem; line-height: 1.2;">Care & <br>Protection</h2>
                        <p class="text-white-50 fs-6">Sistem Terpusat Manajemen Rekam Medis Klinik.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-7 bg-white p-4 p-md-5 d-flex flex-column justify-content-center">
                <div class="mb-4">
                    <h2 class="fw-bold text-dark mb-2">Login Portal 👋</h2>
                    <p class="text-muted">Gunakan ID yang telah didaftarkan resepsionis.</p>
                </div>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-custom mb-4 px-3 py-2">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success-custom mb-4 px-3 py-2">
                        <i class="fa-solid fa-check-circle me-2"></i> <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Username / Nama Pemilik</label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: Budi Santoso atau admin" required autocomplete="off">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-semibold m-0">Kata Sandi</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Sandi" required>
                    </div>
                    
                    <button type="submit" class="btn btn-custom-login w-100 mt-2">
                        Masuk Sistem <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                    </button>
                    
                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">Belum memiliki akun?</p>
                        <a href="register.php" class="fw-bold" style="color: #b59473; text-decoration: none;">Daftar sebagai Pasien Baru</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>