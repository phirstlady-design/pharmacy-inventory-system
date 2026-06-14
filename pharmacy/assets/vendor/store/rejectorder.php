<?php
session_start();
include("include/connect.php");

header("Content-Type: application/json"); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'reject') {
    $itemcode = $_POST['itemcode'];
    $id = $_POST['id']; // Correct variable for order ID

    // Fetch `quantityreleased` from `labconfirm` and `remainingquantity` from `labstore`
    $sql = "SELECT quantityreleased FROM labconfirm WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $confirmData = $result->fetch_assoc();

    if ($confirmData) {
        $quantityReleased = $confirmData['quantityreleased'];

        // Fetch the current remaining quantity in `labstore`
        $storeSql = "SELECT remainingquantity FROM labstore WHERE itemcode = ?";
        $storeStmt = $conn->prepare($storeSql);
        $storeStmt->bind_param("s", $itemcode);
        $storeStmt->execute();
        $storeResult = $storeStmt->get_result();
        $storeData = $storeResult->fetch_assoc();

        if ($storeData) {
            $remainingQuantity = $storeData['remainingquantity'];
            $newRemainingQuantity = $remainingQuantity + $quantityReleased;

            // Update `labstore` remaining quantity
            $updateSql = "UPDATE labstore SET remainingquantity = ? WHERE itemcode = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("is", $newRemainingQuantity, $itemcode);
            $updateStmt->execute();
        }

        // Update request_status to 'rejected' in `labconfirm`
        $updateRequestSql = "UPDATE labconfirm SET request_status = 'rejected' WHERE id = ?";
        $updateRequestStmt = $conn->prepare($updateRequestSql);
        $updateRequestStmt->bind_param("i", $id);
        $updateRequestStmt->execute();

        echo json_encode(['success' => true]); // Send success response
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not found in labconfirm']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
