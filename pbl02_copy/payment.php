<?php include 'php/connect.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jackarmyofficial</title>
    <link rel="stylesheet" href="pbl02.css" />
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

    <main class="payment-container">
        <section class="payment-methods">
            <div class="method-card">
                <i class="fas fa-university"></i>
                <h3>Bank Transfer</h3>
                <p>BCA, Mandiri</p>
            </div>
            <div class="method-card">
                <i class="fas fa-credit-card"></i>
                <h3>Credit Card</h3>
                <p>Visa, Mastercard</p>
            </div>
            <div class="method-card">
                <i class="fas fa-wallet"></i>
                <h3>E-Wallet</h3>
                <p>Gopay, OVO</p>
            </div>
        </section>

        <section class="payment-info">
            <p>Payment is due by <strong>1x24 jam</strong> after purchase.</p>
        </section>

        <a href="index.php" class="btn-back">back to home</a>
    </main>

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
    </div>

    <div class="footer-bottom">
        <p>Copyright &copy; 2025 <strong>JACKARMY</strong></p>
    </div>
</footer>
<script>
    document.getElementById('newsletterForm').addEventListener('submit', function(e) {
  e.preventDefault(); 
  const email = document.getElementById('emailInput').value;
  const messageBox = document.getElementById('newsletterMessage');

  fetch('newsletter_submit.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'email=' + encodeURIComponent(email)
  })
  .then(response => response.text())
  .then(data => {
    messageBox.textContent = data;
    messageBox.style.color = data.toLowerCase().includes('thank') ? 'white' : 'red';
    document.getElementById('newsletterForm').reset();
  })
  .catch(error => {
    messageBox.textContent = "An error occurred.";
    messageBox.style.color = 'red';
  });
});

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