<?php

class MemberController {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

   //get all institutes for dropdown options
    public function getInstitutes() {
        $faculty_table = $this->getTableName('faculty');
        $staff_table = $this->getTableName('staff');
        
        //query to get unique institutes from both faculty and staff tables
        $query = "SELECT DISTINCT institute FROM (
            SELECT institute FROM $faculty_table 
            UNION 
            SELECT institute FROM $staff_table
        ) AS combined_institutes 
        WHERE institute != '' 
        ORDER BY institute ASC";
        
        $result = $this->conn->query($query);
        
        $institutes = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $institutes[] = $row['institute'];
            }
        }
        
        return $institutes;
    }
    

    //search members by criteria
    public function searchMembers($type, $name = '', $institute = '', $letter = '') {
        $table = $this->getTableName($type);
        $query = "SELECT * FROM $table WHERE 1=1";
        $params = [];
        $types = "";
        
        //add search conditions
        if (!empty($name)) {
            $query .= " AND name LIKE ?";
            $nameLike = "%$name%";
            $params[] = $nameLike;
            $types .= "s";
        }
        
        if (!empty($institute)) {
            $query .= " AND institute = ?";
            $params[] = $institute;
            $types .= "s";
        }
        
        if (!empty($letter)) {
            $query .= " AND name LIKE ?";
            $letterLike = "$letter%";
            $params[] = $letterLike;
            $types .= "s";
        }
        
        //add order by
        $query .= " ORDER BY name ASC";
        
        //prepare and execute query
        $stmt = $this->conn->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        //fetch results
        $members = [];
        while ($row = $result->fetch_assoc()) {
            $members[] = $row;
        }
        
        return $members;
    }
    
    //all members of a specific type
    public function getMembers($type) {
        $table = $this->getTableName($type);
        $query = "SELECT * FROM $table ORDER BY name ASC";
        $result = $this->conn->query($query);
        
        $members = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $members[] = $row;
            }
        }
        
        return $members;
    }
    
   //get detailed information about a specific member
    public function getMemberDetails($id, $type) {
        $table = $this->getTableName($type);
        $query = "SELECT * FROM $table WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    //handle all member operations (add, update, delete)
    public function handleMemberOperations($type) {
        //handle delete operation
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $this->deleteMember($_GET['id'], $type);
        }
        
        //handle update operation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_member'])) {
            $this->updateMember($_POST, $_FILES, $type);
        }
        
        //handle add operation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_member'])) {
            $this->addMember($_POST, $_FILES, $type);
        }
    }
    
    //delete a member
    private function deleteMember($id, $type) {
        $id = intval($id);
        $table = $this->getTableName($type);
        
        //get the photo path before deleting
        $photo_query = "SELECT photo_path FROM $table WHERE id = ?";
        $stmt = $this->conn->prepare($photo_query);
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
        $stmt = $this->conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            display_alert("Member deleted successfully!", "success");
        } else {
            display_alert("Error deleting member: " . $this->conn->error, "danger");
        }
        
        //redirect to avoid resubmission
        header('Location: ' . BASE_URL . '/admin/manage-members.php?type=' . $type);
        exit();
    }
    
    //update a member
    private function updateMember($postData, $files, $type) {
        //get member ID
        $member_id = intval($postData['member_id']);
        
        //common fields for both faculty and staff
        $name = trim($postData['name'] ?? '');
        $institute = trim($postData['institute'] ?? '');
        $education = trim($postData['education'] ?? '');
        $existing_photo_path = trim($postData['existing_photo_path'] ?? '');
        
        //type-specific fields
        if ($type === 'faculty') {
            $academic_rank = trim($postData['academic_rank'] ?? '');
            $research_title = isset($postData['research_title']) ? $postData['research_title'] : [];
            $research_title = json_encode(array_filter($research_title));
            $research_link = trim($postData['research_link'] ?? '');
            $google_scholar_link = trim($postData['google_scholar_link'] ?? '');
        } else {
            $position = trim($postData['position'] ?? '');
        }
        
        //validate required fields
        $errors = [];
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        
        if (empty($errors)) {
            //handle photo upload - use existing photo if no new upload
            $photo_path = $existing_photo_path;
            
            if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
                $photo_path = $this->handlePhotoUpload($files['photo'], $existing_photo_path, $errors);
                
                if (isset($errors[0]) && $errors[0] === 'photo_error') {
                    return;
                }
            }
            
            if (empty($errors)) {
                $table = $this->getTableName($type);
                
                //prepare update data
                if ($type === 'faculty') {
                    $query = "UPDATE $table SET 
                    name = ?, 
                    photo_path = ?, 
                    academic_rank = ?, 
                    institute = ?, 
                    education = ?, 
                    research_title = ?, 
                    research_link = ?, 
                    google_scholar_link = ? 
                    WHERE id = ?";
                    
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('ssssssssi', 
                        $name, 
                        $photo_path, 
                        $academic_rank, 
                        $institute, 
                        $education, 
                        $research_title, 
                        $research_link, 
                        $google_scholar_link, 
                        $member_id
                    );
                } else {
                    $query = "UPDATE $table SET 
                    name = ?, 
                    photo_path = ?, 
                    position = ?, 
                    institute = ?, 
                    education = ? 
                    WHERE id = ?";
                    
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('sssssi', 
                        $name, 
                        $photo_path, 
                        $position, 
                        $institute, 
                        $education, 
                        $member_id
                    );
                }
                
                if ($stmt->execute()) {
                    display_alert('Member updated successfully!', 'success');
                    //redirect to avoid resubmission
                    header('Location: ' . BASE_URL . '/admin/manage-members.php?type=' . $type);
                    exit();
                } else {
                    $errors[] = 'Error updating member: ' . $this->conn->error;
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
    
    //dd a new member
    private function addMember($postData, $files, $type) {
        //common fields for both faculty and staff
        $name = trim($postData['name'] ?? '');
        $institute = trim($postData['institute'] ?? '');
        $education = trim($postData['education'] ?? '');
        
        //type-specific fields
        if ($type === 'faculty') {
            $academic_rank = trim($postData['academic_rank'] ?? '');
            $research_title = isset($postData['research_title']) ? $postData['research_title'] : [];
            $research_title = json_encode(array_filter($research_title));
            $research_link = trim($postData['research_link'] ?? '');
            $google_scholar_link = trim($postData['google_scholar_link'] ?? '');
        } else {
            $position = trim($postData['position'] ?? '');
        }
        
        //validate required fields
        $errors = [];
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        
        if (empty($errors)) {
            //handle photo upload
            $photo_path = '';
            if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
                $photo_path = $this->handlePhotoUpload($files['photo'], '', $errors);
                
                if (isset($errors[0]) && $errors[0] === 'photo_error') {
                    return;
                }
            }
            
            if (empty($errors)) {
                $table = $this->getTableName($type);
                
                //prepare and execute SQL query based on member type
                if ($type === 'faculty') {
                    $query = "INSERT INTO $table (name, photo_path, academic_rank, institute, education, research_title, research_link, google_scholar_link) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('ssssssss', 
                        $name, 
                        $photo_path, 
                        $academic_rank, 
                        $institute, 
                        $education, 
                        $research_title, 
                        $research_link, 
                        $google_scholar_link
                    );
                } else {
                    $query = "INSERT INTO $table (name, photo_path, position, institute, education) 
                             VALUES (?, ?, ?, ?, ?)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('sssss', 
                        $name, 
                        $photo_path, 
                        $position, 
                        $institute, 
                        $education
                    );
                }
                
                if ($stmt->execute()) {
                    display_alert('New ' . $type . ' member added successfully!', 'success');
                    //redirect to manage members page to avoid resubmission
                    header('Location: ' . BASE_URL . '/admin/manage-members.php?type=' . $type);
                    exit();
                } else {
                    $errors[] = 'Error adding member: ' . $this->conn->error;
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
    
    //handle photo upload
    private function handlePhotoUpload($file, $existing_photo_path, &$errors) {
        $upload_dir = 'uploads/photos/';
        
        //create directory if it doesn't exist
        if (!is_dir('../' . $upload_dir)) {
            mkdir('../' . $upload_dir, 0755, true);
        }
        
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $file_name = uniqid() . '_' . time() . '.' . $file_ext;
        $upload_path = '../' . $upload_dir . $file_name;
        
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_ext, $allowed_exts)) {
            $errors[] = 'Only JPG, JPEG, PNG, and GIF files are allowed';
            $errors[] = 'photo_error';
            return $existing_photo_path;
        } elseif ($file['size'] > 2097152) { // 2MB
            $errors[] = 'File size must be less than 2MB';
            $errors[] = 'photo_error';
            return $existing_photo_path;
        } elseif (move_uploaded_file($file['tmp_name'], $upload_path)) {
            //delete the old photo if exists
            if (!empty($existing_photo_path) && file_exists('../' . $existing_photo_path)) {
                unlink('../' . $existing_photo_path);
            }
            return $upload_dir . $file_name;
        } else {
            $errors[] = 'Failed to upload file';
            $errors[] = 'photo_error';
            return $existing_photo_path;
        }
    }
    
   //get the table name based on member type
    private function getTableName($type) {
        return $type === 'faculty' ? 'faculty_lists' : 'staff_lists';
    }
}