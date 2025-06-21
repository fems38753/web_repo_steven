<?php
include '../connect.php';

// Handle delete
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("DELETE FROM users WHERE id = $id");
  header('Location: pengguna.php');
  exit();
}

$result = $conn->query("SELECT * FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Management User</title>
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

    .table-responsive {
      overflow-x: auto;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 0 12px rgba(0,0,0,.04);
      padding: 20px;
      margin-top: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
      color: #333;
    }

    thead {
      background: #2c3e50;
      color: #fff;
    }

    th, td {
      padding: 12px 16px;
      border-bottom: 1px solid #e0e0e0;
      text-align: left;
    }

    .actions {
      display: flex;
      gap: 10px;
    }

    .btn-edit,
    .btn-delete {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 30px;
      height: 30px;
      border-radius: 4px;
      color: white;
      font-size: 14px;
      cursor: pointer;
    }

    .btn-edit {
      background: #f39c12;
    }

    .btn-edit:hover {
      background: #e67e22;
    }

    .btn-delete {
      background: #e74c3c;
    }

    .btn-delete:hover {
      background: #c0392b;
    }

    @media(max-width:768px) {
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
      <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> <span>Add Product</span></a></li>
      <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Order</span></a></li>
      <li><a href="pengguna.php"><i class="fas fa-users"></i> <span>User</span></a></li>
      <li><a href="kategori.php"><i class="fas fa-tags"></i> <span>Category</span></a></li>
      <li><a href="/prog_web/web_repo_steven/pbl02_copy/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
    </ul>
  </aside>

  <div class="main-content">
    <h2>Manage User</h2>

    <a href="add_user.php" class="btn"><i class="fas fa-user-plus"></i> Add User</a>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Registration Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $user['id'] ?></td>
              <td><?= htmlspecialchars($user['username']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= $user['created_at'] ?></td>
              <td class="actions">
                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn-edit" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="pengguna.php?delete=<?= $user['id'] ?>" class="btn-delete" title="Hapus"
                   onclick="return confirm('Are you sure you want to delete this user?')">
                  <i class="fas fa-trash-alt"></i>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
