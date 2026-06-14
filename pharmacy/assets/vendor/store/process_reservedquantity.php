

<?php



include("include/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemcode'])) {
    $itemcode = $_POST['itemcode'];

    // Function to calculate the total remaining reserved quantity
    function getTotalRemainingReservedQuantity($conn, $itemcode) {
        $query = "
            SELECT 
                COALESCE(SUM(rb.reservedquantity - rb.collectedreserved), 0) +
                COALESCE(SUM(rbn.reservedquantity - rbn.collectedreserved), 0) AS totalRemainingReserved
            FROM reserved r
            LEFT JOIN receivingbay rb ON r.itemcode = rb.itemcode AND r.reservedfordept = rb.reservedfordept
            LEFT JOIN receivingbaynewitems rbn ON r.itemcode = rbn.itemcode AND r.reservedfordept = rbn.reservedfordept
            WHERE r.itemcode = ?";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die(json_encode(['error' => "SQL Error: " . $conn->error]));
        }

        $stmt->bind_param("s", $itemcode);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return max($row['totalRemainingReserved'] ?? 0, 0); // Ensure non-negative value
    }

    // Fetch total remaining reserved quantity
    $totalRemainingReserved = getTotalRemainingReservedQuantity($conn, $itemcode);
    echo json_encode(['success' => true, 'totalRemainingReserved' => $totalRemainingReserved]);
    exit;
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}


?>
