<?php
session_start();
include("include/connect.php"); 

// Initialize login attempts for brute force protection
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
$max_attempts = 5;
$lockout_time = 300; // seconds (5 minutes)

if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = 0;
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if currently locked out
    if ($_SESSION['login_attempts'] >= $max_attempts && time() < $_SESSION['lockout_time']) {
        $remaining = $_SESSION['lockout_time'] - time();
        $message = "Too many failed login attempts. Please try again in $remaining seconds.";
        $message_type = 'danger';
    } else {
        // Reset lockout if passed
        if (time() >= $_SESSION['lockout_time']) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = 0;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $message = "Please enter both email and password.";
            $message_type = "danger";
        } else {
            $query = "SELECT * FROM register WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (isset($user['is_active']) && $user['is_active'] == 0) {
                    $message = "Your account has been deactivated. Please contact admin.";
                    $message_type = "danger";
                } elseif (password_verify($password, $user['password'])) {
                    // Successful login
                    session_regenerate_id(true); // Prevent session fixation
                    $_SESSION['userid'] = $user['id'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['store_section'] = $user['store_section'];

                    if (isset($user['role']) && $user['role'] === 'admin') {
                        $_SESSION['adminid'] = $user['id'];
                        if (isset($user['status']) && $user['status'] == 0) {
                            header("Location: changepassword.php");
                            exit();
                        } else {
                            header("Location: admindashboard.php");
                            exit();
                        }
                    } else {
                        if (isset($user['status']) && $user['status'] == 0) {
                            header("Location: changepassword.php");
                            exit();
                        } else {
                            header("Location: dashboard.php");
                            exit();
                        }
                    }
                } else {
                    // Invalid password
                    $_SESSION['login_attempts']++;
                    if ($_SESSION['login_attempts'] >= $max_attempts) {
                        $_SESSION['lockout_time'] = time() + $lockout_time;
                    }
                    $message = "Invalid email or password.";
                    $message_type = "danger";
                }
            } else {
                // Invalid email
                $_SESSION['login_attempts']++;
                if ($_SESSION['login_attempts'] >= $max_attempts) {
                    $_SESSION['lockout_time'] = time() + $lockout_time;
                }
                $message = "Invalid email or password.";
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
    <title>OAUTHC Stores & Supplies - Login</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css" />
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #f8fafc;
            --accent-color: #10b981;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --medical-blue: #0ea5e9;
            --medical-green: #059669;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            pointer-events: none;
        }

        .login-container {
            background: white;
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
            position: relative;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--medical-blue), var(--medical-green), var(--primary-color));
        }

        .welcome-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(37,99,235,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(16,185,129,0.1)"/><circle cx="40" cy="80" r="1" fill="rgba(14,165,233,0.1)"/></svg>');
            pointer-events: none;
        }

        .logo-container {
            width: 120px;
            height: 120px;
            /* background: linear-gradient(135deg, var(--medical-blue), var(--medical-green)); */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.3);
            position: relative;
        }

        .logo-container::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            /* background: linear-gradient(135deg, var(--medical-blue), var(--medical-green)); */
            z-index: -1;
            opacity: 0.3;
            filter: blur(8px);
        }

        .caduceus-icon {
            font-size: 48px;
            color: white;
        }

        .welcome-title {
            font-family: 'Poppins', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .welcome-subtitle {
            font-size: 18px;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .department-name {
            font-size: 16px;
            color: var(--medical-blue);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .features-list {
            margin-top: 40px;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .feature-item i {
            color: var(--medical-green);
            margin-right: 12px;
            font-size: 16px;
        }

        .login-section {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 16px;
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
            font-size: 18px;
            z-index: 2;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px 16px 50px;
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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-control:focus + .input-icon {
            color: var(--primary-color);
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
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
        }

        .remember-me label {
            font-size: 14px;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn-login {
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
            margin-bottom: 24px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            background: white;
            padding: 0 16px;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .help-section {
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            margin-top: 20px;
        }

        .help-section h6 {
            color: var(--text-primary);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .help-section p {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 12px;
        }

        .help-contact {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--medical-green);
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                margin: 10px;
                border-radius: 20px;
            }
            
            .welcome-section {
                padding: 40px 30px;
            }
            
            .login-section {
                padding: 40px 30px;
            }
            
            .welcome-title {
                font-size: 24px;
            }
            
            .login-header h2 {
                font-size: 24px;
            }
            
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
        }

        @media (max-width: 576px) {
            .welcome-section,
            .login-section {
                padding: 30px 20px;
            }
            
            .form-control {
                padding: 14px 16px 14px 45px;
            }
        }
    </style>
</head>
<body>
    <?php if (!empty($message)): ?>
  <div class="alert alert-<?php echo htmlspecialchars($message_type); ?>">
    <?php echo htmlspecialchars($message); ?>
  </div>
<?php endif; ?>

    <div class="login-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="">
            <img src="image/logo.png" alt="OAuthC Logo" style="height: 90px;">

            </div>
            
            <h1 class="welcome-title">Welcome!</h1>
            <p class="welcome-subtitle">OAUTHC</p>
            <p class="department-name">Stores and Supplies Department</p>
            
            <div class="features-list">
                <div class="feature-item">
                    <i class="bi bi-shield-check"></i>
                    <span>Secure inventory management</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-graph-up"></i>
                    <span>Real-time supply tracking</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-people"></i>
                    <span>Multi-user access control</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-clock-history"></i>
                    <span>24/7 system availability</span>
                </div>
            </div>
        </div>

        <!-- Login Section -->
        <div class="login-section">
            <div class="login-header">
                <h2>Sign In</h2>
                <p>Access your account with your credentials</p>
            </div>

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <form action="index.php" method="post">
                <!-- Username/Email Field -->
                <div class="form-group">
                    <label for="username" class="form-label">Email</label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email">

                        <i class="bi bi-person input-icon"></i>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                            <input type="password"name="password" class="form-control" id="password" placeholder="Password">

                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Form Options -->
                <div class="form-options">
                    <!-- <div class="remember-me">
                        <input type="checkbox" id="rememberMe">
                        <label for="rememberMe">Remember me</label>
                    </div> -->
                    <a href="#" class="forgot-password" onclick="showForgotPassword()">Forgot Password?</a>
                </div>

                <!-- Login Button -->
                    <button type="submit" name="submit" class="btn btn-primary w-100">
                    <span class="loading-spinner" id="loadingSpinner"></span>
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Sign In
                </button>
            </form>

            <div class="divider">
                <span>Need Help?</span>
            </div>

            <!-- Help Section -->
            <div class="help-section">
                <h6>Don't have an account yet?</h6>
                <!-- <p>Contact the IT Support team for assistance</p> -->
                <a href="signup.php" class="help-contact" onclick="showContactInfo()">
                    <i class="bi bi-telephone me-1"></i>
                    Sign up
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password visibility toggle
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.className = 'bi bi-eye-slash';
            } else {
                passwordField.type = 'password';
                passwordIcon.className = 'bi bi-eye';
            }
        }

        // Show alert message
        function showAlert(message, type = 'danger') {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
            
            alertContainer.innerHTML = `
                <div class="alert ${alertClass}">
                    <i class="bi ${icon} me-2"></i>
                    ${message}
                </div>
            `;
            
            // Auto-hide success messages
            if (type === 'success') {
                setTimeout(() => {
                    alertContainer.innerHTML = '';
                }, 5000);
            }
        }

       

        // Forgot password function
        function showForgotPassword() {
            showAlert('Password reset functionality will be available soon. Please contact IT Support for assistance.', 'success');
        }

        // Contact info function
        function showContactInfo() {
            showAlert('IT Support: ext. 2345 | Email: itsupport@oauthc.edu | Available 24/7', 'success');
        }

        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate container on load
            const container = document.querySelector('.login-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px) scale(0.95)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0) scale(1)';
            }, 100);

            // Add focus animations to form fields
            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach(control => {
                control.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });
                
                control.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });
        });

        // Demo credentials hint
        // setTimeout(() => {
        //     showAlert('Demo: Use username "admin" and password "password123" to login', 'success');
        // }, 3000);
    </script>
    <script src="vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>