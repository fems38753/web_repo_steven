<?php
include 'layout_admin.php';
include '../connect.php';

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$id = (int)$_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

if (!$product) {
    $_SESSION['error'] = "Product not found";
    header('Location: products.php');
    exit();
}

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price = (int)$_POST['price'];
    $discount = (int)$_POST['discount'];
    $stock = (int)$_POST['stock'];
    $size_available = $conn->real_escape_string($_POST['size_available']);
    
    // Keep existing image unless new one is uploaded
    $image = $product['image'];
    
    if ($_FILES['image']['name']) {
        $target_dir = "../uploads/products/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Delete old image if exists
        if ($image && file_exists("../$image")) {
            unlink("../$image");
        }
        
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = "uploads/products/" . $filename;
        }
    }
    
    $stmt = $conn->prepare("UPDATE products SET 
                          name=?, category_id=?, price=?, discount=?, image=?, stock=?, size_available=?
                          WHERE id=?");
    $stmt->bind_param("siiissii", $name, $category_id, $price, $discount, $image, $stock, $size_available, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product updated successfully";
        header('Location: products.php');
        exit();
    } else {
        $error = "Failed to update product: " . $conn->error;
    }
}
?>

<style>
/* Admin Panel Styles */
body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

.main-content {
    padding: 30px;
    margin-left: 250px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0,0,0,0.05);
    max-width: 800px;
}

h2 {
    color: crimson;
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
    color: #2c3e50;
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
    border-color: #3498db;
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
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.btn-submit {
    background: #2ecc71;
    color: white;
}

.btn-submit:hover {
    background: #27ae60;
    transform: translateY(-1px);
}

.btn-cancel {
    background: #95a5a6;
    color: white;
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
    border-color: #3498db;
    background: #f1f9ff;
}

.current-image-preview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 4px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
    display: block;
}

.image-preview-container {
    margin-bottom: 15px;
}

.image-actions {
    display: flex;
    gap: 10px;
    margin-top: 5px;
}

.image-actions button {
    padding: 5px 10px;
    font-size: 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background 0.2s;
}

.change-image-btn {
    background: #3498db;
    color: white;
}

.change-image-btn:hover {
    background: #2980b9;
}

.remove-image-btn {
    background: #e74c3c;
    color: white;
}

.remove-image-btn:hover {
    background: #c0392b;
}
</style>

<div class="main-content">
    <h2>Edit Product</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="product-form">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="price">Price (Rp)</label>
                <input type="number" id="price" name="price" value="<?= $product['price'] ?>" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="discount">Discount (%)</label>
                <input type="number" id="discount" name="discount" value="<?= $product['discount'] ?>" min="0" max="100">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" value="<?= $product['stock'] ?>" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="size_available">Sizes Available</label>
                <input type="text" id="size_available" name="size_available" value="<?= htmlspecialchars($product['size_available']) ?>" required>
                <span class="size-format-hint">Format: Size.Quantity (e.g., S.2,M.3,L.4,XL.5)</span>
            </div>
        </div>
        
        <div class="form-group">
            <label>Product Image</label>
            <?php if ($product['image']): ?>
                <div class="image-preview-container">
                    <img src="../<?= $product['image'] ?>" class="current-image-preview">
                    <div class="image-actions">
                        <button type="button" class="change-image-btn" onclick="document.querySelector('.file-upload-wrapper input').click()">Change Image</button>
                        <button type="button" class="remove-image-btn" onclick="confirmRemoveImage()">Remove Image</button>
                    </div>
                </div>
            <?php endif; ?>
            <div class="file-upload-wrapper">
                <input type="file" name="image" accept="image/*" <?= empty($product['image']) ? 'required' : '' ?>>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-submit">Update Product</button>
            <a href="products.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<script>
function confirmRemoveImage() {
    if (confirm('Are you sure you want to remove the current image?')) {
        // You can implement AJAX to remove the image or just clear the preview
        document.querySelector('.image-preview-container').style.display = 'none';
        document.querySelector('.file-upload-wrapper input').required = true;
    }
}
</script>

<?php include 'footer_admin.php'; ?>