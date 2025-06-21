<?php
include '../connect.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$order_id) {
  die("ID pesanan tidak valid.");
}

// Ambil data order dan user
$order = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT o.*, u.username 
  FROM orders o
  LEFT JOIN users u ON o.user_id = u.id
  WHERE o.id = $order_id
"));

if (!$order) {
  die("Order not found.");
}

// Ambil item pesanan
$items = mysqli_query($conn, "
  SELECT oi.*, p.name, p.image 
  FROM order_items oi
  JOIN products p ON oi.product_id = p.id
  WHERE oi.order_id = $order_id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Order #<?= $order_id ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 30px;
    }

    .order-box {
      max-width: 1000px;
      margin: 0 auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0,0,0,0.05);
    }

    h2 {
      margin-top: 0;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 14px 16px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background: #2c3e50;
      color: white;
    }

    img.product-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }

    .back-link {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      background: #3498db;
      color: white;
      padding: 8px 14px;
      border-radius: 5px;
    }

    .back-link:hover {
      background: #2980b9;
    }

    .total {
      font-weight: bold;
      text-align: right;
      padding: 10px;
    }

    .order-info {
      margin-bottom: 10px;
    }

    .order-info strong {
      display: inline-block;
      width: 160px;
    }
  </style>
</head>
<body>

<div class="order-box">
  <h2>Detail Order #<?= $order['id'] ?></h2>

  <div class="order-info">
    <p><strong>Customer:</strong> <?= htmlspecialchars($order['username'] ?? 'Guest') ?></p>
    <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
    <p><strong>Shipping Method:</strong> <?= $order['shipping_method'] ?></p>
    <p><strong>Total:</strong> Rp<?= number_format($order['total_price'], 0, ',', '.') ?></p>
    <p><strong>Status:</strong> 
      <span style="color: <?= $order['status'] == 'Complete' ? 'green' : 'orange'; ?>; font-weight: bold;">
        <?= ucfirst($order['status']) ?>
      </span>
    </p>
  </div>

  <table>
    <thead>
      <tr>
        <th>Product</th>
        <th>Image</th>
        <th>Size</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($item = mysqli_fetch_assoc($items)): ?>
      <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><img src="../../Images/<?= basename($item['image']) ?>" class="product-img" alt="gambar"></td>
        <td><?= $item['size'] ?></td>
        <td><?= $item['quantity'] ?></td>
        <td>Rp<?= number_format($item['price'], 0, ',', '.') ?></td>
        <td>Rp<?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="orders.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Orders</a>
</div>
</body>
</html>
