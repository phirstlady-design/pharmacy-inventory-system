<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$sales = $pdo->query("
    SELECT s.*, 
    SUM((si.unit_price - p.cost_price) * si.quantity) AS profit
    FROM sales s
    JOIN sale_items si ON s.id = si.sale_id
    JOIN products p ON p.id = si.product_id
    GROUP BY s.id
    ORDER BY s.created_at DESC
")->fetchAll();
?>


<h2>Profit Report</h2>

<table border="1" width="100%">
<tr>
    <th>Invoice</th>
    <th>Total</th>
    <th>Profit</th>
    <th>Date</th>
</tr>

<?php foreach($sales as $s): ?>
<tr>
    <td><?= $s['invoice_no'] ?></td>
    <td><?= $s['total_amount'] ?></td>
    <td><?= number_format($s['profit'],2) ?></td>
    <td><?= $s['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>