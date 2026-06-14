<?php

include("include/connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === "getReservedDepartments" && isset($_POST['itemcode'])) {
        $itemcode = $_POST['itemcode'];

        $query = "SELECT DISTINCT reservedfordept FROM reserved WHERE itemcode = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $itemcode);
        $stmt->execute();
        $result = $stmt->get_result();

        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
        echo json_encode($departments);
        exit;
    }

    if ($action === "getReservedQuantity" && isset($_POST['itemcode']) && isset($_POST['reservedfordept'])) {
        $itemcode = $_POST['itemcode'];
        $reservedfordept = $_POST['reservedfordept'];

        $query = "
            SELECT 
                COALESCE(SUM(rb.reservedquantity - rb.collectedreserved), 0) +
                COALESCE(SUM(rbn.reservedquantity - rbn.collectedreserved), 0) AS remaining_reserved_quantity,
                COALESCE(SUM(rb.remainingquantity), 0) + COALESCE(SUM(rbn.remainingquantity), 0) AS remaining_quantity
            FROM reserved r
            LEFT JOIN receivingbay rb ON r.itemcode = rb.itemcode AND r.reservedfordept = rb.reservedfordept
            LEFT JOIN receivingbaynewitems rbn ON r.itemcode = rbn.itemcode AND r.reservedfordept = rbn.reservedfordept
            WHERE r.itemcode = ? AND r.reservedfordept = ?";

        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            echo json_encode(['error' => "SQL Error: " . $conn->error]);
            exit;
        }

        $stmt->bind_param("ss", $itemcode, $reservedfordept);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            echo json_encode([
                'success' => true,
                'reserved_quantity' => max($row['remaining_reserved_quantity'] ?? 0, 0),
                'remaining_quantity' => max($row['remaining_quantity'] ?? 0, 0)
            ]);
        } else {
            echo json_encode(['error' => 'No data found']);
        }
        exit;
    }

    echo json_encode(['error' => 'Invalid request']);
    exit;
}



?>
