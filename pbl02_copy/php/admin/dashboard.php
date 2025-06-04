<?php
include 'auth_check.php';
include '../connect.php';

// Ambil statistik dari database
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
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #222;
      color: #fff;
      padding: 16px;
      text-align: center;
    }

    main {
      padding: 24px;
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

    nav ul {
      list-style: none;
      padding: 0;
      display: flex;
      gap: 15px;
      justify-content: center;
    }

    nav ul li {
      display: inline;
    }

    nav ul li a {
      text-decoration: none;
      color: #fff;
      background: #333;
      padding: 10px 20px;
      border-radius: 6px;
      transition: background 0.2s;
    }

    nav ul li a:hover {
      background: crimson;
    }
  </style>
</head>
<body>
  <header>
    <h1>Dashboard Admin</h1>
    <p>Login sebagai: <?= $_SESSION['admin_email']; ?></p>
  </header>

  <main>
    <section class="stats">
      <div class="box">
        <h2>Rp<?= number_format($total_income) ?></h2>
        <p>Total Pendapatan</p>
      </div>
      <div class="box">
        <h2><?= $total_orders ?></h2>
        <p>Total Pesanan</p>
      </div>
      <div class="box">
        <h2><?= $total_items ?></h2>
        <p>Total Produk Terjual</p>
      </div>
    </section>

    <nav>
      <ul>
        <li><a href="products.php">Kelola Produk</a></li>
        <li><a href="orders.php">Lihat Pesanan</a></li>
        <li><a href="../logout_admin.php">Logout</a></li>
      </ul>
    </nav>
  </main>
</body>
</html>
