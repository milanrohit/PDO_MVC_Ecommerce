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

    public function getAdminmasterdetails(string $Admin_Username,string $Admin_Password):array {
        $Adminmasterdetails = $this->LoginmasterModel->getAdminmasterdetails($Admin_Username, $Admin_Password);
        
        return $Adminmasterdetails ? $Adminmasterdetails : null;
    }
}


// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$incFunctions = new IncFunctions($db);
?>
