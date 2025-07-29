<?php
// Clean CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';
include_once '../../models/Product.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    http_response_code(500);
    echo json_encode(array("message" => "Database connection failed"));
    exit();
}

$product = new Product($db);
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            // Get all products
            $stmt = $product->getAll();
            $num = $stmt->rowCount();

            if($num > 0) {
                $products_arr = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $product_item = array(
                        "id" => $id,
                        "name" => $name,
                        "image" => $image,
                        "originalPrice" => floatval($original_price),
                        "currentPrice" => floatval($current_price),
                        "category" => $category,
                        "subcategory" => $subcategory,
                        "isSoldOut" => (bool)$is_sold_out
                    );
                    array_push($products_arr, $product_item);
                }
                
                http_response_code(200);
                echo json_encode($products_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No products found."));
            }
            break;

        case 'POST':
            // Create product
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);

            if(!empty($data['name']) && !empty($data['originalPrice']) && !empty($data['currentPrice']) && 
               !empty($data['category']) && !empty($data['subcategory'])) {
                
                $product->name = $data['name'];
                $product->image = $data['image'] ?? '/placeholder.svg?height=200&width=280';
                $product->original_price = $data['originalPrice'];
                $product->current_price = $data['currentPrice'];
                $product->category = $data['category'];
                $product->subcategory = $data['subcategory'];
                $product->is_sold_out = $data['isSoldOut'] ?? false;

                if($product->create()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Product was created.", "id" => $product->id));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to create product."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
            }
            break;

        case 'PUT':
            // Update product
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);

            if(!empty($data['id'])) {
                $product->id = $data['id'];
                $product->name = $data['name'];
                $product->image = $data['image'];
                $product->original_price = $data['originalPrice'];
                $product->current_price = $data['currentPrice'];
                $product->category = $data['category'];
                $product->subcategory = $data['subcategory'];
                $product->is_sold_out = $data['isSoldOut'] ?? false;

                if($product->update()) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Product was updated."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to update product."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Unable to update product. Data is incomplete."));
            }
            break;

        case 'DELETE':
            // Delete product
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);

            if(!empty($data['id'])) {
                $product->id = $data['id'];

                if($product->delete()) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Product was deleted."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to delete product."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Unable to delete product. Data is incomplete."));
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(array("message" => "Method not allowed."));
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Server error: " . $e->getMessage()));
}
?>
