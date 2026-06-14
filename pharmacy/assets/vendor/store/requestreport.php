
<?php
session_start();

if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
    header('Location: index.php'); // Change to your actual login page
    exit();
}
include("include/connect.php");

// Get user-selected start and end date
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : '';

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
    // Ensure user has selected both start and end dates
    if (empty($start_date) || empty($end_date)) {
        die("Please select a valid start and end date for the custom range.");
    }
}

// Fetch report data based on the date range
$query = "(
    SELECT  * FROM requests
    WHERE DATE(request_date) BETWEEN '$start_date' AND '$end_date'
    ORDER BY storesection
)";

$result = $conn->query($query);
if (!$result) {
    die("SQL Error: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAUTHC Request Report</title>
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

.container {
    /* display: flex; */
    width: 100%;
}
.sidebar {
    /* width: 15%; */
    background-color: #1e3d73;
    color: white;
    padding-top: 20px;
    height: 100vh;
    margin-top: 95px;
    position: fixed; /* Keeps it in place on scroll */
    top: 0;
    left: 0;
    overflow-y: auto; /* Adds scrolling if content exceeds viewport height */
    
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
    /* margin-left:100px; */
}

    </style>
</head>
<body>
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
    <div class="container">
    <h1 class="text-center text-success text-white bg-success w-100 fixed-top mx-auto py-4">OAUTHC Request Report</h1>
    
    <div class="row d-flex justify-content-center align-item-center pt-2">  
   
<div class="main-content col-md-12 ">    
    <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                <label for="report_type" class="form-label px-4">Select Report Type:</label>
            <select class="form-select px-4" id="report_type" name="report_type">
                <option value="">Choose Report Type</option>
                <option value="daily" <?php echo ($report_type == 'daily') ? 'selected' : ''; ?>>Daily</option>
                <option value="weekly" <?php echo ($report_type == 'weekly') ? 'selected' : ''; ?>>Weekly</option>
                <option value="monthly" <?php echo ($report_type == 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                <option value="quarterly" <?php echo ($report_type == 'quarterly') ? 'selected' : ''; ?>>Quarterly</option>
                <option value="yearly" <?php echo ($report_type == 'yearly') ? 'selected' : ''; ?>>Yearly</option>
                <option value="custom" <?php echo ($report_type == 'custom') ? 'selected' : ''; ?>>Custom Date</option>
            </select>
        </div>

        <!-- Start Date -->
        <div class="col-md-3">
            <label for="start_date" class="form-label">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
        </div>

        <!-- End Date -->
        <div class="col-md-3">
            <label for="end_date" class="form-label">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
        </div>
                <div class="col-md-4 mt-4">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                    <button type="button" onclick="window.print()" class="btn btn-success no-print">Print Report</button>
                </div>

                <div class="col-md-4 mt-4">
                <a href="reportmain.php" class="btn btn-danger">Back To Dashboard</a>
                <a href="logout.php" class="btn btn-danger">Log Out</a> 
                <input type="text" class="form-control mt-4" id="searchItem" placeholder="Search for itemname, code, or categories..." onkeyup="filterItem()">   

                </div>
            </div>
        </form>
        <div class="card ">
            <div class="card-header">
                <h3 class="px-3">Items and Supplier Details</h3>
            <div class="card-body">
                <table class="table table-responsive table-striped" id="itemResults" >
                    
                    <thead class="table-dark">
                        <tr style="font-size: 12px;">
                            <th>Item Name</th>
                            <th>Item Code</th>
                            <th>Category</th>
                            <th>Store Section</th>
                            <th>Quantity Requested</th>
                            <th>Requisition Form code</th>
                            <th>Item Request Code</th>
                            <th>Department</th>
                            <th> Employee ID</th>
                            <th>Name of Employee</th>
                            <th>Issued By</th>
                            <th>Quantity Released</th>
                            <th>Remaining Quantity</th>
                            <th>Reserved Quantity</th>
                            <th>Requisition Date</th>
                            <th>Requisition Status</th>
                        </tr>
                    </thead>
                   <tbody id="itemsTableBody">
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['itemname']; ?></td>
                            <td><?php echo $row['itemcode']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['storesection']; ?></td>
                            <td><?php echo $row['quantityrequested']; ?></td>
                            <td><?php echo $row['requisitionformcode']; ?></td>
                            <td><?php echo $row['itemrequestformcode']; ?></td>
                            <td><?php echo $row['department']; ?></td>
                            <td><?php echo $row['employeeid']; ?></td>
                            <td><?php echo $row['collectedby']; ?></td>
                            <td><?php echo $row['issuedby']; ?></td>
                            <td><?php echo $row['quantityreleased']; ?></td>
                            <td><?php echo $row['remainingquantity']; ?></td>
                            <td><?php echo $row['reservedquantity']; ?></td>
                            <td><?php echo $row['request_date']; ?></td>
                            <td><?php echo $row['request_status']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>
<!-- <div class="sidebar col-md-1" style="margin-right:20px;" > -->
    </div>
    </div>
    <script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
