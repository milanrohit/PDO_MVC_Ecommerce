<?php
    include_once("../config/connection.php");
    include_once("../lib/function.inc.php");
    include_once("header.inc.php");
    include_once("../controller/ProductMasterController.php");
    include_once("../model/ProductMasterModel.php");
    include_once("../model/CategoryMasterModel.php");

    
    // Database object
    $database = new Database();
    $db = $database->getConnection();
   
    $productMasterModel ='';
    $productMasterModel = new ProductMasterModel($db);

    $CategoryMasterModel ='';
    $CategoryMasterModel = new CategoryMasterModel($db);

    $CategoryMasterDetails = $CategoryMasterModel->getCategoryMasterDetails();
    
    $type ="";
    $productId = "";
    $productName ="";
    $AddProduct = "";
    $chkduplicate ="";
    $updateProductMaster = "";

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
                    <div class="card-header"><strong>Product Form</strong></div>
                    <form method="post" action="your_action_page.php" enctype="multipart/form-data">
                        <div class="container mt-5">
                            <div class="card">
                                <div class="card-body card-block">
                                    <div class="form-group">
                                        <label for="dropdown">Choose an option:</label>
                                        <select id="dropdown" name="Product_Name" class="form-control" required>
                                            <?php if (!empty($CategoryMasterDetails) && is_array($CategoryMasterDetails)): ?>
                                                <?php foreach ($CategoryMasterDetails as $key => $val): ?>
                                                    <option value="<?php echo sanitizeString($val['Categories_Id']); ?>">
                                                        <?php echo sanitizeString($val['Categories_Name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="textbox">Input Text:</label>
                                        <input type="text" id="textbox" name="textbox" class="form-control" required>
                                        <div class="invalid-feedback">
                                            Please provide some text.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="photo">Upload Photo:</label>
                                        <input type="file" id="photo" name="photo" class="form-control-file" required>
                                        <div class="invalid-feedback">
                                            Please upload a photo.
                                        </div>
                                    </div>
                                    <button name="submit" type="submit" class="btn btn-primary float-right">Submit</button>
                                </div>
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