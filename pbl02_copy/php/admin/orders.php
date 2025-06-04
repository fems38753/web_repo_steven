<?php
// ===== FILE: orders.php =====
include 'layout_admin.php';
include '../connect.php';

$sql = "SELECT o.id, u.username, o.total_price, o.created_at, o.status
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";
$result = $conn->query($sql);
?>

<h2>Daftar Pesanan</h2>
<table border="1" cellpadding="10" cellspacing="0">
  <tr>
    <th>ID</th>
    <th>User</th>
    <th>Total</th>
    <th>Tanggal</th>
    <th>Status</th>
    <th>Aksi</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td>#<?= $row['id'] ?></td>
    <td><?= $row['username'] ?? 'Guest' ?></td>
    <td>Rp<?= number_format($row['total_price']) ?></td>
    <td><?= $row['created_at'] ?></td>
    <td><?= ucfirst($row['status']) ?></td>
    <td><a href="order_detail.php?id=<?= $row['id'] ?>">Lihat</a></td>
  </tr>
  <?php endwhile ?>
</table>
