<?php

class AuthController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function adminLogin($username, $password) {
        $sql = "SELECT admin_id, username, password FROM admins WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            //verify the hashed password
            if (password_verify($password, $admin['password'])) {
                return [
                    'success' => true,
                    'admin_id' => $admin['admin_id'],
                    'username' => $admin['username']
                ];
            }
        }
        
        return [
            'success' => false
        ];
    }

    //utility method to hash a password
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    //check if a user is currently logged in
    public function isLoggedIn() {
        return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
    }

    //log out the current user
    public function logout() {
        if (isset($_SESSION['admin_id'])) {
            unset($_SESSION['admin_id']);
            unset($_SESSION['username']);
            session_destroy();
            return true;
        }
        return false;
    }
}