<?php 
session_start();
include("include/connect.php");

if (isset($_GET['code']) && !empty($_GET['code'])) {
    if (!isset($_SESSION['fullname'])) {
        echo "<p class='text-danger'>You must be logged in to perform this action.</p>";
        exit;
    }

    $itemCode = mysqli_real_escape_string($conn, $_GET['code']);
    $officerInCharge = mysqli_real_escape_string($conn, $_SESSION['fullname']);

    // Get store section of the item
    $storeQuery = "SELECT storesection FROM codingunit WHERE itemcode = '$itemCode' LIMIT 1";
    $storeResult = $conn->query($storeQuery);

    if ($storeResult->num_rows > 0) {
        $storeRow = $storeResult->fetch_assoc();
        $storeSection = $storeRow['storesection'];

        // Store Mapping
        $storeMapping = [
            'labStore' => 'labconfirm',
            'electricalStore' => 'electricalconfirm',
            'hardwareStore' => 'hardwareconfirm',
            'medicalStore' => 'medconfirm',
            'civilStore' => 'civilconfirm',
            'healthStationeryStore' => 'healthconfirm',
            'generalStationeryStore' => 'stationeryconfirm'
        ];

        $confirmTable = $storeMapping[$storeSection] ?? null;

        if ($confirmTable) {
        
        $query = "
        SELECT DISTINCT 
            l.itemcode, 
            l.itemname, 
            l.remainingquantity, 
            l.initialprice, 
            l.quantityreleased, 
            l.currentprice, 
            r.department, 
            r.employeeid,  
            l.officerincharge, 
            r.createdon, 
            r.collectedby,
            l.previous_balance,        
            l.current_balance,
            l.request_status,
            -- Fetch exact total reserved at this time
            (
                SELECT COALESCE(SUM(rb.reservedquantity), 0) 
                FROM (
                    SELECT itemcode, reservedquantity FROM receivingbay
                    UNION ALL
                    SELECT itemcode, reservedquantity FROM receivingbaynewitems
                ) rb
                WHERE rb.itemcode = r.itemcode
            ) - (
                SELECT COALESCE(SUM(req.collectedreserved), 0) 
                FROM requests req
                WHERE req.itemcode = r.itemcode 
                AND req.createdon <= r.createdon
            ) AS totalreserved,
        
            -- Correct collected reserved at this transaction
            r.collectedreserved AS collectedreserved,
        
            -- Correct remaining reserved at this transaction
            r.remainingreserved AS remainingreserved
        
        FROM $confirmTable l
        JOIN requests r 
            ON l.itemcode = r.itemcode 
            AND l.department = r.department
            AND l.quantityreleased = r.quantityreleased  -- Ensures request matches exact release quantity
        WHERE l.itemcode = '$itemCode'
        ORDER BY r.createdon ASC
        ";
  
        
        
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo "<table class='detailedclass' border='1' cellspacing='0' cellpadding='5'>";
                echo "<tr>
                        <th>S/N</th> 
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Item Price</th>
                        <th>Previous Balance</th>
                        <th>Quantity Released</th>
                        <th>Current Balance</th>
                        <th>Total Price</th>
                        <th>Collecting Department</th>
                        <th>Status</th>
                        <th>Employee ID</th>
                        <th>Collected By</th>
                        <th>Released By</th>
                        <th>Date</th>
                      
                      </tr>";
                $sn = 1; 

                while ($row = $result->fetch_assoc()) {
                    // Correct total store per transaction
                    $totalstore = $row['remainingquantity'] + $row['totalreserved'];

                    echo "<tr>
                            <td>" . $sn++ . "</td>
                            <td>" . htmlspecialchars($row['itemcode']) . "</td>
                            <td>" . htmlspecialchars($row['itemname']) . "</td>
                            <td>" . htmlspecialchars($row['initialprice']) . "</td>
                            <td>" . htmlspecialchars($row['previous_balance']) . "</td>
                            <td>" . htmlspecialchars($row['quantityreleased']) . "</td>
                            <td>" . htmlspecialchars($row['current_balance']) . "</td>
                            <td>" . htmlspecialchars($row['currentprice']) . "</td>
                            <td>" . htmlspecialchars($row['department']) . "</td>
                              <td>" . htmlspecialchars($row['request_status']) . "</td> 
                            <td>" . htmlspecialchars($row['employeeid']) . "</td>
                            <td>" . htmlspecialchars($row['collectedby']) . "</td>
                            <td>" . htmlspecialchars($row['officerincharge']) . "</td>
                            <td>" . htmlspecialchars($row['createdon']) . "</td>
                         
                            
                           
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='text-danger'>No details found for the provided item code.</p>";
            }
        } else {
            echo "<p class='text-danger'>Store section not recognized.</p>";
        }
    } else {
        echo "<p class='text-danger'>Item code not found in any store section.</p>";
    }
}

$conn->close();
?>
