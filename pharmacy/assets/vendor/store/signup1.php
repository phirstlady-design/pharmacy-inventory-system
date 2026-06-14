<?php
include("include/connect.php");
if(isset($_POST['submit'])){
  $fullname =$_POST['fullname']; 
  $email =$_POST['email']; 
  $password =$_POST['password']; 
  $confirmpassword = $_POST['confirmpassword'];
  $store_section = $_POST['store_section'];  // Capture the store section


  if($password != $confirmpassword){
      echo "Password does not match";
  }
  // $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
else{
  $password = password_hash($password, PASSWORD_DEFAULT);
  // Prepare and bind SQL statement
  $stmt = $conn->prepare("INSERT INTO register (fullname, email, password,store_section ) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $fullname, $email, $password, $store_section);

  // Execute the query
  if ($stmt->execute()) {
      echo "Sign up successful!";

       // Redirect to index.html
    header("Location: index.php");
    exit(); // Always call exit() after header redirection
    
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
    <a class="navbar-brand fw-bold px-3 text-white" href="#">OAUTHC STORE DEPARTMENT </a>
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

<!-- Main Section -->
<!-- <div class="container ">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <
            <div class="hero-section text-center">
                <img src="./image/logo.png" class="w-50" alt="logo">
                <div class="overlay">
                <div class="text-center w-100">
                    <h1 class="text-dark display-6 fw-bold">Welcome!</h1>
                    <p class="text-dark display-6 fw-bold"> Login or Create a new account for free.</p>
                </div>
                </div>
            </div>
        </div> -->
    

    <!-- Register Form Section -->
    <div class="row justify-content-center my-3">
        <div class="col-md-4 ">
            <div class="register-form bg-white p-4 rounded shadow my-5 ">
                <h5 class="text-center">Register with</h5>
                <div class="social-login d-flex justify-content-center my-3">
                    <button class="btn btn-outline-primary mx-1"><i class="fab fa-facebook"></i> Facebook</button>
                    <button class="btn btn-outline-dark mx-1"><i class="fab fa-apple"></i> Apple</button>
                    <button class="btn btn-outline-danger mx-1"><i class="fab fa-google"></i> Google</button>
                </div>
    
                <hr class="my-4">
                <form  method="post"> 
                    <div class="form-group mb-3">
                        <input type="text" name="fullname" class="form-control" id="name" placeholder="Name">
                    </div>
                    <div class="form-group mb-3">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="confirmpassword" class="form-control" id="confirmpassword" placeholder="Confirm password">
                    </div>

<!-- New Store Section Dropdown -->
                    <div class="form-group mb-3">
                            <label>Store Section</label>
                            <select name="store_section" class="form-control" id="store_section" required>
                            <option value="">Select store ..</option>
                                <option value="medicalStore">Medical Store</option>
                                <option value="hardwareStore">Hardware Bedding and Furniture Store</option>
                                <option value="labStore">Laboratory Store</option>
                                <!-- <option value="mechanical">Mechanical Store</option> -->
                                <option value="electricalStore">Electrical Store</option>
                                <option value="civilStore">Civil & Maintenance Store</option>
                                <option value="generalStationeryStore">General Stationery Store</option>
                                <option value="controlunit">Store Control Section</option>
                                <option value="receivingBay">Receiving Bay</option>
                                <option value="hod">Hod</option>
                            </select>
                        </div>


                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="termsCheck">
                        <label class="form-check-label" for="termsCheck">I agree to the <a href="#">Terms and Conditions</a></label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" name="submit">Sign Up</button>
                </form>
                <p class="mt-3 text-center">Already have an account? <a href="login.php">Sign In</a></p>
               
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