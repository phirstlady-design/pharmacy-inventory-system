<?php
session_start();
include("include/connect.php");

// Ensure store is retrieved from session
$store = $_SESSION['store_section'];
$items = [];
$error_message = '';

// Check if session store is set
if (!$store) {
    die("⚠️ Store section not set in session. Please log in again.");
}

// Map 'labstore', 'civilstore', etc. to confirm tables
$store_tables = [
    'labStore' => 'labconfirm',
    'civilStore' => 'civilconfirm',
    'medStore' => 'medconfirm',
    'electricalStore' => 'electricalconfirm',
    'hardwareStore' => 'hardwareconfirm',
    'stationeryStore' => 'stationeryconfirm',
    'stationeryStore' => 'healthconfirm'
];


$report_type = $_POST['report_type'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';

// Validate store
if (!isset($store_tables[$store])) {
    die("❌ Invalid store selected: $store");
}

$table = $store_tables[$store];

// Determine date range based on report type
$today = date('Y-m-d');

switch ($report_type) {
    case 'daily':
        $start_date = $end_date = $today;
        break;
    case 'weekly':
        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));
        break;
    case 'monthly':
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        break;
    case 'quarterly':
        $current_month = date('n');
        $quarter_start = (floor(($current_month - 1) / 3) * 3) + 1;
        $start_date = date("Y-$quarter_start-01");
        $quarter_end_month = $quarter_start + 2;
        $end_date = date("Y-$quarter_end_month-t");
        break;
    case 'yearly':
        $start_date = date('Y-01-01');
        $end_date = date('Y-12-31');
        break;
    case 'custom':
        // Use provided start_date and end_date
        break;
    default:
        $error_message = 'Invalid report type.';
}

if (empty($error_message)) {
    $query = "SELECT * FROM `$table` WHERE `createdon` BETWEEN '$start_date' AND '$end_date'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
    } else {
        $error_message = "Query Error: " . mysqli_error($conn);
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

        .debug-info {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            font-family: monospace;
            font-size: 0.875rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .debug-toggle {
            cursor: pointer;
            color: var(--primary-color);
            text-decoration: underline;
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

        @media print {
            .sidebar, .top-navbar, .filter-section, .search-section, .debug-info, .no-print {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-area {
                padding: 0;
            }
        }
    </style>
<script>
    function filterItem() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('itemResults');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) { // skip header
        const cols = rows[i].getElementsByTagName('td');
        let match = false;

        // Check item name, item code, or department/storesection columns
        const fieldsToCheck = [0, 1, 3]; // change 3 if department is in another column

        for (let j of fieldsToCheck) {
            if (cols[j] && cols[j].textContent.toUpperCase().includes(filter)) {
                match = true;
                break;
            }
        }

        rows[i].style.display = match ? '' : 'none';
    }
}
</script>
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
        <div class="top-navbar no-print">
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
                    <span><?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'User'; ?></span>
                </div>
            </div>
        </div>

       

            <!-- Error Messages -->
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger no-print">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Report Configuration -->
            <div class="report-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-file-alt me-2"></i>
                        OAUTHC Store Report
                    </h2>
                    <p class="card-subtitle">Generate detailed store reports for: <?php echo htmlspecialchars($store); ?></p>
                </div>
                <div class="card-body">
                    <div class="filter-section no-print">
                        <h5 class="filter-title">
                            <i class="fas fa-filter"></i>
                            Report Filters
                        </h5>
                        <form method="POST" action="">
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
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="end_date" class="form-label">End Date:</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
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
                                <i class="fas fa-download me-1"></i>Export CSV
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="search-section no-print">
                <div class="row align-items-end">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Search Items</label>
                        <div class="search-input">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control" placeholder="Search by item name, code, or category..." id="searchInput" onkeyup="filterItem()">
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
                        <!-- < ?php if (!empty($start_date) && !empty($end_date)): ?>
                            <small class="text-muted">(<?php echo htmlspecialchars($start_date); ?> to <?php echo htmlspecialchars($end_date); ?>)</small>
                        < ?php endif; ?> -->
                    </h4>
                    <div class="text-muted">
                        Total Items: <?php echo count($items); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <?php if (empty($items)): ?>
                        <div class="no-data">
                            <div class="no-data-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h5>No Data Found</h5>
                            <p>No items found for the selected criteria.</p>
                            <?php if (!empty($debug['available_stores'])): ?>
                                <p><strong>Available Store Sections:</strong> <?php echo implode(', ', $debug['available_stores']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <table id="itemResults" class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    
                                    <th>Item Name</th>
                                    <th>Item Code</th>
                                    <th>Quantity Released</th>
                                    <th>Remaining Quantity</th>
                                    <th>Initial price</th>
                                    <th>Current price</th>
                                    <th>Department</th>
                                    <th>Officer</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Previous balance</th>
                                    <th>Current balance</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <?php foreach ($items as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['itemname'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['itemcode'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantityreleased'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['remainingquantity'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['initialprice'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['currentprice'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['department'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['officerincharge'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['request_status'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['createdon'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['previous_balance'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['current_balance'] ?? ''); ?></td>
                                    
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
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
            if (!table) return;
            
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
