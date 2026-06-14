<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// Get expired products
$expiredStmt = $pdo->query("
    SELECT 
        products.*,
        categories.category_name
    FROM products

    LEFT JOIN categories
        ON products.category_id = categories.id

    WHERE expiry_date < CURDATE()

    ORDER BY expiry_date ASC
");
$expired = $expiredStmt->fetchAll(PDO::FETCH_ASSOC);

// Get expiring soon (within 30 days)
$expiringStmt = $pdo->query("
    SELECT 
        products.*,
        categories.category_name
    FROM products

    LEFT JOIN categories
        ON products.category_id = categories.id

    WHERE expiry_date >= CURDATE() 
    AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)

    ORDER BY expiry_date ASC
");
$expiring = $expiringStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate stats
$expiredCount = count($expired);
$expiringCount = count($expiring);
$expiredQuantity = array_sum(array_column($expired, 'quantity'));
$expiringQuantity = array_sum(array_column($expiring, 'quantity'));

// Handle removal of expired stock
$message = '';
if (isset($_POST['remove_expired'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    try {
        $pdo->beginTransaction();
        
        // Get current stock
        $productStmt = $pdo->prepare("SELECT quantity FROM products WHERE id = ?");
        $productStmt->execute([$product_id]);
        $product = $productStmt->fetch(PDO::FETCH_ASSOC);
        $oldQty = $product['quantity'];
        
        // Update stock
        $newQty = max(0, $oldQty - $quantity);
        $updateStmt = $pdo->prepare("UPDATE products SET quantity = ? WHERE id = ?");
        $updateStmt->execute([$newQty, $product_id]);
        
        // Log the transaction
        $logStmt = $pdo->prepare("
            INSERT INTO stock_transactions (product_id, transaction_type, quantity, old_quantity, new_quantity, reference, notes, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $logStmt->execute([
            $product_id, 
            'out', 
            $quantity, 
            $oldQty, 
            $newQty, 
            'EXPIRED_REMOVAL', 
            'Expired stock removal',
            1
        ]);
        
        $pdo->commit();
        $message = 'Expired stock removed successfully';
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = 'Error removing expired stock: ' . $e->getMessage();
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
                <i class="fas fa-calendar-times me-2 text-danger"></i>Expiry Date Tracking
            </h1>
            <p class="text-muted">Monitor expired and expiring soon products</p>
        </div>

        <!-- Alert Messages -->
        <?php if ($message): ?>
            <div class="alert alert-<?= strpos($message, 'successfully') !== false ? 'success' : 'danger' ?> alert-dismissible fade show">
                <i class="fas fa-<?= strpos($message, 'successfully') !== false ? 'check-circle' : 'exclamation-circle' ?> me-2"></i>
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Expired Products</h5>
                        <h2 class="text-danger"><?= number_format($expiredCount) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Expiring Soon</h5>
                        <h2 class="text-warning"><?= number_format($expiringCount) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Expired Units</h5>
                        <h2 class="text-danger"><?= number_format($expiredQuantity) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Expiring Units</h5>
                        <h2 class="text-warning"><?= number_format($expiringQuantity) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expired Products -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-circle text-danger me-2"></i>Expired Products
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($expired)): ?>
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle me-2"></i>No expired products found
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Batch Number</th>
                                    <th>Expiry Date</th>
                                    <th>Quantity</th>
                                    <th>Days Expired</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expired as $product): 
                                    $daysExpired = (strtotime('now') - strtotime($product['expiry_date'])) / (60 * 60 * 24);
                                ?>
                                    <tr class="table-danger">
                                        <td><strong><?= htmlspecialchars($product['product_name']) ?></strong></td>
                                        <td><?= htmlspecialchars($product['category_name']) ?></td>
                                        <td><?= htmlspecialchars($product['batch_number'] ?? '-') ?></td>
                                        <td><?= date('d M Y', strtotime($product['expiry_date'])) ?></td>
                                        <td>
                                            <span class="badge bg-danger"><?= (int)$product['quantity'] ?> units</span>
                                        </td>
                                        <td><?= (int)$daysExpired ?> days ago</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#removeModal"
                                                    onclick="setRemovalProduct(<?= $product['id'] ?>, '<?= htmlspecialchars($product['product_name']) ?>', <?= $product['quantity'] ?>)">
                                                <i class="fas fa-trash me-1"></i>Remove
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Expiring Soon Products -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-clock text-warning me-2"></i>Expiring Soon (Within 30 Days)
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($expiring)): ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>No products expiring soon
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Batch Number</th>
                                    <th>Expiry Date</th>
                                    <th>Quantity</th>
                                    <th>Days Until Expiry</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expiring as $product): 
                                    $daysLeft = (strtotime($product['expiry_date']) - strtotime('now')) / (60 * 60 * 24);
                                    $priority = $daysLeft <= 7 ? 'High' : ($daysLeft <= 15 ? 'Medium' : 'Low');
                                    $priorityClass = $daysLeft <= 7 ? 'danger' : ($daysLeft <= 15 ? 'warning' : 'info');
                                ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($product['product_name']) ?></strong></td>
                                        <td><?= htmlspecialchars($product['category_name']) ?></td>
                                        <td><?= htmlspecialchars($product['batch_number'] ?? '-') ?></td>
                                        <td><?= date('d M Y', strtotime($product['expiry_date'])) ?></td>
                                        <td>
                                            <span class="badge bg-warning"><?= (int)$product['quantity'] ?> units</span>
                                        </td>
                                        <td><?= (int)$daysLeft ?> days</td>
                                        <td>
                                            <span class="badge bg-<?= $priorityClass ?>"><?= $priority ?></span>
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

<!-- Remove Expired Stock Modal -->
<div class="modal fade" id="removeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove Expired Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Product</strong></label>
                        <p id="modal_product_name"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Available Quantity</strong></label>
                        <p id="modal_available_qty"></p>
                    </div>
                    <div class="mb-3">
                        <label for="modal_quantity" class="form-label"><strong>Quantity to Remove</strong></label>
                        <input type="number" id="modal_quantity" name="quantity" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="modal_product_id" name="product_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="remove_expired" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Remove Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setRemovalProduct(productId, productName, quantity) {
    document.getElementById('modal_product_id').value = productId;
    document.getElementById('modal_product_name').textContent = productName;
    document.getElementById('modal_available_qty').textContent = quantity + ' units';
    document.getElementById('modal_quantity').value = quantity;
    document.getElementById('modal_quantity').max = quantity;
}
</script>

<?php require_once '../../includes/footer.php'; ?>
