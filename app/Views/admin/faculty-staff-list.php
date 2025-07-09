<?php
    require_once __DIR__ . '/../../../config.php';

    //determine which type of lists to display
    $valid_types = ['faculty', 'staff', 'office'];
    $type = isset($_GET['type']) && in_array($_GET['type'], $valid_types) ? $_GET['type'] : 'faculty';

    //set page title based on type
    $page_title = ucfirst($type) . ' Directory';

    require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';
    require_once APP_PATH . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'listController.php';
    
    //redirect if not logged in
    redirect_if_not_logged_in();

    //initialize the list controller
    $listController = new ListController($conn);

    //handle list operations (add, update, delete)
    $listController->handleListOperations($type);

    //fetch lists from database
    $lists = $listController->getLists($type);
?>

<div class="admin-layout">
    <?php include ROOT_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'sidebar.php'; ?>
    
    <div class="admin-content">
        <h2 class="page-title"><?php echo $page_title; ?></h2>
        <div>
           <?php include APP_PATH . '/Views/components/search-container.php'; ?>
        </div>
        <div class="actions">
            <span>Records found: <?php echo count($lists); ?></span>
            <button class="btn open-modal" data-modal="add-faculty-staff-list-modal">Add New <?php echo ucfirst($type); ?></button>
        </div>
        
        <?php if (count($lists) > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <?php if ($type === 'faculty' || $type === 'staff'): ?>
                                <th>Photo</th>
                                <th>Name</th>
                                <?php if ($type === 'faculty'): ?>
                                    <th>Academic Rank</th>
                                    <th>Institute</th>
                                <?php else: ?>
                                    <th>Position</th>
                                    <th>Office</th>
                                <?php endif; ?>
                                <th>Actions</th>
                            <?php else: ?>
                                <th>Office Name</th>
                                <th>Office Head</th>
                                <th>Email Address</th>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lists as $list): ?>
                            <tr>
                                <?php if ($type === 'faculty' || $type === 'staff'): ?>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/admin/faculty-staff-list-details.php?id=<?php echo $list['id']; ?>&type=<?php echo $type; ?>">
                                            <?php if (!empty($list['photo_path'])) : ?>
                                                <img src="<?php echo BASE_URL . '/' . $list['photo_path']; ?>" alt="<?php echo $list['name']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                            <?php else: ?>
                                                <div class="no-photo-thumbnail">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/admin/faculty-staff-list-details.php?id=<?php echo $list['id']; ?>&type=<?php echo $type; ?>" class="list-name">
                                            <?php echo $list['name']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $list['academic_rank']; ?></td>
                                    <td><?php echo $list['institute']; ?></td>
                                <?php else: ?>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/admin/faculty-staff-list-details.php?id=<?php echo $list['id']; ?>&type=<?php echo $type; ?>" class="list-name">
                                            <?php echo $list['office_name']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $list['head']; ?></td>
                                    <td><?php echo $list['email']; ?></td>
                                <?php endif; ?>
                                <td class="action-buttons">
                                    <button class="btn edit-faculty-staff-list-modal" data-list='<?php echo json_encode($list); ?>'>Edit</button>
                                    <a href="<?php echo BASE_URL; ?>/admin/faculty-staff-list.php?action=delete&id=<?php echo $list['id']; ?>&type=<?php echo $type; ?>" class="btn btn-danger delete-confirm-modal">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No <?php echo $type; ?> lists found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    const BASE_URL = "<?php echo BASE_URL; ?>";
</script>

<!-- Iinclude modal components -->
<?php include APP_PATH . '/Views/components/modal.php'; ?>

<!-- include modal styles -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/modal.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/faculty-staff-list.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- include modal scripts -->
<script src="<?php echo BASE_URL; ?>/assets/js/modal.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/filter.js"></script>

<?php require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'footer.php'; ?>