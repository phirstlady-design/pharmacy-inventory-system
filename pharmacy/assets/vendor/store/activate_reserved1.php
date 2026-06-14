<?php

// Show all errors during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering
ob_start();

include("include/connect.php");

// Add this line to ensure proper JSON response handling
header('Content-Type: application/json');

// Valid store tables
$allowedStores = [
    'labstore', 'medstore', 'stationerystore',
    'electricalstore', 'hardwarestore', 'civilstore',
    'healthstore', 'receivingbay', 'receivingbaynewitems'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 1. Fetch reserved items
    if (isset($_POST['store'])) {
        // Remove JSON header for HTML response
        header('Content-Type: text/html');
        
        $store = $_POST['store'];

        if (!in_array($store, $allowedStores)) {
            echo "<div class='alert alert-danger'>Invalid store selected.</div>";
            exit;
        }

        $stmt = $conn->prepare("
            SELECT id, itemcode, itemname, reservedquantity, reservedfordept, deliverydate 
            FROM $store 
            WHERE reservedquantity > 0 
              AND reserved_released = 0 
              AND DATEDIFF(NOW(), deliverydate) >= 3
        ");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $tableRows = '';
            while ($row = $result->fetch_assoc()) {
                $tableRows .= "<tr id='row_{$row['id']}'>
                    <td>{$row['itemcode']}</td>
                    <td>{$row['itemname']}</td>
                    <td>{$row['reservedquantity']}</td>
                    <td>{$row['reservedfordept']}</td>
                    <td>{$row['deliverydate']}</td>
                    <td>
                        <button class='btn btn-sm btn-primary' 
                            onclick=\"activateItem(
                                {$row['id']}, 
                                '{$row['itemcode']}', 
                                {$row['reservedquantity']}, 
                                '{$row['reservedfordept']}', 
                                '$store', 
                                'receivingbay'
                            )\">Activate</button>
                    </td>
                </tr>";
            }

            echo "
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Reserved Quantity</th>
                            <th>Department</th>
                            <th>Delivery Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        $tableRows
                    </tbody>
                </table>
            ";
        } else {
            echo "<div class='alert alert-info'>No reserved items found for activation.</div>";
        }

        exit;
    }

// In your activate_reserved.php file, replace the activation code with this:

if (isset($_POST['action']) && $_POST['action'] === 'activate_reserved') {
    ob_clean(); // Remove any prior output
    
    try {
        $id = (int)$_POST['reserved_id'];
        $itemcode = $_POST['itemcode'];
        $quantity = (int)$_POST['reservedquantity'];
        $department = $_POST['department'];
        $storeTable = $_POST['storeTable']; // The specific store table (labstore, medstore, etc.)
        $sourceTable = $_POST['sourceTable']; // This should be 'receivingbay' from your HTML
        
        if (!in_array($storeTable, $allowedStores) || !in_array($sourceTable, $allowedStores)) {
            throw new Exception('Invalid table specified.');
        }

        // Start transaction for data integrity
        $conn->begin_transaction();
        
        // Step 1: Determine which receiving table the item belongs to
        $checkReceivingBay = $conn->prepare("SELECT id FROM receivingbay WHERE itemcode = ?");
        $checkReceivingBay->bind_param("s", $itemcode);
        $checkReceivingBay->execute();
        $receivingBayResult = $checkReceivingBay->get_result();
        
        $checkNewItems = $conn->prepare("SELECT id FROM receivingbaynewitems WHERE itemcode = ?");
        $checkNewItems->bind_param("s", $itemcode);
        $checkNewItems->execute();
        $newItemsResult = $checkNewItems->get_result();
        
        // Determine which receiving table to update
        $receivingTable = null;
        if ($receivingBayResult->num_rows > 0) {
            $receivingTable = 'receivingbay';
        } elseif ($newItemsResult->num_rows > 0) {
            $receivingTable = 'receivingbaynewitems';
        } else {
            throw new Exception("Item with code $itemcode not found in any receiving table.");
        }
        
        // Log which tables will be updated (for debugging)
        error_log("Updating tables for itemcode $itemcode: $storeTable and $receivingTable");

        // Step 2: Mark as released in the store table
        $updateStoreReleased = $conn->prepare("UPDATE $storeTable SET reserved_released = 1 WHERE itemcode = ?");
        $updateStoreReleased->bind_param("s", $itemcode);
        if (!$updateStoreReleased->execute()) {
            throw new Exception("Failed to update reserved_released in $storeTable: " . $updateStoreReleased->error);
        }
        error_log("Updated reserved_released in $storeTable for itemcode $itemcode. Affected rows: " . $updateStoreReleased->affected_rows);
        
        // Step 3: Mark as released in the receiving table
        $updateReceivingReleased = $conn->prepare("UPDATE $receivingTable SET reserved_released = 1 WHERE itemcode = ?");
        $updateReceivingReleased->bind_param("s", $itemcode);
        if (!$updateReceivingReleased->execute()) {
            throw new Exception("Failed to update reserved_released in $receivingTable: " . $updateReceivingReleased->error);
        }
        error_log("Updated reserved_released in $receivingTable for itemcode $itemcode. Affected rows: " . $updateReceivingReleased->affected_rows);

        // Step 4: Update totalremainingquantity in both tables
        $tablesToUpdate = [$storeTable, $receivingTable];
        foreach ($tablesToUpdate as $table) {
            $updateQty = $conn->prepare("UPDATE $table SET totalremainingquantity = totalremainingquantity + ? WHERE itemcode = ?");
            $updateQty->bind_param("is", $quantity, $itemcode);
            if (!$updateQty->execute()) {
                throw new Exception("Failed to update quantity in $table: " . $updateQty->error);
            }
        }

        // Step 5: Mark reserved quantity as collected in both tables
        foreach ($tablesToUpdate as $table) {
            $updateCollected = $conn->prepare("UPDATE $table SET collectedreserved = reservedquantity WHERE itemcode = ?");
            $updateCollected->bind_param("s", $itemcode);
            if (!$updateCollected->execute()) {
                throw new Exception("Failed to update collected status in $table: " . $updateCollected->error);
            }
        }

        // Step 6: Send notification
        // $msg = "Your reserved item with code $itemcode has been activated for general collection.";
        // $notify = $conn->prepare("INSERT INTO notifications (message, created_at) VALUES (?, NOW())");
        // $notify->bind_param("s", $msg);
        // if (!$notify->execute()) {
        //     throw new Exception("Failed to send notification: " . $notify->error);
        // }
 // Step 7: update reserved table
            $updateReserved = $conn->prepare("UPDATE reserved SET reservedquantity = 0, reserved_released = 1 WHERE itemcode = ?");
            $updateReserved->bind_param("s", $itemcode);
            if (!$updateReserved->execute()) {
                throw new Exception("Failed to update collected status in reserved: " . $updateReserved->error);
            }
        
        // Commit the transaction
        $conn->commit();
        
        echo json_encode(['status' => 'success', 'message' => 'Reserved item activated successfully.']);
    } catch (Exception $e) {
        // Rollback on error
        if ($conn->inTransaction()) {
            $conn->rollback();
        }
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}
}
?>