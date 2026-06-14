<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';
session_start();

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<table class="table table-sm">

<?php foreach($cart as $id=>$qty):

$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if(!$p) continue;

$subtotal = $p['selling_price'] * $qty;
$total += $subtotal;
?>

<tr>
    <td><?= $p['product_name'] ?></td>
    <td><?= $qty ?></td>
    <td>
        <a href="#" onclick="removeItem(<?= $id ?>)">X</a>
    </td>
</tr>

<?php endforeach; ?>

</table>

<h4>Total: ₦<?= number_format($total,2) ?></h4>