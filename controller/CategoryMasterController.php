<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("../model/CategoryMasterModel.php");

class CategoryMasterController extends CategoryMasterModel {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }
}


// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$incFunctions = new IncFunctions($db);


// Categoriemaster objs
$catMasterController = new CategoryMasterController($db);
$catMasterModel = new CategoryMasterModel($db);

// Retrieve category details
$catId = !empty($_GET['catId']) ? (int)$_GET['catId'] : null;
$categoryDetails = $catMasterModel->getCategoryMasterDetails((int) $catId);

$catFromMaster = $catMasterModel->getCatFromMaster();
?>
