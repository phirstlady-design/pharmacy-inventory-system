<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$message = '';
$messageType = '';

// Get all products with current stock
$products = $pdo->query("SELECT * FROM products ORDER BY product_name ASC")
                ->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['adjust'])) {
    $product_id = intval($_POST['product_id']);
    $old_quantity = intval($_POST['old_quantity']);
    $new_quantity = intval($_POST['new_quantity']);
    $adjustment_reason = trim($_POST['adjustment_reason'] ?? '');

    // Get product info
    $productStmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $productStmt->execute([$product_id]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        try {
            $pdo->beginTransaction();

            // Update product quantity
            $updateStmt = $pdo->prepare("UPDATE products SET quantity = ? WHERE id = ?");
            $updateStmt->execute([$new_quantity, $product_id]);

            // Log the adjustment (optional - if you have a history table)
            $difference = $new_quantity - $old_quantity;
            $logStmt = $pdo->prepare("
                INSERT INTO inventory_adjustments (product_id, old_quantity, new_quantity, difference, reason, adjusted_by, adjusted_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $logStmt->execute([$product_id, $old_quantity, $new_quantity, $difference, $adjustment_reason, 1]);

            $pdo->commit();
            $message = 'Stock adjusted successfully';
            $messageType = 'success';
        } catch(Exception $e) {
            $pdo->rollBack();
            $message = 'Error adjusting stock: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark">
                <i class="fas fa-sync-alt me-2 text-primary"></i>Stock Adjustment
            </h1>
            <p class="text-muted">Adjust inventory quantities for physical count or corrections</p>
        </div>

        <!-- Alert Messages -->
        <?php if($message): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-2"></i>
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Adjustment Form -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Adjust Stock Quantity</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="product_id" class="form-label">
                                    <strong>Select Product</strong>
                                </label>
                                <select id="product_id" name="product_id" class="form-select" required onchange="updateProductInfo()">
                                    <option value="">-- Choose Product --</option>
                                    <?php foreach($products as $product): ?>
                                        <option value="<?= $product['id'] ?>" data-current="<?= $product['quantity'] ?>">
                                            <?= htmlspecialchars($product['product_name']) ?> (Current: <?= (int)$product['quantity'] ?> units)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="old_quantity" class="form-label">
                                            <strong>Current Quantity</strong>
                                        </label>
                                        <input type="number" id="old_quantity" name="old_quantity" class="form-control" 
                                               readonly style="background-color: #f0f0f0;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="new_quantity" class="form-label">
                                            <strong>New Quantity</strong>
                                        </label>
                                        <input type="number" id="new_quantity" name="new_quantity" class="form-control" 
                                               min="0" required onchange="calculateDifference()">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>Adjustment</strong>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="difference">0</span>
                                    <span class="input-group-text">units</span>
                                </div>
                                <small class="text-muted">Positive = Stock In, Negative = Stock Out</small>
                            </div>

                            <div class="mb-3">
                                <label for="adjustment_reason" class="form-label">
                                    <strong>Reason for Adjustment</strong>
                                </label>
                                <select name="adjustment_reason" class="form-select" required>
                                    <option value="">-- Select Reason --</option>
                                    <option value="Physical Count">Physical Count</option>
                                    <option value="Damage/Spoilage">Damage/Spoilage</option>
                                    <option value="Lost">Lost</option>
                                    <option value="Correction">Correction</option>
                                    <option value="Theft">Theft</option>
                                    <option value="Return">Return</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <button type="submit" name="adjust" class="btn btn-primary w-100">
                                <i class="fas fa-check me-2"></i>Adjust Stock
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Info Panel -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Product Information</h5>
                    </div>
                    <div class="card-body" id="product-info">
                        <p class="text-muted">Select a product to view details</p>
                    </div>
                </div>

                <!-- Stock Adjustment Guide -->
                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Instructions
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Physical Count:</strong> Use when conducting inventory count
                            </li>
                            <li class="mb-2">
                                <strong>Damage/Spoilage:</strong> For expired or damaged items
                            </li>
                            <li class="mb-2">
                                <strong>Lost/Theft:</strong> For missing inventory
                            </li>
                            <li class="mb-2">
                                <strong>Correction:</strong> For system entry errors
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function updateProductInfo() {
    const select = document.getElementById('product_id');
    const selectedOption = select.options[select.selectedIndex];
    const currentQty = selectedOption.getAttribute('data-current');
    
    document.getElementById('old_quantity').value = currentQty || 0;
    document.getElementById('new_quantity').value = '';
    document.getElementById('difference').textContent = 0;

    // Update product info panel
    const productInfo = document.getElementById('product-info');
    if (selectedOption.value) {
        productInfo.innerHTML = `
            <div class="alert alert-info">
                <strong>${selectedOption.text}</strong><br>
                Current Stock: <span class="badge bg-primary">${currentQty} units</span>
            </div>
        `;
    } else {
        productInfo.innerHTML = '<p class="text-muted">Select a product to view details</p>';
    }
}

function calculateDifference() {
    const oldQty = parseInt(document.getElementById('old_quantity').value) || 0;
    const newQty = parseInt(document.getElementById('new_quantity').value) || 0;
    const difference = newQty - oldQty;
    
    const diffElement = document.getElementById('difference');
    diffElement.textContent = difference;
    
    if (difference > 0) {
        diffElement.className = 'input-group-text text-success fw-bold';
    } else if (difference < 0) {
        diffElement.className = 'input-group-text text-danger fw-bold';
    } else {
        diffElement.className = 'input-group-text';
    }
}
</script>



<?php require_once '../../includes/footer.php'; ?>