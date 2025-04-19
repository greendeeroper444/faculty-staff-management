<?php

    //start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once '../../../config.php';
    require_once '../../../includes/db-connect.php';
    require_once '../../../app/Controllers/AuthController.php';

    //create AuthController instance
    $authController = new AuthController($conn);

    //logout user
    $authController->logout();

    // display_alert('Logged Out successfully!', 'success');
    //redirect to login page
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit();
?>