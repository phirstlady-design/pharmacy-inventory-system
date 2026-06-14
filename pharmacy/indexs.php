<?php
include("include/connect.php");

// Get statistics
$total_medicines = $conn->query("SELECT COUNT(*) AS count FROM medicines")->fetch_assoc()['count'];
$low_stock = $conn->query("SELECT COUNT(*) AS count FROM medicines WHERE quantity <= reorder_level")->fetch_assoc()['count'];
$expired = $conn->query("SELECT COUNT(*) AS count FROM medicines WHERE expiry_date <= CURDATE()")->fetch_assoc()['count'];
$categories = $conn->query("SELECT COUNT(DISTINCT category) AS count FROM medicines")->fetch_assoc()['count'];

// Get recent medicines
$recent_medicines = $conn->query("SELECT * FROM medicines ORDER BY created_at DESC LIMIT 5");

// Get low stock medicines
$low_stock_medicines = $conn->query("SELECT * FROM medicines WHERE quantity <= reorder_level LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Inventory System - Dashboard</title>
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
                <li class="active"><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
                <li><a href="add-medicine.php"><i class="fas fa-plus"></i> Add Medicine</a></li>
                <li><a href="dispense.php"><i class="fas fa-plus"></i> Dispense</a></li>
                  <li><a href="edit-medicine.php"><i class="fas fa-plus"></i> Edit medicine</a></li>

                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span>Admin</span>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-pills"></i></div>
                    <div class="stat-info"><h3><?php echo $total_medicines; ?></h3><p>Total Medicines</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-info"><h3><?php echo $low_stock; ?></h3><p>Low Stock</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-calendar-times"></i></div>
                    <div class="stat-info"><h3><?php echo $expired; ?></h3><p>Expired</p></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-tags"></i></div>
                    <div class="stat-info"><h3><?php echo $categories; ?></h3><p>Categories</p></div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Medicines -->
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Medicines</h3>
                        <a href="inventory.php" class="btn btn-sm">View All</a>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($medicine = $recent_medicines->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($medicine['name']); ?></td>
                                    <td><?php echo htmlspecialchars($medicine['category']); ?></td>
                                    <td><?php echo $medicine['quantity']; ?></td>
                                    <td>$<?php echo number_format($medicine['price'], 2); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div class="card">
                    <div class="card-header">
                        <h3>Low Stock Alert</h3>
                        <span class="badge badge-warning"><?php echo $low_stock; ?></span>
                    </div>
                    <div class="alert-list">
                        <?php while ($medicine = $low_stock_medicines->fetch_assoc()): ?>
                        <div class="alert-item">
                            <div class="alert-info">
                                <h4><?php echo htmlspecialchars($medicine['name']); ?></h4>
                                <p>Current: <?php echo $medicine['quantity']; ?> | Reorder: <?php echo $medicine['reorder_level']; ?></p>
                            </div>
                            <div class="alert-status">
                                <span class="status-badge low">Low Stock</span>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>
