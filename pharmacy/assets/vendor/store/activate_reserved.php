<?php
session_start();
// Show all errors during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering
ob_start();

include("include/connect.php");

// Valid store tables
$allowedStores = [
    'labstore', 'medstore', 'stationerystore',
    'electricalstore', 'hardwarestore', 'civilstore',
    'healthstore', 'receivingbay', 'receivingbaynewitems'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 1. Fetch reserved items (items that have passed 3 days)
    if (isset($_POST['store'])) {
        // Set HTML header for table response
        header('Content-Type: text/html');
        
        $store = $_POST['store'];

        if (!in_array($store, $allowedStores)) {
            echo "<div class='alert alert-danger'><i class='fas fa-exclamation-triangle me-2'></i>Invalid store selected.</div>";
            exit;
        }

        try {
            // Query to get items that have passed 3 days from delivery date
            $stmt = $conn->prepare("
                SELECT id, itemcode, itemname, reservedquantity, reservedfordept, deliverydate,
                       DATEDIFF(NOW(), deliverydate) as days_passed
                FROM $store 
                WHERE reservedquantity > 0 
                  AND reserved_released = 0 
                  AND DATEDIFF(NOW(), deliverydate) >= 3
                ORDER BY deliverydate ASC, itemcode ASC
            ");
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table class='table table-striped table-hover'>
                        <thead class='table-dark'>
                            <tr>
                                <th><i class='fas fa-barcode me-1'></i>Item Code</th>
                                <th><i class='fas fa-box me-1'></i>Item Name</th>
                                <th><i class='fas fa-sort-numeric-up me-1'></i>Reserved Qty</th>
                                <th><i class='fas fa-building me-1'></i>Department</th>
                                <th><i class='fas fa-calendar me-1'></i>Delivery Date</th>
                                <th><i class='fas fa-clock me-1'></i>Days Passed</th>
                                <th><i class='fas fa-cogs me-1'></i>Action</th>
                            </tr>
                        </thead>
                        <tbody>";

                while ($row = $result->fetch_assoc()) {
                    $daysPassed = $row['days_passed'];
                    $urgencyClass = '';
                    $urgencyIcon = '';
                    
                    if ($daysPassed >= 7) {
                        $urgencyClass = 'table-danger';
                        $urgencyIcon = '<i class="fas fa-exclamation-triangle text-danger me-1"></i>';
                    } elseif ($daysPassed >= 5) {
                        $urgencyClass = 'table-warning';
                        $urgencyIcon = '<i class="fas fa-exclamation text-warning me-1"></i>';
                    }

                    echo "<tr id='row_{$row['id']}' class='$urgencyClass'>
                            <td><span class='badge bg-primary'>{$row['itemcode']}</span></td>
                            <td><strong>{$row['itemname']}</strong></td>
                            <td><span class='badge bg-info'>{$row['reservedquantity']}</span></td>
                            <td><span class='badge bg-secondary'>{$row['reservedfordept']}</span></td>
                            <td>{$row['deliverydate']}</td>
                            <td>{$urgencyIcon}<strong>{$daysPassed} days</strong></td>
                            <td>
                                <button class='btn btn-success btn-sm' 
                                    onclick=\"activateItem(
                                        {$row['id']}, 
                                        '{$row['itemcode']}', 
                                        {$row['reservedquantity']}, 
                                        '{$row['reservedfordept']}', 
                                        '$store', 
                                        'receivingbay'
                                    )\">
                                    <i class='fas fa-play me-1'></i>Activate
                                </button>
                            </td>
                          </tr>";
                }

                echo "</tbody></table>";
                
                // Add summary info
                echo "<div class='alert alert-info mt-3'>
                        <i class='fas fa-info-circle me-2'></i>
                        <strong>Found {$result->num_rows} reserved items</strong> that have passed 3 days from delivery date.
                        <br><small class='text-muted'>Items are sorted by delivery date (oldest first).</small>
                      </div>";

            } else {
                echo "<div class='alert alert-info'>
                        <i class='fas fa-info-circle me-2'></i>
                        <strong>No reserved items found</strong> that have passed 3 days from delivery date in <strong>" . ucfirst($store) . "</strong>.
                        <br><small class='text-muted'>Items become available for activation 3 days after their delivery date.</small>
                      </div>";
            }

        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>
                    <i class='fas fa-exclamation-triangle me-2'></i>
                    <strong>Database Error:</strong> " . $e->getMessage() . "
                  </div>";
        }

        exit;
    }

    // 2. Activate reserved items
    if (isset($_POST['action']) && $_POST['action'] === 'activate_reserved') {
        // Set JSON header for activation response
        header('Content-Type: application/json');
        ob_clean(); // Remove any prior output
        
        try {
            $id = (int)$_POST['reserved_id'];
            $itemcode = $_POST['itemcode'];
            $quantity = (int)$_POST['reservedquantity'];
            $department = $_POST['department'];
            $storeTable = $_POST['storeTable'];
            $sourceTable = $_POST['sourceTable'];
            
            if (!in_array($storeTable, $allowedStores) || !in_array($sourceTable, $allowedStores)) {
                throw new Exception('Invalid table specified.');
            }

            // Validate that the item exists and meets the 3-day criteria
            $validateStmt = $conn->prepare("
                SELECT id, itemcode, DATEDIFF(NOW(), deliverydate) as days_passed 
                FROM $storeTable 
                WHERE id = ? AND itemcode = ? AND reservedquantity > 0 AND reserved_released = 0
            ");
            $validateStmt->bind_param("is", $id, $itemcode);
            $validateStmt->execute();
            $validateResult = $validateStmt->get_result();
            
            if ($validateResult->num_rows === 0) {
                throw new Exception("Item not found or already activated.");
            }
            
            $itemData = $validateResult->fetch_assoc();
            if ($itemData['days_passed'] < 3) {
                throw new Exception("Item cannot be activated yet. Only " . $itemData['days_passed'] . " days have passed (minimum 3 days required).");
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
            
            // Step 2: Mark as released in the store table
            $updateStoreReleased = $conn->prepare("UPDATE $storeTable SET reserved_released = 1 WHERE id = ? AND itemcode = ?");
            $updateStoreReleased->bind_param("is", $id, $itemcode);
            if (!$updateStoreReleased->execute()) {
                throw new Exception("Failed to update reserved_released in $storeTable: " . $updateStoreReleased->error);
            }
            
            // Step 3: Mark as released in the receiving table
            $updateReceivingReleased = $conn->prepare("UPDATE $receivingTable SET reserved_released = 1 WHERE itemcode = ?");
            $updateReceivingReleased->bind_param("s", $itemcode);
            if (!$updateReceivingReleased->execute()) {
                throw new Exception("Failed to update reserved_released in $receivingTable: " . $updateReceivingReleased->error);
            }

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

            // Step 6: Update reserved table
            $updateReserved = $conn->prepare("UPDATE reserved SET reservedquantity = 0, reserved_released = 1 WHERE itemcode = ?");
            $updateReserved->bind_param("s", $itemcode);
            if (!$updateReserved->execute()) {
                throw new Exception("Failed to update reserved table: " . $updateReserved->error);
            }
            
            // Commit the transaction
            $conn->commit();
            
            echo json_encode([
                'status' => 'success', 
                'message' => "Reserved item '$itemcode' has been successfully activated and released to $department."
            ]);
            
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