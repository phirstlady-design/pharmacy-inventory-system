<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// Get all products with valuation
$products = $pdo->query("
    SELECT * FROM products 
    WHERE quantity > 0 
    ORDER BY product_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Calculate total valuation
$totalValuation = 0;
$valuationByCategory = [];

foreach ($products as $product) {
    $value = $product['quantity'] * $product['price'];
    $totalValuation += $value;
    
    $category = $product['category'];
    if (!isset($valuationByCategory[$category])) {
        $valuationByCategory[$category] = ['value' => 0, 'units' => 0, 'items' => 0];
    }
    $valuationByCategory[$category]['value'] += $value;
    $valuationByCategory[$category]['units'] += $product['quantity'];
    $valuationByCategory[$category]['items']++;
}

// Sort by value
arsort($valuationByCategory);
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark">
                <i class="fas fa-vault me-2 text-primary"></i>Inventory Valuation
            </h1>
            <p class="text-muted">Total inventory asset value and category breakdown</p>
        </div>

        <!-- Key Metrics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Inventory Value</h5>
                        <h1 class="text-success">₦<?= number_format($totalValuation, 0) ?></h1>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Units in Stock</h5>
                        <h2 class="text-info"><?= number_format(array_sum(array_column($products, 'quantity'))) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Product Types</h5>
                        <h2 class="text-primary"><?= count($products) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valuation by Category -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Valuation by Category</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($valuationByCategory)): ?>
                            <p class="text-muted">No products in inventory</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Category</th>
                                            <th>Items</th>
                                            <th>Units</th>
                                            <th>Total Value</th>
                                            <th>% of Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($valuationByCategory as $category => $data): 
                                            $percentage = ($data['value'] / $totalValuation) * 100;
                                        ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($category) ?></strong></td>
                                                <td>
                                                    <span class="badge bg-info"><?= $data['items'] ?></span>
                                                </td>
                                                <td><?= number_format($data['units']) ?></td>
                                                <td>
                                                    <strong>₦<?= number_format($data['value'], 0) ?></strong>
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar" style="width: <?= $percentage ?>%">
                                                            <?= number_format($percentage, 1) ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Top Categories</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach (array_slice($valuationByCategory, 0, 5) as $category => $data): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong><?= htmlspecialchars($category) ?></strong>
                                    <small>₦<?= number_format($data['value'], 0) ?></small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" style="width: <?= ($data['value'] / $totalValuation) * 100 ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>Print Report
                        </button>
                        <a href="inventory-report.php" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-chart-bar me-1"></i>View Inventory Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Product Valuation -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Product Valuation Details</h5>
            </div>
            <div class="card-body">
                <?php if (empty($products)): ?>
                    <p class="text-muted mb-0">No products in inventory</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): 
                                    $value = $product['quantity'] * $product['price'];
                                ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($product['product_name']) ?></strong></td>
                                        <td><?= htmlspecialchars($product['category']) ?></td>
                                        <td>₦<?= number_format($product['price'], 2) ?></td>
                                        <td><?= (int)$product['quantity'] ?> units</td>
                                        <td>₦<?= number_format($value, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<style>
@media print {
    .navbar, .sidebar, .btn, .form-control, .form-select {
        display: none !important;
    }
    .main-content {
        padding: 0 !important;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>