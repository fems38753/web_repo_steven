<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = [
    'id' => $data['id'],
    'name' => $data['name'],
    'price' => $data['price'],
    'quantity' => $data['quantity'],
    'size' => $data['size'],
    'image' => $data['image'],
    'category' => $data['category']
];

echo "Item added to cart.";
?>