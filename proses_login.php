<?php
session_start();

// 1. Koneksi ke Database
$koneksi = mysqli_connect("localhost", "root", "", "pwt_medispet");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password']; 

    // 2. Cek Pertama: Apakah yang login Admin / Dokter (Tabel medispet)
    $query = "SELECT * FROM medispet WHERE username='$username' AND password='$password'";
    $hasil = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($hasil) === 1) {
        $data_user = mysqli_fetch_assoc($hasil);

        // Daftarkan session untuk Admin / Dokter
        $_SESSION['status_login'] = true;
        $_SESSION['id_user']      = $data_user['id_user'];
        $_SESSION['username']     = $data_user['username'];
        $_SESSION['role']         = $data_user['role']; 

        // Alihkan ke dashboard utama
        header("Location: index.php");
        exit();
    } else {
        // 3. Cek Kedua: Jika tidak ada di 'medispet', cek ke tabel 'pemilik'
        // Username = ID_Pemilik, Password = No_Telepon
        $query_pemilik = "SELECT * FROM pemilik WHERE ID_Pemilik='$username' AND No_Telepon='$password'";
        $hasil_pemilik = mysqli_query($koneksi, $query_pemilik);

        if (mysqli_num_rows($hasil_pemilik) === 1) {
            $data_pemilik = mysqli_fetch_assoc($hasil_pemilik);

            // Daftarkan session untuk Pasien / Pemilik
            $_SESSION['status_login'] = true;
            $_SESSION['id_user']      = $data_pemilik['ID_Pemilik'];
            $_SESSION['username']     = $data_pemilik['Nama_Pemilik'];
            $_SESSION['role']         = 'pasien'; // Berikan hak akses role 'pasien'

            header("Location: index.php");
            exit();
        } else {
            // Jika kedua pengecekan gagal
            echo "<script>
                    alert('ID Pasien atau Password (No. Telepon) Salah!');
                    window.location.href='login.php';
                  </script>";
            exit();
        }
    }
}
?>