<?php
session_start();
session_unset();
session_destroy(); 

echo "<script>
        alert('Anda telah keluar dari sistem.');
        window.location.href='login.php';
      </script>";
exit();
?>