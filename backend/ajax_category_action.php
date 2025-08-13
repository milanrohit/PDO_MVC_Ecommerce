<?php
declare(strict_types=1);

// ✅ Set content type before any output
header('Content-Type: application/json');

// ✅ Include dependencies
include_once("../lib/Incfunctions.php");
include_once("../config/connection.php");
include_once("../controller/CategoryMasterController.php");
include_once("../model/CategoryMasterModel.php");

// ✅ Initialize DB and model
$database = new Database();
$db = $database->getConnection();
$categoryMaster = new CategoryMasterModel($db);

// ✅ Default response
$response = ['status' => 'error', 'message' => 'Invalid request'];

// ✅ Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['type'])) {
    $type = sanitizeString($_POST['type'] ?? '');
    $operation = sanitizeString($_POST['operation'] ?? '');
    $categorieId = (int)($_POST['categorieId'] ?? 0);

    // ✅ Validate category ID
    if ($categorieId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid category ID']);
        exit;
    }

    switch ($type) {
        case 'status':
            // ✅ Validate operation
            if (!in_array($operation, ['active', 'inactive'], true)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid operation']);
                exit;
            }

            $status = ($operation === 'active') ? 'A' : 'N';
            $result = $categoryMaster->updateCategoriesMaster($categorieId, $status);
            $response = $result
                ? ['status' => $status, 'message' => STATUS_UPDATE]
                : ['status' => 'error', 'message' => 'Failed to update status'];
            break;

        case 'delete':
            $result = $categoryMaster->deleteCategoriesMaster($categorieId);
            $response = $result
                ? ['status' => 'success', 'message' => 'Category deleted successfully']
                : ['status' => 'error', 'message' => 'Failed to delete category'];
            break;

        default:
            $response = ['status' => 'error', 'message' => 'Unknown request type'];
    }
}

// ✅ Final output
echo json_encode($response);