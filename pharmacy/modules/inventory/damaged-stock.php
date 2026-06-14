<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$message = '';
$messageType = '';

// Get all damage records
$damageStmt = $pdo->query("
    SELECT 
        ds.*,
        p.product_name,
        p.category
    FROM damaged_stock ds
    LEFT JOIN products p ON ds.product_id = p.id
    ORDER BY ds.created_at DESC
");
$damageRecords = $damageStmt->fetchAll(PDO::FETCH_ASSOC);

// Get products for dropdown
$products = $pdo->query("SELECT id, product_name FROM products ORDER BY product_name ASC")
    ->fetchAll(PDO::FETCH_ASSOC);

// Calculate stats
$totalDamaged = 0;
$pendingDisposal = 0;
$disposedCount = 0;

foreach ($damageRecords as $record) {
    $totalDamaged += $record['quantity'];
    if ($record['disposal_status'] === 'pending') {
        $pendingDisposal += $record['quantity'];
    } else {
        $disposedCount += 1;
    }
}

// Handle new damage report
if (isset($_POST['report_damage'])) {
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);
    $reason = trim($_POST['reason'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($product_id && $quantity > 0 && $reason) {
        try {
            $pdo->beginTransaction();

            // Get current stock
            $productStmt = $pdo->prepare("SELECT quantity FROM products WHERE id = ?");
            $productStmt->execute([$product_id]);
            $product = $productStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$product || $product['quantity'] < $quantity) {
                throw new Exception('Insufficient stock for this operation');
            }

            $oldQty = $product['quantity'];
            $newQty = $oldQty - $quantity;

            // Record damage
            $insertStmt = $pdo->prepare("
                INSERT INTO damaged_stock (product_id, quantity, reason, notes, disposal_status, reported_at, reported_by)
                VALUES (?, ?, ?, ?, ?, NOW(), ?)
            ");
            $insertStmt->execute([
                $product_id,
                $quantity,
                $reason,
                $notes,
                'pending',
                1
            ]);

            // Update stock
            $updateStmt = $pdo->prepare("UPDATE products SET quantity = ? WHERE id = ?");
            $updateStmt->execute([$newQty, $product_id]);

            // Log transaction
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
                'DAMAGE_REPORT',
                'Damaged stock: ' . $reason,
                1
            ]);

            $pdo->commit();
            $message = 'Damage report recorded successfully';
            $messageType = 'success';
            header('Location: damaged-stock.php');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

// Handle update disposal status
if (isset($_POST['update_disposal'])) {
    $damage_id = intval($_POST['damage_id']);
    $status = $_POST['disposal_status'];

    try {
        $stmt = $pdo->prepare("UPDATE damaged_stock SET disposal_status = ?, disposal_date = NOW() WHERE id = ?");
        $stmt->execute([$status, $damage_id]);
        $message = 'Disposal status updated';
        $messageType = 'success';
        header('Location: damaged-stock.php');
        exit;
    } catch (Exception $e) {
        $message = 'Error updating status: ' . $e->getMessage();
        $messageType = 'danger';
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
                <i class="fas fa-triangle-exclamation me-2 text-danger"></i>Damaged Stock Management
            </h1>
            <p class="text-muted">Track and manage damaged products</p>
        </div>

        <!-- Alert Messages -->
        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show">
                <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-2"></i>
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Damaged</h5>
                        <h2 class="text-danger"><?= number_format($totalDamaged) ?> units</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Pending Disposal</h5>
                        <h2 class="text-warning"><?= number_format($pendingDisposal) ?> units</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Disposed</h5>
                        <h2 class="text-success"><?= number_format($disposedCount) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <button class="btn btn-danger h-100 w-100" data-bs-toggle="modal" data-bs-target="#reportDamageModal">
                    <i class="fas fa-plus me-2"></i>Report Damage
                </button>
            </div>
        </div>

        <!-- Damage Records Table -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Damage Records</h5>
            </div>
            <div class="card-body">
                <?php if (empty($damageRecords)): ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>No damage records found
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Reason</th>
                                    <th>Reported Date</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($damageRecords as $record): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($record['product_name'] ?? 'Unknown') ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($record['category'] ?? '-') ?></td>
                                        <td>
                                            <span class="badge bg-danger"><?= (int)$record['quantity'] ?> units</span>
                                        </td>
                                        <td>
                                            <?php 
                                                $reasonBadgeClass = match($record['reason']) {
                                                    'Broken' => 'danger',
                                                    'Leaked' => 'warning',
                                                    'Contaminated' => 'danger',
                                                    'Defective' => 'info',
                                                    default => 'secondary'
                                                };
                                            ?>
                                            <span class="badge bg-<?= $reasonBadgeClass ?>">
                                                <?= htmlspecialchars($record['reason']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?= date('d M Y', strtotime($record['reported_at'])) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $record['disposal_status'] === 'pending' ? 'warning' : 'success' ?>">
                                                <?= ucfirst($record['disposal_status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= htmlspecialchars($record['notes'] ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <?php if ($record['disposal_status'] === 'pending'): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="damage_id" value="<?= $record['id'] ?>">
                                                    <input type="hidden" name="disposal_status" value="disposed">
                                                    <button type="submit" name="update_disposal" class="btn btn-sm btn-success" onclick="return confirm('Mark as disposed?')">
                                                        <i class="fas fa-check me-1"></i>Mark Disposed
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <small class="text-success">Disposed</small>
                                            <?php endif; ?>
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

<!-- Report Damage Modal -->
<div class="modal fade" id="reportDamageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Damaged Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">
                            <strong>Product</strong>
                        </label>
                        <select id="product_id" name="product_id" class="form-select" required>
                            <option value="">Select a product...</option>
                            <?php foreach ($products as $prod): ?>
                                <option value="<?= $prod['id'] ?>">
                                    <?= htmlspecialchars($prod['product_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">
                            <strong>Quantity Damaged</strong>
                        </label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">
                            <strong>Reason</strong>
                        </label>
                        <select id="reason" name="reason" class="form-select" required>
                            <option value="">Select reason...</option>
                            <option value="Broken">Broken</option>
                            <option value="Leaked">Leaked</option>
                            <option value="Contaminated">Contaminated</option>
                            <option value="Defective">Defective</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="report_damage" class="btn btn-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Report Damage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>