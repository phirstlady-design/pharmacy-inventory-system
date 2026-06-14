<?php
include("include/connect.php");

// Get current date
$current_date = date('Y-m-d');
$thirty_days_from_now = date('Y-m-d', strtotime('+30 days'));

// Low stock alerts
$low_stock_sql = "SELECT * FROM medicines WHERE quantity <= reorder_level ORDER BY (quantity - reorder_level) ASC";
$low_stock_result = $conn->query($low_stock_sql);
$low_stock_medicines = [];
if ($low_stock_result) {
    while ($row = $low_stock_result->fetch_assoc()) {
        $low_stock_medicines[] = $row;
    }
}

// Expired medicines
$expired_sql = "SELECT * FROM medicines WHERE expiry_date <= ? ORDER BY expiry_date ASC";
$expired_stmt = $conn->prepare($expired_sql);
$expired_stmt->bind_param("s", $current_date);
$expired_stmt->execute();
$expired_result = $expired_stmt->get_result();
$expired_medicines = [];
if ($expired_result) {
    while ($row = $expired_result->fetch_assoc()) {
        $expired_medicines[] = $row;
    }
}
$expired_stmt->close();

// Medicines expiring soon (within 30 days)
$expiring_soon_sql = "SELECT * FROM medicines WHERE expiry_date > ? AND expiry_date <= ? ORDER BY expiry_date ASC";
$expiring_stmt = $conn->prepare($expiring_soon_sql);
$expiring_stmt->bind_param("ss", $current_date, $thirty_days_from_now);
$expiring_stmt->execute();
$expiring_result = $expiring_stmt->get_result();
$expiring_medicines = [];
if ($expiring_result) {
    while ($row = $expiring_result->fetch_assoc()) {
        $expiring_medicines[] = $row;
    }
}
$expiring_stmt->close();

// Out of stock medicines
$out_of_stock_sql = "SELECT * FROM medicines WHERE quantity = 0 ORDER BY name";
$out_of_stock_result = $conn->query($out_of_stock_sql);
$out_of_stock_medicines = [];
if ($out_of_stock_result) {
    while ($row = $out_of_stock_result->fetch_assoc()) {
        $out_of_stock_medicines[] = $row;
    }
}

// High value low stock (medicines worth more than $100 with low stock)
$high_value_low_stock_sql = "SELECT * FROM medicines WHERE quantity <= reorder_level AND price > 100 ORDER BY (price * quantity) DESC";
$high_value_low_stock_result = $conn->query($high_value_low_stock_sql);
$high_value_low_stock = [];
if ($high_value_low_stock_result) {
    while ($row = $high_value_low_stock_result->fetch_assoc()) {
        $high_value_low_stock[] = $row;
    }
}

// Recent sales activity (medicines sold in last 7 days)
$recent_sales_sql = "SELECT m.*, SUM(si.quantity) as sold_quantity, COUNT(si.id) as sale_count 
                     FROM medicines m 
                     JOIN sale_items si ON m.id = si.medicine_id 
                     JOIN sales s ON si.sale_id = s.id 
                     WHERE s.sale_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
                     GROUP BY m.id 
                     ORDER BY sold_quantity DESC 
                     LIMIT 10";
$recent_sales_result = $conn->query($recent_sales_sql);
$recent_sales = [];
if ($recent_sales_result) {
    while ($row = $recent_sales_result->fetch_assoc()) {
        $recent_sales[] = $row;
    }
}

// Calculate alert counts
$total_alerts = count($low_stock_medicines) + count($expired_medicines) + count($expiring_medicines) + count($out_of_stock_medicines);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts & Notifications - Pharmacy System</title>
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
                <li>
                    <a href="stock-taking.php">
                        <i class="fas fa-clipboard-list"></i>
                        Stock Taking
                    </a>
                </li>
                <li class="active">
                    <a href="alerts.php">
                        <i class="fas fa-exclamation-triangle"></i>
                        Alerts
                        <?php if ($total_alerts > 0): ?>
                        <span class="alert-badge"><?php echo $total_alerts; ?></span>
                        <?php endif; ?>
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
                <h1>Alerts & Notifications</h1>
                <div class="header-actions">
                    <button onclick="markAllAsRead()" class="btn btn-secondary">
                        <i class="fas fa-check-double"></i>
                        Mark All as Read
                    </button>
                    <button onclick="refreshAlerts()" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i>
                        Refresh
                    </button>
                </div>
            </header>

            <!-- Alert Summary -->
            <div class="alert-summary">
                <div class="summary-card critical">
                    <div class="summary-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="summary-info">
                        <h3><?php echo count($expired_medicines) + count($out_of_stock_medicines); ?></h3>
                        <p>Critical Alerts</p>
                    </div>
                </div>
                <div class="summary-card warning">
                    <div class="summary-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="summary-info">
                        <h3><?php echo count($low_stock_medicines) + count($expiring_medicines); ?></h3>
                        <p>Warning Alerts</p>
                    </div>
                </div>
                <div class="summary-card info">
                    <div class="summary-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="summary-info">
                        <h3><?php echo count($recent_sales); ?></h3>
                        <p>Activity Updates</p>
                    </div>
                </div>
            </div>

            <!-- Alert Tabs -->
            <div class="alert-tabs">
                <button class="tab-button active" onclick="showTab('critical')">
                    <i class="fas fa-exclamation-circle"></i>
                    Critical (<?php echo count($expired_medicines) + count($out_of_stock_medicines); ?>)
                </button>
                <button class="tab-button" onclick="showTab('warning')">
                    <i class="fas fa-exclamation-triangle"></i>
                    Warnings (<?php echo count($low_stock_medicines) + count($expiring_medicines); ?>)
                </button>
                <button class="tab-button" onclick="showTab('activity')">
                    <i class="fas fa-chart-line"></i>
                    Activity (<?php echo count($recent_sales); ?>)
                </button>
            </div>

            <!-- Critical Alerts Tab -->
            <div id="critical-tab" class="tab-content active">
                <!-- Expired Medicines -->
                <?php if (!empty($expired_medicines)): ?>
                <div class="alert-section">
                    <div class="section-header critical">
                        <i class="fas fa-calendar-times"></i>
                        <h3>Expired Medicines (<?php echo count($expired_medicines); ?>)</h3>
                    </div>
                    <div class="alert-list">
                        <?php foreach ($expired_medicines as $medicine): ?>
                        <div class="alert-item critical">
                            <div class="alert-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <div class="alert-content">
                                <h4><?php echo htmlspecialchars($medicine['name']); ?></h4>
                                <p>Expired on <?php echo date('M d, Y', strtotime($medicine['expiry_date'])); ?></p>
                                <p>Quantity: <?php echo $medicine['quantity']; ?> units</p>
                                <p>Value at risk: $<?php echo number_format($medicine['quantity'] * $medicine['price'], 2); ?></p>
                            </div>
                            <div class="alert-actions">
                                <button onclick="removeExpired(<?php echo $medicine['id']; ?>)" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                    Remove
                                </button>
                                <button onclick="adjustStock(<?php echo $medicine['id']; ?>)" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                    Adjust
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Out of Stock -->
                <?php if (!empty($out_of_stock_medicines)): ?>
                <div class="alert-section">
                    <div class="section-header critical">
                        <i class="fas fa-times-circle"></i>
                        <h3>Out of Stock (<?php echo count($out_of_stock_medicines); ?>)</h3>
                    </div>
                    <div class="alert-list">
                        <?php foreach ($out_of_stock_medicines as $medicine): ?>
                        <div class="alert-item critical">
                            <div class="alert-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="alert-content">
                                <h4><?php echo htmlspecialchars($medicine['name']); ?></h4>
                                <p>Category: <?php echo htmlspecialchars($medicine['category']); ?></p>
                                <p>Reorder Level: <?php echo $medicine['reorder_level']; ?> units</p>
                                <p>Last Price: $<?php echo number_format($medicine['price'], 2); ?></p>
                            </div>
                            <div class="alert-actions">
                                <button onclick="reorderMedicine(<?php echo $medicine['id']; ?>)" class="btn btn-sm btn-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                    Reorder
                                </button>
                                <a href="edit-medicine.php?id=<?php echo $medicine['id']; ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Warning Alerts Tab -->
            <div id="warning-tab" class="tab-content">
                <!-- Low Stock -->
                <?php if (!empty($low_stock_medicines)): ?>
                <div class="alert-section">
                    <div class="section-header warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Low Stock Medicines (<?php echo count($low_stock_medicines); ?>)</h3>
                    </div>
                    <div class="alert-list">
                        <?php foreach ($low_stock_medicines as $medicine): ?>
                        <?php if ($medicine['quantity'] > 0): // Exclude out of stock ?>
                        <div class="alert-item warning">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert-content">
                                <h4><?php echo htmlspecialchars($medicine['name']); ?></h4>
                                <p>Current Stock: <?php echo $medicine['quantity']; ?> units</p>
                                <p>Reorder Level: <?php echo $medicine['reorder_level']; ?> units</p>
                                <p>Shortage: <?php echo $medicine['reorder_level'] - $medicine['quantity']; ?> units</p>
                            </div>
                            <div class="alert-actions">
                                <button onclick="reorderMedicine(<?php echo $medicine['id']; ?>)" class="btn btn-sm btn-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                    Reorder
                                </button>
                                <button onclick="adjustReorderLevel(<?php echo $medicine['id']; ?>)" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-cog"></i>
                                    Adjust Level
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Expiring Soon -->
                <?php if (!empty($expiring_medicines)): ?>
                <div class="alert-section">
                    <div class="section-header warning">
                        <i class="fas fa-clock"></i>
                        <h3>Expiring Soon (<?php echo count($expiring_medicines); ?>)</h3>
                    </div>
                    <div class="alert-list">
                        <?php foreach ($expiring_medicines as $medicine): ?>
                        <?php 
                        $days_to_expiry = ceil((strtotime($medicine['expiry_date']) - time()) / (60 * 60 * 24));
                        ?>
                        <div class="alert-item warning">
                            <div class="alert-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="alert-content">
                                <h4><?php echo htmlspecialchars($medicine['name']); ?></h4>
                                <p>Expires in <?php echo $days_to_expiry; ?> days (<?php echo date('M d, Y', strtotime($medicine['expiry_date'])); ?>)</p>
                                <p>Quantity: <?php echo $medicine['quantity']; ?> units</p>
                                <p>Value: $<?php echo number_format($medicine['quantity'] * $medicine['price'], 2); ?></p>
                            </div>
                            <div class="alert-actions">
                                <button onclick="createDiscount(<?php echo $medicine['id']; ?>)" class="btn btn-sm btn-primary">
                                    <i class="fas fa-percentage"></i>
                                    Discount
                                </button>
                                <button onclick="contactSupplier(<?php echo $medicine['id']; ?>)" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-phone"></i>
                                    Return
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Activity Tab -->
            <div id="activity-tab" class="tab-content">
                <?php if (!empty($recent_sales)): ?>
                <div class="alert-section">
                    <div class="section-header info">
                        <i class="fas fa-chart-line"></i>
                        <h3>High Activity Medicines (Last 7 Days)</h3>
                    </div>
                    <div class="alert-list">
                        <?php foreach ($recent_sales as $medicine): ?>
                        <div class="alert-item info">
                            <div class="alert-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="alert-content">
                                <h4><?php echo htmlspecialchars($medicine['name']); ?></h4>
                                <p>Sold: <?php echo $medicine['sold_quantity']; ?> units in <?php echo $medicine['sale_count']; ?> transactions</p>
                                <p>Current Stock: <?php echo $medicine['quantity']; ?> units</p>
                                <p>Revenue: $<?php echo number_format($medicine['sold_quantity'] * $medicine['price'], 2); ?></p>
                            </div>
                            <div class="alert-actions">
                                <?php if ($medicine['quantity'] <= $medicine['reorder_level']): ?>
                                <button onclick="reorderMedicine(<?php echo $medicine['id']; ?>)" class="btn btn-sm btn-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                    Reorder Now
                                </button>
                                <?php endif; ?>
                                <a href="reports.php?medicine_id=<?php echo $medicine['id']; ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-chart-bar"></i>
                                    View Report
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tab => tab.classList.remove('active'));
            
            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => button.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }

        function markAllAsRead() {
            if (confirm('Mark all alerts as read?')) {
                // Here you would typically make an AJAX call to mark alerts as read
                showAlert('All alerts marked as read', 'success');
            }
        }

        function refreshAlerts() {
            showLoading();
            setTimeout(() => {
                hideLoading();
                window.location.reload();
            }, 1000);
        }

        function removeExpired(medicineId) {
            if (confirm('Are you sure you want to remove this expired medicine from inventory?')) {
                // AJAX call to remove expired medicine
                fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'remove_expired',
                        medicine_id: medicineId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Expired medicine removed successfully', 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showAlert('Error removing medicine: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Error removing medicine', 'error');
                });
            }
        }

        function adjustStock(medicineId) {
            window.location.href = `stock-taking.php?adjust=${medicineId}`;
        }

        function reorderMedicine(medicineId) {
            if (confirm('Create a reorder request for this medicine?')) {
                // Here you would typically integrate with your ordering system
                showAlert('Reorder request created successfully', 'success');
            }
        }

        function adjustReorderLevel(medicineId) {
            const newLevel = prompt('Enter new reorder level:');
            if (newLevel && !isNaN(newLevel) && newLevel >= 0) {
                // AJAX call to update reorder level
                fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'update_reorder_level',
                        medicine_id: medicineId,
                        reorder_level: parseInt(newLevel)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Reorder level updated successfully', 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showAlert('Error updating reorder level: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Error updating reorder level', 'error');
                });
            }
        }

        function createDiscount(medicineId) {
            const discount = prompt('Enter discount percentage (e.g., 20 for 20%):');
            if (discount && !isNaN(discount) && discount > 0 && discount <= 100) {
                showAlert(`${discount}% discount created for expiring medicine`, 'success');
            }
        }

        function contactSupplier(medicineId) {
            if (confirm('Contact supplier for return authorization?')) {
                showAlert('Supplier contact request sent', 'success');
            }
        }

        // Auto-refresh alerts every 5 minutes
        setInterval(() => {
            refreshAlerts();
        }, 300000);

        // Show notification count in browser title
        const totalAlerts = <?php echo $total_alerts; ?>;
        if (totalAlerts > 0) {
            document.title = `(${totalAlerts}) Alerts - Pharmacy System`;
        }
    </script>
</body>
</html>