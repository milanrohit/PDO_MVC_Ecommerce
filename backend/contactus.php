<?php

include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("header.inc.php"); //Header menu calling
include_once("../model/ContactusModel.php");

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

$Contactus = new ContactusModel($db);

// Get contactus  details
$ContactusDetails = $Contactus->getContactusDetails();

$successMessage = "";
$type ="";
$operation ="";
$contactus_id ="";

$type = isset($_GET['type']) ? sanitizeString($_GET['type']) : "";
$operation = isset($_GET['operation']) ? sanitizeString($_GET['operation']) : "";
$contactus_id = isset($_GET['contactus_id']) ? sanitizeString($_GET['contactus_id']) : "";


// Update contactus status
if (isset($type) && !empty($type)) {


    if (!empty($type) && $type === 'status' && $operation != '' && $contactus_id != '') {
        $status = ($operation === 'active') ? 'A' : 'N';

        // Update the category status
        $data = ['contactus_status' => $status];
        $ContactusMaster = $Contactus->updateContactus((int) $contactus_id,(array) $data);

        // Check if update was successful
        if ($ContactusMaster) {
            redirect("contactus.php");
        } else {
            $successMessage = "Failed to update status.";
        }
    }

    if ($type === 'delete' && $type != '' && $contactus_id != '') {

        // Delete the category CategoriesMaster
        $DeleteContactus = $Contactus->deleteContactus((int) $contactus_id);

        // Check if update was successful
        if (!empty($DeleteContactus)) {
            redirect("contactus.php");
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
                        <h5 class="card-title"><b>Contactus Master</b></h5>
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
                                                <td class="status-column">
                                                    <?php
                                                        $contactus_status = $Val['contactus_status'];
                                                        switch ($contactus_status) {
                                                            case 'A':
                                                                echo '<div class="btn-group" role="group">
                                                                        <a href="?type=status&operation=inactive&contactus_id=' . ($Val['contactus_id']) . '" class="btn btn-success">Active</a>
                                                                        <a href="managecontactus.php?type=edit&contactus_id=' . ($Val['contactus_id']) . '" class="btn btn-primary">Edit</a>
                                                                        <a href="?type=delete&contactus_id=' . ($Val['contactus_id']) . '" class="btn btn-danger">Delete</a>
                                                                    </div>';
                                                                break;
                                                            case 'N':
                                                                echo '<div class="btn-group" role="group">
                                                                        <a href="?type=status&operation=active&contactus_id=' . ($Val['contactus_id']) . '" class="btn btn-warning">Inactive</a>
                                                                        <a href="managecontactus.php?type=edit&contactus_id=' . ($Val['contactus_id']) . '" class="btn btn-primary">Edit</a>
                                                                        <a href="?type=delete&contactus_id=' . ($Val['contactus_id']) . '" class="btn btn-danger">Delete</a>
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

    $(document).ready(function() {
        $('.update-status').click(function() {
            let type = $(this).data('type');
            let operation = $(this).data('operation');
            let contactus_id = $(this).data('id');

            $.ajax({
                url: 'contactus.php', // Change this to the actual PHP script path
                type: 'GET',
                data: {
                    type: type,
                    operation: operation,
                    contactus_id: contactus_id
                },
                success: function(response) {
                    let result = JSON.parse(response);
                    if (result.success) {
                        alert('Status updated successfully!');
                    } else {
                        alert('Failed to update status.');
                    }
                },
                error: function() {
                    alert('An error occurred while updating status.');
                }
            });
        });
    });

</script>

<?php include_once("footer.inc.php"); // Footer calling ?>
