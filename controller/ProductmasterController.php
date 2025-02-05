<?php
include_once("../config/connection.php");
include_once("../lib/function.inc.php");
include_once("../model/ProductmasterModel.php");
include_once("../controller/ProductmasterController.php");

class ProductmasterController extends ProductmasterModel {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    public function updateProduct()
    {
        $productData = [
            'Product_Id' => filter_input(INPUT_POST, 'Product_Id', FILTER_SANITIZE_NUMBER_INT),
            'Product_CategorieId' => filter_input(INPUT_POST, 'Product_CategorieId', FILTER_SANITIZE_NUMBER_INT),
            'Product_Mrp' => filter_input(INPUT_POST, 'Product_Mrp', FILTER_SANITIZE_NUMBER_INT),
            'Product_SellPrice' => filter_input(INPUT_POST, 'Product_SellPrice', FILTER_SANITIZE_NUMBER_INT),
            'Product_Qty' => filter_input(INPUT_POST, 'Product_Qty', FILTER_SANITIZE_NUMBER_INT),
            'Product_Img' => filter_input(INPUT_POST, 'Product_Img', FILTER_SANITIZE_STRING),
            'Product_Name' => filter_input(INPUT_POST, 'Product_Name', FILTER_SANITIZE_STRING),
            'Product_ShortDesc' => filter_input(INPUT_POST, 'Product_ShortDesc', FILTER_SANITIZE_STRING),
            'Product_LongDesc' => filter_input(INPUT_POST, 'Product_LongDesc', FILTER_SANITIZE_STRING),
            'Product_MetaTitle' => filter_input(INPUT_POST, 'Product_MetaTitle', FILTER_SANITIZE_STRING),
            'Product_MetaDesc' => filter_input(INPUT_POST, 'Product_MetaDesc', FILTER_SANITIZE_STRING)
        ];

        $updateProduct = $this->ProductmasterModel->updateProduct($productData);
        if (!empty($updateProduct)) {
            echo "Product updated successfully.";
        } else {
            echo "Failed to update product Controller.";
        }
    }

}

// Database obj
$database = new Database();
$db = $database->getConnection();

?>
