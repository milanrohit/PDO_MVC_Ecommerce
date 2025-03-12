<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("header.inc.php");
include_once("../model/ContactusModel.php");

// Initialize database connection
$database = new Database();
$db = $database->getConnection();
$ContactusModel = new ContactusModel($db);

$type = $_GET['type'] ?? '';
$contactus_id = (int)($_GET['contactus_id'] ?? 0);
$chkduplicateMsg = '';

if ($contactus_id) {
    $ContactusDetails = $ContactusModel->getContactusDetails($contactus_id);

    if (!empty($ContactusDetails)) {
        $contactus_name = (string)($ContactusDetails['contactus_name'] ?? '');
        $contactus_email = (string)($ContactusDetails['contactus_email'] ?? '');
        $contactus_mobile = (string)($ContactusDetails['contactus_mobile'] ?? '');
        $contactus_comment = (string)($ContactusDetails['contactus_comment'] ?? '');
    } else {
        redirect("contactus.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $contactus_name = sanitizeString($_POST['contactus_name'] ?? '');
    $contactus_email = sanitizeString($_POST['contactus_email'] ?? '');
    $contactus_mobile = sanitizeString((int)($_POST['contactus_mobile'] ?? ''));
    $contactus_comment = sanitizeString($_POST['contactus_comment'] ?? '');

    $data = [
        'contactus_name' => $contactus_name,
        'contactus_email' => $contactus_email,
        'contactus_mobile' => $contactus_mobile,
        'contactus_comment' => $contactus_comment,
    ];

    if ($ContactusModel->checkDuplicatercd($contactus_name)) {
        $chkduplicateMsg = $contactus_name . " : Contact available";
    } else {
        if ($contactus_id) {

            $ResultContactusDetails = $ContactusModel->updateContactus((int) $contactus_id,(array) $data);

            if ($ResultContactusDetails) {
                redirect("contactus.php");
            } else {
                $successMessage = "Failed to update Contactus Name.";
            }
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $AddCategory = $ContactusModel->addContactus($data);
                if ($AddCategory) {
                    redirect("contactus.php");
                } else {
                    $successMessage = "Contactus not added.";
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
                    <div class="card-header"><strong>Contactus Form</strong></div>
                    <?php if (!empty($chkduplicateMsg)): ?>
                    <br/><div class="alert alert-warning" id="chkduplicateMsg" role="alert"> <?php echo $chkduplicateMsg; ?> </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="Contactus Name" class=" form-control-label">Contactus Name</label>
                                <input type="text" name="contactus_name" id="contactus_name" value="<?php echo $contactus_name;?>"placeholder="Enter contactus name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="contactus email" class=" form-control-label">Contactus Email</label>
                                <input type="text" name="contactus_email" id="contactus_email" value="<?php echo $contactus_email;?>"placeholder="Enter contactus email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                            </div>

                            <div class="form-group">
                                <label for="contactus mobile" class=" form-control-label">Contactus Mobile</label>
                                <input type="text" name="contactus_mobile" id="contactus_mobile" value="<?php echo $contactus_mobile;?>"placeholder="Enter contactus Mobile" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="contactus comment" class=" form-control-label">Contactus comment</label>
                                <input type="text" name="contactus_comment" id="contactus_comment" value="<?php echo $contactus_comment;?>"placeholder="Enter contactus comments" class="form-control" required>
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