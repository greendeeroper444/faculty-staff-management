<?php
    require_once '../config.php';
    require_once '../app/Controllers/authController.php';


    $conn = new mysqli('localhost', 'root', '', 'faculty_staff'); // Update with your database credentials
    $authController = new AuthController($conn);

    $password = "Mozilla22";
    $hashedPassword = $authController->hashPassword($password);

    echo "Hashed Password: " . $hashedPassword;
?>
