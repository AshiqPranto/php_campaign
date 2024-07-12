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

    print_r($data);
    
    
}
else 
{
    // Invalid request method, return an error response
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}
?>
