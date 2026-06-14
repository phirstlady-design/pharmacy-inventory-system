<?php
session_start();

// Check if user is logged in (either userid or email should be in session)
if (!isset($_SESSION['userid']) && !isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

include("include/connect.php");

$message = '';
$message_type = '';

if(isset($_POST['changepassword'])){
    $email = $_POST['email'];
    $newpassword = $_POST['newpassword'];
    $confirmpassword = $_POST['confirmpassword'];
    
    // Validate inputs
    if(empty($newpassword) || empty($confirmpassword)){
        $message = "All fields are required";
        $message_type = "danger";
    }
    elseif($newpassword != $confirmpassword){
        $message = "Passwords do not match";
        $message_type = "danger";
    }
    elseif(strlen($newpassword) < 6){
        $message = "Password must be at least 6 characters";
        $message_type = "danger";
    }
    else {
        // Hash the new password
        $hash = password_hash($newpassword, PASSWORD_DEFAULT);
        
        // Use prepared statement to prevent SQL injection
        $query = "UPDATE register SET password = ?, status = 1 WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $hash, $email);
        
        if($stmt->execute() && $stmt->affected_rows > 0){
            $message = "Password changed successfully! Please login again with your new password.";
            $message_type = "success";
            
            // Clear all session data
            session_unset();
            session_destroy();
            
            // Redirect to login page after 3 seconds
            echo "<script>
                setTimeout(function(){
                    window.location.href = 'index.php';
                }, 3000);
            </script>";
        } else {
            $message = "Error updating password. Please try again.";
            $message_type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css">

    <style>
        .btn-color{
            background-color: #0e1c36;
            color: #fff;
        }
        
        .cardbody-color{
            background-color: #ebf2fa;
        }
        
        a{
            text-decoration: none;
        }
        
        .container{
            overflow-x: hidden;
            display: block;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center text-dark mt-5">Change Password</h2>
            <p class="text-center text-muted">You must change your password before accessing the system</p>

            <div class="card bg-primary my-5">
                <form method="POST" class="card-body cardbody-color p-lg-5">   
                    
                    <!-- Show success/error messages -->
                    <?php if(!empty($message)): ?>
                        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" 
                               value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="newpassword" class="form-label">New Password:</label>
                        <input type="password" class="form-control" name="newpassword" required>
                        <small class="text-muted">Minimum 6 characters</small>
                    </div>

                    <div class="mb-3">
                        <label for="confirmpassword" class="form-label">Confirm New Password:</label>
                        <input type="password" class="form-control" name="confirmpassword" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-color" name="changepassword">Change Password</button>
                        <a href="index.php" class="text-decoration-none">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
