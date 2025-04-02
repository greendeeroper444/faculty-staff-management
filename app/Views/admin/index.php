<?php
$page_title = 'Admin Dashboard';
require_once __DIR__ . '/../../../includes/header.php';

//redirect if not logged in
redirect_if_not_logged_in();

//get the requested page
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$validPages = ['dashboard', 'member-details', 'manage-members', 'search-members'];
?>

<div class="admin-layout">
    <?php include __DIR__ . '/../../../includes/sidebar.php'; ?>
    
    <div class="admin-content">
        <?php
            //load the requested page content
            if (in_array($page, $validPages)) {
                //include the content file directly - ensure these files don't have their own header/sidebar/footer
                include __DIR__ . "/{$page}.php";
            } else {
                //default content
                echo '<h2>Welcome to Admin Dashboard</h2>';
                echo '<p>Please select an option from the sidebar.</p>';
            }
        ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>