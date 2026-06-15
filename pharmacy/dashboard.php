<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

// Get statistics from database
$productCount = $pdo->query(
    "SELECT COUNT(*) FROM products"
)->fetchColumn();

$lowStockCount = $pdo->query(
    "SELECT COUNT(*) FROM products WHERE quantity <= reorder_level"
)->fetchColumn();

$expiredCount = $pdo->query(
    "SELECT COUNT(*) FROM products WHERE expiry_date < CURDATE()"
)->fetchColumn();

// Get total sales for today
$totalSalesToday = $pdo->query(
    "SELECT COALESCE(SUM(total_amount), 0) FROM sales WHERE DATE(created_at) = CURDATE()"
)->fetchColumn();



$totalSales = $pdo->query(
"SELECT SUM(total_amount) FROM sales"
)->fetchColumn();

$totalProducts = $pdo->query(
"SELECT COUNT(*) FROM products"
)->fetchColumn();

$todaySales = $pdo->query(
"SELECT SUM(total_amount)
FROM sales
WHERE DATE(created_at)=CURDATE()"
)->fetchColumn();

$totalProfit = $pdo->query(
"
SELECT SUM((si.unit_price - p.cost_price) * si.quantity)
FROM sale_items si
JOIN products p ON p.id = si.product_id
"
)->fetchColumn();
?>

<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>

<!-- MAIN CONTENT AREA -->
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
       <!-- Dashboard Hero -->
<div class="dashboard-hero mb-4">
    <div>
        <h1>
            <i class="fas fa-chart-line me-2"></i>
            Dashboard
        </h1>

        <p>
            Welcome back,
            <strong><?= htmlspecialchars($_SESSION['fullname'] ?? 'User'); ?></strong>
        </p>
    </div>

    <div class="hero-badge">
        <i class="fas fa-calendar-day me-2"></i>
        <?= date('l, d M Y'); ?>
    </div>
</div>

        <!-- Stats Cards Row -->
        <div class="stats-container">
            <!-- Total Products Card -->
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h5>Total Products</h5>
                <h2><?= number_format($productCount) ?></h2>
                <p class="small text-muted mt-2">
                    <i class="fas fa-arrow-up"></i> All items in inventory
                </p>
            </div>

            <!-- Low Stock Card -->
            <div class="stat-card low-stock">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5>Low Stock Items</h5>
                <h2><?= number_format($lowStockCount) ?></h2>
                <p class="small text-muted mt-2">
                    <a href="modules/inventory/low-stock.php" class="text-decoration-none">View Details →</a>
                </p>
            </div>

            <!-- Expired Products Card -->
            <div class="stat-card expired">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h5>Expired Drugs</h5>
                <h2><?= number_format($expiredCount) ?></h2>
                <p class="small text-muted mt-2">
                    <i class="fas fa-alert"></i> Need removal
                </p>
            </div>

            <!-- Today's Sales Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h5>Today's Sales</h5>
                <h2><?= number_format($totalSalesToday, 2) ?></h2>
                <p class="small text-muted mt-2">
                    <i class="fas fa-clock"></i> Current day
                </p>
            </div>
</div>
            <!-- Financial Summary -->
<div class="row mt-4">
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Revenue</h6>
                <h3>₦<?= number_format($totalSales ?? 0, 2) ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Estimated Profit</h6>
                <h3>₦<?= number_format($totalProfit ?? 0, 2) ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Inventory Count</h6>
                <h3><?= number_format($totalProducts ?? 0) ?></h3>
            </div>
        </div>
    </div>

</div>
            
       

        <!-- Quick Action Buttons -->
        <div class="row mt-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Quick Actions</h5>
                        <div class="quick-actions">
                            <a href="modules/products/products.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-list me-2"></i>View Products
                            </a>
                            <a href="modules/inventory/low-stock.php" class="btn btn-warning btn-sm">
                                <i class="fas fa-exclamation-circle me-2"></i>Low Stock Items
                            </a>
                            <a href="modules/sales/pos.php" class="btn btn-success btn-sm">
                                <i class="fas fa-cash-register me-2"></i>Open POS
                            </a>
                            <a href="modules/sales/sales_history.php" class="btn btn-success btn-sm">
                                <i class="fas fa-cash-register me-2"></i>Sales History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Spacer -->
        <div style="height: 50px;"></div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
