<?php
include '../connect.php';

// Sort default
$sort = $_GET['sort'] ?? 'DESC';
$sortToggle = $sort === 'ASC' ? 'DESC' : 'ASC';

$sql = "SELECT o.id, u.username, o.total_price
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.id $sort";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Order</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: #f4f4f4;
      display: flex;
    }

    .sidebar {
      width: 240px;
      background: #2c3e50;
      color: #fff;
      height: 100vh;
      position: fixed;
    }

    .sidebar h3 {
      text-align: center;
      padding: 20px 0;
      margin: 0;
      background: rgba(255,255,255,0.05);
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .sidebar ul li a {
      display: flex;
      align-items: center;
      color: #fff;
      padding: 12px 20px;
      text-decoration: none;
    }

    .sidebar ul li a i {
      margin-right: 10px;
      width: 20px;
    }

    .sidebar ul li a:hover {
      background: rgba(255,255,255,0.1);
    }

    .main-content {
      margin-left: 240px;
      padding: 30px;
      width: 100%;
    }

    h2 {
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 8px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 14px 16px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background: #2c3e50;
      color: #fff;
    }

    tr:hover {
      background: #f9f9f9;
    }

    .btn-view {
      background: #3498db;
      color: #fff;
      padding: 6px 10px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 13px;
    }

    .status-complete {
      color: green;
      font-weight: bold;
    }

    .sort-button {
      background: #7f8c8d;
      color: white;
      padding: 6px 12px;
      font-size: 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      float: right;
    }

    .sort-button:hover {
      background: #636e72;
    }
  </style>
</head>
<body>

  <aside class="sidebar">
    <div class="sidebar-header">
      <h3>Admin Panel</h3>
    </div>
    <ul class="sidebar-menu">
      <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
      <li><a href="products.php"><i class="fas fa-box-open"></i> <span>Manage Product</span></a></li>
      <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> <span>Add Product</span></a></li>
      <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Order</span></a></li>
      <li><a href="pengguna.php"><i class="fas fa-users"></i> <span>User</span></a></li>
      <li><a href="kategori.php"><i class="fas fa-tags"></i> <span>Category</span></a></li>
      <li><a href="/prog_web/web_repo_steven/pbl02_copy/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
    </ul>
  </aside>

  <div class="main-content">
    <h2>Manage Order
      <a href="orders.php?sort=<?= $sortToggle ?>" class="sort-button">
        Sort ID <?= $sort === 'ASC' ? '▲' : '▼' ?>
      </a>
    </h2>

    <table>
      <thead>
        <tr>
          <th>ID Order</th>
          <th>Customer</th>
          <th>Total</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($order = $result->fetch_assoc()): ?>
        <tr>
          <td>#<?= $order['id'] ?></td>
          <td><?= htmlspecialchars($order['username'] ?? 'Guest') ?></td>
          <td>Rp<?= number_format($order['total_price'], 0, ',', '.') ?></td>
          <td><span class="status-complete">Complete</span></td>
          <td><a href="order_items2.php?id=<?= $order['id'] ?>" class="btn-view">View Details </a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
