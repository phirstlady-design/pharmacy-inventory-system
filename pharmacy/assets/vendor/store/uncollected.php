<?php
include("include/connect.php");

// Get item code and department from the POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemCode = $_POST['itemCode']; // Item code passed from JS
    $department = $_POST['department']; // Department passed from JS

    // Fetch the item from codingunit based on the passed parameters
    $query = $conn->prepare("SELECT * FROM codingunit WHERE itemcode = ? AND department = ?");
    $query->bind_param("ss", $itemCode, $department);
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        $releaseDate = $row['release_date']; // Assuming 'release_date' is the field that holds the time it was released
        $thresholdTime = date('Y-m-d H:i:s', strtotime($releaseDate . ' -48 hours')); // Subtract 48 hours from release_date

        // Check if the current time has passed the 48-hour threshold
        $currentTime = date('Y-m-d H:i:s');
        
        if ($currentTime > $thresholdTime) {
            // Proceed with the logic for uncollected items after 48 hours
            
            // 1. Insert uncollected item into labconfirm with request_status = 'not collected'
            $insertLabConfirm = $conn->prepare("
            INSERT INTO labconfirm (itemname, itemcode, department, officerincharge, quantityreleased, remainingquantity, createdon, request_status)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), 'not collected')
            ");
            $insertLabConfirm->bind_param(
                "ssssii", 
                $row['itemname'], 
                $row['itemcode'], 
                $row['department'], 
                $row['officerincharge'], 
                $row['quantityreleased'], 
                $row['remainingquantity']
            );
            $insertLabConfirm->execute();

            // 2. Update codingunit request_status to 'not collected'
            $updateCodingUnit = $conn->prepare("UPDATE codingunit SET request_status = 'not collected' WHERE itemcode = ? AND department = ?");
            $updateCodingUnit->bind_param("ss", $row['itemcode'], $row['department']);
            $updateCodingUnit->execute();

            // 3. Update remainingquantity in receivingbay
            $updateReceivingBay = $conn->prepare("UPDATE receivingbay SET remainingquantity = remainingquantity + ? WHERE itemcode = ?");
            $updateReceivingBay->bind_param("is", $row['quantityreleased'], $row['itemcode']);
            $updateReceivingBay->execute();

            // 4. Update remainingquantity in requests
            $updateRequests = $conn->prepare("UPDATE requests SET remainingquantity = remainingquantity + ? WHERE itemcode = ? AND department = ?");
            $updateRequests->bind_param("iss", $row['quantityreleased'], $row['itemcode'], $row['department']);
            $updateRequests->execute();

            // 5. Update remainingquantity in storetables
            $storeTable = strtolower($row['department']) . 'store'; // Example: 'labstore', 'stationerystore'
            $updateStoreQuery = $conn->prepare("UPDATE $storeTable SET remainingquantity = remainingquantity - ? WHERE itemcode = ? AND department = ?");
            $updateStoreQuery->bind_param("iis", $row['quantityreleased'], $row['itemcode'], $row['department']);
            $updateStoreQuery->execute();
        }
    }

    echo "48-hour uncollected items processed.";
}
?>
