
<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("header.inc.php");
include_once("../model/UsersMasterModel.php");

// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$UsersMasterModel = new UsersMasterModel($db);


$chkduplicateMsg = '';
$successMessage = "";
$type ="";
$operation ="";
$usersID ="";
$Users_Name ="";
$Users_Email ="";
$Users_Mobile ="";
$Users_Comment ="";

$type = isset($_GET['type']) ? sanitizeString($_GET['type']) : "";
$operation = isset($_GET['operation']) ? sanitizeString($_GET['operation']) : "";
$usersID = isset($_GET['usersId']) ? sanitizeString($_GET['usersId']) : "";

if(!empty($usersID)){

    $UsersDetails = $UsersMasterModel->getUsersMasterDetails((int) $usersID);

    if (!empty($UsersDetails)) {
        $Users_Name = (string)($UsersDetails['Users_Name'] ?? '');
        $Users_Email = (string)($UsersDetails['Users_Email'] ?? '');
        $Users_Mobile = (string)($UsersDetails['Users_Mobile'] ?? '');
        $Users_Comment = (string)($UsersDetails['Users_Comment'] ?? '');
    } else {
        redirect("usermaster.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $Users_Name = sanitizeString($_POST['Users_Name'] ?? '');
    $Users_Email = sanitizeString($_POST['Users_Email'] ?? '');
    $Users_Mobile = sanitizeString((int)($_POST['Users_Mobile'] ?? ''));
    $Users_Comment = sanitizeString($_POST['Users_Comment'] ?? '');

    $data = [
        'Users_Name' => $Users_Name,
        'Users_Email' => $Users_Email,
        'Users_Mobile' => $Users_Mobile,
        'Users_Comment' => $Users_Comment,
    ];

    if ($UsersMasterModel->checkDuplicatercd($Users_Name)) {
        $chkduplicateMsg = $Users_Name . " : User available";
    } else {
        if ($usersID) {

            $resultUsersDetails = $UsersMasterModel->updateUsers((int) $usersID,(array) $data);

            if ($resultUsersDetails) {
                redirect("usermaster.php");
            } else {
                $successMessage = "Failed to update Users Name.";
            }
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $AddCategory = $UsersMasterModel->addUsersmaster($data);
                if ($AddCategory) {
                    redirect("usermaster.php");
                } else {
                    $successMessage = "Users not added.";
                }
            }
        }
    }
}
?>
<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Users Form</strong></div>
                    <?php if (!empty($chkduplicateMsg)): ?>
                    <br/><div class="alert alert-warning" id="chkduplicateMsg" role="alert"> <?php echo $chkduplicateMsg; ?> </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="Users Name" class=" form-control-label">Users Name</label>
                                <input type="text" name="Users_Name" id="Users_Name" value="<?php echo $Users_Name;?>"placeholder="Enter Users name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="Users email" class=" form-control-label">Users Email</label>
                                <input type="text" name="Users_Email" id="Users_Email" value="<?php echo $Users_Email;?>"placeholder="Enter Users email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                            </div>

                            <div class="form-group">
                                <label for="Users mobile" class=" form-control-label">Users Mobile</label>
                                <input type="text" name="Users_Mobile" id="Users_Mobile" value="<?php echo $Users_Mobile;?>"placeholder="Enter Users Mobile" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="Users comment" class=" form-control-label">Users comment</label>
                                <input type="text" name="Users_Comment" id="Users_Comment" value="<?php echo $Users_Comment;?>"placeholder="Enter Users comments" class="form-control" required>
                            </div>

                            <button id="payment-button" name="submit"  type="submit" class="btn btn-lg btn-info btn-block">
                                <span id="payment-button-amount" >Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
         $(document).ready(function() {
               // Remove the error message
               setTimeout(function () {
                  $('#chkduplicateMsg').remove();
               },2500);
         });
      </script>

<?php
    include_once("footer.inc.php"); //Footer calling
?>
