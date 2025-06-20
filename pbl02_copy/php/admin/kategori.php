<?php
include '../connect.php';

// Tambah kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $conn->query("INSERT INTO categories (name) VALUES ('$name')");
    }
    header('Location: kategori.php');
    exit();
}

// Hapus kategori
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM categories WHERE id=$id");
    header('Location: kategori.php');
    exit();
}

$result = $conn->query("SELECT * FROM categories ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manage Category</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root{--sidebar-width:250px;--primary-color:#2c3e50;--secondary-color:#3498db;
      --success-color:#2ecc71;--danger-color:#e74c3c;--light-gray:#f8f9fa;--dark-gray:#343a40;}
    body{font-family:'Inter',sans-serif;background:#f4f4f4;margin:0;display:flex;min-height:100vh}
    .sidebar{width:var(--sidebar-width);background:var(--primary-color);color:#fff;position:fixed;height:100vh;overflow:hidden}
    .sidebar-header {
    padding: 20px;
    background: rgba(0, 0, 0, 0.1);
    text-align: center;
    }
    .sidebar-header h3 {
      margin: 0;
      white-space: nowrap;
    }
    .sidebar-menu{list-style:none;padding:0;margin:0}
    .sidebar-menu li a{display:flex;align-items:center;padding:12px 20px;color:#fff;text-decoration:none;white-space:nowrap;transition:background .2s}
    .sidebar-menu li a:hover{background:rgba(255,255,255,.1)}
    .sidebar-menu li a i{margin-right:10px;min-width:20px}
    .main-content{flex:1;margin-left:var(--sidebar-width);padding:25px;background:#fff;min-height:calc(100vh - 50px)}
    h2{color:#2c3e50;margin-bottom:20px}

    /* form tambah */
    form{display:flex;gap:10px;margin-bottom:20px}
    input[type=text]{flex:1;padding:8px;border:1px solid #ccc;border-radius:4px}
    button{padding:8px 15px;background:var(--secondary-color);color:#fff;border:none;border-radius:4px;cursor:pointer}
    button:hover{background:#2980b9}

    /* tabel */
    .table-responsive{overflow-x:auto;background:#fff;border-radius:8px;box-shadow:0 0 12px rgba(0,0,0,.04);padding:20px}
    table{width:100%;border-collapse:collapse;font-size:14px;color:#333}
    thead{background:#2c3e50;color:#fff}
    th,td{padding:12px 16px;border-bottom:1px solid #e0e0e0;text-align:left}
    tr:hover{background:rgba(255,255,255,.1)}

    /* tombol hapus */
    .btn-delete{background:var(--danger-color);color:#fff;padding:6px 10px;border-radius:4px;text-decoration:none}
    .btn-delete:hover{background:#c0392b}

    @media(max-width:768px){
      .sidebar{width:100%;height:auto;position:relative}
      .main-content{margin-left:0}
      .sidebar-menu{display:flex;flex-wrap:wrap;justify-content:center}
      .sidebar-menu li{flex:1 1 auto}
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
      <li><a href="/prog_web/web_repo_steven/pbl02_copy/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
    </ul>
  </aside>

  <div class="main-content">
    <h2>Manage Category</h2>

    <form method="POST">
      <input type="text" name="name" placeholder="Add New Category..." required>
      <button type="submit">Add</button>
    </form>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Categopry Name</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($cat = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $cat['id'] ?></td>
            <td><?= htmlspecialchars($cat['name']) ?></td>
            <td><a href="?delete=<?= $cat['id'] ?>" class="btn-delete" onclick="return confirm('HDelete this category?')">Delete</a></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
