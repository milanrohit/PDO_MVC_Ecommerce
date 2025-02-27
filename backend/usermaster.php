<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("header.inc.php"); //Header menu calling

  // Initialize database connection
  $database = new Database();
  $db = $database->getConnection();
?>


<?php
    include_once("footer.inc.php"); //Footer calling
?>