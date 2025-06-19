<?php
session_start();
require 'php/connect.php';

// Redirect to home if already logged in
if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Handle logout if requested
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: loginout.php'); // <- ganti ini ke halaman login sesungguhnya
    exit;
}

$error = '';
$success = '';
$redirect = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'index.php';

// Process login form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'user';

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        $table = ($role === 'admin') ? 'admins' : 'users';

        if ($role === 'admin') {
            $stmt = $conn->prepare("SELECT id, email, password FROM admins WHERE email = ?");
            $stmt->bind_param("s", $username);
        } else {
            $stmt = $conn->prepare("SELECT id, username, password, email FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $username);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['last_login'] = time();

                if ($role === 'admin') {
                    $stmt = $conn->prepare("SELECT id, email, password FROM admins WHERE email = ?");
                    $stmt->bind_param("s", $username);
                } else {
                    $stmt = $conn->prepare("SELECT id, username, password, email FROM users WHERE username = ? OR email = ?");
                    $stmt->bind_param("ss", $username, $username);
                }

                if (isset($_POST['remember'])) {
                    $token = bin2hex(random_bytes(32));
                    $expiry = time() + 60 * 60 * 24 * 30;

                    setcookie('remember_token', $token, $expiry, '/', '', true, true);
                    setcookie('remember_user', $user['id'], $expiry, '/', '', true, true);

                    $stmt = $conn->prepare("UPDATE $table SET remember_token = ?, token_expiry = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $token, date('Y-m-d H:i:s', $expiry), $user['id']);
                    $stmt->execute();
                }

                if ($role === 'admin') {
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = 'admin';
                    header("Location: php/admin/dashboard.php");
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = 'user';
                    header("Location: /prog_web/web_repo_steven/pbl02_copy/index.php");
                }
                exit;
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
    }
}


// Process registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if(empty($username) || empty($email) || empty($password)) {
        $error = "Please fill in all fields";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } elseif(strlen($password) < 8) {
        $error = "Password must be at least 8 characters long";
    } elseif($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $error = "Username or email already exists";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if($stmt->execute()) {
                $success = "Registration successful! You can now login.";
                // Optionally log the user in automatically
                // $_SESSION['user_id'] = $stmt->insert_id;
                // header("Location: $redirect");
                // exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login/Register - Jackarmyofficial</title>
  <link rel="stylesheet" href="pbl02.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Main container */
    .auth-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 2rem;
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      justify-content: center;
    }
    
    /* Form boxes */
    .auth-box {
      flex: 1;
      min-width: 350px;
      max-width: 450px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      padding: 2rem;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .auth-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .auth-box h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #333;
    }
    
    /* Form elements */
    .form-group {
      margin-bottom: 1.5rem;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: #555;
    }
    
    .form-group input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-family: 'Poppins', sans-serif;
      transition: border 0.3s;
    }
    
    .form-group input:focus {
      border-color: #333;
      outline: none;
    }
    
    .password-group {
      position: relative;
    }
    
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 35px;
      background: none;
      border: none;
      cursor: pointer;
      color: #666;
      font-size: 0.9rem;
    }
    
    /* Role selection */
    .role-selection {
      display: flex;
      margin-bottom: 1.5rem;
      border: 1px solid #ddd;
      border-radius: 6px;
      overflow: hidden;
    }
    
    .role-option {
      flex: 1;
      text-align: center;
      padding: 12px;
      cursor: pointer;
      background: #f5f5f5;
      transition: all 0.3s;
    }
    
    .role-option.active {
      background: #333;
      color: white;
    }
    
    .role-option input {
      display: none;
    }
    
    /* Remember me */
    .remember-me {
      display: flex;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    
    .remember-me input {
      margin-right: 8px;
    }
    
    /* Buttons */
    .auth-btn {
      width: 100%;
      padding: 14px;
      background-color: #333;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      font-size: 1rem;
      transition: background-color 0.3s;
    }
    
    .auth-btn:hover {
      background-color: #555;
    }
    
    /* Messages */
    .auth-message {
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    
    .error-message {
      background-color: #ffebee;
      color: #c62828;
      border: 1px solid #ef9a9a;
    }
    
    .success-message {
      background-color: #e8f5e9;
      color: #2e7d32;
      border: 1px solid #a5d6a7;
    }
    
    /* Links */
    .auth-link {
      text-align: center;
      margin-top: 1.5rem;
      color: #666;
    }
    
    .auth-link a {
      color: #333;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s;
    }
    
    .auth-link a:hover {
      color: #555;
      text-decoration: underline;
    }
    
    /* Admin note */
    .admin-note {
      font-size: 0.9rem;
      color: #666;
      text-align: center;
      margin-top: 1rem;
      font-style: italic;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .auth-container {
        flex-direction: column;
        padding: 1rem;
      }
      
      .auth-box {
        min-width: 100%;
      }
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

  <main class="auth-container">
    <!-- Login Box -->
    <div class="auth-box">
      <h2>Login to Your Account</h2>
      
      <?php if($error && (!isset($_POST['register']) || isset($_POST['login']))): ?>
        <div class="auth-message error-message"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      
      <?php if($success): ?>
        <div class="auth-message success-message"><?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>
      
      <form method="POST" id="loginForm">
        <input type="hidden" name="login" value="1">
        
        <div class="role-selection">
          <label class="role-option active" onclick="selectRole(this, 'user')">
            <input type="radio" name="role" value="user" checked> User
          </label>
          <label class="role-option" onclick="selectRole(this, 'admin')">
            <input type="radio" name="role" value="admin"> Admin
          </label>
        </div>
        
        <div class="form-group">
          <label for="login-username">Username or Email</label>
          <input type="text" id="login-username" name="username" placeholder="Enter your username or email" required>
        </div>
        
        <div class="form-group password-group">
          <label for="login-password">Password</label>
          <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
          <button type="button" class="toggle-password" onclick="togglePassword('login-password')">Show</button>
        </div>
        
        <div class="remember-me">
          <input type="checkbox" id="remember" name="remember">
          <label for="remember">Remember me</label>
        </div>
        
        <button type="submit" class="auth-btn">Login</button>
        
        <div class="auth-link">
          <a href="forgot-password.php">Forgot password?</a>
        </div>
      </form>
    </div>
    
    <!-- Register Box -->
    <div class="auth-box">
      <h2>Create New Account</h2>
      
      <?php if($error && isset($_POST['register'])): ?>
        <div class="auth-message error-message"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      
      <form method="POST" id="registerForm">
        <input type="hidden" name="register" value="1">
        
        <div class="form-group">
          <label for="register-username">Username</label>
          <input type="text" id="register-username" name="username" placeholder="Enter your username" required>
        </div>
        
        <div class="form-group">
          <label for="register-email">Email</label>
          <input type="email" id="register-email" name="email" placeholder="Enter your email" required>
        </div>
        
        <div class="form-group password-group">
          <label for="register-password">Password</label>
          <input type="password" id="register-password" name="password" placeholder="Enter your password (min 8 characters)" required>
          <button type="button" class="toggle-password" onclick="togglePassword('register-password')">Show</button>
        </div>
        
        <div class="form-group password-group">
          <label for="confirm-password">Confirm Password</label>
          <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
          <button type="button" class="toggle-password" onclick="togglePassword('confirm-password')">Show</button>
        </div>
        
        <button type="submit" class="auth-btn">Register</button>
        
        <div class="auth-link">
          Already have an account? <a href="#" onclick="switchForm('loginForm')">Login here</a>
        </div>
        
        <div class="admin-note">
          Note: Registration is for regular users only. Admin accounts must be created by system administrators.
        </div>
      </form>
    </div>
  </main>

  <footer>
    <!-- Your existing footer code -->
  </footer>

  <script>
    // Role selection function
    function selectRole(element, role) {
      // Remove active class from all options
      document.querySelectorAll('.role-option').forEach(opt => {
        opt.classList.remove('active');
      });
      
      // Add active class to selected option
      element.classList.add('active');
      
      // Update the radio button
      document.querySelector(`input[name="role"][value="${role}"]`).checked = true;
    }
    
    // Toggle password visibility
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
    
    // Switch between forms (for mobile view)
    function switchForm(formId) {
      const loginForm = document.getElementById('loginForm');
      const registerForm = document.getElementById('registerForm');
      
      if(formId === 'loginForm') {
        loginForm.closest('.auth-box').scrollIntoView({ behavior: 'smooth' });
      } else {
        registerForm.closest('.auth-box').scrollIntoView({ behavior: 'smooth' });
      }
    }
    
    // Form validation
    document.getElementById('registerForm')?.addEventListener('submit', function(e) {
      const password = document.getElementById('register-password').value;
      const confirmPassword = document.getElementById('confirm-password').value;
      
      if(password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
      }
      
      if(password.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters long!');
        return false;
      }
      
      return true;
    });
    
    // Check for URL parameters to show specific form
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      
      if(urlParams.has('register')) {
        switchForm('registerForm');
      }
    });
  </script>
</body>
</html>