<?php
include '../connect.php';
include 'auth_check.php';

$order_id = $_GET['id'];

// 🔄 Proses simpan status baru jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $order_id);
    $stmt->execute();
    header("Location: order_detail.php?id=" . $order_id); // refresh halaman
    exit;
}

// 🔍 Ambil data pesanan dan itemnya
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();
$items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
?>

<h2>Detail Pesanan #<?= $order_id ?></h2>

<p><strong>Alamat:</strong> <?= $order['address'] ?></p>
<p><strong>Catatan:</strong> <?= $order['notes'] ?></p>
<p><strong>Total:</strong> Rp<?= number_format($order['total_price']) ?></p>
<p><strong>Tanggal:</strong> <?= $order['created_at'] ?></p>
<p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>

<!-- 🔄 Form Ubah Status -->
<h3>Ubah Status Pesanan</h3>
<form method="post">
  <label>Status:</label>
  <select name="status">
    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
    <option value="dikirim" <?= $order['status'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
    <option value="selesai" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
  </select>
  <button type="submit">Simpan</button>
</form>

<!-- 🧾 Tabel item pesanan -->
<h3>Item:</h3>
<table border="1" cellpadding="8">
  <tr>
    <th>Produk</th>
    <th>Jumlah</th>
    <th>Harga</th>
    <th>Subtotal</th>
  </tr>
  <?php while ($item = $items->fetch_assoc()): ?>
  <tr>
    <td>ID #<?= $item['product_id'] ?></td>
    <td><?= $item['quantity'] ?></td>
    <td>Rp<?= number_format($item['price']) ?></td>
    <td>Rp<?= number_format($item['price'] * $item['quantity']) ?></td>
  </tr>
  <?php endwhile ?>
</table>

<p><a href="orders.php">← Kembali ke daftar pesanan</a></p>
