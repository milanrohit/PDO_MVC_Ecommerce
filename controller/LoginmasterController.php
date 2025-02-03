<?php

include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("../model/LoginmasterModel.php");
//Header menu calling

class LoginmasterController extends  LoginmasterModel{
    private $conn;
    //private $table_name = "categoriesmaster";

    public function __construct($db) {
        $this->conn = $db;
    }    

    public function getAdminmasterdetails(string $Admin_Username,string $Admin_Password):array {
        $Adminmasterdetails = $this->LoginmasterModel->getAdminmasterdetails($Admin_Username, $Admin_Password);
        
        return $Adminmasterdetails ? $Adminmasterdetails : null;
    }
}

// Database obj
$database = new Database();
$db = $database->getConnection();



?>