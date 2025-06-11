<?php
include 'layout_admin.php';
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

<div class="main-content">
    <h2>Order Management</h2>
    
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

<?php include 'footer_admin.php'; ?>