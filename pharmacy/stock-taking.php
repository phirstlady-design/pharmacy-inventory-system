<?php
include("include/connect.php");

$message = '';
$error = '';

// Get stock taking data
$medicines = $pdo->query("SELECT * FROM medicines ORDER BY category, name")->fetchAll();

// Calculate stock values
$total_stock_value = 0;
$low_stock_count = 0;
$expired_count = 0;
$categories = [];

foreach ($medicines as $medicine) {
    $stock_value = $medicine['quantity'] * $medicine['price'];
    $total_stock_value += $stock_value;
    
    if ($medicine['quantity'] <= $medicine['reorder_level']) {
        $low_stock_count++;
    }
    
    if (strtotime($medicine['expiry_date']) <= time()) {
        $expired_count++;
    }
    
    if (!isset($categories[$medicine['category']])) {
        $categories[$medicine['category']] = [
            'count' => 0,
            'value' => 0,
            'quantity' => 0
        ];
    }
    
    $categories[$medicine['category']]['count']++;
    $categories[$medicine['category']]['value'] += $stock_value;
    $categories[$medicine['category']]['quantity'] += $medicine['quantity'];
}

// Handle stock adjustment
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'adjust_stock') {
    $medicine_id = $_POST['medicine_id'];
    $new_quantity = $_POST['new_quantity'];
    $reason = $_POST['reason'];
    
    try {
        // Get current quantity
        $stmt = $pdo->prepare("SELECT quantity, name FROM medicines WHERE id = ?");
        $stmt->execute([$medicine_id]);
        $medicine = $stmt->fetch();
        
        if ($medicine) {
            $old_quantity = $medicine['quantity'];
            $adjustment = $new_quantity - $old_quantity;
            
            // Update stock
            $update_stmt = $pdo->prepare("UPDATE medicines SET quantity = ? WHERE id = ?");
            $update_stmt->execute([$new_quantity, $medicine_id]);
            
            // Log the adjustment
            $log_stmt = $pdo->prepare("INSERT INTO stock_adjustments (medicine_id, old_quantity, new_quantity, adjustment, reason, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $log_stmt->execute([$medicine_id, $old_quantity, $new_quantity, $adjustment, $reason]);
            
            $message = "Stock adjusted successfully for " . $medicine['name'];
            
            // Refresh data
            header("Location: stock-taking.php?success=1");
            exit;
        }
    } catch (Exception $e) {
        $error = "Error adjusting stock: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Taking - Pharmacy System</title>
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
                <h2>PharmaCare</h2>
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
                <li>
                    <a href="dispense.php">
                        <i class="fas fa-hand-holding-medical"></i>
                        Dispense Drugs
                    </a>
                </li>
                <li class="active">
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
                <h1>Stock Taking & Valuation</h1>
                <div class="header-actions">
                    <button onclick="exportStockReport()" class="btn btn-secondary">
                        <i class="fas fa-download"></i>
                        Export Report
                    </button>
                    <button onclick="printStockReport()" class="btn btn-secondary">
                        <i class="fas fa-print"></i>
                        Print Report
                    </button>
                </div>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Stock adjustment completed successfully!
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Stock Summary -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>$<?php echo number_format($total_stock_value, 2); ?></h3>
                        <p>Total Stock Value</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo count($medicines); ?></h3>
                        <p>Total Items</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $low_stock_count; ?></h3>
                        <p>Low Stock Items</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $expired_count; ?></h3>
                        <p>Expired Items</p>
                    </div>
                </div>
            </div>

            <!-- Category Breakdown -->
            <div class="card">
                <div class="card-header">
                    <h3>Stock by Category</h3>
                </div>
                <div class="category-grid">
                    <?php foreach ($categories as $category => $data): ?>
                    <div class="category-card">
                        <h4><?php echo htmlspecialchars($category); ?></h4>
                        <div class="category-stats">
                            <p><strong><?php echo $data['count']; ?></strong> items</p>
                            <p><strong><?php echo $data['quantity']; ?></strong> units</p>
                            <p><strong>$<?php echo number_format($data['value'], 2); ?></strong> value</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Stock Details -->
            <div class="card">
                <div class="card-header">
                    <h3>Detailed Stock Report</h3>
                    <div class="filter-controls">
                        <input type="text" id="stockSearch" placeholder="Search medicines..." onkeyup="filterStock()">
                        <select id="categoryFilter" onchange="filterStock()">
                            <option value="">All Categories</option>
                            <?php foreach (array_keys($categories) as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="table-container">
                    <table id="stockTable" class="stock-table">
                        <thead>
                            <tr>
                                <th>Medicine Name</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Reorder Level</th>
                                <th>Unit Price</th>
                                <th>Stock Value</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medicines as $medicine): ?>
                            <?php
                            $stock_value = $medicine['quantity'] * $medicine['price'];
                            $is_low_stock = $medicine['quantity'] <= $medicine['reorder_level'];
                            $is_expired = strtotime($medicine['expiry_date']) <= time();
                            $expires_soon = strtotime($medicine['expiry_date']) <= strtotime('+30 days');
                            ?>
                            <tr data-category="<?php echo htmlspecialchars($medicine['category']); ?>">
                                <td>
                                    <div class="medicine-info">
                                        <strong><?php echo htmlspecialchars($medicine['name']); ?></strong>
                                        <small><?php echo htmlspecialchars($medicine['dosage']); ?></small>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($medicine['category']); ?></td>
                                <td>
                                    <span class="quantity <?php echo $is_low_stock ? 'low-stock' : ''; ?>">
                                        <?php echo $medicine['quantity']; ?>
                                    </span>
                                </td>
                                <td><?php echo $medicine['reorder_level']; ?></td>
                                <td>$<?php echo number_format($medicine['price'], 2); ?></td>
                                <td><strong>$<?php echo number_format($stock_value, 2); ?></strong></td>
                                <td>
                                    <span class="expiry-date <?php echo $is_expired ? 'expired' : ($expires_soon ? 'expires-soon' : ''); ?>">
                                        <?php echo date('M d, Y', strtotime($medicine['expiry_date'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($is_expired): ?>
                                        <span class="status-badge expired">Expired</span>
                                    <?php elseif ($is_low_stock): ?>
                                        <span class="status-badge low">Low Stock</span>
                                    <?php elseif ($expires_soon): ?>
                                        <span class="status-badge warning">Expires Soon</span>
                                    <?php else: ?>
                                        <span class="status-badge active">Good</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button onclick="adjustStock(<?php echo $medicine['id']; ?>, '<?php echo htmlspecialchars($medicine['name']); ?>', <?php echo $medicine['quantity']; ?>)" 
                                            class="btn btn-sm btn-secondary">
                                        <i class="fas fa-edit"></i>
                                        Adjust
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Stock Adjustment Modal -->
    <div id="adjustModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Adjust Stock</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form method="POST" class="modal-form">
                <input type="hidden" name="action" value="adjust_stock">
                <input type="hidden" name="medicine_id" id="adjustMedicineId">
                
                <div class="form-group">
                    <label>Medicine:</label>
                    <span id="adjustMedicineName" class="form-value"></span>
                </div>
                
                <div class="form-group">
                    <label>Current Quantity:</label>
                    <span id="adjustCurrentQty" class="form-value"></span>
                </div>
                
                <div class="form-group">
                    <label for="new_quantity">New Quantity *</label>
                    <input type="number" id="new_quantity" name="new_quantity" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="reason">Reason for Adjustment *</label>
                    <select id="reason" name="reason" required>
                        <option value="">Select Reason</option>
                        <option value="Physical Count Correction">Physical Count Correction</option>
                        <option value="Damaged Goods">Damaged Goods</option>
                        <option value="Expired Items">Expired Items</option>
                        <option value="Theft/Loss">Theft/Loss</option>
                        <option value="Supplier Return">Supplier Return</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Adjust Stock
                    </button>
                    <button type="button" onclick="closeModal()" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        function filterStock() {
            const searchTerm = document.getElementById('stockSearch').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const rows = document.querySelectorAll('#stockTable tbody tr');
            
            rows.forEach(row => {
                const medicineName = row.querySelector('.medicine-info strong').textContent.toLowerCase();
                const category = row.dataset.category;
                
                const matchesSearch = medicineName.includes(searchTerm);
                const matchesCategory = !categoryFilter || category === categoryFilter;
                
                row.style.display = matchesSearch && matchesCategory ? '' : 'none';
            });
        }

        function adjustStock(medicineId, medicineName, currentQty) {
            document.getElementById('adjustMedicineId').value = medicineId;
            document.getElementById('adjustMedicineName').textContent = medicineName;
            document.getElementById('adjustCurrentQty').textContent = currentQty;
            document.getElementById('new_quantity').value = currentQty;
            document.getElementById('adjustModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('adjustModal').style.display = 'none';
        }

        function exportStockReport() {
            const table = document.getElementById('stockTable');
            let csv = [];
            
            // Headers
            const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
            csv.push(headers.slice(0, -1).join(',')); // Exclude Actions column
            
            // Data rows
            const rows = Array.from(table.querySelectorAll('tbody tr')).filter(row => row.style.display !== 'none');
            rows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td')).slice(0, -1); // Exclude Actions column
                const rowData = cells.map(cell => {
                    let text = cell.textContent.trim();
                    // Clean up the text
                    text = text.replace(/\s+/g, ' ');
                    return '"' + text.replace(/"/g, '""') + '"';
                });
                csv.push(rowData.join(','));
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = 'stock_report_' + new Date().toISOString().split('T')[0] + '.csv';
            a.click();
            
            window.URL.revokeObjectURL(url);
        }

        function printStockReport() {
            const printContent = `
                <html>
                <head>
                    <title>Stock Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { text-align: center; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                        th { background-color: #f2f2f2; }
                        .low-stock { color: #e53e3e; font-weight: bold; }
                        .expired { color: #c53030; font-weight: bold; }
                        .expires-soon { color: #d69e2e; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <h1>PharmaCare - Stock Report</h1>
                    <p>Generated on: ${new Date().toLocaleDateString()}</p>
                    <p>Total Stock Value: $${document.querySelector('.stat-card .stat-info h3').textContent}</p>
                    ${document.getElementById('stockTable').outerHTML}
                </body>
                </html>
            `;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('adjustModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>