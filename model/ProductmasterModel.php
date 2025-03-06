<?php
  include_once("../config/connection.php");
  include_once("../lib/Incfunctions.php");

class ProductMasterModel {

    private $conn;
    private $productMaster = "productmaster";
    private $categoriesMaster = "categoriesmaster";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Independent function to handle array creation
    public function createProductArray(array $postData): array {

        // Trim all values in the array
        $postData = array_map('trim', $postData);

        return [
            'Product_Id' => $postData['Product_Id'] ?? null,
            'Product_CategorieId' => $postData['Product_CategorieId'] ?? null,
            'Product_Name' => $postData['Product_Name'] ?? null,
            'Product_Mrp' => $postData['Product_Mrp'] ?? null,
            'Product_SellPrice' => $postData['Product_SellPrice'] ?? null,
            'Product_Qty' => $postData['Product_Qty'] ?? null,
            'Product_ShortDesc' => $postData['Product_ShortDesc'] ?? null,
            'Product_LongDesc' => $postData['Product_LongDesc'] ?? null,
            'Product_MetaTitle' => $postData['Product_MetaTitle'] ?? null,
            'Product_MetaDesc' => $postData['Product_MetaDesc'] ?? null,
            'Product_Status' => $postData['Product_Status'] ?? null,
            'Product_Img' => $postData['Product_Img']['name'] ?? null
        ];
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
                    Product_Id = :pId
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
                $stmt->bindParam(':pId', $productId, PDO::PARAM_INT);
            }

            $stmt->execute();

            return $productId !== null ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function insertMasterProduct(array $data): int {
        try {
            // Prepare the SQL statement
            $insertSql = "INSERT INTO {$this->productMaster} (
                            Product_CategorieId,
                            Product_Name,
                            Product_Mrp,
                            Product_SellPrice,
                            Product_Qty,
                            Product_ShortDesc,
                            Product_LongDesc,
                            Product_MetaTitle,
                            Product_MetaDesc,
                            Product_Status,
                            Product_Img
                        ) VALUES (
                            :Product_CategorieId,
                            :Product_Name,
                            :Product_Mrp,
                            :Product_SellPrice,
                            :Product_Qty,
                            :Product_ShortDesc,
                            :Product_LongDesc,
                            :Product_MetaTitle,
                            :Product_MetaDesc,
                            :Product_Status,
                            :Product_Img
                        )";
            $stmt = $this->conn->prepare($insertSql);

            // Bind parameters
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
            $stmt->bindValue(':Product_Img', $data['Product_Img'], PDO::PARAM_STR);

            $stmt->execute();

            // Return the last inserted ID or 0 if insertion failed
            return $stmt->rowCount() > 0 ? (int) $this->conn->lastInsertId() : 0;
        } catch (Exception $e) {
            // Log the error
            error_log($e->getMessage());
            return 0;
        }
    }
    

    // Select ProductMaster function
    public function getAllProductsMaster(): array {
        try {
            $selectSql = "SELECT * FROM {$this->productMaster}";
            $stmt = $this->conn->query($selectSql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function updateProductStatus(int $productId, string $status): bool {
        try {
            $sql = "UPDATE {$this->productMaster} SET Product_Status = :status WHERE Product_Id = :productId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateProductMaster(int $productId, array $data): bool {
        try {
            $setPart = array_map(fn($key) => "$key = :$key", array_keys($data));
            $setPart = implode(', ', $setPart);

            $sql = "UPDATE {$this->productMaster} SET $setPart WHERE Product_Id = :Product_Id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':Product_Id', $productId, PDO::PARAM_INT);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteProductMaster(int $pId): bool {
        try {

            $data = $this->getProductById($pId);
            // Use a prepared statement to prevent SQL injection
            $sql = "DELETE FROM {$this->productMaster} WHERE Product_Id = :pId LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':pId', $pId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Check if the row was deleted
            if ($stmt->rowCount() > 0) {
                if (!empty($data)) {
                    $imageName = $data['Product_Img'];
                    // Delete the associated image
                    $filePath = PRODUCT_IMAGES_UPLOAD_DIR . $imageName;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    } else {
                        error_log("Image file not found: {$filePath}");
                    }
                }
                return true;
            }
    
            // No rows deleted
            return false;
        } catch (Exception $e) {
            // Log error for debugging
            error_log("Error in deleteProductMaster: " . $e->getMessage());
            return false;
        }
    }

    public function checkDuplicateRcd(string $productName): int{
        // Sanitize the input
        $productName = sanitizeString($productName);

        if (!empty($productName)) {
            $duplicateSelectQuery = "SELECT COUNT(*) as cnt FROM " . $this->productMaster . " WHERE Product_Name = :productName";
            try {
                $stmtDuplicate = $this->conn->prepare($duplicateSelectQuery);
                $stmtDuplicate->bindParam(':productName', $productName, \PDO::PARAM_STR);
                $stmtDuplicate->execute();
                $duplicateCheck = $stmtDuplicate->fetch(\PDO::FETCH_ASSOC);

                return !empty($duplicateCheck['cnt']) ? (int)$duplicateCheck['cnt'] : 0;
            } catch (Exception $e) {
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
$productMasterModel = new ProductMasterModel($db);
?>


