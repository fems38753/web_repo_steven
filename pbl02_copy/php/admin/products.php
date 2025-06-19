<?php
include '../connect.php';

// Handle delete operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    $_SESSION['message'] = "Product deleted successfully";
    header('location: products.php');
    exit();
}

// Fetch all products with pagination
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$result = $conn->query("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id ASC
    LIMIT $limit OFFSET $offset
");

$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$total_pages = ceil($total_products / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
body {
    font-family: 'Inter', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    min-height: 100vh;
}

/* Sidebar fix di kiri */
.sidebar {
    width: var(--sidebar-width);
    background: var(--primary-color);
    color: white;
    overflow: hidden;
    position: fixed;
    height: 100vh;
    z-index: 1000;
    left: 0;
    top: 0;
}

/* Konten utama di kanan sidebar */
.main-content {
    margin-left: var(--sidebar-width);
    padding: 30px;
    flex: 1;
    background: #fff;
    min-height: 100vh;
    box-shadow: inset 0 0 8px rgba(0,0,0,0.03);
}

h2 {
    color: var(--primary-color);
    margin-bottom: 25px;
    font-size: 24px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 8px 15px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #3498db;
    color: white;
    border: none;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-primary img {
    margin-right: 5px;
}

.alert {
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    font-size: 14px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.table-responsive {
    overflow-x: auto;
}

.product-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 14px;
}

.product-table th {
    background: #2c3e50;
    color: white;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
}

.product-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.product-table tr:hover {
    background-color: #f9f9f9;
}

.product-thumbnail {
    border-radius: 4px;
    border: 1px solid #ddd;
}

.actions {
    display: flex;
    gap: 10px;
}

.btn-edit, .btn-delete {
    display: inline-flex;
    padding: 30px 15px ;
    border-radius: 0px;
    transition: all 0.3s ease;
    color: #000;
}

.btn-edit {
    background:rgb(255, 255, 255);
}

.btn-edit:hover {
    background: #3498db;
}

.btn-delete {
    background:rgb(255, 255, 255);
}

.btn-delete:hover {
    background:rgb(255, 0, 0);
}

.pagination {
    display: flex;
    gap: 5px;
    margin-top: 20px;
}

.pagination a {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #3498db;
    transition: all 0.3s ease;
}

.pagination a.active {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.pagination a:hover:not(.active) {
    background: #f1f1f1;
}

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
      <li><a href="products.php"><i class="fas fa-box-open"></i> <span>Kelola Produk</span></a></li>
      <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> <span>Tambah Produk</span></a></li>
      <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Pesanan</span></a></li>
      <li><a href="pengguna.php"><i class="fas fa-users"></i> <span>Pengguna</span></a></li>
      <li><a href="kategori.php"><i class="fas fa-tags"></i> <span>Kategori</span></a></li>
      <li><a href="/prog_web/web_repo_steven/pbl02_copy/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
    </ul>
  </aside>

    <div class="main-content">
    <h2>Manajemen Produk</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <a href="add_product.php" class="btn btn-primary mb-3">
        <i class="fas fa-plus-circle"></i>&emsp; Add New Product
    </a>

    <div class="table-responsive">
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Stock</th>
                    <th>Sizes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td>
                            <img src="../../Images/<?= basename($product['image']) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>" 
                                width="50" height="50" 
                                class="product-thumbnail" 
                                style="object-fit: cover;">

                        </td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['category_name']) ?></td>
                        <td>Rp<?= number_format($product['price'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($product['discount']) ?>%</td>
                        <td><?= htmlspecialchars($product['stock']) ?></td>
                        <td><?= nl2br(htmlspecialchars(str_replace(',', "\n", $product['size_available']))) ?></td>
                        <td class="actions">
                            <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn-edit" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="products.php?delete=<?= $product['id'] ?>"
                                class="btn-delete"
                                title="Delete"
                                onclick="return confirmDelete(event)">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="products.php?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<script>
function confirmDelete(e) {
    e.preventDefault();
    if (confirm('Are you sure you want to delete this product?')) {
        window.location.href = e.target.closest('a').href;
    }
    return false;
}
</script>

<?php include 'footer_admin.php'; ?>