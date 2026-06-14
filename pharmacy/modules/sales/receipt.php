<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$saleId = intval($_GET['sale_id'] ?? 0);

if ($saleId <= 0) {
    die('Invalid sale ID');
}

$stmt = $pdo->prepare("SELECT * FROM sales WHERE id = ?");
$stmt->execute([$saleId]);
$sale = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sale) {
    die('Sale not found');
}

$itemsStmt = $pdo->prepare("
    SELECT si.*, p.product_name
    FROM sale_items si
    JOIN products p ON p.id = si.product_id
    WHERE si.sale_id = ?
");
$itemsStmt->execute([$saleId]);
$items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once '../../includes/header.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipt - <?= htmlspecialchars($sale['invoice_no']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .receipt-container {
                width: 280px;
                margin: 0 auto;
            }
        }

        .receipt-container {
            font-family: 'Courier New', monospace;
            width: 280px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            background: white;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .receipt-header h3 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .receipt-header p {
            margin: 3px 0;
            font-size: 11px;
        }

        .receipt-line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .receipt-items {
            font-size: 11px;
            margin-bottom: 10px;
        }

        .receipt-items table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-items td {
            padding: 4px 0;
        }

        .item-name {
            font-weight: bold;
        }

        .item-qty {
            text-align: right;
        }

        .item-price {
            text-align: right;
        }

        .receipt-total {
            text-align: right;
            font-size: 13px;
            font-weight: bold;
            margin: 10px 0;
        }

        .receipt-footer {
            text-align: center;
            font-size: 10px;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .buttons {
            text-align: center;
            margin-top: 20px;
            gap: 10px;
        }

        .btn-group-custom {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>

<body class="bg-light">

<div class="receipt-container">
    <div class="receipt-header">
        <h3>Pharmacy</h3>
        <p>Osun State, Nigeria</p>
        <p>Tel: 08065182627</p>
    </div>

    <div class="receipt-line"></div>

    <div style="font-size: 11px; margin-bottom: 10px;">
        <div>Invoice: <strong><?= htmlspecialchars($sale['invoice_no']) ?></strong></div>
        <div>Date: <?= date('d M Y H:i', strtotime($sale['created_at'])) ?></div>
        <div>Payment: <?= htmlspecialchars($sale['payment_method']) ?></div>
    </div>

    <div class="receipt-line"></div>

    <div class="receipt-items">
        <table>
            <thead>
                <tr style="border-bottom: 1px solid #000;">
                    <th style="text-align: left; font-size: 10px;">ITEM</th>
                    <th style="text-align: center; font-size: 10px;">QTY</th>
                    <th style="text-align: right; font-size: 10px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $item): ?>
                    <tr>
                        <td class="item-name"><?= htmlspecialchars($item['product_name']) ?></td>
                        <td class="item-qty"><?= (int)$item['quantity'] ?></td>
                        <td class="item-price">₦<?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-size: 9px; padding-left: 10px;">
                            @ ₦<?= number_format($item['unit_price'], 2) ?> each
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="receipt-line"></div>

    <div class="receipt-total">
        TOTAL: ₦<?= number_format($sale['total_amount'], 2) ?>
    </div>

    <?php if ($sale['amount_paid'] && $sale['change_amount']): ?>
        <div style="font-size: 11px; text-align: right;">
            <div>Amount Paid: ₦<?= number_format($sale['amount_paid'], 2) ?></div>
            <div>Change: ₦<?= number_format($sale['change_amount'], 2) ?></div>
        </div>
    <?php endif; ?>

    <div class="receipt-line"></div>

    <div class="receipt-footer">
        <p style="margin-bottom: 5px;"><strong>Thank you for your purchase!</strong></p>
        <p>Please retain this receipt</p>
        <p style="font-size: 9px; margin-top: 10px;"><?= date('d M Y H:i:s') ?></p>
    </div>
</div>

<div class="btn-group-custom no-print">
    <button class="btn btn-primary" onclick="window.print()">
        <i class="fas fa-print me-2"></i>Print Receipt
    </button>
    <a href="pos.php" class="btn btn-success">
        <i class="fas fa-cash-register me-2"></i>New Sale
    </a>
    <a href="sales-history.php" class="btn btn-outline-secondary">
        <i class="fas fa-history me-2"></i>Sales History
    </a>
</div>



<script>
// Auto-open print dialog on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        window.print();
    }, 500);
});
</script>

<?php require_once '../../includes/footer.php'; ?>

</body>
</html>
