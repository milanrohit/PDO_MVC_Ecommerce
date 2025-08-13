<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("../model/LoginMasterModel.php");
//Header menu calling

class LoginMasterController extends  LoginMasterModel{
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    // In LoginMasterController.php
    public function getAdminmasterdetails(string $adminUsername): array|false
    {
        return $this->model->getAdminmasterdetails($adminUsername);
    }
}


// Initialize database connection
$database = new Database();
$db = $database->getConnection();
?>
