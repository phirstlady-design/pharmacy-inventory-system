<?php

// if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
//     header('Location: index.php'); // Change to your actual login page
//     exit();
// }
include("include/connect.php"); 


$result = $conn->query(" 
    SELECT 
        r.id,
        r.itemname,
        r.itemcode, 
        r.storesection,
        r.initialprice,
        r.currentprice,
        r.department AS requesting_department, 
        r.quantityrequested, 
        COALESCE(rb.totalremainingquantity, rbn.totalremainingquantity, 0) AS totalremainingquantity,
        COALESCE(
            (SELECT SUM(reservedquantity - collectedreserved) FROM receivingbay WHERE itemcode = r.itemcode), 0
        ) + 
        COALESCE(
            (SELECT SUM(reservedquantity - collectedreserved) FROM receivingbaynewitems WHERE itemcode = r.itemcode), 0
        ) AS total_reserved_quantity
    FROM requests r
    LEFT JOIN receivingbay rb ON r.itemcode = rb.itemcode AND rb.id = (SELECT MAX(id) FROM receivingbay WHERE itemcode = r.itemcode)
    LEFT JOIN receivingbaynewitems rbn ON r.itemcode = rbn.itemcode AND rbn.id = (SELECT MAX(id) FROM receivingbaynewitems WHERE itemcode = r.itemcode)
    WHERE r.request_status = 'pending' 
    GROUP BY r.id, r.itemname, r.itemcode, r.storesection, r.initialprice, r.currentprice, r.department, r.quantityrequested, totalremainingquantity
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Control Unit</title>
  <!-- Bootstrap 5 CSS -->
 <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
  <script src="assets/js/jquery-3.6.0.min.js"></script>  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
  <!-- Custom CSS -->
  <style>
    :root {
      --primary: #0d6efd;
      --primary-dark: #0b5ed7;
      --secondary: #6c757d;
      --light-bg: #f8f9fa;
      --border-color: #dee2e6;
    }
    
    body {
      background-color: #f5f5f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .sidebar {
      background: linear-gradient(to bottom, #2c3e50, #1a252f);
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
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(13, 110, 253, 0.05);
    }
    
    .btn-release {
      background-color: var(--primary);
      color: white;
      border: none;
      transition: all 0.3s;
    }
    
    .btn-release:hover {
      background-color: var(--primary-dark);
    }
    
    .quantity-input {
      max-width: 80px;
    }
    
    .status-indicator {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 5px;
    }
    
    .status-available {
      background-color: #28a745;
    }
    
    .status-low {
      background-color:rgb(242, 24, 24);
    }
    .status-medium {
    background-color: #fd7e14; 
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
  </style>
</head>
<body>
  <!-- Mobile Navigation -->
  <div class="mobile-nav d-md-none">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col">
          <h5 class="text-white mb-0">Control Unit</h5>
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
      <h4>Control Unit</h4>
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
          <i class="fas fa-cogs"></i>
          <span>Control Unit</span>
        </a>
      </li> -->
      <!-- <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-boxes"></i>
          <span>Inventory</span>
        </a>
      </li> -->
      <!-- <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-clipboard-list"></i>
          <span>Requests</span>
        </a> -->
      </li>
      <li class="nav-item">
        <a class="nav-link" href="codingunitreport.php">
          <i class="fas fa-chart-bar"></i>
          <span>Reports</span>
        </a>
      </li>
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
            <i class="fas fa-cogs"></i>
            Control Unit
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <i class="fas fa-boxes"></i>
            Inventory
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
            <i class="fas fa-chart-bar"></i>
            Reports
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
      <h2>Control Unit</h2>
     
    </div>

    <div class="search-container">
      <i class="fas fa-search"></i>
      <input type="text" class="form-control search-input" placeholder="Search items...">
    </div>

    <div class="table-container">
      <div class="table-responsive">
        <table id = "exportTable" class="table table-hover table-striped" >
          <thead>
                <tr>
                    <th>S/N</th>
                    <th>Item Name</th>
                    <th>Item Code</th>
                    <th>Store Section</th>
                    <th>Requesting Department</th>
                    <th>Item Price</th>
                    <th>Requested Quantity</th>
                    <th>Total Price</th>
                    <th>Reserved Quantity</th>
                    <th>Remaining Quantity</th>
                    <th>Quantity to Release</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                        <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['itemname']; ?></td>
                    <td><?php echo $row['itemcode']; ?></td>
                    <td><?php echo $row['storesection']; ?></td>
                    <td><?php echo $row['requesting_department']; ?></td>
                    <td><?php echo $row['initialprice']; ?></td>
                    <td><?php echo $row['quantityrequested']; ?></td>
                    <td><?php echo $row['currentprice']; ?></td>
                    <td><?php echo $row['total_reserved_quantity'] ?? 0; ?></td>
                    <!-- <td>< ?php echo $row['totalremainingquantity'] ?? 0; ?></td> -->
                     <td>
                        <?php
                            $remaining = $row['totalremainingquantity'] ?? 0;
                            $statusClass = 'status-available'; // default

                            if ($remaining <= 10) {
                                $statusClass = 'status-low';
                            } elseif ($remaining <= 30) {
                                $statusClass = 'status-medium';
                            }
                        ?>
                        <span class="status-indicator <?php echo $statusClass; ?>"></span>
                        <?php echo $remaining; ?>
                        </td>

                  

                           
                            <td>
                                <input type="number" name="quantityreleased" class="form-control" id="quantityreleased<?php echo $row['id']; ?>" max="<?php echo $row['quantityrequested']; ?>" min="1">
                            </td>
                            <td>
                                <button class="btn btn-primary release" data-id="<?php echo $row['id']; ?>">Release</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="10">No pending requests found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
      <div>
        <span class="text-muted">Showing 1 to 8 of 8 entries</span>
      </div>
      <nav aria-label="Page navigation">
        <ul class="pagination">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
          </li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item disabled">
            <a class="page-link" href="#">Next</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/jquery-3.6.0.min.js"></script>

<script>
   
document.getElementById('exportBtn').addEventListener('click', function () {
  if (confirm("Are you sure you want to export this file?")) {
    var table = document.getElementById('exportTable');
    var workbook = XLSX.utils.table_to_book(table, { sheet: "Data" });
    XLSX.writeFile(workbook, 'exported_data.xlsx');
  }
});

</script>
    <script>
        $(document).ready(function () {
        $('.release').click(function () {
        var id = $(this).data('id');
        var quantityreleased = $('#quantityreleased' + id).val();

        if (!quantityreleased || quantityreleased <= 0) {
            alert("Please enter a valid quantity.");
            return;
        }

            // Debugging: Log values to the console
            console.log("ID:", id);
            console.log("Quantity Released:", quantityreleased);

            // Send the data via AJAX
            $.ajax({
                url: 'release_item.php',
                type: 'POST',
                data: { id: id, quantityreleased: quantityreleased },
                success: function(response) {
                    alert(response);  // Show the server response
                    location.reload();  // Reload the page after success
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    console.error("Response:", xhr.responseText);
                    alert("Error occurred: " + xhr.responseText);
                }
            });
        });
    });
    </script>
  <!-- Custom JavaScript -->
  <script>
    // Add event listeners for the release buttons
    document.querySelectorAll('.btn-release').forEach(button => {
      button.addEventListener('click', function() {
        const row = this.closest('tr');
        const itemName = row.cells[1].textContent;
        const quantityInput = row.querySelector('.quantity-input');
        const quantity = quantityInput.value;
        
        if (quantity > 0) {
          alert(`Released ${quantity} units of ${itemName}`);
          // In a real application, you would send this data to the server
        } else {
          alert('Please enter a quantity to release');
        }
      });
    });
    
    // Search functionality
    document.querySelector('.search-input').addEventListener('keyup', function() {
      const searchTerm = this.value.toLowerCase();
      const tableRows = document.querySelectorAll('tbody tr');
      
      tableRows.forEach(row => {
        const itemName = row.cells[1].textContent.toLowerCase();
        const itemCode = row.cells[2].textContent.toLowerCase();
        const department = row.cells[4].textContent.toLowerCase();
        
        if (itemName.includes(searchTerm) || itemCode.includes(searchTerm) || department.includes(searchTerm)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  </script>
    <script>
    // Function to update status indicators based on quantity
    function updateStatusIndicators() {
      const rows = document.querySelectorAll('tbody tr');
      
      rows.forEach(row => {
        const remainingQuantity = parseInt(row.cells[9].textContent.trim());
        const statusIndicator = row.querySelector('.status-indicator');
        
        // Remove all existing status classes
        statusIndicator.classList.remove('status-available', 'status-medium', 'status-low');
        
        // Add appropriate status class based on quantity
        if (remainingQuantity <= 10) {
          statusIndicator.classList.add('status-low');
        } else if (remainingQuantity <= 30) {
          statusIndicator.classList.add('status-medium');
        } else {
          statusIndicator.classList.add('status-available');
        }
      });
    }
    
    // Call the function when page loads
    document.addEventListener('DOMContentLoaded', updateStatusIndicators);
    
    // Add event listeners for the release buttons
    document.querySelectorAll('.btn-release').forEach(button => {
      button.addEventListener('click', function() {
        const row = this.closest('tr');
        const itemName = row.cells[1].textContent;
        const quantityInput = row.querySelector('.quantity-input');
        const quantity = parseInt(quantityInput.value);
        const remainingCell = row.cells[9];
        const currentRemaining = parseInt(remainingCell.textContent.trim());
        
        if (quantity > 0 && quantity <= currentRemaining) {
          // Update the remaining quantity in the UI
          const newRemaining = currentRemaining - quantity;
          remainingCell.innerHTML = `
            <span class="status-indicator"></span>
            ${newRemaining}
          `;
          
          // Update the status indicator
          const statusIndicator = remainingCell.querySelector('.status-indicator');
          statusIndicator.classList.remove('status-available', 'status-medium', 'status-low');
          
          if (newRemaining <= 10) {
            statusIndicator.classList.add('status-low');
          } else if (newRemaining <= 30) {
            statusIndicator.classList.add('status-medium');
          } else {
            statusIndicator.classList.add('status-available');
          }
          
          // Update the max value of the input
          quantityInput.max = newRemaining;
          quantityInput.value = "";
          
          alert(`Released ${quantity} units of ${itemName}. Remaining: ${newRemaining}`);
          // In a real application, you would send this data to the server
        } else if (quantity > currentRemaining) {
          alert(`Error: Cannot release more than the available quantity (${currentRemaining})`);
        } else {
          alert('Please enter a quantity to release');
        }
      });
    });
    </script>
</body>
</html>