<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';


$sql = "
SELECT *
FROM products
WHERE expiry_date IS NOT NULL
AND expiry_date < CURDATE()
ORDER BY expiry_date DESC
";

$stmt = $pdo->query($sql);
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="container-fluid">

    <div class="mb-4">
            <h1 class="h3 fw-bold text-dark">
                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Products Already Expired
            </h1>
            <p class="text-muted">Expired Products</p>
    </div>


<table class="table table-bordered table-striped">

<tr>
    <th>Barcode</th>
    <th>Product</th>
    <th>Batch No</th>
    <th>Expiry Date</th>
    <th>Quantity</th>
    <th>Cost Price</th>
    <th>Estimated Loss</th>
    <th>Status</th>
</tr>

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
<?php
$loss = $row['quantity'] * $row['cost_price'];
?>

<tr>

    <td><?= $row['barcode'] ?></td>

    <td><?= $row['product_name'] ?></td>

    <td><?= $row['batch_number'] ?></td>

    <td><?= date('d-M-Y', strtotime($row['expiry_date'])) ?></td>

    <td><?= $row['quantity'] ?></td>

    <td>₦<?= number_format($row['cost_price'], 2) ?></td>

    <td style="color:red;">
        ₦<?= number_format($loss, 2) ?>
    </td>

    <td>
        <span style="color:red;font-weight:bold;">
            Expired
        </span>
    </td>

</tr>

<?php endwhile; ?>

</table>
</div>
</div>
<?php require_once '../../includes/footer.php'; ?>
