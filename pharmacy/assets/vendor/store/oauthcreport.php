<?php
session_start();

if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
    header('Location: index.php');
    exit();
}

include("include/connect.php");



$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : '';

// Set default dates if none provided
if (empty($start_date) && empty($end_date) && empty($report_type)) {
    $start_date = date('Y-m-d', strtotime('-30 days'));
    $end_date = date('Y-m-d');
}

if ($report_type == 'daily') {
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d');
} elseif ($report_type == 'weekly') {
    $start_date = date('Y-m-d', strtotime('-7 days'));
    $end_date = date('Y-m-d');
} elseif ($report_type == 'monthly') {
    $start_date = date('Y-m-01');
    $end_date = date('Y-m-t');
} elseif ($report_type == 'quarterly') {
    $current_month = date('m');
    $current_year = date('Y');
    if ($current_month <= 3) {
        $start_date = "$current_year-01-01";
        $end_date = "$current_year-03-31";
    } elseif ($current_month <= 6) {
        $start_date = "$current_year-04-01";
        $end_date = "$current_year-06-30";
    } elseif ($current_month <= 9) {
        $start_date = "$current_year-07-01";
        $end_date = "$current_year-09-30";
    } else {
        $start_date = "$current_year-10-01";
        $end_date = "$current_year-12-31";
    }
} elseif ($report_type == 'yearly') {
    $start_date = date('Y-01-01');
    $end_date = date('Y-12-31');
} elseif ($report_type == 'custom') {
    if (empty($start_date) || empty($end_date)) {
        $error_message = "Please select a valid start and end date for the custom range.";
    }
}

// Initialize variables
$result = null;
$total_records = 0;
$error_message = '';

// Only execute query if we have valid dates
if (!empty($start_date) && !empty($end_date) && empty($error_message)) {
    // Escape the dates to prevent SQL injection
    $start_date_safe = mysqli_real_escape_string($conn, $start_date);
    $end_date_safe = mysqli_real_escape_string($conn, $end_date);
    
    // Build the query
    $query = "(
        SELECT rb.itemname, rb.itemcode, rb.category, rb.storesection, rb.supplier, 
            rb.quantity_supplied, rb.unitofmeasurement, rb.deliverydate, rb.manufacturedate, rb.expirydate, 
            rb.reservedquantity, rb.reservedfordept, rb.remainingquantity, s.company_name, s.contact_phone, s.contact_email
        FROM supplier s
        JOIN receivingbay rb ON s.supplier = rb.supplier
        WHERE DATE(rb.deliverydate) BETWEEN '$start_date_safe' AND '$end_date_safe'
    )
    UNION ALL
    (
        SELECT rbn.itemname, rbn.itemcode, rbn.category, rbn.storesection, rbn.supplier, 
            rbn.quantity_supplied, rbn.unitofmeasurement, rbn.deliverydate, rbn.manufacturedate, rbn.expirydate, 
            rbn.reservedquantity, rbn.reservedfordept, rbn.remainingquantity, s.company_name, s.contact_phone, s.contact_email
        FROM supplier s
        JOIN receivingbaynewitems rbn ON s.supplier = rbn.supplier
        WHERE DATE(rbn.deliverydate) BETWEEN '$start_date_safe' AND '$end_date_safe'
    )
    ORDER BY deliverydate DESC";

    // Debug: Print the query (remove this in production)
    // echo "<!-- Debug Query: " . $query . " -->";
    
    $result = $conn->query($query);
    
    if (!$result) {
        $error_message = "SQL Error: " . $conn->error;
    } else {
        $total_records = $result->num_rows;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAUTHC Store Report System</title>
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css" />
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #3b82f6;
            --accent-color: #06b6d4;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --dark-color: #1f2937;
            --light-bg: #f8fafc;
            --sidebar-bg: #1e293b;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #0f172a 100%);
            min-height: 100vh;
            width: 280px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-logo {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-logo:hover {
            color: var(--accent-color);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .nav-icon {
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 280px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        .top-navbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-title {
            color: var(--dark-color);
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--dark-color);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: var(--light-bg);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--dark-color);
            font-weight: 500;
        }

        .content-area {
            padding: 2rem;
        }

        .report-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 1.5rem 2rem;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.2"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        }

        .card-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .card-subtitle {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 2rem;
        }

        .filter-section {
            background: #f8fafc;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .filter-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }

        .btn-generate {
            background: linear-gradient(135deg, var(--success-color) 0%, #047857 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(5, 150, 105, 0.3);
        }

        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(5, 150, 105, 0.4);
            color: white;
        }

        .search-section {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .search-input {
            position: relative;
        }

        .search-input .form-control {
            padding-left: 3rem;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            z-index: 10;
        }

        .table-container {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table-header {
            background: #f8fafc;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-title {
            color: var(--dark-color);
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }

        .custom-table {
            margin: 0;
        }

        .custom-table thead th {
            background: var(--dark-color);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .custom-table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
        }

        .custom-table tbody tr:hover {
            background: #f8fafc;
        }

        .item-code {
            background: #e0e7ff;
            color: #3730a3;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        .no-data-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .alert {
            margin: 1rem 0;
        }

        .debug-info {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            font-family: monospace;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-area {
                padding: 1rem;
            }
            
            .top-navbar {
                padding: 1rem;
            }
            
            .navbar-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-logo">
                <i class="fas fa-hospital"></i>
                <span class="logo-text">OAUTHC</span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="dashboard.php" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </div>
        </nav>
        
        <div style="position: absolute; bottom: 2rem; left: 1rem; right: 1rem;">
            <a href="logout.php" class="nav-link" onclick="logout()">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <span class="nav-text">Log Out</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="navbar-title">
                    <i class="fas fa-chart-line"></i>
                    Store Report System
                </h1>
            </div>
            <div class="navbar-actions">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User'; ?></span>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Report Configuration -->
            <div class="report-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-file-alt me-2"></i>
                        OAUTHC Store Report
                    </h2>
                    <p class="card-subtitle">Generate detailed store reports</p>
                </div>
                <div class="card-body">
                    <div class="filter-section">
                        <h5 class="filter-title">
                            <i class="fas fa-filter"></i>
                            Report Filters
                        </h5>
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="report_type" class="form-label">Select Report Type:</label>
                                    <select class="form-select" id="report_type" name="report_type">
                                        <option value="">Choose Report Type</option>
                                        <option value="daily" <?php echo ($report_type == 'daily') ? 'selected' : ''; ?>>Daily</option>
                                        <option value="weekly" <?php echo ($report_type == 'weekly') ? 'selected' : ''; ?>>Weekly</option>
                                        <option value="monthly" <?php echo ($report_type == 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                                        <option value="quarterly" <?php echo ($report_type == 'quarterly') ? 'selected' : ''; ?>>Quarterly</option>
                                        <option value="yearly" <?php echo ($report_type == 'yearly') ? 'selected' : ''; ?>>Yearly</option>
                                        <option value="custom" <?php echo ($report_type == 'custom') ? 'selected' : ''; ?>>Custom Date</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="start_date" class="form-label">Start Date:</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="end_date" class="form-label">End Date:</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                                </div>
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-chart-bar me-1"></i>Generate Report
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            <button type="button" onclick="window.print()" class="btn btn-success">
                                <i class="fas fa-print me-1"></i>Print Report
                            </button>
                            <button class="btn btn-outline-primary" onclick="exportReport()">
                                <i class="fas fa-download me-1"></i>Export Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Debug Information (remove in production) -->
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Error:</strong> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Search Section -->
            <div class="search-section">
                <div class="row align-items-end">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Search Items</label>
                        <div class="search-input">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control" placeholder="Search by item name, code, or category..." id="searchInput" onkeyup="filterItem()">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status Filter</label>
                        <select class="form-select" id="statusFilter" onchange="filterItem()">
                            <option value="">All Status</option>
                            <option value="in-stock">In Stock</option>
                            <option value="low-stock">Low Stock</option>
                            <option value="out-of-stock">Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-muted">
                            <strong>Total Records:</strong> <?php echo $total_records; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-container">
                <div class="table-header">
                    <h4 class="table-title">
                        <i class="fas fa-table"></i>
                        Items and Supplier Details
                        <?php if (!empty($start_date) && !empty($end_date)): ?>
                            <small class="text-muted">(<?php echo $start_date; ?> to <?php echo $end_date; ?>)</small>
                        <?php endif; ?>
                    </h4>
                </div>
                <div class="table-responsive">
                    <table id="itemResults" class="table custom-table">
                        
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Item Code</th>
                                <th>Category</th>
                                <th>Store Section</th>
                                <th>Supplier</th>
                                <th>Quantity Supplied</th>
                                <th>Unit of Measurement</th>
                                <th>Delivery Date</th>
                                <th>Manufacture Date</th>
                                <th>Expiry Date</th>
                                <th>Reserved Quantity</th>
                                <th>Reserved for Dept</th>
                                <th>Remaining Quantity</th>
                                <th>Company Name</th>
                                <th>Supplier Phone</th>
                                <th>Supplier Email</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <?php if ($result && $total_records > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['itemname']); ?></td>
                                        <td><span class="item-code"><?php echo htmlspecialchars($row['itemcode']); ?></span></td>
                                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                                        <td><?php echo htmlspecialchars($row['storesection']); ?></td>
                                        <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($row['quantity_supplied']); ?></td>
                                        <td><?php echo htmlspecialchars($row['unitofmeasurement']); ?></td>
                                        <td><?php echo htmlspecialchars($row['deliverydate']); ?></td>
                                        <td><?php echo htmlspecialchars($row['manufacturedate']); ?></td>
                                        <td><?php echo htmlspecialchars($row['expirydate']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($row['reservedquantity']); ?></td>
                                        <td><?php echo htmlspecialchars($row['reservedfordept']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($row['remainingquantity']); ?></td>
                                        <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['contact_phone']); ?></td>
                                        <td><?php echo htmlspecialchars($row['contact_email']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="16" class="no-data">
                                        <div class="no-data-icon">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <h5>No Data Found</h5>
                                        <p class="text-muted">
                                            <?php if (empty($start_date) || empty($end_date)): ?>
                                                Please select a date range to view reports.
                                            <?php else: ?>
                                                No records found for the selected date range (<?php echo $start_date; ?> to <?php echo $end_date; ?>).
                                                <br>Try adjusting your date range or check if data exists in the database.
                                            <?php endif; ?>
                                        </p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        let sidebarCollapsed = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebarCollapsed = !sidebarCollapsed;
            
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }
        }

        function filterItem() {
            let input = document.getElementById('searchInput');
            let filter = input.value.toUpperCase();
            let table = document.getElementById('itemResults');
            let rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) { // Skip header row
                let cols = rows[i].getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cols.length; j++) {
                    if (cols[j].textContent.toUpperCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display = match ? '' : 'none';
            }
        }

        function exportReport() {
            // Simple CSV export
            let table = document.getElementById('itemResults');
            let csv = [];
            let rows = table.querySelectorAll('tr');
            
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll('td, th');
                for (let j = 0; j < cols.length; j++) {
                    row.push('"' + cols[j].innerText.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(','));
            }
            
            let csvFile = new Blob([csv.join('\n')], {type: 'text/csv'});
            let downloadLink = document.createElement('a');
            downloadLink.download = 'store_report_' + new Date().toISOString().split('T')[0] + '.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        function logout() {
            if (confirm('Are you sure you want to log out?')) {
                window.location.href = 'logout.php';
            }
        }

        // Set default dates on page load
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            
            if (!startDateInput.value) {
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
                startDateInput.value = thirtyDaysAgo.toISOString().split('T')[0];
            }
            
            if (!endDateInput.value) {
                const today = new Date();
                endDateInput.value = today.toISOString().split('T')[0];
            }
        });
    </script>
    <script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>