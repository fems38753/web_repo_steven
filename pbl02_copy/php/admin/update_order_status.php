<?php
// Menghubungkan ke database
include '../connect.php';

// Cek apakah permintaan dilakukan dengan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Validasi input untuk memastikan data valid
    if (empty($order_id) || !in_array($status, ['Proceed', 'Complete'])) {
        echo "Invalid status or order ID.";
        exit;
    }

    // Memperbarui status pesanan di database
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);

    // Eksekusi query dan periksa apakah berhasil
    if ($stmt->execute()) {
        // Redirect kembali ke halaman orders setelah berhasil memperbarui status
        header('Location: orders.php');
        exit;
    } else {
        // Tampilkan pesan kesalahan jika gagal memperbarui
        echo "Gagal memperbarui status pesanan. Coba lagi.";
    }
} else {
    // Jika permintaan tidak menggunakan POST, tampilkan pesan kesalahan
    echo "Invalid request method.";
}
?>
