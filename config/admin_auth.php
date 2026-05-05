<?php
require_once "auth.php";

if ($_SESSION['user_role'] != 'admin') {
    echo "<script>
        alert('Access denied. Admin only.');
        window.location.href='/pharma-pos/views/dashboard.php';
    </script>";
    exit;
}
?>