<?php

class ListController {
    private $conn;
    private $valid_types = ['faculty', 'staff'];

    public function __construct($conn) {
        $this->conn = $conn;
    }

    //get all members of a specific type (faculty or staff)
    public function getAllMembers($type) {
        $type = $this->validateType($type);
        $table = $this->getTableName($type);
        
        $query = "SELECT * FROM $table ORDER BY name ASC";
        $result = $this->conn->query($query);
        
        return $result;
    }
    
    //get a single member byid
    public function getMemberById($id, $type) {
        $type = $this->validateType($type);
        $table = $this->getTableName($type);
        
        $query = "SELECT * FROM $table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }

    public function addMember($data, $type) {
        $type = $this->validateType($type);
        $table = $this->getTableName($type);
        
        //convert research_title array to JSON if it exists
        if ($type === 'faculty' && isset($data['research_title']) && is_array($data['research_title'])) {
            $data['research_title'] = json_encode(array_filter($data['research_title']));
        }
        
        if ($type === 'faculty') {
            $query = "INSERT INTO $table (name, photo_path, academic_rank, institute, education, research_title, research_link, google_scholar_link) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param(
                "ssssssss", 
                $data['name'], 
                $data['photo_path'], 
                $data['academic_rank'], 
                $data['institute'], 
                $data['education'], 
                $data['research_title'], 
                $data['research_link'], 
                $data['google_scholar_link']
            );
        } else {
                $query = "INSERT INTO $table (name, photo_path, position, institute, education) 
                VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param(
                "sssss", 
                $data['name'], 
                $data['photo_path'], 
                $data['position'], 
                $data['institute'], 
                $data['education']
            );
        }
        
        return $stmt->execute();
    }
    
    //update an existing member
    public function updateMember($id, $data, $type) {
        $type = $this->validateType($type);
        $table = $this->getTableName($type);
        
        //convert research_title array to JSON if it exists
        if ($type === 'faculty' && isset($data['research_title']) && is_array($data['research_title'])) {
            $data['research_title'] = json_encode(array_filter($data['research_title']));
        }
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
            $stmt->bind_param(
                "ssssssssi", 
                $data['name'], 
                $data['photo_path'], 
                $data['academic_rank'], 
                $data['institute'], 
                $data['education'], 
                $data['research_title'], 
                $data['research_link'], 
                $data['google_scholar_link'],
                $id
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
            $stmt->bind_param(
                "sssssi", 
                $data['name'], 
                $data['photo_path'], 
                $data['position'], 
                $data['institute'], 
                $data['education'],
                $id
            );
        }
        
        return $stmt->execute();
    }
    
    
    //add new member
    // public function addMember($data, $type) {
    //     $type = $this->validateType($type);
    //     $table = $this->getTableName($type);
        
    //     //convert research_title array to JSON if it exists
    //     if ($type === 'faculty' && isset($data['research_title']) && is_array($data['research_title'])) {
    //         $data['research_title'] = json_encode(array_filter($data['research_title']));
    //     }
        
    //     //since both tables have the same structure, we can use the same query
    //     $query = "INSERT INTO $table (name, photo_path, academic_rank, institute, education, research_title, research_link, google_scholar_link) 
    //     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bind_param(
    //         "ssssssss", 
    //         $data['name'], 
    //         $data['photo_path'], 
    //         $data['academic_rank'], 
    //         $data['institute'], 
    //         $data['education'], 
    //         $data['research_title'], 
    //         $data['research_link'], 
    //         $data['google_scholar_link']
    //     );
        
    //     return $stmt->execute();
    // }
    
    // public function updateMember($id, $data, $type) {
    //     $type = $this->validateType($type);
    //     $table = $this->getTableName($type);
        
    //     //convert research_title array to JSON if it exists
    //     if ($type === 'faculty' && isset($data['research_title']) && is_array($data['research_title'])) {
    //         $data['research_title'] = json_encode(array_filter($data['research_title']));
    //     }
        
    //     //since both tables have the same structure, we can use the same query
    //     $query = "UPDATE $table SET 
    //     name = ?, 
    //     photo_path = ?, 
    //     academic_rank = ?, 
    //     institute = ?, 
    //     education = ?, 
    //     research_title = ?, 
    //     research_link = ?, 
    //     google_scholar_link = ? 
    //     WHERE id = ?";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bind_param(
    //         "ssssssssi", 
    //         $data['name'], 
    //         $data['photo_path'], 
    //         $data['academic_rank'], 
    //         $data['institute'], 
    //         $data['education'], 
    //         $data['research_title'], 
    //         $data['research_link'], 
    //         $data['google_scholar_link'],
    //         $id
    //     );
        
    //     return $stmt->execute();
    // }
    
    //delete member
    public function deleteMember($id, $type) {
        $type = $this->validateType($type);
        $table = $this->getTableName($type);
        
        //get the photo path before deleting
        $photoPath = $this->getMemberPhotoPath($id, $type);
        
        //delete the member
        $query = "DELETE FROM $table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        
        //return both the result and the photo path
        return [
            'success' => $result,
            'photo_path' => $photoPath
        ];
    }
    
    //get member's photo path
    public function getMemberPhotoPath($id, $type) {
        $type = $this->validateType($type);
        $table = $this->getTableName($type);
        
        $query = "SELECT photo_path FROM $table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['photo_path'];
        }
        
        return '';
    }
    
    //validation type
    private function validateType($type) {
        return in_array($type, $this->valid_types) ? $type : 'faculty';
    }
    
    //get table name
    private function getTableName($type) {
        return $type === 'faculty' ? 'faculty_lists' : 'staff_lists';
    }

    //get member details by id
    public function getMemberDetails($id, $type) {
        $type = $this->validateType($type);
        $table = $this->getTableName($type);
        
        $query = "SELECT * FROM $table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
}