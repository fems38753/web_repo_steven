<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    // Jika belum login, arahkan ke halaman login admin
    header("Location: ../login_admin.php");
    exit;
}
