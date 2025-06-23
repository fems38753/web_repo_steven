<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../loginout.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$conn->query("DELETE FROM cart WHERE user_id = $user_id");

header("Location: ../cart.php");
exit;
?>