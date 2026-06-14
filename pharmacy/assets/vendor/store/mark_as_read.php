<?php
include("include/connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE notifications SET status = 'read' WHERE id = '$id'";
    $conn->query($query);
}

header("Location: dashboard.php"); // Redirect back to dashboard
exit();
?>
