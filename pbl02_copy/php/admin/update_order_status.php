<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    if (empty($order_id) || !in_array($status, ['Proceed', 'Complete'])) {
        echo "Invalid status or order ID.";
        exit;
    }

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        header('Location: orders.php');
        exit;
    } else {
        echo "Gagal memperbarui status pesanan. Coba lagi.";
    }
} else {
    echo "Invalid request method.";
}
?>