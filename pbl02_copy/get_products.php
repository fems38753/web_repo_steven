<?php 
include 'connect.php';
header('Content-Type: application/json');

$products = $conn->query("SELECT * FROM products");
$result = [];
while ($row = $products->fetch_assoc()) {
    $result[] = $row;
}
echo json_encode($result);
?>