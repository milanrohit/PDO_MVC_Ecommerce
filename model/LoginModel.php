<?php

include_once("../config/connection.php");
include_once("../lib/function.inc.php");

class LoginModel {

    private $conn;
    private $table_name = "adminmaster";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAdminmasterdetails(){
      
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            
           if (!empty($_POST['Admin_Username']) && !empty($_POST['Admin_Password'])) {

              // Validate and sanitize input
              $Admin_Username = sanitizeString($_POST['Admin_Username']);
              $Admin_Password = sanitizeString($_POST['Admin_Password']);

              // Prepare SQL query
              $query = "SELECT * FROM " . $this->table_name . " WHERE Admin_Username = :Admin_Username AND Admin_Password = :Admin_Password LIMIT 1";
              
              $stmt = $this->conn->prepare($query);

              // Bind parameters
              $stmt->bindParam(':Admin_Username', $Admin_Username);
              $stmt->bindParam(':Admin_Password', $Admin_Password);
              
              // Execute the statement
              $stmt->execute();
              $adminResult = $stmt->fetch(PDO::FETCH_ASSOC);
                 
              // Verify the password if the user exists
              if (!empty($adminResult)) {
              
                 $_SESSION['Admin_Login'] = 'YES';
                 $_SESSION["Admin_Username"];
                 $_SESSION["Admin_Password"];
                 
                 redirect("categoriemaster.php");
                 
              }else {
                 $errormsg ="Invalid username and password";
              } 
           }
        }
     }

}

// Database object
$database = new Database();
$db = $database->getConnection();

?>