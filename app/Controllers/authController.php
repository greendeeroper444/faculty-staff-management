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
            
            //compare plaintext passwords directly (as per original code)
            if ($password === $admin['password']) {
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

    //check if a user is currently logged in
    public function isLoggedIn() {
        //implementation depends on your session management
        return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
    }

   ///og out the current user
    public function logout() {
        //implementation depends on your session management
        if (isset($_SESSION['admin_id'])) {
            unset($_SESSION['admin_id']);
            unset($_SESSION['username']);
            session_destroy();
            return true;
        }
        return false;
    }
}