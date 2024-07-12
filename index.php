<?php
require 'vendor/autoload.php';

use MongoDB\Client;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // Get the JSON payload from the POST request
    $jsonPayload = file_get_contents('php://input');
    
    // Decode the JSON payload into a PHP array
    $data = json_decode($jsonPayload, true);

    // print_r($data);
    
    if (json_last_error() === JSON_ERROR_NONE) {

        if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($data['bidder_request']) && is_bool($data['bidder_request']) &&
                isset($data['campaigns']) && is_array($data['campaigns'])) {
        
                // Proceed to store data in MongoDB
                // MongoDB connection details
                $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
                echo "mongodb connected successfully";
                
        
            } else {
                // Invalid JSON structure, return an error response
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON structure']);
                exit;
            }
        }
    } 
    else 
    {
        // Invalid JSON, return an error response
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }
}
else 
{
    // Invalid request method, return an error response
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}
?>
