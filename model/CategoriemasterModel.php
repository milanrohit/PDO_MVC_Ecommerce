<?php

include_once("../config/connection.php");
include_once("../lib/function.inc.php");

class CategoryMasterModel {

    private $conn;
    private $table_name = "categoriesmaster";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get details of all categories.
     * 
     * @return array
     */
    public function getCategoryMasterDetails(): array {
        try {

            if(!empty($Categories_Id)){

                $query = "SELECT Categories_Id, Categories_Name, Categories_Status FROM ".$this->table_name." WHERE Categories_Id = :Categories_Id LIMIT 1";
                $stmt = $this->conn->prepare($query);

                // Execute the statement
                $stmt->execute();
                $categoryResult = $stmt->fetch(PDO::FETCH_ASSOC);
            }else{

                $query = "SELECT Categories_Id, Categories_Name, Categories_Status FROM ".$this->table_name." ORDER BY Categories_Id DESC";
                $stmt = $this->conn->prepare($query);

                // Execute the statement
                $stmt->execute();
                $categoryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Execute the statement
            $stmt->execute();
            $categoryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $categoryResult;
        } catch (PDOException $e) {
            echo "Failed to Select a category: " . $e->getMessage();
        }
    }

    /**
     * Update the status of a category.
     * 
     * @param int $Categories_Id
     * @param string $Categories_Status
     * @return bool
     */
    public function updateCategoriesMaster(int $Categories_Id, string $Categories_Status): bool {

        try {
           
            $Categories_Name ='';
            $Categories_Name = sanitizeString(((string)$_POST['Categories_Name']));
            $Categories_Status = sanitizeString(((string)$Categories_Status));
            $Categories_Id = sanitizeString(((int)$Categories_Id));

            if(!empty($Categories_Id) && !empty($Categories_Status)){
                // Prepare SQL query
                $query = "UPDATE $this->table_name SET Categories_Status = :Categories_Status WHERE Categories_Id = :Categories_Id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':Categories_Status', $Categories_Status, PDO::PARAM_STR);
            }else{
                $query = "UPDATE $this->table_name SET Categories_Name = :Categories_Name WHERE Categories_Id = :Categories_Id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':Categories_Name', $Categories_Name, PDO::PARAM_STR);
            }
           
            // Bind parameters
            $stmt->bindParam(':Categories_Id', $Categories_Id, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                return true;
            } else {
                printf("Error: %s.\n", $stmt->errorInfo()[2]);
                return false;
            }
        } catch (PDOException $e) {
            echo "Failed to Update a category: " . $e->getMessage();
        }
    }

    /**
     * Delete a category.
     * 
     * @param int $Categories_Id
     */
    public function deleteCategoriesMaster(int $Categories_Id) {
        try {
            $categorieId ='';
            $categorieId = sanitizeString(((int)$_GET['categorieId']));
            
            if(!empty($categorieId)){
                $query = "DELETE FROM $this->table_name WHERE Categories_Id = :Categories_Id";
                $stmt = $this->conn->prepare($query);

                // Bind parameter
                $stmt->bindParam(':Categories_Id', $categorieId, PDO::PARAM_INT);
                // Execute the statement
                if ($stmt->execute()) {
                    return true; 
                }
            }else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Failed to delete category: " . $e->getMessage();
        }
    }

    public function addCategory(string $Categories_Name) {
        try{
            // Prepare an SQL statement with a placeholder for the category name
            $query = "INSERT INTO $this->table_name (Categories_Name) VALUES (:Categories_Name) LIMIT 1";
            $stmt = $this->conn->prepare($query);
            
            // Sanitize inputs
            $Categories_Name = sanitizeString((string) $Categories_Name);

            // Bind the category name to the placeholder
            $stmt->bindParam(':Categories_Name', $Categories_Name, PDO::PARAM_STR);
        
            // Execute the statement
            if ($stmt->execute()) {
                return true; 
            } else {
                return false;
            }
        }catch (PDOException $e) {
            echo "Failed to Add category: " . $e->getMessage();
        }
    }

    public function getdataCategorie(int $Categories_Id) {
        try{
            $query = "SELECT Categories_Id, Categories_Name, Categories_Status FROM ".$this->table_name." WHERE Categories_Id = :Categories_Id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            // Bind parameter
            $stmt->bindParam(':Categories_Id', $Categories_Id, PDO::PARAM_INT);
            // Execute the statement
            $stmt->execute();
            $categoryResult = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // Execute the statement
            if ($stmt->execute()) {
                return $categoryResult; 
            } else {
                return false;
            }
        }catch (PDOException $e) {
            echo "Failed to fetch category: " . $e->getMessage();
        }
    }

    public function checkDuplicatercd($Categories_Name){

        $Categories_Name ='';
        $Categories_Name = sanitizeString($_POST['Categories_Name']) ?? null;
        
        if(!empty($Categories_Name)){
            // Adding a duplicate check
            $duplicateQuery = "SELECT COUNT(*) as cnt FROM " . $this->table_name . " WHERE Categories_Name = :Categories_Name";
            $stmtDuplicate = $this->conn->prepare($duplicateQuery);
            $stmtDuplicate->bindParam(':Categories_Name', $Categories_Name, PDO::PARAM_STR);
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