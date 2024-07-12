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
                // echo "mongodb connected successfully";
                $collection = $mongoClient->adplaytechnology->campaigns;

                try {
                    // Insert data into MongoDB
                    $insertResult = $collection->insertOne($data);

                    // Check if the insert was successful
                    if ($insertResult->getInsertedCount() > 0) {
                        // Generate a response (success message)
                        $response = [
                            'status' => 'success',
                            'message' => 'Data successfully inserted into MongoDB',
                            'mongo_id' => (string) $insertResult->getInsertedId()
                        ];
                        // echo "response message";
                        // print_r($response);

                        // MySQL connection details
                        $mysqlHost = '127.0.0.1';
                        $mysqlDb = 'bookstore';
                        $mysqlUser = 'pranto';
                        $mysqlPass = '';

                        try {
                            // Connect to MySQL
                            $pdo = new PDO("mysql:host=$mysqlHost;dbname=$mysqlDb", $mysqlUser, $mysqlPass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Prepare the SQL statement
                            $stmt = $pdo->prepare("INSERT INTO campaigns (code, name, goal, starts, ends, campaign_type_id) VALUES (?, ?, ?, ?, ?, ?)");

                            // Insert each campaign into the MySQL database
                            foreach ($data['campaigns'] as $campaign) {
                                $stmt->execute([
                                    uniqid(), // Generate a unique code for each campaign
                                    $campaign['campaign_name'],
                                    $campaign['campaign_goal'],
                                    $campaign['campaign_starts'],
                                    $campaign['campaign_ends'],
                                    getCampaignTypeId($pdo, $campaign['campaign_type'])
                                ]);
                            }

                            // Return success response
                            echo json_encode($response);
                            exit;
                        } catch (PDOException $e) {
                            http_response_code(500);
                            echo json_encode(['error' => $e->getMessage()]);
                            exit;
                        }

                        // Function to get the campaign type ID from the campaign_types table
                        function getCampaignTypeId($pdo, $typeName) {
                            $stmt = $pdo->prepare("SELECT id FROM campaign_types WHERE type_name = ?");
                            $stmt->execute([$typeName]);
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            if ($row) {
                                return $row['id'];
                            } else {
                                // Insert new campaign type if not exists
                                $stmt = $pdo->prepare("INSERT INTO campaign_types (type_name) VALUES (?)");
                                $stmt->execute([$typeName]);
                                return $pdo->lastInsertId();
                            }
                        }

                    } else {
                        throw new Exception('Failed to insert data into MongoDB');
                    }
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => $e->getMessage()]);
                    exit;
                }
        
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
