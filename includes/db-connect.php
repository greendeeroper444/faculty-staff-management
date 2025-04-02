<?php
    //database connection settings
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "faculty_staff";

    //create connection
    $conn = new mysqli($host, $username, $password, $database);

    //check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>