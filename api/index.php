<?php
// Simple router - no CORS headers here since individual files handle them
$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Parse the URL to get the endpoint
$path = parse_url($request_uri, PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));

// Find the 'api' part and get what comes after it
$api_index = array_search('api', $path_parts);
if ($api_index !== false && isset($path_parts[$api_index + 1])) {
    $endpoint = $path_parts[$api_index + 1];
    
    // Handle different endpoints
    switch($endpoint) {
        case 'products':
            include_once __DIR__ . '/products.php';
            break;
            
        case 'orders':
            // Check if it's a status update request
            if (isset($path_parts[$api_index + 3]) && $path_parts[$api_index + 3] == 'status') {
                include_once __DIR__ . '/orders/status.php';
            } else {
                include_once __DIR__ . '/orders.php';
            }
            break;
            
        default:
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");
            http_response_code(404);
            echo json_encode(array("message" => "Endpoint not found: " . $endpoint));
            break;
    }
} else {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code(404);
    echo json_encode(array("message" => "Invalid API endpoint"));
}
?>
