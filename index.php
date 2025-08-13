<?php
session_start();

// Check if the user is logged in
require_once 'redirect_pages/error_handler.php';


if (!isset($_SESSION['user'])) {
    header("Location: backend/login.php");
    exit;
}

// Otherwise, show homepage or dashboard
echo "Welcome to the homepage!";