<?php
include 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background-color: #f4f4f4;
      display: flex;
    }

    .sidebar {
      width: 250px;
      background-color: #222;
      color: #fff;
      height: 100vh;
      padding-top: 20px;
      position: fixed;
      overflow-y: auto;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar a {
      display: block;
      color: #fff;
      padding: 12px 20px;
      text-decoration: none;
      transition: background 0.2s;
    }

    .sidebar a:hover {
      background-color: crimson;
    }

    .main-content {
      margin-left: 250px;
      padding: 30px;
      flex-grow: 1;
      min-height: 100vh;
      background-color: #f9f9f9;
    }

    .dropdown {
      margin-bottom: 10px;
    }

    .dropdown-title {
      font-weight: bold;
      padding: 10px 20px;
      background-color: #333;
    }

    .dropdown a {
      padding-left: 40px;
      font-size: 14px;
    }

    .back-btn {
      display: inline-block;
      margin-bottom: 20px;
      padding: 8px 16px;
      background-color: #333;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      transition: background 0.2s;
    }

    .back-btn:hover {
      background-color: crimson;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Admin Panel</h2>

    <div class="dropdown">
      <div class="dropdown-title">Dashboard</div>
      <a href="dashboard.php">Home</a>
    </div>

    <div class="dropdown">
      <div class="dropdown-title">Manage Product</div>
      <a href="products.php">Product List</a>
      <a href="add_product.php">Add Product</a>
    </div>

    <div class="dropdown">
      <div class="dropdown-title">Order</div>
      <a href="orders.php">Order List</a>
    </div>

    <div class="dropdown">
      <div class="dropdown-title">Admin</div>
      <a href="insert_admin.php">Add Admin</a>
    </div>

    <div class="dropdown">
      <a href="../logout.php">Logout</a>
    </div>
  </div>
  <div class="main-content">