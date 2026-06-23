<?php
session_start();
session_unset();
session_destroy(); // Menghapus seluruh riwayat login dari server

echo "<script>
        alert('Anda telah keluar dari sistem.');
        window.location.href='login.php';
      </script>";
exit();
?>