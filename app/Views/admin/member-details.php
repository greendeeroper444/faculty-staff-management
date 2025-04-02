<?php
    $page_title = "Member Details";
    require_once '../../../includes/header.php';
    require_once '../../../config.php';
    require_once '../../../app/Controllers/manageController.php';

    //redirect if not logged in
    redirect_if_not_logged_in();

    //check if ID and type are provided
    if (!isset($_GET['id']) || !isset($_GET['type'])) {
        display_alert("Invalid request. Missing parameters.", "danger");
        header('Location: ' . BASE_URL . '/admin/manage-members.php');
        exit();
    }

    $id = intval($_GET['id']);
    $valid_types = ['faculty', 'staff'];
    $type = isset($_GET['type']) && in_array($_GET['type'], $valid_types) ? $_GET['type'] : 'faculty';

    //get member details
    $memberController = new MemberController($conn);
    $member = $memberController->getMemberDetails($id, $type);

    if (!$member) {
        display_alert("Member not found.", "danger");
        header('Location: ' . BASE_URL . '/admin/manage-members.php?type=' . $type);
        exit();
    }

    //set page title based on member name
    $page_title = "Details: " . $member['name'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_member'])) {
        //get member ID
        $member_id = intval($_POST['member_id']);
        
        //common fields for both faculty and staff
        $name = trim($_POST['name'] ?? '');
        $institute = trim($_POST['institute'] ?? '');
        $education = trim($_POST['education'] ?? '');
        $existing_photo_path = trim($_POST['existing_photo_path'] ?? '');
        
        //type-specific fields
        if ($type === 'faculty') {
            $academic_rank = trim($_POST['academic_rank'] ?? '');
            //old
            // $research_title = trim($_POST['research_title'] ?? '');
            //new
            $research_title = isset($_POST['research_title']) ? $_POST['research_title'] : [];
            $research_title = json_encode(array_filter($research_title));
            $research_link = trim($_POST['research_link'] ?? '');
            $google_scholar_link = trim($_POST['google_scholar_link'] ?? '');
        } else {
            $position = trim($_POST['position'] ?? '');
        }
        
        //validate required fields
        $errors = [];
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        
        if (empty($errors)) {
            //handle photo upload - use existing photo if no new upload
            $photo_path = $existing_photo_path;
            
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/photos/';
                
                //create directory if it doesn't exist
                if (!is_dir('../' . $upload_dir)) {
                    mkdir('../' . $upload_dir, 0755, true);
                }
                
                $file_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $file_name = uniqid() . '_' . time() . '.' . $file_ext;
                $upload_path = '../' . $upload_dir . $file_name;
                
                $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($file_ext, $allowed_exts)) {
                    $errors[] = 'Only JPG, JPEG, PNG, and GIF files are allowed';
                } else if ($_FILES['photo']['size'] > 2097152) { // 2MB
                    $errors[] = 'File size must be less than 2MB';
                } else if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                    //delete the old photo if exists
                    if (!empty($existing_photo_path) && file_exists('../' . $existing_photo_path)) {
                        unlink('../' . $existing_photo_path);
                    }
                    $photo_path = $upload_dir . $file_name;
                } else {
                    $errors[] = 'Failed to upload file';
                }
            }
            
            if (empty($errors)) {
                //prepare update data
                $data = [
                    'name' => $name,
                    'photo_path' => $photo_path,
                    'institute' => $institute,
                    'education' => $education
                ];
                
                if ($type === 'faculty') {
                    $data['academic_rank'] = $academic_rank;
                    $data['research_title'] = $research_title;
                    $data['research_link'] = $research_link;
                    $data['google_scholar_link'] = $google_scholar_link;
                } else {
                    $data['position'] = $position;
                }
                
                //use the controller to update the member
                require_once '../../../app/Controllers/listController.php';
               //use the controller to update the member
                $listController = new ListController($conn);

                if ($listController->updateMember($member_id, $data, $type)) {
                    display_alert('Member updated successfully!', 'success');
                    //redirect to the current page itself
                    header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id . '&type=' . $type);
                    exit();
                } else {
                    $errors[] = 'Error updating member: ' . $conn->error;
                }
            }
        }
        
        //display errors if any
        if (!empty($errors)) {
            foreach ($errors as $error) {
                display_alert($error, 'danger');
            }
        }
    }
?>

<div class="admin-layout">
    <?php include '../../../includes/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="content-header">
            <h2 class="member-name-header"><?php echo $member['name']; ?></h2>
            <div class="header-actions">
                <a href="<?php echo BASE_URL; ?>/admin/manage-members.php?type=<?php echo $type; ?>" class="btn">Back to List</a>
                <button class="btn" data-member='<?php echo json_encode($member); ?>'>Edit</button>
            </div>
        </div>
        
        <div class="member-details-container">
            <!-- Profile photo section -->
            <div class="profile-section">
                <div class="member-photo-container">
                    <?php if (!empty($member['photo_path']) && file_exists('../' . $member['photo_path'])): ?>
                        <img src="<?php echo BASE_URL . '/' . $member['photo_path']; ?>" alt="<?php echo $member['name']; ?>">
                    <?php else: ?>
                        <div class="no-photo-text">No Photo Available</div>
                    <?php endif; ?>
                </div>
                <a href="mailto:<?php echo $member['email'] ?? ''; ?>" class="email-inquiry">Send an email inquiry</a>
            </div>
            
            <!-- Academic rank and unit section -->
            <div class="info-section">
                <h4 class="section-header">Academic rank and unit</h4>
                <div class="section-content">
                    <?php if ($type === 'faculty'): ?>
                        <div class="info-row">
                            <div class="info-label">Rank</div>
                            <div class="info-value"><?php echo $member['academic_rank'] ?? 'N/A'; ?></div>
                        </div>
                    <?php else: ?>
                        <div class="info-row">
                            <div class="info-label">Position</div>
                            <div class="info-value"><?php echo $member['position'] ?? 'N/A'; ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="info-row">
                        <div class="info-label">Department</div>
                        <div class="info-value"><?php echo $member['department'] ?? 'N/A'; ?></div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">College</div>
                        <div class="info-value"><?php echo $member['institute'] ?? 'N/A'; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Education section -->
            <div class="info-section education-section">
                <h4 class="section-header">Education</h4>
                <div class="section-content">
                    <div class="info-row">
                        <div class="info-label"><?php echo $member['school'] ?? 'N/A'; ?></div>
                        <div class="info-value"><?php echo $member['education'] ?? 'N/A'; ?></div>
                    </div>
                </div>
            </div>
            
            <?php if ($type === 'faculty' && (!empty($member['research_title']) || !empty($member['research_link']))): ?>
                <!-- Research section (if applicable) -->
                <div class="info-section">
                    <h4 class="section-header">Research</h4>
                    <div class="section-content">
                        <?php if (!empty($member['research_title'])): ?>
                        <div class="info-row">
                            <div class="info-label">Research Title<?php echo json_decode($member['research_title']) && count(json_decode($member['research_title'])) > 1 ? 's' : ''; ?></div>
                            <div class="info-value">
                                <?php 
                                $research_titles = json_decode($member['research_title'], true);
                                if (is_array($research_titles)) {
                                    echo '<ul class="research-titles-list">';
                                    foreach ($research_titles as $title) {
                                        echo '<li>' . htmlspecialchars($title) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo htmlspecialchars($member['research_title']);
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($member['research_link'])): ?>
                        <div class="info-row">
                            <div class="info-label">Research Link</div>
                            <div class="info-value">
                                <a href="<?php echo $member['research_link']; ?>" target="_blank"><?php echo $member['research_link']; ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($type === 'faculty' && !empty($member['google_scholar_link'])): ?>
            <!-- Google Scholar section -->
            <div class="info-section">
                <h4 class="section-header">Google Scholar</h4>
                <div class="section-content">
                    <div class="info-row">
                        <div class="info-label">Profile</div>
                        <div class="info-value">
                            <a href="<?php echo $member['google_scholar_link']; ?>" target="_blank"><?php echo $member['google_scholar_link']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Include modal components for editing -->
<?php include APP_PATH . '/Views/components/modal.php'; ?>

<!-- Include modal styles -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/modal.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/manage-members.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/member-details.css">

<!-- Include modal scripts -->
<script src="<?php echo BASE_URL; ?>/assets/js/modal.js"></script>
<script>
    const BASE_URL = "<?php echo BASE_URL; ?>";
</script>

<?php require_once '../../../includes/footer.php'; ?>