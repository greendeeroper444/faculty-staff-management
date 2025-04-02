<?php

// helper functions for the application

//startsession if not already started
function start_session_if_not_started() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

//check if user is logged in
function is_logged_in() {
    return isset($_SESSION['admin_id']);
}

//redirect if user is not logged in
function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        display_alert("Please log in to access this page.", "warning");
        header('Location: ' . BASE_URL . '/login.php');
        exit();
    }
}

//redirect to specified URL
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

//sanitize input data
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

//display alert message
function display_alert($message, $type = 'info') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

//show alert message if exists
function show_alert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        echo '<div class="alert alert-' . $alert['type'] . '">' . $alert['message'] . '</div>';
        unset($_SESSION['alert']);
    }
}

//upload file
function upload_file($file, $upload_dir = 'uploads/photos/') {
    //create directory if it doesn't exist
    if (!is_dir(ROOT_PATH . '/' . $upload_dir)) {
        mkdir(ROOT_PATH . '/' . $upload_dir, 0755, true);
    }
    
    //check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false,
            'error' => 'Upload failed with error code: ' . $file['error']
        ];
    }
    
    //check file type
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (!in_array($file_ext, $allowed_exts)) {
        return [
            'success' => false,
            'error' => 'Only JPG, JPEG, PNG, and GIF files are allowed'
        ];
    }
    
    //check file size (2MB max)
    if ($file['size'] > 2097152) {
        return [
            'success' => false,
            'error' => 'File size must be less than 2MB'
        ];
    }
    
    //generate unique filename
    $file_name = uniqid() . '_' . time() . '.' . $file_ext;
    $upload_path = ROOT_PATH . '/' . $upload_dir . $file_name;
    
    //move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return [
            'success' => true,
            'path' => $upload_dir . $file_name
        ];
    } else {
        return [
            'success' => false,
            'error' => 'Failed to move uploaded file'
        ];
    }
}

//delete file
function delete_file($path) {
    $full_path = ROOT_PATH . '/' . $path;
    if (!empty($path) && file_exists($full_path)) {
        return unlink($full_path);
    }
    return false;
}