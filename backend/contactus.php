<?php

include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("header.inc.php"); //Header menu calling
include_once("../controller/ContactusController.php");

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

$incFunctions = new IncFunctions($db);
$Contactus = new ContactusModel($db);

// Get contactus  details
$ContactusDetails = $Contactus->getContactusDetails();

$successMessage = "";

if(isset($_GET['type']) || isset($_GET['operation']) || isset($_GET['contactus_id '])){

    $type = ($_GET['type']) ? $incFunctions->sanitizeString((string)$_GET['type']) : "" ;
    $operation =  ($_GET['operation']) ? $incFunctions->sanitizeString((string)$_GET['operation']) : "" ;
    $contactus_id = ($_GET['contactus_id']) ? $incFunctions->sanitizeString((int)$_GET['contactus_id']) : 0 ;
}

// Update contactus status
if (isset($type) && !empty($type)) {

    if ($type === 'status') {

        $operation = $incFunctions->sanitizeString((string)$_GET['operation'])?? "";

        // Determine status based on operation
        $status = ($operation === 'active') ? 'A' : 'N';

        // Update the category status
        $ContactusMaster = $Contactus->updateContactusDetails((int)$contactus_id, (string)$status);

        // Check if update was successful
        if (!empty($ContactusMaster)) {
            $incFunctions->redirect("contactus.php");
        } else {
            $successMessage = "Failed to update status.";
        }
    }

    if ($type === 'delete' && $type != '') {

        // Delete the category CategoriesMaster
        $DeleteContactus = $Contactus->deleteContactus((int)$contactus_id);

        // Check if update was successful
        if (!empty($DeleteContactus)) {
            $incFunctions->redirect("contactus.php");
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
                        <p class="card-text"><?php  ?></p>
                        <a href="managecontactus.php" class="btn btn-primary float-right">Add Contact</a>
                    </div>

                    <?php if (!empty($ContactusDetails)) { ?>

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
                                            <th><b>ID</b></th>
                                            <th><b>Name</b></th>
                                            <th><b>Email</b></th>
                                            <th><b>Co.no</b></th>
                                            <th><b>Status</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $index = 0;
                                        foreach ($ContactusDetails as $Val):
                                            $index ++;
                                        ?>
                                            <tr>
                                                <td class="serial"><?php echo $index; ?>.</td>
                                                <td class="avatar">
                                                    <div class="round-img">
                                                        <a href="#"><img class="rounded-circle" src="images/avatar/1.jpg" alt=""></a>
                                                    </div>
                                                </td>
                                                <td><span class="count"><?php echo $Val['contactus_id']; ?></span></td>
                                                <td><span class="name"><?php echo $Val['contactus_name']; ?></span></td>
                                                <td><span class="name"><?php echo $Val['contactus_email']; ?></span></td>
                                                <td><span class="name"><?php echo $Val['contactus_mobile']; ?></span></td>
                                                <td>
                                                    <?php
                                                    $contactus_status = $Val['contactus_status'];
                                                    switch ($contactus_status) {
                                                        case 'A':
                                                            echo '<a href="?type=status&operation=inactive&contactus_id=' . $Val['contactus_id'] . '"><span class="badge badge-Active"><b>Active</b></span></a> | <a href="managecontactus.php?type=edit&contactus_id=' . $Val['contactus_id'] . '"><span class="badge badge-Edit"><b>Edit</b></span></a> | <a href="?type=delete&contactus_id=' . $Val['contactus_id'] . '"><span class="badge badge-Deleted"><b>Delete</b></span></a>';
                                                            break;
                                                        case 'N':
                                                            echo '<a href="?type=status&operation=active&contactus_id=' . $Val['contactus_id'] . '"><span class="badge badge-Inactive"><b>Inactive</b></span></a> | <a href="managecontactus.php?type=edit&contactus_id=' . $Val['contactus_id'] . '"><span class="badge badge-Edit"><b>Edit</b></span></a> | <a href="?type=delete&contactus_id=' . $Val['contactus_id'] . '"><span class="badge badge-Deleted"><b>Delete</b></span></a>';
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
