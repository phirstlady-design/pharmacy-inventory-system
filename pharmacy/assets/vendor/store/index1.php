<?php
session_start();
include("include/connect.php");
include("include/validate.php");

if(isset($_POST['submit'])){
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
    
    $query = "SELECT * FROM register WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->num_rows;
    
    if ($rows == 1) {
        $loggedinuser = $result->fetch_assoc();
            // Check if the user is deactivated
    if ($loggedinuser['is_active'] == 0) {
        echo "<script>alert('Your account has been deactivated. Please contact admin.'); window.location.href='index.php';</script>";
        exit;
    }
        if (password_verify($password, $loggedinuser['password'])) {
            if ($loggedinuser['role'] == 'user') {
                $_SESSION['userid'] = $loggedinuser['id'];
                if ($loggedinuser['status'] == 0) {
                    $_SESSION['email'] = $loggedinuser['email'];
                    header("Location: changepassword.php");
                }
                    else{
                        $_SESSION['fullname'] = $loggedinuser['fullname'];
                        $_SESSION['email'] = $loggedinuser['email'];
                        $_SESSION['store_section'] = $loggedinuser['store_section']; 
                        header("Location: dashboard.php");
                        exit();
                    }   
            } 
            elseif ($loggedinuser['role'] == 'admin') {
                $_SESSION['adminid'] = $loggedinuser['id'];
                if ($loggedinuser['status'] == 0) {
                    header("Location: changepassword.php");
                }
                    else{
                        $_SESSION['fullname'] = $loggedinuser['fullname'];
                        $_SESSION['email'] = $loggedinuser['email'];
                        header("Location: admindashboard.php");
                        exit();
                    }
                
            }
        } else {
            echo "Wrong Password";
            header("Location:index.php");
            exit();
        }
    } else {
        echo "Wrong Username or Password";
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css">
    <!-- Custom CSS -->
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>
<body>
<div class="container-fluid">
<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light  bg-success p-3">
    <a class="navbar-brand fw-bold px-3 text-white" href="#">OAUTHC STORES AND SUPPLIES DEPARTMENT </a>
    <div class="collapse navbar-collapse justify-content-end">
        <!-- <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white fs-5" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white fs-5" href="login.php">Sign In</a>
            </li>
        </ul> -->
    </div>
</nav>

<!-- Main Section -->
<div class="container ">
    <div class="row d-flex justify-content-center my-5">
        <div class="col-md-7">
            <!-- Background image with welcome message -->
            <div class="hero-section text-center">
                <img src="./image/logo.png" class="w-50" alt="logo">
                <div class="overlay">
                <div class="text-center w-100">
                    <h1 class="text-dark display-6 fw-bold">Welcome!</h1>
                    <p class="text-dark display-6 fw-bold"> Login and Change your Password</p>
                </div>
                </div>
            </div>
        </div>
    

    <!-- Register Form Section -->
    <!-- <div class="row justify-content-center my-5"> -->
    <div class="col-md-5 d-flex justify-content-center align-items-center bg-light">
            <div class="login-form w-75 m-5">
                <h2 class="text-primary mb-4">WELCOME TO OAUTHC STORES AND SUPPLIES DEPARTMENT</h2>
                <p> Sign in with these credentials:<br>
                   <strong></strong><br>
                   <strong></strong>
                </p>
                <form method="POST">
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password"name="password" class="form-control" id="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100">Sign In</button>
                </form>
                <p class="mt-3">
                    <!-- <a href="#">Forgot your password?</a> | -->
                    
                    <!-- <a href="signup.php">Sign Up</a> -->
                </p>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Bootstrap JS -->
<script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="/assets/js/script.js"></script> -->
     <!-- Bootstrap Icons (for social media icons) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.js"></script>
<!-- Custom JS -->
<script src="script.js"></script>

</body>
</html>