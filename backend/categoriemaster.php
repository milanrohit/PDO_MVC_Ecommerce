<?php

include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("header.inc.php"); //Header menu calling
include_once("../controller/CategoryMasterController.php");
include_once("../model/CategoryMasterModel.php");

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// CategoryMasterModel object
$categoryMaster = new CategoryMasterModel($db);

// Get category master details
$CategoryMasterDetails = $categoryMaster->getCategoryMasterDetails();

$successMessage = "";

if(isset($_GET['type']) || isset($_GET['operation']) || isset($_GET['categorieId'])){

    $type = sanitizeString((string)$_GET['type']) ?? "";
    $operation = sanitizeString((string)$_GET['operation'])?? "";
    $categorieId = sanitizeString((int)$_GET['categorieId'])?? 0;
}

// Update category master status
if (isset($type) && !empty($type)) {

    if ($type === 'status') {

        $operation = sanitizeString((string)$_GET['operation'])?? "";

        // Determine status based on operation
        $status = ($operation === 'active') ? 'A' : 'N';

        // Update the category status
        $CategoriesMaster = $categoryMaster->updateCategoriesMaster((int)$categorieId, (string)$status);

        // Check if update was successful
        if (!empty($CategoriesMaster)) {
            redirect("categoriemaster.php");
        } else {
            $successMessage = "Failed to update status.";
        }
    }

    if ($type === 'delete' && $type != '') {

        // Delete the category CategoriesMaster
        $CategoriesMasterStatus = $categoryMaster->deleteCategoriesMaster((int)$categorieId);

        // Check if update was successful
        if (!empty($CategoriesMasterStatus)) {
            redirect("categoriemaster.php");
        } else {
            $successMessage = "Failed to Delete the category CategoriesMaster.";
        }
    }
}
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Category Master</b></h5>
                        <p class="card-text"><?php echo CATEGORIE_MASTER_DETAILS; ?></p>
                        <a href="managecategories.php" class="btn btn-primary float-right">Add Category</a>
                    </div>

                    <?php if (!empty($CategoryMasterDetails)) { ?>

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
                                            <th><b>Categorie ID</b></th>
                                            <th><b>Categorie Name</b></th>
                                            <th><b>Categorie Status</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $index = 0;
                                        foreach ($CategoryMasterDetails as $cat):
                                            $index ++;
                                        ?>
                                            <tr>
                                                <td class="serial"><?php echo $index; ?>.</td>
                                                <td class="avatar">
                                                    <div class="round-img">
                                                        <a href="#"><img class="rounded-circle" src="images/avatar/1.jpg" alt=""></a>
                                                    </div>
                                                </td>
                                                <td><span class="count"><?php echo $cat['Categories_Id']; ?></span></td>
                                                <td><span class="name"><?php echo $cat['Categories_Name']; ?></span></td>
                                                <td class="status-column">
                                                    <?php
                                                        $Categories_Status = $cat['Categories_Status'];
                                                        switch ($Categories_Status) {
                                                            case 'A':
                                                                echo '<div class="btn-group" role="group">
                                                                        <a href="?type=status&operation=inactive&categorieId=' . ($cat['Categories_Id']) . '" class="btn btn-success">Active</a>
                                                                        <a href="managecategories.php?type=edit&categorieId=' . ($cat['Categories_Id']) . '" class="btn btn-primary">Edit</a>
                                                                        <a href="?type=delete&categorieId=' . ($cat['Categories_Id']) . '" class="btn btn-danger">Delete</a>
                                                                    </div>';
                                                                break;
                                                            case 'N':
                                                                echo '<div class="btn-group" role="group">
                                                                        <a href="?type=status&operation=active&categorieId=' . ($cat['Categories_Id']) . '" class="btn btn-warning">Inactive</a>
                                                                        <a href="managecategories.php?type=edit&categorieId=' . ($cat['Categories_Id']) . '" class="btn btn-primary">Edit</a>
                                                                        <a href="?type=delete&categorieId=' . ($cat['Categories_Id']) . '" class="btn btn-danger">Delete</a>
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

<?php include_once("footer.inc.php"); // Footer calling ?>
