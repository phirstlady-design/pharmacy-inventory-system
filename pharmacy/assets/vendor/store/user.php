<?php 
session_start();

if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
    header('Location: index.php'); // Change to your actual login page
    exit();
}
// Include database connection file
include("include/connect.php");

// Fetch users from the database
$users_query = "SELECT * FROM register";
$users_result = $conn->query($users_query);

// Fetch store sections query
$sections_query = "SELECT DISTINCT store_section FROM register";

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id']; // Get the user ID from the form
    if (isset($_POST['activate'])) {
        // Activate user
        $query = "UPDATE register SET is_active = 1 WHERE id = ?";
        // $query = "UPDATE register SET status = 'active' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    } elseif (isset($_POST['deactivate'])) {
        // Deactivate user
        $query = "UPDATE register SET is_active = 0 WHERE id = ?";
        // $query = "UPDATE register SET status = 'inactive' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    } elseif (isset($_POST['reassign'])) {
        // Reassign user to a new store section
        $new_store_section = $_POST['store_section']; // Get the selected store section
        $query = "UPDATE register SET store_section = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_store_section, $user_id);
        if ($stmt->execute()) {
            // Success message for debugging
            echo "<div class='alert alert-success'>Store section updated successfully for User ID: $user_id</div>";
        } else {
            // Error message for debugging
            echo "<div class='alert alert-danger'>Error updating store section: " . $stmt->error . "</div>";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    //    header("Location: " .'index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
</head>
<body>
<div class="container p-5">
    <h1 class="text-center pb-2 text-success text-white bg-success w-50 mx-auto">Registered Staff</h1>
    <div class="w-25 mb-3 mx-auto">
        <a href="dashboard.php" class="nav-link btn btn-primary p-2 mt-2 text-white fw-bold"><i class="fas fa-arrow-left px-2"></i>Back To Dashboard</a>
        <a href="logout.php" class="nav-link btn btn-primary p-2 mt-2 text-white fw-bold">Log Out <i class="fas fa-arrow-right px-2"></i></a>
    </div>
    <div class="card border-5 rounded-border w-75 mx-auto shadow">
        <div class="card-header">
            <h3>Users List</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Staff Name</th>
                        <th>Email</th>
                        <th>Store Section</th>
                        <th>Status</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['fullname']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['store_section']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td><?php echo $row['is_active']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="activate" class="btn btn-success btn-sm">Activate</button>
                                <button type="submit" name="deactivate" class="btn btn-warning btn-sm">Deactivate</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <select name="store_section" class="form-select d-inline w-50">
                                <?php
        // Query to fetch suppliers
        $storesection_query = "SELECT id, storesection FROM store"; 
        $storesection_result = $conn->query($storesection_query); // Execute the query

        // Check if the query executed successfully and there are results
        if ($storesection_result && $storesection_result->num_rows > 0) {
            while ($storesection = $storesection_result->fetch_assoc()) {
                echo "<option value='{$storesection['storesection']}'>{$storesection['storesection']}</option>";
            }
        } else {
            echo "<option value=''>No storesection available</option>"; // Handle no results case
        }
        ?>
                                    <!-- < ?php 
                                    // Refetch store sections for each user
                                    $sections_result = $conn->query($sections_query);
                                    while ($section = $sections_result->fetch_assoc()): ?>
                                        <option value="< ?php echo $section['store_section']; ?>" 
                                            < ?php echo ($row['store_section'] == $section['store_section']) ? 'selected' : ''; ?>>
                                            < ?php echo $section['store_section']; ?>
                                        </option>
                                    < ?php endwhile; ?> -->
                                </select>
                                <button type="submit" name="reassign" class="btn btn-info btn-sm">Reassign</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Bootstrap JS -->
<script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
