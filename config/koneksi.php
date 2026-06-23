<?php
// koneksi.php
$host     = "localhost";
$user     = "root"; // Sesuaikan dengan user XAMPP/server Anda
$password = "";     // Sesuaikan dengan password database Anda
$database = "pwt_medispet"; // Nama database dari file SQL Anda

$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>