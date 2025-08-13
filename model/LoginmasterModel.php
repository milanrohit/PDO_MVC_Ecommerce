<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");

class LoginMasterModel {
    private $conn;
    private string $tableName = "adminmaster";

    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAdminmasterdetails(string $adminUsername): array|false{
    
        // Use prepared statements to prevent SQL injection
        try {
            $query = "SELECT * FROM {$this->tableName} WHERE Admin_Username = :adminUsername LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':adminUsername', $adminUsername, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            // Log error instead of echoing it
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
}

// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$LoginMasterModel = new LoginMasterModel($db);
