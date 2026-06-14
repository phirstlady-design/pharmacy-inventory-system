<?php
session_start();

if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
    header('Location: index.php'); // Change to your actual login page
    exit();
}
include("include/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (!isset($_SESSION['fullname'])) {
        echo "Session data is missing. Please log in again.";
        exit;
    }

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

    // Fetch release record
    $storeCheckQuery = $conn->prepare("SELECT storesection, itemcode, itemname, initialprice, quantityreleased, currentprice FROM codingunit WHERE id = ?");
    $storeCheckQuery->bind_param("i", $id);
    $storeCheckQuery->execute();
    $storeCheckResult = $storeCheckQuery->get_result()->fetch_assoc();

    if (!$storeCheckResult) {
        echo "Error: Release record not found for request ID: $id.";
        exit;
    }

    $actualStoreSection = $storeCheckResult['storesection']; 
    $itemcode = $storeCheckResult['itemcode'];
    $itemname = $storeCheckResult['itemname'];
    $quantityreleased = $storeCheckResult['quantityreleased'];
    $initialprice = $storeCheckResult['initialprice'];
    $currentprice = $storeCheckResult['currentprice'];

    // Validate store mapping
    if (!isset($storeTableMapping[$actualStoreSection]) || !isset($confirmTableMapping[$actualStoreSection])) {
        echo "Error: Store section '$actualStoreSection' is not recognized.";
        exit;
    }

    $storeTable = $storeTableMapping[$actualStoreSection];
    $confirmTable = $confirmTableMapping[$actualStoreSection];

    // Fetch department
    $departmentQuery = $conn->prepare("SELECT department FROM requests WHERE id = ?");
    $departmentQuery->bind_param("i", $id);
    $departmentQuery->execute();
    $DeptResult = $departmentQuery->get_result()->fetch_assoc();

    if (!$DeptResult) {
        echo "Error: Department not found for request ID: $id.";
        exit;
    }

    $department = $DeptResult['department'];

    // Fetch remaining quantity from the latest request
    $remainingQuery = $conn->prepare("
        SELECT remainingquantity
        FROM requests
        WHERE itemcode = ?
        ORDER BY id DESC LIMIT 1
    ");
    $remainingQuery->bind_param("s", $itemcode);
    $remainingQuery->execute();
    $remainingResult = $remainingQuery->get_result()->fetch_assoc();
    $remainingquantity = $remainingResult ? $remainingResult['remainingquantity'] : 0;

    // Fetch total reserved quantity across all departments
    $reservedTotalQuery = $conn->prepare("
        SELECT COALESCE(SUM(reservedquantity), 0) AS total_reserved_quantity
        FROM reserved
        WHERE itemcode = ?
    ");
    $reservedTotalQuery->bind_param("s", $itemcode);
    $reservedTotalQuery->execute();
    $reservedTotalResult = $reservedTotalQuery->get_result()->fetch_assoc();
    $total_reserved_quantity = $reservedTotalResult['total_reserved_quantity'];

    // ✅ Final correct balance calculation (universal)
    $total_stock_in_store = $remainingquantity + $total_reserved_quantity;
    $previous_balance = $total_stock_in_store;
    $current_balance = $previous_balance - $quantityreleased;

    // Ensure current balance is not negative
    if ($current_balance < 0) {
        echo "Error: Insufficient stock for the transaction.";
        exit;
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update total remaining quantity in the store table
        // $updateQuery = $conn->prepare("
        //     UPDATE $storeTable
        //     SET totalremainingquantity = totalremainingquantity - ?
        //     WHERE itemcode = ?
        // ");
        // $updateQuery->bind_param("is", $quantityreleased, $itemcode);
        // $updateQuery->execute();

        // Insert into confirm table
        $confirmQuery = $conn->prepare("
            INSERT INTO $confirmTable 
            (itemname, itemcode, department, officerincharge, initialprice, quantityreleased, currentprice, previous_balance, current_balance, request_status, createdon) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'collected', NOW())
        ");
        $confirmQuery->bind_param(
            "ssssdidii",
            $itemname,
            $itemcode,
            $department,
            $name,
            $initialprice,
            $quantityreleased,
            $currentprice,
            $previous_balance,
            $current_balance
        );

        if (!$confirmQuery->execute()) {
            throw new Exception("Error inserting into $confirmTable: " . $conn->error);
        }

        // Update request status in codingunit and requests table
        $updateCodingUnitQuery = $conn->prepare("UPDATE codingunit SET request_status = 'collected' WHERE id = ?");
        $updateCodingUnitQuery->bind_param("i", $id);
        $updateCodingUnitQuery->execute();

        $updateRequestsSql = $conn->prepare("UPDATE requests SET request_status = 'collected' WHERE id = ?");
        $updateRequestsSql->bind_param("i", $id);
        $updateRequestsSql->execute();

        // Reduce reserved quantity if it's a reserved department
        $updateReservedQuery = $conn->prepare("
            UPDATE reserved
            SET reservedquantity = reservedquantity - ?
            WHERE itemcode = ? AND reservedfordept = ?
        ");
        $updateReservedQuery->bind_param("iss", $quantityreleased, $itemcode, $department);
        $updateReservedQuery->execute();

        // Commit all changes
        $conn->commit();
        echo "Release confirmed successfully for request ID: $id in $storeTable.";

    } catch (Exception $e) {
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }
}
?>
