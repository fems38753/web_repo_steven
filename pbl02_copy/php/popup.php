<?php
include 'connect.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(["error" => "Missing or invalid product ID"]);
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
if (!$stmt) {
    echo json_encode(["error" => "Database error"]);
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    echo json_encode(["error" => "Produk tidak ditemukan"]);
    exit;
}

$product = $result->fetch_assoc();

$requiredFields = ['name', 'price', 'image', 'size_available'];
foreach ($requiredFields as $field) {
    if (!isset($product[$field])) {
        echo json_encode(["error" => "Data produk tidak lengkap"]);
        exit;
    }
}

$sizes = [];
$totalStock = 0;

if (!empty($product['size_available'])) {
    // Validasi format
    if (!preg_match('/^([a-zA-Z]+:\d+,?)+$/', $product['size_available'])) {
        echo json_encode(["error" => "Format size_available tidak valid"]);
        exit;
    }

    $pairs = explode(',', $product['size_available']);
    foreach ($pairs as $pair) {
        $parts = explode(':', $pair);
        if (count($parts) === 2) {
            $size = trim($parts[0]);
            $stock = (int) trim($parts[1]);
            $sizes[] = ["size" => $size, "stock" => $stock];
            $totalStock += $stock;
        }
    }
}

echo json_encode([
    "id" => (int) $product['id'],
    "name" => $product['name'],
    "price" => (int) $product['price'],
    "image" => $product['image'],
    "sizes" => $sizes,
    "total_stock" => $totalStock,
    "success" => true
]);

$stmt->close();
$conn->close();
exit;
?>