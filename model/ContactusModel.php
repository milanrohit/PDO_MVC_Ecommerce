<?php

include_once("../config/connection.php");
include_once("../lib/function.inc.php");

class ContactusModel {

    private $conn;
    private $table_name = "contactus";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get details of all Contactus.
     * 
     * @return array
     */
    public function getContactusDetails(int $contactus_id=null): array {
        try {
            if ($contactus_id !== null) {
                $query = "SELECT contactus_id, contactus_name, contactus_email, contactus_mobile, contactus_status, contactus_comment, contactus_add_datetime, contactus_update_datetime FROM $this->table_name WHERE contactus_id = :contactus_id LIMIT 1";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':contactus_id', $contactus_id, PDO::PARAM_INT);
                $stmt->execute();
                $ContactusDetails = $stmt->fetch(PDO::FETCH_ASSOC);

                return $ContactusDetails !== false ? $ContactusDetails : [];
            } else {
                $query = "SELECT contactus_id, contactus_name, contactus_email, contactus_mobile, contactus_status, contactus_comment FROM $this->table_name ORDER BY contactus_id DESC";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $ContactusDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $ContactusDetails;
            }
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            echo "Failed to select a contact: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Update the status of a Contactus.
     * 
     * @param int $contactus_id
     * @param string $contactus_status
     * @return bool
     */
    
    public function updateContactusDetails(int $contactus_id , string $contactus_status): bool {

        try {

            $contactus_name = ($_POST['contactus_name']) ? sanitizeString((string)$_POST['contactus_name']) : ""; 
            $contactus_status = sanitizeString(((string)$contactus_status));
            $contactus_id = ($_GET['contactus_id']) ? sanitizeString((int)$_GET['contactus_id']) : 0; 
            
            if(!empty($contactus_id) && !empty($contactus_status)){
                // Prepare SQL query
                $query = "UPDATE $this->table_name SET contactus_status = :contactus_status WHERE contactus_id = :contactus_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':contactus_status', $contactus_status, PDO::PARAM_STR);
            }else{
                $query = "UPDATE $this->table_name SET contactus_name = :contactus_name WHERE contactus_id = :contactus_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':contactus_name', $contactus_name, PDO::PARAM_STR);
            }
           
            // Bind parameters
            $stmt->bindParam(':contactus_id', $contactus_id, PDO::PARAM_INT);


            // Execute the statement
            if ($stmt->execute()) {
                return true;
            } else {
                printf("Error: %s.\n", $stmt->errorInfo()[2]);
                return false;
            }
        } catch (PDOException $e) {
            echo "Failed to Update a contactus name & status: " . $e->getMessage();
        }
    }

    /**
     * Delete a Contactus.
     * 
     * @param int $contactus_id
     */
    public function deleteContactus(int $contactus_id) {
        try {
            $contactus_id ='';
            $contactus_id = sanitizeString(((int)$_GET['contactus_id']));
            
            if(!empty($contactus_id)){
                $query = "DELETE FROM $this->table_name WHERE contactus_id = :contactus_id";
                $stmt = $this->conn->prepare($query);

                // Bind parameter
                $stmt->bindParam(':contactus_id', $contactus_id, PDO::PARAM_INT);
                // Execute the statement
                if ($stmt->execute()) {
                    return true; 
                }
            }else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Failed to delete contactus user: " . $e->getMessage();
        }
    }

    public function addContactus(array $data): bool {
        try{

            if(isset($_POST['contactus_name']) && $_POST['contactus_name'] !=''){
                $contactus_name = ($_POST['contactus_name']) ? sanitizeString((string)$_POST['contactus_name']) : "" ;
            }

            if(isset($_POST['contactus_email']) && $_POST['contactus_email'] !=''){
                $contactus_email = ($_POST['contactus_email']) ? sanitizeString((string)$_POST['contactus_email']) : "" ;
            }

            if(isset($_POST['contactus_mobile']) && $_POST['contactus_mobile'] !=''){
                $contactus_mobile = ($_POST['contactus_mobile']) ? sanitizeString((string)$_POST['contactus_mobile']) : "" ;
            }

            if(isset($_POST['contactus_comment']) && $_POST['contactus_comment'] !=''){
                $contactus_comment = ($_POST['contactus_comment']) ? sanitizeString((string)$_POST['contactus_comment']) : "" ;
            }

            // Prepare an SQL statement with a placeholder for the Contactus name
            $query = "INSERT INTO $this->table_name (contactus_name,contactus_email,contactus_mobile,contactus_comment) VALUES (:contactus_name,:contactus_email,:contactus_mobile,:contactus_comment) LIMIT 1";
            $stmt = $this->conn->prepare($query);
            

            // Bind the Contactus name to the placeholder
            $stmt->bindParam(':contactus_name', $contactus_name, PDO::PARAM_STR);
            $stmt->bindParam(':contactus_email', $contactus_email, PDO::PARAM_STR);
            $stmt->bindParam(':contactus_mobile', $contactus_mobile, PDO::PARAM_INT);
            $stmt->bindParam(':contactus_comment', $contactus_comment, PDO::PARAM_STR);
        
            // Execute the statement
            if ($stmt->execute()) {
                return true; 
            } else {
                return false;
            }
        }catch (PDOException $e) {
            echo "Failed to Add Contactus: " . $e->getMessage();
        }
    }

    public function getdataContactus(int $contactus_id) {
        try{
            $query = "SELECT contactus_id, contactus_name, contactus_email, contactus_mobile, contactus_status, contactus_comment, contactus_add_datetime, contactus_update_datetime FROM ".$this->table_name." WHERE contactus_id = :contactus_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            // Bind parameter
            $stmt->bindParam(':contactus_id', $contactus_id, PDO::PARAM_INT);
            // Execute the statement
            $stmt->execute();
            $ContactusResult = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // Execute the statement
            if ($stmt->execute()) {
                return $ContactusResult; 
            } else {
                return false;
            }
        }catch (PDOException $e) {
            echo "Failed to fetch Contactus: " . $e->getMessage();
        }
    }

    public function checkDuplicatercd($contactus_name){

        $contactus_name = ($_POST['contactus_name']) ? sanitizeString((string)$_POST['contactus_name']) : ""; 

        if(!empty($contactus_name)){
            // Adding a duplicate check
            $duplicateQuery = "SELECT COUNT(*) as cnt FROM " . $this->table_name . " WHERE contactus_name = :contactus_name";
            $stmtDuplicate = $this->conn->prepare($duplicateQuery);
            $stmtDuplicate->bindParam(':contactus_name', $contactus_name, PDO::PARAM_STR);
            $stmtDuplicate->execute();
            $duplicateCheck = $stmtDuplicate->fetch(PDO::FETCH_ASSOC);
            
            if (!empty($duplicateCheck['cnt']) > 0 ) {
                return 1 ;
            }else{
                return 0;
            }
            
        }else{
            return false;
        }
    }
}

// Database object
$database = new Database();
$db = $database->getConnection();

?>