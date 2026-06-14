<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$id = intval($_GET['id'] ?? 0);

// Get purchase order details
$purchaseStmt = $pdo->prepare("
    SELECT
        purchase_orders.*,
        suppliers.supplier_name,
        suppliers.contact_person,
        suppliers.phone,
        suppliers.email
    FROM purchase_orders
    LEFT JOIN suppliers ON purchase_orders.supplier_id = suppliers.id
    WHERE purchase_orders.id = ?
");
$purchaseStmt->execute([$id]);
$purchase = $purchaseStmt->fetch(PDO::FETCH_ASSOC);

if (!$purchase) {
    die('Purchase order not found');
}

// Get purchase items
$itemsStmt = $pdo->prepare("
    SELECT
        purchase_items.*,
        products.product_name,
        manufacturers.manufacturer_name,
        unit.unit_name

    FROM purchase_items

    LEFT JOIN products 
        ON purchase_items.product_id = products.id

    LEFT JOIN manufacturers
        ON products.manufacturer_id = manufacturers.id

    LEFT JOIN unit
        ON products.unit_id = unit.id

    WHERE purchase_items.purchase_id = ?
");
$itemsStmt->execute([$id]);
$items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle payment status update
if (isset($_POST['update_payment'])) {
    $paymentStatus = $_POST['payment_status'];
    $updateStmt = $pdo->prepare("UPDATE purchase_orders SET payment_status = ? WHERE id = ?");
    if ($updateStmt->execute([$paymentStatus, $id])) {
        $purchase['payment_status'] = $paymentStatus;
    }
}
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark">
                    <i class="fas fa-receipt me-2 text-primary"></i>Purchase Order Details
                </h1>
                <p class="text-muted">Invoice: <strong><?= htmlspecialchars($purchase['invoice_number']) ?></strong></p>
            </div>
            <a href="purchase-orders.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>

        <div class="row">
            <!-- Purchase Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Invoice Number:</strong>
                                <p><?= htmlspecialchars($purchase['invoice_number']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <strong>Date:</strong>
                                <p><?= date('d M Y, h:i A', strtotime($purchase['created_at'])) ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Supplier:</strong>
                                <p><?= htmlspecialchars($purchase['supplier_name'] ?? 'Unknown') ?></p>
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong>
                                <p>
                                    <span class="badge bg-<?= match($purchase['payment_status']) {
                                        'Paid' => 'success',
                                        'Pending' => 'warning',
                                        'Partial' => 'info',
                                        default => 'secondary'
                                    } ?>">
                                        <?= htmlspecialchars($purchase['payment_status']) ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Items -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Purchase Items</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($items)): ?>
                            <p class="text-muted">No items in this purchase order</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $totalAmount = 0; foreach($items as $item): 
                                            $totalAmount += $item['subtotal'];
                                        ?>
                                            <tr>
                                                <td>
                                                                <strong><?= htmlspecialchars($item['product_name']) ?></strong>

                                                                <?php if (!empty($item['manufacturer_name'])): ?>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        <?= htmlspecialchars($item['manufacturer_name']) ?>
                                                                    </small>
                                                                <?php endif; ?>
                                                            </td>
                                                <td><?= (int)$item['quantity'] ?></td>
                                                <td> <strong><?= htmlspecialchars($item['unit_name']) ?></strong></td>
                                                <td>₦<?= number_format($item['unit_price'], 2) ?></td>
                                                <td>₦<?= number_format($item['subtotal'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                            <td><strong>₦<?= number_format($totalAmount, 2) ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Supplier Information & Actions -->
            <div class="col-lg-4">
                <!-- Supplier Details -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Supplier Information</h5>
                    </div>
                    <div class="card-body">
                        <strong><?= htmlspecialchars($purchase['supplier_name'] ?? 'Unknown') ?></strong>
                        <?php if ($purchase['contact_person']): ?>
                            <p class="small text-muted mb-2">
                                <i class="fas fa-user me-1"></i>
                                <?= htmlspecialchars($purchase['contact_person']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($purchase['phone']): ?>
                            <p class="small mb-2">
                                <i class="fas fa-phone me-1"></i>
                                <a href="tel:<?= htmlspecialchars($purchase['phone']) ?>">
                                    <?= htmlspecialchars($purchase['phone']) ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if ($purchase['email']): ?>
                            <p class="small">
                                <i class="fas fa-envelope me-1"></i>
                                <a href="mailto:<?= htmlspecialchars($purchase['email']) ?>">
                                    <?= htmlspecialchars($purchase['email']) ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Update Payment Status -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Update Payment Status</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <select name="payment_status" class="form-select">
                                    <option value="Pending" <?= $purchase['payment_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Partial" <?= $purchase['payment_status'] === 'Partial' ? 'selected' : '' ?>>Partial</option>
                                    <option value="Paid" <?= $purchase['payment_status'] === 'Paid' ? 'selected' : '' ?>>Paid</option>
                                </select>
                            </div>
                            <button type="submit" name="update_payment" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Invoice
                        </button>
                        <a href="add-purchase.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-plus me-2"></i>New Purchase
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>