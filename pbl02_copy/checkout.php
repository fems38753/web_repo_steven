<?php
session_start();
include 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);

$cart = $data['cart'];
$address = $data['address'];
$notes = $data['notes'];

if (!$cart || !$address) {
    echo "invalid";
    exit;
}

$user_id = $_SESSION['user_id'] ?? 1; // default sementara jika belum login
$total = 0;

foreach ($cart as $item) {
    $total += $item['harga'] * $item['jumlah'];
}

// Simpan order
$stmt = $conn->prepare("INSERT INTO orders (user_id, address, notes, total_price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issi", $user_id, $address, $notes, $total);
$stmt->execute();
$order_id = $stmt->insert_id;

// Simpan detail item
foreach ($cart as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    // Sementara product_id tidak diketahui pasti dari hardcoded, pakai 0 dulu
    $pid = 0;
    $stmt->bind_param("iiii", $order_id, $pid, $item['jumlah'], $item['harga']);
    $stmt->execute();
}

echo "success";
?>
