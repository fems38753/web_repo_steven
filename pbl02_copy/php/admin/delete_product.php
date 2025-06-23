<?php
include '../connect.php';
include 'auth_check.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: products.php");
?>