<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'php/connect.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (empty($query)) {
    echo "Masukkan kata kunci pencarian.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%', ?, '%') OR category LIKE CONCAT('%', ?, '%')");
$stmt->bind_param("ss", $query, $query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hasil Pencarian - JackArmy</title>
  <link rel="stylesheet" href="pbl02.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(0,0,0,0.7);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
    .popup-content {
      background: #fff;
      padding: 20px;
      width: 90%;
      max-width: 400px;
      border-radius: 10px;
      text-align: center;
      position: relative;
    }
    .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 20px;
      cursor: pointer;
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

<section class="search-results" style="padding: 40px;">
  <h2>Hasil Pencarian untuk: <em><?= htmlspecialchars($query) ?></em></h2>
  <?php if ($result->num_rows > 0): ?>
    <div class="kaos-container">
      <?php while ($p = $result->fetch_assoc()):
        $hargaAwal = $p['price'] * 2;
        $discountPercent = round(($hargaAwal - $p['price']) / $hargaAwal * 100);
        $onclick = (strtolower($p['category']) === 'hat')
            ? "openHatPopup('{$p['image']}', '{$p['name']}', {$p['price']}, {$p['id']}, {$p['stock']})"
            : "openPopup('{$p['image']}', '{$p['name']}', {$p['price']}, {$p['id']}, '{$p['size_available']}')";
      ?>
        <div class="kaos-item" onclick="<?= $onclick ?>">
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
  <?php else: ?>
    <p>Tidak ada produk ditemukan untuk <strong><?= htmlspecialchars($query) ?></strong>.</p>
  <?php endif; ?>
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

<!-- Popup Product Detail for Hat -->
<div class="popup-overlay" id="hatPopupOverlay" style="display: none;">
  <div class="popup-content">
    <span class="close-btn" onclick="closeHatPopup()">&times;</span>
    <img id="hatPopupImage" src="" alt="Hat Produk">
    <h3 id="hatPopupTitle">Product Name</h3>
    <p class="popupPrice" id="hatPopupPrice">Rp0</p>
    <p id="hatPopupStockInfo" style="color: #666;">Stok: 0</p>

    <form action="add_to_cart.php" method="POST">
      <input type="hidden" name="product_id" id="formHatProductId">
      <input type="hidden" name="size" value="All Size">
      <input type="hidden" name="quantity" id="formHatQty" value="1">

      <div class="popupSize">
        <label>Size:</label>
        <div class="size-buttons">
          <button type="button" class="size-selected">All Size</button>
        </div>
      </div>

      <div class="popupQuantity">
        <label>Quantity:</label>
        <div class="quantity-wrapper">
          <button type="button" onclick="decreaseHatQuantity()">-</button>
          <span id="hatQuantityDisplay">1</span>
          <button type="button" onclick="increaseHatQuantity()">+</button>
        </div>
      </div><br>

      <button type="submit" class="addToCartBtn">Add To Cart</button>
    </form>
  </div>
</div>


<script>
function searchProducts() {
  const searchTerm = document.getElementById('searchInput').value.trim();
  if (searchTerm) {
    window.location.href = `search.php?query=${encodeURIComponent(searchTerm)}`;
  } else {
    alert('Masukkan kata kunci');
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        e.preventDefault();
        searchProducts();
      }
    });
  }
});


let qty = 1;
let size = '';
let stockInfo = {};

// === T-SHIRT / JACKET ===
function openPopup(img, title, price, id, size_available) {
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

  ['S', 'M', 'L', 'XL'].forEach(updateSizeButton);
}

function updateSizeButton(sz) {
  const btn = document.getElementById('size' + sz);
  btn.innerText = sz + ' (' + (stockInfo[sz] ?? 0) + ')';
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

  ['S', 'M', 'L', 'XL'].forEach(s => {
    document.getElementById('size' + s).classList.remove('size-selected');
  });
  document.getElementById('size' + sz).classList.add('size-selected');
}

function addToCart() {
  const productId = document.getElementById('popupProductId').value;
  const productName = document.getElementById('popupTitle').innerText;
  const priceText = document.getElementById('popupPrice').innerText;

  if (!size) {
    alert('Silakan pilih ukuran terlebih dahulu.');
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
      alert(`${productName} berhasil ditambahkan ke keranjang!`);
      closePopup();
    } else if (response === 'OUT_OF_STOCK') {
      alert('Stok tidak mencukupi.');
    } else if (response === 'NOT_LOGGED_IN') {
      alert('Anda belum login.');
      window.location.href = 'loginout.php';
    } else {
      alert('Terjadi kesalahan saat menambahkan ke keranjang.');
      console.log(response);
    }
  });
}

// === HAT ===
let hatQty = 1;
let hatStock = 0;

function openHatPopup(img, title, price, id, stock) {
  document.getElementById('hatPopupOverlay').style.display = 'flex';
  document.getElementById('hatPopupImage').src = img;
  document.getElementById('hatPopupTitle').textContent = title;
  document.getElementById('hatPopupPrice').textContent = 'Rp' + price.toLocaleString('id-ID');
  document.getElementById('formHatProductId').value = id;
  document.getElementById('formHatQty').value = 1;
  document.getElementById('hatQuantityDisplay').textContent = '1';
  document.getElementById('hatPopupStockInfo').textContent = `Stok: ${stock}`;

  hatQty = 1;
  hatStock = stock;
}

function increaseHatQuantity() {
  if (hatQty < hatStock) {
    hatQty++;
    document.getElementById('hatQuantityDisplay').innerText = hatQty;
    document.getElementById('formHatQty').value = hatQty;
  }
}

function decreaseHatQuantity() {
  if (hatQty > 1) {
    hatQty--;
    document.getElementById('hatQuantityDisplay').innerText = hatQty;
    document.getElementById('formHatQty').value = hatQty;
  }
}

function closeHatPopup() {
  document.getElementById('hatPopupOverlay').style.display = 'none';
}

function addHatToCart() {
  const productId = document.getElementById('formHatProductId').value;
  const qty = document.getElementById('formHatQty').value;
  const productName = document.getElementById('hatPopupTitle').innerText;

  fetch('add_to_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `product_id=${productId}&quantity=${qty}&size=All Size`
  })
  .then(res => res.text())
  .then(response => {
    if (response === 'SUCCESS') {
      alert(`${productName} berhasil ditambahkan ke keranjang!`);
      closeHatPopup();
    } else if (response === 'OUT_OF_STOCK') {
      alert('Stok habis.');
    } else if (response === 'NOT_LOGGED_IN') {
      alert('Anda belum login.');
      window.location.href = 'loginout.php';
    } else {
      alert('Gagal menambahkan produk ke keranjang.');
      console.log(response);
    }
  });
}
</script>

</body>
</html>