<?php
session_start();
include 'php/connect.php';

$user_id = $_SESSION['user_id'] ?? null;
$userData = null;
if ($user_id) {
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
    $userData = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment'])) {
    $shippingMethod = $_POST['shipping'];
    $shippingAddress = $_POST['shippingAddress'];
    $payment = strtolower($_POST['payment']);
    $notes = $_POST['notes'] ?? '';

    $cart = [];
    $cartQuery = $conn->prepare("SELECT c.product_id, c.size, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $cartQuery->bind_param("i", $user_id);
    $cartQuery->execute();
    $cartResult = $cartQuery->get_result();
    while ($item = $cartResult->fetch_assoc()) {
        $cart[] = $item;
    }

    if (empty($cart)) {
        echo "<script>alert('Keranjang kosong'); window.location.href='cart.php';</script>";
        exit;
    }

    $shippingCost = ["JNE" => 10000, "J&T" => 12500, "SiCepat" => 15000][$shippingMethod] ?? 10000;
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    $total += $shippingCost;

    mysqli_query($conn, "INSERT INTO orders (user_id, address, shipping_method, payment_method, notes, total_price) VALUES
        ($user_id, '$shippingAddress', '$shippingMethod', '$payment', '$notes', $total)");
    $order_id = mysqli_insert_id($conn);

    foreach ($cart as $item) {
        $product_id = $item['product_id'];
        $qty = $item['quantity'];
        $size = $item['size'];
        $price = $item['price'];
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, size, price)
            VALUES ($order_id, $product_id, $qty, '$size', $price)");

        $res = mysqli_query($conn, "SELECT size_available FROM products WHERE id = $product_id");
        $product = mysqli_fetch_assoc($res);
        $sizes = [];
        foreach (explode(',', $product['size_available']) as $pair) {
            list($sz, $val) = explode(':', $pair);
            $sizes[$sz] = (int)$val;
        }
        if (isset($sizes[$size])) {
            $sizes[$size] = max(0, $sizes[$size] - $qty);
        }
        $updatedSizeStr = implode(',', array_map(function($sz) use ($sizes) {
            return "$sz:{$sizes[$sz]}";
        }, array_keys($sizes)));
        mysqli_query($conn, "UPDATE products SET size_available='$updatedSizeStr' WHERE id=$product_id");
    }

    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    $redirects = [
        "bca" => "payment_bca.php",
        "mandiri" => "payment_mandiri.php",
        "visa" => "payment_visa.php",
        "mastercard" => "payment_mastercard.php",
        "gopay" => "payment_gopay.php",
        "ovo" => "payment_ovo.php",
    ];

    $target = $redirects[$payment] ?? 'index.php';
    header("Location: $target?order_id=$order_id");
    exit;
}
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

<section class="cart-section">
  <div class="cart-container">
    <div class="cart-left">
      
      <h2 style="color: #d7235b; font-size: 28px; margin-bottom: 20px;">Your Shopping Cart</h2>

      <?php
        $total = 0;
        $shippingCost = 10000;

        if ($user_id) {
          $stmt = $conn->prepare("SELECT c.product_id, c.size, c.quantity, p.name, p.price, p.image 
                                  FROM cart c 
                                  JOIN products p ON c.product_id = p.id 
                                  WHERE c.user_id = ?");
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0):
            echo '<form method="POST" action="php/update_cart.php" onsubmit="return confirm(\'Hapus semua item dari cart?\')" style="margin-bottom:15px;">';
            echo '<input type="hidden" name="action" value="clear_all">';
            echo '<button type="submit" class="remove-btn">Hapus Semua</button>';
            echo '</form>';

            echo '<div id="cartItems" class="cart-items-container">';

            while ($item = $result->fetch_assoc()):
              $subtotal = $item['price'] * $item['quantity'];
              $total += $subtotal;
      ?>
              <div class="cart-item">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-img">
                <div class="item-details">
                  <h3><?= htmlspecialchars($item['name']) ?></h3>
                  <p>Size: <?= htmlspecialchars($item['size']) ?></p>
                  <p>Price: Rp<?= number_format($item['price'], 0, ',', '.') ?></p>

                  <div class="item-actions">
                    <form method="POST" action="php/update_cart.php" style="display:inline;">
                      <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                      <input type="hidden" name="size" value="<?= $item['size'] ?>">
                      <input type="hidden" name="action" value="decrease">
                      <button type="submit">-</button>
                    </form>

                    <span style="margin: 0 8px; font-weight: bold;"><?= $item['quantity'] ?></span>

                    <form method="POST" action="php/update_cart.php" style="display:inline;">
                      <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                      <input type="hidden" name="size" value="<?= $item['size'] ?>">
                      <input type="hidden" name="action" value="increase">
                      <button type="submit">+</button>
                    </form>

                    <form method="POST" action="php/update_cart.php" style="display:inline;">
                      <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                      <input type="hidden" name="size" value="<?= $item['size'] ?>">
                      <input type="hidden" name="action" value="delete">
                      <button type="submit" class="remove-btn" >Remove</button>
                    </form>
                  </div>

                  <p><strong>Subtotal: Rp<?= number_format($subtotal, 0, ',', '.') ?></strong></p>
                </div>
              </div>
      <?php
            endwhile;
            echo '</div>'; 

          else:
            echo "<p class='empty-cart'>Your cart is empty.</p>";
          endif;
        } else {
          echo "<p class='empty-cart'>You must <a href='loginout.php'>log in</a> to view your cart.</p>";
        }
      ?>

      <div class="summary-row">
        <span>Total Products:</span>
        <span>Rp<?= number_format($total, 0, ',', '.') ?></span>
      </div>
      <div class="summary-row">
        <span>Shipping Cost:</span>
        <span>Rp<?= number_format($shippingCost, 0, ',', '.') ?></span>
      </div>
      <div class="summary-row total">
        <span>Total Payment:</span>
        <span>Rp<?= number_format($total + $shippingCost, 0, ',', '.') ?></span>
      </div>
    </div>


    <div class="cart-right">
  <h2 class="section-title">Payment Info</h2>

  <?php if (!isset($_SESSION['user_id'])): ?>
    <div class="login-required">
      <p>You need to <a href="loginout.php">login</a> to complete your order.</p>
      <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
  <?php endif; ?>

  <form id="checkoutForm" method="POST" action="cart.php" <?php if (!isset($_SESSION['user_id'])) echo 'style="display:none;"'; ?>>
    
    <div class="form-group">
      <label for="shippingAddress">Shipping Address</label>
      <input type="text" id="shippingAddress" name="shippingAddress" placeholder="Masukkan Alamat"
             value="<?= $userData ? htmlspecialchars($userData['alamat']) : '' ?>" required>
    </div>

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
        <textarea id="orderNotes" name="notes" placeholder="Silakan tinggalkan pesan"></textarea>
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
const shippingRadios = document.querySelectorAll('input[name="shipping"]');
const shippingCosts = {
    'JNE': 10000,
    'J&T': 12500,
    'SiCepat': 15000
};

const shippingCostElem = document.querySelector('.summary-row:nth-child(2) span:last-child');
const totalPaymentElem = document.querySelector('.summary-row.total span:last-child');

let totalProduk = <?= $total ?>;

function formatCurrency(amount) {
    return 'Rp' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

shippingRadios.forEach(radio => {
    radio.addEventListener('change', () => {
        const selectedShipping = radio.value;
        const newShippingCost = shippingCosts[selectedShipping];
        const newTotal = totalProduk + newShippingCost;

        if (shippingCostElem) {
            shippingCostElem.textContent = formatCurrency(newShippingCost);
        }

        if (totalPaymentElem) {
            totalPaymentElem.textContent = formatCurrency(newTotal);
        }
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
</script>
</body>
</html>