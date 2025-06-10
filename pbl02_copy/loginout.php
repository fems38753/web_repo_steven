<?php include 'php/connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jackarmyofficial</title>
  <link rel="stylesheet" href="pbl02.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    /* Additional styles for role selection */
    .role-selection {
      display: flex;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
      overflow: hidden;
    }
    
    .role-option {
      flex: 1;
      text-align: center;
      padding: 10px;
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
    
    .admin-only-note {
      color: #666;
      font-size: 14px;
      margin-top: 10px;
      text-align: center;
    }
  </style>
</head>
<body>
  <header>
    <nav class="navbar">
      <!-- Your existing navbar code remains the same -->
    </nav>
  </header>

  <!-- Login Popup -->
  <div class="overlay" id="login-popup">
    <div class="popup">
      <span class="close" onclick="closePopup('login-popup')">&times;</span>
      <h2>Login</h2>
      <form id="loginForm" action="php/login.php" method="POST">
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
        <div class="form-group">
          <button type="submit" class="button">Login</button>
        </div>
        <div class="form-footer">
          Don't have an account? <a href="#" onclick="switchPopup('register-popup')">Register here</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Register Popup -->
  <div class="overlay" id="register-popup" style="display: none;">
    <div class="popup">
      <span class="close" onclick="closePopup('register-popup')">&times;</span>
      <h2>Register</h2>
      <form id="registerForm" action="php/register.php" method="POST">
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
          <input type="password" id="register-password" name="password" placeholder="Enter your password" required>
          <button type="button" class="toggle-password" onclick="togglePassword('register-password')">Show</button>
        </div>
        <div class="form-group">
          <button type="submit" class="button">Register</button>
        </div>
        <div class="form-footer">
          Already have an account? <a href="#" onclick="switchPopup('login-popup')">Login here</a>
        </div>
        <div class="admin-only-note">
          Note: Registration is only available for regular users. Admin accounts must be created by system administrators.
        </div>
      </form>
    </div>
  </div>

  <!-- Your existing footer code remains the same -->

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
    
    // Handle form submissions with fetch API
    document.getElementById('loginForm')?.addEventListener('submit', async function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      
      try {
        const response = await fetch('php/login.php', {
          method: 'POST',
          body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
          window.location.href = result.redirect;
        } else {
          alert(result.message || 'Login failed');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('An error occurred during login');
      }
    });
    
    document.getElementById('registerForm')?.addEventListener('submit', async function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      
      try {
        const response = await fetch('php/register.php', {
          method: 'POST',
          body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
          alert('Registration successful! Please login.');
          switchPopup('login-popup');
        } else {
          alert(result.message || 'Registration failed');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('An error occurred during registration');
      }
    });
  </script>
</body>
</html>