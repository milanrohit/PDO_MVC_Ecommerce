<?php

include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");

class CategoryMasterModel {

    private $conn;
    public $tableName = "categoriesmaster";

    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getCategoryMasterDetails(?int $catId = null): array {
        try {
            if (is_numeric($catId)) {
                $catId = intval($catId);
            } else {
                $catId = null;
            }

            $query = "SELECT
                            Categories_Id,
                            Categories_Name,
                            Categories_Status
                        FROM  {$this->tableName} ";

            if (!is_null($catId)) {
                $query .= " WHERE Categories_Id = :CatId";
            } else {
                $query .= " ORDER BY Categories_Id DESC";
            }

            $stmt = $this->conn->prepare($query);

            if (!is_null($catId)) {
                $stmt->bindParam(':CatId', $catId, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Failed to select a category: " . $e->getMessage();
            return [];
        }
    }

    public function updateCategoriesMaster(int $catId, string $catStatus): bool {

        try {
            $catName = sanitizeString(((string)$_POST['Categories_Name']));
            $catStatus = sanitizeString(((string)$catStatus));
            $catId = sanitizeString(((int)$catId));

            if(!empty($catId) && !empty($catStatus)){
                // Prepare SQL query
                $query = "UPDATE $this->tableName SET Categories_Status = :catStatus WHERE Categories_Id = :catId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':catStatus', $catStatus, PDO::PARAM_STR);
            }else{
                $query = "UPDATE $this->tableName SET Categories_Name = :catName WHERE Categories_Id = :catId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':catName', $catName, PDO::PARAM_STR);
            }
           
            // Bind parameters
            $stmt->bindParam(':catId', $catId, PDO::PARAM_INT);

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

    public function deleteCategoriesMaster(int $categoriesId) {
        try {
            $categorieId = sanitizeString(((int)$_GET['categorieId']));
            
            if(!empty($categorieId)){
                $query = "DELETE FROM $this->tableName WHERE Categories_Id = :categoriesId";
                $stmt = $this->conn->prepare($query);

                // Bind parameter
                $stmt->bindParam(':categoriesId', $categoriesId, PDO::PARAM_INT);
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

    public function addCategory(string $categoriesName): bool {
        try {
            // Ensure the table name is properly sanitized and escaped (preferably predefined)
            $query = "INSERT INTO {$this->tableName} (Categories_Name) VALUES (:categoriesName) LIMIT 1";
            $stmt = $this->conn->prepare($query);
    
            // Sanitize and validate the category name
            $categoriesName = sanitizeString($categoriesName);
    
            // Bind the category name to the placeholder
            $stmt->bindParam(':categoriesName', $categoriesName, PDO::PARAM_STR);
    
            // Execute the statement
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Use proper logging for error messages
            error_log("Failed to add category: " . $e->getMessage());
            return false;
        }
    }

    public function getdataCategorie(int $catId) {
        try{
            $query = "SELECT Categories_Id, Categories_Name, Categories_Status FROM $this->tableName WHERE Categories_Id = :Categories_Id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            // Bind parameter
            $stmt->bindParam(':Categories_Id', $catId, PDO::PARAM_INT);
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

    public function checkDuplicateRecord(string $categoriesName): bool {
        // Sanitize input
        $categoriesName = sanitizeString($categoriesName);
    
        if (!empty($categoriesName)) {
            try {
                // Adding a duplicate check
                $duplicateQuery = "SELECT COUNT(*) as cnt FROM {$this->tableName} WHERE Categories_Name = :categoriesName";
                $stmtDuplicate = $this->conn->prepare($duplicateQuery);
                $stmtDuplicate->bindParam(':categoriesName', $categoriesName, PDO::PARAM_STR);
                $stmtDuplicate->execute();
                $duplicateCheck = $stmtDuplicate->fetch(PDO::FETCH_ASSOC);
    
                return !empty($duplicateCheck['cnt']) > 0;
            } catch (PDOException $e) {
                // Use proper logging for error messages
                error_log("Failed to check duplicate record: " . $e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    // Function to generate the dropdown
    public function getCatFromMaster($pCategorieId = null) {

        // Load the category master details based on the selected category or all if not selected
        $catMasterDetails = !empty($pCategorieId) ? $this->getCategoryMasterDetails($pCategorieId) : $this->getCategoryMasterDetails();
        
        $options = '<option value="" selected disabled>Select a category from cat master</option>';
        
        // Check if the category master details are not empty and are an array
        if (!empty($catMasterDetails) && is_array($catMasterDetails)) {
            // Loop through each category master detail
            foreach ($catMasterDetails as $val) {
                // Get the category ID and Name
                $categoryId = $val['Categories_Id'];
                $categoryName = $val['Categories_Name'];
                
                // Check if the category ID matches the selected category ID
                $selected = (!empty($pCategorieId) && $pCategorieId == $categoryId) ? 'selected' : '';
                $options .= '<option value="' . $categoryId . '" ' . $selected . '>' . $categoryName . '</option>';
            }
        }
        return $options;
    }
}

// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$CategoryMasterModel = new CategoryMasterModel($db);
?>

