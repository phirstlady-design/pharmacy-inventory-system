<?php
require_once 'config/database.php';

$message = '';
$message_type = '';

if(isset($_POST['submit'])){
    $fullname = trim($_POST['fullname']); 
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);  
    $email = trim($_POST['email']); 
    $fileno = trim($_POST['fileno']); 
    $password = $_POST['password']; 
    $confirmpassword = $_POST['confirmpassword'];
    // $store_section = $_POST['store_section'];

    // Validate inputs
    if(empty($fullname) || empty($username) || empty($email) || empty($phone) || empty($fileno) || empty($password)){
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
        $check_email = $pdo->prepare("SELECT email FROM users WHERE email = ?");
        $check_email->execute([$email]);

        if($check_email->rowCount() > 0){
            $message = "Email already exists";
            $message_type = "danger";
        }
        else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert with status = 0 (needs password change on first login)
                $stmt = $pdo->prepare("INSERT INTO users 
                (fullname, username, phone, email, fileno, password, role_id, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $default_role_id = 2; // Pharmacist
                $status = 'active';

                if ($stmt->execute([
                    $fullname,
                    $username,
                    $phone,
                    $email,
                    $fileno,
                    $password_hashed,
                    $default_role_id,
                    $status
                ])) {
                $message = "Sign up successful! Redirecting to login page...";
                $message_type = "success";
                
                // Auto-redirect to login page after 2 seconds
                echo "<script>
                    setTimeout(function(){
                        window.location.href = 'login.php';
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
    <title>Register - Pharmacy Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-bottom: none;
        }
        
        .navbar-brand {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .register-wrapper {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px 12px 0 0;
            padding: 2rem;
            text-align: center;
            color: white;
            border: none;
        }
        
        .card-header h5 {
            color: white;
            margin: 0;
            font-size: 1.5rem;
        }
        
        .social-login {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin: 1.5rem 0;
        }
        
        .social-login .btn {
            flex: 1;
            min-width: 120px;
            font-size: 0.85rem;
            padding: 0.6rem 0.75rem;
            border-radius: 8px;
        }
        
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            color: var(--neutral-400);
        }
        
        .divider::before {
            content: '';
            display: block;
            height: 1px;
            background: var(--neutral-200);
            margin-bottom: 1rem;
        }
        
        .divider::after {
            content: '';
            display: block;
            height: 1px;
            background: var(--neutral-200);
            margin-top: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.6rem;
            display: block;
        }
        
        .form-control,
        .form-select {
            border: 2px solid var(--neutral-200);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background-color: white;
        }
        
        .form-check {
            margin-bottom: 1.25rem;
        }
        
        .form-check-label {
            color: var(--neutral-700);
            margin-left: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-check-label a {
            color: #667eea;
            font-weight: 600;
        }
        
        .btn-signup {
            padding: 0.85rem;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            color: white;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--neutral-700);
        }
        
        .login-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PHARMACY</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="login.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="login.php">Sign In</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Register Form -->
    <div class="register-wrapper">
        <div class="register-card">
            <div class="card-header">
                <h5>Create Your Account</h5>
            </div>
            <div class="card-body p-4">
                
                <!-- Show success/error messages -->
                <?php if(!empty($message)): ?>
                    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="form-group">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Enter your full name" required 
                               value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter your phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="fileno" class="form-label">File Number</label>
                        <input type="text" id="fileno" name="fileno" class="form-control" placeholder="At least 6 characters" required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="At least 6 characters" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmpassword" class="form-label">Confirm Password</label>
                        <input type="password" id="confirmpassword" name="confirmpassword" class="form-control" placeholder="Confirm your password" required>
                    </div>

                    <!-- Store Section Dropdown 
                    <div class="form-group">
                        <label for="store_section" class="form-label">Store Section</label>
                        <select id="store_section" name="store_section" class="form-select" required>
                            <option value="">Select store section...</option>
                            <option value="medicalStore" < ?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'medicalStore') ? 'selected' : ''; ?>>Medical Store</option>
                            <option value="hardwareStore" < ?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'hardwareStore') ? 'selected' : ''; ?>>Hardware Bedding and Furniture Store</option>
                            <option value="labStore" < ?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'labStore') ? 'selected' : ''; ?>>Laboratory Store</option>
                            <option value="electricalStore" < ?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'electricalStore') ? 'selected' : ''; ?>>Electrical Store</option>
                            <option value="civilStore" < ?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'civilStore') ? 'selected' : ''; ?>>Civil & Maintenance Store</option>
                            <option value="generalStationeryStore" < ?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'generalStationeryStore') ? 'selected' : ''; ?>>General Stationery Store</option>
                            <option value="controlunit" < ?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'controlunit') ? 'selected' : ''; ?>>Store Control Section</option>
                            <option value="receivingBay" < ?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'receivingBay') ? 'selected' : ''; ?>>Receiving Bay</option>
                            <option value="hod" < ?php echo (isset($_POST['store_section']) && $_POST['store_section'] == 'hod') ? 'selected' : ''; ?>>HOD</option>
                        </select>
                    </div>-->

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="termsCheck" required>
                        <label class="form-check-label" for="termsCheck">I agree to the <a href="#">Terms and Conditions</a></label>
                    </div>
                    
                    <button type="submit" class="btn btn-signup w-100" name="submit">Create Account</button>
                </form>
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Sign In</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>