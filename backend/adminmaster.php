<?php
// Include necessary files
foreach ([
    "../config/connection.php",
    "../lib/Incfunctions.php",
    "header.inc.php",
    "../model/AdminMasterModel.php"
] as $file) {
    include_once $file;
}

// Initialize database connection
$db = (new Database())->getConnection();

// Fetch admin master details
$adminMasterDetails = (new AdminMasterModel($db))->getUsersMasterDetails();

// Initialize variables
$successMessage = "";
$adminMaster = "";

// Check for query parameters with null coalescing operator
$type = sanitizeString($_GET['type'] ?? "");
$operation = sanitizeString($_GET['operation'] ?? "");
$usersId = sanitizeString((int)($_GET['usersId'] ?? 0));

// Update users master status
if (!empty($type)) {
    switch ($type) {
        case 'status':
            $status = ($operation === 'active') ? 'A' : 'N';

            // Update the user's status
            $adminMaster = $adminMasterModel->updateAdminMasterModel((int) $usersId,(string) $status);
            if (!empty($adminMaster)) {
                redirect("adminmaster.php");
            } else {
                $successMessage = "Failed to update status.";
            }
            break;

        case 'delete':
            // Delete the user from UsersMaster
            $deleteAdminMaster = $adminMasterModel->delete((int)$usersId);

            if (!empty($deleteAdminMaster)) {
                redirect("adminmaster.php");
            } else {
                $successMessage = "Failed to delete the user.";
            }
            break;

        default:
            $successMessage = "Invalid operation type.";
            break;
    }
}

// Display success message (if any)
if (!empty($successMessage)) {
    echo "<p>{$successMessage}</p>";
}
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>User Master</b></h5>
                        <p class="card-text"><?php echo CATEGORIE_MASTER_DETAILS; ?></p>
                        <a href="manageadminmaster.php" class="btn btn-primary float-right">Add User</a>
                    </div>

                    <?php if (!empty($adminMasterDetails)) { ?>

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
                                            <th><b>Name</b></th>
                                            <th><b>Mobile</b></th>
                                            <th><b>Email</b></th>
                                            <th><b>Comment</b></th>
                                            <th><b>Manage</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $index = 0;
                                        foreach ($adminMasterDetails as $val):
                                            $index ++;
                                        ?>
                                            <tr>
                                                <td class="serial"><?php echo $index; ?>.</td>
                                                <td><span class="name"><?php echo $val['Users_Name'];?></span></td>
                                                <td><span class="mobile"><?php echo $val['Users_Mobile'];?></span></td>
                                                <td><span class="email"><?php echo $val['Users_Email'];?></span></td>
                                                <td><span class="comment"><?php echo $val['Users_Comment'];?></span></td>
                                                <td class="status-column">
                                                    <?php
                                                        $Users_Status = $val['Users_Status'];
                                                        switch ($Users_Status) {
                                                            case 'A':
                                                                echo '<div class="btn-group" role="group">
                                                                        <a href="?type=status&operation=inactive&usersId=' . ($val['Users_ID']) . '" class="btn btn-success">Active</a>
                                                                        <a href="manageadminmaster.php?type=edit&usersId=' . ($val['Users_ID']) . '" class="btn btn-primary">Edit</a>
                                                                        <a href="?type=delete&usersId=' . ($val['Users_ID']) . '" class="btn btn-danger">Delete</a>
                                                                    </div>';
                                                                break;
                                                            case 'N':
                                                                echo '<div class="btn-group" role="group">
                                                                        <a href="?type=status&operation=active&usersId=' . ($val['Users_ID']) . '" class="btn btn-warning">Inactive</a>
                                                                        <a href="manageadminmaster.php?type=edit&usersId=' . ($val['Users_ID']) . '" class="btn btn-primary">Edit</a>
                                                                        <a href="?type=delete&usersId=' . ($val['Users_ID']) . '" class="btn btn-danger">Delete</a>
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
<?php
    include_once("footer.inc.php"); //Footer calling
?>
