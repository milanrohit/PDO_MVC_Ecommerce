<?php

include_once("../config/connection.php");
include_once("../lib/function.inc.php");

class CategoryMasterModel {

    private $conn;
    private $tableName = "categoriesmaster";

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

    /**
     * Update the status of a category.
     * 
     * @param int $Categories_Id
     * @param string $Categories_Status
     * @return bool
     */
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
                $query = "DELETE FROM $this->tableName WHERE Categories_Id = :Categories_Id";
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
            $query = "INSERT INTO $this->tableName (Categories_Name) VALUES (:Categories_Name) LIMIT 1";
            $stmt = $this->conn->prepare($query);
            
            // Sanitize inputs
            $Categories_Name = sanitizeString((string) $Categories_Name);

            // Bind the category name to the placeholder
            $stmt->bindParam(':Categories_Name', $Categories_Name, PDO::PARAM_STR);
        
            // Execute the statement
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }catch (PDOException $e) {
            echo "Failed to Add category: " . $e->getMessage();
        }
    }

    public function getdataCategorie(int $catId) {
        try{
            $query = "SELECT Categories_Id, Categories_Name, Categories_Status FROM ".$this->tableName." WHERE Categories_Id = :Categories_Id LIMIT 1";
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

    public function checkDuplicatercd($catName){

        $catName ='';
        $catName = sanitizeString($_POST['Categories_Name']) ?? null;
        
        if(!empty($catName)){
            // Adding a duplicate check
            $duplicateQuery = "SELECT COUNT(*) as cnt FROM " . $this->tableName . " WHERE Categories_Name = :Categories_Name";
            $stmtDuplicate = $this->conn->prepare($duplicateQuery);
            $stmtDuplicate->bindParam(':Categories_Name', $catName, PDO::PARAM_STR);
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
                $categoryId = sanitizeString($val['Categories_Id']);
                $categoryName = sanitizeString($val['Categories_Name']);
                
                // Check if the category ID matches the selected category ID
                $selected = (!empty($pCategorieId) && $pCategorieId == $categoryId) ? 'selected' : '';
                $options .= '<option value="' . $categoryId . '" ' . $selected . '>' . $categoryName . '</option>';
            }
        }

        return $options;
    }

}

// Database object
$database = new Database();
$db = $database->getConnection();
?>
