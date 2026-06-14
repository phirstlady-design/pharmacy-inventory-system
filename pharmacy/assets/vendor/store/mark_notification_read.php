<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// include("include/connect.php");
// session_start();

// if (!isset($_SESSION['storesection'])) {
//     echo json_encode(["success" => false, "error" => "Session storesection not set"]);
//     exit;
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['notification_id'])) {
//     $notification_id = intval($_POST['notification_id']);

//     if (!isset($_SESSION['storesection'])) {
//         echo json_encode(["success" => false, "error" => "Session storesection not set"]);
//         exit;
//     }

//     $store_section = $_POST['store_section'];

//     $sql = "UPDATE notifications SET status = 'read' WHERE id = ? AND storesection = ?";
//     $stmt = $conn->prepare($sql);

//     if (!$stmt) {
//         echo json_encode(["success" => false, "error" => $conn->error]);
//         exit;
//     }

//     $stmt->bind_param("is", $notification_id, $storesection);
    
//     if ($stmt->execute()) {
//         echo json_encode(["success" => true]);
//     } else {
//         echo json_encode(["success" => false, "error" => $conn->error]);
//     }

//     $stmt->close();
// } else {
//     echo json_encode(["success" => false, "error" => "Invalid request"]);
// }
?>
<?php
include("include/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["notification_id"])) {
    $notification_id = $_POST["notification_id"];

    $sql = "UPDATE notifications SET status = 'read' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notification_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>

