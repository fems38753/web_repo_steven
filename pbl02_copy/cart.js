
// Variabel global untuk menyimpan data produk yang sedang dilihat
let currentProduct = {
  gambar: '',
  nama: '',
  harga: 0,
  ukuran: '',
  jumlah: 1
};

// Fungsi untuk membuka popup
function openPopup(imageSrc, productName, productPrice) {
  // Reset quantity setiap kali popup dibuka
  currentProduct.jumlah = 1;
  document.getElementById('quantityDisplay').textContent = '1';
  
  // Set data produk saat ini
  currentProduct.gambar = imageSrc;
  currentProduct.nama = productName;
  
  // Konversi harga dari string "RpXX.XXX" ke number
  const priceNumber = parseInt(productPrice.replace(/[^\d]/g, ''));
  currentProduct.harga = priceNumber;
  
  // Update tampilan popup
  document.getElementById('popupImage').src = imageSrc;
  document.getElementById('popupTitle').textContent = productName;
  document.getElementById('popupPrice').textContent = productPrice;
  
  // Tampilkan popup
  document.getElementById('popupOverlay').style.display = 'flex';
}

// Fungsi untuk menutup popup
function closePopup() {
  document.getElementById('popupOverlay').style.display = 'none';
}

// Fungsi untuk memilih ukuran
function selectSize(size) {
  currentProduct.ukuran = size;
  
  // Update tampilan button ukuran yang aktif
  const sizeButtons = document.querySelectorAll('.size-buttons button');
  sizeButtons.forEach(button => {
    button.classList.remove('active');
    if (button.textContent === size) {
      button.classList.add('active');
    }
  });
}

// Fungsi untuk menambah jumlah
function increaseQuantity() {
  currentProduct.jumlah++;
  document.getElementById('quantityDisplay').textContent = currentProduct.jumlah;
}

// Fungsi untuk mengurangi jumlah
function decreaseQuantity() {
  if (currentProduct.jumlah > 1) {
    currentProduct.jumlah--;
    document.getElementById('quantityDisplay').textContent = currentProduct.jumlah;
  }
}

// Fungsi untuk menambahkan ke keranjang
function addToCart() {
  // Validasi ukuran telah dipilih
  if (!currentProduct.ukuran) {
    alert('Silakan pilih ukuran terlebih dahulu');
    return;
  }
  
  // Ambil data keranjang dari localStorage atau buat array kosong jika belum ada
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  
  // Tambahkan produk saat ini ke keranjang
  cart.push({
    gambar: currentProduct.gambar,
    nama: currentProduct.nama,
    harga: currentProduct.harga,
    ukuran: currentProduct.ukuran,
    jumlah: currentProduct.jumlah
  });
  
  // Simpan kembali ke localStorage
  localStorage.setItem('cart', JSON.stringify(cart));
  
  // Tutup popup
  closePopup();
  
  // Beri feedback ke user
  alert('Produk berhasil ditambahkan ke keranjang!');
}

// Fungsi untuk mencari produk
function searchProducts() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase();
  if (searchTerm.trim() === '') {
    alert('Silakan masukkan kata kunci pencarian');
    return;
  }
  // Redirect ke halaman products dengan parameter search
  window.location.href = `products.html?search=${encodeURIComponent(searchTerm)}`;
}

