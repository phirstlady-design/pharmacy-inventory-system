<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// Get filter parameters
$dateFrom = $_GET['date_from'] ?? date('Y-m-01');
$dateTo = $_GET['date_to'] ?? date('Y-m-d');
$category = $_GET['category'] ?? '';

// Build query for report
$sql = "
    SELECT 
        p.*,
        c.category_name,
        
        COUNT(im.id) as total_transactions,

        COALESCE(SUM(
            CASE 
                WHEN im.movement_type = 'in' 
                THEN im.quantity 
                ELSE 0 
            END
        ), 0) as total_in,

        COALESCE(SUM(
            CASE 
                WHEN im.movement_type = 'out' 
                THEN im.quantity 
                ELSE 0 
            END
        ), 0) as total_out

    FROM products p

    LEFT JOIN inventory_movements im 
        ON p.id = im.product_id
        AND DATE(im.created_at) BETWEEN ? AND ?

    LEFT JOIN categories c
        ON p.category_id = c.id

    WHERE 1=1
";

$params = [$dateFrom, $dateTo];

if ($category) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category;
}

$sql .= " GROUP BY p.id ORDER BY p.product_name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$inventoryData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories for filter
$categories = $pdo->query("
    SELECT * FROM categories
    ORDER BY category_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Calculate summary stats
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

$totalValue = 0;
$totalUnits = 0;
$lowStockItems = 0;

foreach ($inventoryData as $item) {

    $totalValue += ($item['quantity'] * $item['cost_price']);

    $totalUnits += $item['quantity'];

    if ($item['quantity'] <= $item['reorder_level']) {
        $lowStockItems++;
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
                <i class="fas fa-chart-bar me-2 text-primary"></i>Inventory Report
            </h1>
            <p class="text-muted">Detailed inventory analysis and statistics</p>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" id="date_from" name="date_from" class="form-control" value="<?= $dateFrom ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" id="date_to" name="date_to" class="form-control" value="<?= $dateTo ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
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
                        <h5 class="card-title text-muted">Total Inventory Value</h5>
                        <h2 class="text-success">₦<?= number_format($totalValue, 0) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Units</h5>
                        <h2 class="text-info"><?= number_format($totalUnits) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Low Stock Items</h5>
                        <h2 class="text-warning"><?= number_format($lowStockItems) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Total Products</h5>
                        <h2 class="text-primary"><?= number_format($totalProducts) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Report Table -->
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Inventory Details</h5>
                <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($inventoryData)): ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>No inventory data found for the selected period
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Unit Price</th>
                                    <th>Stock Value</th>
                                    <th>Reorder Level</th>
                                    <th>Period Inbound</th>
                                    <th>Period Outbound</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventoryData as $item): 
                                   
                                    $stockValue = $item['quantity'] * $item['cost_price'];
                                    $status = $item['quantity'] > $item['reorder_level'] ? 'OK' : 'Low';
                                    $statusClass = $status === 'OK' ? 'success' : 'warning';
                                ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($item['product_name']) ?></strong></td>
                                        <td><?= htmlspecialchars($item['category_name'] ?? 'N/A') ?></td>
                                        <td><?= (int)$item['quantity'] ?> units</td>
                                        <td>₦<?= number_format($item['cost_price'], 2) ?></td>
                                        <td>₦<?= number_format($stockValue, 2) ?></td>
                                        <td><?= (int)$item['reorder_level'] ?></td>
                                        <td>
                                            <span class="badge bg-success"><?= (int)$item['total_in'] ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger"><?= (int)$item['total_out'] ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $statusClass ?>"><?= $status ?></span>
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
