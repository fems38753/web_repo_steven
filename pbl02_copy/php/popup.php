<?php
include 'connect.php';

header('Content-Type: application/json');

// Cek apakah parameter id dikirim
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo json_encode(["error" => "Missing or invalid product ID"]);
  exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE id = $id LIMIT 1");

// Cek apakah produk ditemukan
if (!$result || $result->num_rows === 0) {
  echo json_encode(["error" => "Produk tidak ditemukan"]);
  exit;
}

$product = $result->fetch_assoc();

// Parsing size_available dan hitung total stok
$sizes = [];
$totalStock = 0;

if (!empty($product['size_available'])) {
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

// Kirim satu kali respons JSON
echo json_encode([
  "id" => $product['id'],
  "name" => $product['name'],
  "price" => (int) $product['price'],
  "image" => $product['image'],
  "sizes" => $sizes,
  "total_stock" => $totalStock
]);
exit;
