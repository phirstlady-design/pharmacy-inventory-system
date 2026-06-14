<?php
session_start();

if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
    header('Location: index.php'); // Change to your actual login page
    exit();
}
include("include/connect.php");
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
    SELECT rb.itemname, rb.itemcode, rb.category, rb.storesection, rb.supplier, 
        rb.quantity_supplied, rb.unitofmeasurement, rb.deliverydate, rb.manufacturedate, rb.expirydate, 
        rb.reservedquantity, rb.reservedfordept, rb.remainingquantity, s.company_name, s.contact_phone, s.contact_email
    FROM supplier s
    JOIN receivingbay rb ON s.supplier = rb.supplier
    WHERE DATE(rb.deliverydate) BETWEEN '$start_date' AND '$end_date'
)
UNION ALL
(
    SELECT rbn.itemname, rbn.itemcode, rbn.category, rbn.storesection, rbn.supplier, 
        rbn.quantity_supplied, rbn.unitofmeasurement, rbn.deliverydate, rbn.manufacturedate, rbn.expirydate, 
        rbn.reservedquantity, rbn.reservedfordept, rbn.remainingquantity, s.company_name, s.contact_phone, s.contact_email
    FROM supplier s
    JOIN receivingbaynewitems rbn ON s.supplier = rbn.supplier
    WHERE DATE(rbn.deliverydate) BETWEEN '$start_date' AND '$end_date'
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
    <title>OAUTHC Store Report</title>
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
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
    <div class="container p-5">
        <h1 class="text-center my-4">OAUTHC Store Report</h1>
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="report_type" class="form-label">Select Report Type:</label>
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
                <a href="dashboard.php" class="btn btn-danger">Back To Dashboard</a>
                <a href="logout.php" class="btn btn-danger">Log Out</a> 
                <input type="text" class="form-control mt-4" id="searchItem" placeholder="Search for itemname, code, or categories..." onkeyup="filterItem()">   

                </div>
            </div>
        </form>
        <div class="card">
            <div class="card-header">
                <h3>Items and Supplier Details</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="itemResults">
                    <thead class="table-dark">
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
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['itemname']; ?></td>
                            <td><?php echo $row['itemcode']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['storesection']; ?></td>
                            <td><?php echo $row['supplier']; ?></td>
                            <td><?php echo $row['quantity_supplied']; ?></td>
                            <td><?php echo $row['unitofmeasurement']; ?></td>
                            <td><?php echo $row['deliverydate']; ?></td>
                            <td><?php echo $row['manufacturedate']; ?></td>
                            <td><?php echo $row['expirydate']; ?></td>
                            <td><?php echo $row['reservedquantity']; ?></td>
                            <td><?php echo $row['reservedfordept']; ?></td>
                            <td><?php echo $row['remainingquantity']; ?></td>
                            <td><?php echo $row['company_name']; ?></td>
                            <td><?php echo $row['contact_phone']; ?></td>
                            <td><?php echo $row['contact_email']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
