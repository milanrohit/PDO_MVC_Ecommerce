<?php
   include_once("../config/connection.php");
   include_once("../lib/Incfunctions.php");
   
   // Initialize database connection
   $database = new Database();
   $db = $database->getConnection();

   if (session_status() === PHP_SESSION_NONE) {
       session_start();
   }

   unset($_SESSION['Admin_Login']);
   unset($_SESSION['Admin_Username']);

   redirect("login.php");
?>
