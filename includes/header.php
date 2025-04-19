<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/db-connect.php';
require_once __DIR__ . '/functions.php';
start_session_if_not_started();
?>
<!DOCTYPE html>
<html lang="en">
<head></head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/main.css"> <!-- Ensure correct path -->
    <?php if (isset($extra_css)) echo $extra_css; ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<body>
    <!-- <header class="main-header">
        <div class="container">
            <h1 class="site-title"><a href="<?php echo BASE_URL; ?>"><?php echo SITE_NAME; ?></a></h1>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/faculty-listing.php">Faculty</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/staff-listing.php">Staff</a></li>
                    <?php if (is_logged_in()): ?>
                        <li><a href="<?php echo BASE_URL; ?>/admin/">Admin Panel</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/admin/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>/login.php">Admin Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header> -->
    <main class="main-content">
        <div class="container">
            <?php show_alert(); ?>