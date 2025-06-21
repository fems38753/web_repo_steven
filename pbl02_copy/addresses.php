<?php 
session_start();
include 'php/connect.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userData = [];
$orderStats = [
    'total' => 0,
    'pending' => 0,
    'completed' => 0
];

$orderQuery->bind_param("i", $userId);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();

while ($row = $orderResult->fetch_assoc()) {
    $orderStats['total'] += $row['count'];
    if ($row['status'] === 'pending') {
        $orderStats['pending'] = $row['count'];
    } elseif ($row['status'] === 'completed') {
        $orderStats['completed'] = $row['count'];
    }
}

if ($isLoggedIn) {
    // Fetch user data from database
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Account | Jackarmyofficial</title>
  <link rel="stylesheet" href="pbl02.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    /* Additional styles for account page */
    .account-container {
      max-width: 1200px;
      margin: 50px auto;
      padding: 20px;
      display: flex;
      gap: 30px;
    }
    
    .account-sidebar {
      width: 250px;
      background: #f8f8f8;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .account-content {
      flex: 1;
      background: #fff;
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .account-sidebar h3, .account-content h2 {
      color: #333;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }
    
    .account-sidebar ul {
      list-style: none;
      padding: 0;
    }
    
    .account-sidebar li {
      margin-bottom: 10px;
    }
    
    .account-sidebar a {
      display: block;
      padding: 8px 10px;
      color: #555;
      text-decoration: none;
      border-radius: 4px;
      transition: all 0.3s;
    }
    
    .account-sidebar a:hover, .account-sidebar a.active {
      background: #e9e9e9;
      color: #000;
    }
    
    .account-sidebar a.active {
      font-weight: bold;
    }
    
    .user-profile {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
    }
    
    .user-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #ddd;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 20px;
      overflow: hidden;
    }
    
    .user-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .user-info h3 {
      margin: 0 0 5px;
      color: #333;
    }
    
    .user-info p {
      margin: 0;
      color: #777;
    }
    
    .account-details {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }
    
    .detail-card {
      background: #f9f9f9;
      padding: 15px;
      border-radius: 6px;
      border-left: 4px solid #333;
    }
    
    .detail-card h4 {
      margin: 0 0 10px;
      color: #333;
    }
    
    .detail-card p {
      margin: 0;
      color: #666;
    }
    
    .order-history {
      margin-top: 30px;
    }
    
    .order-table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .order-table th, .order-table td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }
    
    .order-table th {
      background: #f5f5f5;
      font-weight: 600;
    }
    
    .order-status {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600;
    }
    
    .status-pending {
      background: #fff3cd;
      color: #856404;
    }
    
    .status-completed {
      background: #d4edda;
      color: #155724;
    }
    
    .status-shipped {
      background: #cce5ff;
      color: #004085;
    }
    
    .login-required {
      text-align: center;
      padding: 50px;
    }
    
    .login-required h2 {
      margin-bottom: 20px;
    }
    
    .account-actions {
      margin-top: 30px;
      display: flex;
      gap: 15px;
    }
    
    .btn {
      padding: 10px 20px;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s;
    }
    
    .btn-primary {
      background: #333;
      color: white;
    }
    
    .btn-primary:hover {
      background: #555;
    }
    
    .btn-secondary {
      background: #f0f0f0;
      color: #333;
    }
    
    .btn-secondary:hover {
      background: #e0e0e0;
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

  <main class="account-container">
    <?php if ($isLoggedIn): ?>
      <!-- User is logged in - show account dashboard -->
      <aside class="account-sidebar">
        <div class="user-profile">
          <div class="user-avatar">
            <?php if (!empty($userData['avatar'])): ?>
              <img src="<?php echo htmlspecialchars($userData['avatar']); ?>" alt="Profile Picture">
            <?php else: ?>
              <?php echo strtoupper(substr($userData['username'], 0, 1)); ?>
            <?php endif; ?>
          </div>
          <div class="user-info">
            <h3><?php echo htmlspecialchars($userData['username']); ?></h3>
            <p><?php echo htmlspecialchars($userData['email']); ?></p>
          </div>
        </div>
        
        <h3>My Account</h3>
        <ul>
          <li><a href="account.php">Dashboard</a></li>
          <li><a href="myorders.php">My Orders</a></li>
          <li><a href="addresses.php" class="active">Addresses</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </aside>
      
      <section class="account-content">
        <h2>Addres</h2>
        
      </section>

    <?php else: ?>
      <!-- User is not logged in - show login prompt -->
      <div class="login-required" style="width: 100%;">
        <h2>Please Login to View Your Account</h2>
        <p>You need to be logged in to access your account dashboard, order history, and settings.</p>
        <div class="account-actions">
          <button class="btn btn-primary" onclick="openPopup('login-popup')">Login</button>
          <button class="btn btn-secondary" onclick="openPopup('register-popup')">Register</button>
        </div>
      </div>
    <?php endif; ?>
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
                    <li><a href="products.php">All product</a></li>
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
                <input type="email" id="emailInput" placeholder="Insert your email" required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>

    <div class="footer-bottom">
        <p>Copyright &copy; 2025 <strong>JACKARMY</strong></p>
    </div>
  </footer>

  <script src="search.js"></script>
  <script src="cart.js"></script>
  <script src="news.js"></script>
  <script>
    // Popup functions
    function openPopup(popupId) {
      document.getElementById(popupId).style.display = 'block';
    }
    
    function closePopup(popupId) {
      document.getElementById(popupId).style.display = 'none';
    }
    
    function switchPopup(toPopupId) {
      // Hide all popups first
      document.querySelectorAll('.overlay').forEach(popup => {
        popup.style.display = 'none';
      });
      // Show the requested popup
      document.getElementById(toPopupId).style.display = 'block';
    }
    
    function togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const button = input.nextElementSibling;
      
      if (input.type === 'password') {
        input.type = 'text';
        button.textContent = 'Hide';
      } else {
        input.type = 'password';
        button.textContent = 'Show';
      }
    }
    
    // Handle form submissions
    document.getElementById('loginForm')?.addEventListener('submit', function(e) {
      e.preventDefault();
      // Add your login AJAX here
      console.log('Login form submitted');
    });
    
    document.getElementById('registerForm')?.addEventListener('submit', function(e) {
      e.preventDefault();
      // Add your registration AJAX here
      console.log('Register form submitted');
    });

  </script>
</body>
</html>