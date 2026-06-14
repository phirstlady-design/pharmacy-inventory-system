<?php
// include("include/connect.php");
// session_start();

// if (!isset($_SESSION['store_section'])) {
//     echo json_encode(["notification_count" => 0, "notifications" => []]);
//     exit;
// }

// $store_section = $_SESSION['store_section'];

// // Debugging session value
// echo "Store Section: " . $store_section; 
// exit;

// // // Query to fetch notifications
// // $sql = "SELECT id, message, created_at FROM notifications WHERE status = 'unread'";
// // $stmt = $conn->prepare($sql);
// // $stmt->bind_param("s", $store_section);
// // $stmt->execute();
// // $result = $stmt->get_result();
// // Query to fetch notifications
// $sql = "SELECT id, message, created_at FROM notifications WHERE status = 'unread'";
// $result = $conn->query($sql);
// $notifications = [];
// while ($row = $result->fetch_assoc()) {
//     $notifications[] = $row;
// }
// if (!$result) {
//     die("Query failed: " . $conn->error); // Debugging line
// }
// // Send JSON response
// echo json_encode([
//     "notification_count" => count($notifications),
//     "notifications" => $notifications
// ]);


include("include/connect.php");
session_start();

if (!isset($_SESSION['store_section'])) {
    echo json_encode(["notification_count" => 0, "notifications" => []]);
    exit;
}

$store_section = $_SESSION['store_section'];

// If the logged-in store section is "controlunit", fetch all notifications
if ($store_section === "controlunit") {
    $sql = "SELECT id, message, created_at FROM notifications WHERE status = 'unread'";
} else {
    $sql = "SELECT id, message, created_at FROM notifications WHERE status = 'unread' AND storesection = ?";
}

$stmt = $conn->prepare($sql);

// Bind parameter only if it's not the control unit
if ($store_section !== "controlunit") {
    $stmt->bind_param("s", $store_section);
}

$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

// Send JSON response
echo json_encode([
    "notification_count" => count($notifications),
    "notifications" => $notifications
]);
?>




