<?php
// Model/CommentModel.php
include_once './tech/config.php';
class CommentModel {
    protected $db;

    public function __construct() {
        $this->db = $this->dbConnect();
    }
    private function dbConnect() {
        global $dbConfig; 
        $conn = new mysqli($dbConfig['servername'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
        
        if ($conn->connect_error) {
            error_log("Connection error: " . $conn->connect_error);
            return null;
        }
        
        return $conn;
    }
    public function getCommentsForMovie($movieId) {
        $sql = "SELECT comments.*, users.username, users.image_path, roles.name AS role_name 
                FROM comments
                INNER JOIN users ON comments.user_id = users.id
                INNER JOIN roles ON users.id_role = roles.id
                WHERE comments.movies_id = ?
                ORDER BY comments.timestamp ASC";

        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $movieId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getCommentUserId($commentId) {
        $sql = "SELECT user_id FROM comments WHERE id = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            throw new Exception("Ошибка подготовки запроса: " . $this->db->error);
        }
    
        $stmt->bind_param("i", $commentId);
    
        if (!$stmt->execute()) {
            throw new Exception("Ошибка выполнения запроса: " . $stmt->error);
        }
    
        $result = $stmt->get_result();
    
        if ($result === false) {
            throw new Exception("Ошибка получения результата: " . $this->db->error);
        }
        $row = $result->fetch_assoc();

        if ($row) {
            return $row['user_id'];
        } else {
            return null;
        }
    }
    public function addComment($movieId, $commentText, $userId) {
        $sql = "INSERT INTO comments (text, user_id, movies_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sii", $commentText, $userId, $movieId);
        
        return $stmt->execute();
    }
    public function deleteComment($commentId) {
        $sql = "DELETE FROM comments WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $commentId);
        
        return $stmt->execute();
    }
    public function reportComment($commentId, $typeId) {
        $sqlGetUserId = "SELECT user_id, report_count FROM comments WHERE id = ?";
        $stmtGetUserId = $this->db->prepare($sqlGetUserId);
        $stmtGetUserId->bind_param("i", $commentId);
        $stmtGetUserId->execute();
        $result = $stmtGetUserId->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            return false;
        }

        $userId = $row['user_id'];
        $currentCount = $row['report_count'];

        $sqlUpdateComment = "UPDATE comments SET report_count = report_count + 1 WHERE id = ?";
        $stmtUpdateComment = $this->db->prepare($sqlUpdateComment);
        $stmtUpdateComment->bind_param("i", $commentId);

        if (!$stmtUpdateComment->execute()) {
             return false;
        }

        if ($currentCount >= 3) { 
            $sqlInsertReport = "INSERT INTO reports (comments_id, type_id, user_id) VALUES (?, ?, ?)";
            $stmtInsertReport = $this->db->prepare($sqlInsertReport);
            $stmtInsertReport->bind_param("iii", $commentId, $typeId, $userId);

            if (!$stmtInsertReport->execute()) {
                 return ['success' => 'false',
                'error' => 'Instert went wrong. Try again later or contant support center.'];;
            }
            $data = ['type' => 'new_report', 'message' => 'New report added'];
            
        }

         return true;
    }

}
