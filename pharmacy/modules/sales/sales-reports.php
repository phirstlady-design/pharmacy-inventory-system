<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

/* =========================
   DATE FILTER
========================= */
$dateFrom = $_GET['date_from'] ?? date('Y-m-01');
$dateTo   = $_GET['date_to'] ?? date('Y-m-d');

/* =========================
   MAIN SALES QUERY (OPTIMIZED)
   - includes item count in one query
========================= */
$sql = "
    SELECT 
        s.*,
        COUNT(si.id) AS item_count
    FROM sales s
    LEFT JOIN sale_items si ON si.sale_id = s.id
    WHERE DATE(s.created_at) BETWEEN ? AND ?
    GROUP BY s.id
    ORDER BY s.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$dateFrom, $dateTo]);
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   SUMMARY STATS (SAFE + FILTERED)
========================= */

// Total sales count
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM sales 
    WHERE DATE(created_at) BETWEEN ? AND ?
");
$stmt->execute([$dateFrom, $dateTo]);
$totalSales = $stmt->fetchColumn();

// Total revenue
$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(total_amount), 0)
    FROM sales
    WHERE DATE(created_at) BETWEEN ? AND ?
");
$stmt->execute([$dateFrom, $dateTo]);
$totalRevenue = $stmt->fetchColumn();

// Average sale value
$avgSaleValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

/* =========================
   PROFIT (OPTIONAL BUT POWERFUL)
========================= */
$stmt = $pdo->prepare("
    SELECT 
        COALESCE(SUM((si.unit_price - p.cost_price) * si.quantity), 0) AS profit
    FROM sale_items si
    JOIN products p ON p.id = si.product_id
    JOIN sales s ON s.id = si.sale_id
    WHERE DATE(s.created_at) BETWEEN ? AND ?
");
$stmt->execute([$dateFrom, $dateTo]);
$totalProfit = $stmt->fetchColumn();

?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
<div class="container-fluid">

<!-- ================= HEADER ================= -->
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">
        <i class="fas fa-history me-2 text-primary"></i>Sales History
    </h1>
    <p class="text-muted">Track all sales transactions</p>
</div>

<!-- ================= FILTER ================= -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">

            <div class="col-md-5">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="<?= $dateFrom ?>">
            </div>

            <div class="col-md-5">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="<?= $dateTo ?>">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>

        </form>
    </div>
</div>

<!-- ================= STATS ================= -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h6>Total Sales</h6>
                <h3 class="text-primary"><?= number_format($totalSales) ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h6>Revenue</h6>
                <h3 class="text-success">₦<?= number_format($totalRevenue, 2) ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h6>Profit</h6>
                <h3 class="text-warning">₦<?= number_format($totalProfit, 2) ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h6>Avg Sale</h6>
                <h3 class="text-info">₦<?= number_format($avgSaleValue, 2) ?></h3>
            </div>
        </div>
    </div>

</div>

<!-- ================= TABLE ================= -->
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transactions</h5>
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <div class="card-body">

        <?php if (!$sales): ?>
            <div class="alert alert-info mb-0">
                No sales found for selected period.
            </div>
        <?php else: ?>

        <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>

                    <td>
                        <strong>
                            <?= htmlspecialchars($sale['invoice_no'] ?? '#' . $sale['id']) ?>
                        </strong>
                    </td>

                    <td>
                        <?= date('d M Y, h:i A', strtotime($sale['created_at'])) ?>
                    </td>

                    <td>
                        <span class="badge bg-secondary">
                            <?= $sale['item_count'] ?> items
                        </span>
                    </td>

                    <td>
                        <strong>₦<?= number_format($sale['total_amount'], 2) ?></strong>
                    </td>

                    <td>
                        <?= ucfirst($sale['payment_method'] ?? 'cash') ?>
                    </td>

                    <td>
                        <?php if ($sale['payment_status'] === 'paid'): ?>
                            <span class="badge bg-success">Paid</span>
                        <?php elseif ($sale['payment_status'] === 'partial'): ?>
                            <span class="badge bg-warning">Partial</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Pending</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <a href="receipt.php?sale_id=<?= $sale['id'] ?>"
                           class="btn btn-sm btn-info">
                            View
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

<!-- ================= PRINT STYLE ================= -->
<style>
@media print {
    .sidebar, .navbar, form, button {
        display: none !important;
    }
    .main-content {
        padding: 0 !important;
    }
}
</style>

<?php require_once '../../includes/footer.php'; ?>