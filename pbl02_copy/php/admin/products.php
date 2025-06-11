<?php
include 'layout_admin.php';
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
    ORDER BY p.id DESC
    LIMIT $limit OFFSET $offset
");

$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$total_pages = ceil($total_products / $limit);
?>

<style>
.main-content {
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0,0,0,0.05);
    margin: 20px;
}

h2 {
    color: #2c3e50;
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
    padding: 12px 15px;
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
    padding: 5px;
    border-radius: 4px;
    transition: all 0.3s ease;
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
</style>

<div class="main-content">
    <h2>Product Management</h2>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    
    <a href="add_product.php" class="btn btn-primary mb-3">
        <img src="images/logo_add.png" alt="Add" width="16"> Add New Product
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
                    <td><img src="../<?= $product['image'] ?>" width="50" height="50" class="product-thumbnail" style="object-fit: cover;"></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['category_name']) ?></td>
                    <td>Rp<?= number_format($product['price'], 0, ',', '.') ?></td>
                    <td><?= $product['discount'] ?>%</td>
                    <td><?= $product['stock'] ?></td>
                    <td><?= str_replace(',', '<br>', htmlspecialchars($product['size_available'])) ?></td>
                    <td class="actions">
                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn-edit" title="Edit">
                            <img src="images/logo_edit.png" alt="Edit" width="16">
                        </a>
                        <a href="products.php?delete=<?= $product['id'] ?>" 
                           class="btn-delete" 
                           title="Delete"
                           onclick="return confirmDelete(event)">
                            <img src="images/logo_delete.png" alt="Delete" width="16">
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
            <a href="products.php?page=<?= $i ?>" <?= $i == $page ? 'class="active"' : '' ?>>
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