<?php
include '../connect.php';

// Check if sidebar should be minimized
$sidebarMinimized = isset($_COOKIE['sidebar_minimized']) && $_COOKIE['sidebar_minimized'] === 'true';

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price = (int)$_POST['price'];
    $discount = (int)$_POST['discount'];
    $stock = (int)$_POST['stock'];
    $size_available = $conn->real_escape_string($_POST['size_available']);

    // File upload handling
    $image = '';
    if ($_FILES['image']['name']) {
        $target_dir = "../uploads/products/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = "uploads/products/" . $filename;
        }
    }

    $stmt = $conn->prepare("INSERT INTO products 
                          (name, category_id, price, discount, image, stock, size_available) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiissi", $name, $category_id, $price, $discount, $image, $stock, $size_available);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product added successfully";
        header('Location: products.php');
        exit();
    } else {
        $error = "Failed to add product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    :root {
        --sidebar-width: 250px;
        --sidebar-minimized-width: 80px;
        --primary-color: #2c3e50;
        --secondary-color: #3498db;
        --success-color: #2ecc71;
        --danger-color: #e74c3c;
        --light-gray: #f8f9fa;
        --dark-gray: #343a40;
        --transition-speed: 0.3s;
    }

    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f7fa;
        transition: margin-left var(--transition-speed);
    }

    .main-container {
        display: flex;
        min-height: 100vh;
    }

    .main-content {
        flex: 1;
        margin-left: var(--sidebar-width);
        padding: 25px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
        transition: margin-left var(--transition-speed);
        min-height: calc(100vh - 50px);
    }

    .sidebar.minimized ~ .main-content {
        margin-left: var(--sidebar-minimized-width);
    }

    h2 {
        color: var(--primary-color);
        margin-bottom: 25px;
        font-size: 24px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .alert {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        font-size: 14px;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .product-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 14px;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select,
    .form-group input[type="file"],
    .form-group textarea {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border 0.3s ease;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="number"]:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--secondary-color);
        outline: none;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
    }

    .form-row {
        display: flex;
        gap: 20px;
    }

    .form-row .form-group {
        flex: 1;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-submit {
        background: var(--success-color);
        color: white;
    }

    .btn-submit:hover {
        background: #27ae60;
        transform: translateY(-1px);
    }

    .btn-cancel {
        background: #95a5a6;
        color: white;
        text-decoration: none;
        text-align: center;
        line-height: normal;
    }

    .btn-cancel:hover {
        background: #7f8c8d;
        transform: translateY(-1px);
    }

    .size-format-hint {
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 4px;
    }

    .file-upload-wrapper {
        border: 1px dashed #ddd;
        padding: 15px;
        border-radius: 4px;
        text-align: center;
        background: #f9f9f9;
    }

    .file-upload-wrapper:hover {
        border-color: var(--secondary-color);
        background: #f1f9ff;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: var(--sidebar-minimized-width);
        }
        
        .sidebar:not(.minimized) ~ .main-content {
            margin-left: var(--sidebar-width);
        }
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
<body>
    <div class="main-container">
        <div class="main-content">
            <h2>Add Product</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        <?php if ($categories->num_rows > 0): ?>
                            <?php while ($cat = $categories->fetch_assoc()): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="">No categories available</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price (Rp)</label>
                        <input type="number" id="price" name="price" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="discount">Discount (%)</label>
                        <input type="number" id="discount" name="discount" min="0" max="100" value="0">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="size_available">Sizes Available</label>
                        <input type="text" id="size_available" name="size_available" placeholder="S.2,M.3,L.4,XL.5" required>
                        <span class="size-format-hint">Format: Size.Quantity (e.g., S.2,M.3,L.4,XL.5)</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Product Image</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="image" accept="image/*" required>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Save Product</button>
                    <a href="products.php" class="btn btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>