<?php
include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("header.inc.php");
include_once("../model/ProductMasterModel.php");

// Database object
$database = new Database();
$db = $database->getConnection();

// Productmaster obj
$productMasterModel = new ProductMasterModel($db);
$productMasterDetails = $productMasterModel->getProductMasterDetails();

$pId = 0;
$type = '';
$operation ='';

if(isset($_GET['type']) || isset($_GET['operation']) || isset($_GET['pId'])){
    $type = sanitizeString((string)$_GET['type']) ?? "";
    $pId = sanitizeString((int)$_GET['pId'])?? 0;
}

// Update Product master status
if (isset($type) && !empty($type)) {
    if ($type === 'status') {

        $operation = sanitizeString((string)$_GET['operation'])?? "";

        // Determine status based on operation
        $status = ($operation === 'active') ? 'A' : 'N';

        // Update the product status
        $productMaster = $productMasterModel->updateProductStatus((int)$pId,$status) ;

        // Check if update was successful
        if (!empty($productMaster)) {
            redirect("productmaster.php");
        } else {
            $successMessage = "Failed to update status.";
        }
    }

    if ($type === 'delete' && $type != '' && !empty($pId)) {
        // Delete the category ProductMaster
        $deleteProductMaster = $productMasterModel->deleteProductMaster((int)$pId);
        // Check if update was successful
        if (!empty($deleteProductMaster)) {
            redirect("productmaster.php");
        } else {
            $successMessage = " Product was deleted sucessfully From Product Master.";
        }
    }
}
?>
<div class="container pt-5 pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Product Master</h5>
                        <p class="card-text"><?php echo PRODUCTMASTERDETAILS; ?></p>
                        <a href="manageproduct.php" class="btn btn-primary float-right">Add Product</a>
                    </div>
                    <?php if (!empty($productMasterDetails)) { ?>
                        <div class="card-body">
                            <div class="table-stats order-table ov-h">
                                <?php if (!empty($successMessage)): ?>
                                    <div id="successMessage" class="success-message">
                                        <?php echo $successMessage; ?>
                                    </div>
                                <?php endif; ?>
                                <div style="overflow-x:auto;">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="serial">#</th>
                                                <th class="avatar">Avatar</th>
                                                <th>Name</th>
                                                <th>Category Name</th>
                                                <th>Mrp</th>
                                                <th>Sell Price</th>
                                                <th>Qty</th>
                                                <th>Short Desc</th>
                                                <th>Long Desc</th>
                                                <th>Meta Title</th>
                                                <th>Meta Desc</th>
                                                <th class="status-column">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $index = 0; foreach ($productMasterDetails as $val): $index++; ?>
                                                <tr>
                                                    <td class="serial"><?php echo $index; ?>.</td>
                                                    <td class="avatar">
                                                        <div class="round-img">
                                                            <a href="#"><img src="images/avatar/1.jpg" alt="Avatar"></a>
                                                        </div>
                                                    </td>
                                                    <td><?php echo sanitizeString($val['Product_Name']); ?></td>
                                                    <td><?php echo sanitizeString($val['Categories_Name']); ?></td>
                                                    <td><?php echo sanitizeString($val['Product_Mrp']); ?></td>
                                                    <td><?php echo sanitizeString($val['Product_SellPrice']); ?></td>
                                                    <td><?php echo sanitizeString($val['Product_Qty']); ?></td>
                                                    <td><?php echo sanitizeString($val['Product_ShortDesc']); ?></td>
                                                    <td><?php echo sanitizeString($val['Product_LongDesc']); ?></td>
                                                    <td><?php echo sanitizeString($val['Product_MetaTitle']); ?></td>
                                                    <td><?php echo sanitizeString($val['Product_MetaDesc']); ?></td>
                                                    <td class="status-column">
                                                        <?php
                                                        $productStatus = $val['Product_Status'];
                                                        switch ($productStatus) {
                                                            case 'A':
                                                                echo '<div class="btn-group" role="group">
                                                                        <a href="?type=status&operation=inactive&pId=' . ($val['Product_Id']) . '" class="btn btn-success">Active</a>
                                                                        <a href="manageproduct.php?type=edit&pId=' . ($val['Product_Id']) . '" class="btn btn-primary">Edit</a>
                                                                        <a href="?type=delete&pId=' . ($val['Product_Id']) . '" class="btn btn-danger">Delete</a>
                                                                    </div>';
                                                                break;
                                                            case 'N':
                                                                echo '<div class="btn-group" role="group">
                                                                        <a href="?type=status&operation=active&pId=' . ($val['Product_Id']) . '" class="btn btn-warning">Inactive</a>
                                                                        <a href="manageproduct.php?type=edit&pId=' . ($val['Product_Id']) . '" class="btn btn-primary">Edit</a>
                                                                        <a href="?type=delete&pId=' . ($val['Product_Id']) . '" class="btn btn-danger">Delete</a>
                                                                    </div>';
                                                                break;
                                                            case 'D':
                                                                echo '<div class="btn-group" role="group"><span class="badge badge-Deleted">Deleted</span></div>';
                                                                break;
                                                            default:
                                                                echo '<div class="btn-group" role="group"><span class="badge badge-Unknown">Unknown Status</span></div>';
                                                                break;
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="card-body">
                            <h4 class="box-title"><?php echo NO_RECORED_FOUND; ?></h4>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Remove the success message
        setTimeout(function() {
            $('#successMessage').remove();
        }, 3000);
    });
</script>
<?php
include_once("footer.inc.php");
?>
