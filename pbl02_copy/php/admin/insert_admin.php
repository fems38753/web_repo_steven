<?php
// ===== FILE: insert_admin.php =====
include 'layout_admin.php';
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  echo "<p style='color:green'>Admin berhasil ditambahkan.</p>";
}

$admins = $conn->query("SELECT id, email, password FROM admins");
?>

<h2>Tambah Admin</h2>
<form method="post" style="max-width:400px;">
  <label>Email:</label><br>
  <input type="email" name="email" required style="width:100%; padding:8px;"><br><br>

  <label>Password:</label><br>
  <input type="password" name="password" id="admin_password" required style="width:100%; padding:8px;">
  <input type="checkbox" onclick="togglePassword()"> Tampilkan Password<br><br>

  <button type="submit" style="padding:8px 16px;">Tambah Admin</button>
</form>

<h3>Daftar Admin</h3>
<table border="1" cellpadding="10" cellspacing="0">
  <tr>
    <th>ID</th>
    <th>Email</th>
    <th>Password Hash</th>
  </tr>
  <?php while ($a = $admins->fetch_assoc()): ?>
  <tr>
    <td><?= $a['id'] ?></td>
    <td><?= $a['email'] ?></td>
    <td><span style="font-size:12px;"><?= $a['password'] ?></span></td>
  </tr>
  <?php endwhile ?>
</table>

<script>
function togglePassword() {
  const input = document.getElementById("admin_password");
  input.type = input.type === "password" ? "text" : "password";
}
</script>

<hr>