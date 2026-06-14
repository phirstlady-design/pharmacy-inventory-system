<?php
session_start();

// Check if user is logged in (either userid or email should be in session)
if (!isset($_SESSION['userid']) && !isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

include("include/connect.php");

$user_email = $_SESSION['email'] ?? '';


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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - OAUTHC</title>
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <script src="assets/js/jquery-3.6.0.min.js"></script>
  <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #f8fafc;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --error-color: #ef4444;
            --warning-color: #f59e0b;
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

        .password-change-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 480px;
            width: 100%;
            position: relative;
        }

        .password-change-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
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
            font-weight: 400;
        }

        .form-body {
            padding: 30px 40px 40px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .password-input-group {
            position: relative;
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
            border-radius: 4px;
            transition: color 0.3s ease;
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
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
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

        @media (max-width: 576px) {
            .password-change-container {
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
    <div class="password-change-container">
        <div class="form-header">
            <div class="">
                <img src="image/logo.png" alt="OAuthC Logo" style="height: 90px;">
            </div>
            <h1>Set Your Password</h1>
            <p>Create a secure password for your account</p>
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

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <!-- Email Field (readonly) -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope me-2"></i>Email Address
                    </label>
                    <input type="email" class="form-control" name="email" id="email" 
                           value="<?php echo htmlspecialchars($user_email); ?>" readonly>
                </div>

                <!-- New Password Field -->
                <div class="form-group">
                    <label for="newPassword" class="form-label">
                        <i class="bi bi-key me-2"></i>New Password
                    </label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" name="newpassword" id="newPassword" 
                               placeholder="Enter your new password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
                            <i class="bi bi-eye" id="newPasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="confirmPassword" class="form-label">
                        <i class="bi bi-shield-check me-2"></i>Confirm Password
                    </label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" name="confirmpassword" id="confirmPassword" 
                               placeholder="Confirm your new password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                            <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="changepassword" class="btn btn-primary">
                    <i class="bi bi-shield-lock me-2"></i>
                    Set Password
                </button>

                <!-- Back to Login Link -->
                <div class="back-link">
                    <a href="login.php">
                        <i class="bi bi-arrow-left me-1"></i>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
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

        // Real-time password confirmation checking
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = this.value;
            
            if (confirmPassword !== '' && newPassword !== confirmPassword) {
                this.style.borderColor = '#ef4444';
            } else if (confirmPassword !== '' && newPassword === confirmPassword) {
                this.style.borderColor = '#10b981';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
        });
    </script>
</body>
</html>
