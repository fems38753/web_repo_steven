<?php
$host = "localhost";
$user = "root";         // default XAMPP
$pass = "";             // default XAMPP (tanpa password)
$db = "jackarmy_db";    // PASTIKAN nama ini sesuai di phpMyAdmin

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
