<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow all origins (optional, for CORS)

class CategoryMasterAPIModel {

    private $conn;
    public $tableName = "categoriesmaster";
    public function __construct($db) {
        $this->conn = $db;
    }

    // Function to get category master details by category id (if provided) or all records (if not)
    public function getCategoryMasterDetails(?int $catId = null): array {
        // Validate the category ID input
        try {
            // Construct the base query
            $query = "SELECT * FROM " . $this->tableName;

            // Append WHERE clause if category ID is provided
            if ($catId !== null) {
                $query .= " WHERE Categories_Id = :catId";
            }

            // Prepare the query
            $stmt = $this->conn->prepare($query);

            // Bind parameter if category ID is provided
            if ($catId !== null) {
                $stmt->bindParam(':catId', $catId, PDO::PARAM_INT);
            }

            // Execute the query
            $stmt->execute();

            // Fetch results as an associative array
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Return a structured response
            return [
                'status' => 'success',
                'data' => $data
            ];
        } catch (PDOException $e) {
            // Handle and return error details
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

}

    // Initialize database connection
$database = new Database();
$db = $database->getConnection();
$CategoryMasterAPIModel = new CategoryMasterAPIModel($db);

$id = null;
$categoryDetails = $CategoryMasterAPIModel->getCategoryMasterDetails($id); // Pass category ID (optional)
echo (json_encode($categoryDetails));
?>
