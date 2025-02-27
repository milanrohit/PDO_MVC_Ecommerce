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

    public function getAdminmasterdetails(string $adminUsername,string $adminPassword):array {
        $adminMasterDetails = $this->LoginmasterModel->getAdminmasterdetails($adminUsername, $adminPassword);
        
        return $adminMasterDetails ? $adminMasterDetails : null;
    }
}


// Initialize database connection
$database = new Database();
$db = $database->getConnection();
?>
