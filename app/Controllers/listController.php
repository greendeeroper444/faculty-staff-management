<?php

class ListController {
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
    

    //search lists by criteria
    public function searchLists($type, $name = '', $institute = '', $letter = '') {
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
        $lists = [];
        while ($row = $result->fetch_assoc()) {
            $lists[] = $row;
        }
        
        return $lists;
    }
    
    //all lists of a specific type
    // public function getLists($type) {
    //     $table = $this->getTableName($type);
    //     $query = "SELECT * FROM $table ORDER BY name ASC";
    //     $result = $this->conn->query($query);
        
    //     $lists = [];
    //     if ($result) {
    //         while ($row = $result->fetch_assoc()) {
    //             $lists[] = $row;
    //         }
    //     }
        
    //     return $lists;
    // }
    public function getLists($type) {
        $table = $this->getTableName($type);
        
        //use different ORDER BY column depending on the list type
        $orderByColumn = ($type === 'office') ? 'office_name' : 'name';
        
        $query = "SELECT * FROM $table ORDER BY $orderByColumn ASC";
        $result = $this->conn->query($query);
        
        $lists = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $lists[] = $row;
            }
        }
        
        return $lists;
    }

   //get detailed information about a specific list
    public function getListDetails($id, $type) {
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
    
    //handle all list operations (add, update, delete)
    public function handleListOperations($type) {
        //handle delete operation
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $this->deleteList($_GET['id'], $type);
        }
        
        //handle update operation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_list'])) {
            $this->updateList($_POST, $_FILES, $type);
        }
        
        //handle add operation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_list'])) {
            $this->addList($_POST, $_FILES, $type);
        }
    }
    
    //delete a list
    private function deleteList($id, $type) {
        $id = intval($id);
        $table = $this->getTableName($type);
        
        //check if we need to handle photo deletion based on list type
        if ($type === 'faculty' || $type === 'staff') {
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
        }
        
        //delete the list
        $delete_query = "DELETE FROM $table WHERE id = ?";
        $stmt = $this->conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            display_alert("List deleted successfully!", "success");
        } else {
            display_alert("Error deleting list: " . $this->conn->error, "danger");
        }
        
        //redirect to avoid resubmission
        header('Location: ' . BASE_URL . '/admin/faculty-staff-list.php?type=' . $type);
        exit();
    }
    //update a list
    private function updateList($postData, $files, $type) {
        //get list ID
        $list_id = intval($postData['list_id']);
        
        //common fields initialization based on list type
        if ($type === 'faculty' || $type === 'staff') {
            $name = trim($postData['name'] ?? '');
            $designation = trim($postData['designation'] ?? '');
            $email = trim($postData['email'] ?? '');
            $institute = trim($postData['institute'] ?? '');
            $education = isset($postData['education']) ? $postData['education'] : [];
            $education = json_encode(array_filter($education));
            $existing_photo_path = trim($postData['existing_photo_path'] ?? '');
            
            //type-specific fields for faculty and staff
            $academic_rank = trim($postData['academic_rank'] ?? '');
            $research_title = isset($postData['research_title']) ? $postData['research_title'] : [];
            $research_title = json_encode(array_filter($research_title));
            $research_link = isset($postData['research_link']) ? $postData['research_link'] : [];
            $research_link = json_encode(array_filter($research_link));
            $google_scholar_link = trim($postData['google_scholar_link'] ?? '');
        } elseif ($type === 'office') {
            $office_name = trim($postData['office_name'] ?? '');
            $about = trim($postData['about'] ?? '');
            $head = trim($postData['head'] ?? '');
            $contact_number = trim($postData['contact_number'] ?? '');
            $email = trim($postData['email'] ?? '');
        }
        
        //validate required fields
        $errors = [];
        if ($type === 'faculty' || $type === 'staff') {
            if (empty($name)) {
                $errors[] = 'Name is required';
            }
        } elseif ($type === 'office') {
            if (empty($office_name)) {
                $errors[] = 'Office name is required';
            }
        }
        
        if (empty($errors)) {
            // Only handle photo upload for faculty and staff, not for office
            if ($type === 'faculty' || $type === 'staff') {
                //handle photo upload - use existing photo if no new upload
                $photo_path = $existing_photo_path;
                
                if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
                    $photo_path = $this->handlePhotoUpload($files['photo'], $existing_photo_path, $errors);
                    
                    if (isset($errors[0]) && $errors[0] === 'photo_error') {
                        return;
                    }
                }
            }
            
            if (empty($errors)) {
                $table = $this->getTableName($type);
                
                //prepare update data based on type
                if ($type === 'faculty' || $type === 'staff') {
                    $query = "UPDATE $table SET 
                    name = ?, 
                    designation = ?, 
                    email = ?, 
                    photo_path = ?, 
                    academic_rank = ?, 
                    institute = ?, 
                    education = ?, 
                    research_title = ?, 
                    research_link = ?, 
                    google_scholar_link = ? 
                    WHERE id = ?";
                    
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('ssssssssssi', 
                        $name, 
                        $designation,
                        $email,
                        $photo_path, 
                        $academic_rank, 
                        $institute, 
                        $education, 
                        $research_title, 
                        $research_link, 
                        $google_scholar_link, 
                        $list_id
                    );
                } elseif ($type === 'office') {
                    $query = "UPDATE $table SET 
                    office_name = ?, 
                    about = ?, 
                    head = ?,
                    contact_number = ?, 
                    email = ?
                    WHERE id = ?";
                    
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('sssssi', 
                        $office_name, 
                        $about,
                        $head,
                        $contact_number, 
                        $email,
                        $list_id
                    );
                }
                
                if ($stmt->execute()) {
                    display_alert('List updated successfully!', 'success');
                    
                    //check if we're on the details page
                    $current_script = basename($_SERVER['PHP_SELF']);
                    
                    if ($current_script === 'faculty-staff-list-details.php') {
                        //if on details page, redirect back to details page
                        header('Location: ' . BASE_URL . '/admin/faculty-staff-list-details.php?id=' . $list_id . '&type=' . $type);
                    } else {
                        //otherwise, redirect to list page
                        header('Location: ' . BASE_URL . '/admin/faculty-staff-list.php?type=' . $type);
                    }
                    
                    exit();
                } else {
                    $errors[] = 'Error updating list: ' . $this->conn->error;
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
    
    //Add a new list
    private function addList($postData, $files, $type) {
        // common fields initialization
        if ($type === 'faculty' || $type === 'staff') {
            $name = trim($postData['name'] ?? '');
            $designation = trim($postData['designation'] ?? '');
            $email = trim($postData['email'] ?? '');
            $institute = trim($postData['institute'] ?? '');
            $education = isset($postData['education']) ? $postData['education'] : [];
            $education = json_encode(array_filter($education));
            
            //type-specific fields
            $academic_rank = trim($postData['academic_rank'] ?? '');
            $research_title = isset($postData['research_title']) ? $postData['research_title'] : [];
            $research_title = json_encode(array_filter($research_title));
            $research_link = isset($postData['research_link']) ? $postData['research_link'] : [];
            $research_link = json_encode(array_filter($research_link));
            $google_scholar_link = trim($postData['google_scholar_link'] ?? '');
        } elseif ($type === 'office') {
            $office_name = trim($postData['office_name'] ?? '');
            $about = trim($postData['about'] ?? '');
            $head = trim($postData['head'] ?? '');
            $contact_number = trim($postData['contact_number'] ?? '');
            $email = trim($postData['email'] ?? '');
        }
        
        //validate required fields
        $errors = [];
        if ($type === 'faculty' || $type === 'staff') {
            if (empty($name)) {
                $errors[] = 'Name is required';
            }
        } elseif ($type === 'office') {
            if (empty($office_name)) {
                $errors[] = 'Office name is required';
            }
        }
        
        if (empty($errors)) {
            //handle photo upload for faculty/staff
            $photo_path = '';
            if (($type === 'faculty' || $type === 'staff') && isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
                $photo_path = $this->handlePhotoUpload($files['photo'], '', $errors);
                
                if (isset($errors[0]) && $errors[0] === 'photo_error') {
                    return;
                }
            }
            
            if (empty($errors)) {
                $table = $this->getTableName($type);
                
                //prepare and execute SQL query based on list type
                if ($type === 'faculty' || $type === 'staff') {
                    $query = "INSERT INTO $table (name, designation, email, photo_path, academic_rank, institute, education, research_title, research_link, google_scholar_link) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('ssssssssss', 
                        $name,
                        $designation,
                        $email,
                        $photo_path, 
                        $academic_rank, 
                        $institute,
                        $education, 
                        $research_title, 
                        $research_link, 
                        $google_scholar_link
                    );
                } elseif ($type === 'office') {
                    $query = "INSERT INTO $table (office_name, about, head, contact_number, email) 
                    VALUES (?, ?, ?, ?, ?)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('sssss', 
                        $office_name, 
                        $about,
                        $head,
                        $contact_number, 
                        $email
                    );
                }
                
                if ($stmt->execute()) {
                    display_alert('New ' . $type . ' added successfully!', 'success');
                    //redirect to manage lists page to avoid resubmission
                    header('Location: ' . BASE_URL . '/admin/faculty-staff-list.php?type=' . $type);
                    exit();
                } else {
                    $errors[] = 'Error adding ' . $type . ': ' . $this->conn->error;
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
        if (!is_dir('../../../' . $upload_dir)) {
            mkdir('../../../' . $upload_dir, 0755, true);
        }
        
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $file_name = uniqid() . '_' . time() . '.' . $file_ext;
        $upload_path = '../../../' . $upload_dir . $file_name;
        
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
            if (!empty($existing_photo_path) && file_exists('../../../' . $existing_photo_path)) {
                unlink('../../../' . $existing_photo_path);
            }
            return $upload_dir . $file_name;
        } else {
            $errors[] = 'Failed to upload file';
            $errors[] = 'photo_error';
            return $existing_photo_path;
        }
    }
    
   //get the table name based on list type
    // private function getTableName($type) {
    //     return $type === 'faculty' ? 'faculty_lists' : 'staff_lists';
    // }
    private function getTableName($type) {
        switch ($type) {
            case 'faculty':
                return 'faculty_lists';
            case 'staff':
                return 'staff_lists';
            case 'office':
                return 'office_lists';
            default:
                throw new InvalidArgumentException("Unknown type: $type");
        }
    }
    
}