<?php
include 'user_auth.php';
include 'connect.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - <?= $username ?></title>
  <link rel="stylesheet" href="../pbl02.css">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">JACK<span>ARMY</span></div>
      <ul class="nav-links">
        <li><a href="../index.html">Home</a></li>
        <li><a href="../products.html">Products</a></li>
        <li><a href="../cart.html">Cart</a></li>
        <li><a href="../account.html">Account</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main style="padding: 30px;">
    <h2>Halo, <?= $username ?>!</h2>
    <h3>Riwayat Pesanan Anda</h3>

    <?php if ($orders->num_rows > 0): ?>
      <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 20px;">
        <tr style="background: #eee;">
          <th>ID</th>
          <th>Produk</th>
          <th>Jumlah</th>
          <th>Tanggal</th>
        </tr>
        <?php while ($row = $orders->fetch_assoc()): ?>
        <tr>
          <td>#<?= $row['id'] ?></td>
          <td><?= $row['product_id'] ?></td>
          <td><?= $row['quantity'] ?></td>
          <td><?= $row['order_date'] ?></td>
        </tr>
        <?php endwhile ?>
      </table>
    <?php else: ?>
      <p>Belum ada pesanan.</p>
    <?php endif ?>
  </main>
</body>
</html>
