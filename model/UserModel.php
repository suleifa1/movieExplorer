<?php
// Model/UserModel.php
include_once './tech/config.php';

class UserModel {
    protected $db;
    
    public function __construct() {
        $this->db = $this->dbConnect();
    }
    private function dbConnect() {
        global $dbConfig; // It's better to pass this as a parameter
        $conn = new mysqli($dbConfig['servername'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
        
        if ($conn->connect_error) {
            error_log("Connection error: " . $conn->connect_error);
            return null;
        }
        
        return $conn;
    }
    public function userExists($username, $email) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = ? OR email = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new Exception($this->db->error);
        }
    
        $stmt->bind_param('ss', $username, $email);
        if (!$stmt->execute()) {
            throw new Exception( $stmt->error);
        }
    
        $result = $stmt->get_result(); 
        $row = $result->fetch_assoc();
        return $row['count'] > 0; 
    }
    public function registerUser($username, $email, $password) {

    
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            throw new Exception("Ошибка подготовки запроса: " . $this->db->error);
        }
    
        $stmt->bind_param('sss', $username, $email, $hashed_password);
        if (!$stmt->execute()) {
            throw new Exception("Ошибка выполнения запроса: " . $stmt->error);
        }
    
        return true; 
    }
    public function updatePassword($userId, $newPassword) {
        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?"; 
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            return false;
        }
    
        $stmt->bind_param("si", $hashed_password, $userId);
    
        return $stmt->execute();
    }
    public function loginUser($email, $password) {
        
        
        $sql = "SELECT users.*, roles.name AS role_name FROM users 
                INNER JOIN roles ON users.id_role = roles.id
                WHERE email = ?";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $user = $result->fetch_assoc();
        
        if ($user && password_verify($password, $user["password"])) { 
            if(session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION["logged_in"] = true; 
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role_name"];  
            return true;
        }
        
        $stmt->close();
        
        return false;
    }
    public function logout() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
    
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    
        return true;
    }
    public function determineUserRole() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        
        if(isset($_SESSION["user_id"])) {
            $userId = $_SESSION["user_id"];

            $sql = "SELECT roles.name AS role_name FROM users 
                INNER JOIN roles ON users.id_role = roles.id
                WHERE users.id = ?";
    
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $userRole = $result->fetch_assoc();
            $stmt->close();
    
            if ($userRole) {
                return $userRole["role_name"];
            }
        }
    
        return 'guest'; 
    }
    public function updateImage($userId, $imagePath) {
        $sql = "UPDATE users SET image_path = ? WHERE id = ?";

        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('si', $imagePath, $userId);

            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false; 
            }
        } else {
            return false; 
        }
    }
    public function getUserInfo($userId) {
        $sql = "SELECT users.id, users.username, users.email, users.image_path, roles.name AS role 
                FROM users 
                INNER JOIN roles ON users.id_role = roles.id 
                WHERE users.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userInfo = $result->fetch_assoc();
        $stmt->close();

        if (!$userInfo) {
            return null;
        }

        return $userInfo;
    }
    public function getAllUsersWithRoles() {
        $sql = "SELECT users.id, users.username, users.id_role, users.email, roles.name AS role
                FROM users
                INNER JOIN roles ON users.id_role = roles.id
                WHERE users.id <> 0 AND users.id_role <> 0
                ORDER BY FIELD(role, 'superadmin', 'admin', 'moderator', 'user'), users.username ASC";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        return $users;
    }
    public function getUserRole($userId) {
        $sql = "SELECT roles.name AS role 
                FROM users 
                INNER JOIN roles ON users.id_role = roles.id 
                WHERE users.id = ?";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userRole = $result->fetch_assoc();
        $stmt->close();
    
        if (!$userRole) {
            return null;
        }
    
        return $userRole['role'];
    }
    public function updateUserRole($userId, $newRoleId) {
        $sql = "UPDATE users SET id_role = ? WHERE id = ?";
    
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Unable to prepare statement: " . $this->db->error);
        }
    
        $stmt->bind_param('ii', $newRoleId, $userId);
        $result = $stmt->execute();
    
        $stmt->close();
    
        return $result; 
    }
}
