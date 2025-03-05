<?php

include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");

// Database object
class ContactusModel {
    private $conn;
    private string $tableName = "contactus";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getContactusDetails(int $contactusId = null): array {
        try {
            if ($contactusId !== null) {
                $query = "SELECT
                            contactus_id,
                            contactus_name,
                            contactus_email,
                            contactus_mobile,
                            contactus_status,
                            contactus_comment,
                            contactus_add_datetime,
                            contactus_update_datetime
                        FROM
                            {$this->tableName}
                        WHERE
                            contactus_id = :contactUSId
                        LIMIT 1";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':contactUSId', $contactusId, PDO::PARAM_INT);
                $stmt->execute();
                $contactusDetails = $stmt->fetch(PDO::FETCH_ASSOC);
                return $contactusDetails !== false ? $contactusDetails : [];
            } else {
                $query="SELECT
                            contactus_id,
                            contactus_name,
                            contactus_email,
                            contactus_mobile,
                            contactus_status,
                            contactus_comment
                        FROM
                            {$this->tableName}
                        ORDER BY contactus_id DESC";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            echo "Failed to fetch contact: " . $e->getMessage();
            return [];
        }
    }

    public function updateContactusDetails(int $contactusId, string $contactus_status): bool {
        try {
            // Ensure proper sanitization
            $contactusName = isset($_POST['contactus_name']) ? sanitizeString((string)$_POST['contactus_name']) : "";
            $contactus_status = sanitizeString((string)$contactus_status);

            if ($contactusId && $contactus_status) {
                // Update status query
                $query = "UPDATE $this->tableName SET contactus_status = :contactUsstatus WHERE contactus_id = :contactusId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':contactUsstatus', $contactus_status, PDO::PARAM_STR);
            } else {
                // Update name query
                $query = "UPDATE $this->tableName SET contactus_name = :contactusName WHERE contactus_id = :contactUSId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':contactusName', $contactusName, PDO::PARAM_STR);
            }

            $stmt->bindParam(':contactUSId', $contactusId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Failed to update contact: " . $e->getMessage();
            return false;
        }
    }

    public function deleteContactus(int $contactusId): bool {
        try {
            if (!empty($contactusId)) {
                $query = "DELETE FROM $this->tableName WHERE contactus_id = :contactusId";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':contactusId', $contactusId, PDO::PARAM_INT);
                return $stmt->execute();
            }
            return false;
        } catch (PDOException $e) {
            echo "Failed to delete contact: " . $e->getMessage();
            return false;
        }
    }

    public function addContactus(array $data): bool {
        try {
            // Validate and sanitize input fields
            $contactusName = isset($data['contactus_name']) ? sanitizeString((string)$data['contactus_name']) : '';
            $contactusEmail = isset($data['contactus_email']) ? sanitizeString((string)$data['contactus_email']) : '';
            $contactusMobile = isset($data['contactus_mobile']) ? sanitizeString((string)$data['contactus_mobile']) : '';
            $contactusComment = isset($data['contactus_comment']) ? sanitizeString((string)$data['contactus_comment']) : '';

            $query = "INSERT INTO $this->tableName (contactus_name, contactus_email, contactus_mobile, contactus_comment) VALUES (:contactUsname, :contactUsemail, :contactUsmobile, :contactUscomment)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':contactUsname', $contactusName, PDO::PARAM_STR);
            $stmt->bindParam(':contactUsemail', $contactusEmail, PDO::PARAM_STR);
            $stmt->bindParam(':contactUsmobile', $contactusMobile, PDO::PARAM_STR);
            $stmt->bindParam(':contactUscomment', $contactusComment, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Failed to add contact: " . $e->getMessage();
            return false;
        }
    }

    public function getdataContactus(int $contactusId) {
        try {
            $query = "SELECT contactus_id, contactus_name, contactus_email, contactus_mobile, contactus_status, contactus_comment, contactus_add_datetime, contactus_update_datetime FROM $this->tableName WHERE contactus_id = :contactusId LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':contactusId', $contactusId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Failed to fetch contact: " . $e->getMessage();
            return false;
        }
    }

    public function checkDuplicatercd(string $contactusName): bool {
        try {
            $contactusName = sanitizeString($contactusName);
            if (!empty($contactusName)) {
                $duplicateQuery = "SELECT COUNT(*) as cnt FROM $this->tableName WHERE contactus_name = :contactUsname";
                $stmtDuplicate = $this->conn->prepare($duplicateQuery);
                $stmtDuplicate->bindParam(':contactUsname', $contactusName, PDO::PARAM_STR);
                $stmtDuplicate->execute();
                $duplicateCheck = $stmtDuplicate->fetch(PDO::FETCH_ASSOC);
                return $duplicateCheck['cnt'] > 0;
            }
            return false;
        } catch (PDOException $e) {
            echo "Failed to check duplicate contact: " . $e->getMessage();
            return false;
        }
    }
}

// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$ContactusModel = new ContactusModel($db);


?>
