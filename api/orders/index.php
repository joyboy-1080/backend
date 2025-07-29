<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Clean CORS headers - only set once
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
    switch($method) {
        case 'GET':
            // Check if it's a search request
            if(isset($_GET['mobile'])) {
                $mobile = $_GET['mobile'];
                $stmt = $order->searchByMobile($mobile);
            } else {
                // Get all orders
                $stmt = $order->getAll();
            }

            if($stmt === false) {
                http_response_code(500);
                echo json_encode(array("message" => "Database query failed"));
                exit();
            }

            $num = $stmt->rowCount();

            if($num > 0) {
                $orders_arr = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $order_item = array(
                        "id" => $id,
                        "customerName" => $customer_name,
                        "mobileNo" => $mobile_no,
                        "customerAddress" => $customer_address,
                        "productList" => json_decode($product_list),
                        "amount" => floatval($amount),
                        "status" => $status,
                        "dateTime" => $date_time
                    );
                    array_push($orders_arr, $order_item);
                }
                
                http_response_code(200);
                echo json_encode($orders_arr);
            } else {
                http_response_code(200);
                echo json_encode(array());
            }
            break;

        case 'POST':
            // Get raw input and decode
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);

            if(!empty($data['name']) && !empty($data['mobile']) && !empty($data['address']) && 
               !empty($data['productList']) && !empty($data['totalAmount'])) {
                
                $order->customer_name = $data['name'];
                $order->mobile_no = $data['mobile'];
                $order->customer_address = $data['address'];
                $order->product_list = json_encode($data['productList']);
                $order->amount = $data['totalAmount'];
                $order->status = 'Pending Payment';

                if($order->create()) {
                    // Return the created order
                    $stmt = $order->getById($order->id);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    $created_order = array(
                        "id" => $row['id'],
                        "customerName" => $row['customer_name'],
                        "mobileNo" => $row['mobile_no'],
                        "customerAddress" => $row['customer_address'],
                        "productList" => json_decode($row['product_list']),
                        "amount" => floatval($row['amount']),
                        "status" => $row['status'],
                        "dateTime" => $row['date_time']
                    );

                    http_response_code(201);
                    echo json_encode($created_order);
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to create order."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array(
                    "message" => "Unable to create order. Data is incomplete.",
                    "received" => $data
                ));
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(array("message" => "Method $method not allowed."));
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Server error: " . $e->getMessage()));
}
?>
