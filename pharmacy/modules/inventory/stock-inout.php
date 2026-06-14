<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$message = '';
$messageType = '';
$transactionType = $_GET['type'] ?? 'in';

// Get all products
$products = $pdo->query("SELECT * FROM products ORDER BY product_name ASC")
                ->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['submit_transaction'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $reference = trim($_POST['reference'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $transaction_type = $_POST['transaction_type'];

    // Get product info
    $productStmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $productStmt->execute([$product_id]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    if ($product && $quantity > 0) {
        try {
            $pdo->beginTransaction();

            // Calculate new quantity
            $oldQty = $product['quantity'];
            if ($transaction_type === 'out') {
                // Check if there's enough stock
                if ($oldQty < $quantity) {
                    throw new Exception("Insufficient stock. Available: {$oldQty} units");
                }
                $newQty = $oldQty - $quantity;
            } else {
                $newQty = $oldQty + $quantity;
            }

            // Update product quantity
            $updateStmt = $pdo->prepare("UPDATE products SET quantity = ? WHERE id = ?");
            $updateStmt->execute([$newQty, $product_id]);

            // Log the transaction
            $logStmt = $pdo->prepare("
                INSERT INTO stock_transactions (product_id, transaction_type, quantity, old_quantity, new_quantity, reference, notes, created_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $logStmt->execute([$product_id, $transaction_type, $quantity, $oldQty, $newQty, $reference, $notes, 1]);

            $pdo->commit();
            $message = "Stock {$transaction_type} transaction recorded successfully";
            $messageType = 'success';
        } catch(Exception $e) {
            $pdo->rollBack();
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'danger';
        }
    } else {
        $message = 'Please select a product and enter a valid quantity';
        $messageType = 'warning';
    }
}

// Get recent transactions
$recentTransactions = $pdo->query("
    SELECT st.*, p.product_name, p.manufacturer
    FROM stock_transactions st
    LEFT JOIN products p ON st.product_id = p.id
    ORDER BY st.created_at DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark">
                <i class="fas fa-exchange-alt me-2 text-primary"></i>Stock In/Out
            </h1>
            <p class="text-muted">Record incoming and outgoing inventory movements</p>
        </div>

        <!-- Alert Messages -->
        <?php if($message): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-2"></i>
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Transaction Form -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Record Stock Movement</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <!-- Transaction Type Tabs -->
                            <div class="btn-group w-100 mb-4" role="group">
                                <input type="radio" class="btn-check" name="transaction_type" id="stock_in" value="in" checked>
                                <label class="btn btn-outline-success flex-grow-1" for="stock_in">
                                    <i class="fas fa-arrow-down me-2"></i>Stock In
                                </label>

                                <input type="radio" class="btn-check" name="transaction_type" id="stock_out" value="out">
                                <label class="btn btn-outline-danger flex-grow-1" for="stock_out">
                                    <i class="fas fa-arrow-up me-2"></i>Stock Out
                                </label>
                            </div>

                            <!-- Product Selection -->
                            <div class="mb-3">
                                <label for="product_id" class="form-label">
                                    <strong>Product</strong>
                                </label>
                                <select id="product_id" name="product_id" class="form-select" required onchange="updateProductDetails()">
                                    <option value="">-- Select Product --</option>
                                    <?php foreach($products as $product): ?>
                                        <option value="<?= $product['id'] ?>" 
                                                data-current="<?= $product['quantity'] ?>"
                                                data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                                data-manufacturer="<?= htmlspecialchars($product['manufacturer']) ?>">
                                            <?= htmlspecialchars($product['product_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Current Stock -->
                            <div class="alert alert-info" id="stock-alert" style="display: none;">
                                <small>
                                    <strong>Current Stock:</strong> <span id="current-stock">0</span> units
                                </small>
                            </div>

                            <!-- Quantity -->
                            <div class="mb-3">
                                <label for="quantity" class="form-label">
                                    <strong>Quantity</strong>
                                </label>
                                <input type="number" id="quantity" name="quantity" class="form-control" 
                                       min="1" required placeholder="Enter quantity">
                            </div>

                            <!-- Reference -->
                            <div class="mb-3">
                                <label for="reference" class="form-label">
                                    <strong>Reference Number</strong>
                                </label>
                                <input type="text" id="reference" name="reference" class="form-control"
                                       placeholder="e.g., PO#12345, Invoice#XYZ">
                                <small class="text-muted">Optional reference for tracking</small>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label for="notes" class="form-label">
                                    <strong>Notes</strong>
                                </label>
                                <textarea id="notes" name="notes" class="form-control" rows="3"
                                         placeholder="Additional details..."></textarea>
                            </div>

                            <button type="submit" name="submit_transaction" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Record Transaction
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Summary & Help -->
            <div class="col-lg-6">
                <!-- Transaction Summary -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Transaction Summary</h5>
                    </div>
                    <div class="card-body">
                        <div id="transaction-summary">
                            <p class="text-muted">Select a product to see summary</p>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>Tips
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Stock In (Incoming):</strong>
                            <ul class="small mb-0">
                                <li>Purchase from suppliers</li>
                                <li>Returned items</li>
                                <li>Stock transfers</li>
                                <li>Corrections/adjustments</li>
                            </ul>
                        </div>
                        <div>
                            <strong>Stock Out (Outgoing):</strong>
                            <ul class="small mb-0">
                                <li>Sales/dispensing</li>
                                <li>Damage/spoilage</li>
                                <li>Theft/loss</li>
                                <li>Returns to supplier</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Recent Transactions</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentTransactions)): ?>
                    <p class="text-muted">No transactions recorded yet</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Reference</th>
                                    <th>New Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentTransactions as $txn): ?>
                                    <tr>
                                        <td>
                                            <small><?= date('M d, h:i A', strtotime($txn['created_at'])) ?></small>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($txn['product_name'] ?? 'Unknown') ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $txn['transaction_type'] === 'in' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($txn['transaction_type']) ?>
                                            </span>
                                        </td>
                                        <td><?= (int)$txn['quantity'] ?> units</td>
                                        <td>
                                            <small><?= htmlspecialchars($txn['reference'] ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <badge class="badge bg-info"><?= (int)$txn['new_quantity'] ?></badge>
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

<script>
function updateProductDetails() {
    const select = document.getElementById('product_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const currentStock = selectedOption.getAttribute('data-current');
        const productName = selectedOption.getAttribute('data-name');
        
        // Update stock alert
        document.getElementById('current-stock').textContent = currentStock;
        document.getElementById('stock-alert').style.display = 'block';
        
        // Update summary
        const transactionType = document.querySelector('input[name="transaction_type"]:checked').value;
        updateTransactionSummary(productName, currentStock, transactionType);
    } else {
        document.getElementById('stock-alert').style.display = 'none';
        document.getElementById('transaction-summary').innerHTML = '<p class="text-muted">Select a product to see summary</p>';
    }
}

function updateTransactionSummary(productName, currentStock, transactionType) {
    const quantity = parseInt(document.getElementById('quantity').value) || 0;
    let newStock = parseInt(currentStock);
    
    if (transactionType === 'out') {
        newStock -= quantity;
        if (newStock < 0) newStock = 0;
    } else {
        newStock += quantity;
    }
    
    const type = transactionType === 'in' ? 'Stock In' : 'Stock Out';
    const typeClass = transactionType === 'in' ? 'success' : 'danger';
    
    document.getElementById('transaction-summary').innerHTML = `
        <table class="table table-sm mb-0">
            <tr>
                <td>Product:</td>
                <td><strong>${productName}</strong></td>
            </tr>
            <tr>
                <td>Transaction:</td>
                <td><span class="badge bg-${typeClass}">${type}</span></td>
            </tr>
            <tr>
                <td>Current Stock:</td>
                <td><span class="badge bg-info">${currentStock} units</span></td>
            </tr>
            <tr>
                <td>Movement:</td>
                <td><span class="badge bg-warning">${transactionType === 'in' ? '+' : '-'}${quantity} units</span></td>
            </tr>
            <tr style="border-top: 2px solid #ddd;">
                <td><strong>New Stock:</strong></td>
                <td><strong><span class="badge bg-primary" style="font-size: 1rem;">${newStock} units</span></strong></td>
            </tr>
        </table>
    `;
}

// Update summary on quantity change
document.getElementById('quantity').addEventListener('change', function() {
    const select = document.getElementById('product_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const currentStock = selectedOption.getAttribute('data-current');
        const productName = selectedOption.getAttribute('data-name');
        const transactionType = document.querySelector('input[name="transaction_type"]:checked').value;
        updateTransactionSummary(productName, currentStock, transactionType);
    }
});

// Update summary on transaction type change
document.querySelectorAll('input[name="transaction_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const select = document.getElementById('product_id');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption.value) {
            const currentStock = selectedOption.getAttribute('data-current');
            const productName = selectedOption.getAttribute('data-name');
            updateTransactionSummary(productName, currentStock, this.value);
        }
    });
});
</script>

<?php require_once '../../includes/footer.php'; ?>