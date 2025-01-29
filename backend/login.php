<?php 
   include_once("../config/connection.php");
   include_once("../lib/function.inc.php");

   class User {
      private $conn;
      private $table_name = "adminmaster";
  
      public function __construct($db) {
          $this->conn = $db;
      }


      public function getAdminmasterdetails(){
      
         if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
             
            if (!empty($_POST['Admin_Username']) && !empty($_POST['Admin_Password'])) {

               // Validate and sanitize input
               $Admin_Username = sanitizeString($_POST['Admin_Username']);
               $Admin_Password = sanitizeString($_POST['Admin_Password']);

               // Prepare SQL query
               $query = "SELECT * FROM " . $this->table_name . " WHERE Admin_Username = :Admin_Username AND Admin_Password = :Admin_Password LIMIT 1";
               
               $stmt = $this->conn->prepare($query);

               // Bind parameters
               $stmt->bindParam(':Admin_Username', $Admin_Username);
               $stmt->bindParam(':Admin_Password', $Admin_Password);
               
               // Execute the statement
               $stmt->execute();
               $adminResult = $stmt->fetch(PDO::FETCH_ASSOC);
                  
               // Verify the password if the user exists
               if (!empty($adminResult)) {
               
                  $_SESSION['Admin_Login'] = 'YES';
                  $_SESSION["Admin_Username"];
                  $_SESSION["Admin_Password"];
                  
                  redirect("categoriemaster.php");
                  
               }else {
                  $errormsg ="Invalid username and password";
               } 
            }
         }
      }
  }
  
  // Database obj
  $database = new Database();
  $db = $database->getConnection();
  
  // User obj
  $user = new User($db);
  $user = $user->getAdminmasterdetails();

  $errormsg = (isset($_POST['submit']) && $_POST['Admin_Username'] && $_POST['Admin_Password']) ? $errormsg ="Invalid username and password" : $errormsg ="Please enter username or password";
  
?>

<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Login Page</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="assets/css/normalize.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/font-awesome.min.css">
      <link rel="stylesheet" href="assets/css/themify-icons.css">
      <link rel="stylesheet" href="assets/css/pe-icon-7-filled.css">
      <link rel="stylesheet" href="assets/css/flag-icon.min.css">
      <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   </head>
   <body class="bg-dark">
      <div class="sufee-login d-flex align-content-center flex-wrap">
         <div class="container">
            <div class="login-content">
               <div class="login-form mt-150">
                  <form method="POST">
                     <div class="form-group">
                        <label>Email address</label>
                        <input type="text" name="Admin_Username" class="form-control" placeholder="Admin Username" required>
                     </div>
                     <div class="form-group">
                        <label>Password</label>
                        <input type="text"  name="Admin_Password" class="form-control" placeholder="Admin Password" required>
                     </div>
                     <button type="submit" name="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
					   </form>
               <div class="field_error" id="field_error"><?php echo $errormsg ?></div> 
               </div>
            </div>
         </div>
      </div>
      <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
      <script src="assets/js/popper.min.js" type="text/javascript"></script>
      <script src="assets/js/plugins.js" type="text/javascript"></script>
      <script src="assets/js/main.js" type="text/javascript"></script>

      <script>
         $(document).ready(function() {
               // Remove the error message
               setTimeout(function () {
                  $('#field_error').remove();
               },2000);
         });
      </script>
   </body>

</html>