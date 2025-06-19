<?php
session_start();
include 'php/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginout.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;
$selected_size = $_POST['size'] ?? null;
$quantity = intval($_POST['quantity'] ?? 0);

if (!$product_id || !$selected_size || $quantity < 1) {
    header("Location: products.php?error=invalid_input");
    exit;
}

// Cek apakah produk valid
$stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: products.php?error=not_found");
    exit;
}

// Cek apakah item sudah ada di cart
$cekCart = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
$cekCart->bind_param("iis", $user_id, $product_id, $selected_size);
$cekCart->execute();
$res = $cekCart->get_result();

if ($res->num_rows > 0) {
    // Sudah ada → update quantity
    $row = $res->fetch_assoc();
    $newQty = $row['quantity'] + $quantity;
    $updateCart = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ?");
    $updateCart->bind_param("iiis", $newQty, $user_id, $product_id, $selected_size);
    $updateCart->execute();
} else {
    // Belum ada → insert baru
    $insertCart = $conn->prepare("INSERT INTO cart (user_id, product_id, size, quantity) VALUES (?, ?, ?, ?)");
    $insertCart->bind_param("iisi", $user_id, $product_id, $selected_size, $quantity);
    $insertCart->execute();
}

// Redirect ke products.php
header("Location: products.php?added=success");
exit;
?>
