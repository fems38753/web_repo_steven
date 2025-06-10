<?php include 'php/connect.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jackarmyofficial</title>
    <link rel="stylesheet" href="pbl02.css">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">JACK<span>ARMY</span></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li class="dropdown">
                <a href="#">Products ▼</a>
                <ul class="dropdown-menu">
                    <li><a href="products.php">All Product</a></li>
                    <li><a href="baju.php">T-Shirt</a></li>
                    <li><a href="jaket.php">Jacket</a></li>
                    <li><a href="topi.php">Hat</a></li>
                </ul>
            </li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="account.php">Account</a></li>
            <li class="dropdown">
                <a href="#">Help Center ▼</a>
                <ul class="dropdown-menu">
                <li><a href="shopping.php">How To Order</a></li>
                <li><a href="shipping.php">Shipping Information</a></li>
                <li><a href="payment.php">Payment Methods</a></li>
                <li><a href="refund.php">Refund & Return Policy</a></li>
                <li><a href="size.php">Size Chart</a></li>
                </ul>
            </li>
        </ul>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search...">
            <button onclick="searchProducts()">Search</button>
        </div>
    </nav>
</header>

<!-- Baju -->
<section class="produk-kaos">
  <h2>T-SHIRT COLLECTION</h2>
  <div class="kaos-container">
    <?php
    $products = $conn->query("SELECT * FROM products WHERE category = 'T-Shirt'");
    while ($p = $products->fetch_assoc()):
      $hargaAwal = $p['price'] * 2; // estimasi harga awal sebelum diskon
    ?>
      <div class="kaos-item" onclick="openPopup('<?= $p['image'] ?>', '<?= $p['name'] ?>', 'Rp<?= number_format($p['price']) ?>')">
        <span class="discount">Diskon!</span>
        <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
        <h3><?= $p['name'] ?></h3>
        <p class="price"><del>Rp<?= number_format($hargaAwal) ?></del> <strong>Rp<?= number_format($p['price']) ?></strong></p>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- Popup Product Detail -->
<div class="popup-overlay" id="popupOverlay" style="display: none;">
  <div class="popup-content">
    <span class="close-btn" onclick="closePopup()">&times;</span>
    <img id="popupImage" src="" alt="Produk">
    <h3 id="popupTitle">Product Name</h3>
    <p class="popupPrice" id="popupPrice">Rp0</p>

    <div class="popupSize">
      <label>Size Choice:</label>
      <div class="size-buttons">
        <button onclick="selectSize('S')">S</button>
        <button onclick="selectSize('M')">M</button>
        <button onclick="selectSize('L')">L</button>
        <button onclick="selectSize('XL')">XL</button>
      </div>
    </div>

    <div class="popupQuantity">
      <label>Quantity:</label>
      <div class="quantity-wrapper">
        <button onclick="decreaseQuantity()">-</button>
        <span id="quantityDisplay">1</span>
        <button onclick="increaseQuantity()">+</button>
      </div>
    </div><br>

    <a href="#" class="addToCartBtn" onclick="addToCart()">Add To Cart</a>
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
<script src="cart.js"></script>
<script src="news.js"></script>
<script src="account.js"></script>
<script>
// Popup functionality
let currentQuantity = 1;
let selectedSize = '';

function openPopup(imageSrc, productName, productPrice) {
    document.getElementById('popupImage').src = imageSrc;
    document.getElementById('popupTitle').textContent = productName;
    document.getElementById('popupPrice').textContent = productPrice;
    document.getElementById('popupOverlay').style.display = 'flex';
    document.getElementById('quantityDisplay').textContent = '1';
    currentQuantity = 1;
    selectedSize = '';
}

function closePopup() {
    document.getElementById('popupOverlay').style.display = 'none';
}

function selectSize(size) {
    selectedSize = size;
    // You can add visual feedback for selected size here
    const buttons = document.querySelectorAll('.size-buttons button');
    buttons.forEach(button => {
        button.style.backgroundColor = button.textContent === size ? '#333' : '#f1f1f1';
        button.style.color = button.textContent === size ? 'white' : 'black';
    });
}

function increaseQuantity() {
    currentQuantity++;
    document.getElementById('quantityDisplay').textContent = currentQuantity;
}

function decreaseQuantity() {
    if (currentQuantity > 1) {
        currentQuantity--;
        document.getElementById('quantityDisplay').textContent = currentQuantity;
    }
}

function addToCart() {
    if (!selectedSize) {
        alert('Please select a size first');
        return;
    }
    
    const productName = document.getElementById('popupTitle').textContent;
    const productPrice = document.getElementById('popupPrice').textContent;
    
    // Here you would typically send this data to your server or update localStorage
    alert(`Added to cart: ${productName} (Size: ${selectedSize}, Quantity: ${currentQuantity})`);
    closePopup();
}

function searchProducts() {
    const searchTerm = document.getElementById('searchInput').value.trim().toLowerCase();
    if (searchTerm) {
        // Here you would typically redirect to a search page or filter products
        alert(`Searching for: ${searchTerm}`);
    } else {
        alert('Please enter a search term');
    }
}
</script>
</body>
</html>