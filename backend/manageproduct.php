<?php
// Including necessary files
include_once("../config/connection.php");
include_once("../lib/function.inc.php");
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
$pId = filter_input(INPUT_GET, 'pId', FILTER_SANITIZE_NUMBER_INT) ?? '';
$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING) ?? '';


// Insert a new product || Create a new product
if (isset($_POST['submit']) && empty($pId)) {
    $productName = sanitizeString((string) $_POST['Product_Name']) ?? '';
    $insertArray = [
        'Product_CategorieId' => $_POST['Product_CategorieId'],
        'Product_Name' => $_POST['Product_Name'],
        'Product_Mrp' => $_POST['Product_Mrp'],
        'Product_SellPrice' => $_POST['Product_SellPrice'],
        'Product_Qty' => $_POST['Product_Qty'],
        'Product_ShortDesc' => $_POST['Product_ShortDesc'],
        'Product_LongDesc' => $_POST['Product_LongDesc'],
        'Product_MetaTitle' => $_POST['Product_MetaTitle'],
        'Product_MetaDesc' => $_POST['Product_MetaDesc'],
        'Product_Status' => $_POST['Product_Status']
    ];

    if (!empty($productName)) {
        $chkduplicate = $productMasterModel->checkDuplicatercd($productName);

        if ($chkduplicate === 1) {
            $chkduplicateMsg = $productName . ' : Product name available in master';
        } else {
            if (empty($chkduplicateMsg)) {
                if (!empty($insertArray)) {
                    $addProduct = $productMasterModel->insertMasterProduct($insertArray);
                    if (!empty($addProduct)) {
                        redirect("productmaster.php");
                    } else {
                        $errorMessage = "Product Name not added, something went wrong.";
                        echo "<div class='alert alert-danger'>$errorMessage</div>";
                    }
                } else {
                    $errorMessage = "Product details are empty.";
                    echo "<div class='alert alert-danger'>$errorMessage</div>";
                }
            }
        }
    }
}

if ($pId !== '' && $type === 'edit') {
    $pId = sanitizeString((int) $pId);
    $productMasterData = $productMasterModel->getProductMasterDetails($pId);

    if (!empty($productMasterData)) {
        $pCategorieId = sanitizeString($productMasterData['Product_CategorieId']);
        $pName = sanitizeString($productMasterData['Product_Name']);
        $pMrp = sanitizeString($productMasterData['Product_Mrp']);
        $pSellPrice = sanitizeString($productMasterData['Product_SellPrice']);
        $pQty = sanitizeString($productMasterData['Product_Qty']);
        $pShortDesc = sanitizeString($productMasterData['Product_ShortDesc']);
        $pLongDesc = sanitizeString($productMasterData['Product_LongDesc']);
        $pMetaTitle = sanitizeString($productMasterData['Product_MetaTitle']);
        $pMetaDesc = sanitizeString($productMasterData['Product_MetaDesc']);
        $pStatus = sanitizeString($productMasterData['Product_Status']);
    } else {
        redirect("productmaster.php");
    }
}

// Function to compare current product data with new data
function getChangedValues($currentData, $newData) {
    $changedValues = [];
    foreach ($newData as $key => $value) {
        if ($currentData[$key] != $value) {
            $changedValues[$key] = $value;
        }
    }
    return $changedValues;
}

// Update existing product | Update a product
if (isset($_POST['submit']) && !empty($pId)) {
    $updateArray = [
        'Product_CategorieId' => $_POST['Product_CategorieId'],
        'Product_Name' => $_POST['Product_Name'],
        'Product_Mrp' => $_POST['Product_Mrp'],
        'Product_SellPrice' => $_POST['Product_SellPrice'],
        'Product_Qty' => $_POST['Product_Qty'],
        'Product_ShortDesc' => $_POST['Product_ShortDesc'],
        'Product_LongDesc' => $_POST['Product_LongDesc'],
        'Product_MetaTitle' => $_POST['Product_MetaTitle'],
        'Product_MetaDesc' => $_POST['Product_MetaDesc'],
        'Product_Status' => $_POST['Product_Status']
    ];

    // Fetch the current product data
    $currentProduct = $productMasterModel->getProductById((int)$pId);

    // Get the changed values only
    $updateArray = $productMasterModel->getChangedValues($currentProduct, $updateArray);

    $productName = sanitizeString((string)$_POST['Product_Name']) ?? '';

    if (!empty($productName)) {
        $chkduplicate = $productMasterModel->checkDuplicatercd($productName);

        if ($chkduplicate === 1) {
            $chkduplicateMsg = $productName . ' : Product name available in master';
        } else {
            if (empty($chkduplicateMsg) && !empty($updateArray)) {
                
                $updateProduct = $productMasterModel->updateProductMaster((int)$pId, $updateArray);

                if (!empty($updateProduct)) {
                    redirect("productmaster.php");
                } else {
                    $errorMessage = "Failed to update Product Name.";
                    echo "<div class='alert alert-danger'>$errorMessage</div>";
                }
            }
        }
    }
}

// Delete a product
if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($pId)) {
    $pId = sanitizeString((int) $pId);
    $deleteProduct = $productMasterModel->deleteProductMaster($pId);

    if ($deleteProduct) {
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
                     <br/><div class="alert alert-danger" id="chkduplicateMsg" role="alert"> <?php echo $chkduplicateMsg; ?> </div>
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
                                                $catMasterDetails = $pCategorieId ? $categoryMasterModel->getCategoryMasterDetails($pCategorieId) : $categoryMasterModel->getCategoryMasterDetails();
                                                
                                                if (!empty($catMasterDetails) && is_array($catMasterDetails)) {
                                                    foreach ($catMasterDetails as $val) {
                                                        $categoryId = sanitizeString($val['Categories_Id']);
                                                        $categoryName = sanitizeString($val['Categories_Name']);
                                                        
                                                        $selected = ($pCategorieId && $pCategorieId === $categoryId) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?= $categoryId; ?>" <?= $selected; ?>>
                                                            <?= $categoryName; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">Please choose an option.</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="Product_Name">Product Name</label>
                                        <input type="text" id="Product_Name" name="Product_Name" value="<?php echo $pName ?? ''; ?>" class="form-control" required>
                                        <div class="invalid-feedback">
                                            Please provide some text.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_Mrp">Product Mrp</label>
                                        <input type="number" id="Product_Mrp" name="Product_Mrp" value="<?php echo $pMrp?? ''; ?>" class="form-control" onkeypress="return isNumberKey(event)" required>
                                        <div class="invalid-feedback">
                                            Please provide Product Mrp.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_SellPrice">Product Sell Price</label>
                                        <input type="number" id="Product_SellPrice" name="Product_SellPrice" value="<?php echo $pSellPrice ?? ''; ?>" class="form-control" onkeypress="return isNumberKey(event)" required>
                                        <div class="invalid-feedback">
                                            Please provide Product Sell Price.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_Qty">Product Quantity</label>
                                        <input type="number" id="Product_Qty" name="Product_Qty" value="<?php echo $pQty ?? ''; ?>" class="form-control" onkeypress="return isNumberKey(event)" required>
                                        <div class="invalid-feedback">
                                            Please provide Product Quantity.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_ShortDesc">Product Short Description</label>
                                        <textarea class="form-control" id="Product_ShortDesc" name="Product_ShortDesc" rows="3" required> <?php echo $pShortDesc ?? ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_LongDesc">Product Long Description</label>
                                        <textarea class="form-control" id="Product_LongDesc" name="Product_LongDesc" rows="5" required><?php echo $pLongDesc ?? ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_MetaTitle">Product Meta Title</label>
                                        <textarea class="form-control" id="Product_MetaTitle" name="Product_MetaTitle" rows="3" required><?php echo $pMetaTitle ?? ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="Product_MetaDesc">Product Meta Description</label>
                                        <textarea class="form-control" id="Product_MetaDesc" name="Product_MetaDesc" rows="3" required><?php echo $pMetaDesc ?? ''; ?></textarea>
                                    </div>

                                    <div class="container mt-5">
                                        <div class="form-group">
                                            <label for="Product_Status">Product Status</label>
                                            <select class="form-control" id="Product_Status" name="Product_Status" required>
                                                <option value="">Select Product Status</option>
                                                <option value="A" <?php echo ($pStatus == 'A') ? 'selected' : ''; ?>>Active</option>
                                                <option value="N" <?php echo ($pStatus == 'N') ? 'selected' : ''; ?>>Inactive</option>
                                                <option value="D" <?php echo ($pStatus == 'D') ? 'selected' : ''; ?>>Deleted</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please Select Product Status.
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
        setTimeout(function() {
            $('#chkduplicateMsg').remove();
        }, 2000);

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
