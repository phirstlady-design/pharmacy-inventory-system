<?php
include("include/connect.php");

    if (isset($_GET['id']) && isset($_GET['code'])) {
        $id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
        $itemcode = isset($_GET['code']) ? mysqli_real_escape_string($conn, $_GET['code']) : '';


    $query = mysqli_query($conn, "SELECT  itemname, itemcode, category, storesection, supplier, 
    quantity_supplied, unitofmeasurement, deliverydate, manufacturedate, expirydate, 
    reservedquantity, reservedfordept FROM receivingbay
    WHERE id = '$id' AND itemcode = '$itemcode'
        UNION
        SELECT itemname, itemcode, category, storesection, supplier, 
    quantity_supplied, unitofmeasurement, deliverydate, manufacturedate, expirydate, 
    reservedquantity, reservedfordept FROM receivingbaynewitems 
    WHERE id = '$id' AND itemcode = '$itemcode'");
        // WHERE 
        // itemcode = '$itemCode'");

    if ($row = mysqli_fetch_assoc($query)) {
        echo "<p><strong>Item Name:</strong> " . $row['itemname'] . "</p>";
        echo "<p><strong>Item Code:</strong> " . $row['itemcode'] . "</p>";
        echo "<p><strong>Category:</strong> " . $row['category'] . "</p>";
        echo "<p><strong>Quantity Supplied:</strong> " . $row['quantity_supplied'] . "</p>";
        echo "<p><strong>Supplier:</strong> " . $row['supplier'] . "</p>";
        echo "<p><strong>Delivery Date:</strong> " . $row['deliverydate'] . "</p>";
        echo "<p><strong>Manufacture Date:</strong> " . $row['manufacturedate'] . "</p>";
        echo "<p><strong>Expiry Date:</strong> " . $row['expirydate'] . "</p>";
        echo "<p><strong>Reserved Quantity:</strong> " . $row['reservedquantity'] . "</p>";
        echo "<p><strong>Reserved For Department:</strong> " . $row['reservedfordept'] . "</p>";
    } else {
        echo "<p class='text-danger'>Item details not found.</p>";
    }
} else {
    echo "<p class='text-danger'>Invalid request.</p>";
}
?>
