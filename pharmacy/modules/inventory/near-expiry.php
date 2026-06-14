<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// var_dump($_GET);

$days = isset($_GET['days']) ? (int)$_GET['days'] : 90;

$count_sql = "
SELECT COUNT(*) AS total
FROM products
WHERE expiry_date IS NOT NULL
AND expiry_date >= CURDATE()
AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL :days DAY)
";

$count_stmt = $pdo->prepare($count_sql);
$count_stmt->bindValue(':days', $days, PDO::PARAM_INT);
$count_stmt->execute();

$total_near_expiry = $count_stmt->fetchColumn();


$stmt = $pdo->prepare("
    SELECT *,
    DATEDIFF(expiry_date, CURDATE()) AS days_left
    FROM products
    WHERE expiry_date IS NOT NULL
    AND expiry_date >= CURDATE()
    AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL :days DAY)
    ORDER BY expiry_date ASC
");

$stmt->bindValue(':days', $days, PDO::PARAM_INT);
$stmt->execute();
?>
<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">

    <div class="mb-4">
            <h1 class="h3 fw-bold text-dark">
                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Expiry Alert
            </h1>
              <h3><?= $total_near_expiry ?></h3>
            <p class="text-muted">Products Near Expiry</p>
    </div>

    
<form method="GET">
    <select name="days" onchange="this.form.submit()">
        <option value="30" <?= ($days == 30) ? 'selected' : '' ?>>30 Days</option>
        <option value="60" <?= ($days == 60) ? 'selected' : '' ?>>60 Days</option>
        <option value="90" <?= ($days == 90) ? 'selected' : '' ?>>90 Days</option>
    </select>
</form>

<br>

<table class="table table-bordered table-striped">

<tr>
    <th>Barcode</th>
    <th>Product</th>
    <th>Batch No</th>
    <th>Expiry Date</th>
    <th>Quantity</th>
    <th>Days Left</th>
    <th>Status</th>
</tr>

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <tr>

    <td><?= $row['barcode'] ?></td>

    <td><?= $row['product_name'] ?></td>

    <td><?= $row['batch_number'] ?></td>

    <td><?= date('d-M-Y', strtotime($row['expiry_date'])) ?></td>

    <td><?= $row['quantity'] ?></td>

    <td><?= $row['days_left'] ?></td>

    <td>

        <?php
            $status = match(true) {
            $row['days_left'] <= 30 => "<span style='color:red;'>Critical</span>",
            $row['days_left'] <= 60 => "<span style='color:orange;'>Warning</span>",
            default => "<span style='color:blue;'>Monitor</span>",
            };
            echo $status;
        ?>

    </td>

</tr>

<?php endwhile; ?>

</table>

</div>
</div>
<?php require_once '../../includes/footer.php'; ?>
