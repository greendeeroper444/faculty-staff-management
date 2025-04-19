<?php
    $page_title = "Manage Members";
    require_once '../../../includes/header.php';
    require_once '../../../config.php';

    //redirect if not logged in
    redirect_if_not_logged_in();

    //determine which type of members to display
    $valid_types = ['faculty', 'staff'];
    $type = isset($_GET['type']) && in_array($_GET['type'], $valid_types) ? $_GET['type'] : 'faculty';

    //set page title based on type
    $page_title = '' . ucfirst($type) . ' Directory';

    //define table based on type
    $table = $type === 'faculty' ? 'faculty_lists' : 'staff_lists';

    //handle delete action
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        //get the photo path before deleting
        $photo_query = "SELECT photo_path FROM $table WHERE id = ?";
        $stmt = $conn->prepare($photo_query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            //delete the photo file if it exists
            if (!empty($row['photo_path']) && file_exists('../' . $row['photo_path'])) {
                unlink('../' . $row['photo_path']);
            }
        }
        
        //delete the member
        $delete_query = "DELETE FROM $table WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            display_alert("Member deleted successfully!", "success");
        } else {
            display_alert("Error deleting member: " . $conn->error, "danger");
        }
        
        //redirect to avoid resubmission
        header('Location: ' . BASE_URL . '/admin/faculty-staff-list.php?type=' . $type);
        exit();
    }

    //process form submission for updating a member
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
                $listController = new ListController($conn);
                
                if ($listController->updateMember($member_id, $data, $type)) {
                    display_alert('Member updated successfully!', 'success');
                    //redirect to avoid resubmission
                    header('Location: ' . BASE_URL . '/admin/faculty-staff-list.php?type=' . $type);
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

    //process form submission for adding a new member
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_member'])) {
        //common fields for both faculty and staff
        $name = trim($_POST['name'] ?? '');
        $institute = trim($_POST['institute'] ?? '');
        $education = trim($_POST['education'] ?? '');
        
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
            //handle photo upload
            $photo_path = '';
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
                    $photo_path = $upload_dir . $file_name;
                } else {
                    $errors[] = 'Failed to upload file';
                }
            }
            
            if (empty($errors)) {
                //prepare and execute SQL query based on member type
                if ($type === 'faculty') {
                    $query = 'INSERT INTO faculty_lists (name, photo_path, academic_rank, institute, education, research_title, research_link, google_scholar_link) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ssssssss', $name, $photo_path, $academic_rank, $institute, $education, $research_title, $research_link, $google_scholar_link);
                } else {
                    $query = 'INSERT INTO staff_lists (name, photo_path, position, institute, education) 
                              VALUES (?, ?, ?, ?, ?)';
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('sssss', $name, $photo_path, $position, $institute, $education);
                }
                
                if ($stmt->execute()) {
                    display_alert('New ' . $type . ' member added successfully!', 'success');
                    //redirect to manage members page to avoid resubmission
                    header('Location: ' . BASE_URL . '/admin/faculty-staff-list.php?type=' . $type);
                    exit();
                } else {
                    $errors[] = 'Error adding member: ' . $conn->error;
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

    //fetch members from database
    $query = "SELECT * FROM $table ORDER BY name ASC";
    $result = $conn->query($query);
?>

<div class="admin-layout">
    <?php include '../../../includes/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="content-header">
            <h2 class="page-title"><?php echo $page_title; ?></h2>
            <div class="header-actions">
                <button class="btn open-modal" data-modal="add-member-modal">Add New <?php echo ucfirst($type); ?></button>
            </div>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
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
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/admin/faculty-staff-list-details.php?id=<?php echo $row['id']; ?>&type=<?php echo $type; ?>">
                                    <?php if (!empty($row['photo_path']) && file_exists('../' . $row['photo_path'])): ?>
                                        <img src="<?php echo BASE_URL . '/' . $row['photo_path']; ?>" alt="<?php echo $row['name']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    <?php else: ?>
                                        <div class="no-photo-thumbnail">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>
                               
                            </td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/admin/faculty-staff-list-details.php?id=<?php echo $row['id']; ?>&type=<?php echo $type; ?>" class="member-name">
                                    <?php echo $row['name']; ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $type === 'faculty' ? $row['academic_rank'] : $row['position']; ?>
                            </td>
                            <td><?php echo $row['institute']; ?></td>
                            <td class="action-buttons">
                                <button class="btn edit-member-btn" data-member='<?php echo json_encode($row); ?>'>Edit</button>
                                <a href="<?php echo BASE_URL; ?>/admin/faculty-staff-list.php?action=delete&id=<?php echo $row['id']; ?>&type=<?php echo $type; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
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

<!-- include modal components -->
<?php include APP_PATH . '/Views/components/modal.php'; ?>

<!-- include modal styles -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/modal.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/faculty-staff-list.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- include modal scripts -->
<script src="<?php echo BASE_URL; ?>/assets/js/modal.js"></script>

<?php require_once '../../../includes/footer.php'; ?>