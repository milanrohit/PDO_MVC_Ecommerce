<?php
declare(strict_types=1);
ob_start();
require_once '../config/connection.php';
require_once '../lib/Incfunctions.php';
require_once '../controller/LoginMasterController.php';
require_once '../model/LoginMasterModel.php';

$errormsg = '';
$adminUsername = '';
$adminPassword = '';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$LoginmasterModel = new LoginmasterModel($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $adminUsername = trim($_POST['Admin_Username'] ?? '');
    $adminPassword = trim($_POST['Admin_Password'] ?? '');

    if ($adminUsername === '' || $adminPassword === '') {
        $errormsg = 'Please fill in all fields.';
    } else {
        // Only query by username, not password
        $adminDetails = $LoginmasterModel->getAdminmasterdetails($adminUsername);
        
        if (!$adminDetails || !isset($adminDetails['Admin_Password'])) {
            $errormsg = 'Invalid username or password.';
        } else {
            $adminLogin = $adminDetails['Admin_Login'] ?? '';
            $adminId = (int) ($adminDetails['Admin_Id'] ?? 0);
            $hashedPassword = $adminDetails['Admin_Password'];

            if ($adminId === 1 && $adminPassword === $hashedPassword) {
                redirect("categoriemaster.php");
            } else {
                $errormsg = 'Invalid username or password.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
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
                  <label for="Admin Username">Email Address</label>
                  <input type="text" id="Admin_Username" name="Admin_Username" class="form-control" placeholder="Admin Username"
                  value="<?php echo $adminUsername ?? ""; ?>" required>
               </div>
               <div class="form-group">
                  <label for="Admin Password">Email Password</label>
                  <input type="password" name="Admin_Password" class="form-control" placeholder="Admin Password" 
                  value="<?php echo $adminPassword ?? ""; ?>" required>
               </div>
               <button type="submit" name="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
               </form>
               <?php if (!empty($errormsg)): ?>
               <br/>
               <div class="alert alert-danger" id="field_error" role="alert"> <?php echo $errormsg; ?> </div>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</body>

    <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
    <script src="assets/js/popper.min.js" type="text/javascript"></script>
    <script src="assets/js/plugins.js" type="text/javascript"></script>
    <script src="assets/js/main.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            // Remove the error message after 3 seconds
            setTimeout(function () {
                $('#field_error').remove();
            }, 3000);
        });
    </script>
</html>
