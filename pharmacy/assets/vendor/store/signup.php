<?php
session_start();
include("include/connect.php"); // Ensure this file correctly connects to DB

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize messages
$error_message = '';
$success_message = '';

if (isset($_POST['submit'])) {
    // Sanitize and collect data
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $store_section = trim($_POST['store_section']);
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Validate required fields
    if (empty($fullname) || empty($email) || empty($store_section) || empty($password)) {
        $error_message = "All fields are required";
    } elseif ($password !== $confirmpassword) {
        $error_message = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters";
    } else {
        // Check for duplicate email
        $check = $conn->prepare("SELECT id FROM register WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error_message = "Email already exists";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert into DB
            $insert = $conn->prepare("INSERT INTO register (fullname, email, password, store_section, role, status, is_active) VALUES (?, ?, ?, ?, 'user', 0, 1)");
            $insert->bind_param("ssss", $fullname, $email, $hashedPassword, $store_section);

            if ($insert->execute()) {
                $success_message = "Registration successful! Redirecting...";
                echo "<script>
                    setTimeout(() => window.location.href = 'index.php', 2000);
                </script>";
            } else {
                $error_message = "Database error: " . $insert->error;
            }
            $insert->close();
        }
        $check->close();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Registration</title>
    <!-- Bootstrap 5 CSS -->
      <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css" />
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #f8fafc;
            --accent-color: #10b981;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --error-color: #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .registration-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            position: relative;
        }

        .registration-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), #8b5cf6, #ec4899);
        }

        .form-header {
            text-align: center;
            padding: 40px 40px 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .form-header .icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .form-header h1 {
            color: var(--text-primary);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-header p {
            color: var(--text-secondary);
            font-size: 16px;
        }

        .form-body {
            padding: 30px 40px 40px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 14px;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 16px;
            z-index: 2;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 16px 20px 16px 48px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 4px;
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .btn-primary {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 24px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .signin-link {
            text-align: center;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .signin-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .registration-container {
                margin: 10px;
                border-radius: 16px;
            }
            
            .form-header,
            .form-body {
                padding: 30px 25px;
            }
            
            .form-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="form-header">
            <div class="">
            <img src="image/logo.png" alt="OAuthC Logo" style="height: 90px;">
        </div>

            <h1>Create Account</h1>
            <p>Join us and get started</p>
        </div>

        <div class="form-body">
            <!-- Alert Messages -->
            <?php if(!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($success_message)): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

             <form action="signup.php" method="post">
                    <!-- Full Name Field -->
                <div class="form-group">
                    <label for="fullname" class="form-label">Full Name</label>
                    <div class="input-group">
                        <input type="text" name="fullname" class="form-control" id="fullname" 
                               placeholder="Enter your full name" 
                               value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>" 
                               required>
                        <i class="bi bi-person input-icon"></i>
                    </div>
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control" id="email" 
                               placeholder="Enter your email address" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               required>
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="password" 
                               placeholder="Create a password" required>
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="confirmpassword" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" name="confirmpassword" class="form-control" id="confirmpassword" 
                               placeholder="Confirm your password" required>
                        <i class="bi bi-shield-check input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('confirmpassword')">
                            <i class="bi bi-eye" id="confirmpasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Store Section Field -->
                <div class="form-group">
                    <label for="store_section" class="form-label">Store Section</label>
                    <div class="input-group">
                        <select name="store_section" class="form-select" id="store_section" required>
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
                        <i class="bi bi-shop input-icon"></i>
                    </div>
                </div>

                <!-- Submit Button -->
                
                <button type="submit" name="submit" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>
                    Create Account
                </button>
            </form>

            <!-- Sign In Link -->
            <div class="signin-link">
                Already have an account? <a href="login.php">Sign In</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password visibility toggle
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        // Form validation
        // document.querySelector('form').addEventListener('submit', function(e) {
        //     const password = document.getElementById('password').value;
        //     const confirmPassword = document.getElementById('confirmpassword').value;
            
        //     if (password !== confirmPassword) {
        //         e.preventDefault();
        //         alert('Passwords do not match!');
        //         return false;
        //     }
            
        //     if (password.length < 4) {
        //         e.preventDefault();
        //         alert('Password must be at least 4 characters long!');
        //         return false;
        //     }
        // });

        // // Auto-hide success message and redirect
        // < ?php if(!empty($success_message)): ?>
        //     setTimeout(function() {
        //         window.location.href = 'index.php';
        //     }, 2000);
        // < ?php endif; ?>
    </script>
</body>
</html>
