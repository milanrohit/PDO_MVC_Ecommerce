<?php
include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("header.inc.php"); //Header menu calling
include_once("../controller/ContactusController.php");

    // Database object
    $database = new Database();
    $db = $database->getConnection();
   
    $ContactusModel = new ContactusModel($db);

    $type ="";
    $contactus_id = "";
    $contactus_name ="";
    $contactus_email ="";
    $contactus_mobile ="";
    $contactus_comment ="";
    $AddCategory = "";
    $ResultContactusDetails = "";
    $chkduplicate ="";

    
    if(isset($_GET['type']) && $_GET['type'] !=''){
        $type = ($_GET['type']) ? sanitizeString((string)$_GET['type']) : "" ;
    }

    if(isset($_POST['contactus_name']) && $_POST['contactus_name'] !=''){
        $contactus_name = ($_POST['contactus_name']) ? sanitizeString((string)$_POST['contactus_name']) : "" ;
    }

    if(isset($_POST['contactus_email']) && $_POST['contactus_email'] !=''){
        $contactus_email = ($_POST['contactus_email']) ? sanitizeString((string)$_POST['contactus_email']) : "" ;
    }

    if(isset($_POST['contactus_mobile']) && $_POST['contactus_mobile'] !=''){
        $contactus_mobile = ($_POST['contactus_mobile']) ? sanitizeString((int)$_POST['contactus_mobile']) : "" ;
    }

    if(isset($_POST['contactus_comment']) && $_POST['contactus_comment'] !=''){
        $contactus_comment = ($_POST['contactus_comment']) ? sanitizeString((string)$_POST['contactus_comment']) : "" ;
    }

    if(isset($_GET['contactus_id']) && $_GET['contactus_id'] !=''){

        $contactus_id =  ($_GET['contactus_id']) ? sanitizeString((int)$_GET['contactus_id']) : 0 ;

        $ContactusDetails = $ContactusModel->getContactusDetails((int) $contactus_id);
        
        if(!empty($contactus_id) && !empty($ContactusDetails)){

            $contactus_name = ($ContactusDetails['contactus_name']) ? ((string)$ContactusDetails['contactus_name']) : "";
            $contactus_email = ($ContactusDetails['contactus_email']) ? ((string)$ContactusDetails['contactus_email']) : "";
            $contactus_mobile = ($ContactusDetails['contactus_mobile']) ? ((string)$ContactusDetails['contactus_mobile']) : "";
            $contactus_comment = ($ContactusDetails['contactus_comment']) ? ((string)$ContactusDetails['contactus_comment']) : "";
            
            $contactus_id = ($_GET['contactus_id']) ? sanitizeString((int)$_GET['contactus_id']) : 0 ;
        }else{

            redirect("contactus.php");
        }
    }
    
    if(isset($_POST['submit'])){

        
        $contactus_name = ($_POST['contactus_name']) ? sanitizeString((string)$_POST['contactus_name']) : "";        
        $contactus_email = ($_POST['contactus_email']) ? sanitizeString((string)$_POST['contactus_email']) : "";        
        $contactus_mobile = ($_POST['contactus_mobile']) ? sanitizeString((string)$_POST['contactus_mobile']) : "";        
        $contactus_comment = ($_POST['contactus_comment']) ? sanitizeString((string)$_POST['contactus_comment']) : "";        
        

        $chkduplicate = $ContactusModel->checkDuplicatercd((string) $contactus_name);
        
        $chkduplicate_msg = "";
           
        if($chkduplicate == 1){

            $chkduplicate_msg = $contactus_name.' : '." Contact availble";
        }else{
            if($chkduplicate_msg ==''){

                if(isset($contactus_id) && $contactus_id !=''){

                    $ResultContactusDetails = $ContactusModel->updateContactusDetails((int) $contactus_id , (string)$Categories_Status = null);     
                    
                    // Check !empty
                    if (!empty($ResultContactusDetails)) {
                        redirect("contactus.php");
                    } else {
                        $successMessage = "Failed to update Contactus Name.";
                    }
                }else{
    
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
                        // Insert contactus details
                    $data = [
                        'contactus_name' => sanitizeString((string)$_POST['contactus_name']),
                        'contactus_email' => sanitizeString((string)$_POST['contactus_email']),
                        'contactus_mobile' => sanitizeString((int)$_POST['contactus_email']),
                        'contactus_comment' => sanitizeString((string)$_POST['contactus_comment']),
                    ];

                    $AddCategory = $ContactusModel->addContactus((array) $data);
                    if (!empty($AddCategory)) {
                        redirect("contactus.php");
                    } else {
                        $successMessage = "Contactus not add";
                    }
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

                            <?php if(!empty($chkduplicate_msg)){?>
                            <div class="container mt-5" id="chkduplicate_msg">
                                <div class="alert alert-warning" role="alert"><?php echo ($chkduplicate_msg) ?? "";?> </div>
                            </div>
                            <?php }?>
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
                  $('#chkduplicate_msg').remove();
               },2000);
         });
      </script>

<?php
    include_once("footer.inc.php"); //Footer calling
?>