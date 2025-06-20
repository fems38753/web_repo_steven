<?php
session_start();
include 'php/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = mysqli_real_escape_string($conn, $_POST['shippingAddress']);
    $shipping_method = $_POST['shipping'];
    $payment_method = strtolower($_POST['payment']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes'] ?? '');

    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        echo "<script>alert('Empty Cart.'); window.location.href='cart.php';</script>";
        exit;
    }

    $shipping_costs = [
        'JNE' => 10000,
        'J&T' => 12500,
        'SiCepat' => 15000
    ];
    $shipping_cost = $shipping_costs[$shipping_method] ?? 10000;

    $total_price = 0;
    foreach ($cart as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
    $total_price += $shipping_cost;

    // Simpan order ke tabel orders
    $insert_order = mysqli_query($conn, "INSERT INTO orders (user_id, address, shipping_method, payment_method, notes, total_price)
        VALUES ($user_id, '$address', '$shipping_method', '$payment_method', '$notes', $total_price)");

    if (!$insert_order) {
        die("Gagal menyimpan order: " . mysqli_error($conn));
    }

    $order_id = mysqli_insert_id($conn);

    // Simpan setiap item ke order_items
    foreach ($cart as $item) {
        $pid = $item['id'];
        $qty = $item['quantity'];
        $size = mysqli_real_escape_string($conn, $item['size']);
        $price = $item['price'];

        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, size, price)
            VALUES ($order_id, $pid, $qty, '$size', $price)");
    }

    unset($_SESSION['cart']);

    // Redirect ke halaman pembayaran sesuai metode
    $redirects = [
        'bca' => 'payment_bca.php',
        'mandiri' => 'payment_mandiri.php',
        'visa' => 'payment_visa.php',
        'mastercard' => 'payment_mastercard.php',
        'gopay' => 'payment_gopay.php',
        'ovo' => 'payment_ovo.php'
    ];

    $redirect = $redirects[$payment_method] ?? 'account.php?page=orders';
    header("Location: $redirect?order_id=$order_id");
    exit;
}

// Jika bukan POST
header("Location: cart.php");
exit;
