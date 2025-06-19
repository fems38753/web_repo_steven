<?php include 'php/connect.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jackarmyofficial</title>
    <link rel="stylesheet" href="pbl02.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    /* Updated notification style - smaller and more subtle */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: rgba(76, 175, 80, 0.9); /* Slightly transparent */
        color: white;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 14px;
        z-index: 1000;
        animation: slideIn 0.3s, fadeOut 0.3s 2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        width: 250px;
        height: 50px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    @keyframes slideIn {
        from {transform: translateX(100%); opacity: 0;}
        to {transform: translateX(0); opacity: 1;}
    }

    @keyframes fadeOut {
        from {opacity: 1;}
        to {opacity: 0;}
    }
    </style>
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

<!-- TOPI --> 
<section class="produk-topi">
  <h2>HAT COLLECTION</h2>
  <div class="topi-container"> <!-- TAMBAHKAN INI -->
    <?php
      $products = $conn->query("SELECT * FROM products WHERE LOWER(category) = 'hat'");
      while ($p = $products->fetch_assoc()):
        $hargaAwal = $p['price'] * 1.6;
        $discountPercent = round(($hargaAwal - $p['price']) / $hargaAwal * 100);
    ?>
      <div class="kaos-item" onclick="openHatPopup(
          '<?= $p['image'] ?>',
          '<?= htmlspecialchars($p['name']) ?>',
          <?= $p['price'] ?>,
          <?= $p['id'] ?>,
          <?= $p['stock'] ?>
        )">
        <span class="discount"><?= $discountPercent ?>% OFF</span>
        <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
        <h3><?= $p['name'] ?></h3>
        <p class="price"><del>Rp<?= number_format($hargaAwal, 0, ',', '.') ?></del> <strong>Rp<?= number_format($p['price'], 0, ',', '.') ?></strong></p>
      </div>
    <?php endwhile; ?>
  </div> <!-- TUTUP .topi-container -->
</section>

<!-- Popup Product Detail for Hat -->
<div class="popup-overlay" id="hatPopupOverlay" style="display: none;">
  <div class="popup-content">
    <span class="close-btn" onclick="closeHatPopup()">&times;</span>
    <img id="hatPopupImage" src="" alt="Hat Produk">
    <h3 id="hatPopupTitle">Product Name</h3>
    <p class="popupPrice" id="hatPopupPrice">Rp0</p>
    <p id="hatPopupStockInfo" style="color: #666;">Stok: 0</p>

    <form action="add_to_cart.php" method="POST">
      <input type="hidden" name="product_id" id="formHatProductId">
      <input type="hidden" name="size" value="All Size">
      <input type="hidden" name="quantity" id="formHatQty" value="1">

      <div class="popupSize">
        <label>Size:</label>
        <div class="size-buttons">
          <button type="button" class="size-selected">All Size</button>
        </div>
      </div>

      <div class="popupQuantity">
        <label>Quantity:</label>
        <div class="quantity-wrapper">
          <button type="button" onclick="decreaseHatQuantity()">-</button>
          <span id="hatQuantityDisplay">1</span>
          <button type="button" onclick="increaseHatQuantity()">+</button>
        </div>
      </div><br>

      <button type="submit" class="addToCartBtn">Add To Cart</button>
    </form>
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
// === HAT ===
let hatQty = 1;
let hatStock = 0;

function openHatPopup(img, title, price, id, stock) {
  document.getElementById('hatPopupOverlay').style.display = 'flex';
  document.getElementById('hatPopupImage').src = img;
  document.getElementById('hatPopupTitle').textContent = title;
  document.getElementById('hatPopupPrice').textContent = 'Rp' + price.toLocaleString('id-ID');
  document.getElementById('formHatProductId').value = id;
  document.getElementById('formHatQty').value = 1;
  document.getElementById('hatQuantityDisplay').textContent = '1';
  document.getElementById('hatPopupStockInfo').textContent = `Stok: ${stock}`;

  hatQty = 1;
  hatStock = stock;
}

function increaseHatQuantity() {
  if (hatQty < hatStock) {
    hatQty++;
    document.getElementById('hatQuantityDisplay').innerText = hatQty;
    document.getElementById('formHatQty').value = hatQty;
  }
}

function decreaseHatQuantity() {
  if (hatQty > 1) {
    hatQty--;
    document.getElementById('hatQuantityDisplay').innerText = hatQty;
    document.getElementById('formHatQty').value = hatQty;
  }
}

function closeHatPopup() {
  document.getElementById('hatPopupOverlay').style.display = 'none';
}

function addHatToCart() {
  const productId = document.getElementById('formHatProductId').value;
  const qty = document.getElementById('formHatQty').value;
  const productName = document.getElementById('hatPopupTitle').innerText;

  fetch('add_to_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `product_id=${productId}&quantity=${qty}&size=All Size`
  })
  .then(res => res.text())
  .then(response => {
    if (response === 'SUCCESS') {
      alert(`${productName} berhasil ditambahkan ke keranjang!`);
      closeHatPopup();
    } else if (response === 'OUT_OF_STOCK') {
      alert('Stok habis.');
    } else if (response === 'NOT_LOGGED_IN') {
      alert('Anda belum login.');
      window.location.href = 'loginout.php';
    } else {
      alert('Gagal menambahkan produk ke keranjang.');
      console.log(response);
    }
  });
}

function searchProducts() {
    const searchTerm = document.getElementById('searchInput').value.trim().toLowerCase();
    if (searchTerm) {
        alert(`Searching for: ${searchTerm}`);
        // In a real implementation, you would redirect to a search page or filter products
        // window.location.href = `search.php?q=${encodeURIComponent(searchTerm)}`;
    } else {
        alert('Please enter a search term');
    }
}
</script>
</body>
</html>