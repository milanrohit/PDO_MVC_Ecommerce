<?php 
//use App\Config\Database;
   include_once("../config/connection.php");
   include_once("../lib/Incfunctions.php");
   include_once("../controller/LoginMasterController.php");
   include_once("../model/LoginMasterModel.php");
   
   // Initialize database connection
   $database = new Database();
   $db = $database->getConnection();
   $LoginmasterModel = new LoginmasterModel($db);

    $errormsg ='';
   
   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && !empty($_POST['Admin_Username']) &&  !empty($_POST['Admin_Password'])) {
            
      $Admin_Username = ($_POST['Admin_Username']) ? ((string)$_POST['Admin_Username']) : "";
      $Admin_Password = ($_POST['Admin_Password']) ? ((string)$_POST['Admin_Password']) : "";

      $Adminmasterdetails ='';
      $Adminmasterdetails = $LoginmasterModel->getAdminmasterdetails($Admin_Username,$Admin_Password);
      
      if (count($Adminmasterdetails) > 0) {
            redirect("categoriemaster.php");
      } else {
            
         $errormsg = "Invalid username or password";
      }
   }
    
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
                  <form method="POST" action="">
                     <div class="form-group">
                        <label>Email address</label>
                        <input type="text" name="Admin_Username" class="form-control" placeholder="Admin Username" 
                        <?php if (!empty($Admin_Username)): ?>
                        value="<?php echo $incFunctions->sanitizeString((string) $Admin_Username ?? '', ENT_QUOTES); ?>" 
                        <?php endif; ?> required>
                     </div>
                     <div class="form-group">
                        <label>Password</label>
                        <input type="text" name="Admin_Password" class="form-control" placeholder="Admin Password" 
                        <?php if (!empty($Admin_Password)): ?>
                        value="<?php echo $incFunctions->sanitizeString((string) $Admin_Password ?? '', ENT_QUOTES); ?>" 
                        <?php endif; ?> required>
                     </div>
                     <button type="submit" name="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
					   </form>

                  <?php if (!empty($errormsg)): ?>
                     <br/><div class="alert alert-danger" id="field_error" role="alert"> <?php echo $errormsg; ?> </div>
                  <?php endif; ?>
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
               },3000);
         });
      </script>
   </body>

</html>