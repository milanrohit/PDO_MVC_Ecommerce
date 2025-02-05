<?php
include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("../model/CategoryMasterModel.php");


class CategoryMasterController extends CategoryMasterModel {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }
}

// Database obj
$database = new Database();
$db = $database->getConnection();

// Categoriemaster obj
$CategoryMasterModel = new CategoryMasterModel($db);
$data="";
$CategoryMasterDetails = $CategoryMasterModel->getCategoryMasterDetails($data);

?>
