<?php
session_start();
include '../connect.php';

// Handle adding a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "User added successfully!";
        header('Location: pengguna.php');
        exit();
    } else {
        $_SESSION['error'] = "Error adding user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Add User</title>
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
      background: #f4f4f4;
      margin: 0;
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: var(--sidebar-width);
      background: var(--primary-color);
      color: #fff;
      position: fixed;
      height: 100vh;
      overflow: hidden;
      z-index: 1000;
    }

    .sidebar-header {
      padding: 20px;
      background: rgba(0,0,0,.1);
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
      color: #fff;
      text-decoration: none;
      white-space: nowrap;
      transition: background .2s;
    }

    .sidebar-menu li a:hover {
      background: rgba(255,255,255,.1);
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
      min-height: 100vh;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      padding: 10px 15px;
      border-radius: 4px;
      text-decoration: none;
      background: var(--secondary-color);
      color: white;
      font-size: 14px;
      margin-bottom: 15px;
    }

    .btn i {
      margin-right: 6px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      font-size: 14px;
      font-weight: bold;
      color: #333;
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    .form-actions {
      margin-top: 20px;
    }

    .form-actions button {
      padding: 10px 20px;
      background: var(--success-color);
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }

    .form-actions button:hover {
      background: #27ae60;
    }

    .alert-error {
      color: red;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
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
      <li><a href="/prog_web/web_repo_steven/pbl02_copy/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </aside>

  <!-- Main Content -->
  <div class="main-content">
    <h2>Add User</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="role">Role</label>
        <select id="role" name="role" required>
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
      </div>

      <div class="form-actions">
        <button type="submit">Add User</button>
        <a href="pengguna.php" class="btn">Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>
