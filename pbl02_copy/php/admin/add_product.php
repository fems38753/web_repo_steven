<?php
// ===== FILE: add_product.php =====
include 'layout_admin.php';
include '../connect.php';

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $category = $_POST['category_id'];
  $price = $_POST['price'];
  $discount = $_POST['discount'];

  $imagePath = '';
  if (!empty($_FILES['image_upload']['name'])) {
    $filename = time() . '_' . basename($_FILES['image_upload']['name']);
    $targetPath = '../uploads/' . $filename;
    if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $targetPath)) {
      $imagePath = 'uploads/' . $filename;
    }
  }

  $stmt = $conn->prepare("INSERT INTO products (name, category_id, price, discount, image) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("siiis", $name, $category, $price, $discount, $imagePath);
  $stmt->execute();
  header("Location: products.php");
  exit;
}
?>

<h2 style="text-align:center;">Tambah Produk</h2>
<form method="post" enctype="multipart/form-data" style="max-width:500px; margin:0 auto;">
  <label>Nama Produk:</label><br>
  <input type="text" name="name" required style="width:100%; padding:8px;"><br><br>

  <label>Kategori:</label><br>
  <select name="category_id" style="width:100%; padding:8px;">
    <?php while ($cat = $categories->fetch_assoc()): ?>
      <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
    <?php endwhile ?>
  </select><br><br>

  <label>Harga:</label><br>
  <input type="number" name="price" required style="width:100%; padding:8px;"><br><br>

  <label>Diskon (%):</label><br>
  <input type="number" name="discount" value="0" style="width:100%; padding:8px;"><br><br>

  <label>Upload Gambar:</label><br>
  <input type="file" name="image_upload" required><br><br>

  <button type="submit" style="padding:10px 20px;">Simpan</button>
</form>

<hr>