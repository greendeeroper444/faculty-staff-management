<?php

class FileUtils {
    public static function uploadPhoto($file, $existingPath = '', $baseDir = '../') {
        $result = [
            'path' => $existingPath,
            'errors' => []
        ];
        
        $upload_dir = 'uploads/photos/';
        
        //create directory if it doesn't exist
        if (!is_dir($baseDir . $upload_dir)) {
            mkdir($baseDir . $upload_dir, 0755, true);
        }
        
        //skip if no file or error occurred
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return $result;
        }
        
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $file_name = uniqid() . '_' . time() . '.' . $file_ext;
        $upload_path = $baseDir . $upload_dir . $file_name;
        
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_ext, $allowed_exts)) {
            $result['errors'][] = 'Only JPG, JPEG, PNG, and GIF files are allowed';
            return $result;
        }
        
        if ($file['size'] > 2097152) { // 2MB
            $result['errors'][] = 'File size must be less than 2MB';
            return $result;
        }
        
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            //delete the old photo if exists
            if (!empty($existingPath) && file_exists($baseDir . $existingPath)) {
                unlink($baseDir . $existingPath);
            }
            $result['path'] = $upload_dir . $file_name;
        } else {
            $result['errors'][] = 'Failed to upload file';
        }
        
        return $result;
    }
}