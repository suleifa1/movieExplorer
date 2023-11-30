
<?php 
include_once './tech/config.php';
class ReportModel {
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
    public function getReports() {
        $sql = "SELECT reports.*, comments.movies_id 
                FROM reports 
                JOIN comments ON reports.comments_id = comments.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
 
}