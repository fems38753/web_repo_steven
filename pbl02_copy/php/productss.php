<?php
include 'connect.php';

$category = isset($_GET['category']) ? intval($_GET['category']) : 0;

if ($category > 0) {
    $sql = "SELECT id, name, price, discount, image FROM products WHERE category_id = $category";
} else {
    $sql = "SELECT id, name, price, discount, image FROM products";
}

$result = $conn->query($sql);
$products = [];

while ($row = $result->fetch_assoc()) {
    $row['final_price'] = $row['price'] - ($row['price'] * $row['discount'] / 100);
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
?>