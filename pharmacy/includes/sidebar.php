<!-- SIDEBAR NAVIGATION -->
<div class="sidebar" id="sidebar">
    <h4 class="px-4 mt-3 mb-3">
        <i class="fas fa-pills me-2"></i>Pharmacy
    </h4>
    <hr>

    <ul class="nav flex-column">

    <!-- Dashboard -->
    <li class="nav-item">
        <a href="/pharmacy/dashboard.php" class="nav-link">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Inventory Dropdown -->
    <li class="nav-item">

        <a class="nav-link d-flex justify-content-between align-items-center"
           data-bs-toggle="collapse"
           href="#inventoryMenu"
           role="button">

            <div>
                <i class="fas fa-warehouse"></i>
                <span>Inventory</span>
            </div>

            <i class="fas fa-chevron-down small"></i>
        </a>

        <div class="collapse" id="inventoryMenu">

            <ul class="nav flex-column ms-3">

                <li class="nav-item">
                    <a href="/pharmacy/modules/inventory/inventory.php" class="nav-link">
                        <i class="fas fa-boxes"></i>
                        Inventory
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/pharmacy/modules/inventory/low-stock.php" class="nav-link">
                        <i class="fas fa-exclamation-triangle"></i>
                        Low Stock
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/pharmacy/modules/inventory/purchase-orders.php" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        Purchase Orders
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/pharmacy/modules/inventory/expiry-tracking.php" class="nav-link">
                        <i class="fas fa-calendar-times"></i>
                        Expiry Tracking
                    </a>
                </li>
                 <li class="nav-item">
                    <a href="/pharmacy/modules/inventory/near-expiry.php" class="nav-link">
                        <i class="fas fa-calendar-times"></i>
                        Near Expiry
                    </a>
                </li>


                <li class="nav-item">
                    <a href="/pharmacy/modules/inventory/inventory-report.php" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        Inventory Report
                    </a>
                </li>

            </ul>

        </div>
    </li>

    <!-- Settings Dropdown -->
    <li class="nav-item">

        <a class="nav-link d-flex justify-content-between align-items-center"
           data-bs-toggle="collapse"
           href="#settingsMenu"
           role="button">

            <div>
                <i class="fas fa-cogs"></i>
                <span>Settings</span>
            </div>

            <i class="fas fa-chevron-down small"></i>
        </a>

        <div class="collapse" id="settingsMenu">

            <ul class="nav flex-column ms-3">

                <li class="nav-item">
                    <a href="/pharmacy/modules/settings/add-unit.php" class="nav-link">
                        <i class="fas fa-ruler"></i>
                        Manage Units
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/pharmacy/modules/settings/add-manufacturer.php" class="nav-link">
                        <i class="fas fa-industry"></i>
                        Manage Manufacturer
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/pharmacy/modules/settings/add-supplier.php" class="nav-link">
                        <i class="fas fa-truck"></i>
                        Manage Supplier
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/pharmacy/modules/settings/add-brand.php" class="nav-link">
                        <i class="fas fa-tags"></i>
                        Manage Brand
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/pharmacy/modules/settings/add-category.php" class="nav-link">
                        <i class="fas fa-layer-group"></i>
                        Manage Category
                    </a>
                </li>

            </ul>

        </div>
    </li>

    <!-- Products -->
    <li class="nav-item">
        <a href="/pharmacy/modules/products/products.php" class="nav-link">
            <i class="fas fa-box"></i>
            <span>Products</span>
        </a>
    </li>

    <!-- POS -->
    <li class="nav-item">
        <a href="/pharmacy/modules/sales/pos.php" class="nav-link">
            <i class="fas fa-cash-register"></i>
            <span>POS (Sales)</span>
        </a>
    </li>
     <li class="nav-item">
        <a href="/pharmacy/modules/sales/sales-reports.php" class="nav-link">
            <i class="fas fa-cash-register"></i>
            <span>Sales Report</span>
        </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
        <a href="/pharmacy/logout.php" class="nav-link logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

</ul>
</div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = event.target.closest('button');
            if (!event.target.closest('.sidebar') && toggleBtn) {
                if (!toggleBtn.querySelector('.fa-bars')) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
