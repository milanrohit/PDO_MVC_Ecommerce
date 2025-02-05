<?php
include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("header.inc.php");
include_once("../model/ProductMasterModel.php");

    // Database object
    $database = new Database();
    $db = $database->getConnection();
   
    // Productmaster obj
    $ProductmasterModel = new ProductMasterModel($db);

    $productMasterDetails = '';
    $productMasterDetails = $ProductmasterModel->getProductMasterDetails();
    
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Product Master</b></h5>
                        <p class="card-text"><?php echo Product_master_details; ?></p>
                        <a href="manageproduct.php" class="btn btn-primary float-right">Add Product</a>
                    </div>

                    <?php if (!empty($productMasterDetails)) { ?>

                        <div class="card-body--">
                            <div class="table-stats order-table ov-h">
                                <?php if (!empty($successMessage)): ?>
                                    <div id="successMessage" class="success-message">
                                        <?php echo $successMessage; ?>
                                    </div>
                                <?php endif; ?>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="serial">#</th>
                                            <th class="avatar">Avatar</th>
                                            <th><b>Name</b></th>
                                            <th><b>Category Name</b></th>
                                            <th><b>Mrp</b></th>
                                            <th><b>SellPrice</b></th>
                                            <th><b>Qty</b></th>
                                            <th><b>ShortDesc</b></th>
                                            <th><b>LongDesc</b></th>
                                            <th><b>Meta Title</b></th>
                                            <th><b>Meta Desc</b></th>
                                            <th><b>Status</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $index = 0;
                                        foreach ($productMasterDetails as $val):
                                            $index ++;
                                        ?>
                                            <tr>
                                                <td class="serial"><?php echo $index; ?>.</td>
                                                <td class="avatar">
                                                    <div class="round-img">
                                                        <a href="#"><img class="rounded-circle" src="images/avatar/1.jpg" alt=""></a>
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
                                                <td>
                                                    <?php
                                                    $productStatus = sanitizeString($val['Product_Satus']);
                                                    switch ($productStatus) {
                                                        case 'A':
                                                            echo '<a href="?type=status&operation=inactive&productId=' . sanitizeString($val['Product_Id']) . '"><span class="badge badge-Active"><b>Active</b></span></a> | <a href="manageproduct.php?type=edit&productId=' . sanitizeString($val['Product_Id']) . '"><span class="badge badge-Edit"><b>Edit</b></span></a> | <a href="?type=delete&productId=' . sanitizeString($val['Product_Id']) . '"><span class="badge badge-Deleted"><b>Delete</b></span></a>';
                                                            break;
                                                        case 'N':
                                                            echo '<a href="?type=status&operation=active&productId=' . sanitizeString($val['Product_Id']) . '"><span class="badge badge-Inactive"><b>Inactive</b></span></a> | <a href="manageproduct.php?type=edit&productId=' . sanitizeString($val['Product_Id']) . '"><span class="badge badge-Edit"><b>Edit</b></span></a> | <a href="?type=delete&productId=' . sanitizeString($val['Product_Id']) . '"><span class="badge badge-Deleted"><b>Delete</b></span></a>';
                                                            break;
                                                        case 'D':
                                                            echo '<span class="badge badge-Deleted"><b>Deleted</b></span>';
                                                            break;
                                                        default:
                                                            error();
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
