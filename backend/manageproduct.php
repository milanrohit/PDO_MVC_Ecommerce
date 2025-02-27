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

// Fetch product data for editing | Read product data
$pId = isset($_GET['pId']) ? sanitizeString((int)$_GET['pId']) : '';
$type = isset($_GET['type']) ? sanitizeString((string)$_GET['type']) : '';

$pStatus = '';

/* Start Insert a new product || Create a new product */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['pId'])) {
    $productName = sanitizeString($_POST['Product_Name'] ?? '');
    $chkduplicateMsg = '';
    $insertArray = $productMasterModel->createProductArray($_POST);
    $errorMessage = '';

    if ($productName !== '') {
        $chkduplicate = $productMasterModel->checkDuplicatercd($productName);

        if ($chkduplicate === 1) {
            $chkduplicateMsg = "<b>{$productName}</b> " . DUPLICATE_PRODUCT_NAME;
        } else {
            if ($chkduplicateMsg === '' && !empty($insertArray)) {
                
                /* Start productImg upload code */
                $productImg = $_FILES['Product_Img'] ?? '';

                if (isset($productImg) && $productImg['error'] === UPLOAD_ERR_OK) {
                    $productImg = $incFunctions->imageUpload($productImg);
                    $insertArray['Product_Img'] = $productImg; // Add image path to the array
                } else {
                    $insertArray['Product_Img'] = '';
                }
                /* End productImg upload code */
                
                $addProduct = $productMasterModel->insertMasterProduct($insertArray);
                
                if (!empty($addProduct)) {
                    redirect("productmaster.php");
                } else {
                    echo "<div class='alert alert-danger'>Product Name not added, something went wrong.</div>";
                    echo "<div class='alert alert-danger'>{$errorMessage}</div>";
                }
            }
        }
    }
}
/* End Insert a new product || Create a new product */


if ($pId !== '' && $type === 'edit') {

    $pId = sanitizeString((int)$pId ?? '');

    if(!empty($pId)){

        $productMasterData = $productMasterModel->getProductMasterDetails($pId);
   
        // Trim all values in the array
        $productMasterData = array_map('trim', $productMasterData);

        $pCategorieId = ($productMasterData['Product_CategorieId']);
        $pName = ($productMasterData['Product_Name']);
        $pMrp = ($productMasterData['Product_Mrp']);
        $pSellPrice = ($productMasterData['Product_SellPrice']);
        $pQty = ($productMasterData['Product_Qty']);
        $pShortDesc = ($productMasterData['Product_ShortDesc']);
        $pLongDesc = ($productMasterData['Product_LongDesc']);
        $pMetaTitle = ($productMasterData['Product_MetaTitle']);
        $pMetaDesc = ($productMasterData['Product_MetaDesc']);
        $pStatus = ($productMasterData['Product_Status']);
        $pImg = $productMasterData['Product_Img'];
    } else {
       redirect("productmaster.php");
    }
}

/* Start Update existing product | Update a product */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['pId'])) {

    // Sanitize the product ID
    $pId = isset($_POST['pId']) ? sanitizeString((int)$_POST['pId']) : '';

    // Create an array with the product data from the form
    $updateArray = $productMasterModel->createProductArray($_POST);

    // Fetch the current product data
    $currentProduct = $productMasterModel->getProductById($pId);

    // Get the changed values only
    $updateArray = $productMasterModel->getChangedValues($currentProduct, $updateArray);

    // Handle product image upload if there's a new image
    $productImg = $_FILES['Product_Img'] ?? '';

    if (isset($productImg) && $productImg['error'] === UPLOAD_ERR_OK) {
        $productImg = $productMasterModel->imageUpload($productImg);
        $updateArray['Product_Img'] = $productImg;
    }

    // Update the product if there are changes
    if (!empty($updateArray) && !empty($pId)) {
        $updateProduct = $productMasterModel->updateProductMaster($pId, $updateArray);

        if (!empty($updateProduct)) {
            redirect("productmaster.php");
        } else {
            $errorMessage = "Failed to update product.";
            echo "<div class='alert alert-danger'>{$errorMessage}</div>";
        }
    }
}
/* End Update existing product | Update a product */

// Delete a product
if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['pId'])) {
    $pId = sanitizeString((int) $pId);
    $deleteProduct = $productMasterModel->deleteProductMaster($pId);

    if (!empty($deleteProduct)) {
        redirect("productmaster.php");
    } else {
        $errorMessage = "Failed to delete product.";
        echo "<div class='alert alert-danger'>$errorMessage</div>";
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
                                        <input type="file" id="Product_Img" name="Product_Img" class="form-control-file" required>
                                        <input type="hidden" name="existing_img" value="<?php echo $pImg; ?>">
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
        }, 200000);

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

