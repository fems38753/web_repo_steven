<?php 
include 'php/connect.php';
session_start();
?>

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
   <style>
    /* Cart item styles */
    .cart-item {
        display: flex;
        padding: 20px;
        border-bottom: 1px solid #eee;
        gap: 20px;
    }

    .cart-item img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }

    .item-details {
        flex-grow: 1;
    }

    .item-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 10px;
    }

    .size-option {
        display: flex;
        flex-direction: column;
    }

    .size-buttons {
        display: flex;
        gap: 5px;
        margin-top: 5px;
    }

    .size-buttons button {
        padding: 5px 10px;
        border: 1px solid #ddd;
        background-color: #f1f1f1;
        cursor: pointer;
    }

    .size-buttons button.active {
        background-color: #333;
        color: white;
    }

    .quantity-option {
        display: flex;
        flex-direction: column;
    }

    .quantity-wrapper {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
    }

    .quantity-wrapper button {
        padding: 2px 8px;
        border: 1px solid #ddd;
        background-color: #f1f1f1;
        cursor: pointer;
    }

    .item-total {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        min-width: 150px;
    }

    .remove-btn {
        background-color: #ff4444;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        margin-top: 10px;
        border-radius: 3px;
    }

    .empty-cart {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    /* Summary styles */
    .cart-summary {
        margin-top: 20px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 5px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .summary-row.total {
        font-weight: bold;
        font-size: 1.1em;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }

    /* Hide radio buttons but keep functionality */
    .shipping-options input[type="radio"],
    .payment-options input[type="radio"] {
        display: none;
    }

    /* Style for selected shipping/payment options */
    .shipping-option img,
    .payment-option img {
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s;
    }

    .shipping-options input[type="radio"]:checked + img,
    .payment-options input[type="radio"]:checked + img {
        border-color: #333;
        transform: scale(1.05);
    }
    
    /* Login required message */
    .login-required {
        background-color: #fff8e1;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .login-required a {
        color: #ff6f00;
        font-weight: bold;
        text-decoration: none;
    }
    
    .login-required a:hover {
        text-decoration: underline;
    }
  </style>
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

<section class="cart-section">
  <div class="cart-container">
    <div class="cart-left">
      <h2>Your Shopping Cart</h2>
      <div id="cartItems" class="cart-items-container">
        <!-- Item keranjang akan muncul di sini melalui JavaScript -->
      </div>

      <div class="cart-summary">
        <div class="summary-row">
          <span>Total Products:</span>
          <span id="subtotalProduk">Rp 0</span>
        </div>
        <div class="summary-row">
          <span>Shipping Cost:</span>
          <span id="shippingCost">Rp 10.000</span>
        </div>
        <div class="summary-row total">
          <span>Total Payment:</span>
          <span id="totalBayar">Rp 10.000</span>
        </div>
      </div>
    </div>

    <div class="cart-right">
      <h2>Payment Info</h2>
      <?php if(!isset($_SESSION['user_id'])): ?>
        <div class="login-required">
          <p>You need to <a href="loginout.php">login</a> to complete your order.</p>
          <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
      <?php endif; ?>
      <form id="checkoutForm" <?php if(!isset($_SESSION['user_id'])) echo 'style="display:none;"'; ?>>
        <label>Shipping Address</label><br>
        <input type="text" id="shippingAddress" placeholder="Masukkan Alamat" required><br>
        <label>Shipping Choice</label>
        <div class="shipping-options">
          <label class="shipping-option">
            <input type="radio" name="shipping" value="JNE" checked>
            <img src="images/5.png" alt="JNE" title="JNE - Rp 10.000">
          </label>
          <label class="shipping-option">
            <input type="radio" name="shipping" value="J&T">
            <img src="images/6.png" alt="J&T" title="J&T - Rp 12.500">
          </label>
          <label class="shipping-option">
            <input type="radio" name="shipping" value="SiCepat">
            <img src="images/7.png" alt="SiCepat" title="SiCepat - Rp 15.000">
          </label>
        </div>
        <label>Payment Type</label>
        <div class="payment-options">
          <label class="payment-option">
            <input type="radio" name="payment" value="BCA" checked>
            <img src="images/8.png" alt="BCA">
          </label>
          <label class="payment-option">
            <input type="radio" name="payment" value="Mandiri">
            <img src="images/9.png" alt="Mandiri">
          </label>
          <label class="payment-option">
            <input type="radio" name="payment" value="Visa">
            <img src="images/12.png" alt="Visa">
          </label>
          <label class="payment-option">
            <input type="radio" name="payment" value="MasterCard">
            <img src="images/13.png" alt="MasterCard">
          </label>
          <label class="payment-option">
            <input type="radio" name="payment" value="Gopay">
            <img src="images/10.png" alt="Gopay">
          </label>
          <label class="payment-option">
            <input type="radio" name="payment" value="OVO">
            <img src="images/11.png" alt="OVO">
          </label>
        </div>
        <label>Notes</label><br>
        <textarea id="orderNotes" placeholder="Silakan tinggalkan pesan"></textarea>
        <button type="submit" class="checkout-btn">Make Order</button>
      </form>
    </div>
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
// Shipping cost mapping
const shippingCosts = {
    'JNE': 10000,
    'J&T': 12500,
    'SiCepat': 15000
};

// Load cart items when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
    updateCartSummary();
    
    // Handle shipping method change
    document.querySelectorAll('input[name="shipping"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateCartSummary();
        });
    });
    
    // Handle checkout form submission
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        checkout();
    });
});

function loadCartItems() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartContainer = document.getElementById('cartItems');
    
    if (cart.length === 0) {
        cartContainer.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
        return;
    }
    
    let html = '';
    cart.forEach((item, index) => {
        const formattedPrice = formatCurrency(item.price * item.quantity);
        const isHat = item.category === 'hat' || item.name.toLowerCase().includes('hat') || item.name.toLowerCase().includes('topi');
        
        html += `
            <div class="cart-item" data-index="${index}">
                <img src="${item.image}" alt="${item.name}">
                <div class="item-details">
                    <h3>${item.name}</h3>
                    <p class="item-price">${formatCurrency(item.price)}</p>
                    <div class="item-options">
                        <div class="quantity-option">
                            <label>Qty:</label>
                            <div class="quantity-wrapper">
                                <button onclick="updateItemQuantity(${index}, ${item.quantity - 1})">-</button>
                                <span>${item.quantity}</span>
                                <button onclick="updateItemQuantity(${index}, ${item.quantity + 1})">+</button>
                            </div>
                        </div>
                        ${isHat ? 
                            `<div class="size-option">
                                <label>Size:</label>
                                <div>All Size</div>
                            </div>` : 
                            `<div class="size-option">
                                <label>Size:</label>
                                <div class="size-buttons">
                                    <button class="${item.size === 'S' ? 'active' : ''}" onclick="updateItemSize(${index}, 'S')">S</button>
                                    <button class="${item.size === 'M' ? 'active' : ''}" onclick="updateItemSize(${index}, 'M')">M</button>
                                    <button class="${item.size === 'L' ? 'active' : ''}" onclick="updateItemSize(${index}, 'L')">L</button>
                                    <button class="${item.size === 'XL' ? 'active' : ''}" onclick="updateItemSize(${index}, 'XL')">XL</button>
                                </div>
                            </div>`
                        }
                    </div>
                </div>
                <div class="item-total">
                    <p>${formattedPrice}</p>
                    <button class="remove-btn" onclick="removeItem(${index})">Remove</button>
                </div>
            </div>
        `;
    });
    
    cartContainer.innerHTML = html;
}

function updateItemSize(index, newSize) {
    let cart = JSON.parse(localStorage.getItem('cart'));
    cart[index].size = newSize;
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCartItems();
    updateCartSummary();
}

function updateItemQuantity(index, newQuantity) {
    if (newQuantity < 1) {
        removeItem(index);
        return;
    }
    
    let cart = JSON.parse(localStorage.getItem('cart'));
    cart[index].quantity = newQuantity;
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCartItems();
    updateCartSummary();
}

function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('cart'));
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCartItems();
    updateCartSummary();
}

function updateCartSummary() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    // Get selected shipping method
    const selectedShipping = document.querySelector('input[name="shipping"]:checked').value;
    const shippingCost = shippingCosts[selectedShipping];
    const total = subtotal + shippingCost;
    
    document.getElementById('subtotalProduk').textContent = formatCurrency(subtotal);
    document.getElementById('shippingCost').textContent = formatCurrency(shippingCost);
    document.getElementById('totalBayar').textContent = formatCurrency(total);
}

function formatCurrency(amount) {
    return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function checkout() {
    // Check if user is logged in
    <?php if(!isset($_SESSION['user_id'])): ?>
        alert('You need to login first before placing an order.');
        window.location.href = 'loginout.php?redirect=cart.php';
        return;
    <?php endif; ?>

    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    const shippingAddress = document.getElementById('shippingAddress').value;
    if (!shippingAddress) {
        alert('Please enter your shipping address');
        return;
    }
    
    const shippingMethod = document.querySelector('input[name="shipping"]:checked').value;
    const paymentMethod = document.querySelector('input[name="payment"]:checked').value;
    const notes = document.getElementById('orderNotes').value;
    
    // Calculate totals
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const shippingCost = shippingCosts[shippingMethod];
    const total = subtotal + shippingCost;
    
    // Create order object
    const order = {
        date: new Date().toISOString(),
        items: cart,
        shipping: {
            address: shippingAddress,
            method: shippingMethod,
            cost: shippingCost
        },
        payment: {
            method: paymentMethod
        },
        notes: notes,
        subtotal: subtotal,
        total: total,
        status: 'pending',
        user_id: <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>
    };
    
    // Here you would typically send this to your server via AJAX
    // For now, we'll store it in localStorage
    let orders = JSON.parse(localStorage.getItem('orders')) || [];
    orders.push(order);
    localStorage.setItem('orders', JSON.stringify(orders));
    
    // Clear the cart
    localStorage.removeItem('cart');
    
    // Redirect to thank you page or show success message
    alert('Order placed successfully! Thank you for your purchase.');
    window.location.href = 'index.php';
}

function searchProducts() {
    const searchTerm = document.getElementById('searchInput').value.trim().toLowerCase();
    if (searchTerm) {
        window.location.href = `products.php?search=${encodeURIComponent(searchTerm)}`;
    } else {
        alert('Please enter a search term');
    }
}
</script>
</body>
</html>