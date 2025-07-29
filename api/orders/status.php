<?php
// Clean CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';
include_once '../../models/Order.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    http_response_code(500);
    echo json_encode(array("message" => "Database connection failed"));
    exit();
}

$order = new Order($db);
$method = $_SERVER['REQUEST_METHOD'];

try {
    if($method == 'PUT') {
        // Get order ID from URL
        $request_uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($request_uri, PHP_URL_PATH);
        $path_parts = explode('/', $path);
        $order_id = $path_parts[count($path_parts) - 2]; // Get ID before 'status'

        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if(!empty($order_id) && !empty($data['status'])) {
            $order->id = $order_id;
            $order->status = $data['status'];

            if($order->updateStatus()) {
                http_response_code(200);
                echo json_encode(array("message" => "Order status was updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update order status."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to update order status. Data is incomplete."));
        }
    } else {
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Server error: " . $e->getMessage()));
}
?>
