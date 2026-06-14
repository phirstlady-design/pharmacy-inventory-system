<?php
session_start();

// if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
//     header('Location: index.php'); // Change to your actual login page
//     exit();
// }
include("include/connect.php"); 

// Fetch data from the audit table
$query = "SELECT * FROM audit WHERE request_status != 'audited'"; // Example condition to exclude audited records
$result = $conn->query($query);

if (!$result) {
    die("Error fetching data: " . $conn->error); // Handle database errors gracefully
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Items Information</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Custom CSS -->
  <style>
    :root {
      --primary: #0d6efd;
      --primary-dark: #0b5ed7;
      --secondary: #6c757d;
      --light-bg: #f8f9fa;
      --border-color: #dee2e6;
      --success: #198754;
      --warning: #ffc107;
      --danger: #dc3545;
    }
    
    body {
      background-color: #f5f5f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .sidebar {
      background: #1e3d73;
      color: white;
      height: 100vh;
      position: fixed;
      padding-top: 20px;
    }
    
    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.8);
      padding: 10px 20px;
      margin: 5px 0;
      border-radius: 5px;
      transition: all 0.3s;
    }
    
    .sidebar .nav-link:hover, .sidebar .nav-link:focus {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
    }
    
    .sidebar .nav-link i {
      margin-right: 10px;
    }
    
    .main-content {
      margin-left: 250px;
      padding: 20px;
    }
    
    .page-header {
      background-color: #fff;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
    }
    
    .table-container {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table th {
      background-color: #f8f9fa;
      font-weight: 600;
      border-top: none;
      white-space: nowrap;
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(13, 110, 253, 0.05);
    }
    
    .btn-details {
      background-color: var(--primary);
      color: white;
      border: none;
      transition: all 0.3s;
    }
    
    .btn-details:hover {
      background-color: var(--primary-dark);
    }
    
    .search-container {
      position: relative;
      margin-bottom: 20px;
    }
    
    .search-container i {
      position: absolute;
      left: 10px;
      top: 10px;
      color: #6c757d;
    }
    
    .search-input {
      padding-left: 35px;
      border-radius: 20px;
    }
    
    .badge-category {
      background-color: #e9ecef;
      color: #495057;
      font-weight: 500;
      padding: 5px 10px;
      border-radius: 4px;
    }
    
    .expiry-warning {
      color: var(--danger);
      font-weight: 500;
    }
    
    .expiry-upcoming {
      color: var(--warning);
      font-weight: 500;
    }
    
    .expiry-good {
      color: var(--success);
      font-weight: 500;
    }
    
    .quantity-indicator {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 5px;
    }
    
    .quantity-high {
      background-color: var(--success);
    }
    
    .quantity-medium {
      background-color: var(--warning);
    }
    
    .quantity-low {
      background-color: var(--danger);
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
    }
    
    .mobile-nav {
      display: none;
      background-color: #2c3e50;
      padding: 10px;
    }
    
    .table-responsive {
      overflow-x: auto;
    }
    
    /* Alternating row colors */
    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0, 0, 0, 0.02);
    }
    
    .dropdown-filter {
      min-width: 200px;
    }
    
    .filter-badge {
      background-color: var(--primary);
      color: white;
      margin-right: 5px;
      padding: 5px 10px;
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      font-size: 0.8rem;
    }
    
    .filter-badge .close-icon {
      margin-left: 5px;
      cursor: pointer;
    }
    
    .filter-container {
      margin-bottom: 15px;
    }
    .watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    width: 300px;
    height: 300px;
    background: url('image/oauthclogo.jpg') no-repeat center center;
    background-size: contain;
    opacity: 0.1;
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 9999;
 }

  </style>

  <script>
        function filterItem() {
            let input = document.getElementById('searchItem');
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
    </script>
</head>
<body>
    <div class="watermark"></div>

 <!-- Mobile Navigation -->
  <div class="mobile-nav d-md-none">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col">
          <h5 class="text-white mb-0">Inventory System</h5>
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
      <h4>Inventory System</h4>
    </div>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link active" href="#">
          <i class="fas fa-boxes"></i>
          <span>All Items</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-clipboard-list"></i>
          <span>Requests</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-truck"></i>
          <span>Suppliers</span>
        </a>
      </li> -->
      <!-- <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-chart-bar"></i>
          <span>Reports</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-cogs"></i>
          <span>Settings</span>
        </a>
      </li> -->
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
      <h5 class="offcanvas-title">Inventory System</h5>
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
    <div class="page-header d-flex justify-content-between align-items-center">
      <h2>All Items Information</h2>
      <!-- <div>
        <button class="btn btn-outline-primary me-2">
          <i class="fas fa-file-export"></i> Export
        </button>
        <button class="btn btn-primary">
          <i class="fas fa-plus"></i> Add New Item
        </button>
      </div> -->
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <div class="search-container">
          <i class="fas fa-search"></i>
          <input type="text" class="form-control search-input" id="searchItem" placeholder="Search for itemname, code...">
        </div>
      </div>
      <div class="col-md-6 d-flex justify-content-md-end mt-2 mt-md-0">
        <div class="dropdown me-2">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="categoryFilter" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-filter"></i> Category
          </button>
          <ul class="dropdown-menu dropdown-filter" aria-labelledby="categoryFilter">
            <li><a class="dropdown-item filter-category" href="#" data-category="all">All Categories</a></li>
            <li><a class="dropdown-item filter-category" href="#" data-category="Current">Current</a></li>
            <li><a class="dropdown-item filter-category" href="#" data-category="Non-Current">Non Current</a></li>
          </ul>
        </div>
        <div class="dropdown me-2">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="supplierFilter" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-filter"></i> Supplier
          </button>
          <ul class="dropdown-menu dropdown-filter" aria-labelledby="supplierFilter">
            <li><a class="dropdown-item filter-supplier" href="#" data-supplier="all">All Suppliers</a></li>
            <li><a class="dropdown-item filter-supplier" href="#" data-supplier="Famous">Famous</a></li>
            <li><a class="dropdown-item filter-supplier" href="#" data-supplier="Steve A.J">Steve A.J</a></li>
          </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-sort"></i> Sort
          </button>
          <ul class="dropdown-menu dropdown-filter" aria-labelledby="sortDropdown">
            <li><a class="dropdown-item sort-option" href="#" data-sort="name-asc">Name (A-Z)</a></li>
            <li><a class="dropdown-item sort-option" href="#" data-sort="name-desc">Name (Z-A)</a></li>
            <li><a class="dropdown-item sort-option" href="#" data-sort="price-asc">Price (Low to High)</a></li>
            <li><a class="dropdown-item sort-option" href="#" data-sort="price-desc">Price (High to Low)</a></li>
            <li><a class="dropdown-item sort-option" href="#" data-sort="quantity-asc">Quantity (Low to High)</a></li>
            <li><a class="dropdown-item sort-option" href="#" data-sort="quantity-desc">Quantity (High to Low)</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="filter-container" id="activeFilters">
      <!-- Active filters will be displayed here -->
    </div>
 
 
 <div class="table-container">
      <div class="table-responsive">
 <table class="table table-bordered table-striped table-hover" id="itemResults">
          <thead class="thead-dark">
           <tr>
                            <th>S/N</th>
                            <th>Item Name</th>
                            <th>Item Code</th>
                            <th>Category</th>
                            <th>Store Section</th>
                            <th>Item price</th>
                            <th>Supplier</th>
                            <th>Quantity Supplied</th>
                            <th>Unit of Measurement</th>
                            <th>Delivery Date</th>
                            <th>Manufacture Date</th>
                            <th>Expiry Date</th>
                            <th>Reserved Quantity</th>
                            <th>Reserved For Dept</th>
                            <th>Remaining Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                     <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['itemname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['itemcode']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td><?php echo htmlspecialchars($row['storesection']); ?></td>
                                    <td><?php echo htmlspecialchars($row['initialprice']); ?></td>
                                    <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity_supplied']); ?></td>
                                    <td><?php echo htmlspecialchars($row['unitofmeasurement']); ?></td>
                                    <td><?php echo htmlspecialchars($row['deliverydate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['manufacturedate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['expirydate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reservedquantity']); ?></td>
                                    <td><?php echo htmlspecialchars($row['reservedfordept']); ?></td>
                                    <td><?php echo htmlspecialchars($row['remainingquantity']); ?></td>
                                    <td>
                                        <button class="btn btn-primary audit" data-id="<?php echo $row['id']; ?>">Audit</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="14">No records found in the audit table.</td></tr>
                        <?php endif; ?>
          </tbody>
        </table>
        </div>
        </div>
        </div>
         <script>
        $('.audit').click(function () {
            var id = $(this).data('id'); // Get the item ID

            if (confirm("Are you sure you want to audit this item?")) {
                $.ajax({
                    url: 'audit_action.php', // Point to the backend PHP script
                    type: 'POST',
                    data: { id: id },
                    success: function (response) {
                        alert(response); // Show the server response
                        location.reload(); // Reload the page
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: " + error);
                        alert("Error occurred: " + xhr.responseText);
                    }
                });
            }
        });
    </script>
  
      <!-- search by filter-->
<script>
    $(document).ready(function() {
  // Initialize Bootstrap tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Search input debounce and filtering
  var searchTimeout;
  $('#searchItem').on('keyup', function() {
    clearTimeout(searchTimeout);
    var query = $(this).val().toLowerCase();

    searchTimeout = setTimeout(function() {
      if (query === "") {
        // Show all rows if empty search
        $('tbody tr').show();
        return;
      }

      // Filter rows by name, code, supplier, or category
      $('tbody tr').each(function() {
        var itemName = $(this).find('td:eq(1)').text().toLowerCase();
        var itemCode = $(this).find('td:eq(2)').text().toLowerCase();
        var supplier = $(this).find('td:eq(4)').text().toLowerCase();
        var category = $(this).find('td:eq(3)').text().toLowerCase();

        if (itemName.includes(query) || itemCode.includes(query) || supplier.includes(query) || category.includes(query)) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    }, 300);
  });

  // Category filter buttons
  $('.filter-category').on('click', function(e) {
    e.preventDefault();
    var category = $(this).data('category');

    $('.filter-badge[data-type="category"]').remove();

    if (category !== 'all') {
      $('#activeFilters').append(
        `<div class="filter-badge" data-type="category" data-value="${category}">
          Category: ${category} <span class="close-icon">&times;</span>
        </div>`
      );

      $('tbody tr').each(function() {
        var rowCategory = $(this).find('td:eq(3)').text().trim();
        if (rowCategory === category) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    } else {
      $('tbody tr').show();
    }
  });

  // Supplier filter buttons
  $('.filter-supplier').on('click', function(e) {
    e.preventDefault();
    var supplier = $(this).data('supplier');

    $('.filter-badge[data-type="supplier"]').remove();

    if (supplier !== 'all') {
      $('#activeFilters').append(
        `<div class="filter-badge" data-type="supplier" data-value="${supplier}">
          Supplier: ${supplier} <span class="close-icon">&times;</span>
        </div>`
      );

      $('tbody tr').each(function() {
        var rowSupplier = $(this).find('td:eq(4)').text().trim();
        if (rowSupplier === supplier) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    } else {
      $('tbody tr').show();
    }
  });

  // Sorting options
  $('.sort-option').on('click', function(e) {
    e.preventDefault();
    var sortType = $(this).data('sort');
    var rows = $('tbody tr').get();

    rows.sort(function(a, b) {
      var keyA, keyB;

      if (sortType === 'name-asc' || sortType === 'name-desc') {
        keyA = $(a).find('td:eq(1)').text().toUpperCase();
        keyB = $(b).find('td:eq(1)').text().toUpperCase();
        return sortType === 'name-asc' ? (keyA > keyB ? 1 : -1) : (keyA < keyB ? 1 : -1);
      } 
      else if (sortType === 'price-asc' || sortType === 'price-desc') {
        keyA = parseFloat($(a).find('td:eq(6)').text()) || 0;
        keyB = parseFloat($(b).find('td:eq(6)').text()) || 0;
        return sortType === 'price-asc' ? (keyA - keyB) : (keyB - keyA);
      }
      else if (sortType === 'quantity-asc' || sortType === 'quantity-desc') {
        keyA = parseInt($(a).find('td:eq(12)').text()) || 0;
        keyB = parseInt($(b).find('td:eq(12)').text()) || 0;
        return sortType === 'quantity-asc' ? (keyA - keyB) : (keyB - keyA);
      }

      return 0;
    });

    $('.filter-badge[data-type="sort"]').remove();

    var sortText = $(this).text();
    $('#activeFilters').append(
      `<div class="filter-badge" data-type="sort" data-value="${sortType}">
        Sort: ${sortText} <span class="close-icon">&times;</span>
      </div>`
    );

    $.each(rows, function(index, row) {
      $('tbody').append(row);
    });
  });

  // Remove filter badge when close icon clicked
  $(document).on('click', '.close-icon', function() {
    var badge = $(this).closest('.filter-badge');
    var type = badge.data('type');
    badge.remove();

    // Show all rows
    $('tbody tr').show();

    // Reapply active filters except removed one
    $('.filter-badge').each(function() {
      var filterType = $(this).data('type');
      var filterValue = $(this).data('value');

      if (filterType === 'category') {
        $('tbody tr').each(function() {
          var rowCategory = $(this).find('td:eq(3)').text().trim();
          if (rowCategory !== filterValue) {
            $(this).hide();
          }
        });
      } else if (filterType === 'supplier') {
        $('tbody tr').each(function() {
          var rowSupplier = $(this).find('td:eq(4)').text().trim();
          if (rowSupplier !== filterValue) {
            $(this).hide();
          }
        });
      }
    });
  });

  // // Check low stock on page load
  // checkLowStock();
    });



  //   // Close alert function (can be called from button)
  //   function closeAlert() {
  //   $('#customAlert').hide();
  //   $('#alertOverlay').hide();
  //   }


      </script>
</body>
</html>