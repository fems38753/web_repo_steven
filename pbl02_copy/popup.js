function openPopup(productId) {
    fetch('php/popup.php?id=' + productId)
    .then(res => res.json())
    .then(data => {
      // Set gambar dan info dasar
      document.getElementById("popupImage").src = data.image;
      document.getElementById("popupTitle").textContent = data.name;
      document.getElementById("popupPrice").textContent = "Rp" + data.price.toLocaleString("id");

      // Tampilkan total stok awal
      document.getElementById("popupStockInfo").textContent = `Total stok tersedia: ${stock}`;

      // Generate tombol size
      const sizeContainer = document.getElementById("sizeButtons");
      sizeContainer.innerHTML = "";

      data.sizes.forEach(sizeObj => {
        const btn = document.createElement("button");
        btn.textContent = sizeObj.size;
        btn.classList.add("size-btn");
        btn.onclick = () => {
          document.querySelectorAll(".size-btn").forEach(b => b.classList.remove("selected"));
          btn.classList.add("selected");
          document.getElementById("popupStockInfo").textContent = `Total stok tersedia: ${data.stock}`;
        };
        sizeContainer.appendChild(btn);
      });

      quantity = 1;
      document.getElementById("quantityDisplay").textContent = quantity;
      document.getElementById("popupOverlay").style.display = "flex";
    })
    .catch(err => {
      alert("Gagal mengambil data produk.");
      console.error(err);
    });
}
