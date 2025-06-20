<?php
// Start session and check admin authentication
session_start();

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../account.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

include '../connect.php';

// Get statistics from database
$total_income = $conn->query("SELECT SUM(total_price) AS t FROM orders")->fetch_assoc()['t'] ?? 0;
$total_orders = $conn->query("SELECT COUNT(*) AS o FROM orders")->fetch_assoc()['o'] ?? 0;
$total_items = $conn->query("SELECT SUM(quantity) AS i FROM order_items")->fetch_assoc()['i'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
        --sidebar-width: 250px;
        --primary-color: #2c3e50;
        --secondary-color: #3498db;
        --success-color: #2ecc71;
        --danger-color: #e74c3c;
        --light-gray: #f8f9fa;
        --dark-gray: #343a40;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: var(--sidebar-width);
      background: var(--primary-color);
      color: white;
      overflow: hidden;
      position: fixed;
      height: 100vh;
      z-index: 1000;
    }

    .sidebar-header {
      padding: 20px;
      background: rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .sidebar-header h3 {
      margin: 0;
      white-space: nowrap;
    }

    .sidebar-menu {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .sidebar-menu li a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      transition: background 0.2s;
      white-space: nowrap;
    }

    .sidebar-menu li a:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .sidebar-menu li a i {
      margin-right: 10px;
      font-size: 1.1rem;
      min-width: 20px;
    }

    .main-content {
      flex: 1;
      margin-left: var(--sidebar-width);
      padding: 25px;
      background: #fff;
      min-height: calc(100vh - 50px);
    }

    .stats {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }

    .box {
      flex: 1 1 250px;
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .box:hover {
      transform: translateY(-5px);
    }

    .box h2 {
      margin: 0;
      font-size: 28px;
      color: #333;
    }

    .box p {
      color: #666;
      margin-top: 6px;
    }

    .welcome-message {
      text-align: center;
      margin-bottom: 20px;
      color: #444;
    }

    .quick-actions {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 30px;
    }

    .quick-actions a {
      text-decoration: none;
      color: #fff;
      background: var(--secondary-color);
      padding: 12px 24px;
      border-radius: 6px;
      transition: all 0.2s;
      display: inline-block;
    }

    .quick-actions a:hover {
      background: #2980b9;
      transform: scale(1.05);
    }

    .quick-actions a.logout {
      background: var(--danger-color);
      background:rgb(255, 0, 0);
    }

    .quick-actions a.logout:hover {
      background:rgb(255, 25, 0);
    }

    .admin-info {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      margin-bottom: 20px;
      text-align: center;
    }

    .admin-info h2 {
      margin-top: 0;
      color: var(--primary-color);
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }
      
      .main-content {
        margin-left: 0;
      }
      
      .sidebar-menu {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
      }
      
      .sidebar-menu li {
        flex: 1 1 auto;
      }
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
      <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> <span>Add product</span></a></li>
      <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Order</span></a></li>
      <li><a href="pengguna.php"><i class="fas fa-users"></i> <span>User</span></a></li>
      <li><a href="kategori.php"><i class="fas fa-tags"></i> <span>Category</span></a></li>
      <li><a href="/prog_web/web_repo_steven/pbl02_copy/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
    </ul>
  </aside>

  <div class="main-content">
    <div class="admin-info">
      <h2>Welcome, <?= htmlspecialchars($_SESSION['admin_email'] ?? $_SESSION['username'] ?? 'Admin'); ?></h2>
      <p>Last login: <?= date('d F Y H:i'); ?></p>
    </div>

    <section class="stats">
      <div class="box">
        <h2>Rp<?= number_format($total_income, 0, ',', '.'); ?></h2>
        <p>Total Income</p>
      </div>
      <div class="box">
        <h2><?= number_format($total_orders, 0, ',', '.'); ?></h2>
        <p>Order Total</p>
      </div>
      <div class="box">
        <h2><?= number_format($total_items, 0, ',', '.'); ?></h2>
        <p>Total Products Sold</p>
      </div>
    </section>

    <div class="quick-actions">
      <a href="products.php"><i class="fas fa-box-open"></i> Manage Product</a>
      <a href="orders.php"><i class="fas fa-shopping-cart"></i> View Orders</a>
      <a href="pengguna.php"><i class="fas fa-users"></i> Manage User</a>
      <a href="/prog_web/web_repo_steven/pbl02_copy/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
  </div>
</body>
</html>