<?php
session_start();

if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
    header('Location: index.php'); // Change to your actual login page
    exit();
}
include("include/connect.php");

$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : '';

// Default date range
$start_date = '';
$end_date = '';

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
    margin-left:100px;
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
        <div class="sidebar col-md-1" style="margin-right:20px;" >
        <ul class="navbar-nav mx-auto" >
        <li class="nav-item"><a class="nav-link text-center"href="requestreport.php">Request Report</a></li>
        <li class="nav-item"><a class="nav-link text-center"href="codingunitreport.php">Coding Report</a></li>
        <li class="nav-item"><a class="nav-link text-center"href="dashboard.php">Back To Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-center"href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="main-content col-md-12 ">    
<h2 class="text-center text-danger display-4" style="margin-top: 100px;">WELCOME TO YOUR REPORT PAGE</h2>
<h4 class="text-center text-dark display-6">Select Report Type to View</h4>
    </div>
    </div>
    <script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
