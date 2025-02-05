<?php
    include_once("../config/connection.php");
    include_once("../lib/function.inc.php");
    include_once("header.inc.php");
    include_once("../controller/ProductMasterController.php");
    include_once("../model/ProductMasterModel.php");

    
    // Database object
    $database = new Database();
    $db = $database->getConnection();
   
    $productMasterModel ='';
    $productMasterModel = new ProductMasterModel($db);

    $type ="";
    $productId = "";
    $productName ="";
    $AddProduct = "";
    $updateProductMaster = "";
    $chkduplicate ="";

    if(isset($_GET['type'])){
        $type = sanitizeString((string)$_GET['type']) ?? "";
    }


    if(isset($_POST['Product_Name'])){
        $productName = sanitizeString((string)$_POST['Product_Name']) ?? "";
    }
    

    if(isset($_GET['productId']) && $_GET['productId'] !=''){

        $productId = sanitizeString((int)$_GET['productId']) ?? 0;

        $productMasterData = $productMasterModel->getdataCategorie((int) $productId);

        if(!empty($productId)){
            $productName = ($productMasterModel['Product_Name']) ? ((string)$productMasterModel['Product_Name']) : "";
        }else{
            redirect("productmaster.php");
        }
    }
    
    if(isset($_POST['submit'])){

        if(isset($_POST['Product_Name']) && $_POST['Product_Name'] !=''){
            $productName = ($_POST['Product_Name']) ? ((string)$_POST['Product_Name']) : "";
        }
        $chkduplicate = $productMasterModel->checkDuplicatercd((string) $productName);
        
        $chkduplicate_msg = "";
           
        if($chkduplicate == 1){

            $chkduplicate_msg = $productName.' : '." Product name availble in master";
        }else{
            if($chkduplicate_msg ==''){

                if(isset($productId) && $productId !=''){

                    $updateProduct = $productMasterModel->updateProductMaster((int) $productId , (string)$Product_Status = null);
                    // Check !empty
                    if (!empty($updateProduct)) {
                        redirect("productmaster.php");
                    } else {
                        $successMessage = "Failed to update Product Name.";
                    }
                }else{
    
                    $AddProduct = $productMasterModel->insertProductMaster((string) $productName);
                    if (!empty($AddProduct)) {
                        redirect("productmaster.php");
                    } else {
                        $successMessage = "Product Name not add something was wrong";
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