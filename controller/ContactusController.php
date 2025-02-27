<?php

include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("../model/ContactusModel.php");
include_once("../controller/CategoryMasterController.php");
//Header menu calling

class ContactusController extends ContactusModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
}

// Database obj
$database = new Database();
$db = $database->getConnection();
$incFunctions = new IncFunctions($db);
// Categoriemaster obj
$ContactusModel = new ContactusModel($db);
$contactus_id="";
$ContactusDetails = $ContactusModel->getContactusDetails((int) $contactus_id);

?>