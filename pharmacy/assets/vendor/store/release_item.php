<?php
include("include/connect.php"); 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $quantityreleased = intval($_POST['quantityreleased']);

    // Fetch all supplies for the item
    $sql_fetch = "
        SELECT r.*, 
               rb.id AS rb_id, rbn.id AS rbn_id,
               rb.totalremainingquantity AS rb_totalremainingquantity, 
               rbn.totalremainingquantity AS rbn_totalremainingquantity,
               rb.reservedquantity AS rb_reservedquantity, 
               rbn.reservedquantity AS rbn_reservedquantity, 
               rb.collectedreserved AS rb_collectedreserved, 
               rbn.collectedreserved AS rbn_collectedreserved, 
               rb.remainingreserved AS rb_remainingreserved, 
               rbn.remainingreserved AS rbn_remainingreserved, 
               rb.reservedfordept AS rb_reservedfordept, 
               rbn.reservedfordept AS rbn_reservedfordept

                
   
        FROM requests r 
        LEFT JOIN receivingbay rb ON r.itemcode = rb.itemcode
         AND rb.id = (SELECT MAX(id) FROM receivingbay WHERE itemcode = r.itemcode) 
        LEFT JOIN receivingbaynewitems rbn ON r.itemcode = rbn.itemcode 
         AND rbn.id = (SELECT MAX(id) FROM receivingbaynewitems WHERE itemcode = r.itemcode)
        WHERE r.id = ? AND r.request_status = 'pending' 
        ORDER BY COALESCE(rb.id, rbn.id) DESC";

    $stmt = $conn->prepare($sql_fetch);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $found_reserved = false;
        $last_totalremainingquantity = 0;
        $last_supply_id = null;
        $last_supply_table = null;
        
        while ($row = $result->fetch_assoc()) {
            $itemname = $row['itemname'];
            $itemcode = $row['itemcode'];
            $storesection = $row['storesection'];
            $initialprice = $row['initialprice'];
            $requestedquantity = $row['quantityrequested'];
            $currentprice = $row['currentprice'];
           $requestingdept = $row['department'];

            $reservedfordept = $row['rb_reservedfordept'] ?? $row['rbn_reservedfordept'];
            $reservedquantity = $row['rb_reservedquantity'] ?? $row['rbn_reservedquantity'];
            $collectedreserved = $row['rb_collectedreserved'] ?? $row['rbn_collectedreserved'];
            $remainingreserved = $row['rb_remainingreserved'] ?? $row['rbn_remainingreserved'];
            $supply_id = $row['rb_id'] ?? $row['rbn_id'];
            $source_table = $row['rb_id'] ? 'receivingbay' : 'receivingbaynewitems';

            if ($quantityreleased > $requestedquantity) {
                echo json_encode(["success" => false, "message" => "Quantity released cannot exceed the requested quantity."]);
                exit;
            }
            
            if (!$last_totalremainingquantity) {
                $last_totalremainingquantity = $row['rb_totalremainingquantity'] ?? $row['rbn_totalremainingquantity'];
                $last_supply_id = $supply_id;
                $last_supply_table = $source_table;
            }

            if ($quantityreleased > $last_totalremainingquantity) {
                echo json_encode(["success" => false, "message" => "Quantity released cannot exceed the remaining quantity."]);
                exit;
            }

            if ($reservedfordept === $requestingdept) {
                $found_reserved = true;
                $collectedreserved += $quantityreleased;
                $remainingreserved = $reservedquantity - $collectedreserved;

                // Update reserved department row
                $updateSQL = "UPDATE $source_table SET collectedreserved = ?, remainingreserved = ? WHERE id = ?";
                $stmt = $conn->prepare($updateSQL);
                $stmt->bind_param("iii", $collectedreserved, $remainingreserved, $supply_id);
                $stmt->execute();
                break;
            }
        }

        if (!$found_reserved) {
            $last_totalremainingquantity -= $quantityreleased;
            
            // Update only the last supply entry
            if ($last_supply_id && $last_supply_table) {
                $updateSQL = "UPDATE $last_supply_table SET totalremainingquantity = ? WHERE id = ?";
                $stmt = $conn->prepare($updateSQL);
                $stmt->bind_param("ii", $last_totalremainingquantity, $last_supply_id);
                $stmt->execute();
            }
        }

        // Insert into `codingunit`
        $sqlInsert = "INSERT INTO codingunit (itemid, itemname, itemcode, storesection, department, quantityrequested, initialprice, quantityreleased, currentprice, release_date, request_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'released')";
        $stmt = $conn->prepare($sqlInsert);
        $stmt->bind_param("issssidid", $id, $itemname, $itemcode, $storesection, $requestingdept, $requestedquantity, $initialprice, $quantityreleased, $currentprice);
        $stmt->execute();

        $collected_reserved_value = $found_reserved ? $collectedreserved : 0;
        $remaining_reserved_value = $found_reserved ? $remainingreserved : 0;
        
        // Update `requests` table
        $updateRequestSQL = "UPDATE requests SET request_status = 'released', quantityreleased = ?, totalremainingquantity = ?, collectedreserved = ?, remainingreserved = ? WHERE id = ?";
        $stmt = $conn->prepare($updateRequestSQL);
  

$stmt->bind_param("iiiii", 
    $quantityreleased, 
    $last_totalremainingquantity, 
    $collected_reserved_value, 
    $remaining_reserved_value, 
    $id
);

        $stmt->execute();

        // Identify correct store table
        $storeTables = [
            "electricalStore" => "electricalstore",
            "hardwareStore" => "hardwarestore",
            "generalStationeryStore" => "stationerystore",
            "labStore" => "labstore",
            "healthStationeryStore" => "healthstore",
            "medicalStore" => "medstore",
            "civilStore" => "civilstore"
        ];

        if (!isset($storeTables[$storesection])) {
            echo json_encode(["success" => false, "message" => "Error: Unknown store section."]);
            exit;
        }
        $storeTable = $storeTables[$storesection];

        // Update store table
     
        
        if ($found_reserved) {
            $updateStoreSQL = "UPDATE $storeTable SET collectedreserved = ?, remainingreserved = ?, reserved_released = 1  WHERE itemcode = ? AND reservedfordept = ?";
            $stmt = $conn->prepare($updateStoreSQL);
            $stmt->bind_param("iiis", $collectedreserved, $remainingreserved, $itemcode, $requestingdept);
        } else {
            // $updateStoreSQL = "UPDATE $storeTable SET totalremainingquantity = ? WHERE itemcode = ? AND id = ?";
            // $stmt = $conn->prepare($updateStoreSQL);
            // $stmt->bind_param("isi", $last_totalremainingquantity, $itemcode, $last_supply_id);
        
            $updateStoreSQL = "UPDATE $storeTable SET totalremainingquantity = ? WHERE itemcode = ? ORDER BY id DESC LIMIT 1";
            $stmt = $conn->prepare($updateStoreSQL);
            $stmt->bind_param("is", $last_totalremainingquantity, $itemcode);
        }
        $stmt->execute();

        echo json_encode(["success" => true, "message" => "Item released successfully and inventory updated."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: Request not found or already processed."]);
    }
}
?>
