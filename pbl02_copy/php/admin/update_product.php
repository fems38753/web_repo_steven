<?php
include '../connect.php';
include 'auth_check.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $category = $_POST['category_id'];
  $price = $_POST['price'];
  $discount = $_POST['discount'];
  $image = $_POST['image'];
  $imagePath = $product['image']; // default pakai gambar lama

if (!empty($_FILES['image_upload']['name'])) {
    $filename = basename($_FILES['image_upload']['name']);
    $targetPath = "../uploads/" . $filename;

    if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $targetPath)) {
        $imagePath = "uploads/" . $filename;
    }
}

  $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, price=?, discount=?, image=? WHERE id=?");
  $stmt->bind_param("siiisi", $name, $category, $price, $discount, $imagePath, $id);
  $stmt->execute();

  header("Location: products.php");
}
?>

<h2>Edit Product</h2>
<form method="post" enctype="multipart/form-data">
  <label>Product Name:</label><br>
  <input type="text" name="name" value="<?= $product['name'] ?>" required><br>

  <label>category:</label><br>
  <select name="category_id">
    <?php while ($cat = $categories->fetch_assoc()): ?>
      <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
        <?= $cat['name'] ?>
      </option>
    <?php endwhile ?>
  </select><br>

  <label>Price:</label><br>
  <input type="number" name="price" value="<?= $product['price'] ?>" required><br>

  <label>Discount (%):</label><br>
  <input type="number" name="discount" value="<?= $product['discount'] ?>"><br>

  <label>Image (path):</label><br>
  <input type="text" name="image" value="<?= $product['image'] ?>"><br>

  <label>Upload New Image (Opsional):</label><br>
  <input type="file" name="image_upload" accept="image/*"><br>

  <button type="submit">Update</button>
</form>
