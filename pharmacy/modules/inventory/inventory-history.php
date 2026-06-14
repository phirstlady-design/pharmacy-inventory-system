<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// Get filter parameters
$product_id = intval($_GET['product'] ?? 0);
$transaction_type = $_GET['type'] ?? '';
$dateFrom = $_GET['date_from'] ?? date('Y-m-01');
$dateTo = $_GET['date_to'] ?? date('Y-m-d');

// Build query for transactions
$sql = "
    SELECT 
        st.*,
        p.product_name,
        p.category
    FROM stock_transactions st
    LEFT JOIN products p ON st.product_id = p.id
    WHERE DATE(st.created_at) BETWEEN ? AND ?
";

$params = [$dateFrom, $dateTo];

if ($product_id) {
    $sql .= " AND st.product_id = ?";
    $params[] = $product_id;
}

if ($transaction_type) {
    $sql .= " AND st.transaction_type = ?";
    $params[] = $transaction_type;
}

$sql .= " ORDER BY st.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get products for filter
$products = $pdo->query("SELECT id, product_name FROM products ORDER BY product_name ASC")
    ->fetchAll(PDO::FETCH_ASSOC);

// Calculate stats
$totalIn = 0;
$totalOut = 0;
foreach ($transactions as $txn) {
    if ($txn['transaction_type'] === 'in') {
        $totalIn += $txn['quantity'];
    } else {
        $totalOut += $txn['quantity'];
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
                <i class="fas fa-history me-2 text-primary"></i>Inventory History
            </h1>
            <p class="text-muted">Track all stock movements and transactions</p>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" id="date_from" name="date_from" class="form-control" value="<?= $dateFrom ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" id="date_to" name="date_to" class="form-control" value="<?= $dateTo ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="product" class="form-label">Product</label>
                        <select id="product" name="product" class="form-select">
                            <option value="">All Products</option>
                            <?php foreach ($products as $prod): ?>
                                <option value="<?= $prod['id'] ?>" <?= $product_id == $prod['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prod['product_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="in" <?= $transaction_type === 'in' ? 'selected' : '' ?>>Stock In</option>
                            <option value="out" <?= $transaction_type === 'out' ? 'selected' : '' ?>>Stock Out</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Transactions</h5>
                        <h2 class="text-info"><?= count($transactions) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Stock In</h5>
                        <h2 class="text-success">+<?= number_format($totalIn) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Stock Out</h5>
                        <h2 class="text-danger">-<?= number_format($totalOut) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Net Change</h5>
                        <h2 class="<?= ($totalIn - $totalOut) >= 0 ? 'text-success' : 'text-danger' ?>">
                            <?= ($totalIn - $totalOut) >= 0 ? '+' : '' ?><?= number_format($totalIn - $totalOut) ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History Table -->
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transaction History</h5>
                <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($transactions)): ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>No transactions found for the selected filters
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Previous Stock</th>
                                    <th>New Stock</th>
                                    <th>Reference</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $txn): ?>
                                    <tr>
                                        <td>
                                            <small><?= date('d M Y, h:i A', strtotime($txn['created_at'])) ?></small>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($txn['product_name'] ?? 'Unknown') ?></strong>
                                        </td>
                                        <td>
                                            <small><?= htmlspecialchars($txn['category'] ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $txn['transaction_type'] === 'in' ? 'success' : 'danger' ?>">
                                                <?= $txn['transaction_type'] === 'in' ? 'Stock In' : 'Stock Out' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong>
                                                <?= $txn['transaction_type'] === 'in' ? '+' : '-' ?>
                                                <?= (int)$txn['quantity'] ?>
                                            </strong>
                                        </td>
                                        <td><?= (int)$txn['old_quantity'] ?> units</td>
                                        <td>
                                            <span class="badge bg-info"><?= (int)$txn['new_quantity'] ?></span>
                                        </td>
                                        <td>
                                            <small><?= htmlspecialchars($txn['reference'] ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= htmlspecialchars($txn['notes'] ?? '-') ?></small>
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

<style>
@media print {
    .navbar, .sidebar, .btn, form {
        display: none !important;
    }
    .main-content {
        padding: 0 !important;
    }
}
</style>

<?php require_once '../../includes/footer.php'; ?>
