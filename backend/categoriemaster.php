<?php

include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("header.inc.php"); //Header menu calling
include_once("../controller/CategoriemasterController.php");



    // CategoryMasterModel object
    $categoryMaster = new CategoryMasterModel($db);

    // Get category master details
    $CategoryMasterDetails = $categoryMaster->getCategoryMasterDetails(); 

    $successMessage = "";
    // Update category master status
    if (isset($_GET['type']) && !empty($_GET['type'])) {

        $type = sanitizeString(((string)$_GET['type']));

        if ($type === 'status') {

            $operation = sanitizeString(((string)$_GET['operation']));
            $categorieId = sanitizeString(((int)$_GET['categorieId']));

            // Determine status based on operation
            $status = ($operation === 'active') ? 'A' : 'N';

            // Update the category status
            $CategoriesMaster = $categoryMaster->updateCategoriesMaster((int)$categorieId,(string) $status);
            
            // Check if update was successful
            if (!empty($CategoriesMaster)) {
                redirect("categoriemaster.php");
            } else {
                $successMessage = "Failed to update status.";
            }
        }
        
        if($type ==='delete' && $type !=''){

           $categorieId = sanitizeString(((int)$_GET['categorieId']));
           
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
                    <div class="container">
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Category Master</h5>
                                <p class="card-text"><?php echo Categorie_master_details ;?></p>
                                <a href="managecategories.php" class="btn btn-primary float-right">Add Category</a>
                            </div>
                        </div>
                    </div>
  
                <?php if(!empty($CategoryMasterDetails)) {?>

                    <div class="card-body--">
                    <div class="table-stats order-table ov-h">
                    <?php if (!empty($successMessage)): ?>
                        <div id="successMessage" class="success-message" >
                            <?php echo $successMessage; ?>
                        </div>
                    <?php endif; ?>

                        <table class="table ">
                            <thead>
                                <tr>
                                    <!-- <th class="serial">#</th> -->
                                    <th class="avatar">Avatar</th> 
                                    <th class="serial">#</th>
                                    <th><b>Categorie ID</b></th>
                                    <th><b>Categorie Name</b></th>
                                    <th><b>Categorie Status</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($CategoryMasterDetails as $cat): ?>
                                    <tr> 
                                        <td class="avatar">
                                            <div class="round-img">
                                                <a href="#"><img class="rounded-circle" src="images/avatar/1.jpg" alt=""></a>
                                            </div>
                                        </td>
                                        <td class="serial">2.</td>
                                        <td><span class="count"><?php echo ($cat['Categories_Id']); ?></span></td>
                                        <td><span class="name"><?php echo ($cat['Categories_Name']); ?></span> </td>
                                        <td>
                                            <?php 
                                                $Categories_Status = $cat['Categories_Status']; 
                                                switch($Categories_Status){
                                                    case 'A':
                                                        echo '<a href="?type=status&operation=inactive&categorieId='.$cat['Categories_Id'].'"><span class="badge badge-Active"><b>Active</b></span></a> | <a href="managecategories.php?type=edit&categorieId=' . $cat['Categories_Id'] . '"><span class="badge badge-Edit"><b>Edit</b></span></a> | <a href="?type=delete&categorieId=' . $cat['Categories_Id'] . '"><span class="badge badge-Deleted"><b>Delete</b></span></a>';
                                                    break;
                                                    case 'N':
                                                        echo '<a href="?type=status&operation=active&categorieId='.$cat['Categories_Id'] .'"><span class="badge badge-Inactive"><b>Inactive</b></span></a> | <a href="managecategories.php?type=edit&categorieId=' . $cat['Categories_Id'] . '"><span class="badge badge-Edit"><b>Edit</b></span></a> | <a href="?type=delete&categorieId=' . $cat['Categories_Id'] . '"><span class="badge badge-Deleted"><b>Delete</b></span></a>';
                                                    break;
                                                    case 'D':
                                                        echo '<span class="badge badge-Deleted"><b>Deleted</b></span>';
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
                    <?php  }else { ?>
                        <div class="card-body">
                            <h4 class="box-title"><?php echo NO_RECORED_FOUND ; ?></h4>
                        </div>
                    <?php  } ?>
                </div>
            </div>
        </div>
    </div>
</div>

</style>

    <script>
        $(document).ready(function() {
            // Remove the success message
            setTimeout(function() {
                $('#successMessage').remove();
            }, 3000);

        });
    </script>
<?php
    include_once("footer.inc.php"); //Footer calling
?>