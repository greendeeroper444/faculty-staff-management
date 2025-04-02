<?php
    $page_title = "Manage Members";
    require_once '../../../includes/header.php';
    require_once '../../../config.php';
    require_once '../../../app/Controllers/manageController.php';

    //redirect if not logged in
    redirect_if_not_logged_in();

    //determine which type of members to display
    $valid_types = ['faculty', 'staff'];
    $type = isset($_GET['type']) && in_array($_GET['type'], $valid_types) ? $_GET['type'] : 'faculty';

    //set page title based on type
    $page_title = ucfirst($type) . ' Directory';

    //initialize the member controller
    $memberController = new MemberController($conn);

    //handle member operations (add, update, delete)
    $memberController->handleMemberOperations($type);

    //fetch members from database
    $members = $memberController->getMembers($type);
?>

<div class="admin-layout">
    <?php include '../../../includes/sidebar.php'; ?>
    
    <div class="admin-content">
        <h2 class="page-title"><?php echo $page_title; ?></h2>
        <div>
           <?php include APP_PATH . '/Views/components/search-container.php'; ?>
        </div>
        <div class="actions">
            <button class="btn open-modal" data-modal="add-member-modal">Add New <?php echo ucfirst($type); ?></button>
        </div>
        
        <?php if (count($members) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <?php if ($type === 'faculty'): ?>
                            <th>Academic Rank</th>
                        <?php else: ?>
                            <th>Position</th>
                        <?php endif; ?>
                        <th>Institute</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/admin/member-details.php?id=<?php echo $member['id']; ?>&type=<?php echo $type; ?>">
                                    <?php if (!empty($member['photo_path']) && file_exists('../' . $member['photo_path'])): ?>
                                        <img src="<?php echo BASE_URL . '/' . $member['photo_path']; ?>" alt="<?php echo $member['name']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    <?php else: ?>
                                        <div class="no-photo-thumbnail">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/admin/member-details.php?id=<?php echo $member['id']; ?>&type=<?php echo $type; ?>" class="member-name">
                                    <?php echo $member['name']; ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $type === 'faculty' ? $member['academic_rank'] : $member['position']; ?>
                            </td>
                            <td><?php echo $member['institute']; ?></td>
                            <td class="action-buttons">
                                <button class="btn edit-member-btn" data-member='<?php echo json_encode($member); ?>'>Edit</button>
                                <!-- <a href="<?php echo BASE_URL; ?>/admin/manage-members.php?action=delete&id=<?php echo $member['id']; ?>&type=<?php echo $type; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this member?')">Delete</a> -->
                                <a href="<?php echo BASE_URL; ?>/admin/manage-members.php?action=delete&id=<?php echo $member['id']; ?>&type=<?php echo $type; ?>" class="btn btn-danger delete-member-btn">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No <?php echo $type; ?> members found.</p>
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
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/manage-members.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- include modal scripts -->
<script src="<?php echo BASE_URL; ?>/assets/js/modal.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/filter.js"></script>

<?php require_once '../../../includes/footer.php'; ?>