<?php
include '../connect.php';

// Filter and sorting parameters
$status = isset($_GET['status']) ? $_GET['status'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at DESC';

// Build the query
$where = $status ? "WHERE o.status = '$status'" : "";
$sql = "SELECT o.id, u.username, o.total_price, o.created_at, o.status
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        $where
        ORDER BY $sort";

$result = $conn->query($sql);
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
      color: #2c3e50;
        margin-bottom: 25px;
        font-size: 24px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
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
    }

    .quick-actions a.logout:hover {
      background: #c0392b;
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

    /* Filter & action buttons */
.order-filters {
  margin-bottom: 20px;
  background: #f8f9fa;
  padding: 15px 20px;
  border-radius: 8px;
  box-shadow: 0 0 8px rgba(0,0,0,0.03);
}

.filter-form {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  align-items: flex-end;
}

.filter-form .form-group {
  display: flex;
  flex-direction: column;
}

.filter-form label {
  font-size: 14px;
  margin-bottom: 6px;
  color: #333;
}

.filter-form select {
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

.btn-filter, .btn-reset {
  padding: 8px 16px;
  border-radius: 5px;
  background: var(--secondary-color);
  color: white;
  border: none;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
}

.btn-filter:hover {
  background: #2980b9;
}

.btn-reset {
  background: #7f8c8d;
}

.btn-reset:hover {
  background: #636e72;
}

/* Table style */
.table-responsive {
  background: #fff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0,0,0,0.03);
  overflow-x: auto;
}

.order-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
  color: #333;
}

.order-table th {
  background: #2c3e50;
  color: white;
  padding: 12px 16px;
  text-align: left;
}

.order-table td {
  padding: 12px 16px;
  border-bottom: 1px solid #eee;
}

.order-table tr:hover {
  background-color: #f9f9f9;
}

/* Status badge */
.status-badge {
  padding: 6px 10px;
  border-radius: 4px;
  font-size: 13px;
  font-weight: 600;
  text-transform: capitalize;
}

.status-pending { background: #f39c12; color: white; }
.status-processing { background: #2980b9; color: white; }
.status-shipped { background: #8e44ad; color: white; }
.status-completed { background: #2ecc71; color: white; }
.status-cancelled { background: #e74c3c; color: white; }

/* View button */
.btn-view {
  padding: 6px 12px;
  background: #3498db;
  color: white;
  border-radius: 4px;
  text-decoration: none;
  font-size: 13px;
}

.btn-view:hover {
  background: #2980b9;
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
      <li><a href="products.php"><i class="fas fa-box-open"></i> <span>Kelola Produk</span></a></li>
      <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> <span>Tambah Produk</span></a></li>
      <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Pesanan</span></a></li>
      <li><a href="pengguna.php"><i class="fas fa-users"></i> <span>Pengguna</span></a></li>
      <li><a href="kategori.php"><i class="fas fa-tags"></i> <span>Kategori</span></a></li>
      <li><a href="/prog_web/web_repo_steven/pbl02_copy/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
    </ul>
  </aside>
<div class="main-content">
    <h2>Manajemen Pesanan</h2>
    
    <div class="order-filters">
        <form method="get" class="filter-form">
            <div class="form-group">
                <label>Status:</label>
                <select name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="processing" <?= $status == 'processing' ? 'selected' : '' ?>>Processing</option>
                    <option value="shipped" <?= $status == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Sort By:</label>
                <select name="sort">
                    <option value="created_at DESC" <?= $sort == 'created_at DESC' ? 'selected' : '' ?>>Newest First</option>
                    <option value="created_at ASC" <?= $sort == 'created_at ASC' ? 'selected' : '' ?>>Oldest First</option>
                    <option value="total_price DESC" <?= $sort == 'total_price DESC' ? 'selected' : '' ?>>Highest Amount</option>
                    <option value="total_price ASC" <?= $sort == 'total_price ASC' ? 'selected' : '' ?>>Lowest Amount</option>
                </select>
            </div>
            
            <button type="submit" class="btn-filter">Apply Filters</button>
            <a href="orders.php" class="btn-reset">Reset</a>
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['username'] ?? 'Guest') ?></td>
                    <td>Rp<?= number_format($order['total_price'], 0, ',', '.') ?></td>
                    <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                    <td>
                        <span class="status-badge status-<?= $order['status'] ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="order_detail.php?id=<?= $order['id'] ?>" class="btn-view">View</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php include 'footer_admin.php'; ?>