<?php
// Enable error reporting for debugging
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("include/connect.php");
include("include/function.php"); 

$message = '';
$message_type = '';

// Function to handle the last requisition code request
function handleGetLastRequisitionCode($conn) {
  $storeSection = $_POST['storeSection'] ?? '';
  $year = date("Y");

  $query = "SELECT requisitionformcode FROM requests WHERE storesection = ? AND YEAR(request_date) = ? ORDER BY id DESC LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("si", $storeSection, $year);
  $stmt->execute();
  $result = $stmt->get_result();
  $lastCode = $result->fetch_assoc();

  if ($lastCode) {
      preg_match('/(\d+)$/', $lastCode['requisitionformcode'], $matches);
      if (!empty($matches)) {
          // Get the numeric part and increment it
          $lastNumber = (int)$matches[0];
          $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
      } else {
          // If no match found, start from 001
          $nextNumber = '001';
      }
      echo json_encode(['lastCode' => $storeSection . '/' . $year . '/' . $nextNumber]);
  } else {
      // Handle the case when no previous code exists
      echo json_encode(['lastCode' => $storeSection . '/' . $year . '/001']);
  }

  exit;
}

// Function to generate requisition code (fallback)
function generateRequisitionCode($conn, $storesection) {
  $year = date("Y");
  $query = "SELECT requisitionformcode FROM requests WHERE storesection = ? AND YEAR(request_date) = ? ORDER BY id DESC LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("si", $storesection, $year);
  $stmt->execute();
  $result = $stmt->get_result();
  $lastCode = $result->fetch_assoc();

  if ($lastCode) {
      preg_match('/(\d+)$/', $lastCode['requisitionformcode'], $matches);
      $nextNumber = str_pad($matches[0] + 1, 3, '0', STR_PAD_LEFT);
  } else {
      $nextNumber = '001';  // Start from 001 if no previous code exists
  }

  return "$storesection/$year/$nextNumber";
}

// New function to check if an item is locked
function checkItemLock($conn, $itemcode) {
    $lockQuery = "SELECT request_time FROM requests 
        WHERE itemcode = ? 
        AND request_status = 'pending'
        ORDER BY request_time DESC
        LIMIT 1";

    $lockStmt = $conn->prepare($lockQuery);
    $lockStmt->bind_param("s", $itemcode);
    $lockStmt->execute();
    $lockResult = $lockStmt->get_result();
    $lockRow = $lockResult->fetch_assoc();

    if ($lockRow && !empty($lockRow['request_time'])) {
        $lastRequestTime = new DateTime($lockRow['request_time']);
        $now = new DateTime();
        $elapsedSeconds = $now->getTimestamp() - $lastRequestTime->getTimestamp();
        $lockDuration = 300; // 5 minutes in seconds
        $remainingSeconds = $lockDuration - $elapsedSeconds;
        
        if ($remainingSeconds > 0) {
            $minutesRemaining = ceil($remainingSeconds / 60);
            echo json_encode([
                'locked' => true,
                'minutesRemaining' => $minutesRemaining,
                'message' => "This item is locked. Please wait {$minutesRemaining} minute(s) before requesting again."
            ]);
            exit;
        }
    }
    
    echo json_encode(['locked' => false]);
    exit;
}

function handleLiveSearch($conn) {
    $query = $_POST['query'] ?? '';
    $store = $_SESSION['store_section'];
    $sql = "
    SELECT id, TRIM(itemname) AS itemname, itemcode, category, storesection, initialprice, supplier, 
           quantity_supplied, unitofmeasurement, deliverydate, 
           manufacturedate, expirydate, reservedquantity, reservedfordept, remainingquantity, totalremainingquantity
    FROM (
        SELECT id, itemname, itemcode, category, storesection, initialprice, supplier, 
               quantity_supplied, unitofmeasurement, deliverydate, 
               manufacturedate, expirydate, reservedquantity, reservedfordept, remainingquantity, totalremainingquantity
        FROM receivingbay
        WHERE (itemcode, id) IN (
            SELECT itemcode, MAX(id) FROM receivingbay GROUP BY itemcode
        )
        
        UNION ALL
        
        SELECT id, itemname, itemcode, category, storesection, initialprice, supplier,
               quantity_supplied, unitofmeasurement, deliverydate, 
               manufacturedate, expirydate, reservedquantity, reservedfordept, remainingquantity, totalremainingquantity
        FROM receivingbaynewitems
        WHERE (itemcode, id) IN (
            SELECT itemcode, MAX(id) FROM receivingbaynewitems GROUP BY itemcode
        )
    ) AS combined
    WHERE itemname LIKE ? 
    LIMIT 10";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error); // Catch query preparation errors
    }
    $searchTerm = '%'. $query . '%';
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

// Handle different POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for specific actions
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'getLastRequisitionCode') {
            handleGetLastRequisitionCode($conn);
        } else if ($_POST['action'] == 'getReservedQuantity') {
            handleGetReservedQuantity($conn);
        } else if ($_POST['action'] == 'checkItemLock') {
            checkItemLock($conn, $_POST['itemcode'] ?? '');
        }
    }
    
    // Check if this is a live search request
    if (isset($_POST['query'])) {
        handleLiveSearch($conn);
    }
    
    // Handle form submission
    try {
        $itemname = $_POST['searchItem'] ?? '';
        $itemcode = $_POST['itemcode'] ?? '';
        $category = $_POST['category'] ?? '';           
        $initialprice = isset($_POST['initialprice']) && $_POST['initialprice'] !== '' ? floatval($_POST['initialprice']) : 0.00;
        $quantityrequested = (int) ($_POST['quantityrequested'] ?? 0);
        $currentprice = isset($_POST['currentprice']) ? floatval($_POST['currentprice']) : 0.00;
        $requisitionformcode = $_POST['requisitionformcode'] ?? '';
        $itemrequestformcode = $_POST['itemrequestformcode'] ?? '';
        $department = $_POST['department'] ?? '';
        $employeeid = $_POST['employeeid'] ?? '';
        $collectedby = $_POST['collectedby'] ?? '';
        $remainingquantity = $_POST['remainingquantity'] ?? '';
        $reservedquantity = $_POST['reservedquantity'] ?? '';
        $storesection = $_POST['storesection'] ?? '';
        $request_date = $_POST['request_date'] ?? '';
        $name = $_SESSION['fullname'] ?? '';

        // Only process if this is a form submission (has required fields)
        if (!empty($itemname) && !empty($itemcode)) {
            // Validate input
            if (empty($category) || empty($initialprice) || empty($currentprice) || $quantityrequested <= 0 || 
                empty($requisitionformcode) || empty($itemrequestformcode) || empty($department) || 
                empty($employeeid) || empty($collectedby) || empty($storesection)) {
                $message = "Error: Missing required fields.";
                $message_type = "danger";
            } else if ($quantityrequested > $remainingquantity) {
                // If quantity is greater than remaining quantity, show an error message
                $message = "Error: Your requested quantity cannot be greater than what is available";
                $message_type = "danger";
            } else {
                // 🔒 Improved Lock check: Prevent duplicate item requests within 5 minutes
                $lockQuery = "SELECT request_time FROM requests 
                    WHERE itemcode = ? 
                    AND request_status = 'pending'
                    ORDER BY request_time DESC
                    LIMIT 1";

                $lockStmt = $conn->prepare($lockQuery);
                $lockStmt->bind_param("s", $itemcode);
                $lockStmt->execute();
                $lockResult = $lockStmt->get_result();
                $lockRow = $lockResult->fetch_assoc();

                if ($lockRow && !empty($lockRow['request_time'])) {
                    $lastRequestTime = new DateTime($lockRow['request_time']);
                    $now = new DateTime();
                    $elapsedSeconds = $now->getTimestamp() - $lastRequestTime->getTimestamp();
                    $lockDuration = 300; // 5 minutes in seconds
                    $remainingSeconds = $lockDuration - $elapsedSeconds;
                    
                    if ($remainingSeconds > 0) {
                        $minutesRemaining = ceil($remainingSeconds / 60);
                        echo json_encode([
                            'error' => true,
                            'message' => "This item is locked. Please wait {$minutesRemaining} minute(s) before requesting again."
                        ]);
                        exit;
                    }
                }
                
                $sql = "INSERT INTO requests (
                    itemname, itemcode, category, initialprice, currentprice, quantityrequested,
                    requisitionformcode, itemrequestformcode, department, employeeid, collectedby, storesection, issuedby,
                    remainingquantity, reservedquantity, request_date, request_status, totalremainingquantity, createdon
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, NOW())";
                
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                
                $totalremainingquantity = $_POST['totalremainingquantity'] ?? 0;

                $stmt->bind_param(
                    "sssddisssssssiisi",
                    $itemname, $itemcode, $category, $initialprice, $currentprice, $quantityrequested, 
                    $requisitionformcode, $itemrequestformcode, $department, $employeeid, $collectedby, $storesection, $name,
                    $remainingquantity, $reservedquantity, $request_date, $totalremainingquantity
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("Execution failed: " . $stmt->error);
                }
                
                // Notification
                $notificationMessage = "A new request has been made for item: $itemname ($itemcode) by $department.";
                $recipient = "controlunit"; // Notify the Control Unit

                // Get store section based on itemcode
                $store_query = $conn->prepare("SELECT storesection FROM receivingbay WHERE itemcode = ?");
                $store_query->bind_param("s", $itemcode);
                $store_query->execute();
                $store_result = $store_query->get_result();
                $store_data = $store_result->fetch_assoc();

                $storesection = $store_data ? $store_data['storesection'] : "General"; // Default if not found

                // Send notification
                sendNotification($conn, $notificationMessage, $recipient, $storesection);

                // Declare employeeid as a session variable
                $_SESSION['employeeid'] = $employeeid;
                
                $message = 'Request submitted successfully!';
                $message_type = 'success';
                
                echo json_encode(['message' => $message]);
                exit;
            }
        }
     } catch (Exception $e) {
        echo json_encode(["message" => "Exception occurred: " . $e->getMessage()]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Item Request Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
  <script src="assets/js/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .form-container {
      max-width: 1200px;
      margin: 100px auto;
      background-color: white;
      padding: 40px;
      padding-top: 100px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-title {
      font-size: 24px;
      font-weight: bold;
    }
    .form-subtitle {
      font-size: 14px;
      color: #666;
      margin-bottom: 25px;
    }
    label {
      font-weight: 500;
      font-size: 14px;
    }
    .form-control, .form-select {
      font-size: 14px;
    }
    .submit-button {
      margin-top: 20px;
    }

    .sidebar {
    background-color: #1e3d73;
    color: white;
    padding-top: 20px;
    height: 100vh;
    margin-top: 95px;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    }

    .sidebar ul {
        list-style-type: none;
    }

    .sidebar ul li {
        padding: 15px 5px;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
    }
    .sidebar .nav-link:hover {
                background-color:rgb(25,135,84);
                color: #fff;
                transition: transform 0.2s; 
                font-weight: bold;
                border-radius: 5px;
            }

    .sidebar ul li a.active {
        background-color: #365fa2;
        border-radius: 5px;
        padding: 10px;
    }
    .main-content {   
        padding: 20px;
        position: absolute;
        top: 100px;
        margin-left:100px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header h2 {
        margin: 0;
    }

    .filter-options {
        display: flex;
        gap: 10px;
    }

    .filter-options input, .filter-options button {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .inventory-table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }

    .inventory-table thead th {
        padding: 10px;
        background-color: #f0f0f0;
        text-align: left;
        border-bottom: 1px solid #ccc;
    }

    .inventory-table tbody td {
        padding: 10px;
        border-bottom: 1px solid #ccc;
    }
     .header {
                margin-bottom: 15px;
            }
            .filter-options input {
                margin-right: 10px;
            }

            .form-group {
          margin-bottom: 15px;
        }
        #results {
          border: 1px solid #ccc;
          max-height: 200px;
          overflow-y: auto;
          position: absolute;
          background-color: white;
          z-index: 1000;
          width: 100%;
          margin: 0;
          padding: 0;
        }
        #results div {
          padding: 8px;
          margin: 0;
          cursor: pointer;
          white-space: nowrap;
        }
        #results div:hover {
          background-color: #f0f0f0;
        }
        
            .form-control[readonly] {
                background-color: #e9ecef;
            }
  </style>
</head>
<body>

<div class="container form-container">
    <h1 class="text-center text-success text-white bg-success w-100 fixed-top mx-auto py-4">Items Request form</h1>
        <div class="row d-flex justify-content-center align-item-center pt-2">
        <div class="sidebar col-md-1" style="margin-right:20px;" >
        <ul class="navbar-nav mx-auto" >
                <li class="nav-item"><a class="nav-link text-center"href="dashboard.php">Back To Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-center"href="logout.php">Log Out</a></li>
        </ul>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="form-subtitle">Fill in the details below to request an item from inventory</div>

    <form id="requestForm" action="requisitionform.php" method="POST">

  <div class="form-group mb-3">
    <label for="searchItem">Search Item Name:</label>
    <input type="text" id="searchItem" name="searchItem" class="form-control" placeholder="Type item name..." autocomplete="off">
    <div id="results"></div>
  </div>

  <div class="row g-3">
    <div class="col-md-4">
      <label for="itemcode">Item Code:</label>
      <input type="text" class="form-control" id="itemcode" name="itemcode" readonly>
    </div>
    <div class="col-md-4">
      <label for="category">Category:</label>
      <input type="text" class="form-control" id="category" name="category" onchange="fetchReservedQuantity()" readonly>
    </div>
    <div class="col-md-4">
      <label for="department">Requesting Department:</label>
      <select name="department" id="department" class="form-select" required>
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

  <div class="row g-3 mt-1">
    <div class="col-md-4">
      <label for="totalremainingquantity">Remaining Quantity:</label>
      <input type="number" class="form-control" id="totalremainingquantity" name="remainingquantity" readonly>
    </div>
    <div class="col-md-4">
      <label for="reservedquantity">Reserved Quantity:</label>
      <input type="number" class="form-control" id="reservedquantity" name="reservedquantity" readonly>
    </div>
    <div class="col-md-4">
      <label for="reservedfordept">Reserved For Dept:</label>
      <select name="reservedfordept" id="reservedfordept" class="form-select">
        <option value="">Select Reserved Department</option>
      </select>
    </div>
  </div>

  <div class="row g-3 mt-1">
    <div class="col-md-4">
      <label for="initialprice">Item Price:</label>
      <input type="number" class="form-control" id="initialprice" name="initialprice" readonly>
    </div>
    <div class="col-md-4">
      <label for="quantityrequested">Quantity:</label>
      <input type="number" class="form-control" id="quantityrequested" name="quantityrequested" required>
    </div>
    <div class="col-md-4">
      <label for="currentprice">Total Price:</label>
      <input type="number" class="form-control" id="currentprice" name="currentprice" readonly>
    </div>
  </div>

  <div class="row g-3 mt-1">
    <div class="col-md-4">
      <label for="itemrequestformcode">Item Request Form Code:</label>
      <input type="text" class="form-control" name="itemrequestformcode" required>
    </div>
    <div class="col-md-4">
      <label for="requisitionformcode">Requisition Form Code:</label>
      <input type="text" class="form-control" id="requisitionformcode" name="requisitionformcode" readonly>
    </div>
    <div class="col-md-4">
      <label for="collectedby">Collected By:</label>
      <input type="text" class="form-control" id="collectedby" name="collectedby" required>
    </div>
  </div>

  <div class="row g-3 mt-1">
    <div class="col-md-4">
      <label for="employeeid">Employee ID:</label>
      <input type="text" class="form-control" id="employeeid" name="employeeid" required>
    </div>
    <div class="col-md-4">
      <label for="storesection">Store Section:</label>
      <input type="text" class="form-control" id="storesection" name="storesection" readonly>
    </div>
    <div class="col-md-4">
      <label for="request_date">Date:</label>
      <input type="date" class="form-control" id="request_date" name="request_date" required>
    </div>
  </div>

  <div class="text-end mt-5">
    <button type="submit" class="btn btn-dark w-30">Submit Request</button>
  </div>
</form>

<div id="lockCountdown" class="d-none"></div>

 <!-- search -->
<script>
  $(document).ready(function () {
    $('#searchItem').on('keyup', function () {
      let query = $(this).val();

      if (query.length >= 1) {
        $('#results').html('<div>Loading...</div>');

        $.ajax({
          url: '', // Send request to the same file
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
                    data-totalremainingquantity="${item.totalremainingquantity}"
                  data-reservedquantity="${item.reservedquantity}"
                  data-reservedfordept="${item.reservedfordept}"
                  data-initialprice="${item.initialprice}"  >
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

    $(document).on('click', '.result-item', function () {
      let itemName = $(this).text();
      let itemCode = $(this).data('code');
      let category = $(this).data('category');
      let storeSection = $(this).data('store');
      let totalremainingquantity = $(this).data('totalremainingquantity');
      let reservedquantity = $(this).data('reservedquantity');
      let reservedfordept = $(this).data('reservedfordept');
      let initialprice = $(this).data('initialprice');

      $('#searchItem').val(itemName);
      $('#itemcode').val(itemCode);
        $('#category').val(category);
        $('#storesection').val(storeSection);
        $('#totalremainingquantity').val(totalremainingquantity);
        $('#reservedquantity').val(reservedquantity);
        $('#reservedfordept').val(reservedfordept);
        $("#initialprice").val(initialprice);
          // Fetch the last requisition form code and auto-increment it
          fetchRequisitionCode(storeSection);

      $('#results').empty();
    });

    // Function to fetch the last requisition form code and auto-increment it
    function fetchRequisitionCode(storeSection) {
      $.ajax({
        url: '', // Send request to the same file
        method: 'POST',
        data: { storeSection: storeSection, action: 'getLastRequisitionCode' },
        dataType: 'json',
        success: function (data) {
          if (data.lastCode) {
            // Use the code directly from the server without incrementing again
            $('#requisitionformcode').val(data.lastCode);
          } else {
            // If no previous requisition code exists, use default
            $('#requisitionformcode').val(storeSection + '/' + new Date().getFullYear() + '/001');
          }
        },
        error: function (xhr, status, error) {
          console.log('Error fetching requisition code:', error);
          // Fallback to a default code
          $('#requisitionformcode').val(storeSection + '/' + new Date().getFullYear() + '/001');
        }
      });
    }
  });
</script>

<!--  Handle form submission -->
<script>
    
    $("#requestForm").on("submit", function (e) {
        e.preventDefault(); // Prevent default form submission
        let formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: "requisitionform.php", // Path to PHP file
            type: "POST",
            data: formData,
            dataType: "json", // Expect JSON response
            success: function (response) {
                if (response.message) {
                    // Create a success alert at the top of the form
                    let alertHtml = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    
                    // Insert the alert at the top of the form
                    $("#requestForm").prepend(alertHtml);
                    
                    // Reset the form completely
                    $("#requestForm")[0].reset();
                    
                    // Clear all readonly fields that might not be reset by form.reset()
                    $('#itemcode, #category, #totalremainingquantity, #reservedquantity, #initialprice, #currentprice, #requisitionformcode, #storesection').val('');
                    
                    // Clear the search results
                    $('#results').empty();
                    
                    // Reset any dropdowns
                    $('#reservedfordept').empty().append('<option value="">Select Reserved Department</option>');
                    
                    // Scroll to the top of the form to see the success message
                    $('html, body').animate({
                        scrollTop: $("#requestForm").offset().top - 100
                    }, 500);
                    
                    // Automatically remove the alert after 5 seconds
                    setTimeout(function() {
                        $('.alert').alert('close');
                    }, 5000);
                } else {
                    alert("Unexpected response format.");
                }
            },
            error: function (xhr, status, error) {
                console.error("XHR Response Text:", xhr.responseText); // Debug response
                alert("Failed to submit: " + xhr.responseText);
            }
        });
    });

    // Handle item selection
    $(document).on('click', '.result-item', function () {
        let itemname = $(this).text();
        let itemcode = $(this).data('code');
        let category = $(this).data('category');
        let storesection = $(this).data('store');
        let totalremainingquantity = $(this).data('totalremainingquantity');
        let reservedquantity = $(this).data('reservedquantity');

        // Populate fields with selected item details
        $("#itemName").val(itemname);
        $("#itemcode").val(itemcode);
        $("#category").val(category);
        $("#storesection").val(storesection);
        $("#totalremainingquantity").val(totalremainingquantity);
        $("#reservedquantity").val(reservedquantity);
        
        // Now, fetch the reserved quantity based on the populated itemcode
        fetchReservedQuantity(itemcode);
 
 
        // Hide the suggestions dropdown after selection
        $('#results').empty().hide();  // Hide the dropdown after selecting an item
    });

    // Trigger search on keyup
    $('#searchItem').on('keyup', function () {
        let query = $(this).val();

        if (query.length >= 1) { // Start searching from the first letter
            $('#results').html('<div>Loading...</div>');
            $.ajax({
                url: '', // Leave empty to send the request to the same PHP file
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
                                    data-totalremainingquantity="${item.totalremainingquantity}"
                                    data-reservedquantity="${item.reservedquantity}">
                                    ${item.itemname}
                                </div>
                            `);
                        });
                        results.show(); // Show the dropdown if there are results
                    } else {
                        results.append('<div>No results found</div>');
                        results.show(); // Show the dropdown even if no results
                    }
                },
                error: function (xhr, status, error) {
                    $('#results').html(`<div>Error fetching results: ${xhr.responseText || error}</div>`);
                }
            });
        } else {
            $('#results').empty().hide(); // Hide results when query is empty
        }
    });
</script>


<script>
  $(document).ready(function () {
    // Fetch reserved departments when an item is selected
    $(document).on('click', '.result-item', function () {
        let itemcode = $(this).data('code');
        $("#itemcode").val(itemcode);

        fetchReservedDepartments(itemcode);  // Reserved departments dropdown
        fetchTotalRemainingReserved(itemcode); // Total remaining reserved quantity
    });

    // Fetch reserved quantity for selected department
    $("#reservedfordept").change(function () {
        let selectedDept = $(this).val();
        let itemcode = $("#itemcode").val();

        if (selectedDept) {
            fetchReservedQuantity(itemcode, selectedDept);
        }
    });
    });


    function fetchReservedDepartments(itemcode) {
    if (itemcode) {
        $.ajax({
            url: 'getreserved_dept.php', 
            type: 'POST',
            data: { action: 'getReservedDepartments', itemcode: itemcode },
            dataType: 'json',
            success: function(response) {
                let deptDropdown = $("#reservedfordept");
                deptDropdown.empty().append('<option value="">Select Reserved Department</option>');
                reservedDepartments = response.map(dept => dept.reservedfordept);
                if (Array.isArray(response) && response.length > 0) {
                    response.forEach(dept => {
                        deptDropdown.append(`<option value="${dept.reservedfordept}">${dept.reservedfordept}</option>`);
                    });
                } else {
                    console.error("No reserved departments found:", response.error);
                    deptDropdown.append('<option value="">No reserved departments</option>');
                    deptDropdown.prop("disabled", true); // Disable dropdown if no departments
                }
            },
            error: function(xhr) {
                console.error('Error fetching reserved departments:', xhr.responseText);
                alert("Error fetching reserved departments.");
            }
        });
    }
    }

    // Function to check if selected department is in reserved departments
    $("#department").change(function () {
    let selectedDept = $(this).val();
    let reservedDeptDropdown = $("#reservedfordept");

    if (reservedDepartments.includes(selectedDept)) {
        reservedDeptDropdown.prop("disabled", false);
    } else {
        reservedDeptDropdown.prop("disabled", true).val(""); // Disable and reset dropdown
    }
  });
    // Fetch total remaining reserved quantity
    function fetchTotalRemainingReserved(itemcode) {
    if (itemcode) {
        $.ajax({
            url: 'process_reservedquantity.php',
            type: 'POST',
            data: { itemcode: itemcode },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $("#reservedquantity").val(response.totalRemainingReserved);
                } else {
                    $("#reservedquantity").val(0);
                    alert("No remaining reserved quantity found.");
                }
            },
            error: function() {
                alert("Error fetching total remaining reserved quantity.");
            }
        });
    }
    }


    function fetchReservedQuantity(itemcode, reservedfordept) {
        if (itemcode && reservedfordept) {
            $.ajax({
                url: 'getreserved_dept.php',
                type: 'POST',
                data: { action: 'getReservedQuantity', itemcode: itemcode, reservedfordept: reservedfordept },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $("#reservedquantity").val(response.reserved_quantity);
                        $("#remainingquantity").val(response.remaining_quantity);
                    } else {
                        $("#reservedquantity").val(0);
                        $("#remainingquantity").val(0);
                        console.error('Error:', response.error);
                        alert("Error fetching reserved quantity: " + response.error);
                    }
                },
                error: function(xhr, status, error) {
                console.error('AJAX Error:', error, xhr.responseText);
                alert("AJAX request failed. Check console for details.");
            }
        });
    }
    }
</script>
<script>
// Function to check if an item is locked
function checkItemLock(itemcode) {
    $.ajax({
        url: '',
        type: 'POST',
        data: { action: 'checkItemLock', itemcode: itemcode },
        dataType: 'json',
        success: function(response) {
            if (response.locked) {
                showLockCountdown(response.minutesRemaining);
            } else {
                // Remove any existing countdown
                $("#lockCountdown").removeClass().addClass("d-none").empty();
                // Enable submit button if it was disabled
                $("#submitRequest").prop("disabled", false).removeClass("btn-secondary").addClass("btn-primary");
            }
        },
        error: function(xhr, status, error) {
            console.error('Error checking item lock:', error);
        }
    });
}
</script>
<script>
// Function to show a message
function showMessage(type, message) {
    $("#responseMessage")
        .removeClass()
        .addClass(`alert alert-${type} mt-3`)
        .html(message)
        .fadeIn();
}
</script>
<script>
// Function to show countdown timer
function showLockCountdown(minutes) {
    // Convert minutes to seconds
    let totalSeconds = minutes * 60;
    let remainingSeconds = totalSeconds;
    
    // Create or update countdown element
    let countdownEl = $("#lockCountdown");
    countdownEl.removeClass("d-none").addClass("alert alert-warning");
    
    // Update the countdown text
    function updateCountdown() {
        const mins = Math.floor(remainingSeconds / 60);
        const secs = remainingSeconds % 60;
        countdownEl.html(`
            <strong><i class="fas fa-lock"></i> Item Locked:</strong> Please wait ${mins}:${secs < 10 ? '0' + secs : secs} before requesting this item again.
            <div class="progress mt-2">
                <div class="progress-bar bg-info" role="progressbar" 
                     style="width: ${(remainingSeconds / totalSeconds) * 100}%" 
                     aria-valuenow="${remainingSeconds}" aria-valuemin="0" aria-valuemax="${totalSeconds}">
                </div>
            </div>
        `);
    }
    
    // Initial update
    updateCountdown();
    
    // Disable submit button
    $("#submitRequest").prop("disabled", true).removeClass("btn-primary").addClass("btn-secondary");
    
    // Clear any existing interval
    if (window.countdownInterval) {
        clearInterval(window.countdownInterval);
    }
    
    // Start countdown
    window.countdownInterval = setInterval(function() {
        remainingSeconds--;
        
        if (remainingSeconds <= 0) {
            // Countdown complete
            clearInterval(window.countdownInterval);
            countdownEl.removeClass("alert-warning").addClass("alert-success")
                .html("<strong><i class='fas fa-unlock'></i> Lock Released!</strong> You can now submit your request.");
            
            // Re-enable submit button
            $("#submitRequest").prop("disabled", false).removeClass("btn-secondary").addClass("btn-primary");
            
            // Remove the countdown after a few seconds
            setTimeout(function() {
                countdownEl.fadeOut(500, function() {
                    $(this).removeClass().addClass("d-none").empty();
                });
            }, 5000);
        } else {
            // Update countdown
            updateCountdown();
        }
    }, 1000);
}

</script>


<!-- calculate price -->
<script>
  $(document).ready(function () {
      $("#quantityrequested").on("input", function () {
          let initialPrice = parseFloat($("#initialprice").val()) || 0;
          let quantity = parseInt($(this).val()) || 0;
          let currentPrice = initialPrice * quantity;

          $("#currentprice").val(currentPrice);
      });
  });
</script>

<script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
