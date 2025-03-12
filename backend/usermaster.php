<?php
include_once("../config/connection.php");
include_once("../lib/Incfunctions.php");
include_once("header.inc.php"); //Header menu calling
include_once("../model/UsersMasterModel.php");

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

$Users = new UsersMasterModel($db);

// Get Users  details
$usersMasterDetails = $Users->getUsersMasterDetails();

$successMessage = "";
$type ="";
$operation ="";
$usersID ="";

$type = isset($_GET['type']) ? sanitizeString($_GET['type']) : "";
$operation = isset($_GET['operation']) ? sanitizeString($_GET['operation']) : "";
$usersID = isset($_GET['usersId']) ? sanitizeString($_GET['usersId']) : "";


// Update Users status
if (isset($type) && !empty($type)) {


    if (!empty($type) && $type === 'status' && $operation != '' && $usersID != '') {
        $status = ($operation === 'active') ? 'A' : 'N';

        // Update the category status
        $data = ['Users_Status' => $status];
        $ContactusMaster = $Users->updateUsers((int) $usersID,(array) $data);

        // Check if update was successful
        if ($ContactusMaster) {
            redirect("usermaster.php");
        } else {
            $successMessage = "Failed to update status.";
        }
    }

    if ($type === 'delete' && $type != '' && $usersID != '') {

        // Delete the Users UsersMaster
        $DeleteUser = $Users->delete((int) $usersID);

        // Check if update was successful
        if (!empty($DeleteUser)) {
            redirect("usermaster.php");
        } else {
            $successMessage = "Failed to Delete the Users UsersMaster.";
        }
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
                        <p class="card-text"><?php echo USER_MASTER_DETAILS; ?></p>
                        <a href="manageusermaster.php" class="btn btn-primary float-right">Add User</a>
                    </div>

                    <?php if (!empty($usersMasterDetails)) { ?>

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
                                        foreach ($usersMasterDetails as $val):
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
                                                                        <a href="manageusermaster.php?type=edit&usersId=' . ($val['Users_ID']) . '" class="btn btn-primary">Edit</a>
                                                                        <a href="?type=delete&usersId=' . ($val['Users_ID']) . '" class="btn btn-danger">Delete</a>
                                                                    </div>';
                                                                break;
                                                            case 'N':
                                                                echo '<div class="btn-group" role="group">
                                                                        <a href="?type=status&operation=active&usersId=' . ($val['Users_ID']) . '" class="btn btn-warning">Inactive</a>
                                                                        <a href="manageusermaster.php?type=edit&usersId=' . ($val['Users_ID']) . '" class="btn btn-primary">Edit</a>
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
