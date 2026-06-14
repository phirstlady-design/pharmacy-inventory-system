<?php
include("include/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    // Fetch the item from the audit table
    $query = "SELECT * FROM audit WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();

        // Extract item details
        $itemname = $item['itemname'];
        $itemcode = $item['itemcode'];
        $category = $item['category'];
        $storesection = $item['storesection'];
        $initialprice = $item['initialprice'];
        $supplier = $item['supplier'];
        $quantity_supplied = $item['quantity_supplied'];
        $unitofmeasurement = $item['unitofmeasurement'];
        $deliverydate = $item['deliverydate'];
        $manufacturedate = $item['manufacturedate'];
        $expirydate = $item['expirydate'];
        $reservedquantity = $item['reservedquantity'];
        $reservedfordept = $item['reservedfordept'];
        $remainingquantity = $item['remainingquantity'];
        $totalremainingquantity = $item['totalremainingquantity'];
        $source = $item['source']; // Get the source

        // Determine the target table
        $targetTable = ($source === 'receivingbaynewitems') ? 'receivingbaynewitems' : 'receivingbay';

        // Insert into the respective table
        $insertQuery = "INSERT INTO $targetTable 
            (itemname, itemcode, category, storesection, initialprice, supplier, quantity_supplied, unitofmeasurement, deliverydate, manufacturedate, expirydate, reservedquantity, reservedfordept, remainingquantity, totalremainingquantity) 
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param(
            "ssssdsissssisii",
            $itemname,
            $itemcode,
            $category,
            $storesection,
            $initialprice,
            $supplier,
            $quantity_supplied,
            $unitofmeasurement,
            $deliverydate,
            $manufacturedate,
            $expirydate,
            $reservedquantity,
            $reservedfordept,
            $remainingquantity,
            $totalremainingquantity
        );

        if ($stmt->execute()) {
            // Update the audit table to set request_status to 'audited'
            $updateAudit = "UPDATE audit SET request_status = 'audited' WHERE id = ?";
            $stmt = $conn->prepare($updateAudit);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Item successfully audited and saved to " . $targetTable . "."]);
            } 
            else {
                echo json_encode(["success" => false, "message" => "Failed to update audit table."]);
            }
        } 
        else {
            echo json_encode(["success" => false, "message" => "Failed to insert into " . $targetTable . ": " . $conn->error]);
        }
    } 
    else {
        echo json_encode(["success" => false, "message" => "Item not found in the audit table."]);
    }

    $conn->close();
} 
else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
