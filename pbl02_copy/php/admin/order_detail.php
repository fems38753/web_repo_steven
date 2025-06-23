<?php
include '../connect.php';
include 'auth_check.php';

$order_id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $conn->real_escape_string($_POST['status']);
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $order_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Order status updated successfully";
        header("Location: order_detail.php?id=" . $order_id);
        exit();
    } else {
        $error = "Failed to update order status";
    }
}

$order = $conn->query("
    SELECT o.*, u.username, u.email, u.phone 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    WHERE o.id = $order_id
")->fetch_assoc();

if (!$order) {
    $_SESSION['error'] = "Order not found";
    header("Location: orders.php");
    exit();
}

$items = $conn->query("
    SELECT oi.*, p.name AS product_name 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = $order_id
");
?>

<div class="main-content">
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    
    <h2>Order #<?= $order_id ?></h2>
    
    <div class="order-details">
        <div class="customer-info">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($order['username'] ?? 'Guest') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['email'] ?? 'N/A') ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone'] ?? 'N/A') ?></p>
        </div>
        
        <div class="shipping-info">
            <h3>Shipping Information</h3>
            <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
            <p><strong>Notes:</strong> <?= $order['notes'] ? nl2br(htmlspecialchars($order['notes'])) : 'None' ?></p>
        </div>
        
        <div class="order-summary">
            <h3>Order Summary</h3>
            <p><strong>Date:</strong> <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-<?= $order['status'] ?>">
                    <?= ucfirst($order['status']) ?>
                </span>
            </p>
            <p><strong>Total:</strong> Rp<?= number_format($order['total_price'], 0, ',', '.') ?></p>
        </div>
    </div>
    
    <div class="status-update">
        <h3>Update Order Status</h3>
        <form method="post">
            <div class="form-group">
                <select name="status" class="status-select">
                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <button type="submit" class="btn-update">Update Status</button>
            </div>
        </form>
    </div>
    
    <div class="order-items">
        <h3>Order Items</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $items->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>Rp<?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td>Rp<?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <div class="order-actions">
        <a href="orders.php" class="btn-back">← Back to Orders</a>
    </div>
</div>
<?php include 'footer_admin.php'; ?>