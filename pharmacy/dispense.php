<?php
include("include/connect.php"); // Assume this connects using mysqli and provides $mysqli

// if (!isset($mysqli)) {
//     die("Error: Database connection (\$mysqli) not initialized.");
// }

$message = '';
$error = '';
$receipt_data = null;



$medicines = [];
$result = $mysqli->query("SELECT * FROM medicines WHERE quantity > 0 ORDER BY name");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $medicines[] = $row;
    }
    $result->free();
} else {
    die("Database query error: " . $mysqli->error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'] ?? '';
    $customer_phone = $_POST['customer_phone'] ?? '';
    $prescription_number = $_POST['prescription_number'] ?? '';
    $dispensed_items = $_POST['items'] ?? [];

    // Begin transaction
    $mysqli->begin_transaction();

    try {
        $total_amount = 0;
        $receipt_items = [];

        // Generate receipt number (you can improve this later)
        $receipt_number = 'RCP' . date('Ymd') . sprintf('%04d', rand(1, 9999));

        // Insert sale record with total_amount=0 initially
        $stmt = $mysqli->prepare("INSERT INTO sales (receipt_number, customer_name, customer_phone, prescription_number, total_amount, sale_date) VALUES (?, ?, ?, ?, 0, NOW())");
        if (!$stmt) throw new Exception("Prepare failed: " . $mysqli->error);
        $stmt->bind_param('ssss', $receipt_number, $customer_name, $customer_phone, $prescription_number);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
        $sale_id = $mysqli->insert_id;
        $stmt->close();

        foreach ($dispensed_items as $item) {
            $medicine_id = (int)($item['medicine_id'] ?? 0);
            $quantity = (int)($item['quantity'] ?? 0);

            if ($medicine_id > 0 && $quantity > 0) {
                // Get medicine details
                $stmt = $mysqli->prepare("SELECT * FROM medicines WHERE id = ?");
                if (!$stmt) throw new Exception("Prepare failed: " . $mysqli->error);
                $stmt->bind_param('i', $medicine_id);
                if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
                $res = $stmt->get_result();
                $medicine = $res->fetch_assoc();
                $stmt->close();

                if (!$medicine) {
                    throw new Exception("Medicine not found");
                }

                if ($medicine['quantity'] < $quantity) {
                    throw new Exception("Insufficient stock for " . $medicine['name']);
                }

                $item_total = $medicine['price'] * $quantity;
                $total_amount += $item_total;

                // Insert sale item
                $stmt = $mysqli->prepare("INSERT INTO sale_items (sale_id, medicine_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
                if (!$stmt) throw new Exception("Prepare failed: " . $mysqli->error);
                $stmt->bind_param('iiidd', $sale_id, $medicine_id, $quantity, $medicine['price'], $item_total);
                if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
                $stmt->close();

                // Update stock
                $stmt = $mysqli->prepare("UPDATE medicines SET quantity = quantity - ? WHERE id = ?");
                if (!$stmt) throw new Exception("Prepare failed: " . $mysqli->error);
                $stmt->bind_param('ii', $quantity, $medicine_id);
                if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
                $stmt->close();

                $receipt_items[] = [
                    'name' => $medicine['name'],
                    'dosage' => $medicine['dosage'],
                    'quantity' => $quantity,
                    'unit_price' => $medicine['price'],
                    'total_price' => $item_total
                ];
            }
        }

        // Update total amount in sales
        $stmt = $mysqli->prepare("UPDATE sales SET total_amount = ? WHERE id = ?");
        if (!$stmt) throw new Exception("Prepare failed: " . $mysqli->error);
        $stmt->bind_param('di', $total_amount, $sale_id);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
        $stmt->close();

        $mysqli->commit();

        $receipt_data = [
            'receipt_number' => $receipt_number,
            'customer_name' => $customer_name,
            'customer_phone' => $customer_phone,
            'prescription_number' => $prescription_number,
            'items' => $receipt_items,
            'total_amount' => $total_amount,
            'sale_date' => date('Y-m-d H:i:s')
        ];

        $message = "Drugs dispensed successfully! Receipt generated.";

    } catch (Exception $e) {
        $mysqli->rollback();
        $error = "Error dispensing drugs: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispense Drugs - Pharmacy System</title>
    <link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-pills"></i>
                <h2>OAUTHC Pharmacy Inventory Management</h2>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="index.php">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="inventory.php">
                        <i class="fas fa-boxes"></i>
                        Inventory
                    </a>
                </li>
                <li class="active">
                    <a href="dispense.php">
                        <i class="fas fa-hand-holding-medical"></i>
                        Dispense Drugs
                    </a>
                </li>
                <li>
                    <a href="stock-taking.php">
                        <i class="fas fa-clipboard-list"></i>
                        Stock Taking
                    </a>
                </li>
                <li>
                    <a href="alerts.php">
                        <i class="fas fa-exclamation-triangle"></i>
                        Alerts
                    </a>
                </li>
                <li>
                    <a href="reports.php">
                        <i class="fas fa-chart-bar"></i>
                        Reports
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1>Dispense Drugs</h1>
                <div class="header-actions">
                    <a href="sales-history.php" class="btn btn-secondary">
                        <i class="fas fa-history"></i>
                        Sales History
                    </a>
                </div>
            </header>

            <?php if ($message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="dispense-container">
                <!-- Dispensing Form -->
                <div class="card dispense-form-card">
                    <div class="card-header">
                        <h3>New Prescription</h3>
                    </div>
                    <form method="POST" id="dispenseForm" class="dispense-form">
                        <div class="customer-info">
                            <div class="form-group">
                                <label for="customer_name">Customer Name *</label>
                                <input type="text" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="form-group">
                                <label for="customer_phone">Phone Number</label>
                                <input type="tel" id="customer_phone" name="customer_phone">
                            </div>
                            <div class="form-group">
                                <label for="prescription_number">Prescription Number</label>
                                <input type="text" id="prescription_number" name="prescription_number">
                            </div>
                        </div>

                        <div class="items-section">
                            <div class="section-header">
                                <h4>Prescription Items</h4>
                                <button type="button" onclick="addItem()" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Add Item
                                </button>
                            </div>
                            
                            <div id="items-container">
                                <div class="item-row">
                                    <select name="items[0][medicine_id]" class="medicine-select" onchange="updatePrice(this, 0)">
                                        <option value="">Select Medicine</option>
                                        <?php foreach ($medicines as $medicine): ?>
                                        <option value="<?php echo $medicine['id']; ?>" 
                                                data-price="<?php echo $medicine['price']; ?>"
                                                data-stock="<?php echo $medicine['quantity']; ?>"
                                                data-name="<?php echo htmlspecialchars($medicine['name']); ?>">
                                            <?php echo htmlspecialchars($medicine['name']); ?> - 
                                            <?php echo htmlspecialchars($medicine['dosage']); ?> 
                                            (Stock: <?php echo $medicine['quantity']; ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" class="quantity-input" onchange="calculateTotal(0)">
                                    <span class="unit-price">$0.00</span>
                                    <span class="item-total">$0.00</span>
                                    <button type="button" onclick="removeItem(this)" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="total-section">
                            <div class="total-display">
                                <strong>Total Amount: $<span id="grandTotal">0.00</span></strong>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-hand-holding-medical"></i>
                                Dispense & Generate Receipt
                            </button>
                            <button type="reset" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-undo"></i>
                                Reset
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Receipt Preview -->
                <?php if ($receipt_data): ?>
                <div class="card receipt-card">
                    <div class="card-header">
                        <h3>Receipt Generated</h3>
                        <button onclick="printReceipt()" class="btn btn-sm btn-secondary">
                            <i class="fas fa-print"></i>
                            Print
                        </button>
                    </div>
                    <div id="receipt-content" class="receipt-content">
                        <div class="receipt-header">
                            <h2>PharmaCare Pharmacy</h2>
                            <p>123 Health Street, Medical City</p>
                            <p>Phone: (555) 123-4567</p>
                            <hr>
                        </div>
                        
                        <div class="receipt-info">
                            <p><strong>Receipt #:</strong> <?php echo $receipt_data['receipt_number']; ?></p>
                            <p><strong>Date:</strong> <?php echo date('M d, Y H:i', strtotime($receipt_data['sale_date'])); ?></p>
                            <p><strong>Customer:</strong> <?php echo htmlspecialchars($receipt_data['customer_name']); ?></p>
                            <?php if ($receipt_data['customer_phone']): ?>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($receipt_data['customer_phone']); ?></p>
                            <?php endif; ?>
                            <?php if ($receipt_data['prescription_number']): ?>
                            <p><strong>Prescription #:</strong> <?php echo htmlspecialchars($receipt_data['prescription_number']); ?></p>
                            <?php endif; ?>
                            <hr>
                        </div>
                        
                        <div class="receipt-items">
                            <table class="receipt-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($receipt_data['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($item['name']); ?>
                                            <?php if ($item['dosage']): ?>
                                            <br><small><?php echo htmlspecialchars($item['dosage']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                                        <td>$<?php echo number_format($item['total_price'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <hr>
                            <div class="receipt-total">
                                <h3>Total: $<?php echo number_format($receipt_data['total_amount'], 2); ?></h3>
                            </div>
                        </div>
                        
                        <div class="receipt-footer">
                            <p>Thank you for choosing PharmaCare!</p>
                            <p><small>Please keep this receipt for your records</small></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
    <script>
        let itemCount = 1;

        function addItem() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'item-row';
            newRow.innerHTML = `
                <select name="items[${itemCount}][medicine_id]" class="medicine-select" onchange="updatePrice(this, ${itemCount})">
                    <option value="">Select Medicine</option>
                    <?php foreach ($medicines as $medicine): ?>
                    <option value="<?php echo $medicine['id']; ?>" 
                            data-price="<?php echo $medicine['price']; ?>"
                            data-stock="<?php echo $medicine['quantity']; ?>"
                            data-name="<?php echo htmlspecialchars($medicine['name']); ?>">
                        <?php echo htmlspecialchars($medicine['name']); ?> - 
                        <?php echo htmlspecialchars($medicine['dosage']); ?> 
                        (Stock: <?php echo $medicine['quantity']; ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="items[${itemCount}][quantity]" placeholder="Qty" min="1" class="quantity-input" onchange="calculateTotal(${itemCount})">
                <span class="unit-price">$0.00</span>
                <span class="item-total">$0.00</span>
                <button type="button" onclick="removeItem(this)" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newRow);
            itemCount++;
        }

        function removeItem(button) {
            button.parentElement.remove();
            calculateGrandTotal();
        }

        function updatePrice(select, index) {
            const option = select.selectedOptions[0];
            const price = option.dataset.price || 0;
            const stock = option.dataset.stock || 0;
            
            const row = select.parentElement;
            const priceSpan = row.querySelector('.unit-price');
            const quantityInput = row.querySelector('.quantity-input');
            
            priceSpan.textContent = '$' + parseFloat(price).toFixed(2);
            quantityInput.max = stock;
            
            calculateTotal(index);
        }

        function calculateTotal(index) {
            const rows = document.querySelectorAll('.item-row');
            const row = rows[index];
            
            if (!row) return;
            
            const select = row.querySelector('.medicine-select');
            const quantityInput = row.querySelector('.quantity-input');
            const totalSpan = row.querySelector('.item-total');
            
            const option = select.selectedOptions[0];
            const price = parseFloat(option.dataset.price || 0);
            const quantity = parseInt(quantityInput.value || 0);
            const stock = parseInt(option.dataset.stock || 0);
            
            if (quantity > stock) {
                alert(`Only ${stock} units available for ${option.dataset.name}`);
                quantityInput.value = stock;
                return;
            }
            
            const total = price * quantity;
            totalSpan.textContent = '$' + total.toFixed(2);
            
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            const totalSpans = document.querySelectorAll('.item-total');
            let grandTotal = 0;
            
            totalSpans.forEach(span => {
                const value = parseFloat(span.textContent.replace('$', '')) || 0;
                grandTotal += value;
            });
            
            document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
        }

        function resetForm() {
            document.getElementById('dispenseForm').reset();
            const container = document.getElementById('items-container');
            container.innerHTML = `
                <div class="item-row">
                    <select name="items[0][medicine_id]" class="medicine-select" onchange="updatePrice(this, 0)">
                        <option value="">Select Medicine</option>
                        <?php foreach ($medicines as $medicine): ?>
                        <option value="<?php echo $medicine['id']; ?>" 
                                data-price="<?php echo $medicine['price']; ?>"
                                data-stock="<?php echo $medicine['quantity']; ?>"
                                data-name="<?php echo htmlspecialchars($medicine['name']); ?>">
                            <?php echo htmlspecialchars($medicine['name']); ?> - 
                            <?php echo htmlspecialchars($medicine['dosage']); ?> 
                            (Stock: <?php echo $medicine['quantity']; ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" class="quantity-input" onchange="calculateTotal(0)">
                    <span class="unit-price">$0.00</span>
                    <span class="item-total">$0.00</span>
                    <button type="button" onclick="removeItem(this)" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            itemCount = 1;
            calculateGrandTotal();
        }

        function printReceipt() {
            const receiptContent = document.getElementById('receipt-content').innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Receipt</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .receipt-header { text-align: center; margin-bottom: 20px; }
                        .receipt-table { width: 100%; border-collapse: collapse; }
                        .receipt-table th, .receipt-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        .receipt-total { text-align: right; margin-top: 10px; }
                        .receipt-footer { text-align: center; margin-top: 20px; }
                        hr { border: 1px solid #ddd; }
                    </style>
                </head>
                <body>${receiptContent}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>
</html>