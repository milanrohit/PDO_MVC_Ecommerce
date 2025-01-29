<?php

include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("../model/CategoriemasterModel.php");
//Header menu calling

class CategoriemasterController extends CategoryMasterModel {
    private $conn;
    //private $table_name = "categoriesmaster";

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