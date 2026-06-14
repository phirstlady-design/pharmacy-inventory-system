<?php
include("include/connect.php");

if (isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];

    // Fetch the reservation date for the item
    $sql = "SELECT deliverydate, status FROM receivingbay WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        $deliverydate = $item['deliverydate'];
        $status = $item['status'];
        
        // Calculate the difference between the current date and reservation date
        $current_date = new DateTime();
        $deliverydate = new DateTime($deliverydate);
        $interval = $current_date->diff($deliverydate);
        
        // If item is reserved for more than 6 months, mark it as inactive
        if ($interval->d >= 3) {
            $new_status = 'inactive';
        } else {
            $new_status = 'active';
        }

        // Update the item's status
        $update_sql = "UPDATE receivingbay SET status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_status, $item_id);
        $update_stmt->execute();
        
        if ($update_stmt->affected_rows > 0) {
            echo json_encode(['message' => 'Item activated/marked as inactive for collection.']);
        } else {
            echo json_encode(['message' => 'Error activating item.']);
        }
    } else {
        echo json_encode(['message' => 'Item not found.']);
    }

    // Close connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['message' => 'Item ID not provided.']);
}
?>
