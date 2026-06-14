<?php
include("include/connect.php");

$message = '';
$message_type = '';

if(isset($_POST['submit'])){
    $fullname = trim($_POST['fullname']); 
    $email = trim($_POST['email']); 
    $password = $_POST['password']; 
    $confirmpassword = $_POST['confirmpassword'];
    $store_section = $_POST['store_section'];

    // Validate inputs
    if(empty($fullname) || empty($email) || empty($password) || empty($store_section)){
        $message = "All fields are required";
        $message_type = "danger";
    }
    elseif($password != $confirmpassword){
        $message = "Passwords do not match";
        $message_type = "danger";
    }
    elseif(strlen($password) < 6){
        $message = "Password must be at least 6 characters";
        $message_type = "danger";
    }
    else {
        // Check if email already exists
        $check_email = $conn->prepare("SELECT email FROM register WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result = $check_email->get_result();
        
        if($result->num_rows > 0){
            $message = "Email already exists";
            $message_type = "danger";
        }
        else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert with status = 0 (needs password change on first login)
            $stmt = $conn->prepare("INSERT INTO register (fullname, email, password, store_section, role, status, is_active) VALUES (?, ?, ?, ?, 'user', 0, 1)");
            $stmt->bind_param("ssss", $fullname, $email, $password_hashed, $store_section);

            if ($stmt->execute()) {
                $message = "Sign up successful! Redirecting to login page...";
                $message_type = "success";
                
                // Auto-redirect to login page after 2 seconds
                echo "<script>
                    setTimeout(function(){
                        window.location.href = 'index.php';
                    }, 2000);
                </script>";
            } else {
                $message = "Error creating account. Please try again.";
                $message_type = "danger";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css">
</head>
<body>
<div class="container-fluid">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-success p-3">
        <a class="navbar-brand fw-bold px-3 text-white" href="#">OAUTHC STORE DEPARTMENT</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white fs-5" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fs-5" href="index.php">Sign In</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Register Form Section -->
    <div class="row justify-content-center my-3">
        <div class="col-md-4">
            <div class="register-form bg-white p-4 rounded shadow my-5">
                
                <!-- Show success/error messages -->
                <?php if(!empty($message)): ?>
                    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <h5 class="text-center">Register with</h5>
                <div class="social-login d-flex justify-content-center my-3">
                    <button class="btn btn-outline-primary mx-1"><i class="fab fa-facebook"></i> Facebook</button>
                    <button class="btn btn-outline-dark mx-1"><i class="fab fa-apple"></i> Apple</button>
                    <button class="btn btn-outline-danger mx-1"><i class="fab fa-google"></i> Google</button>
                </div>

                <hr class="my-4">
                <form method="post"> 
                    <div class="form-group mb-3">
                        <input type="text" name="fullname" class="form-control" placeholder="Full Name" required 
                               value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password (min 6 characters)" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password" required>
                    </div>

                    <!-- Store Section Dropdown -->
                    <div class="form-group mb-3">
                        <label>Store Section</label>
                        <select name="store_section" class="form-control" required>
                            <option value="">Select store section...</option>
                            <option value="medicalStore" <?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'medicalStore') ? 'selected' : ''; ?>>Medical Store</option>
                            <option value="hardwareStore" <?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'hardwareStore') ? 'selected' : ''; ?>>Hardware Bedding and Furniture Store</option>
                            <option value="labStore" <?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'labStore') ? 'selected' : ''; ?>>Laboratory Store</option>
                            <option value="electricalStore" <?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'electricalStore') ? 'selected' : ''; ?>>Electrical Store</option>
                            <option value="civilStore" <?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'civilStore') ? 'selected' : ''; ?>>Civil & Maintenance Store</option>
                            <option value="generalStationeryStore" <?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'generalStationeryStore') ? 'selected' : ''; ?>>General Stationery Store</option>
                            <option value="controlunit" <?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'controlunit') ? 'selected' : ''; ?>>Store Control Section</option>
                            <option value="receivingBay" <?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'receivingBay') ? 'selected' : ''; ?>>Receiving Bay</option>
                            <option value="hod" <?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'hod') ? 'selected' : ''; ?>>HOD</option>
                        </select>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="termsCheck" required>
                        <label class="form-check-label" for="termsCheck">I agree to the <a href="#">Terms and Conditions</a></label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" name="submit">Sign Up</button>
                </form>
                <p class="mt-3 text-center">Already have an account? <a href="index.php">Sign In</a></p>
            </div>
        </div>
    </div>
</div>

<script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
