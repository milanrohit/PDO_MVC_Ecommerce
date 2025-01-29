<?php
   include_once("../config/connection.php");
   include_once("../lib/function.inc.php");
    session_start();
    unset($_SESSION['Admin_Login']);
    unset($_SESSION['Admin_Username']);
    redirect("login.php");
?>