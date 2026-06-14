<?php
session_start();
include("include/connect.php");

header("Content-Type: application/json");

if (
    $_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['action']) && $_POST['action'] == 'reject'
) {
    $itemcode = $_POST['itemcode'];
    $id = $_POST['id'];
    $name = $_SESSION['fullname'];

    // Store and Confirm Table Mapping
    $storeTableMapping = [
        "labStore" => "labstore",
        "electricalStore" => "electricalstore",
        "hardwareStore" => "hardwarestore",
        "healthStationeryStore" => "healthstore",
        "generalStationeryStore" => "stationerystore",
        "medicalStore" => "medstore",
        "civilStore" => "civilstore"
    ];

    $confirmTableMapping = [
        "labStore" => "labconfirm",
        "electricalStore" => "electricalconfirm",
        "hardwareStore" => "hardwareconfirm",
        "medicalStore" => "medconfirm",
        "civilStore" => "civilconfirm",
        "healthStationeryStore" => "healthconfirm",
        "generalStationeryStore" => "stationeryconfirm"
    ];

    // Fetch REQUEST details from `codingunit`
    $sql = "SELECT storesection, itemname, department, quantityreleased,initialprice, currentprice FROM codingunit WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $confirmData = $result->fetch_assoc();

    if ($confirmData) {
        $storeSection = $confirmData['storesection'];
        $quantityReleased = $confirmData['quantityreleased'];
        $itemName = $confirmData['itemname'];
        $department = $confirmData['department'];
        $initialprice = $confirmData['initialprice'];
        $currentprice = $confirmData['currentprice'];

        if (!isset($storeTableMapping[$storeSection]) || !isset($confirmTableMapping[$storeSection])) {
            echo json_encode(['success' => false, 'message' => "Error: Store section '$storeSection' is not recognized."]);
            exit;
        }

        $confirmTable = $confirmTableMapping[$storeSection];
        $storeTable = $storeTableMapping[$storeSection];
        $lastTotalRemainingQuantity = 0;
        $foundReserved = false;
        $reservedquantity = 0;
        $newCollected = 0;
        $newRemaining = 0;

        // Check if department is reserved
        $reservedSql = "SELECT id, reservedquantity, collectedreserved FROM receivingbay WHERE itemcode = ? AND reservedfordept = ?
                        UNION ALL
                        SELECT id, reservedquantity, collectedreserved FROM receivingbaynewitems WHERE itemcode = ? AND reservedfordept = ?";
        $reservedStmt = $conn->prepare($reservedSql);
        $reservedStmt->bind_param("ssss", $itemcode, $department, $itemcode, $department);
        $reservedStmt->execute();
        $reservedResult = $reservedStmt->get_result();

        while ($row = $reservedResult->fetch_assoc()) {
            $foundReserved = true;
            $reservedquantity = $row['reservedquantity'];
            $newCollected = max(0, $row['collectedreserved'] - $quantityReleased);
            $newRemaining = max(0, $reservedquantity - $newCollected);
            $table = is_numeric($row['id']) ? "receivingbay" : "receivingbaynewitems";

            // Update collectedreserved and remainingreserved
            $updateReservedSql = "UPDATE $table SET collectedreserved = ?, remainingreserved = ? WHERE id = ? AND reservedfordept = ?";
            $updateReservedStmt = $conn->prepare($updateReservedSql);
            $updateReservedStmt->bind_param("iiis", $newCollected, $newRemaining, $row['id'], $department);
            $updateReservedStmt->execute();
        }

        // Get last totalremainingquantity from last supply
        $sourceTable = "receivingbay"; // default
        $checkSql = "SELECT totalremainingquantity FROM receivingbay WHERE itemcode = ? ORDER BY id DESC LIMIT 1";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $itemcode);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $row = $checkResult->fetch_assoc();

        if (!$row) {
            $checkSql = "SELECT totalremainingquantity FROM receivingbaynewitems WHERE itemcode = ? ORDER BY id DESC LIMIT 1";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("s", $itemcode);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $row = $checkResult->fetch_assoc();
            $sourceTable = "receivingbaynewitems"; // ✅ switch source
        }

        if ($row) {
            $lastTotalRemainingQuantity = $row['totalremainingquantity'];
        }

        // Update store table
        if ($foundReserved) {
            $updateStoreReservedSql = "UPDATE $storeTable SET collectedreserved = ?, remainingreserved = ? WHERE itemcode = ? AND reservedfordept = ?";
            $updateStoreReservedStmt = $conn->prepare($updateStoreReservedSql);
            $updateStoreReservedStmt->bind_param("iiis", $newCollected, $newRemaining, $itemcode, $department);
            $updateStoreReservedStmt->execute();
        } else {
            // $updateReceivingSql = "UPDATE receivingbay SET totalremainingquantity = totalremainingquantity + ? WHERE itemcode = ? ORDER BY id DESC LIMIT 1";
            // $updateReceivingStmt = $conn->prepare($updateReceivingSql);
            // $updateReceivingStmt->bind_param("is", $quantityReleased, $itemcode);
            // $updateReceivingStmt->execute();

            $updateReceivingSql = "UPDATE $sourceTable SET totalremainingquantity = totalremainingquantity + ? WHERE itemcode = ? ORDER BY id DESC LIMIT 1";
            $updateReceivingStmt = $conn->prepare($updateReceivingSql);
            $updateReceivingStmt->bind_param("is", $quantityReleased, $itemcode);
            $updateReceivingStmt->execute();




            $updateStoreSql = "UPDATE $storeTable SET totalremainingquantity = totalremainingquantity + ? WHERE itemcode = ? ORDER BY id DESC LIMIT 1";
            $updateStoreStmt = $conn->prepare($updateStoreSql);
            $updateStoreStmt->bind_param("is", $quantityReleased, $itemcode);
            $updateStoreStmt->execute();
        }

                // Fetch remaining quantity from the latest request
                $remainingQuery = $conn->prepare("SELECT totalremainingquantity FROM requests WHERE itemcode = ? ORDER BY id DESC LIMIT 1");
                $remainingQuery->bind_param("s", $itemcode);
                $remainingQuery->execute();
                $remainingResult = $remainingQuery->get_result()->fetch_assoc();
                $remainingquantity = $remainingResult ? $remainingResult['totalremainingquantity'] : 0;
        
                // Fetch total reserved quantity across all departments
                $reservedTotalQuery = $conn->prepare("SELECT COALESCE(SUM(reservedquantity), 0) AS total_reserved_quantity FROM reserved WHERE itemcode = ?");
                $reservedTotalQuery->bind_param("s", $itemcode);
                $reservedTotalQuery->execute();
                $reservedTotalResult = $reservedTotalQuery->get_result()->fetch_assoc();
                $total_reserved_quantity = $reservedTotalResult['total_reserved_quantity'];
        
                // Calculate previous balance and current balance
                // $previousBalance = $remainingquantity + $total_reserved_quantity;
                // $currentBalance = $previousBalance + $quantityReleased ;
                if ($foundReserved) {
                    $previousBalance = $remainingquantity + $total_reserved_quantity - $quantityReleased;
                    $currentBalance = $previousBalance + $quantityReleased;
                } else {
                    $previousBalance = $remainingquantity + $total_reserved_quantity;
                    $currentBalance = $previousBalance + $quantityReleased;
                    $lastTotalRemainingQuantity += $quantityReleased;
                }
                
        
                // Ensure current balance is not negative
                if ($currentBalance < 0) {
                    echo json_encode(['success' => false, 'message' => 'Error: Insufficient stock for the transaction.']);
                    exit;
                }
        
                // // Now update request table
                // if (!$foundReserved) {
                //     $lastTotalRemainingQuantity += $quantityReleased;
                // }
        
                $updateRequestSql = "UPDATE requests SET request_status = 'rejected', collectedreserved = ?, remainingreserved = ?, totalremainingquantity = ? WHERE itemcode = ? AND department = ?";
                $updateRequestStmt = $conn->prepare($updateRequestSql);
                $updateRequestStmt->bind_param("iiiss", $newCollected, $newRemaining, $lastTotalRemainingQuantity, $itemcode, $department);
                $updateRequestStmt->execute();
        
                // Update codingunit
                $updateCodingSql = "UPDATE codingunit SET request_status = 'rejected' WHERE id = ?";
                $updateCodingStmt = $conn->prepare($updateCodingSql);
                $updateCodingStmt->bind_param("i", $id);
                $updateCodingStmt->execute();
        
                // Insert into confirm table
                $insertSql = "INSERT INTO $confirmTable (itemcode, itemname, quantityreleased,remainingquantity,initialprice, currentprice, department, officerincharge, request_status, createdon, previous_balance, current_balance )
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'rejected',  NOW(), ?, ? )";
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bind_param("ssiiddssii", $itemcode, $itemName, $quantityReleased, $lastTotalRemainingQuantity, $initialprice, $currentprice,  $department, $name, $previousBalance, $currentBalance );
                $insertStmt->execute();
                echo json_encode(['success' => true, 'message' => 'Item rejection processed successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Item not found in codingunit']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
        }
        ?>
                