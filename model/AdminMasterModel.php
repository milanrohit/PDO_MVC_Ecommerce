<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");

class AdminMasterModel
{
    private $conn;
    public $tableName = "usersmaster";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create
    public function create(array $data): bool
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
    public function update(int $id, array $data): bool
    {
        $updates = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));

        $sql = "UPDATE {$this->tableName} SET $updates WHERE Users_ID = :id";
        $stmt = $this->conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public function updateAdminMasterModel(int $userId, string $usersStatus): bool
    {
        try {
            $usersName = sanitizeString((string)$_POST['Users_Name']);
            $usersStatus = sanitizeString($usersStatus);
            $userId = sanitizeString($userId);

            if (empty($userId) || empty($usersStatus)) {
                $query = "UPDATE {$this->tableName} SET Users_Name = :usersName WHERE Users_ID = :userId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':usersName', $usersName, PDO::PARAM_STR);
            } else {
                $query = "UPDATE {$this->tableName} SET Users_Status = :usersStatus WHERE Users_ID = :userId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':usersStatus', $usersStatus, PDO::PARAM_STR);
            }

            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }

            printf("Error: %s.\n", $stmt->errorInfo()[2]);
            return false;
        } catch (PDOException $e) {
            echo "Failed to update admin master model: " . $e->getMessage();
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
}
// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$adminMasterModel = new AdminMasterModel($db);

?>
