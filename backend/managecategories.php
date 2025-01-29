<?php
include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("header.inc.php"); //Header menu calling
include_once("../controller/CategoriemasterController.php");

    // Database object
    $database = new Database();
    $db = $database->getConnection();

    // CategoryMasterModel object
    $categoryMaster = new CategoryMasterModel($db);

    // Get category master details

    if(isset($_POST['Categories_Name'])){

        $Categories_Name = sanitizeString((string)$_POST['Categories_Name']);
        $AddCategory = $categoryMaster->addCategory((string) $Categories_Name);
    
        // Check !empty
        if (!empty($AddCategory)) {
            $successMessage = "AddCategory successfully.";
            redirect("categoriemaster.php");
        } else {
            $successMessage = "Failed to update status.";
        }
    }
    

    if (isset($_GET['type']) && !empty($_GET['type'])) {


        $type = sanitizeString(((string)$_GET['type']));
    
            
            $type = sanitizeString((string)$_GET['type']);
            $categorieId = sanitizeString((int)$_GET['categorieId']);
            
          

            $categoryMaster = $categoryMaster->getdataCategorie((int) $categorieId);
            _dx($categoryMaster);

           
    }
?>
<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Categories</strong><small> Form</small></div>
                    <form method="POST">
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="Categories Name" class=" form-control-label">Categories Name</label>
                                <input type="text" name="Categories_Name" id="Categories_Name" placeholder="Enter Categories name" class="form-control" required>
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