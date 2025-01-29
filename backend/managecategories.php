<?php
include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("header.inc.php"); //Header menu calling
include_once("../controller/CategoriemasterController.php");

    // Database object
    $database = new Database();
    $db = $database->getConnection();
   
    $categoryMaster = new CategoryMasterModel($db);

    $Categories_Name ="";
    if (isset($_GET['type']) && !empty($_GET['type'])) {

        $type = sanitizeString(((string)$_GET['type']));
        $categorieId = sanitizeString((int)$_GET['categorieId']);

        if(!empty($categorieId)){
            $CategoryMaster = $categoryMaster->getdataCategorie((int) $categorieId);
            if(!empty($categorieId)){
                $Categories_Name = ($CategoryMaster['Categories_Name']) ?? "";
            }else{
                redirect("categoriemaster.php");
            }
        }else{
            redirect("categoriemaster.php");
        }
    }

    if(isset($_POST['submit'])){

        $categorieId ='';
        $Categories_Name = sanitizeString((string)($_POST['Categories_Name']))  ?? "default";
        $categorieId = sanitizeString((int)($_GET['categorieId'])) ??  "default";
        
        if(empty($categorieId)){
            $AddCategory = $categoryMaster->addCategory((string) $Categories_Name);
        }else{            
            $updateCategoriesMaster = $categoryMaster->updateCategoriesMaster((int) $categorieId , (string)$Categories_Status = null);
        }
        
        // Check !empty
        if (!empty($updateCategoriesMaster) || !empty($AddCategory)) {
            redirect("categoriemaster.php");
        } else {
            $successMessage = "Failed to update Categorie Name.";
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include_once("footer.inc.php"); //Footer calling
?>