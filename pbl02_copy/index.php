<?php
session_start();
include 'php/connect.php';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jackarmyofficial</title>
    <link rel="stylesheet" href="pbl02.css">
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
<body>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
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

            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
                <li><a href="account.php">Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php elseif (isset($_SESSION['admin_id']) && $_SESSION['role'] === 'admin'): ?>
                <li><a href="php/admin/dashboard.php">Admin Panel</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="loginout.php">Login</a></li>
            <?php endif; ?>

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

<!-- Hero Section -->
<section class="hero-carousel">
  <div class="carousel">
    <!-- Navigation Buttons -->
    <button class="prev" aria-label="Previous Slide" onclick="moveSlide(-1)">&#10094;</button>

    <!-- Carousel Slides -->
    <img src="Images/hero1.jpg" class="slide active" alt="Hero 1">
    <img src="Images/hero2.jpg" class="slide" alt="Hero 2">
    <img src="Images/hero3.jpg" class="slide" alt="Hero 3">
    <img src="Images/hero4.jpg" class="slide" alt="Hero 4">

    <button class="next" aria-label="Next Slide" onclick="moveSlide(1)">&#10095;</button>
  </div>
</section>

<!-- Produk Kaos -->
<section class="produk-kaos">
  <h2>T-SHIRT COLLECTION</h2>
  <div class="kaos-container">
    <div class="kaos-item" onclick="openPopup('images/baju1.jpg', 'Kaos Origami Navy', 'Rp79.900')">
      <span class="discount">60% OFF</span>
      <img src="images/baju1.jpg" alt="Kaos Origami Navy">
      <h3>T-Shirt Angel Demon</h3>
      <p class="price"><del>Rp199.000</del> <strong>Rp79.900</strong></p>
    </div>
    <div class="kaos-item" onclick="openPopup('images/baju2.jpg', 'T-Shirt Wolf', 'Rp69.000')">
      <span class="discount">65% OFF</span>
      <img src="images/baju2.jpg" alt="T-Shirt Wolf">
      <h3>T-Shirt Wolf</h3>
      <p class="price"><del>Rp199.000</del> <strong>Rp69.000</strong></p>
    </div>
    <div class="kaos-item" onclick="openPopup('images/baju3.jpg', 'T-Shirt Breath', 'Rp89.000')">
      <span class="discount">55% OFF</span>
      <img src="images/baju3.jpg" alt="T-Shirt Breath">
      <h3>T-Shirt Breath</h3>
      <p class="price"><del>Rp199.000</del> <strong>Rp89.000</strong></p>
    </div>
    <div class="kaos-item" onclick="openPopup('images/baju4.jpg', 'T-Shirt Creates', 'Rp93.000')">
      <span class="discount">53% OFF</span>
      <img src="images/baju4.jpg" alt="T-Shirt Creates">
      <h3>T-Shirt Creates</h3>
      <p class="price"><del>Rp199.000</del> <strong>Rp93.000</strong></p>
    </div>
    <div class="kaos-item" onclick="openPopup('images/baju5.jpg', 'T-Shirt Love Hurts', 'Rp79.900')">
      <span class="discount">60% OFF</span>
      <img src="images/baju5.jpg" alt="T-Shirt Love Hurts">
      <h3>T-Shirt Love Hurts</h3>
      <p class="price"><del>Rp199.000</del> <strong>Rp79.900</strong></p>
    </div>
  </div>
</section>

<!-- Produk Jaket -->
<section class="produk-jaket">
  <h2>JACKET COLLECTION</h2>
  <div class="jaket-container">
    <div class="kaos-item" onclick="openPopup('images/jaket1.jpg', 'Jacket Hoodie Starboy', 'Rp199.000')">
      <span class="discount">50% OFF</span>
      <img src="images/jaket1.jpg" alt="Jacket Hoodie Starboy">
      <h3>Jacket Hoodie Starboy</h3>
      <p class="price"><del>Rp399.000</del> <strong>Rp199.000</strong></p>
    </div>
    <div class="kaos-item" onclick="openPopup('images/jaket2.jpg', 'Jacket Hoodie X', 'Rp197.450')">
      <span class="discount">45% OFF</span>
      <img src="images/jaket2.jpg" alt="Jacket Hoodie X">
      <h3>Jacket Hoodie X</h3>
      <p class="price"><del>Rp359.000</del> <strong>Rp197.450</strong></p>
    </div>
    <div class="kaos-item" onclick="openPopup('images/jaket3.jpg', 'Jacket Hoodie Star', 'Rp199.000')">
      <span class="discount">50% OFF</span>
      <img src="images/jaket3.jpg" alt="Jacket Hoodie Star">
      <h3>Jacket Hoodie Star</h3>
      <p class="price"><del>Rp399.000</del> <strong>Rp199.000</strong></p>
    </div>
    <div class="kaos-item" onclick="openPopup('images/jaket4.jpg', 'Jacket Cardigan', 'Rp197.450')">
      <span class="discount">45% OFF</span>
      <img src="images/jaket4.jpg" alt="Jacket Cardigan">
      <h3>Jacket Cardigan</h3>
      <p class="price"><del>Rp359.000</del> <strong>Rp197.450</strong></p>
    </div>
    <div class="kaos-item" onclick="openPopup('images/jaket5.jpg', 'Jacket Canvas', 'Rp199.000')">
      <span class="discount">50% OFF</span>
      <img src="images/jaket5.jpg" alt="Jacket Canvas">
      <h3>Jacket Canvas</h3>
      <p class="price"><del>Rp399.000</del> <strong>Rp199.000</strong></p>
    </div>
  </div>
</section>

<!-- Produk Topi -->
<section class="produk-topi">
  <h2>HAT COLLECTION</h2>
  <div class="topi-container">
    <div class="kaos-item" onclick="openHatPopup('images/topi1.jpg', 'Bucket Hat Nylon Black', 'Rp95.400')">
      <span class="discount">40% OFF</span>
      <img src="images/topi1.jpg" alt="Bucket Hat Nylon Black">
      <h3>Bucket Hat Nylon Black</h3>
      <p class="price"><del>Rp159.000</del> <strong>Rp95.400</strong></p>
    </div>
    <div class="kaos-item" onclick="openHatPopup('images/topi2.jpg', 'Bucket Hat Nusantara', 'Rp83.850')">
      <span class="discount">35% OFF</span>
      <img src="images/topi2.jpg" alt="Bucket Hat Nusantara">
      <h3>Bucket Hat Nusantara</h3>
      <p class="price"><del>Rp129.000</del> <strong>Rp83.850</strong></p>
    </div>
    <div class="kaos-item" onclick="openHatPopup('images/topi3.jpg', 'Hat Nusantara Bold', 'Rp95.400')">
      <span class="discount">40% OFF</span>
      <img src="images/topi3.jpg" alt="Hat Nusantara Bold">
      <h3>Hat Nusantara Bold</h3>
      <p class="price"><del>Rp159.000</del> <strong>Rp95.400</strong></p>
    </div>
    <div class="kaos-item" onclick="openHatPopup('images/topi4.jpg', 'Hat Nocturn', 'Rp83.850')">
      <span class="discount">35% OFF</span>
      <img src="images/topi4.jpg" alt="Hat Nocturn">
      <h3>Hat Nocturn</h3>
      <p class="price"><del>Rp129.000</del> <strong>Rp83.850</strong></p>
    </div>
    <div class="kaos-item" onclick="openHatPopup('images/topi5.jpg', 'Hat Freedom', 'Rp95.400')">
      <span class="discount">40% OFF</span>
      <img src="images/topi5.jpg" alt="Hat Freedom">
      <h3>Hat Freedom</h3>
      <p class="price"><del>Rp159.000</del> <strong>Rp95.400</strong></p>
    </div>
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

<!-- Hat Popup Product Detail (with All Size option) -->
<div class="popup-overlay" id="hatPopupOverlay" style="display: none;">
  <div class="popup-content">
    <span class="close-btn" onclick="closeHatPopup()">&times;</span>
    <img id="hatPopupImage" src="" alt="Produk">
    <h3 id="hatPopupTitle">Product Name</h3>
    <p class="popupPrice" id="hatPopupPrice">Rp0</p>

    <div class="popupSize">
      <label>Size:</label>
      <div class="size-buttons">
        <button class="size-selected" onclick="selectHatSize('All Size')">All Size</button>
      </div>
    </div>

    <div class="popupQuantity">
      <label>Quantity:</label>
      <div class="quantity-wrapper">
        <button onclick="decreaseHatQuantity()">-</button>
        <span id="hatQuantityDisplay">1</span>
        <button onclick="increaseHatQuantity()">+</button>
      </div>
    </div><br>

    <a href="#" class="addToCartBtn" onclick="addHatToCart()">Add To Cart</a>
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
// Carousel functionality
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');

function showSlide(n) {
    slides.forEach(slide => slide.classList.remove('active'));
    currentSlide = (n + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
}

function moveSlide(n) {
    showSlide(currentSlide + n);
}

// Auto-advance slides every 5 seconds
setInterval(() => moveSlide(1), 5000);

// Popup functionality for regular products
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
    
    // Reset size buttons
    const buttons = document.querySelectorAll('.size-buttons button');
    buttons.forEach(button => {
        button.style.backgroundColor = '#f1f1f1';
        button.style.color = 'black';
    });
}

function closePopup() {
    document.getElementById('popupOverlay').style.display = 'none';
}

function selectSize(size) {
    selectedSize = size;
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
    const productPriceText = document.getElementById('popupPrice').textContent;
    const productImage = document.getElementById('popupImage').src;
    
    // Extract numeric price from text (e.g., "Rp79.900" -> 79900)
    const productPrice = parseInt(productPriceText.replace(/\D/g, ''));
    
    // Get or initialize cart from localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Add new item to cart
    cart.push({
        name: productName,
        price: productPrice,
        image: productImage,
        size: selectedSize,
        quantity: currentQuantity
    });
    
    // Save back to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Show success notification
    showNotification(`${productName} added to cart!`);
    
    // Close popup
    closePopup();
}

// Popup functionality for hat products
let hatCurrentQuantity = 1;
let hatSelectedSize = 'All Size';

function openHatPopup(imageSrc, productName, productPrice) {
    document.getElementById('hatPopupImage').src = imageSrc;
    document.getElementById('hatPopupTitle').textContent = productName;
    document.getElementById('hatPopupPrice').textContent = productPrice;
    document.getElementById('hatPopupOverlay').style.display = 'flex';
    document.getElementById('hatQuantityDisplay').textContent = '1';
    hatCurrentQuantity = 1;
    hatSelectedSize = 'All Size';
    
    // Set All Size as selected
    const sizeButton = document.querySelector('#hatPopupOverlay .size-buttons button');
    sizeButton.style.backgroundColor = '#333';
    sizeButton.style.color = 'white';
}

function closeHatPopup() {
    document.getElementById('hatPopupOverlay').style.display = 'none';
}

function selectHatSize(size) {
    hatSelectedSize = size;
    const sizeButton = document.querySelector('#hatPopupOverlay .size-buttons button');
    sizeButton.style.backgroundColor = '#333';
    sizeButton.style.color = 'white';
}

function increaseHatQuantity() {
    hatCurrentQuantity++;
    document.getElementById('hatQuantityDisplay').textContent = hatCurrentQuantity;
}

function decreaseHatQuantity() {
    if (hatCurrentQuantity > 1) {
        hatCurrentQuantity--;
        document.getElementById('hatQuantityDisplay').textContent = hatCurrentQuantity;
    }
}

function addHatToCart() {
    const productName = document.getElementById('hatPopupTitle').textContent;
    const productPriceText = document.getElementById('hatPopupPrice').textContent;
    const productImage = document.getElementById('hatPopupImage').src;
    
    // Extract numeric price from text (e.g., "Rp79.900" -> 79900)
    const productPrice = parseInt(productPriceText.replace(/\D/g, ''));
    
    // Get or initialize cart from localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Add new item to cart
    cart.push({
        name: productName,
        price: productPrice,
        image: productImage,
        size: hatSelectedSize,
        quantity: hatCurrentQuantity
    });
    
    // Save back to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Show success notification
    showNotification(`${productName} added to cart!`);
    
    // Close popup
    closeHatPopup();
}

// Function to show notification
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
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