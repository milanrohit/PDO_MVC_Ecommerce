<?php
    declare(strict_types=1);

    define('PRODUCT_IMAGES_UPLOAD_DIR', '../Img/productImages/'); // image directory

    // Debugging function (non-disruptive)
    function _d(mixed $data): void {
        if (defined('DEBUG') && DEBUG) {
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
    }

    // Debugging with exit (use cautiously)
    function _dx(mixed $data): void {
        if (defined('DEBUG') && DEBUG) {
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            exit;
        }
    }

    // Convert array to object
    function arrayToObject(array $array): object {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            $object->$key = is_array($value) ? arrayToObject($value) : $value;
        }
        return $object;
    }

    // Convert object to array
    function objectToArray(object|array $input): array {
        $input = is_object($input) ? get_object_vars($input) : $input;
        return array_map(fn($item) => is_object($item) || is_array($item) ? objectToArray($item) : $item, $input);
    }

    // PDO fetch associative constant
    function fetchAssociative(): int {
        return PDO::FETCH_ASSOC;
    }

    // Safe redirection
    function redirect(string $url): void {
        if (!headers_sent()) {
            header('Location: ' . $url);
            exit;
        }
    }

    // Redirection with error message
    function redirectWithError(string $url, string $error): void {
        $_SESSION['error_msg'] = $error;
        redirect($url);
    }

    // Sanitize input string
    function sanitizeString(string $input): string {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    // Get file metadata
    function getFileInfo(string $filePath): array {
        $fileInfo = new SplFileInfo($filePath);
        return [
            'name' => $fileInfo->getFilename(),
            'path' => $fileInfo->getRealPath(),
            'type' => $fileInfo->getType(),
            'directory' => $fileInfo->getPath()
        ];
    }

    // Password hashing
    function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Password verification
    function verifyPassword(string $enteredPassword, string $storedHash): bool {
        return password_verify($enteredPassword, $storedHash);
    }

    // Return copyright string
    function getCopyright(): string {
        return '© ' . date('Y') . ' MilanRohit';
    }

    // Return designer/developer credit
    function getDesignerCredit(): string {
        return 'Designed & Developed by <a href="#">MilanRohit</a>';
    }

    // Upload image with validation
    function uploadImage(array $file): string {
        if (!isset($file['type']) || strpos($file['type'], 'image/') !== 0) {
            throw new InvalidArgumentException('Invalid file type. Only images are allowed.');
        }

        $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file['type'], $allowedMimeTypes, true)) {
            throw new InvalidArgumentException('Invalid mime type. Only JPG, JPEG, and PNG are allowed.');
        }

        if ($file['size'] < 10240 || $file['size'] > 3145728) {
            throw new InvalidArgumentException('File size must be between 10KB and 3MB.');
        }

        if (!empty($file['name']) && $file['error'] === UPLOAD_ERR_OK) {
            $uniqueFileName = uniqid('img_', true) . '_' . basename($file['name']);
            $targetPath = PRODUCT_IMAGES_UPLOAD_DIR . $uniqueFileName;

            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            return $uniqueFileName;
        }

        throw new RuntimeException('Error: Invalid file or missing fields.');
    }

    // Clean string to alphanumeric only
    function cleanAlphanumeric(string $input): string {
        return preg_replace('/[^a-zA-Z0-9]/', '', $input) ?? '';
    }

    // Convert array to JSON safely
    function arrayToJson(array $inputArray): string {
        if (empty($inputArray)) {
            throw new InvalidArgumentException('Input array cannot be empty.');
        }

        try {
            return json_encode($inputArray, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (JsonException $e) {
            throw new RuntimeException('Failed to convert array to JSON: ' . $e->getMessage());
        }
    }

    // Decode JSON safely
    function decodeJson(string $jsonData): array {
        try {
            $decoded = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
            if (empty($decoded)) {
                throw new InvalidArgumentException('Decoded JSON is empty or invalid.');
            }
            return $decoded;
        } catch (JsonException $e) {
            error_log('JSON decoding error: ' . $e->getMessage());
            throw new RuntimeException('Failed to decode JSON: ' . $e->getMessage());
        }
    }

    // Base project root (two levels up from /lib)
    define('PROJECT_ROOT', dirname(__DIR__, 1)); // C:\xampp\htdocs\PDO_MVC_Ecommerce

    // Path Constants
    define('BACK_END_PATH', PROJECT_ROOT . '/backend/');
    define('FRONT_END_PATH', PROJECT_ROOT . '/frontend/');
    define('MASTER_CONTROLLER', PROJECT_ROOT . '/controller/');
    define('JS_CDN', PROJECT_ROOT . '/js_cdn/');

    define('BKEND_CSS_DIR', 'bkend_css/bkend_css.css');

    const NEW_ARRIVALS = "New Arrivals";
    const BEST_SELLER = "Best Seller";
    
    // Messages
    const NO_RECORED_FOUND = "No Record Found.";
    const CATEGORIE_MASTER_DETAILS = "Manage categories in the category master.";
    const USER_MASTER_DETAILS = "Manage user in the user master.";
    const PRODUCT_MASTER_DETAILS = "Manage products in the product master.";
    const DUPLICATE_PRODUCT_NAME = "Duplicate product name. Only uniqe product name are allowed to Insert/Update.";
    const INVALID_PRODUCT_DATA = "<div class='alert alert-danger'>Invalid Product Data.</div>";
    const INVALID_PRODUCT_ID = "<div class='alert alert-danger'>Invalid Product ID.</div>";
    const PRODUCT_NAME_REQUIRED = "<div class='alert alert-danger'>Product Name is required.</div>";

    // Image & File Operations
    const FAILED_TO_FILE_REMOVE_DIR = "Failed to delete the file.";
    const FILE_NOT_FOUND_DIR = "File not found.";

    // Success Messages
    const PRODUCT_ADDED_SUCCESSFULLY_MSG = "<div class='alert alert-success'>Product added successfully.</div>";
    const PRODUCT_UPDATED_SUCCESSFULLY_MSG = "<div class='alert alert-success'>Product update successfully.</div>";
  

    // Failed Messages
    const FAILED_PRODUCT_ADDED_MSG = "<div class='alert alert-danger'>Product Insert failed, something went wrong.</div>";
    const FAILED_PRODUCT_UPDATE_MSG = "<div class='alert alert-danger'>Product not added, something went wrong.</div>";
    const FAILED_TO_DELETE_PRODUCT = "<div class='alert alert-danger'>Failed to delete product.</div>";
    const PRODUCT_NOT_FOUND = "<div class='alert alert-danger' role='alert'>Product not found</div>";

    //Frontend Message

    const ABOUT_US = "MTechnology is a forward-thinking tech company dedicated to innovation and excellence. We specialize in delivering cutting-edge solutions that empower businesses and enhance everyday experiences. Our mission is to shape the future with technology that truly matters.";
    /*
        1. Ternary Operator (Before PHP 7)
        $var = isset($var) ? $var : "default";

        2. Null Coalescing Operator (PHP 7+)
        $var = $var ?? "default";

        3. Null Coalescing Assignment Operator (PHP 7.4+)
        $var ??= "default";

        4. Ternary Operator Shorthand (PHP 5.3+)
        isset($var) ?: $var = 'default';
    */
?>
