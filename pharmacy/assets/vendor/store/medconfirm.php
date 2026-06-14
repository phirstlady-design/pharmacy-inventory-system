<?php
session_start();
include("include/connect.php");

$store = $_SESSION['store_section'] ?? '';

// Get total pending count for the specific store section
$pendingSql = "SELECT COUNT(*) AS total_pending FROM codingunit WHERE request_status = 'released' AND storesection = ?";

$stmt = $conn->prepare($pendingSql);
$stmt->bind_param("s", $store);
$stmt->execute();

$result = $stmt->get_result();
$pendingCount = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $pendingCount = $row['total_pending'] ?? 0;
}

$stmt->close();




// Get total released value (filtered by store section)
$priceSql = "SELECT SUM(currentprice) AS total_price FROM codingunit WHERE request_status = 'released' AND storesection = ?";

$stmt = $conn->prepare($priceSql);
$stmt->bind_param("s", $store);
$stmt->execute();

$result = $stmt->get_result();
$totalPrice = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $totalPrice = $row['total_price'] ?? 0;
}

$stmt->close();


// Pagination setup
$results_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Get total records count for this store
$stmtTotal = $conn->prepare("SELECT COUNT(*) AS total FROM codingunit WHERE request_status = 'released' AND storesection = ?");
$stmtTotal->bind_param("s", $store);
$stmtTotal->execute();
$totalResult = $stmtTotal->get_result();
$totalRow = $totalResult->fetch_assoc();
$total_records = $totalRow['total'] ?? 0;
$total_pages = ceil($total_records / $results_per_page);

// Fetch records for current page
$stmt = $conn->prepare("SELECT * FROM codingunit WHERE request_status = 'released' AND storesection = ? LIMIT ?, ?");
$stmt->bind_param("sii", $store, $start_from, $results_per_page);
$stmt->execute();
$result = $stmt->get_result();

// Get departments for dropdown
$deptSql = "SELECT dept_name FROM dept";
$deptResult = $conn->query($deptSql);
$departments = [];
if ($deptResult) {
    while ($drow = $deptResult->fetch_assoc()) {
        $departments[] = $drow['dept_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Medical Release Page</title>
<link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css" />
<link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css" />
<script src="assets/js/jquery-3.6.0.min.js"></script>


<style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #34495e;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .main-container {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .page-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            border-left: 4px solid var(--primary-color);
        }

        .page-title {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #6c757d;
            margin-bottom: 0;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--card-shadow);
            border: none;
            transition: transform 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .table-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-title {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 0;
        }

        .search-box {
            max-width: 300px;
        }
         .search-container {
      position: relative;
      margin-bottom: 20px;
    }
    
    .search-container i {
      position: absolute;
      left: 10px;
      top: 10px;
    }
      col
        .custom-table {
            margin-bottom: 0;
        }

        .custom-table thead th {
            background-color: #f8f9fa;
            border: none;
            font-weight: 600;
            color: var(--secondary-color);
            padding: 1rem 0.75rem;
            border-bottom: 2px solid #dee2e6;
        }

        .custom-table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
        }

        .custom-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .item-code {
            background-color: #e9ecef;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }

        .department-badge {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .price-display {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .quantity-badge {
            background-color: #fff3e0;
            color: #f57c00;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            min-width: 40px;
            text-align: center;
            display: inline-block;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-confirm {
            background-color: var(--success-color);
            border-color: var(--success-color);
            color: white;
            padding: 0.375rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-confirm:hover {
            background-color: #229954;
            border-color: #229954;
            color: white;
            transform: translateY(-1px);
        }

        .btn-reject {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
            padding: 0.375rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-reject:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            color: white;
            transform: translateY(-1px);
        }

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
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
        .pagination-container {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination-info {
            color: #6c757d;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .table-container {
                padding: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .stats-card {
                margin-bottom: 1rem;
            }
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
 <!-- <div class="watermark"></div> -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#"><i class="fas fa-flask me-2"></i>Medical Release</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="fas fa-home me-1"></i>Dashboard</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link active"><i class="fas fa-box me-1"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container main-container">

  <h1><i class="fas fa-shipping-fast me-2"></i>Medical Store Release Management</h1>
  <p>Manage and track medical equipment releases</p>

  <!-- Stats cards -->
  <div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
      <div class="stats-card">
        <div class="stats-icon text-primary"><i class="fas fa-boxes"></i></div>
        <div class="stats-number text-primary"><?= $total_records ?></div>
        <div>Total Items</div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
      <div class="stats-card">
        <div class="stats-icon text-warning"><i class="fas fa-clock"></i></div>
        <div class="stats-number text-warning"><?= $pendingCount ?></div>
        <div>Pending Release</div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
      <div class="stats-card">
        <div class="stats-icon text-success"><i class="fas fa-naira-sign"></i></div>
        <div class="stats-number text-success"><?= number_format($totalPrice, 2) ?></div>
        <div>Released Value</div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
      <div class="stats-card">
        <div class="stats-icon text-info"><i class="fas fa-building"></i></div>
        <div class="stats-number text-info"><?= count($departments) ?></div>
        <div>Departments</div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="filter-section">
    <div class="row g-3">
      <div class="col-md-4">
          <i class="fas fa-search"></i>
          <input type="text" class="form-control search-input" id="searchItem" placeholder="Search for itemname, code...">
      </div>
      <!-- <div class="col-md-4">
        <label for="reservedfordept" class="form-label">Reserved For Department</label>
        <select id="reservedfordept" class="form-select">
          <option value="">Select Department</option>
          <?php foreach ($departments as $dept): ?>
            <option><?= htmlspecialchars($dept) ?></option>
          <?php endforeach; ?>
        </select>
      </div> -->
      <!-- <div class="col-md-4">
        <label for="statusFilter" class="form-label">Status</label>
        <select id="statusFilter" class="form-select">
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="rejected">Rejected</option>
        </select>
      </div> -->
    </div>
  </div>

  <!-- Table -->
  <div class="table-container">
    <table class="table custom-table ">
      <thead>
        <tr>
          <!-- <th>S/N</th>
          <th class="item-name">Item Name</th>
          <th class="item-code">Item Code</th> -->
         <th>S/N</th>
                    <th>Item Name</th>
                    <th>Item Code</th>
                    <th>Department</th>
                    <th>Item Price</th>
                    <th>Quantity Released</th>
                    <th>Total Price</th>
                    <th>Action</th>
        </tr>
      </thead>
      <tbody id="tableBody">
            <?php
            $sn = $start_from + 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
            <tr data-id="<?= htmlspecialchars($row['id']) ?>">
            <td><?= $sn++; ?></td> <!-- Increment Serial Number -->
            <td class="item-name"><?= htmlspecialchars($row['itemname']) ?></td>
            <td class="item-code"><?= htmlspecialchars($row['itemcode']) ?></td>
            <td class="department"><?= htmlspecialchars($row['department']) ?></td>
            <td class="initial-price"><?= htmlspecialchars($row['initialprice']) ?></td>
            <td class="quantity-released"><?= htmlspecialchars($row['quantityreleased']) ?></td>
            <td class="current-price"><?= htmlspecialchars($row['currentprice']) ?></td>
            <td>
                <button class="btn btn-success confirm" data-id="<?= htmlspecialchars($row['id']) ?>">Confirm</button>
                <button class="btn btn-danger reject-btn" 
                        data-itemcode="<?= htmlspecialchars($row['itemcode']) ?>" 
                        data-id="<?= htmlspecialchars($row['id']) ?>">
                    Reject
                </button>
            </td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
}
?>
</tbody>

    </table>
  </div>

  <!-- Pagination -->
  <nav class="pagination-container" aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <li class="page-item <?= $i === $page ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
      </li>
      <?php endfor; ?>

      <?php if ($page < $total_pages): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
      <?php endif; ?>
    </ul>
  </nav>

</div>




  <!-- search script -->
 <script>
             const searchInput = document.querySelector('input[placeholder*="Search"]');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const itemNameEl = row.querySelector('.item-name');
            const itemCodeEl = row.querySelector('.item-code');

            const itemName = itemNameEl ? itemNameEl.textContent.toLowerCase() : '';
            const itemCode = itemCodeEl ? itemCodeEl.textContent.toLowerCase() : '';

            if (itemName.includes(searchTerm) || itemCode.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}


</script>

 <script>
        $('.confirm').click(function() {
            var id = $(this).data('id');

            // Confirm via AJAX
            $.ajax({
                url: 'confirm_release.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    alert(response); // Show the server response
                    location.reload(); // Reload the page after success
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    alert("Error occurred: " + xhr.responseText);
                }
            });
        });
</script>




 <!-- reject order -->
<script>
    $(document).ready(function() {
        $(".reject-btn").on("click", function() {
            var itemCode = $(this).data("itemcode");
            var id = $(this).data("id"); 
            var row = $(this).closest("tr"); // Get the correct row
            var itemName = row.find(".item-name").text(); // Assuming you have a class for item name
            var itemCode = row.find(".item-code").text(); // Assuming you have a class for item code
            var department = row.find(".department").text(); // Assuming you have a class for department
            var initialprice = row.find(".initialprice").text(); // Assuming you have a class for department
            var quantityReleased = row.find(".quantity-released").text(); // Assuming quantity is shown in a table column
            var currentprice = row.find(".currentprice").text(); // Assuming you have a class for department

            if (!itemCode || !id || !itemName || !department || !quantityReleased) {
                alert("Missing data attributes. Please check the HTML structure.");
                return;
            }

            if (confirm("Are you sure you want to reject this order?")) {
                $.ajax({
                    url: "reject.php",
                    type: "POST",
                    data: {
                        itemcode: itemCode,
                        id: id, 
                        itemname: itemName,
                        department: department,
                        initialprice: initialprice,
                        quantityreleased: quantityReleased,
                        currentprice: currentprice,
                        action: "reject"
                    },
                    dataType: "json", // Expect JSON response
                    success: function(response) {
                        if (response.success) {
                            alert("Order has been rejected successfully.");
                            row.remove(); // Remove only this row
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("AJAX error: " + xhr.responseText);
                    }
                });
            }
        });
    });


</script>

<script>
    // PHP variables passed to JS
    const itemCode = "<?php echo $itemCode; ?>"; // PHP item code
    const department = "<?php echo $department; ?>"; // PHP department
    const releasedTime = new Date("<?php echo $releaseDate; ?>"); // PHP release date to JS date

    // Function to update the backend database
    const updateRemainingQuantity = () => {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "uncollected.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Sending item code and department dynamically fetched from PHP
        xhr.send("itemCode=" + itemCode + "&department=" + department);

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response if needed
                console.log(xhr.responseText);
            }
        };
    };

    const checkTimeLimit = () => {
        const currentTime = new Date();
        const timeDiff = currentTime - releasedTime; // Time difference in milliseconds
        const hoursDiff = timeDiff / (1000 * 60 * 60); // Convert milliseconds to hours

        if (hoursDiff >= 48) {
            // Remove item from the table (front-end)
            document.getElementById("itemRow").remove(); // 'itemRow' is the ID of the row in the table
            alert("Item has been removed due to 48-hour expiration.");
            
            // Update remaining quantity on the backend
            updateRemainingQuantity();
        }
    };

    // Call the function on page load
    window.onload = function() {
        checkTimeLimit();
    };
</script>
<script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
