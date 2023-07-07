<?php

require_once 'App/Database/DB.php';
require_once 'App/Utils/requestParser.php';

use App\Database\DB;
use App\Utils;

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Extract the table name and record ID from the URL parameters
$tableName = $_GET['table'];
$recordId = $_GET['recordId'];

// Perform CRUD operations based on the request method
$db = DB::getInstance();
switch ($requestMethod) {
    case 'GET':
        // Retrieve the record from the database
        try {
            $rows = $db->sql("SELECT * FROM $tableName WHERE name = ?", [$recordId]);

            $response = array(
                'success' => true,
                'error' => '',
                'message' => '',
                'rows' => $rows
            );
        } catch (Exception $e) {
            $response = array(
                'success' => false,
                'error' => array('errcd' => $e->getCode(), 'errmsg' => $e->getMessage()),
                'message' => ''
            );
        }
        break;
    case 'POST':
        // Insert a new record into the database
        break;
    case 'PUT':
        // Update the existing record in the database
        break;
    case 'DELETE':
        // Delete the record from the database
        break;
    default:
        // Invalid request method
        http_response_code(405);
        break;
}

// Set the content type and return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

