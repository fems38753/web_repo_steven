<?php
session_start();
include 'php/connect.php';

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

$payment_method = $_POST['payment_method'] ?? 'OVO';
$courier = $_POST['courier'] ?? 'JNE';
$address = $_POST['address'] ?? 'Alamat default';

$total_price = 0;
foreach ($cart as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

$stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, payment_method, shipping_address, courier) VALUES (?, ?, 'Completed', ?, ?, ?)");
$stmt->bind_param("idsss", $user_id, $total_price, $payment_method, $address, $courier);
$stmt->execute();
$order_id = $stmt->insert_id;

foreach ($cart as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, size, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisd", $order_id, $item['id'], $item['quantity'], $item['size'], $item['price']);
    $stmt->execute();
}

unset($_SESSION['cart']);

header("Location: ../account.php?order=success");
exit();
?>