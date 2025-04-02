<?php

    require_once '../../../index.php';
    //start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $page_title = "Admin Login";
    require_once '../../../config.php';
    require_once '../../../includes/db-connect.php';
    require_once '../../../includes/functions.php';
    require_once '../../../includes/header.php';
    require_once '../../../app/Controllers/AuthController.php';

    //create controller instance
    $authController = new AuthController($conn);

    //if user is already logged in, redirect to admin dashboard
    if (isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])) {
        header('Location: ' . BASE_URL . '/admin/manage-members.php?type=faculty');
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
                header('Location: ' . BASE_URL . '/admin/manage-members.php?type=faculty');
                exit();
            } else {
                $errors[] = "Invalid username or password";
            }
        }
    }
?>

<div class="login-form">
    <h2>Admin Login</h2>
    
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username">
            <!-- value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" -->
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
    
    <p class="text-center mt-3">
        Note: If you don't have an admin account, please contact the system administrator.
    </p>
</div>

<style>
    .login-form {
        max-width: 400px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .login-form h2 {
        margin-top: 0;
        margin-bottom: 1.5rem;
        text-align: center;
        color: #00722c;
    }

    .text-center {
        text-align: center;
    }

    .mt-3 {
        margin-top: 1rem;
    }
</style>

<!-- <?php
require_once '../../../includes/footer.php';
?> -->