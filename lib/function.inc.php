<?php
    // Debugging functions
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
                $value = arrayToObject($value);
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
            return array_map('objectToArray', $object);
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

    function copyRight(){
        $yr = date("Y");
        echo "<b>Copyright &copy; $yr MilanRohit</b>";
    }

    function designdevelopeby(){
    echo "<b>Designed & Developed by <a href='#'>MilanRohit</a></b>";
    }

    // Path constants
    const BackendPath="/PDO_MVC_Ecommerce/backend/"; // BackendPath
    const FrontendPath="/PDO_MVC_Ecommerce/frontend/"; // FrontendPath
    const controller="/PDO_MVC_Ecommerce/controller/"; // controller
    const NO_RECORED_FOUND="No Record Found."; // NO_RECORED_FOUND
    const Categorie_master_details="This is a categorie master where you can add & manage categories ."; // Categorie master details
    const PRODUCTMASTERDETAILS="This is a product master where you can add & manage product ."; // Product master details
    const DUPLICATE_PRODUCT_NAME = "Duplicate Found. Product not inserted/updated."; // Product master
    const PRODUCT_IMGES_UPLOAD_DIR = "/backend/images/productImages/"; // Product master
?>
