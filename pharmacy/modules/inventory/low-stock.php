<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// Get low stock products
// $stmt = $pdo->query("
//     SELECT * FROM products 
//     WHERE quantity <= reorder_level 
//     ORDER BY quantity ASC
// ");
$stmt = $pdo->query("
    SELECT 
        products.*,
        categories.category_name
    FROM products
    LEFT JOIN categories 
        ON products.category_id = categories.id
    WHERE quantity <= reorder_level
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate metrics
$totalLowStock = count($products);
$criticalStock = $pdo->query("
    SELECT COUNT(*) FROM products WHERE quantity = 0
")->fetchColumn();
$totalToReorder = $pdo->query("
    SELECT COALESCE(SUM(reorder_level - quantity), 0) FROM products 
    WHERE quantity <= reorder_level
")->fetchColumn();
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark">
                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Low Stock Alert
            </h1>
            <p class="text-muted">Products that need to be reordered</p>
        </div>

        <!-- Alert Metrics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Low Stock Items</h5>
                        <h2 class="text-warning"><?= number_format($totalLowStock) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Out of Stock</h5>
                        <h2 class="text-danger"><?= number_format($criticalStock) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Units to Reorder</h5>
                        <h2 class="text-info"><?= number_format($totalToReorder) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <a href="purchase-orders.php" class="btn btn-primary h-100 w-100 d-flex align-items-center justify-content-center">
                    <i class="fas fa-plus me-2"></i>Create Purchase Order
                </a>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Products Below Reorder Level</h5>
            </div>
            <div class="card-body">
                <?php if (empty($products)): ?>
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        All products are at optimal stock levels!
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Shortage</th>
                                    <th>Unit Price</th>
                                    <th>Est. Cost</th>
                                    <th>Priority</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($products as $product): 
                                $shortage = $product['reorder_level'] - $product['quantity'];
                                $estCost = $shortage * $product['cost_price'];
                                
                                // Determine priority
                                if ($product['quantity'] == 0) {
                                    $priority = 'Critical';
                                    $priorityClass = 'danger';
                                } elseif ($shortage > $product['reorder_level'] * 0.5) {
                                    $priority = 'High';
                                    $priorityClass = 'warning';
                                } else {
                                    $priority = 'Normal';
                                    $priorityClass = 'info';
                                }
                            ?>                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($product['product_name']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($product['category_name']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $product['quantity'] == 0 ? 'danger' : 'warning' ?>">
                                            <?= (int)$product['quantity'] ?> units
                                        </span>
                                    </td>
                                    <td><?= (int)$product['reorder_level'] ?></td>
                                    <td class="text-danger fw-bold">
                                        -<?= (int)$shortage ?> units
                                    </td>
                                    <td>₦<?= number_format($product['cost_price'], 2) ?></td>
                                    <td>₦<?= number_format($estCost, 2) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $priorityClass ?>">
                                            <?= $priority ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="add-purchase.php" class="btn btn-sm btn-primary" title="Create Purchase Order">
                                            <i class="fas fa-shopping-cart"></i> Order
                                        </a>
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
</div>

<?php require_once '../../includes/footer.php'; ?>