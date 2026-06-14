<?php
session_start();
include("include/connect.php");

$message = '';
$message_type = '';

if(isset($_POST['submit'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if(empty($email) || empty($password)){
        $message = "Please enter both email and password";
        $message_type = "danger";
    }
    else {
        $query = "SELECT * FROM register WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Check if account is active
            if (isset($user['is_active']) && $user['is_active'] == 0) {
                $message = "Your account has been deactivated. Please contact admin.";
                $message_type = "danger";
            }
            elseif (password_verify($password, $user['password'])) {
                // Set common session variables
                $_SESSION['userid'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['store_section'] = $user['store_section'];
                
                // Check user role and status
                if (isset($user['role']) && $user['role'] == 'admin') {
                    $_SESSION['adminid'] = $user['id'];
                    // Check if admin needs to change password
                    if (isset($user['status']) && $user['status'] == 0) {
                        header("Location: changepassword.php");
                        exit();
                    } else {
                        header("Location: admindashboard.php");
                        exit();
                    }
                }
                else { 
                    // Regular user login
                    // Check if user needs to change password (status = 0)
                    if (isset($user['status']) && $user['status'] == 0) {
                        header("Location: changepassword.php");
                        exit();
                    } else {
                        // Normal login - go to dashboard
                        header("Location: dashboard.php");
                        exit();
                    }
                }
            } 
            else {
                $message = "Invalid email or password";
                $message_type = "danger";
            }
        } 
        else {
            $message = "Invalid email or password";
            $message_type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OAUTHC Store Department</title>
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css">
</head>
<body>
<div class="container-fluid">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-success p-3">
        <a class="navbar-brand fw-bold px-3 text-white" href="#">OAUTHC STORES AND SUPPLIES DEPARTMENT</a>
    </nav>

    <!-- Main Section -->
    <div class="container">
        <div class="row d-flex justify-content-center my-5">
            <div class="col-md-7">
                <!-- Background image with welcome message -->
                <div class="hero-section text-center">
                    <img src="./image/logo.png" class="w-50" alt="logo">
                    <div class="overlay">
                        <div class="text-center w-100">
                            <h1 class="text-dark display-6 fw-bold">Welcome!</h1>
                            <p class="text-dark display-6 fw-bold">Login to access your dashboard</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login Form Section -->
            <div class="col-md-5 d-flex justify-content-center align-items-center bg-light">
                <div class="login-form w-75 m-5">
                    
                    <!-- Show success/error messages -->
                    <?php if(!empty($message)): ?>
                        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <h2 class="text-primary mb-4">WELCOME TO OAUTHC STORES AND SUPPLIES DEPARTMENT</h2>
                    <p>Sign in with your credentials:</p>
                    
                    <form method="POST">
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100">Sign In</button>
                    </form>
                    <p class="mt-3 text-center">
                        Don't have an account? <a href="signup.php">Sign Up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
