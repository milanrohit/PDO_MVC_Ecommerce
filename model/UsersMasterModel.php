<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");

class UsersMasterModel
{
    private $conn;
    public $tableName = "usersmaster";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create
    public function addUsersmaster(array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($key) => ":$key", array_keys($data)));

        $sql = "INSERT INTO {$this->tableName} ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Read
    public function getUsersMasterDetails(?int $id = null): ?array
    {
        $sql = $id !== null
            ? "SELECT
                    Users_ID,
                    Users_Name,
                    Users_Mobile,
                    Users_Email,
                    Users_Comment,
                    Users_Status,
                    Users_Add_Date
               FROM
                    {$this->tableName}
               WHERE
                    Users_ID = :id"
            : "SELECT
                    Users_ID,
                    Users_Name,
                    Users_Mobile,
                    Users_Email,
                    Users_Comment,
                    Users_Status,
                    Users_Add_Date
               FROM
                    {$this->tableName}
               ORDER BY
                    Users_ID DESC";
    
        $stmt = $this->conn->prepare($sql);
    
        if ($id !== null) {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }
    
        $stmt->execute();
    
        return $id !== null
            ? $stmt->fetch(PDO::FETCH_ASSOC)
            : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function updateUsers(int  $usersid, array $data): bool {
        try {
            $setPart = array_map(fn($key) => "$key = :$key", array_keys($data));
            $setPart = implode(', ', $setPart);

            $sql = "UPDATE {$this->tableName} SET $setPart WHERE Users_ID = :usersid";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':usersid', $usersid, PDO::PARAM_INT);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
   
    // Delete
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->tableName} WHERE Users_ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function checkDuplicatercd(string $userName): bool {
        try {
            $userName = sanitizeString($userName);
            if (!empty($userName)) {
                $duplicateQuery = "SELECT COUNT(*) as cnt FROM $this->tableName WHERE Users_Name = :userName";
                $stmtDuplicate = $this->conn->prepare($duplicateQuery);
                $stmtDuplicate->bindParam(':userName', $userName, PDO::PARAM_STR);
                $stmtDuplicate->execute();
                $duplicateCheck = $stmtDuplicate->fetch(PDO::FETCH_ASSOC);
                return $duplicateCheck['cnt'] > 0;
            }
            return false;
        } catch (PDOException $e) {
            echo "Failed to check duplicate User: " . $e->getMessage();
            return false;
        }
    }
}
// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$UsersMasterModel = new UsersMasterModel($db);

?>
