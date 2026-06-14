<?php
include("include/connect.php");

function sendNotification($conn, $message, $recipient, $storesection) {
    $sql = "INSERT INTO notifications (message, recipient, storesection, status, created_at) 
            VALUES (?, ?, ?, 'unread', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $message, $recipient, $storesection);
    
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Notification Error: " . $stmt->error); // Log error for debugging
        return false;
    }
}
?>
