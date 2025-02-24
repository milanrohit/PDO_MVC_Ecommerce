<?php
  include_once("../config/connection.php");
  include_once("../lib/function.inc.php");

class ProductmasterModel {

    private $conn;
    private $productMaster = "productmaster";
    private $categoriesMaster = "categoriesmaster";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getProductMasterDetails(): array {
        try {
            $productId = '';
            $selectQuery = !empty($productId) ?
                "SELECT Product_Id, Product_CategorieId, Product_Name, Product_Mrp, Product_SellPrice, Product_Qty, Product_Img, Product_ShortDesc, Product_LongDesc, Product_MetaTitle, Product_MetaDesc, Product_Satus, Product_datetime 
                FROM ".$this->productMaster." WHERE Product_Id = :Product_Id LIMIT 1":
                "SELECT
                    pm.Product_Id,
                    pm.Product_CategorieId,
                    cm.Categories_Id,
                    cm.Categories_Name,
                    pm.Product_Name,
                    pm.Product_Mrp,
                    pm.Product_SellPrice,
                    pm.Product_Qty,
                    pm.Product_Img,
                    pm.Product_ShortDesc,
                    pm.Product_LongDesc,
                    pm.Product_MetaTitle,
                    pm.Product_MetaDesc,
                    pm.Product_Satus,
                    pm.Product_datetime
                FROM ".$this->productMaster." AS pm
                INNER JOIN ".$this->categoriesMaster." AS cm
                ON pm.Product_CategorieId = cm.Categories_Id
                ORDER BY pm.Product_Id DESC";
            $stmt = $this->conn->prepare($selectQuery);
            if (!empty($productId)) {
                $stmt->bindParam(':Product_Id', $productId, PDO::PARAM_INT);
            }
            $stmt->execute();
            return !empty($productId) ? $stmt->fetch(PDO::FETCH_ASSOC):$stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function insertMasterProduct($pdo, $data) {
        try {
            // Prepare the SQL statement
            $insertSql = "INSERT INTO ".$this->productMaster."(
                                Product_CategorieId,
                                Product_Name,
                                Product_Mrp,
                                Product_SellPrice,
                                Product_Qty,
                                Product_ShortDesc,
                                Product_LongDesc,
                                Product_MetaTitle,
                                Product_MetaDesc,
                                Product_Status
                            )
                            VALUES(
                                :Product_CategorieId,
                                :Product_Name,
                                :Product_Mrp,
                                :Product_SellPrice,
                                :Product_Qty,
                                :Product_ShortDesc,
                                :Product_LongDesc,
                                :Product_MetaTitle,
                                :Product_MetaDesc,
                                :Product_Status
                            ) ";
            $stmt = $pdo->prepare($insertSql);
    
            // Sanitize and bind inputs
            $stmt->bindParam(':Product_CategorieId', sanitizeString((int) $data['Product_CategorieId']), PDO::PARAM_STR);
            $stmt->bindParam(':Product_Name', sanitizeString((string) $data['Product_Name']), PDO::PARAM_STR);
            $stmt->bindParam(':Product_Mrp', sanitizeString((int) $data['Product_Mrp']), PDO::PARAM_STR);
            $stmt->bindParam(':Product_SellPrice', sanitizeString((int) $data['Product_SellPrice']), PDO::PARAM_STR);
            $stmt->bindParam(':Product_Qty', sanitizeString((int) $data['Product_Qty']), PDO::PARAM_STR);
            $stmt->bindParam(':Product_ShortDesc', sanitizeString((string) $data['Product_ShortDesc']), PDO::PARAM_STR);
            $stmt->bindParam(':Product_LongDesc', sanitizeString((string) $data['Product_LongDesc']), PDO::PARAM_STR);
            $stmt->bindParam(':Product_MetaTitle', sanitizeString((string) $data['Product_MetaTitle']), PDO::PARAM_STR);
            $stmt->bindParam(':Product_MetaDesc', sanitizeString((string) $data['Product_MetaDesc']), PDO::PARAM_STR);
            $stmt->bindParam(':Product_Status', sanitizeString((string) $data['Product_Status']), PDO::PARAM_STR);
            
            // Execute the statement
            $stmt->execute();
    
            // Check for successful insertion
            if($stmt->rowCount() > 0) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            // Log or handle the error
            error_log($e->getMessage());
            return 0;
        }
    }

    // Select function
    public function selectProducts($pdo) {
        $sql = "SELECT * FROM products";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update function
    public function updateProduct($pdo, $data) {
        // Sanitize inputs
        $productId = sanitizeString((int) $data['Product_Id']) ?? 0;

        if (isset($productId) && !empty($productId) && filter_var($productId, FILTER_VALIDATE_INT) !== false){

            $sql = "UPDATE
                    products
                SET
                    Product_CategorieId = :Product_CategorieId,
                    Product_Name = :Product_Name,
                    Product_Mrp = :Product_Mrp,
                    Product_SellPrice = :Product_SellPrice,
                    Product_Qty = :Product_Qty,
                    Product_ShortDesc = :Product_ShortDesc,
                    Product_LongDesc = :Product_LongDesc,
                    Product_MetaTitle = :Product_MetaTitle,
                    Product_MetaDesc = :Product_MetaDesc,
                    Product_Status = :Product_Status
                WHERE
                    Product_Id = :Product_Id ";
                $stmt = $pdo->prepare($sql);
                            // Bind parameters
                $productId = $stmt->bindParam(':Product_Id', $productId, PDO::PARAM_INT);
                $stmt->execute($data);
        }
        else
        {
            return false;
        }
    }

    // Delete function
    public function deleteProduct($pdo, $id) {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function checkDuplicateRcd(string $productName): int
    {
        // Sanitize the input
        $productName = sanitizeString((string)($productName));
        
        if (!empty($productName)) {
            $duplicateSelectQuery = "SELECT COUNT(*) as cnt FROM " . $this->productMaster . " WHERE Product_Name = :productName";
            try {
                $stmtDuplicate = $this->conn->prepare($duplicateSelectQuery);
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
