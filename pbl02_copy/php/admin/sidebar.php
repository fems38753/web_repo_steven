<?php
// sidebar.php
$sidebarMinimized = isset($_COOKIE['sidebar_minimized']) && $_COOKIE['sidebar_minimized'] === 'true';
?>
<aside class="sidebar <?= $sidebarMinimized ? 'minimized' : '' ?>">
    <div class="sidebar-header">
        <h3>Admin Panel</h3>
        <button class="toggle-sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <li><a href="products.php"><i class="fas fa-box-open"></i> <span>Kelola Produk</span></a></li>
        <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> <span>Tambah Produk</span></a></li>
        <li><a href="categories.php"><i class="fas fa-tags"></i> <span>Kategori</span></a></li>
        <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a></li>
        <li><a href="insert_admin.php"><i class="fas fa-users-cog"></i> <span>Admin</span></a></li>
        <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
    </ul>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleSidebar = document.querySelector('.toggle-sidebar');
    const sidebar = document.querySelector('.sidebar');
    
    // Check for saved preference
    const isMinimized = localStorage.getItem('sidebarMinimized') === 'true';
    if (isMinimized) {
        sidebar.classList.add('minimized');
    }
    
    toggleSidebar.addEventListener('click', function() {
        sidebar.classList.toggle('minimized');
        
        // Save preference
        const isNowMinimized = sidebar.classList.contains('minimized');
        localStorage.setItem('sidebarMinimized', isNowMinimized);
        document.cookie = `sidebar_minimized=${isNowMinimized}; path=/`;
    });
});
</script>