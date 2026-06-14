
<?php
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$success = '';
$error = '';

$suppliers = $pdo->query("SELECT * FROM suppliers ORDER BY supplier_name ASC")
                 ->fetchAll(PDO::FETCH_ASSOC);

$products = $pdo->query("SELECT * FROM products ORDER BY product_name ASC")
                ->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['save'])) {

    $supplier_id = $_POST['supplier_id'];
    $invoice_number = trim($_POST['invoice_number']);
    $payment_status = $_POST['payment_status'];

    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['unit_price'];

    $total_amount = 0;

  foreach($quantities as $index => $qty) {

    $qty = (int)$qty;
    $price = (float)$prices[$index];

    if($qty > 0 && $price > 0) {

        $subtotal = $qty * $price;
        $total_amount += $subtotal;
    }
}

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO purchase_orders(
            supplier_id,
            invoice_number,
            total_amount,
            payment_status,
            created_by
        ) VALUES(?,?,?,?,?)");

        $stmt->execute([
            $supplier_id,
            $invoice_number,
            $total_amount,
            $payment_status,
            1
        ]);

        $purchase_id = $pdo->lastInsertId();

        foreach($product_ids as $index => $product_id) {

            $qty = (int)$quantities[$index];
                $price = (float)$prices[$index];

                if($qty <= 0 || $price <= 0) {
                    continue;
                }

                $subtotal = $qty * $price;

            $item = $pdo->prepare("INSERT INTO purchase_items(
                purchase_id,
                product_id,
                quantity,
                unit_price,
                subtotal
            ) VALUES(?,?,?,?,?)");

            $item->execute([
                $purchase_id,
                $product_id,
                $qty,
                $price,
                $subtotal
            ]);

            $updateStock = $pdo->prepare("UPDATE products
                SET quantity = quantity + ?
                WHERE id = ?");

            $updateStock->execute([$qty, $product_id]);
        }

        $pdo->commit();

        $success = 'Purchase added successfully';

    } catch(Exception $e) {

        $pdo->rollBack();
        $error = $e->getMessage();
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
                <i class="fas fa-shopping-cart me-2 text-primary"></i>Add Purchase Order
            </h1>
            <p class="text-muted">Create a new purchase order from suppliers</p>
        </div>

    <div class="card">

        <div class="card-header bg-light">
            <h5 class="mb-0">Purchase Order Details</h5>
        </div>

        <div class="card-body">

            <?php if($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" id="purchaseForm">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="supplier_id" class="form-label">
                            <strong>Supplier</strong>
                        </label>
                        <select id="supplier_id" name="supplier_id" class="form-select" required>
                            <option value="">-- Select Supplier --</option>
                            <?php foreach($suppliers as $supplier): ?>
                                <option value="<?= $supplier['id'] ?>">
                                    <?= htmlspecialchars($supplier['supplier_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="invoice_number" class="form-label">
                            <strong>Invoice Number</strong>
                        </label>
                        <input type="text" id="invoice_number" name="invoice_number" class="form-control" required 
                               placeholder="e.g., INV-2024-001">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="payment_status" class="form-label">
                            <strong>Payment Status</strong>
                        </label>
                        <select id="payment_status" name="payment_status" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="Paid">Paid</option>
                            <option value="Partial">Partial</option>
                        </select>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="mb-3">
                    <h5>Purchase Items</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 45%;">Product</th>
                                    <th style="width: 15%;">Quantity</th>
                                    <th style="width: 20%;">Unit Price (₦)</th>
                                    <th style="width: 15%;">Subtotal (₦)</th>
                                    <th style="width: 5%;">
                                        <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($i = 0; $i < 3; $i++): ?>
                                    <tr>
                                        <td>
                                            <select name="product_id[]" class="form-select product-select" onchange="updateSubtotal(this)">
                                                <option value="">-- Select Product --</option>
                                                <?php foreach($products as $product): ?>
                                                    <option value="<?= $product['id'] ?>">
                                                        <?= htmlspecialchars($product['product_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="quantity[]" class="form-control qty-input" 
                                                   min="1" onchange="updateSubtotal(this)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="unit_price[]" class="form-control price-input"
                                                   min="0" onchange="updateSubtotal(this)">
                                        </td>
                                        <td>
                                            <span class="subtotal">0.00</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                    <td>
                                        <strong>₦<span id="totalAmount">0.00</span></strong>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="save" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Purchase Order
                    </button>
                    <a href="purchase-orders.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>

            </form>

        </div>

    </div>

    </div>
</div>

<script>
function addRow() {
    const table = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
    const newRow = table.insertRow();
    
    const productOptions = `
        <option value="">-- Select Product --</option>
        <?php foreach($products as $product): ?>
            <option value="<?= $product['id'] ?>">
                <?= htmlspecialchars($product['product_name']) ?>
            </option>
        <?php endforeach; ?>
    `;
    
    newRow.innerHTML = `
        <td>
            <select name="product_id[]" class="form-select product-select" onchange="updateSubtotal(this)">
                ${productOptions}
            </select>
        </td>
        <td>
            <input type="number" name="quantity[]" class="form-control qty-input" 
                   min="1" onchange="updateSubtotal(this)">
        </td>
        <td>
            <input type="number" step="0.01" name="unit_price[]" class="form-control price-input"
                   min="0" onchange="updateSubtotal(this)">
        </td>
        <td>
            <span class="subtotal">0.00</span>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
}

function removeRow(button) {
    const table = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
    if (table.rows.length > 1) {
        button.closest('tr').remove();
        calculateTotal();
    } else {
        alert('At least one item is required');
    }
}

function updateSubtotal(element) {
    const row = element.closest('tr');
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const subtotal = qty * price;
    
    row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
        const subtotal = parseFloat(row.querySelector('.subtotal').textContent) || 0;
        total += subtotal;
    });
    
    document.getElementById('totalAmount').textContent = total.toFixed(2);
}
</script>

<!-- < ?php require_once '../footer.php'; ?> -->
<?php require_once '../../includes/footer.php'; ?>