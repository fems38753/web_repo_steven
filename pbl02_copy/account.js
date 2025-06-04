function closePopup(id) {
      document.getElementById(id).style.display = 'none';
    }

    function switchPopup(id) {
      document.getElementById('login-popup').style.display = 'none';
      document.getElementById('register-popup').style.display = 'none';
      document.getElementById(id).style.display = 'flex';
    }

    function togglePassword(fieldId) {
      const field = document.getElementById(fieldId);
      const toggleBtn = field.nextElementSibling;
      if (field.type === 'password') {
        field.type = 'text';
        toggleBtn.textContent = 'Hide';
      } else {
        field.type = 'password';
        toggleBtn.textContent = 'Show';
      }
    }

    // Tampilkan popup login saat halaman dimuat
    window.onload = function () {
      document.getElementById('login-popup').style.display = 'flex';
    };

    // Proses Register
    document.getElementById("registerForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const username = document.getElementById("register-username").value.trim();
      const email = document.getElementById("register-email").value.trim();
      const password = document.getElementById("register-password").value.trim();

      if (username && email && password) {
        const userData = {
          username: username,
          email: email,
          password: password
        };
        localStorage.setItem("jackarmyUser", JSON.stringify(userData));
        alert("Successfully registered!");
        closePopup('register-popup');
        document.getElementById("login-popup").style.display = "flex";
      } else {
        alert("Please fill in all fields.");
      }
    });

    // Proses Login
    document.getElementById("loginForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const emailOrUsername = document.getElementById("login-username").value.trim();
      const password = document.getElementById("login-password").value.trim();
      const storedUser = JSON.parse(localStorage.getItem("jackarmyUser"));

      if (!storedUser) {
        alert("You must register first!");
        closePopup("login-popup");
        document.getElementById("register-popup").style.display = "flex";
        return;
      }

      if (
        (emailOrUsername === storedUser.username || emailOrUsername === storedUser.email) &&
        password === storedUser.password
      ) {
        alert("Successfully logged in!");
        closePopup("login-popup");
      } else {
        alert("Invalid login credentials.");
      }
    });

    // Fungsi pencarian (jika ada elemen produk di halaman)
    function searchProducts() {
      let input = document.getElementById("searchInput").value.toLowerCase();
      let items = document.querySelectorAll(".kaos-item, .jaket-item, .topi-item");
      items.forEach(item => {
        let text = item.innerText.toLowerCase();
        item.style.display = text.includes(input) ? "block" : "none";
      });
    }