<?php
session_start();
include 'php/connect.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../account/loginout.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "Empty Cart!";
    exit();
}

// Ambil data dari POST
$payment_method = $_POST['payment_method'] ?? 'OVO';
$courier = $_POST['courier'] ?? 'JNE';
$address = $_POST['address'] ?? 'Alamat default';

// Hitung total harga
$total_price = 0;
foreach ($cart as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Insert ke tabel orders
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, payment_method, shipping_address, courier) VALUES (?, ?, 'Completed', ?, ?, ?)");
$stmt->bind_param("idsss", $user_id, $total_price, $payment_method, $address, $courier);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert item ke tabel order_items
foreach ($cart as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, size, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisd", $order_id, $item['id'], $item['quantity'], $item['size'], $item['price']);
    $stmt->execute();
}

// Kosongkan cart
unset($_SESSION['cart']);

// Redirect ke halaman akun atau halaman sukses
header("Location: ../account.php?order=success");
exit();
?>
