<?php
// Including necessary files
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("header.inc.php");
include_once("../model/ProductMasterModel.php");
include_once("../model/CategoryMasterModel.php");

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize models
$productMasterModel = new ProductMasterModel($db);
$categoryMasterModel = new CategoryMasterModel($db);

$pId ='';
$pStatus  ='';
$type = '';
$chkduplicateMsg = '';
$pId = isset($_GET['pId'])? sanitizeString((int)$_GET['pId']) : 0;
$type = isset($_GET['type'])? sanitizeString((int)$_GET['type']) : null;
$pImg='';
$pName='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize and validate inputs
    $pCategorieId = sanitizeString($_POST['Product_CategorieId']) ?? null;
    $pName = sanitizeString($_POST['Product_Name']) ?? null;
    $pMrp = sanitizeString($_POST['Product_Mrp']) ?? null;
    $pSellPrice = sanitizeString($_POST['Product_SellPrice']) ?? null;
    $pQty = sanitizeString($_POST['Product_Qty']) ?? null;
    $pShortDesc = sanitizeString($_POST['Product_ShortDesc']) ?? null;
    $pLongDesc = sanitizeString($_POST['Product_LongDesc']) ?? null;
    $pMetaTitle = sanitizeString($_POST['Product_MetaTitle']) ?? null;
    $pMetaDesc = sanitizeString($_POST['Product_MetaDesc']) ?? null;
    $pStatus = sanitizeString($_POST['Product_Status']) ?? null;
    
    $image = ($_FILES['Product_Img']['name']) ?? null;

    // Check if an image file is being uploaded
    if ($image !== null && $image !== '') {
        $pImg = uploadImage($_FILES['Product_Img']);
    }else{
        $pImg ='';
    }

    $data = $productMasterModel->checkDuplicateRcd($pName);
    // Prevent duplicate product names during insertion
    if ($data > 0) {
        $chkduplicateMsg = sprintf('<b>%s</b>: %s',sanitizeString($pName),DUPLICATE_PRODUCT_NAME);
    }

    if ($pId === 0) {

        if ($chkduplicateMsg === '' || $chkduplicateMsg === null) {

            $data = [
                'Product_CategorieId' => $pCategorieId ?? null,
                'Product_Name'        => $pName ?? '',
                'Product_Mrp'         => $pMrp ?? 0.0,
                'Product_SellPrice'   => $pSellPrice ?? 0.0,
                'Product_Qty'         => $pQty ?? 0,
                'Product_ShortDesc'   => $pShortDesc ?? '',
                'Product_LongDesc'    => $pLongDesc ?? '',
                'Product_MetaTitle'   => $pMetaTitle ?? '',
                'Product_MetaDesc'    => $pMetaDesc ?? '',
                'Product_Status'      => $pStatus ?? false,
                'Product_Img'         => $pImg ?? ''
            ];
            // Insert the product data
            $insertData = $productMasterModel->insertMasterProduct($data);
            if ($insertData != false && $insertData > 0) {
                redirect('productmaster.php');
            } else {
                echo FAILED_PRODUCT_ADDED_MSG;
                exit; // Halt on failure
            }
        }
    } else {
        if ($chkduplicateMsg === '' || $chkduplicateMsg === null) {
            // Prepare data for update
            $dataUpdateArr = [
                'Product_CategorieId' => $pCategorieId ?? null,
                'Product_Mrp'         => $pMrp ?? 0.0,
                'Product_SellPrice'   => $pSellPrice ?? 0.0,
                'Product_Qty'         => $pQty ?? 0,
                'Product_ShortDesc'   => $pShortDesc ?? '',
                'Product_LongDesc'    => $pLongDesc ?? '',
                'Product_MetaTitle'   => $pMetaTitle ?? '',
                'Product_MetaDesc'    => $pMetaDesc ?? '',
                'Product_Status'      => $pStatus ?? false,
            ];

            if (!empty($pId) && $pId > 0){
                $prodData = $productMasterModel->getProductById($pId);
                $productImg = sanitizeString($prodData['Product_Img']);
                $productName = sanitizeString($prodData['Product_Name']);
            }

            // If an image file is being uploaded, add it to the update array
            // Merge the update data with the existing image if one exists
            $addArray = [];
            if($pImg!== null && $pImg!== '' && $productImg!== $pImg){
                $addArray =['Product_Img' => $pImg ?? ''];
                $dataUpdateArr = array_merge($dataUpdateArr,$addArray);
            }

            // If the product name has changed, add it to the update array
            if($pName!== null && $pName!== '' && $productName!== $pName){
                $addArray =['Product_Name' => $pName ?? ''];
                $dataUpdateArr = array_merge($dataUpdateArr,$addArray);
            }

            if (!empty($pId) && $pId > 0) {
                // Update the product record
                $updateData = $productMasterModel->updateProductMaster($pId,$dataUpdateArr);
            
                if(!empty($updateData)){
                    redirect('productmaster.php');
                }else{
                    echo FAILED_PRODUCT_UPDATE_MSG;
                    exit;
                }
            }else{
                echo FAILED_PRODUCT_UPDATE_MSG;
                exit;
            }
        }
    }
}

if($pId!= '' && $type!= ''){

    // Get product details for edit
    $productData = $productMasterModel->getProductMasterDetails($pId);
    // Validate product data before displaying the edit form
    if (!empty($productData) && is_array($productData)) {
        
        $productData = array_map('trim', $productData);

        $pCategorieId = $productData['Product_CategorieId'] ?? null;
        $pName = $productData['Product_Name'] ?? null;
        $pMrp = $productData['Product_Mrp'] ?? null;
        $pSellPrice = $productData['Product_SellPrice'] ?? null;
        $pQty = $productData['Product_Qty'] ?? null;
        $pImg = $productData['Product_Img'] ?? null;
        $pShortDesc = $productData['Product_ShortDesc'] ?? null;
        $pLongDesc = $productData['Product_LongDesc'] ?? null;
        $pMetaTitle = $productData['Product_MetaTitle'] ?? null;
        $pMetaDesc = $productData['Product_MetaDesc'] ?? null;
        $pStatus = $productData['Product_Status'] ?? null;
    }else{
        redirect('productmaster.php');
    }
}
?>
<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Product Master Manage</strong></div>
                    <?php if (!empty($chkduplicateMsg)): ?>
                    <br/><div class="alert alert-warning" id="chkduplicateMsg" role="alert"> <?php echo $chkduplicateMsg; ?> </div>
                    <?php endif; ?>
                    <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="container mt-5">
                            <div class="card">
                                <div class="card-body card-block">
                                    <div class="form-group">
                                        <label for="dropdown">Product Choose a Category:</label>
                                        <select id="dropdown" name="Product_CategorieId" class="form-control" required>
                                            <option value="" selected disabled>Select a category from cat master</option>
                                            <?php
                                                $catMasterDetails = $categoryMasterModel->getCategoryMasterDetails();
                                                if (!empty($catMasterDetails) && is_array($catMasterDetails)) {
                                                    foreach ($catMasterDetails as $val) {
                                                        $categoryId = sanitizeString($val['Categories_Id']);
                                                        $categoryName = sanitizeString($val['Categories_Name']);
                                                        $selected = ($pCategorieId !== null && $pCategorieId === $categoryId) ? 'selected' : '';
                                                        echo '<option value="' . $categoryId . '" ' . $selected . '>' . $categoryName . '</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">Please choose a Category option.</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_Name">Product Name</label>
                                        <input type="text" id="Product_Name" name="Product_Name" value="<?php echo $pName ?? ''; ?>" class="form-control" required>
                                        <div class="invalid-feedback">Please provide some text.</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_Mrp">Product Mrp</label>
                                        <input type="number" id="Product_Mrp" name="Product_Mrp" value="<?php echo $pMrp ?? ''; ?>" class="form-control" onkeypress="return isNumberKey(event)" required>
                                        <div class="invalid-feedback">Please provide Product Mrp.</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_SellPrice">Product Sell Price</label>
                                        <input type="number" id="Product_SellPrice" name="Product_SellPrice" value="<?php echo $pSellPrice ?? ''; ?>" class="form-control" onkeypress="return isNumberKey(event)" required>
                                        <div class="invalid-feedback">Please provide Product Sell Price.</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_Qty">Product Quantity</label>
                                        <input type="number" id="Product_Qty" name="Product_Qty" value="<?php echo $pQty ?? ''; ?>" class="form-control" onkeypress="return isNumberKey(event)" required>
                                        <div class="invalid-feedback">Please provide Product Quantity.</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_ShortDesc">Product Short Description</label>
                                        <textarea class="form-control" id="Product_ShortDesc" name="Product_ShortDesc" rows="3" required><?php echo $pShortDesc ?? ''; ?></textarea>
                                        <div class="invalid-feedback">Please provide a short description.</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_LongDesc">Product Long Description</label>
                                        <textarea class="form-control" id="Product_LongDesc" name="Product_LongDesc" rows="5" required><?php echo $pLongDesc ?? ''; ?></textarea>
                                        <div class="invalid-feedback">Please provide a long description.</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_MetaTitle">Product Meta Title</label>
                                        <textarea class="form-control" id="Product_MetaTitle" name="Product_MetaTitle" rows="3" required><?php echo $pMetaTitle ?? ''; ?></textarea>
                                        <div class="invalid-feedback">Please provide a meta title.</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_MetaDesc">Product Meta Description</label>
                                        <textarea class="form-control" id="Product_MetaDesc" name="Product_MetaDesc" rows="3" required><?php echo $pMetaDesc ?? ''; ?></textarea>
                                        <div class="invalid-feedback">Please provide a meta description.</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="Product_Img">Product Image Upload</label>
                                        <?php if (!empty($pImg)) { ?>
                                            <!-- Show the existing image -->
                                            <img src="<?php echo PRODUCT_IMAGES_UPLOAD_DIR . $pImg; ?>" alt="Product Img" class="img-thumbnail" style="width: 100px; height: 100px;">
                                            <!-- Hidden field to retain the existing image -->
                                            <input type="hidden" name="existing_img" value="<?php echo $pImg; ?>">
                                        <?php } ?>
                                        <!-- File input for new image upload -->
                                        <input type="file" class="form-control" id="Product_Img" name="Product_Img" <?php echo empty($pImg) ? 'required' : ''; ?>>
                                        <div class="invalid-feedback">Please upload a product image.</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="Product_Status">Product Status</label>
                                        <select class="form-control" id="Product_Status" name="Product_Status" required>
                                            <option value="" disabled <?php echo ($pStatus === '') ? 'selected' : ''; ?>>Select Product Status</option>
                                            <?php
                                                $statusOptions = [
                                                    'A' => 'Active',
                                                    'N' => 'Inactive',
                                                    'D' => 'Deleted'
                                                ];
                                                foreach ($statusOptions as $key => $value) {
                                                    $sanitizedValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                                                    $selected = ($pStatus === $key) ? 'selected' : '';
                                                    echo '<option value="' . $key . '" ' . $selected . '>' . $sanitizedValue . '</option>';
                                                }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">Please select a product status.</div>
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
        // Remove the error message after 2 seconds
        setTimeout(function() {
            $('#chkduplicateMsg').remove();
        }, 3000);

        $('#Product_Status').on('change', function() {
            const validStatuses = ['A', 'N', 'D'];
            if (!validStatuses.includes(this.value)) {
                this.value = '';
            }
        });
    });

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    // Bootstrap validation styles
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            // Get the form elements
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
<?php
    include_once("footer.inc.php"); //Footer calling
?>

