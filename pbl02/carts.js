// Format harga ke Rupiah
function formatRupiah(angka) {
  return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Memuat item keranjang dari localStorage
function loadCartItems() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const cartItemsContainer = document.getElementById('cartItems');
  
  // Kosongkan container terlebih dahulu
  cartItemsContainer.innerHTML = '';
  
  // Jika keranjang kosong
  if (cart.length === 0) {
    cartItemsContainer.innerHTML = `
      <div class="empty-cart">
        <p>Your cart is empty</p>
        <a href="products.html" class="btn">Continue Shopping</a>
      </div>
    `;
    document.getElementById('subtotalProduk').textContent = formatRupiah(0);
    document.getElementById('totalBayar').textContent = formatRupiah(10000);
    return;
  }
  
  // Hitung subtotal
  let subtotal = 0;
  
  // Tambahkan setiap item ke container
  cart.forEach((item, index) => {
    const itemTotal = item.harga * item.jumlah;
    subtotal += itemTotal;
    
    const itemElement = document.createElement('div');
    itemElement.className = 'cart-item';
    itemElement.innerHTML = `
      <img src="${item.gambar}" alt="${item.nama}" class="cart-item-img">
      <div class="cart-item-details">
        <h3>${item.nama}</h3>
        <p>Size: ${item.ukuran}</p>
        <p>Price: ${formatRupiah(item.harga)}</p>
        <div class="quantity-control">
          <button onclick="updateQuantity(${index}, -1)">-</button>
          <span>${item.jumlah}</span>
          <button onclick="updateQuantity(${index}, 1)">+</button>
        </div>
        <p>Subtotal: ${formatRupiah(itemTotal)}</p>
        <button class="remove-btn" onclick="removeItem(${index})">
          <i class="fas fa-trash"></i> Remove
        </button>
      </div>
    `;
    
    cartItemsContainer.appendChild(itemElement);
  });
  
  // Update summary
  const shippingCost = 10000;
  document.getElementById('subtotalProduk').textContent = formatRupiah(subtotal);
  document.getElementById('totalBayar').textContent = formatRupiah(subtotal + shippingCost);
}

// Update quantity item
function updateQuantity(index, change) {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  
  if (cart[index]) {
    cart[index].jumlah += change;
    
    // Pastikan jumlah minimal 1
    if (cart[index].jumlah < 1) cart[index].jumlah = 1;
    
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCartItems();
  }
}

// Hapus item dari keranjang
function removeItem(index) {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  
  if (index >= 0 && index < cart.length) {
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCartItems();
    
    // Tampilkan notifikasi
    showNotification('Item removed from cart');
  }
}

// Proses checkout
document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
  e.preventDefault();
  
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  if (cart.length === 0) {
    showNotification('Your cart is empty');
    return;
  }
  
  // Dapatkan data form
  const address = document.getElementById('address').value;
  const shippingMethod = document.querySelector('input[name="shipping"]:checked').value;
  
  // Simpan data order (biasanya ini dikirim ke server)
  const order = {
    items: cart,
    address: address,
    shipping: shippingMethod,
    date: new Date().toISOString(),
    total: document.getElementById('totalBayar').textContent
  };
  
  // Simpan ke localStorage (sementara)
  localStorage.setItem('lastOrder', JSON.stringify(order));
  localStorage.removeItem('cart');
  
  // Redirect ke halaman konfirmasi
  window.location.href = 'order-confirmation.html';
});

// Tampilkan notifikasi
function showNotification(message) {
  const notification = document.createElement('div');
  notification.className = 'notification';
  notification.textContent = message;
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.remove();
  }, 3000);
}

// Jalankan saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
  loadCartItems();
});