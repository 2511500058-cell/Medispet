<?php
session_start();


$koneksi = mysqli_connect("localhost", "root", "", "pwt_medispet");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password']; 

    
    $query = "SELECT * FROM medispet WHERE username='$username' AND password='$password'";
    $hasil = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($hasil) === 1) {
        $data_user = mysqli_fetch_assoc($hasil);

        
        $_SESSION['status_login'] = true;
        $_SESSION['id_user']      = $data_user['id_user'];
        $_SESSION['username']     = $data_user['username'];
        $_SESSION['role']         = $data_user['role']; 

        
        header("Location: index.php");
        exit();
    } else {
        
        $query_pemilik = "SELECT * FROM pemilik WHERE ID_Pemilik='$username' AND No_Telepon='$password'";
        $hasil_pemilik = mysqli_query($koneksi, $query_pemilik);

        if (mysqli_num_rows($hasil_pemilik) === 1) {
            $data_pemilik = mysqli_fetch_assoc($hasil_pemilik);

           
            $_SESSION['status_login'] = true;
            $_SESSION['id_user']      = $data_pemilik['ID_Pemilik'];
            $_SESSION['username']     = $data_pemilik['Nama_Pemilik'];
            $_SESSION['role']         = 'pasien'; 

            header("Location: index.php");
            exit();
        } else {
            
            echo "<script>
                    alert('ID Pasien atau Password Salah!');
                    window.location.href='login.php';
                  </script>";
            exit();
        }
    }
}
?>