<?php

    // Debugging functions with exit
    function _d($arr): void {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }

    // Debugging functions with exit
    function _dx($arr): void {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        exit;
    }

    // Convert array to object
    function arrayToObject(array $array): object {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->arrayToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }

    // Convert object to array
    function objectToArray($object): array {
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        if (is_array($object)) {
            return array_map([$this, 'objectToArray'], $object);
        } else {
            return $object;
        }
    }

    // Return PDO fetch associative constant
    function fetchAssociative(): int {
        return PDO::FETCH_ASSOC;
    }

    // Function to handle redirection
    function redirect(string $url): void {
        header('Location: ' . $url);
        exit();
    }

    // Function to sanitize input
    function sanitizeString(string $input): string {
        $input = trim($input);

        // Remove HTML tags
        $sanitized = strip_tags($input);

        // Encode special characters
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');

        return $sanitized;
    }

    // Function to handle redirection with an error message
    function redirectWithError(string $url, string $error): void {
        $_SESSION['error_msg'] = $error;
        header('Location: ' . $url);
        exit();
    }

    // Function to get file information
    function getFileInfo($filePath) {
        $fileInfo = new SplFileInfo($filePath);
        return [
            'name' => $fileInfo->getFilename(),
            'path' => $fileInfo->getRealPath(),
            'type' => $fileInfo->getType(),
            'directory' => $fileInfo->getPath()
        ];
    }

    // Function to hash the password
    function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Function to verify the password
    function verifyPassword($enteredPassword, $storedHashedPassword) {
        return password_verify($enteredPassword, $storedHashedPassword);
    }

    // Function to display copyright information
    function copyRight() {
        $yr = date("Y");
        echo "<b>Copyright &copy; $yr MilanRohit</b>";
    }

    // Function to display designer and developer information
    function designdevelopeby() {
        echo "<b>Designed & Developed by <a href='#'>MilanRohit</a></b>";
    }

    function uploadImage(array $file): string {
        // Check if the file is an image
        if (!isset($file['type']) || strpos($file['type'], 'image/') !== 0) {
            throw new Exception('Invalid file type. Only images are allowed.');
        }

        // Check the mime type
        $allowedMimeTypes = ['image/jpeg','image/jpg', 'image/png'];
        if (!in_array($file['type'], $allowedMimeTypes, true)) {
            throw new Exception('Invalid mime type. Only JPG, JPEG, and PNG are allowed.');
        }

        // Check the file size
        $fileSize = $file['size'];
        if ($fileSize < 1000 || $fileSize > 3145728) { // 10 KB to 3 MB
            throw new Exception('File size must be between 10KB and 3MB.');
        }

        // Generate a unique file name and determine the target path
        $fileName = uniqid('img_', true) .rand(0,999).''.$file['name'];
        $targetPath = PRODUCT_IMAGES_UPLOAD_DIR . $fileName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Failed to upload the image.');
        }

        // Return only the image name
        return $fileName;
    }

    // Path Constants
    const BACK_END_PATH = "/PDO_MVC_Ecommerce/backend/";  // BackendPath
    const FRONT_END_PATH = "/PDO_MVC_Ecommerce/frontend/"; // FrontendPath
    const MASTER_CONTROLLER = "/PDO_MVC_Ecommerce/controller/"; // controller

    // Messages
    const NO_RECORED_FOUND = "No Record Found.";
    const CATEGORIE_MASTER_DETAILS = "Manage categories in the category master.";
    const PRODUCT_MASTER_DETAILS = "Manage products in the product master.";
    const DUPLICATE_PRODUCT_NAME = "Duplicate product name. Operation failed.";
    const INVALID_PRODUCT_DATA = "<div class='alert alert-danger'>Invalid Product Data.</div>";
    const INVALID_PRODUCT_ID = "<div class='alert alert-danger'>Invalid Product ID.</div>";
    const PRODUCT_NAME_REQUIRED = "<div class='alert alert-danger'>Product Name is required.</div>";


    // Image & File Operations
    const PRODUCT_IMAGES_UPLOAD_DIR = "images/productimanges/";
    const FAILED_TO_FILE_REMOVE_DIR = "Failed to delete the file.";
    const FILE_NOT_FOUND_DIR = "File not found.";

    // Success Messages
    const PRODUCT_ADDED_SUCCESSFULLY_MSG = "<div class='alert alert-success'>Product added successfully.</div>";
    const PRODUCT_NOT_ADDED_UNSUCCESSFULLY_MSG = "<div class='alert alert-danger'>Product update failed, something went wrong.</div>";
    const PRODUCT_UPDATED_SUCCESSFULLY_MSG = "<div class='alert alert-success'>Product update successfully.</div>";
    
    // Failed Messages
    const PRODUCT_CREATION_FAILED_MSG = "<div class='alert alert-danger'>Product update failed, something went wrong.</div>";
    const PRODUCT_NOT_ADDED_SONMETHING_WRONG_MSG = "<div class='alert alert-danger'>Product not added, something went wrong.</div>";
    const FAILED_TO_DELETE_PRODUCT = "<div class='alert alert-danger'>Failed to delete product.</div>";
?>


