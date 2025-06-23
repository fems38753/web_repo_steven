<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../loginout.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$product_id = $_POST['product_id'] ?? null;
$size = $_POST['size'] ?? null;

if ($action === 'clear_all') {
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header("Location: ../cart.php");
    exit;
}

if (!$product_id || !$size || !in_array($action, ['increase', 'decrease', 'delete'])) {
    header("Location: ../cart.php?error=invalid_input");
    exit;
}

$stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
$stmt->bind_param("iis", $user_id, $product_id, $size);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    header("Location: ../cart.php?error=not_found");
    exit;
}

$row = $res->fetch_assoc();
$currentQty = $row['quantity'];

if ($action === 'increase') {
    $newQty = $currentQty + 1;
    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ?");
    $update->bind_param("iiis", $newQty, $user_id, $product_id, $size);
    $update->execute();

} elseif ($action === 'decrease') {
    $newQty = $currentQty - 1;
    if ($newQty < 1) {
        $delete = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
        $delete->bind_param("iis", $user_id, $product_id, $size);
        $delete->execute();
    } else {
        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ?");
        $update->bind_param("iiis", $newQty, $user_id, $product_id, $size);
        $update->execute();
    }

} elseif ($action === 'delete') {
    $delete = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
    $delete->bind_param("iis", $user_id, $product_id, $size);
    $delete->execute();
}

header("Location: ../cart.php");
exit;
?>