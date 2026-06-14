<?php
include("include/connect.php");
session_start();

if (isset($_POST['controlunit'])) {
    
    $request_id = $_POST['request_id'];
    $transferred_quantity = $_POST['transferred_quantity'];
    $transferredby = $_POST['transferredby'];

     // Fetch the request details
     $request_query = "SELECT product_id, requested_quantity FROM requests WHERE id = '$request_id' AND request_status = 'Pending'";
     $request_result = $conn->query($request_query);
 
     if ($request_result && $request_result->num_rows > 0) {
         $request = $request_result->fetch_assoc();
         $product_id = $request['product_id'];
         $requested_quantity = $request['requested_quantity'];
 
         // Fetch product details
         $product_query = "SELECT quantity, remainingquantity, totaltransfered FROM product WHERE id = '$product_id'";
         $product_result = $conn->query($product_query);
 
         if ($product_result && $product_result->num_rows > 0) {
             $product = $product_result->fetch_assoc();
 
            // Handle NULL values for remainingquantity and totaltransfered
 $available_quantity = !is_null($product['remainingquantity']) ? $product['remainingquantity'] : $product['quantity'];
 $total_transferred = !is_null($product['totaltransfered']) ? $product['totaltransfered'] : 0;

 // Check if transfer quantity is valid
 if ($transferred_quantity <= $requested_quantity && $transferred_quantity <= $available_quantity) {
    // Perform transfer updates
    $new_total_transferred = $total_transferred + $transferred_quantity;
    $new_remaining_quantity = $available_quantity - $transferred_quantity;

    // Update the database with the new values
    $update_product_query = "UPDATE product SET totaltransfered = '$new_total_transferred', remainingquantity = '$new_remaining_quantity' WHERE id = '$product_id'";
    $update_request_query = "UPDATE requests SET request_status = 'Completed' WHERE id = '$request_id'";
      // Insert into transfers table (optional)
$insert_transfer = "INSERT INTO transfers (request_id, requested_quantity, transferred_quantity, transferredby ) VALUES ('$request_id', '$requested_quantity','$transferred_quantity','$transferredby' )";
$conn->query($insert_transfer);

    if ($conn->query($update_product_query) === TRUE && $conn->query($update_request_query) === TRUE) {
        $message = "Item transferred successfully!";
    } else {
        $message = "Error in updating records: " . $conn->error;
    }
} else {
    $message = "Transfer quantity exceeds available stock or requested amount!";
}
} else {
$message = "Error: Product not found in the database.";
}
} else {
$message = "Error: Request not found or already completed.";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transfer Item</title>
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css">
    <!-- Custom CSS -->
    <!-- <link rel="stylesheet" href="style.css">  -->
    
</head>
<body>
    <div class="container text-center py-2">
    <h1 class="text-center text-success text-white bg-success w-50 mx-auto mt-3 p-2">Requsition Order Process Form</h1>

    <?php if (isset($message)): ?>
            <p class="message text-danger display-6"><?php echo $message; ?></p>
        <?php endif; ?>
    <form action="" method="POST" class="p-2 px-5 card border-5 rounded-border w-50 mx-auto shadow">
    <label for="request_id" class="form-label text-danger fw-bold fs-4">Pending Requests</label>

<table class="table table-responsive table-primary table-striped shadow ">
    <thead class="fw-bold">
        <tr>
            <th>Select</th>
            <th>Item Name</th>
            <th>Requested Quantity</th>
            <th>Department</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch pending requests
        $query = "SELECT r.id, p.itemname, r.requested_quantity, r.department 
                  FROM requests r 
                  JOIN product p ON r.product_id = p.id 
                  WHERE r.request_status = 'Pending'";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><input type='radio' name='request_id' value='{$row['id']}' required></td>";
            echo "<td>{$row['itemname']}</td>";
            echo "<td>{$row['requested_quantity']}</td>";
            echo "<td>{$row['department']}</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<div class="row d-flex justify-content-between align-items-center">
    <div class="col-md-6">
        <label for="transferred_quantity" class="form-label fw-bold fs-5">Quantity to Transfer</label>
        <input type="number" class="form-control" name="transferred_quantity" min="1" required>
        </div>
     <div class="col-md-6">   
        <label for="" class="form-label fw-bold fs-5">Staff On Duty</label>
        <input type="text" class="form-control fw-bold " name="transferredby" value=" <?php  echo $_SESSION['fullname'];?>"  readonly >
        </div> 
    </div>
        <button type="submit" class="btn btn-success text-white fw-bold fs-4 m-4" name="transfer">Transfer Item</button>
       
    </form>
    
        <div class="d-flex justify-content-between align-items-center mb-2">
<a href="dashboard2.php" class="nav-link btn btn-primary p-2 mt-2 text-white fw-bold">Back To Dashboard</a>
<a href="logout.php" class="nav-link btn btn-primary p-2 mt-2 text-white fw-bold"> Log Out  </a>

</div>
    </div>
</body>
</html>
