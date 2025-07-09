<?php
    require_once __DIR__ . '/../../../config.php';

    //check if ID and type are provided
    if (!isset($_GET['id']) || !isset($_GET['type'])) {
        display_alert("Invalid request. Missing parameters.", "danger");
        header('Location: ' . BASE_URL . '/admin/faculty-staff-list.php');
        exit();
    }

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $valid_types = ['faculty', 'staff', 'office'];
    $type = isset($_GET['type']) && in_array($_GET['type'], $valid_types) ? $_GET['type'] : 'faculty';

    $page_title = ucfirst($type) . " Details";

    require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';
    require_once APP_PATH . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'listController.php';

    //redirect if not logged in
    redirect_if_not_logged_in();

    //get list details
    $listController = new ListController($conn);
    $list = $listController->getListDetails($id, $type);

    if (!$list) {
        display_alert("List not found.", "danger");
        header('Location: ' . BASE_URL . '/admin/faculty-staff-list.php?type=' . $type);
        exit();
    }

    //update page title based on list name - for display in the content area
    $displayTitle = "";
    if ($type === 'office') {
        $displayTitle = "Office Details: " . $list['office_name'];
    } else {
        $displayTitle = "Details: " . $list['name'];
    }

    //handle list operations (add, update, delete)
    $listController->handleListOperations($type);
?>

<div class="admin-layout">
    <?php include '../../../includes/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="content-header">
            <div class="header-actions">
                <a href="<?php echo BASE_URL; ?>/admin/faculty-staff-list.php?type=<?php echo $type; ?>" class="btn">Back to List</a>
                <!-- <button class="btn" data-list='<?php echo json_encode($list); ?>'>Edit</button> -->
                <button class="btn edit-faculty-staff-list-modal" data-list='<?php echo json_encode($list); ?>'>Edit</button>
            </div>
        </div>
        
        <div class="faculty-staff-list-details-container">
            <?php if ($type === 'faculty' || $type === 'staff'): ?>
               <!-- profile photo section -->
                <div class="profile-section">
                    <div class="faculty-staff-list-photo-container">
                        <?php if (!empty($list['photo_path'])): ?>
                            <img src="<?php echo BASE_URL . '/' . $list['photo_path']; ?>" alt="<?php echo $list['name']; ?>">
                        <?php else: ?>
                            <div class="no-photo-text">No Photo Available</div>
                        <?php endif; ?>
                    </div>
                    <a href="mailto:<?php echo $list['email'] ?? ''; ?>" class="email-inquiry">Send an email inquiry</a>
                </div>
                
                <h2 class="faculty-staff-list-name-header"><?php echo $list['name']; ?></h2>
                <!-- academic rank and unit section -->
                <div class="info-section">
                    <?php if ($type === 'faculty'): ?>
                        <h4 class="section-header">Academic Rank and Insitute</h4>
                    <?php else: ?>
                        <h4 class="section-header">Position and Office</h4>
                    <?php endif; ?>
                    <div class="section-content">
                        <div class="info-row">
                            <?php if ($type === 'faculty'): ?>
                                <div class="info-label">Rank</div>
                            <?php else: ?>
                                <div class="info-label">Position</div>
                            <?php endif; ?>
                            <div class="info-value"><?php echo $list['academic_rank'] ?? 'N/A'; ?></div>
                        </div>
                        <div class="info-row">
                            <?php if ($type === 'faculty'): ?>
                                <div class="info-label">Insitute</div>
                            <?php else: ?>
                                <div class="info-label">Office</div>
                            <?php endif; ?>
                            <div class="info-value"><?php echo $list['institute'] ?? 'N/A'; ?></div>
                        </div>
                    </div>
                </div>

                <!-- designation -->
                <!-- <div class="info-section">
                    <h4 class="section-header">Designation</h4>
                    <div class="section-content">
                        <div class="info-row">
                            <div class="info-label">Designation</div>
                            <div class="info-value"><?php echo $list['designation'] ?? 'N/A'; ?></div>
                        </div>
                    </div>
                </div> -->
                <!-- education section -->
                <div class="info-section">
                    <h4 class="section-header">Designation</h4>
                    <div class="section-content">
                        <div class="info-row">
                            <div class="info-label">Designation<?php echo json_decode($list['designation']) && count(json_decode($list['research_title'])) > 1 ? 's' : ''; ?></div>
                            <div class="info-value">
                                <?php 
                                $designation = json_decode($list['designation'], true);
                                if (is_array($designation)) {
                                    echo '<ul class="research-titles-list">';
                                    foreach ($designation as $title) {
                                        echo '<li>' . htmlspecialchars($title) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo htmlspecialchars($list['designation']);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- email -->
                <div class="info-section">
                    <h4 class="section-header">Email Address</h4>
                    <div class="section-content">
                        <div class="info-row">
                            <div class="info-label">Email Address</div>
                            <div class="info-value"><?php echo $list['email'] ?? 'N/A'; ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- education section -->
                <div class="info-section">
                    <h4 class="section-header">Education</h4>
                    <div class="section-content">
                        <div class="info-row">
                            <div class="info-label">Education<?php echo json_decode($list['education']) && count(json_decode($list['research_title'])) > 1 ? 's' : ''; ?></div>
                            <div class="info-value">
                                <?php 
                                $education = json_decode($list['education'], true);
                                if (is_array($education)) {
                                    echo '<ul class="research-titles-list">';
                                    foreach ($education as $title) {
                                        echo '<li>' . htmlspecialchars($title) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo htmlspecialchars($list['education']);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- <?php if ($type === 'faculty' && (!empty($list['research_title']) || !empty($list['research_link']))): ?>
                
                    <div class="info-section">
                        <h4 class="section-header">Research and Link</h4>
                        <div class="section-content">
                            <?php if (!empty($list['research_title'])): ?>
                            <div class="info-row">
                                <div class="info-label">Research Title<?php echo json_decode($list['research_title']) && count(json_decode($list['research_title'])) > 1 ? 's' : ''; ?></div>
                                <div class="info-value">
                                    <?php 
                                    $research_titles = json_decode($list['research_title'], true);
                                    if (is_array($research_titles)) {
                                        echo '<ul class="research-titles-list">';
                                        foreach ($research_titles as $title) {
                                            echo '<li>' . htmlspecialchars($title) . '</li>';
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo htmlspecialchars($list['research_title']);
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($list['research_link'])): ?>
                            <div class="info-row">
                                <div class="info-label">Research Link</div>
                                <div class="info-value">
                                    <a href="<?php echo $list['research_link']; ?>" target="_blank"><?php echo $list['research_link']; ?></a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?> -->
                <div class="info-section">
                    <h4 class="section-header">Research</h4>
                    <div class="section-content">
                        <?php if (!empty($list['research_title'])): ?>
                        <div class="info-row">
                            <div class="info-label">Research Title<?php echo json_decode($list['research_title']) && count(json_decode($list['research_title'])) > 1 ? 's' : ''; ?></div>
                            <div class="info-value">
                                <?php 
                                $research_titles = json_decode($list['research_title'], true);
                                if (is_array($research_titles)) {
                                    echo '<ul class="research-titles-list">';
                                    foreach ($research_titles as $title) {
                                        echo '<li>' . htmlspecialchars($title) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo htmlspecialchars($list['research_title']);
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($list['research_link'])): ?>
                            <div class="info-row">
                                <div class="info-label">Research Link<?php echo json_decode($list['research_link']) && count(json_decode($list['research_link'])) > 1 ? 's' : ''; ?></div>
                                <div class="info-value">
                                    <?php 
                                    $research_links = json_decode($list['research_link'], true);
                                    if (is_array($research_links)) {
                                        echo '<ul class="research-titles-list">';
                                        foreach ($research_links as $index => $link) {
                                            echo '<li><a href="' . htmlspecialchars($link) . '" target="_blank">' . htmlspecialchars($link) . '</a></li>';
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo '<a href="' . htmlspecialchars($list['research_link']) . '" target="_blank">' . htmlspecialchars($list['research_title'] ?? $list['research_link']) . '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- <?php if ($type === 'faculty' && !empty($list['google_scholar_link'])): ?>
                
                <div class="info-section">
                    <h4 class="section-header">Google Scholar</h4>
                    <div class="section-content">
                        <div class="info-row">
                            <div class="info-label">Google Link</div>
                            <div class="info-value">
                                <a href="<?php echo $list['google_scholar_link']; ?>" target="_blank"><?php echo $list['google_scholar_link']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?> -->
                <div class="info-section">
                    <h4 class="section-header">Google Scholar</h4>
                    <div class="section-content">
                        <div class="info-row">
                            <div class="info-label">Google Link</div>
                            <div class="info-value">
                                <a href="<?php echo $list['google_scholar_link']; ?>" target="_blank"><?php echo $list['google_scholar_link']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <h2 class="faculty-staff-list-name-header"><?php echo $list['office_name']; ?></h2>
                <div class="info-section">
                    <h4 class="section-header">About</h4>
                    <div class="section-content">
                        <div class="info-row">
                            <div class="info-label">About</div>
                            <div class="info-value"><?php echo $list['about'] ?? 'N/A'; ?></div>
                        </div>
                    </div>
                </div>
                <div class="info-section">
                     <h4 class="section-header">Office Head</h4>
                    <div class="section-content">
                        <div class="info-row">
                            <div class="info-label">Office Head</div>
                            <div class="info-value"><?php echo $list['head'] ?? 'N/A'; ?></div>
                        </div>
                    </div>
                </div>
                <div class="info-section">
                     <h4 class="section-header">Contact Details</h4>
                    <div class="section-content">
                        <div class="info-row">
                            <div class="info-label">Contact Number</div>
                            <div class="info-value"><?php echo $list['contact_number'] ?? 'N/A'; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?php echo $list['email'] ?? 'N/A'; ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- include modal components for editing -->
<?php include APP_PATH . '/Views/components/modal.php'; ?>

<!-- include modal styles -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/modal.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/faculty-staff-list.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/faculty-staff-list-details.css">

<!-- include modal scripts -->
<script src="<?php echo BASE_URL; ?>/assets/js/modal.js"></script>
<script>
    const BASE_URL = "<?php echo BASE_URL; ?>";
</script>

<?php require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'footer.php'; ?>