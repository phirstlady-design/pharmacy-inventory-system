<?php
include("include/connect.php");

// Get search query
$query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';

// Array of store sections
$storeSections = ['receivingbaynewitems', 'receivingbay'];

//$storeSections = ['products', 'receivingbay', 'itemdetails', 'labstore', 'hardwarestore', 'medstore', 'electricalstore', 'mechstore', 'civilstore'];

// Build dynamic search queries
$searchQueries = [];
foreach ($storeSections as $table) {
    $searchQueries[] = "
        SELECT 
            itemcode, itemname, category, supplier, quantity_supplied, initialprice, expirydate, 
            manufacturedate, deliverydate, reservedquantity, reservedfordept 
        FROM 
            $table
        WHERE 
            itemname LIKE '%$query%' OR 
            itemcode LIKE '%$query%' OR 
            category LIKE '%$query%'
    ";
}

// Execute combined query
$sql = implode(" UNION ", $searchQueries);
$result = $conn->query($sql);

// Output results
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['itemcode']}</td>
            <td>{$row['itemname']}</td>
            <td>{$row['category']}</td>
            <td>{$row['supplier']}</td>
            <td>{$row['quantity_supplied']}</td>
            <td>{$row['initialprice']}</td>
            <td>{$row['expirydate']}</td>
            <td>{$row['manufacturedate']}</td>
            <td>{$row['deliverydate']}</td>
            <td>{$row['reservedquantity']}</td>
            <td>{$row['reservedfordept']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='10'>No items found.</td></tr>";
}
?>
