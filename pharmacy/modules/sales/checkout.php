<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$cart = $_SESSION['cart'] ?? [];
$error = '';

$total = 0;
$items = [];

// Prepare cart items
if (!empty($cart)) {

    foreach($cart as $productId => $qty) {

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$productId]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            continue;
        }

        $subtotal = $product['selling_price'] * $qty;

        $total += $subtotal;

        $items[] = [
            'product' => $product,
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
    }

} else {

    $error = 'Cart is empty';
}


// Handle checkout form submission
if(isset($_POST['process_payment']) && !empty($cart)) {

    $paymentMethod = $_POST['payment_method'] ?? 'cash';
    $amountPaid = floatval($_POST['amount_paid'] ?? 0);

    // Validate payment
    if ($amountPaid < $total) {

        $error = 'Insufficient payment amount. Total required: ₦' . number_format($total, 2);

    } else {

        try {

            $pdo->beginTransaction();

            // Generate invoice number
            $invoice = "INV-" . date('Ymd') . "-" . rand(1000,9999);

            // Insert sale
           $stmt = $pdo->prepare("
                INSERT INTO sales (
                    invoice_no,
                    total_amount,
                    payment_method,
                    payment_status,
                    amount_paid,
                    change_amount,
                    created_at
                ) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                $invoice,
                $total,
                $paymentMethod,
                'paid',
                $amountPaid,
                ($amountPaid - $total)
            ]);

            $sale_id = $pdo->lastInsertId();

            // Insert sale items
            foreach($items as $item) {

                $product = $item['product'];
                $qty = $item['qty'];
                $subtotal = $item['subtotal'];

                // Save sale item
                $itemStmt = $pdo->prepare("
                    INSERT INTO sale_items (
                        sale_id,
                        product_id,
                        quantity,
                        unit_price,
                        subtotal
                    )
                    VALUES (?, ?, ?, ?, ?)
                ");

                $itemStmt->execute([
                    $sale_id,
                    $product['id'],
                    $qty,
                    $product['selling_price'],
                    $subtotal
                ]);

                // Reduce stock
                $updateStmt = $pdo->prepare("
                    UPDATE products
                    SET quantity = quantity - ?
                    WHERE id = ?
                ");

                $updateStmt->execute([
                    $qty,
                    $product['id']
                ]);
            }

            $pdo->commit();

            // Clear cart
            unset($_SESSION['cart']);

            // Redirect to receipt
            header("Location: receipt.php?sale_id=" . $sale_id);
            exit();

        } catch(Exception $e) {

            $pdo->rollBack();

            $error = 'Error processing payment: ' . $e->getMessage();
        }
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
                <i class="fas fa-credit-card me-2 text-primary"></i>Checkout
            </h1>
            <p class="text-muted">Complete your purchase</p>
        </div>

        <!-- Error Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($cart)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Your cart is empty. <a href="pos.php">Go back to POS</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <!-- Order Summary -->
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($items as $item): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($item['product']['product_name']) ?></strong></td>
                                                <td><?= (int)$item['qty'] ?></td>
                                                <td>₦<?= number_format($item['product']['selling_price'], 2) ?></td>
                                                <td>₦<?= number_format($item['subtotal'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td><h5 class="mb-0 text-primary">₦<?= number_format($total, 2) ?></h5></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Payment Details</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label"><strong>Payment Method</strong></label>
                                    <select id="payment_method" name="payment_method" class="form-select" onchange="updatePaymentFields()">
                                        <option value="cash">Cash</option>
                                        <option value="pos">POS</option>
                                        <option value="transfer">Bank Transfer</option>
                                        <option value="wallet">Wallet</option>
                                        <option value="insurance">NHIA</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="amount_paid" class="form-label"><strong>Amount Received</strong></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">₦</span>
                                        <input type="number" id="amount_paid" name="amount_paid" class="form-control" 
                                               step="0.01" value="<?= $total ?>" onchange="calculateChange()" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="change" class="form-label"><strong>Change</strong></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">₦</span>
                                        <input type="text" id="change" class="form-control" readonly 
                                               value="<?= number_format($total - $total, 2) ?>">
                                    </div>
                                </div>

                                <hr>

                                <div class="d-grid gap-2">
                                    <button type="submit" name="process_payment" class="btn btn-success btn-lg">
                                        <i class="fas fa-check-circle me-2"></i>Process Payment
                                    </button>
                                    <a href="pos.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to POS
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Total Summary -->
                    <div class="card mt-3">
                        <div class="card-body text-center">
                            <p class="text-muted mb-2">Total Amount Due</p>
                            <h2 class="text-success">₦<?= number_format($total, 2) ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
function calculateChange() {
    const totalAmount = <?= $total ?>;
    const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
    const change = amountPaid - totalAmount;
    
    document.getElementById('change').value = change.toFixed(2);
    
    // Color code the change field
    if (change < 0) {
        document.getElementById('change').style.color = 'red';
    } else {
        document.getElementById('change').style.color = 'green';
    }
}

function updatePaymentFields() {
    const method = document.getElementById('payment_method').value;
    // Can be extended to show different fields based on payment method
}
</script>

<?php require_once '../../includes/footer.php'; ?>