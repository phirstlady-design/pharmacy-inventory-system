<?php
header('Content-Type: application/json');
include("include/connect.php");

$response = [
    'effectiveQty' => 0,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'getEffectiveQty') {
    $itemcode = $_POST['itemcode'] ?? '';
    $requestedQuantity = $_POST['quantityrequested'] ?? 0;

    if (empty($itemcode)) {
        echo json_encode($response);
        exit;
    }

    // Count pending requests
    $stmtCount = $conn->prepare("SELECT COUNT(*) FROM requests WHERE itemcode = ? AND request_status = 'pending'");
    $stmtCount->bind_param("s", $itemcode);
    $stmtCount->execute();
    $stmtCount->bind_result($pendingCount);
    $stmtCount->fetch();
    $stmtCount->close();

    // Get total remaining quantity
    $stmtRem = $conn->prepare("
        SELECT COALESCE(
            (SELECT totalremainingquantity FROM receivingbay WHERE itemcode = ? ORDER BY id DESC LIMIT 1),
            (SELECT totalremainingquantity FROM receivingbaynewitems WHERE itemcode = ? ORDER BY id DESC LIMIT 1),
            0
        ) AS remainingquantity
    ");
    $stmtRem->bind_param("ss", $itemcode, $itemcode);
    $stmtRem->execute();
    $stmtRem->bind_result($totalRemainingQty);
    $stmtRem->fetch();
    $stmtRem->close();

    // Sum of all pending request quantities
    $stmt2 = $conn->prepare("SELECT SUM(quantityrequested) FROM requests WHERE itemcode = ? AND request_status = 'pending'");
    $stmt2->bind_param("s", $itemcode);
    $stmt2->execute();
    $stmt2->bind_result($totalPending);
    $stmt2->fetch();
    $stmt2->close();

    $totalRemainingQty = $totalRemainingQty ?? 0;
    $totalPending = $totalPending ?? 0;

    // Calculate effectiveQty
    $effectiveQty = $totalRemainingQty - $totalPending;
    if ($effectiveQty < 0) $effectiveQty = 0;

    // If no pending requests, recalculate based on released items
    if ($pendingCount == 0) {
        $stmtActualRem = $conn->prepare("
            SELECT COALESCE(
                (SELECT totalremainingquantity FROM receivingbay WHERE itemcode = ? ORDER BY id DESC LIMIT 1),
                (SELECT totalremainingquantity FROM receivingbaynewitems WHERE itemcode = ? ORDER BY id DESC LIMIT 1),
                0
            ) AS totalRem
        ");
        $stmtActualRem->bind_param("ss", $itemcode, $itemcode);
        $stmtActualRem->execute();
        $stmtActualRem->bind_result($totalRem);
        $stmtActualRem->fetch();
        $stmtActualRem->close();

        $stmtReleased = $conn->prepare("SELECT SUM(quantityreleased) FROM release_item WHERE itemcode = ?");
        $stmtReleased->bind_param("s", $itemcode);
        $stmtReleased->execute();
        $stmtReleased->bind_result($releasedQty);
        $stmtReleased->fetch();
        $stmtReleased->close();

        $totalRem = $totalRem ?? 0;
        $releasedQty = $releasedQty ?? 0;

        $effectiveQty = $totalRem - $releasedQty;
        if ($effectiveQty < 0) {
            $effectiveQty = 0;
            $response['message'] = 'Transaction is not possible.';
        }
    }

    $response['effectiveQty'] = $effectiveQty;

    // Evaluate new request
    if ($requestedQuantity > 0) {
        if ($effectiveQty >= $requestedQuantity) {
            $response['message'] = "Request can be fulfilled.";
        } else {
            $response['message'] = "Request cannot be fulfilled. Not enough effective quantity.";
        }
    } else {
        $response['message'] = "Invalid request quantity.";
    }

    echo json_encode($response);
    exit;
}
?>
