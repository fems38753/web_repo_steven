<?php include 'php/connect.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jackarmyofficial</title>
    <link rel="stylesheet" href="pbl02.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<body>
<header>
  <nav class="navbar">
  <a href="index.php" class="logo">JACK<span>ARMY</span></a>
  
  <div class="right-navbar">
      <!-- ✅ Search Bar -->
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
          <li><a href="celana.php">Pants</a></li>
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
          <li><a href="size.php">Size Chart</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
</header>

    <section class="shipping-container">
        <div class="shipping-content">
            <h3>Fast and safe shipping</h3>
            <p>Trust our courier:</p>
            <ul class="shipping-list">
                <li>JNE</li>
                <li>J&T</li>
                <li>SiCepat</li>
                <li>Ninja Express</li>
            </ul>
            <p><strong>Shipping Estimation:</strong></p>
            <ul class="estimasi-list">
                <li>Jabodetabek: 1-2 working days</li>
                <li>Pulau Jawa: 2-3 working days</li>
                <li>Luar Jawa: 3-7 working days</li>
            </ul>
            <p>Shipping cost will be automatically added on your checkout base on your location.</p>

            <a href="index.php" class="btn-back">Back to Home</a>
        </div>

        <div class="shipping-image">
            <img src="images/shipping.png" alt="Ilustrasi Pengiriman" />
        </div>
    </section>

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
                <ul class="dropdown-menu">
                  <li><a href="products.php">All Product</a></li>
                  <li><a href="baju.php">T-Shirt</a></li>
                  <li><a href="jaket.php">Jacket</a></li>
                  <li><a href="celana.php">Pants</a></li>
                  <li><a href="topi.php">Hat</a></li>
                </ul>
        </div>

        <div class="footer-section">
            <h4>Help Center</h4>
            <ul>
                <li><a href="shopping.php">How To Order</a></li>
                <li><a href="shipping.php">Shipping Information</a></li>
                <li><a href="payment.php">Payment Methods</a></li>
                <li><a href="size.php">Size Chart</a></li>
            </ul>
        </div>

        <div class="footer-section">
          <h4>Newsletter</h4>
            <form id="newsletterForm">
              <input type="email" name="email" id="emailInput" placeholder="Insert your email" required>
              <button type="submit">Send</button>
            </form>
            <p id="newsletterMessage" style="margin-top: 10px; color: green;"></p>
      </div>
    </div>

    <div class="footer-bottom">
        <p>Copyright &copy; 2025 <strong>JACKARMY</strong></p>
    </div>
</footer>

<script src="news.js">
// Search
function searchProducts() {
  const searchTerm = document.getElementById('searchInput').value.trim();
  if (searchTerm) {
    window.location.href = `search.php?query=${encodeURIComponent(searchTerm)}`;
  } else {
    alert('Please enter a search term');
  }
}

document.getElementById('searchInput').addEventListener('keypress', function (e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    searchProducts();
  }
});
</script>
</body>
</html>