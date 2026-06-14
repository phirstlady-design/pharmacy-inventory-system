<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

/* =========================
   DATA: REVENUE (7 DAYS)
========================= */
$revenueStmt = $pdo->query("
    SELECT 
        DATE(created_at) AS day,
        SUM(total_amount) AS revenue
    FROM sales
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY day ASC
");
$revenueData = $revenueStmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   DATA: TOP PRODUCTS
========================= */
$productStmt = $pdo->query("
    SELECT 
        p.product_name,
        SUM(si.quantity) AS qty
    FROM sale_items si
    JOIN products p ON p.id = si.product_id
    GROUP BY si.product_id
    ORDER BY qty DESC
    LIMIT 10
");
$productData = $productStmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   DATA: PAYMENT METHODS
========================= */
$methods = ['cash', 'transfer', 'pos', 'wallet', 'insurance'];

$paymentCounts = array_fill_keys($methods, 0);

$stmt = $pdo->query("
    SELECT payment_method, COUNT(*) AS total
    FROM sales
    GROUP BY payment_method
");

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $paymentCounts[$row['payment_method']] = $row['total'];
}

$paymentLabels = array_map('ucfirst', array_keys($paymentCounts));
$paymentValues = array_values($paymentCounts);

/* =========================
   DATA: SALES VOLUME (7 DAYS)
========================= */
$volumeStmt = $pdo->query("
    SELECT 
        DATE(created_at) AS day,
        COUNT(*) AS sales_count
    FROM sales
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY day ASC
");
$volumeData = $volumeStmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   PREPARE ARRAYS FOR CHART.JS
========================= */
$revenueLabels = [];
$revenueValues = [];

foreach ($revenueData as $row) {
    $revenueLabels[] = $row['day'];
    $revenueValues[] = $row['revenue'] ?? 0;
}

$productLabels = [];
$productValues = [];

foreach ($productData as $row) {
    $productLabels[] = $row['product_name'];
    $productValues[] = $row['qty'];
}

// $labelMap = [
//     'cash' => 'Cash',
//     'transfer' => 'Bank Transfer',
//     'pos' => 'Card (POS)',
//     'wallet' => 'Wallet',
//     'insurance' => 'Insurance'
// ];

// $paymentLabels = [];
// $paymentValues = [];

// foreach ($paymentData as $row) {
//     $key = $row['payment_method'];

//     $paymentLabels[] = $labelMap[$key] ?? ucfirst($key);
//     $paymentValues[] = $row['total'];
// }

$volumeLabels = [];
$volumeValues = [];

foreach ($volumeData as $row) {
    $volumeLabels[] = $row['day'];
    $volumeValues[] = $row['sales_count'];
}
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
<div class="container-fluid">

<h2 class="mb-4">Sales Dashboard</h2>

<!-- ================= CHART GRID ================= -->
<div class="row">

    <!-- REVENUE -->
    <div class="col-md-6 mb-4">
        <div class="card p-3">
            <h5>Revenue Trend (7 Days)</h5>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- TOP PRODUCTS -->
    <div class="col-md-6 mb-4">
        <div class="card p-3">
            <h5>Top Selling Products</h5>
            <canvas id="productChart"></canvas>
        </div>
    </div>

    <!-- PAYMENT -->
    <div class="col-md-6 mb-4">
        <div class="card p-3">
            <h5>Payment Methods</h5>
            <canvas id="paymentChart"></canvas>
        </div>
    </div>

    <!-- VOLUME -->
    <div class="col-md-6 mb-4">
        <div class="card p-3">
            <h5>Sales Volume (7 Days)</h5>
            <canvas id="volumeChart"></canvas>
        </div>
    </div>

</div>

</div>
</div>

<!-- ================= CHART.JS ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* ================= REVENUE CHART ================= */
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($revenueLabels) ?>,
        datasets: [{
            label: 'Revenue (₦)',
            data: <?= json_encode($revenueValues) ?>,
            borderColor: '#28a745',
            tension: 0.3,
            fill: false
        }]
    }
});

/* ================= TOP PRODUCTS ================= */
new Chart(document.getElementById('productChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($productLabels) ?>,
        datasets: [{
            label: 'Quantity Sold',
            data: <?= json_encode($productValues) ?>,
            backgroundColor: '#007bff'
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y'
    }
});

/* ================= PAYMENT METHODS ================= */
new Chart(document.getElementById('paymentChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($paymentLabels) ?>,
        datasets: [{
            data: <?= json_encode($paymentValues) ?>,
            backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545', '#6f42c1']
        }]
    }
});

/* ================= SALES VOLUME ================= */
new Chart(document.getElementById('volumeChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($volumeLabels) ?>,
        datasets: [{
            label: 'Sales Count',
            data: <?= json_encode($volumeValues) ?>,
            borderColor: '#ffc107',
            tension: 0.3
        }]
    }
});
</script>

<?php require_once '../../includes/footer.php'; ?>