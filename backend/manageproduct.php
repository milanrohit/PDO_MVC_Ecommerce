<?php
// Including necessary files
include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("header.inc.php");
include_once("../model/ProductMasterModel.php");
include_once("../model/CategoryMasterModel.php");

// Database object
$database = new Database();
$db = $database->getConnection();

// Initializing models
$productMasterModel = new ProductMasterModel($db);
$categoryMasterModel = new CategoryMasterModel($db);

// Initializing variables
$type = $_GET['type'] ?? '';
$productId = '';
$productName = $_POST['Product_Name'] ?? '';
$addProduct = '';
$chkduplicate = '';
$updateProductMaster = '';

// Sanitizing and fetching the product ID
if (!empty($_GET['productId'])) {
    $productId = sanitizeString((int) filter_var($_GET['productId'], FILTER_SANITIZE_NUMBER_INT) ?? 0);

    // Fetching data using the product ID
    $productMasterData = $productMasterModel->getdataCategorie($productId);

    if (!empty($productMasterData)) {
        $productName = $productMasterData['Product_Name'] ?? "";
    } else {
        redirect("productmaster.php");
    }
}

// Handling form submission
if (!empty($_POST['submit'])) {
    $productName = sanitizeString((string) $_POST['Product_Name']) ?? '';

    if (!empty($productName)) {
        $chkduplicate = $productMasterModel->checkDuplicatercd($productName);

        if ($chkduplicate == 1) {
            $chkduplicateMsg = $productName . ' : Product name available in master';
        } else {
            if (empty($chkduplicateMsg)) {
                if (!empty($productId)) {
                    $productStatus = sanitizeString((string) ($_POST['Product_Status'] ?? ''));
                    $updateProduct = $productMasterModel->updateProductMaster((int) $productId, $productStatus);

                    if (!empty($updateProduct)) {
                        redirect("productmaster.php");
                    } else {
                        $successMessage = "Failed to update Product Name.";
                        echo "<div class='alert alert-danger'>$successMessage</div>";
                    }
                } else {
                    $addProduct = $productMasterModel->insertMasterProduct($productName);

                    if (!empty($addProduct)) {
                        redirect("productmaster.php");
                    } else {
                        $successMessage = "Product Name not added, something went wrong.";
                        echo "<div class='alert alert-danger'>$successMessage</div>";
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
                    <div class="card-header"><strong>Product Master Manage</strong></div>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="container mt-5">
                            <div class="card">
                                <div class="card-body card-block">
                                    <div class="form-group">
                                        <label for="dropdown">Product Choose a Category:</label>
                                        <select id="dropdown" name="Product_CategorieId" class="form-control" required>
                                            <option value="" selected disabled>Select a category form cat master</option>
                                            <?php
                                            // Fetching Category Master Details
                                            $catMasterDetails = $categoryMasterModel->getCategoryMasterDetails();
                                            if(!empty($catMasterDetails) && is_array($catMasterDetails)) : ?>
                                                <?php foreach ($catMasterDetails as $val) : ?>
                                                    <option value="<?= sanitizeString($val['Categories_Id']); ?>">
                                                        <?= sanitizeString($val['Categories_Name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_Name">Product Name</label>
                                        <input type="text" id="Product_Name" name="Product_Name" class="form-control" required>
                                        <div class="invalid-feedback">
                                            Please provide some text.
                                        </div>
                                    </div>
                                    <!--<div class="form-group">
                                        <label for="photo">Upload Photo:</label>
                                        <input type="file" id="photo" name="Product_Img" class="form-control-file" required>
                                        <div class="invalid-feedback">
                                            Please upload a photo.
                                        </div>
                                    </div>-->
                                    <div class="form-group">
                                        <label for="Product_Mrp">Product Mrp</label>
                                        <input type="number" id="Product_Mrp" name="Product_Mrp" class="form-control" onkeypress="return isNumberKey(event)" required>
                                        <div class="invalid-feedback">
                                            Please provide Product Mrp.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_SellPrice">Product Sell Price</label>
                                        <input type="number" id="Product_SellPrice" name="Product_SellPrice" class="form-control" onkeypress="return isNumberKey(event)" required>
                                        <div class="invalid-feedback">
                                            Please provide Product Sell Price.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_Qty">Product Quantity</label>
                                        <input type="number" id="Product_Qty" name="Product_Qty" class="form-control" onkeypress="return isNumberKey(event)" required>
                                        <div class="invalid-feedback">
                                            Please provide Product Quantity.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_ShortDesc">Product Short Description</label>
                                        <textarea class="form-control" id="Product_ShortDesc" name="Product_ShortDesc" rows="3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_LongDesc">Product Long Description</label>
                                        <textarea class="form-control" id="Product_LongDesc" name="Product_LongDesc" rows="5" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_MetaTitle">Product Meta Title</label>
                                        <textarea class="form-control" id="Product_MetaTitle" name="Product_MetaTitle" rows="3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_MetaDesc">Product Meta Description</label>
                                        <textarea class="form-control" id="Product_MetaDesc" name="Product_MetaDesc" rows="3" required></textarea>
                                    </div>
                                    <div class="form-group d-flex justify-content-between align-items-center">
                                        <label for="Product_Status">
                                            Product Status
                                        </label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="Product_Status" name="Product_Status" required>
                                            <label class="custom-control-label" for="Product_Status">Active/Deactive</label>
                                            <div class="invalid-feedback">
                                                Please provide Product Status.
                                            </div>
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
        // Remove the error message after 2 seconds
        setTimeout(function () {
            $('#chkduplicateMsg').remove();
        }, 2000);
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
