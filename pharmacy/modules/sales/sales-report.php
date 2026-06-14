<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$filter = $_GET['filter'] ?? 'daily';

$where = "";

if ($filter === 'daily') {
    $where = "DATE(s.created_at) = CURDATE()";
} elseif ($filter === 'weekly') {
    $where = "s.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} else {
    $where = "s.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}

/* ================= TOTAL SALES ================= */
$total_sql = "
SELECT 
    COUNT(s.id) AS total_sales,
    SUM(s.total_amount) AS revenue,
    SUM(s.discount) AS discount,
    SUM(s.amount_paid) AS paid
FROM sales s
WHERE $where
";

$total = $pdo->query($total_sql)->fetch(PDO::FETCH_ASSOC);

/* ================= BEST SELLING ================= */
$best_sql = "
SELECT 
    p.product_name,
    SUM(si.quantity) AS qty,
    SUM(si.subtotal) AS total
FROM sale_items si
JOIN products p ON p.id = si.product_id
JOIN sales s ON s.id = si.sale_id
WHERE $where
GROUP BY si.product_id
ORDER BY qty DESC
LIMIT 10
";

$best_stmt = $pdo->query($best_sql);
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
<div class="container-fluid">

<h2>Sales Report</h2>

<!-- FILTER -->
<form method="GET">
    <select name="filter" onchange="this.form.submit()">
        <option value="daily" <?= $filter=='daily'?'selected':'' ?>>Daily</option>
        <option value="weekly" <?= $filter=='weekly'?'selected':'' ?>>Weekly</option>
        <option value="monthly" <?= $filter=='monthly'?'selected':'' ?>>Monthly</option>
    </select>
</form>

<br>

<!-- SUMMARY CARDS -->
<div>
    <p>Total Sales: <?= $total['total_sales'] ?? 0 ?></p>
    <p>Revenue: ₦<?= number_format($total['revenue'] ?? 0, 2) ?></p>
    <p>Discount: ₦<?= number_format($total['discount'] ?? 0, 2) ?></p>
    <p>Paid: ₦<?= number_format($total['paid'] ?? 0, 2) ?></p>
</div>

<hr>

<!-- BEST SELLING -->
<h3>Top Selling Products</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Product</th>
    <th>Qty Sold</th>
    <th>Total Sales</th>
</tr>

<?php while($row = $best_stmt->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
    <td><?= $row['product_name'] ?></td>
    <td><?= $row['qty'] ?></td>
    <td><?= number_format($row['total'], 2) ?></td>
</tr>
<?php endwhile; ?>

</table>

</div>
</div>

<?php require_once '../../includes/footer.php'; ?>