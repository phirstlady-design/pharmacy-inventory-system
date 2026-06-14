<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';


// Get filter parameters
$paymentFilter = $_GET['payment'] ?? '';
$searchTerm = $_GET['search'] ?? '';

// Build query
$sql = "
    SELECT
        purchase_orders.*,
        suppliers.supplier_name,
        COUNT(purchase_items.id) as item_count
    FROM purchase_orders
    LEFT JOIN suppliers ON purchase_orders.supplier_id = suppliers.id
    LEFT JOIN purchase_items ON purchase_orders.id = purchase_items.purchase_id
    WHERE 1=1
";

$params = [];

if ($paymentFilter) {
    $sql .= " AND purchase_orders.payment_status = ?";
    $params[] = $paymentFilter;
}

if ($searchTerm) {
    $sql .= " AND (purchase_orders.invoice_number LIKE ? OR suppliers.supplier_name LIKE ?)";
    $params[] = "%$searchTerm%";
    $params[] = "%$searchTerm%";
}

$sql .= " GROUP BY purchase_orders.id ORDER BY purchase_orders.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate stats
$totalPurchases = $pdo->query("SELECT COUNT(*) FROM purchase_orders")->fetchColumn();
$totalValue = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM purchase_orders")->fetchColumn();
$pendingPayments = $pdo->query("SELECT COUNT(*) FROM purchase_orders WHERE payment_status = 'Pending'")->fetchColumn();
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark">
                <i class="fas fa-file-invoice me-2 text-primary"></i>Purchase Orders
            </h1>
            <p class="text-muted">Manage supplier purchases and payments</p>
        </div>

        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Purchases</h5>
                        <h2 class="text-primary"><?= number_format($totalPurchases) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Value</h5>
                        <h2 class="text-info">₦<?= number_format($totalValue, 0) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Pending Payments</h5>
                        <h2 class="text-warning"><?= number_format($pendingPayments) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <a href="add-purchase.php" class="btn btn-primary h-100 w-100 d-flex align-items-center justify-content-center">
                    <i class="fas fa-plus me-2"></i>New Purchase
                </a>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by invoice or supplier..." 
                               value="<?= htmlspecialchars($searchTerm) ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="payment" class="form-select">
                            <option value="">All Payment Status</option>
                            <option value="Pending" <?= $paymentFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Paid" <?= $paymentFilter === 'Paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="Partial" <?= $paymentFilter === 'Partial' ? 'selected' : '' ?>>Partial</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Purchase Orders Table -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($purchases)): ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>No purchase orders found
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Supplier</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Date</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($purchases as $purchase): 
                                    $paymentStatusClass = match($purchase['payment_status']) {
                                        'Paid' => 'success',
                                        'Pending' => 'warning',
                                        'Partial' => 'info',
                                        default => 'secondary'
                                    };
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($purchase['invoice_number']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($purchase['supplier_name'] ?? 'Unknown') ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= (int)$purchase['item_count'] ?> items</span>
                                    </td>
                                    <td>
                                        <strong>₦<?= number_format($purchase['total_amount'], 2) ?></strong>
                                    </td>
                                    <td>
                                        <small><?= date('d M Y', strtotime($purchase['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $paymentStatusClass ?>">
                                            <?= htmlspecialchars($purchase['payment_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="purchase-details.php?id=<?= $purchase['id'] ?>" class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i> View
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