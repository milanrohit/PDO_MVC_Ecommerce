<?php

include_once("../config/connection.php");
include_once("../lib/function.inc.php");

class ProductmasterModel {

    private $conn;
    private $table_name = "productmaster";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get details of all Products.
     * 
     * @return array
     */ 
    public function getProductMasterDetails(): array {
        try {

            if(!empty($Product_Id )){

                $query = "SELECT Product_Id, Product_CategorieId, Product_Name, Product_Mrp, Product_SellPrice, Product_Qty, Product_Img, Product_ShortDesc, Product_LongDesc, Product_MetaTitle, Product_MetaDesc, Product_Satus, Product_datetime FROM ".$this->table_name." WHERE Product_Id = :Product_Id LIMIT 1";
                $stmt = $this->conn->prepare($query);

                // Execute the statement
                $stmt->execute();
                $ProductResult = $stmt->fetch(PDO::FETCH_ASSOC);
            }else{

                $query = "SELECT Product_Id, Product_CategorieId, Product_Name, Product_Mrp, Product_SellPrice, Product_Qty, Product_Img, Product_ShortDesc, Product_LongDesc, Product_MetaTitle, Product_MetaDesc, Product_Satus, Product_datetime FROM ".$this->table_name." ORDER BY Product_Id DESC";
                $stmt = $this->conn->prepare($query);

                // Execute the statement
                $stmt->execute();
                $ProductResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Execute the statement
            $stmt->execute();
            $ProductResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $ProductResult;
        } catch (PDOException $e) {
            echo "Failed to Select a Product: " . $e->getMessage();
        }
    }

    /**
     * Update the status of a Product.
     * 
     * @param int $Product_Id
     * @param string $Product_Status
     * @return bool
     */
    public function updateProductMaster($productData): bool
    {
        try {

            if (!empty($productId) && !empty($productStatus)) {
                // Prepare SQL query
                $query = "UPDATE {$this->table_name} SET Product_Status = :Product_Status WHERE Product_Id = :Product_Id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':Product_Status', $productStatus, PDO::PARAM_STR);
            } else {
                $query = "UPDATE {$this->table_name} SET Product_Name = :Product_Name WHERE Product_Id = :Product_Id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':Product_Name', $productName, PDO::PARAM_STR);
            }

            // Bind parameters
            $stmt->bindParam(':Product_Id', $productId, PDO::PARAM_INT);

            // Execute the statement
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Failed to update Product: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a Product.
     * 
     * @param int $Product_Id
     */
    public function deleteProductMaster(int $Product_Id): bool
    {
        try {
            // Sanitize the product ID
            
            $Product_Id = sanitizeString($Product_Id, FILTER_SANITIZE_NUMBER_INT);
            
            if (!empty($productId)) {
                $query = "DELETE FROM {$this->table_name} WHERE Product_Id = :Product_Id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':Product_Id', $Product_Id, PDO::PARAM_INT);

                return $stmt->execute();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Failed to delete Product: " . $e->getMessage());
            return false;
        }
    }

    public function addProduct(array $productData): bool {
        try {
            // Prepare an SQL statement with placeholders for the product attributes
            $query = "INSERT INTO $this->table_name (
                            Product_CategorieId,
                            Product_Name,
                            Product_Mrp,
                            Product_SellPrice,
                            Product_Qty,
                            Product_Img,
                            Product_ShortDesc,
                            Product_LongDesc,
                            Product_MetaTitle,
                            Product_MetaDesc
                        ) VALUES (
                            :Product_CategorieId,
                            :Product_Name,
                            :Product_Mrp,
                            :Product_SellPrice,
                            :Product_Qty,
                            :Product_Img,
                            :Product_ShortDesc,
                            :Product_LongDesc,
                            :Product_MetaTitle,
                            :Product_MetaDesc
                        ) LIMIT 1";

            $stmt = $this->conn->prepare($query);

            // Bind the parameters
            $stmt->bindParam(':Product_CategorieId', sanitizeString((int) $Product_CategorieId), PDO::PARAM_INT);
            $stmt->bindParam(':Product_Name', sanitizeString((string) $Product_Name), PDO::PARAM_STR);
            $stmt->bindParam(':Product_Mrp', sanitizeString((int) $Product_Mrp), PDO::PARAM_INT);
            $stmt->bindParam(':Product_SellPrice', sanitizeString((int) $Product_SellPrice), PDO::PARAM_INT);
            $stmt->bindParam(':Product_Qty', sanitizeString((int) $Product_Qty), PDO::PARAM_INT);
            $stmt->bindParam(':Product_Img', sanitizeString((string) $Product_Img), PDO::PARAM_STR);
            $stmt->bindParam(':Product_ShortDesc', sanitizeString((string) $Product_ShortDesc), PDO::PARAM_STR);
            $stmt->bindParam(':Product_LongDesc', sanitizeString((string) $Product_LongDesc), PDO::PARAM_STR);
            $stmt->bindParam(':Product_MetaTitle', sanitizeString((string) $Product_MetaTitle), PDO::PARAM_STR);
            $stmt->bindParam(':Product_MetaDesc', sanitizeString((string) $Product_MetaDesc), PDO::PARAM_STR);

            // Execute the statement
            if ($stmt->execute()) {
                return true; 
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log('General error: ' . $e->getMessage());
            return false;
        }catch (Exception $e) {
            error_log('Failed to Add Product: ' . $e->getMessage());
            return false;
        }
    }

    public function getDataProduct(int $Product_Id): ?array
    {
        $query = "SELECT 
                    Product_Id,
                    Product_CategorieId,
                    Product_Name,
                    Product_Mrp,
                    Product_SellPrice,
                    Product_Qty,
                    Product_Img,
                    Product_ShortDesc,
                    Product_LongDesc,
                    Product_MetaTitle,
                    Product_MetaDesc 
                  FROM ".$this->table_name." 
                  WHERE Product_Id = :Product_Id 
                  LIMIT 1";
        
        try {
            $stmt = $this->conn->prepare($query);
            // Bind parameter
            $stmt->bindParam(':Product_Id', sanitizeString((int) $Product_Id), PDO::PARAM_INT);
            // Execute the statement
            $stmt->execute();

            $ProductResult = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Return the result
            return $ProductResult ?: null;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return null;
        } catch (Exception $e) {
            error_log('General error: ' . $e->getMessage());
            return null;
        }
    }

    public function checkDuplicateRcd(string $Product_Name): int
    {
        // Sanitize the input
        $Product_Name = sanitizeString((string)($Product_Name));
        
        if (!empty($Product_Name)) {
            $duplicateQuery = "SELECT COUNT(*) as cnt FROM " . $this->table_name . " WHERE Product_Name = :Product_Name";
            try {
                $stmtDuplicate = $this->conn->prepare($duplicateQuery);
                $stmtDuplicate->bindParam(':Product_Name', $Product_Name, PDO::PARAM_STR);
                $stmtDuplicate->execute();
                $duplicateCheck = $stmtDuplicate->fetch(PDO::FETCH_ASSOC);
                
                return !empty($duplicateCheck['cnt']) ? (int) $duplicateCheck['cnt'] : 0;
            } catch (PDOException $e) {
                error_log('Database error: ' . $e->getMessage());
                return 0;
            } catch (Exception $e) {
                error_log('General error: ' . $e->getMessage());
                return 0;
            }
        } else {
            return 0;
        }
    }
}

// Database object
$database = new Database();
$db = $database->getConnection();

?>