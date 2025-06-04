<?php
include 'layout_admin.php';
include '../connect.php';

$result = $conn->query("SELECT p.id, p.name, p.price, p.discount, p.image, c.name AS category 
                        FROM products p JOIN categories c ON p.category_id = c.id");
?>

<h2>Daftar Produk</h2>
<table border="1" cellpadding="10" cellspacing="0">
  <tr>
    <th>Nama</th>
    <th>Kategori</th>
    <th>Harga</th>
    <th>Diskon</th>
    <th>Gambar</th>
    <th>Aksi</th>
  </tr>
  <?php while ($p = $result->fetch_assoc()): ?>
  <tr>
    <td><?= $p['name'] ?></td>
    <td><?= $p['category'] ?></td>
    <td>Rp<?= number_format($p['price']) ?></td>
    <td><?= $p['discount'] ?>%</td>
    <td><img src="../<?= $p['image'] ?>" width="80"></td>
    <td>
      <a href="update_product.php?id=<?= $p['id'] ?>">Edit</a> |
      <a href="delete_product.php?id=<?= $p['id'] ?>" onclick="return confirm('Yakin?')">Hapus</a>
    </td>
  </tr>
  <?php endwhile ?>
</table>
