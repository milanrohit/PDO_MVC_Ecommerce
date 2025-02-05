<?php
  include_once("../config/connection.php");
  include_once("../lib/function.inc.php");

class ProductmasterModel {

    private $conn;
    private $tableName = "productmaster";

    public function __construct($db) {
        $this->conn = $db;
    }
    public function getProductMasterDetails(): array {
        try {
            
            $productId ='';
            if(!empty($productId)){

                $query = "SELECT Product_Id, Product_CategorieId, Product_Name, Product_Mrp, Product_SellPrice, Product_Qty, Product_Img, Product_ShortDesc, Product_LongDesc, Product_MetaTitle, Product_MetaDesc, Product_Satus, Product_datetime FROM ".$this->tableName." WHERE Product_Id = :Product_Id LIMIT 1";
                $stmt = $this->conn->prepare($query);

                // Execute the statement
                $stmt->execute();
                $stmt->fetch(\PDO::FETCH_ASSOC);
            }else{

                $query = "SELECT Product_Id, Product_CategorieId, Product_Name, Product_Mrp, Product_SellPrice, Product_Qty, Product_Img, Product_ShortDesc, Product_LongDesc, Product_MetaTitle, Product_MetaDesc, Product_Satus, Product_datetime FROM ".$this->tableName." ORDER BY Product_Id DESC";
                $stmt = $this->conn->prepare($query);

                // Execute the statement
                $stmt->execute();
                $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }

            // Execute the statement
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch (\PDOException $e) {
            echo "Failed to Select a Product: " . $e->getMessage();
        }
    }

    public function updateProductMaster($productId,$productStatus): bool
    {
        try {

            if (!empty($productId) && !empty($productStatus)) {
                $query = "UPDATE {$this->tableName} SET Product_Status = :ProductStatus WHERE Product_Id = :ProductId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':ProductStatus', $Product_Status, \PDO::PARAM_STR);
            } else {
                $query = "UPDATE {$this->tableName} SET Product_Name = :ProductName WHERE Product_Id = :ProductId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':ProductName', $Product_Name, \PDO::PARAM_STR);
            }

            $stmt->bindParam(':ProductId', $productId, \PDO::PARAM_INT);
            return $stmt->execute();
        }
        catch (\PDOException $e) {
            error_log("Failed to update Product: " . $e->getMessage());
            return false;
        }
    }

    public function deleteProductMaster(int $productId): bool
    {
        try {
            $productId = sanitizeString($productId, FILTER_SANITIZE_NUMBER_INT);
            
            if (!empty($productId)) {
                $query = "DELETE FROM {$this->tableName} WHERE Product_Id = :productId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':productId', $productId, \PDO::PARAM_INT);

                return $stmt->execute();
            } else {
                return false;
            }
        }
        catch (\PDOException $e) {
            error_log("Failed to delete Product: " . $e->getMessage());
            return false;
        }
    }

    public function addProduct(): bool {
        try {
            $query = "INSERT INTO $this->tableName (
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
                            :productCategorieId,
                            :productName,
                            :productMrp,
                            :productSellPrice,
                            :productQty,
                            :productImg,
                            :productShortDesc,
                            :productLongDesc,
                            :productMetaTitle,
                            :productMetaDesc
                        ) LIMIT 1";

            $stmt = $this->conn->prepare($query);

            $productCategorieId ='';
            $productName ='';
            $productMrp ='';
            $productSellPrice ='';
            $productQty ='';
            $productImg ='';
            $productShortDesc ='';
            $productLongDesc ='';
            $productMetaTitle ='';
            $productMetaDesc ='';

            // Bind the parameters
            $stmt->bindParam(':productCategorieId', sanitizeString((int) $productCategorieId), \PDO::PARAM_INT);
            $stmt->bindParam(':productName', sanitizeString((string) $productName), \PDO::PARAM_STR);
            $stmt->bindParam(':productMrp', sanitizeString((int) $productMrp), \PDO::PARAM_INT);
            $stmt->bindParam(':productSellPrice', sanitizeString((int) $productSellPrice), \PDO::PARAM_INT);
            $stmt->bindParam(':productQty', sanitizeString((int) $productQty), \PDO::PARAM_INT);
            $stmt->bindParam(':productImg', sanitizeString((string) $productImg), \PDO::PARAM_STR);
            $stmt->bindParam(':productShortDesc', sanitizeString((string) $productShortDesc), \PDO::PARAM_STR);
            $stmt->bindParam(':productLongDesc', sanitizeString((string) $productLongDesc), \PDO::PARAM_STR);
            $stmt->bindParam(':productMetaTitle', sanitizeString((string) $productMetaTitle), \PDO::PARAM_STR);
            $stmt->bindParam(':productMetaDesc', sanitizeString((string) $productMetaDesc), \PDO::PARAM_STR);

            // Execute the statement
            if ($stmt->execute()){
                return true;
            }
        }
        catch (Exception $e) {
            error_log('Failed to Add Product: ' . $e->getMessage());
            return false;
        }
    }

    public function getDataProduct(int $productId): ?array
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
                  FROM ".$this->tableName."
                  WHERE Product_Id = :productId
                  LIMIT 1";

        try {
            $stmt = $this->conn->prepare($query);
            // Bind parameter
            $stmt->bindParam(':productId', sanitizeString((int) $productId), \PDO::PARAM_INT);
            // Execute the statement
            $stmt->execute();

            $productResult ='';
            $productResult = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Return the result
            return $productResult ?: null;
        } catch (Exception $e) {
            error_log('General error: ' . $e->getMessage());
            return null;
        }
    }

    public function checkDuplicateRcd(string $productName): int
    {
        // Sanitize the input
        $productName = sanitizeString((string)($productName));
        
        if (!empty($productName)) {
            $duplicateQuery = "SELECT COUNT(*) as cnt FROM " . $this->tableName . " WHERE Product_Name = :productName";
            try {
                $stmtDuplicate = $this->conn->prepare($duplicateQuery);
                $stmtDuplicate->bindParam(':productName', $productName, \PDO::PARAM_STR);
                $stmtDuplicate->execute();
                $duplicateCheck = $stmtDuplicate->fetch(\PDO::FETCH_ASSOC);
                
                return !empty($duplicateCheck['cnt']) ? (int) $duplicateCheck['cnt'] : 0;
            }
            catch (Exception $e) {
                error_log('General error: ' . $e->getMessage());
                return 0;
            }
        } else {
            return 0;
        }
    }
}

// Database object
$dataBase = new Database();
$db = $dataBase->getConnection();
?>
