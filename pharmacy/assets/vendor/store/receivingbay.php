<?php

session_start();

if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
    header('Location: index.php'); // Change to your actual login page
    exit();
}
include("include/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['query'])) {
        handleLiveSearch($conn);
    }
    if (isset($_POST['submit'])) {
        handleFormSubmission($conn);
    }
}

function handleLiveSearch($conn) {
    $query = $_POST['query'] ?? '';
    $sql = "SELECT a.id, a.itemname, a.itemcode, a.category, a.storesection, a.quantity 
            FROM allitems a
            LEFT JOIN (SELECT itemcode, MAX(totalremainingquantity) AS totalremainingquantity 
                       FROM receivingbay GROUP BY itemcode) r 
            ON a.itemcode = r.itemcode
            WHERE a.itemname LIKE ?
            LIMIT 10";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = array_map('trim', $row);
    }

    echo json_encode($items);
    exit;
}

function handleFormSubmission($conn) {
    $verified = $_POST['verified'] ?? null;
    $itemname = $_POST['searchItem'] ?? ''; 
    $itemcode = $_POST['itemCode'] ?? ''; 
    $category = $_POST['itemCategory'] ?? '';
    $initialprice = isset($_POST['initialprice']) && $_POST['initialprice'] !== '' ? floatval($_POST['initialprice']) : 0.00; 
    $storesection = $_POST['storeSection'] ?? ''; 
    $supplier = $_POST['supplier'] ?? ''; 
    $quantity_supplied = (int) ($_POST['quantity'] ?? 0); 
    $unitofmeasurement = $_POST['unitofmeasurement'] ?? ''; 
    $deliverydate = $_POST['deliverydate'] ?? ''; 
    $manufacturedate = $_POST['manufacturedate'] ?? null;
    $expirydate = $_POST['expirydate'] ?? null;
    $reservedquantity = (int) ($_POST['reservedquantity'] ?? 0);
    $reservedfordept = $_POST['reservedfordept'] ?? null;


        $sql = "SELECT totalremainingquantity FROM receivingbay WHERE itemcode = ? ORDER BY id DESC LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",  $itemcode);
    $stmt->execute();
    $stmt->bind_result($previous_total_remaining);
    $stmt->fetch();
    $stmt->close();

    
    
    $previous_total_remaining = $previous_total_remaining ?? 0;
    $remainingquantity = $quantity_supplied - $reservedquantity;
    $totalremainingquantity = $previous_total_remaining + $remainingquantity;
    
    if ($remainingquantity < 0) {
        echo "Error: Reserved quantity cannot exceed total supplied quantity.";
        exit();
    }

    $table = ($verified === "Yes") ? "receivingbay" : "audit";
    $query = "INSERT INTO $table 
              (itemname, itemcode, category, quantity_supplied, storesection, initialprice, supplier, 
              unitofmeasurement, deliverydate, manufacturedate, expirydate, 
              reservedquantity, reservedfordept, remainingquantity, totalremainingquantity) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?)";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssisdsssssisii",
        $itemname, $itemcode, $category, $quantity_supplied, $storesection, $initialprice, $supplier,
        $unitofmeasurement, $deliverydate, $manufacturedate, $expirydate, 
        $reservedquantity, $reservedfordept, $remainingquantity, $totalremainingquantity);

    if ($stmt->execute()) {
        echo "Record successfully submitted to " . ucfirst($table) . " table.";
        // Update request_status to "unaudited" if verified is "No"    
    if ($verified === "No") {
            $update_status_query = "UPDATE audit SET request_status = 'unaudited', source= 'receivingbay' WHERE itemname = ? AND itemcode = ? AND storesection = ? AND supplier = ?";
            $stmt_update = $conn->prepare($update_status_query);
            $stmt_update->bind_param("ssss", $itemname, $itemcode, $storesection, $supplier);
            $stmt_update->execute();
            $stmt_update->close();
        }
    } else {
        echo "Error inserting into $table: " . $stmt->error;
    }
    
    // Insert into reserved table
    if (!empty($reservedquantity) && $reservedquantity > 0) {
    $insert_reserved = "INSERT INTO reserved (itemcode, reservedquantity, reservedfordept) VALUES (?, ?, ?)";
    $stmt_reserved = $conn->prepare($insert_reserved);
    $stmt_reserved->bind_param("sis", $itemcode, $reservedquantity, $reservedfordept);
    $stmt_reserved->execute();
    $stmt_reserved->close();
    }
    
    // Determine the store table based on store section
    $store_table = getStoreTable($storesection);
    if ($store_table) {
        $sql_store = "INSERT INTO $store_table (itemname, itemcode, category, supplier, quantity_supplied, expirydate, manufacturedate, deliverydate, reservedquantity, reservedfordept, remainingquantity, initialprice,totalremainingquantity)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_store = $conn->prepare($sql_store);
    $stmt_store->bind_param("ssssisssisidi", $itemname, $itemcode, $category, $supplier, $quantity_supplied, $expirydate, $manufacturedate, $deliverydate, $reservedquantity, $reservedfordept, $remainingquantity, $initialprice, $totalremainingquantity);
    $stmt_store->execute();
    $stmt_store->close();
    }
    
    $stmt->close();
    $conn->close();
}

function getStoreTable($storesection) {
    switch ($storesection) {
        case 'electricalStore': return 'electricalstore';
        case 'hardwareStore': return 'hardwarestore';
        case 'generalStationeryStore': return 'stationerystore';
        case 'labStore': return 'labstore';
        case 'healthStationeryStore': return 'healthstore';
        case 'medicalStore': return 'medstore';
        case 'civilStore': return 'civilstore';
        default: return null;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receiving Bay - Inventory Management</title>
  <!-- Bootstrap 5 CSS -->
 <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <script src="assets/js/jquery-3.6.0.min.js"></script>
  <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css">
  <!-- Custom CSS -->
<style>
    :root {
      --primary: #0d6efd;
      --primary-dark: #0b5ed7;
      --secondary: #6c757d;
      --success: #198754;
      --warning: #ffc107;
      --danger: #dc3545;
      --light-bg: #f8f9fa;
      --border-color: #dee2e6;
    }
    
    body {
      background: linear-gradient(120deg,rgba(247, 248, 251, 0.43) 0%,rgb(220, 229, 244) 50%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .sidebar {
      background: #1e3d73;
      color: white;
      height: 100vh;
      position: fixed;
      padding-top: 20px;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.8);
      padding: 12px 20px;
      margin: 5px 0;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .sidebar .nav-link:hover, .sidebar .nav-link:focus {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
      transform: translateX(5px);
    }
    
    .sidebar .nav-link.active {
      background-color: var(--primary);
      color: white;
    }
    
    .sidebar .nav-link i {
      margin-right: 10px;
      width: 20px;
    }
    
    .main-content {
      margin-left: 250px;
      padding: 20px;
    }
    
    .page-header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 20px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .form-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      overflow: hidden;
    }
    
    .form-header {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      padding: 25px 30px;
      margin: 0;
    }
    
    .form-body {
      padding: 30px;
    }
    
    .form-control, .form-select {
      border: 2px solid #e9ecef;
      border-radius: 10px;
      padding: 12px 15px;
      transition: all 0.3s ease;
      background-color: #fff;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
      transform: translateY(-2px);
    }
    
    .form-label {
      font-weight: 600;
      color: #495057;
      margin-bottom: 8px;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      border: none;
      border-radius: 10px;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
    }
    
    .btn-outline-primary {
      border: 2px solid var(--primary);
      border-radius: 10px;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }
    
    .form-check-input {
      border-radius: 50%;
      border: 2px solid #dee2e6;
      width: 1.2em;
      height: 1.2em;
    }
    
    .form-check-input:checked {
      background-color: var(--primary);
      border-color: var(--primary);
    }
    
    .form-check-label {
      font-weight: 500;
      margin-left: 8px;
    }
    
    .input-group-text {
      background-color: var(--light-bg);
      border: 2px solid #e9ecef;
      border-radius: 10px 0 0 10px;
    }
    
    .input-group .form-control {
      border-left: none;
      border-radius: 0 10px 10px 0;
    }
    
    .section-divider {
      border: none;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--primary), transparent);
      margin: 30px 0;
    }
    
    .floating-label {
      position: relative;
    }
    
    .floating-label .form-control:focus + .form-label,
    .floating-label .form-control:not(:placeholder-shown) + .form-label {
      transform: translateY(-25px) scale(0.8);
      color: var(--primary);
    }
    
    .card-stats {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    #results {
                    border: 1px solid #ccc;
                    max-height: 200px;
                    overflow-y: auto;
                    position: absolute;
                    background: white;
                    z-index: 1000;
                    width: 100%;
                }
        #results div {
                    padding: 10px;
                    cursor: pointer;
                }
        #results div:hover {
                    background-color: #f0f0f0;
                }
                  .watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    width: 300px;
    height: 300px;
    background: url('image/logo.png') no-repeat center center;
    background-size: contain;
    opacity: 0.1;
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 9999;
  }
    @media (max-width: 992px) {
      .sidebar {
        width: 70px;
        text-align: center;
      }
      
      .sidebar .nav-link span {
        display: none;
      }
      
      .sidebar .nav-link i {
        margin-right: 0;
        font-size: 1.2rem;
      }
      
      .main-content {
        margin-left: 70px;
      }
    }
    
    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 10px;
      }
      
      .sidebar {
        display: none;
      }
      
      .mobile-nav {
        display: block !important;
      }
      
      .form-body {
        padding: 20px;
      }
      
      .page-header {
        padding: 15px 20px;
      }
    }
    
    .mobile-nav {
      display: none;
      background: linear-gradient(135deg, #2c3e50, #1a252f);
      padding: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .progress-indicator {
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--success));
      border-radius: 2px;
      margin-bottom: 20px;
    }
    
    .form-section {
      margin-bottom: 30px;
      padding: 20px;
      background: rgba(248, 249, 250, 0.5);
      border-radius: 10px;
      border-left: 4px solid var(--primary);
    }
    
    .form-section h5 {
      color: var(--primary);
      font-weight: 600;
      margin-bottom: 20px;
    }
    
    .required {
      color: var(--danger);
    }
    
    .tooltip-icon {
      color: var(--secondary);
      margin-left: 5px;
      cursor: help;
    }
  </style>
</head>
<body>
    <div class="watermark"></div>
  <!-- Mobile Navigation -->
  <div class="mobile-nav d-md-none">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col">
          <h5 class="text-white mb-0">
            <i class="fas fa-warehouse me-2"></i>
            OAUTHC STORE
          </h5>
        </div>
        <div class="col-auto">
          <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar d-none d-md-block">
    <div class="text-center mb-4">
      <i class="fas fa-warehouse fa-2x mb-2"></i>
      <h4>OAUTHC Store</h4>
    </div>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <!--  -->
      <li class="nav-item mt-5">
        <a class="nav-link" href="logout.php">
          <i class="fas fa-sign-out-alt"></i>
          <span>Log Out</span>
        </a>
      </li>
    </ul>
  </div>

  <!-- Offcanvas Sidebar for Mobile -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">
        <i class="fas fa-warehouse me-2"></i>
        OAUTHC
      </h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark active" href="#">
            <i class="fas fa-truck-loading"></i>
            Receiving Bay
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <i class="fas fa-boxes"></i>
            All Items
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <i class="fas fa-clipboard-list"></i>
            Requests
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <i class="fas fa-truck"></i>
            Suppliers
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <i class="fas fa-chart-bar"></i>
            Reports
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <i class="fas fa-cogs"></i>
            Settings
          </a>
        </li>
        <li class="nav-item mt-5">
          <a class="nav-link text-dark" href="#">
            <i class="fas fa-sign-out-alt"></i>
            Log Out
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h1 class="mb-0">
            <i class="fas fa-truck-loading text-primary me-3"></i>
            Receiving Bay
          </h1>
          <p class="text-muted mb-0">Add new items to inventory</p>
        </div>
        <div class="col-auto">
          <!-- <button class="btn btn-outline-primary me-2">
            <i class="fas fa-history"></i> View History
          </button> -->
          <button class="btn btn-primary">
            <a href="add_newitem.php" class="btn btn-primary">Add new item</a>

            <!-- <a href="add_newitem.php" class="btn btn-primary">Add new item</a> -->
          </button>
        </div>
      </div>
    </div>

    <!-- Progress Indicator -->
    <div class="progress-indicator"></div>

    <!-- Form Container -->
    <div class="form-container">
      <div class="form-header">
        <h3 class="mb-0">
          <i class="fas fa-plus-circle me-2"></i>
          Add New Item
        </h3>
        <p class="mb-0 opacity-75">Fill in the details below to add a new item to inventory</p>
      </div>

      <div class="form-body">
                <div id="formMessage" style="color: green; margin-top: 10px;"></div>

        <form id="itemForm" action="receivingbay.php" method="post" class="new">
        <!-- <a href="add_newitem.php" class="btn btn-primary">Add new item</a> -->
          <!-- Basic Information Section -->
          <div class="form-section">
            <h5>
              <i class="fas fa-info-circle me-2"></i>
              Basic Information
            </h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="searchItem">Search Item Name:</label>
                  <i class="fas fa-question-circle tooltip-icon" data-bs-toggle="tooltip" title="Enter the name of the item"></i>
                </label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fas fa-search"></i>
                  </span>
                    <input type="text" id="searchItem" name="searchItem" class="form-control" placeholder="Type item name..." autocomplete="off">                
                    <div id="results"></div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="itemCode">Item Code:</label>
                  <i class="fas fa-question-circle tooltip-icon" data-bs-toggle="tooltip" title="Unique identifier for the item"></i>
                </label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fas fa-barcode"></i>
                  </span> 
                <input type="text" id="itemCode" name="itemCode" class="form-control" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                 <label for="itemCategory">Category:</label>
                <input type="text" id="itemCategory" name="itemCategory" class="form-control" readonly>

                </label>
                
              </div>
              <div class="col-md-6 mb-3">
                 <label for="storeSection">Store Section:</label>
                 <input type="text" name="storeSection" id="storeSection" class="form-control" readonly>
              </div>
            </div>
          </div>

          <hr class="section-divider">

          <!-- Quantity and Pricing Section -->
          <div class="form-section">
            <h5>
              <i class="fas fa-calculator me-2"></i>
              Quantity & Pricing
            </h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                 <label class="fw-bold form-label" for="quantity">Quantity Supplied:
                    <span class="required">*</span>
                 </label>
       
                </label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fas fa-boxes"></i>
                  </span>
                   <input type="text" name="quantity" id="quantity" class="form-control" required> 
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="initialprice">Item Price(#): <span class="required">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fas fa-dollar-sign"></i>
                  </span>
                 <input type="number" class="form-control" id="initialprice" name="initialprice"  step="0.01" required>
                </div>
              </div>
            </div>
            <input type="hidden" name="source" value="receivingbay">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="reservedQuantity">Reserved Quantity:</label>
                <input type="text" name="reservedquantity"  id="reserved_Quantity" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label for="remaining_Quantity">Remaining Quantity:</label>
                <input type="text" id="remaining_Quantity" name="remainingquantity" class="form-control" readonly>
              </div>
            </div>
          </div>

          <hr class="section-divider">

          <!-- Supplier and Audit Section -->
          <div class="form-section">
            <h5>
              <i class="fas fa-truck me-2"></i>
              Supplier & Audit Information
            </h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="fw-bold form-label">Supplier <span class="required">*</span>
                </label>
                <select class="form-select" name="supplier" required>
        <option value="">Select Supplier</option>
        <!-- Populate suppliers dynamically -->
        <?php
        // Query to fetch suppliers
        $suppliers_query = "SELECT id, supplier FROM supplier"; 
        $suppliers_result = $conn->query($suppliers_query); // Execute the query

        // Check if the query executed successfully and there are results
        if ($suppliers_result && $suppliers_result->num_rows > 0) {
            while ($supplier = $suppliers_result->fetch_assoc()) {
                echo "<option value='{$supplier['supplier']}'>{$supplier['supplier']}</option>";
            }
        } else {
            echo "<option value=''>No suppliers available</option>"; // Handle no results case
        }
        ?>
        </select>
              </div>
    <div class="col-md-6 mb-3">
                <label class="fw-bold form-label" class="form-label">Unit of Measurement <span class="required">*</span>
                </label>
                 <select class="form-select" name="unitofmeasurement">
            <option value="">Select Unit</option>
                    <?php
                    $query = "SELECT id, unitname FROM units";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['unitname'], ENT_QUOTES, 'UTF-8') . "'>" 
                                . htmlspecialchars($row['unitname'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                    } else {
                        echo "<option value=''>No unit available</option>";
                    }
                    ?>
            </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="verified">Is the item audited? <span class="required">*</span>
                </label>
                <div class="mt-2">
                  <div class="form-check form-check-inline">
                    <!-- <label for="verified">Is the item audited?</label><br> -->
                        <input type="radio" id="verifiedYes" name="verified" value="Yes" required>
                        <label for="verifiedYes">Yes</label>
                      <i class="fas fa-check-circle text-success me-1"></i>
                    
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input type="radio" id="verifiedNo" name="verified" value="No">
                    <label for="verifiedNo">No</label>
                      <i class="fas fa-times-circle text-danger me-1"></i>
                    
                    </label>
                  </div>
                </div>
              </div>

              <div class="col-md-6 mb-3">
                <label for="reservedForDept">Reserved For Department:</label>
                <select name="reservedfordept" id="reservedfordept" class="form-select">
                <option value="">Select Department</option>
                    <?php
                    $query = "SELECT id, dept_name FROM dept";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['dept_name'], ENT_QUOTES, 'UTF-8') . "'>" 
                                . htmlspecialchars($row['dept_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                    } else {
                        echo "<option value=''>No departments available</option>";
                    }
                    ?>
            </select>
              </div>
            </div>
          </div>

          <hr class="section-divider">

          <!-- Date Information Section -->
          <div class="form-section">
            <h5>
              <i class="fas fa-calendar-alt me-2"></i>
              Date Information
            </h5>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="fw-bold form-label" for="date">Delivery Date:</label>
                 <input type="date" name="deliverydate" id="deliverydate" class="form-control" required><span class="required">*</span>
               
            <!-- <input type="date" name="deliverydate" id="deliverydate" class="form-control" required>     -->
                  </div>
              <div class="col-md-4 mb-3">
                <label class="fw-bold form-label" for="date"><b>Manufacturing Date</b></label>
               <input type="date" name="manufacturedate" id="manufacturedate" class="form-control" required >
              </div>
              <div class="col-md-4 mb-3">
                  <label class="fw-bold form-label" for="date">Expiry Date:</label>
                  <input type="date" name="expirydate" id="expirydate" class="form-control">
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="row mt-4">
            <!-- <div class="col-md-6">
              <button type="button" class="btn btn-outline-primary w-100">
                <i class="fas fa-save me-2"></i>
                Save as Draft
              </button>
            </div> -->
            <div class="col-md-6">
                
             <button type="submit" name="submit" class="btn btn-primary w-100">Submit</button>

                <i class="fas fa-plus-circle me-2"></i>
                
              </button>
            </div>
          </div>
        </form>
        <div id="formMessage" style="color: green; margin-top: 10px;"></div>

      </div>
    </div>
  </div>

  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title text-success" id="successModalLabel">
            <i class="fas fa-check-circle me-2"></i>
            Success!
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
          <h4>Item Added Successfully!</h4>
          <p class="text-muted">The item has been added to your inventory and is ready for use.</p>
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
            <i class="fas fa-plus me-2"></i>
            Add Another Item
          </button>
          <button type="button" class="btn btn-outline-primary">
            <i class="fas fa-eye me-2"></i>
            View Inventory
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>  
  <!-- Custom JavaScript -->

<script>
  $(document).ready(function () {
  // Search input keyup handler
  $('#searchItem').on('keyup', function () {
    let query = $(this).val().trim();

    if (query.length >= 1) {
      $('#results').html('<div>Loading...</div>');

      $.ajax({
        url: '', // Current file
        method: 'POST',
        data: { query: query },
        dataType: 'json',
        success: function (data) {
          let results = $('#results');
          results.empty();

          if (data.length > 0) {
            data.forEach(item => {
              results.append(`
                <div 
                  class="result-item" 
                  data-code="${item.itemcode}" 
                  data-category="${item.category}" 
                  data-store="${item.storesection}"
                  data-quantity="${item.quantity}">
                  ${item.itemname}
                </div>
              `);
            });
          } else {
            results.append('<div>No results found</div>');
          }
        },
        error: function (xhr, status, error) {
          $('#results').html(`<div>Error: ${xhr.responseText || error}</div>`);
        }
      });
    } else {
      $('#results').empty();
    }
  });

  // Click on search result item
  $(document).on('click', '.result-item', function () {
    let itemName = $(this).text();
    let itemCode = $(this).data('code');
    let category = $(this).data('category');
    let storeSection = $(this).data('store');
    let quantity = $(this).data('quantity');

    $('#searchItem').val(itemName);
    $('#itemCode').val(itemCode);
    $('#itemCategory').val(category);
    $('#storeSection').val(storeSection);
    $('#quantity').val(quantity);
    $('#results').empty();
  });

  // Optional: Clear results if input is manually cleared by user
  $('#searchItem').on('input', function() {
    if ($(this).val().trim() === '') {
      $('#results').empty();
    }
  });

  // Optional: After form submit, clear inputs and results (if you want)
  $('#yourFormId').on('submit', function(e) {
    // If you're submitting via AJAX, prevent default and handle submit in JS
    // e.preventDefault();

    // After successful submission, reset the form & clear results
    // $(this).trigger('reset');
    // $('#results').empty();
  });
  });

</script>

<script>
  $('#itemForm').on('submit', function (e) {
  e.preventDefault();

  $.ajax({
    url: '', // current PHP file
    type: 'POST',
    data: $(this).serialize() + '&submit=1', // send form data + submit flag
    success: function (response) {
      $('#formMessage').html(response); // show success message
      // Reload page after 2 seconds
      setTimeout(function () {
        location.reload();
      }, 2000);
    },
    error: function () {
      $('#formMessage').html('An error occurred while submitting the form.');
    }
  });
});

</script>

<script>
    $(document).ready(function () {
    $('#reserved_Quantity').on('input', function () {
        // Get the supplied quantity (default to 0 if empty)
        let quantitySupplied = parseInt($('#quantity').val()) || 0;

        // Get the reserved quantity (default to 0 if empty or invalid)
        let reservedQuantity = parseInt($(this).val()) || 0;

        console.log('Supplied Quantity:', quantitySupplied);
        console.log('Reserved Quantity:', reservedQuantity);

        // Ensure reserved quantity does not exceed supplied quantity
        if (reservedQuantity > quantitySupplied) {
            alert("Reserved quantity cannot exceed supplied quantity.");
            $('#reserved_Quantity').val(''); // Clear invalid input
            $('#remaining_Quantity').val(quantitySupplied); // Set remaining to supplied
        } else {
            // Calculate and update the remaining quantity
            let remainingQuantity = quantitySupplied - reservedQuantity;
            $('#remaining_Quantity').val(remainingQuantity);
            console.log('Remaining Quantity:', remainingQuantity);
        }
    });

    // Ensure remaining quantity reflects the supplied quantity if reserved is empty
    $('#quantity').on('input', function () {
        let quantitySupplied = parseInt($(this).val()) || 0;

        // Check if reserved quantity is empty or invalid
        let reservedQuantity = parseInt($('#reserved_Quantity').val()) || 0;

        // Calculate the remaining quantity
        let remainingQuantity = quantitySupplied - reservedQuantity;

        // Update the remaining quantity
        $('#remaining_Quantity').val(remainingQuantity);
        console.log('Supplied Quantity:', quantitySupplied, 'Remaining Quantity:', remainingQuantity);
    });
    });
</script>
  <!-- <script>
   
    
    // Barcode scanning simulation
    document.querySelector('.btn-primary').addEventListener('click', function() {
      if (this.innerHTML.includes('Scan Barcode')) {
        // Simulate barcode scanning
        const simulatedBarcode = 'MED-2501-' + Math.floor(Math.random() * 100).toString().padStart(2, '0');
        document.getElementById('itemCode').value = simulatedBarcode;
        
        // Show toast notification
        showToast('Barcode scanned successfully!', 'success');
      }
    });
    
    // Toast notification function
    function showToast(message, type = 'info') {
      const toastContainer = document.createElement('div');
      toastContainer.className = 'position-fixed top-0 end-0 p-3';
      toastContainer.style.zIndex = '1055';
      
      const toast = document.createElement('div');
      toast.className = `toast align-items-center text-white bg-${type} border-0`;
      toast.setAttribute('role', 'alert');
      toast.innerHTML = `
        <div class="d-flex">
          <div class="toast-body">
            <i class="fas fa-check-circle me-2"></i>
            ${message}
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      `;
      
      toastContainer.appendChild(toast);
      document.body.appendChild(toastContainer);
      
      const bsToast = new bootstrap.Toast(toast);
      bsToast.show();
      
      // Remove toast container after it's hidden
      toast.addEventListener('hidden.bs.toast', function() {
        document.body.removeChild(toastContainer);
      });
    }
  </script> -->
</body>
</html>