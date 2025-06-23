<?php
session_start();
include 'php/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: loginout.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $shipping_address = $_POST['shipping'];
    $payment_method = $_POST['payment'];
    $notes = $_POST['notes'];
    $total = (int) $_POST['total'];
    $cart = json_decode($_POST['cart'], true);

    $order_date = date('Y-m-d H:i:s');
    $status = 'pending';
    $query = "INSERT INTO orders (user_id, address, payment_method, notes, total, status, created_at) 
              VALUES ('$user_id', '$shipping_address', '$payment_method', '$notes', '$total', '$status', '$order_date')";
    mysqli_query($conn, $query);
    $order_id = mysqli_insert_id($conn);

    foreach ($cart as $item) {
        $product_id = $item['id'];
        $name = mysqli_real_escape_string($conn, $item['name']);
        $qty = (int)$item['quantity'];
        $size = $item['size'];
        $price = (int)$item['price'];

        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, name, size, quantity, price) 
            VALUES ('$order_id', '$product_id', '$name', '$size', '$qty', '$price')");

        $result = mysqli_query($conn, "SELECT size_available FROM products WHERE id = '$product_id'");
        $row = mysqli_fetch_assoc($result);
        $sizes = [];
        foreach (explode(',', $row['size_available']) as $pair) {
            list($sz, $val) = explode(':', $pair);
            $sizes[$sz] = (int)$val;
        }

        if ($sizes[$size] >= $qty) {
            $sizes[$size] -= $qty;
        }

        $updatedSizeStr = implode(',', array_map(function($sz) use ($sizes) {
            return "$sz:{$sizes[$sz]}";
        }, ['S','M','L','XL']));
        mysqli_query($conn, "UPDATE products SET size_available='$updatedSizeStr' WHERE id='$product_id'");
    }

    echo "<script>
        localStorage.removeItem('cart');
        alert('Order successful! Please check your account.');
        window.location.href = 'account.php';
    </script>";
    exit;
}
?>