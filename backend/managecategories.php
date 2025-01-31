<?php
include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("header.inc.php"); //Header menu calling
include_once("../controller/CategoriemasterController.php");

    // Database object
    $database = new Database();
    $db = $database->getConnection();
   
    $categoryMaster = new CategoryMasterModel($db);

    $type ="";
    $categorieId = "";
    $Categories_Name ="";
    $AddCategory = "";
    $updateCategoriesMaster = "";

    if(isset($_GET['type'])){
        $type = sanitizeString((string)$_GET['type']) ?? "";
    }

    if(isset($_GET['categorieId'])){
        $categorieId = sanitizeString((int)$_GET['categorieId']) ?? 0;
    }

    if(isset($_POST['Categories_Name'])){
        $Categories_Name = sanitizeString((string)$_POST['Categories_Name']) ?? "";
    }
    

    if (isset($type)) {
        if(!empty($categorieId)){

            $CategoryMaster = $categoryMaster->getdataCategorie((int) $categorieId);

            if(!empty($categorieId)){

                $Categories_Name = !empty($CategoryMaster['Categories_Name']) ? sanitizeString((string)$CategoryMaster['Categories_Name']) : "";
            }else{

                redirect("categoriemaster.php");
            }
        }
    }
    
    $chkduplicate ="";
    $chkduplicate_msg ="";

    if(isset($_POST['submit'])){

        
        $chkduplicate = $categoryMaster->checkDuplicatercd((string) $Categories_Name);
        
        if(!empty($chkduplicate)){
            
            $chkduplicate_msg = $Categories_Name.':'."Categorie Name exist in Categoriemaster.";
            
        }else{
            $AddCategory = $categoryMaster->addCategory((string) $Categories_Name);
            if (!empty($AddCategory)) {
                redirect("categoriemaster.php");
            } else {
                $successMessage = "Categorie Name not add";
            }
            
        }
    
        if(!empty($categorieId)){
            
            $updateCategoriesMaster = $categoryMaster->updateCategoriesMaster((int) $categorieId , (string)$Categories_Status = null);       
            // Check !empty
            if (!empty($updateCategoriesMaster)) {
                redirect("categoriemaster.php");
            } else {
                $successMessage = "Failed to update Categorie Name.";
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
                            <div class="container mt-5" id="chkduplicate_msg">
                                <div class="alert alert-primary" role="alert"><?php echo ($chkduplicate_msg) ?? "";?> </div>
                            </div>
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