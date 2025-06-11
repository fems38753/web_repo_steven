console.log("✅ account.js berhasil dimuat!");

document.addEventListener("DOMContentLoaded", function () {

  // Fungsi ganti popup antara login ↔ register
  window.switchPopup = function (popupIdToShow) {
    const popups = document.querySelectorAll(".overlay");
    popups.forEach(popup => popup.style.display = "none");

    const target = document.getElementById(popupIdToShow);
    if (target) {
      target.style.display = "block";
    }
  };

  // Fungsi register
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const username = document.getElementById("register-username").value.trim();
      const email = document.getElementById("register-email").value.trim();
      const password = document.getElementById("register-password").value.trim();

      fetch("php/register.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
      })
        .then(res => res.json())
        .then(data => {
          alert(data.message);
          if (data.status === "success") {
            switchPopup("login-popup");
            registerForm.reset();
          }
        })
        .catch(err => {
          alert("Gagal koneksi ke server: " + err);
        });
    });
  }

  // Fungsi login
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const email = document.getElementById("login-username").value.trim();
      const password = document.getElementById("login-password").value.trim();

      fetch("php/login.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
      })
        .then(res => res.json())
        .then(data => {
          alert(data.message);
          if (data.status === "success") {
            const urlParams = new URLSearchParams(window.location.search);
            const redirect = urlParams.get("redirect");
            if (redirect === "checkout") {
              window.location.href = "checkout.php";
            } else {
              window.location.href = "php/dashboard.php";
            }
          }
        })
        .catch(err => {
          alert("Gagal login: " + err);
        });
    });
  }

});