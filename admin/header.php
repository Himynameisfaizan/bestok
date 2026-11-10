<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<!-- Modern Admin Sidebar -->
<nav class="sidebar bg-dark text-white vh-100 position-fixed" style="width: 260px; z-index: 1000;">
    <div class="sidebar-header py-4 px-3 border-bottom border-secondary">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="assets/img/logo_icon.jpg" alt="Logo" class="rounded-circle me-2" width="40" height="40">
                <h5 class="mb-0 fw-bold text-primary">Admin Panel</h5><br>
            </div>
            <span class="d-block"><?php echo $_SESSION['admin_name'] ?? 'Admin'; ?></span>
            <button class="btn btn-link text-white d-lg-none sidebar-toggle" type="button">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Search Box -->
    <div class="search-box p-3 border-bottom border-secondary">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-dark border-secondary text-muted">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="sidebarSearch" class="form-control bg-dark border-secondary text-white" 
                   placeholder="Search menu..." style="caret-color: white;">
        </div>
    </div>

    <!-- Menu Items -->
    <div class="sidebar-menu py-3" style="height: calc(100vh - 170px); overflow-y: auto;">
        <ul class="nav flex-column" id="sidebarMenu">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="index.php" class="nav-link text-white py-3 px-4 d-flex align-items-center 
                   <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active bg-primary' : ''; ?>">
                    <i class="fas fa-tachometer-alt fa-fw me-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Home Content -->
            <li class="nav-item">
                <a class="nav-link text-white py-3 px-4 d-flex align-items-center" 
                   data-bs-toggle="collapse" href="#homeContent" role="button">
                    <i class="fas fa-home fa-fw me-3"></i>
                    <span class="flex-grow-1">Home Content</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array(basename($_SERVER['PHP_SELF']), ['home-items.php', 'add-banner.php']) ? 'show' : ''; ?>" 
                     id="homeContent">
                    <ul class="nav flex-column ps-5">
                        <li class="nav-item">
                            <a href="home-items.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'home-items.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-image fa-sm me-2"></i>
                                <span>Logo</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="add-banner.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'add-banner.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-images fa-sm me-2"></i>
                                <span>Banners</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- About Us -->
            <li class="nav-item">
                <a href="about_us.php" class="nav-link text-white py-3 px-4 d-flex align-items-center 
                   <?php echo basename($_SERVER['PHP_SELF']) == 'about_us.php' ? 'active bg-primary' : ''; ?>">
                    <i class="fas fa-info-circle fa-fw me-3"></i>
                    <span>About Us</span>
                </a>
            </li>

            <!-- Contact -->
            <li class="nav-item">
                <a class="nav-link text-white py-3 px-4 d-flex align-items-center" 
                   data-bs-toggle="collapse" href="#contactMenu" role="button">
                    <i class="fas fa-address-book fa-fw me-3"></i>
                    <span class="flex-grow-1">Contact</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array(basename($_SERVER['PHP_SELF']), ['add_contact.php', 'new-leads.php']) ? 'show' : ''; ?>" 
                     id="contactMenu">
                    <ul class="nav flex-column ps-5">
                        <li class="nav-item">
                            <a href="add_contact.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'add_contact.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-edit fa-sm me-2"></i>
                                <span>Edit Contact</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="new-leads.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'new-leads.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-inbox fa-sm me-2"></i>
                                <span>Inquiries</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item">
                <a href="career.php" class="nav-link text-white py-3 px-4 d-flex align-items-center 
                    <?php echo basename($_SERVER['PHP_SELF']) == 'career.php' ? 'active bg-primary' : ''; ?>">
                    <i class="fas fa-briefcase fa-fw me-3"></i>
                    <span>Career Entries</span>
                </a>
            </li>

            <!-- Categories -->
            <li class="nav-item">
                <a class="nav-link text-white py-3 px-4 d-flex align-items-center" 
                   data-bs-toggle="collapse" href="#categoryMenu" role="button">
                    <i class="fas fa-sitemap fa-fw me-3"></i>
                    <span class="flex-grow-1">Categories</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array(basename($_SERVER['PHP_SELF']), ['add-categories.php', 'view-categories.php', 'add-sub-category.php', 'view-sub-categories.php']) ? 'show' : ''; ?>" 
                     id="categoryMenu">
                    <ul class="nav flex-column ps-5">
                        <li class="nav-item">
                            <a href="add-categories.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'add-categories.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-plus-circle fa-sm me-2"></i>
                                <span>Add Category</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="view-categories.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'view-categories.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-list fa-sm me-2"></i>
                                <span>View Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="add-sub-category.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'add-sub-category.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-plus-square fa-sm me-2"></i>
                                <span>Add Sub Category</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="view-sub-categories.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'view-sub-categories.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-list-ol fa-sm me-2"></i>
                                <span>View Sub Categories</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Products -->
            <li class="nav-item">
                <a class="nav-link text-white py-3 px-4 d-flex align-items-center" 
                   data-bs-toggle="collapse" href="#productMenu" role="button">
                    <i class="fas fa-box fa-fw me-3"></i>
                    <span class="flex-grow-1">Products</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array(basename($_SERVER['PHP_SELF']), ['add-products.php', 'show-products.php']) ? 'show' : ''; ?>" 
                     id="productMenu">
                    <ul class="nav flex-column ps-5">
                        <li class="nav-item">
                            <a href="add-products.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'add-products.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-plus fa-sm me-2"></i>
                                <span>Add Products</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="show-products.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'show-products.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-eye fa-sm me-2"></i>
                                <span>Show Products</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Testimonials -->
            <li class="nav-item">
                <a href="testimonials.php" class="nav-link text-white py-3 px-4 d-flex align-items-center 
                   <?php echo basename($_SERVER['PHP_SELF']) == 'testimonials.php' ? 'active bg-primary' : ''; ?>">
                    <i class="fas fa-star fa-fw me-3"></i>
                    <span>Testimonials</span>
                </a>
            </li>
            
             <li class="nav-item">
    <a href="brands.php" class="nav-link text-white py-3 px-4 d-flex align-items-center 
       <?php echo basename($_SERVER['PHP_SELF']) == 'brands.php' ? 'active bg-primary' : ''; ?>">
        <i class="fas fa-handshake fa-fw me-3"></i>
        <span>Client Logos</span>
    </a>
</li>
            
             <li class="nav-item">
                <a href="video.php" class="nav-link text-white py-3 px-4 d-flex align-items-center 
                   <?php echo basename($_SERVER['PHP_SELF']) == 'video.php' ? 'active bg-primary' : ''; ?>">
                    <i class="fas fa-star fa-fw me-3"></i>
                    <span>Video</span>
                </a>
            </li>
            
             <li class="nav-item">
                <a href="blog.php" class="nav-link text-white py-3 px-4 d-flex align-items-center 
                   <?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active bg-primary' : ''; ?>">
                    <i class="fas fa-star fa-fw me-3"></i>
                    <span>Blog</span>
                </a>
            </li>
            
            

            <!-- Gallery -->
            <li class="nav-item">
                <a href="add-gallery.php" class="nav-link text-white py-3 px-4 d-flex align-items-center 
                   <?php echo basename($_SERVER['PHP_SELF']) == 'add-gallery.php' ? 'active bg-primary' : ''; ?>">
                    <i class="fas fa-photo-video fa-fw me-3"></i>
                    <span>Gallery</span>
                </a>
            </li>
            
             <li class="nav-item">
                <a href="certificate.php" class="nav-link text-white py-3 px-4 d-flex align-items-center 
                   <?php echo basename($_SERVER['PHP_SELF']) == 'certificate.php' ? 'active bg-primary' : ''; ?>">
                    <i class="fas fa-photo-video fa-fw me-3"></i>
                    <span>Certificate</span>
                </a>
            </li>
            
            

            <!-- Users Management -->
            <li class="nav-item">
                <a class="nav-link text-white py-3 px-4 d-flex align-items-center" 
                   data-bs-toggle="collapse" href="#userMenu" role="button">
                    <i class="fas fa-users-cog fa-fw me-3"></i>
                    <span class="flex-grow-1">User Management</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array(basename($_SERVER['PHP_SELF']), ['all-admin.php', 'admin-create.php', 'manage-profile.php']) ? 'show' : ''; ?>" 
                     id="userMenu">
                    <ul class="nav flex-column ps-5">
                        <li class="nav-item">
                            <a href="create-user.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'create-user.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-user-plus fa-sm me-2"></i>
                                <span>Create User</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="all-admin.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'all-admin.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-users fa-sm me-2"></i>
                                <span>Manage Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage-profile.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'manage-profile.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-user-edit fa-sm me-2"></i>
                                <span>Manage Profile</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Customers -->
            <li class="nav-item">
                <a href="all-customers.php" class="nav-link text-white py-3 px-4 d-flex align-items-center 
                   <?php echo basename($_SERVER['PHP_SELF']) == 'all-customers.php' ? 'active bg-primary' : ''; ?>">
                    <i class="fas fa-user-friends fa-fw me-3"></i>
                    <span>Customers</span>
                </a>
            </li>

            <!-- Orders & Invoice -->
            <li class="nav-item">
                <a class="nav-link text-white py-3 px-4 d-flex align-items-center" 
                   data-bs-toggle="collapse" href="#orderMenu" role="button">
                    <i class="fas fa-shopping-cart fa-fw me-3"></i>
                    <span class="flex-grow-1">Orders</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse <?php echo in_array(basename($_SERVER['PHP_SELF']), ['orders.php', 'invoice-generate.php']) ? 'show' : ''; ?>" 
                     id="orderMenu">
                    <ul class="nav flex-column ps-5">
                        <li class="nav-item">
                            <a href="orders.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-shopping-bag fa-sm me-2"></i>
                                <span>All Orders</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="invoice-generate.php" class="nav-link text-muted py-2 d-flex align-items-center 
                               <?php echo basename($_SERVER['PHP_SELF']) == 'invoice-generate.php' ? 'text-primary' : ''; ?>">
                                <i class="fas fa-file-invoice fa-sm me-2"></i>
                                <span>Generate Invoice</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Logout -->
            <li class="nav-item mt-auto border-top border-secondary">
                <a href="auth/logout.php" class="nav-link text-danger py-3 px-4 d-flex align-items-center">
                    <i class="fas fa-sign-out-alt fa-fw me-3"></i>
                    <span>Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</nav>


<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.querySelectorAll('.sidebar-toggle');
    const overlay = document.querySelector('.sidebar-overlay');
    
    sidebarToggle.forEach(btn => {
        btn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
        });
    });
    
    // Close sidebar on overlay click
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        overlay.style.display = 'none';
    });
    
    // Search functionality
    const sidebarSearch = document.getElementById('sidebarSearch');
    const menuItems = document.querySelectorAll('#sidebarMenu .nav-item');
    
    sidebarSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        menuItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            const isVisible = text.includes(searchTerm);
            
            if (isVisible) {
                item.style.display = '';
                // Expand parent collapses if child matches
                const parentCollapse = item.closest('.collapse');
                if (parentCollapse && !parentCollapse.classList.contains('show')) {
                    const collapseInstance = bootstrap.Collapse.getInstance(parentCollapse) || 
                                           new bootstrap.Collapse(parentCollapse);
                    collapseInstance.show();
                }
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Highlight active menu
    const currentPage = '<?php echo basename($_SERVER["PHP_SELF"]); ?>';
    const activeLinks = document.querySelectorAll(`a[href="${currentPage}"]`);
    
    activeLinks.forEach(link => {
        link.classList.add('active', 'bg-primary');
        // Expand parent collapses
        const parentCollapse = link.closest('.collapse');
        if (parentCollapse) {
            const collapseInstance = bootstrap.Collapse.getInstance(parentCollapse) || 
                                   new bootstrap.Collapse(parentCollapse);
            collapseInstance.show();
        }
    });
});

// Style for mobile
const style = document.createElement('style');
style.textContent = `
    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .sidebar.show {
            transform: translateX(0);
        }
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        main {
            margin-left: 0 !important;
        }
    }
    @media (min-width: 992px) {
        main {
            margin-left: 260px !important;
        }
    }
    .sidebar-menu::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar-menu::-webkit-scrollbar-track {
        background: #2d3748;
    }
    .sidebar-menu::-webkit-scrollbar-thumb {
        background: #4a5568;
        border-radius: 3px;
    }
    .nav-link.active {
        background-color: #6366f1 !important;
        border-radius: 8px !important;
        margin: 2px 8px !important;
    }
    .collapse .nav-link {
        padding-left: 0.5rem !important;
    }
`;
document.head.appendChild(style);
</script>