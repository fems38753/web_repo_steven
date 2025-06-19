<?php
session_start();
include 'php/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginout.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id"));
$page = $_GET['page'] ?? 'dashboard';

// ⬇⬇⬇ INI YANG DITAMBAHKAN UNTUK MENYIMPAN DATA ⬇⬇⬇
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET email='$email', password='$hashed', no_telp='$no_telp', alamat='$alamat' WHERE id=$user_id";
    } else {
        $sql = "UPDATE users SET email='$email', no_telp='$no_telp', alamat='$alamat' WHERE id=$user_id";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: account.php?page=settings&success=1");
        exit;
    } else {
        echo "<p style='color:red;'>Gagal menyimpan perubahan: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="pbl02.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .container { display: flex; max-width: 1100px; margin: 40px auto; background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
        .sidebar { width: 250px; background: #333; color: white; padding: 30px 20px; }
        .sidebar h2 { color: white; margin-bottom: 20px; }
        .sidebar a { color: white; text-decoration: none; display: block; margin: 10px 0; padding: 10px; border-radius: 5px; }
        .sidebar a:hover { background: #555; }
        .content { flex: 1; padding: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        h3 { margin-top: 0; }
        .btn { padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #2980b9; }

        .settings-form {
          display: flex;
          flex-direction: column;
          gap: 15px;
          max-width: 600px;
        }

        .settings-form .form-row {
          display: flex;
          flex-direction: column;
        }

        .settings-form label {
          font-weight: bold;
          margin-bottom: 4px;
        }

        .settings-form input, 
        .settings-form textarea {
          padding: 10px;
          border: 1px solid #ccc;
          border-radius: 5px;
        }

        .settings-form .btn-save {
          padding: 10px 20px;
          background: #3498db;
          color: white;
          border: none;
          border-radius: 5px;
          cursor: pointer;
        }

        .btn-toggle-sort {
          background: #2c3e50;
          color: white;
          padding: 6px 12px;
          border-radius: 4px;
          text-decoration: none;
          font-size: 14px;
        }
        .btn-toggle-sort:hover {
          background: #34495e;
        }

        .logout-popup {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      z-index: 999;
    }

    .logout-box {
      background: white;
      padding: 30px 25px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      font-family: 'Poppins', sans-serif;
      min-width: 280px;
    }

    .logout-box p {
      font-size: 16px;
      font-weight: 500;
      margin-bottom: 20px;
    }

    .logout-box button {
      padding: 8px 20px;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.2s;
    }

    .logout-btn-yes {
      background-color: #e74c3c;
      color: white;
    }

    .logout-btn-yes:hover {
      background-color: #c0392b;
    }

    .logout-btn-no {
      background-color: #bdc3c7;
      color: #2c3e50;
    }

    .logout-btn-no:hover {
      background-color: #95a5a6;
    }

    .account-settings-form {
  max-width: 600px;
  margin: auto;
}

.account-settings-form input,
.account-settings-form textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 15px;
}

.account-settings-form label {
  font-weight: bold;
  display: block;
  margin-bottom: 5px;
}

.account-settings-form button {
  background-color: #3498db;
  color: white;
  padding: 12px;
  width: 100%;
  border: none;
  border-radius: 5px;
  font-weight: bold;
  cursor: pointer;
}

.account-settings-form button:hover {
  background-color: #2980b9;
}
    </style>
</head>
<body>
<header>
    <nav class="navbar">
  <a href="index.php" class="logo">JACK<span>ARMY</span></a>
  
  <div class="right-navbar">
    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Search...">
      <button onclick="searchProducts()"><i class="fas fa-search"></i></button>
    </div>

    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>

      <li class="dropdown">
        <a href="#"><i class="fas fa-box"></i> Products ▼</a>
        <ul class="dropdown-menu">
          <li><a href="products.php">All Product</a></li>
          <li><a href="baju.php">T-Shirt</a></li>
          <li><a href="jaket.php">Jacket</a></li>
          <li><a href="topi.php">Hat</a></li>
        </ul>
      </li>

      <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>

      <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
        <li><a href="account.php"><i class="fas fa-user"></i></a></li>
      <?php elseif (isset($_SESSION['admin_id']) && $_SESSION['role'] === 'admin'): ?>
        <li><a href="php/admin/dashboard.php"><i class="fas fa-user-shield"></i> Admin Panel</a></li>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="loginout.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
      <?php endif; ?>

      <li class="dropdown">
        <a href="#"><i class="fas fa-question-circle"></i>▼</a>
        <ul class="dropdown-menu">
          <li><a href="shopping.php">How To Order</a></li>
          <li><a href="shipping.php">Shipping Information</a></li>
          <li><a href="payment.php">Payment Methods</a></li>
          <li><a href="refund.php">Refund & Return Policy</a></li>
          <li><a href="size.php">Size Chart</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
</header>

<div class="container">
    <div class="sidebar">
        <h2>My Account</h2>
        <a href="account.php?page=dashboard">Dashboard</a>
        <a href="account.php?page=orders">My Orders</a>
        <a href="account.php?page=settings">Account Settings</a>
        <a href="#" onclick="confirmLogout(event)">Logout</a>
    </div>
    <div class="content">
        <?php if ($page === 'dashboard'): ?>
            <h3>Hi <?= htmlspecialchars($user['username']) ?> 👋</h3>
            <p>Ubah informasi Anda di halaman <a href="account.php?page=settings">Account Settings</a></p>
            <table>
                <tr><th>Email</th><td><?= htmlspecialchars($user['email']) ?></td><td><a class="btn" href="account.php?page=settings">Edit</a></td></tr>
                <tr><th>No. Telepon</th><td><?= htmlspecialchars($user['no_telp']) ?></td><td><a class="btn" href="account.php?page=settings">Edit</a></td></tr>
                <tr><th>Alamat</th><td><?= htmlspecialchars($user['alamat']) ?></td><td><a class="btn" href="account.php?page=settings">Edit</a></td></tr>
                <tr><th>Akun Dibuat</th><td><?= $user['created_at'] ?? 'N/A' ?></td><td></td></tr>
                <tr><th>Order Terakhir</th><td colspan="2"><a class="btn" href="account.php?page=orders">See</a></td></tr>
            </table>

        <?php elseif ($page === 'orders'): ?>
  <h3>My Orders</h3>

  <?php
    // Toggle sort ASC/DESC
    $sortOrder = isset($_GET['sort']) && $_GET['sort'] === 'asc' ? 'ASC' : 'DESC';
    $toggleOrder = $sortOrder === 'ASC' ? 'desc' : 'asc';

    // Query orders berdasarkan user dan sort
    $orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY id $sortOrder");
  ?>

  <!-- Tombol Sort -->
  <div style="margin: 10px 0;">
    <a href="account.php?page=orders&sort=<?= $toggleOrder ?>" class="btn-toggle-sort">
      Sort by ID <?= $sortOrder === 'ASC' ? '▲' : '▼' ?>
    </a>
  </div>

  <?php if (mysqli_num_rows($orders) === 0): ?>
    <p>Belum ada pesanan.</p>
  <?php else: ?>
    <table style="width:100%; border-collapse: collapse; margin-top: 10px;">
      <thead style="background-color: #f2f2f2;">
        <tr>
          <th style="padding: 10px; border: 1px solid #ccc;">ID</th>
          <th style="padding: 10px; border: 1px solid #ccc;">Total</th>
          <th style="padding: 10px; border: 1px solid #ccc;">Payment</th>
          <th style="padding: 10px; border: 1px solid #ccc;">Shipping</th>
          <th style="padding: 10px; border: 1px solid #ccc;">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($o = mysqli_fetch_assoc($orders)): ?>
          <tr>
            <td style="padding: 10px; border: 1px solid #ccc;">#<?= $o['id'] ?></td>
            <td style="padding: 10px; border: 1px solid #ccc;">Rp<?= number_format($o['total_price'], 0, ',', '.') ?></td>
            <td style="padding: 10px; border: 1px solid #ccc;"><?= ucfirst($o['payment_method']) ?></td>
            <td style="padding: 10px; border: 1px solid #ccc;"><?= $o['shipping_method'] ?></td>
            <td style="padding: 10px; border: 1px solid #ccc; color: green; font-weight: bold;">Complete</td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>

        <?php elseif ($page === 'settings'): ?>
  <h3>Account Settings</h3>

  <?php if (isset($_GET['success'])): ?>
    <div style="color:green; margin-bottom:10px;">Perubahan berhasil disimpan.</div>
  <?php endif; ?>

  <form method="POST" class="account-settings-form">
    <label>Email:</label>
    <input type="email" name="email" value="<?= $user['email'] ?>" required>

    <label>Password <small>(biarkan kosong jika tidak ingin diganti)</small>:</label>
    <input type="password" name="password" placeholder="******">

    <label>No. Telp:</label>
    <input type="text" name="no_telp" value="<?= $user['no_telp'] ?? '' ?>">

    <label>Alamat:</label>
    <textarea name="alamat"><?= $user['alamat'] ?? '' ?></textarea>

    <button type="submit" name="update_account">Simpan Perubahan</button>
  </form>
<?php endif; ?>
    </div>
</div>

<div id="logoutPopup" class="logout-popup">
  <div class="logout-box">
    <p>Apakah Anda yakin ingin keluar?</p>
    <div style="display: flex; justify-content: center; gap: 15px;">
      <button class="logout-btn-yes" onclick="window.location.href='logout.php'">Ya</button>
      <button class="logout-btn-no" onclick="document.getElementById('logoutPopup').style.display='none'">Tidak</button>
    </div>
  </div>
</div>

<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h4>Hello JackArmyFriends!</h4>
            <p>You can also order via:</p>
            <div class="social-icons">
                <a href="https://www.instagram.com/jackarmy.official/?hl=en"><img src="images/2.png" alt="Instagram"></a>
                <a href="https://www.tiktok.com/@jackarmyofficial"><img src="images/4.png" alt="TikTok"></a>
            </div>
            <a href="#" class="customer-service">Customer Service</a><br>
            <div class="social-icons">
            <a href="https://wa.me/6282197194669"><img src="images/3.png" alt="WhatsApp"></a>
        </div>
        </div>

        <div class="footer-section">
            <h4>Products</h4>
                <ul>
                    <li><a href="products.php">All Product</a></li>
                    <li><a href="baju.php">T-Shirt</a></li>
                    <li><a href="jaket.php">Jacket</a></li>
                    <li><a href="topi.php">Hat</a></li>
                </ul>
        </div>

        <div class="footer-section">
            <h4>Help Center</h4>
            <ul>
                <li><a href="shopping.php">How To Order</a></li>
                <li><a href="shipping.php">Shipping Information</a></li>
                <li><a href="payment.php">Payment Methods</a></li>
                <li><a href="refund.php">Refund & Return Policy</a></li>
                <li><a href="size.php">Size Chart</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Newsletter</h4>
            <form id="newsletterForm">
                <input type="email" id="emailInput" placeholder="Insert your email" required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>

    <div class="footer-bottom">
        <p>Copyright &copy; 2025 <strong>JACKARMY</strong></p>
    </div>
</footer>

<script>
function confirmLogout(e) {
  e.preventDefault();
  document.getElementById('logoutPopup').style.display = 'flex';
}
</script>
</body>
</html>
