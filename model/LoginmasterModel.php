<?php
   include_once("../config/connection.php");
   include_once("../lib/function.inc.php");

    class LoginMasterModel {
        private $conn;
        private $tableName = "adminmaster";
        
        public function __construct() {
            $this->conn = (new Database())->getConnection();
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
             if (!empty($adminResult)) {
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
  
  // Database obj
  $dataBase = new Database();
  $db = $dataBase->getConnection();
  ?>
