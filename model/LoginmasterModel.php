<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");

class LoginMasterModel {
    private $conn;
    private string $tableName = "adminmaster";

    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAdminmasterdetails(string $adminUsername, string $adminPassword): array {
        try {
            // Prepare SQL query
            $query = "SELECT * FROM " . $this->tableName . " WHERE Admin_Username = :adminUsername AND Admin_Password = :adminPassword LIMIT 1";
            
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':adminUsername', $adminUsername, PDO::PARAM_STR);
            $stmt->bindParam(':adminPassword', $adminPassword, PDO::PARAM_STR);
            
            // Execute the statement
            $stmt->execute();
            $adminResult = $stmt->fetch(PDO::FETCH_ASSOC);
               
            // Verify the password if the user exists
            if ($adminResult) {
                $_SESSION['Admin_Login'] = 'YES';
                $_SESSION["adminUsername"] = $adminUsername;
                $_SESSION["adminPassword"] = $adminPassword;

                return array_merge($_SESSION, $adminResult);
            } else {
                // Return an empty array if no user is found
                return [];
            }
        } catch (PDOException $e) {
            echo "Failed to Login: " . $e->getMessage();
            return [];
        }
    }
}

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

$LoginMasterModel = new LoginMasterModel();
?>
