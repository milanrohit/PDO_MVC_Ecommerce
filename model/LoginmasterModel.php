<?php 
   include_once("../config/connection.php");
   include_once("../lib/function.inc.php");

   class LoginmasterModel {
      private $conn;
      private $table_name = "adminmaster";
  
      public function __construct($db) {
          $this->conn = $db;
      }


      public function getAdminmasterdetails(string $Admin_Username, string $Admin_Password): array {
         try {
             // Prepare SQL query
             $query = "SELECT * FROM " . $this->table_name . " WHERE Admin_Username = :Admin_Username AND Admin_Password = :Admin_Password LIMIT 1";
             
             $stmt = $this->conn->prepare($query);
     
             // Bind parameters
             $stmt->bindParam(':Admin_Username', $Admin_Username, PDO::PARAM_STR);
             $stmt->bindParam(':Admin_Password', $Admin_Password, PDO::PARAM_STR);
             
             // Execute the statement
             $stmt->execute();
             $adminResult = $stmt->fetch(PDO::FETCH_ASSOC);
                
             // Verify the password if the user exists
             if (!empty($adminResult)) {
                 $_SESSION['Admin_Login'] = 'YES';
                 $_SESSION["Admin_Username"] = $Admin_Username;
                 $_SESSION["Admin_Password"] = $Admin_Password;
     
                 $finalarray = array_merge($_SESSION, $adminResult);
     
                 return $finalarray;
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
  $database = new Database();
  $db = $database->getConnection();
  
  ?>