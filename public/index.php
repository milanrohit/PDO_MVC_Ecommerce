<?php

require_once '../vendor/autoload.php';

use App\Controllers\UserController;

$userController = new UserController();
$userController->index();

?>
