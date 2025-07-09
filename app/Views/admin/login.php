<?php
    // Include config.php to define ROOT_PATH and other constants
    require_once __DIR__ . '/../../../config.php';

    //start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $page_title = "Admin Login";
    require_once ROOT_PATH . '/index.php';
    require_once ROOT_PATH . '/includes/db-connect.php';
    require_once ROOT_PATH . '/includes/functions.php';
    require_once ROOT_PATH . '/includes/header.php';
    require_once ROOT_PATH . '/app/Controllers/authController.php';
    

    //create controller instance
    $authController = new AuthController($conn);

    //if user is already logged in, redirect to admin dashboard
    if (isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])) {
        header('Location: ' . BASE_URL . '/admin/faculty-staff-list.php?type=faculty');
        exit();
    }

    //process login form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //get form data
        $username = sanitize($_POST['username']);
        $password = $_POST['password'];
        
        //validate form data
        $errors = [];
        if (empty($username)) {
            $errors[] = "Username is required";
        }
        if (empty($password)) {
            $errors[] = "Password is required";
        }
        
        //if no validation errors, attempt login
        if (empty($errors)) {
            $login_result = $authController->adminLogin($username, $password);
            
            if ($login_result['success']) {
                //login successful, set session
                $_SESSION['admin_id'] = $login_result['admin_id'];
                $_SESSION['username'] = $login_result['username'];
                
                //redirect to admin dashboard
                header('Location: ' . BASE_URL . '/admin/faculty-staff-list.php?type=faculty');
                exit();
            } else {
                $errors[] = "Invalid username or password";
            }
        }
    }
?>

<!-- FontAwesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="login-wrapper">
    <div class="login-form">
        <div class="login-header">
            <h2>Admin Portal</h2>
            <div class="login-subtitle">Enter your credentials to manage the faculty/staff list.</div>
        </div>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <span class="input-icon username-icon">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" id="username" name="username" placeholder="Enter your username" autocomplete="off">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon password-icon">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                    <span class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            
            <div class="form-group remember-group">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary" id="login-btn">
                    <span class="btn-text">Login</span>
                    <span class="spinner"><i class="fas fa-circle-notch fa-spin"></i> Logging in...</span>
                </button>
            </div>
        </form>
        
        <div class="help-text">
            <span class="info-icon"><i class="fas fa-info-circle"></i></span>
            <span>Note: If you don't have an admin account, please contact the system administrator.</span>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/login.css">
<script src="<?php echo BASE_URL; ?>/assets/js/login.js"></script>