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

<!-- BAJU -->   
<section class="produk-kaos" id="products">
  <h2>T-SHIRT COLLECTION</h2>
  <div class="kaos-container">
    <?php
    $products = $conn->query("SELECT * FROM products WHERE category_id = (SELECT id FROM categories WHERE name = 'T-Shirt') OR category = 'T-Shirt'");
    while ($p = $products->fetch_assoc()):
      $hargaAwal = $p['price'] * 2;
      $discountPercent = round(($hargaAwal - $p['price']) / $hargaAwal * 100);
    ?>
      <div class="kaos-item" onclick="openPopup(
        '<?= $p['image'] ?>', 
        '<?= $p['name'] ?>', 
        '<?= $p['price'] ?>', 
        '<?= $p['id'] ?>', 
        '<?= $p['size_available'] ?>')">
        <span class="discount"><?= $discountPercent ?>% OFF</span>
        <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
        <h3><?= $p['name'] ?></h3>
        <p class="price">
          <del>Rp<?= number_format($hargaAwal, 0, ',', '.') ?></del>
          <strong>Rp<?= number_format($p['price'], 0, ',', '.') ?></strong>
        </p>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- Popup Product Detail (T-Shirt / Jacket) -->
<div class="popup-overlay" id="popupOverlay" style="display: none;">
  <div class="popup-content">
    <span class="close-btn" onclick="closePopup()">&times;</span>
    <img id="popupImage" src="" alt="Product Image">
    <h3 id="popupTitle">Product Name</h3>
    <p id="popupPrice" class="popupPrice">Rp0</p>
    <p id="popupStock" class="popupStock">Stok: 0</p>

    <form action="add_to_cart.php" method="POST">
      <input type="hidden" name="product_id" id="popupProductId">
      <input type="hidden" name="size" id="popupSelectedSize">
      <input type="hidden" name="quantity" id="popupQuantity" value="1">

      <div class="popupSize">
        <label>Size:</label>
        <div class="size-buttons">
          <button type="button" id="sizeS" onclick="selectSize('S')">S</button>
          <button type="button" id="sizeM" onclick="selectSize('M')">M</button>
          <button type="button" id="sizeL" onclick="selectSize('L')">L</button>
          <button type="button" id="sizeXL" onclick="selectSize('XL')">XL</button>
        </div>
      </div>

      <div class="popupQuantity">
        <label>Quantity:</label>
        <div class="quantity-wrapper">
          <button type="button" onclick="changeQty(-1)">-</button>
          <span id="qtyDisplay">1</span>
          <button type="button" onclick="changeQty(1)">+</button>
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

<script>
let qty = 1;
let size = '';
let stockInfo = {};

// === T-SHIRT / JACKET ===
function openPopup(img, title, price, id, size_available, stock) {
  document.getElementById('popupOverlay').style.display = 'flex';
  document.getElementById('popupImage').src = img;
  document.getElementById('popupTitle').innerText = title;
  document.getElementById('popupPrice').innerText = 'Rp' + price;
  document.getElementById('popupProductId').value = id;
  qty = 1;
  document.getElementById('qtyDisplay').innerText = qty;
  document.getElementById('popupQuantity').value = qty;

  size = '';
  document.getElementById('popupSelectedSize').value = '';

  stockInfo = {};
  size_available.split(',').forEach(function (item) {
    let [sz, stok] = item.split(':');
    stockInfo[sz] = parseInt(stok);
  });

  // Calculate the total stock for the product
  let totalStock = 0;
  for (let size in stockInfo) {
    totalStock += stockInfo[size];
  }

  // Display total stock in the popup
  document.getElementById('popupStock').innerText = 'Stok: ' + totalStock;

  // Update buttons based on the stock
  ['S', 'M', 'L', 'XL'].forEach(updateSizeButton);
}

function updateSizeButton(sz) {
  const btn = document.getElementById('size' + sz);
  // Display the stock available in parentheses
  btn.innerText = sz + ' (' + (stockInfo[sz] ?? 0) + ')';
  // Disable the button if stock is 0
  btn.disabled = stockInfo[sz] <= 0;
}

function closePopup() {
  document.getElementById('popupOverlay').style.display = 'none';
}

function changeQty(val) {
  qty += val;
  if (qty < 1) qty = 1;
  if (size && qty > stockInfo[size]) qty = stockInfo[size];
  document.getElementById('qtyDisplay').innerText = qty;
  document.getElementById('popupQuantity').value = qty;
}

function selectSize(sz) {
  if (stockInfo[sz] <= 0) {
    alert('Stok habis untuk size ' + sz);
    return;
  }
  size = sz;
  qty = 1;
  document.getElementById('qtyDisplay').innerText = qty;
  document.getElementById('popupQuantity').value = qty;
  document.getElementById('popupSelectedSize').value = sz;

  // Remove 'size-selected' class from all buttons
  ['S', 'M', 'L', 'XL'].forEach(s => {
    document.getElementById('size' + s).classList.remove('size-selected');
  });
  // Add 'size-selected' class to the selected button
  document.getElementById('size' + sz).classList.add('size-selected');
}

function addToCart() {
  const productId = document.getElementById('popupProductId').value;
  const productName = document.getElementById('popupTitle').innerText;
  const priceText = document.getElementById('popupPrice').innerText;

  if (!size) {
    alert('Please select the size first.');
    return;
  }

  const productPrice = parseInt(priceText.replace(/\D/g, ''));

  fetch('add_to_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `product_id=${productId}&quantity=${qty}&size=${encodeURIComponent(size)}`
  })
  .then(res => res.text())
  .then(response => {
    if (response === 'SUCCESS') {
      alert(`${productName} successfully added to cart!`);
      closePopup();
    } else if (response === 'OUT_OF_STOCK') {
      alert('Insufficient stock.');
    } else if (response === 'NOT_LOGGED_IN') {
      alert('You are not logged in yet.');
      window.location.href = 'loginout.php';
    } else {
      alert('An error occurred while adding to cart.');
      console.log(response);
    }
  });
}


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

document.getElementById('newsletterForm').addEventListener('submit', function(e) {
  e.preventDefault(); // Mencegah form reload halaman
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
</script>
</body>
</html>