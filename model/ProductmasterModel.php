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

    public function getProductMasterDetails(?int $productId = null): array {
        try {
            $selectQuery = $productId !== null ?
                "SELECT
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
                    Product_MetaDesc,
                    Product_Status,
                    Product_datetime
                FROM
                    {$this->productMaster}
                WHERE
                    Product_Id = :Product_Id
                LIMIT 1" :"
                SELECT
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
                    pm.Product_Status,
                    pm.Product_datetime
                FROM {$this->productMaster} AS pm
                INNER JOIN {$this->categoriesMaster} AS cm
                ON pm.Product_CategorieId = cm.Categories_Id
                ORDER BY pm.Product_Id DESC";

            $stmt = $this->conn->prepare($selectQuery);

            if ($productId !== null) {
                $stmt->bindParam(':Product_Id', $productId, PDO::PARAM_INT);
            }

            $stmt->execute();

            return $productId !== null ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function insertMasterProduct($data): int {
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
            $stmt = $this->conn->prepare($insertSql);
    
            // Bind parameters using bindValue
            $stmt->bindValue(':Product_CategorieId', $data['Product_CategorieId'], PDO::PARAM_INT);
            $stmt->bindValue(':Product_Name', $data['Product_Name'], PDO::PARAM_STR);
            $stmt->bindValue(':Product_Mrp', $data['Product_Mrp'], PDO::PARAM_STR);
            $stmt->bindValue(':Product_SellPrice', $data['Product_SellPrice'], PDO::PARAM_STR);
            $stmt->bindValue(':Product_Qty', $data['Product_Qty'], PDO::PARAM_INT);
            $stmt->bindValue(':Product_ShortDesc', $data['Product_ShortDesc'], PDO::PARAM_STR);
            $stmt->bindValue(':Product_LongDesc', $data['Product_LongDesc'], PDO::PARAM_STR);
            $stmt->bindValue(':Product_MetaTitle', $data['Product_MetaTitle'], PDO::PARAM_STR);
            $stmt->bindValue(':Product_MetaDesc', $data['Product_MetaDesc'], PDO::PARAM_STR);
            $stmt->bindValue(':Product_Status', $data['Product_Status'], PDO::PARAM_INT);

            $stmt->execute();
    
            // Check for successful insertion
            if($stmt->rowCount() > 0) {
                return (int) $this->conn->lastInsertId();
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

    // Update ProductMaster function
    public function updateProductMaster($productId, $data) {
        // Sanitize inputs
        $productId = sanitizeString((int) $productId) ?? 0;

        if (isset($productId) && !empty($productId) && filter_var($productId, FILTER_VALIDATE_INT) !== false) {
            // Build the SET part of the query dynamically based on provided data
            $setPart = [];
            foreach ($data as $key => $value) {
                $setPart[] = "$key = :$key";
            }
            $setPart = implode(', ', $setPart);

            $sql = "UPDATE {$this->productMaster} SET $setPart WHERE Product_Id = :Product_Id";
            $stmt = $this->conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':Product_Id', $productId, PDO::PARAM_INT);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            return $stmt->execute();
        } else {
            return false;
        }
    }

    // Delete function
    public function deleteProduct($pdo, $id) {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
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

    // Optimized getProductById function
    public function getProductById($productId) {
        $sql = "SELECT * FROM {$this->productMaster} WHERE Product_Id = :productId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Function to compare current product data with new data
    public function getChangedValues($currentData, $newData) {
        $changedValues = [];
        foreach ($newData as $key => $value) {
            if ($currentData[$key] != $value) {
                $changedValues[$key] = $value;
            }
        }
        return $changedValues;
    }
}

// Database object
$dataBase = new Database();
$db = $dataBase->getConnection();
?>
