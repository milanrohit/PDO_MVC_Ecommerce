<?php

class IncFunctions {
    // Debugging functions with exit
    public function _dx($arr): void {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        exit;
    }

    // Convert array to object
    public function arrayToObject(array $array): object {
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
    public function objectToArray($object): array {
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
    public function fetchAssociative(): int {
        return PDO::FETCH_ASSOC;
    }

    // Function to handle redirection
    public function redirect(string $url): void {
        header('Location: ' . $url);
        exit();
    }

    // Function to sanitize input
    public function sanitizeString(string $input): string {
        $input = trim($input);

        // Remove HTML tags
        $sanitized = strip_tags($input);

        // Encode special characters
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');

        return $sanitized;
    }

    // Function to handle redirection with an error message
    public function redirectWithError(string $url, string $error): void {
        $_SESSION['error_msg'] = $error;
        header('Location: ' . $url);
        exit();
    }

    // Function to get file information
    public function getFileInfo($filePath) {
        $fileInfo = new SplFileInfo($filePath);
        return [
            'name' => $fileInfo->getFilename(),
            'path' => $fileInfo->getRealPath(),
            'type' => $fileInfo->getType(),
            'directory' => $fileInfo->getPath()
        ];
    }

    // Function to hash the password
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Function to verify the password
    public function verifyPassword($enteredPassword, $storedHashedPassword) {
        return password_verify($enteredPassword, $storedHashedPassword);
    }

    // Function to display copyright information
    public function copyRight() {
        $yr = date("Y");
        echo "<b>Copyright &copy; $yr MilanRohit</b>";
    }

    // Function to display designer and developer information
    public function designdevelopeby() {
        echo "<b>Designed & Developed by <a href='#'>MilanRohit</a></b>";
    }

    // Function to upload an image
    public function imageUpload($productImg): string {
        try {
            if (isset($productImg) && $productImg['error'] === UPLOAD_ERR_OK) {
                $uploadDir = self::PRODUCT_IMAGES_UPLOAD_DIR; // Directory to save uploaded images
                $uploadFile = $uploadDir . basename($productImg['name']);

                // Allowed file types
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                $fileType = mime_content_type($productImg['tmp_name']);

                // Check file type
                if (!in_array($fileType, $allowedTypes)) {
                    $msg = 'Only JPG, JPEG, and PNG files are allowed.';
                    throw new ImageUploadException($msg);
                }

                // Check file size (min 10KB, max 3MB)
                $fileSize = $productImg['size'];
                if ($fileSize < 10240 || $fileSize > 3145728) {
                    $msg = 'File size must be between 10KB and 3MB.';
                    throw new ImageUploadException($msg);
                }

                if (move_uploaded_file($productImg['tmp_name'], $uploadFile)) {
                    return $uploadFile;
                } else {
                    $msg = 'Failed to upload image.';
                    throw new ImageUploadException($msg);
                }
            }
            return ''; // If no image is uploaded, set it as empty
        } catch (ImageUploadException $e) {
            error_log($e->errorMessage());
            return ''; // Return empty string on failure
        }
    }
}
    // Path constants
    const BackendPath="/PDO_MVC_Ecommerce/backend/"; // BackendPath
    const FrontendPath="/PDO_MVC_Ecommerce/frontend/"; // FrontendPath
    const controller="/PDO_MVC_Ecommerce/controller/"; // controller
    const NO_RECORED_FOUND="No Record Found."; // NO_RECORED_FOUND
    const Categorie_master_details="This is a categorie master where you can add & manage categories ."; // Categorie master details
    const PRODUCTMASTERDETAILS="This is a product master where you can add & manage product ."; // Product master details
    const DUPLICATE_PRODUCT_NAME = "Duplicate Found. Product not inserted/updated."; // Product master
    const PRODUCT_IMAGES_UPLOAD_DIR = "images/productimanges/"; // Product master
?>
