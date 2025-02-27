<?php
    include_once("../config/connection.php");
    include_once("../lib/Incfunctions.php");
    include_once("header.inc.php");
    include_once("../controller/CategoryMasterController.php");
    include_once("../model/CategoryMasterModel.php");

    // Initialize database connection
    $database = new Database();
    $db = $database->getConnection();
    $incFunctions = new IncFunctions($db);
    $categoryMaster = new CategoryMasterModel($db);

    $type ="";
    $categorieId = "";
    $Categories_Name ="";
    $AddCategory = "";
    $updateCategoriesMaster = "";
    $chkduplicate ="";

    if(isset($_GET['type'])){
        $type = $incFunctions->sanitizeString((string)$_GET['type']) ?? "";
    }


    if(isset($_POST['Categories_Name'])){
        $Categories_Name = $incFunctions->sanitizeString((string)$_POST['Categories_Name']) ?? "";
    }
    

    if(isset($_GET['categorieId']) && $_GET['categorieId'] !=''){

        $categorieId = $incFunctions->sanitizeString((int)$_GET['categorieId']) ?? 0;

        $CategoryMaster = $categoryMaster->getdataCategorie((int) $categorieId);

        if(!empty($categorieId)){

            $Categories_Name = ($CategoryMaster['Categories_Name']) ? ((string)$CategoryMaster['Categories_Name']) : "";
        }else{

            $incFunctions->redirect("categoriemaster.php");
        }
    }
    
    if(isset($_POST['submit'])){

        if(isset($_POST['Categories_Name']) && $_POST['Categories_Name'] !=''){
            $Categories_Name = ($_POST['Categories_Name']) ? ((string)$_POST['Categories_Name']) : "";        
        }

        $chkduplicate = $categoryMaster->checkDuplicatercd((string) $Categories_Name);
        
        $chkduplicate_msg = "";
           
        if($chkduplicate == 1){

            $chkduplicate_msg = $Categories_Name.' : '." Categories availble in master";
        }else{
            if($chkduplicate_msg ==''){

                if(isset($categorieId) && $categorieId !=''){

                    $updateCategoriesMaster = $categoryMaster->updateCategoriesMaster((int) $categorieId , (string)$Categories_Status = null);       
                    // Check !empty
                    if (!empty($updateCategoriesMaster)) {
                        $incFunctions->redirect("categoriemaster.php");
                    } else {
                        $successMessage = "Failed to update Categorie Name.";
                    }
                }else{
    
                    $AddCategory = $categoryMaster->addCategory((string) $Categories_Name);
                    if (!empty($AddCategory)) {
                        $incFunctions->redirect("categoriemaster.php");
                    } else {
                        $successMessage = "Categorie Name not add";
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
                    <div class="card-header"><strong>Categories Form</strong></div>
                    <form method="POST">
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="Categories Name" class=" form-control-label">Categories Name</label>
                                <input type="text" name="Categories_Name" id="Categories_Name" value="<?php echo $Categories_Name;?>"placeholder="Enter Categories name" class="form-control" required>
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