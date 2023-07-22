<?php

require_once 'App/Database/DB.php';
require_once 'App/Utils/utils.php';

use App\Database\DB;
use App\Utils;

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Extract the table name and record ID from the URL parameters
$tableName = Utils\scrub($_GET['table']);

// Perform CRUD operations based on the request method
$db = DB::getInstance();
switch ($requestMethod) {
    case 'GET':
        // Retrieve the record from the database
        $payload = $_GET;
        $response = getRecord($db, $tableName, $payload);
        break;
    case 'POST':
        $payload = Utils\parseRequestBody();
        $response = saveRecord($db, $tableName, $payload);
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

function getRecord($db, $tableName, $payload) {
    try {
        $recordId = $payload['recordId'];
        $rows = $db->sql("SELECT * FROM $tableName WHERE name = ?", [$recordId]);

        return array(
            'success' => true,
            'error' => '',
            'message' => '',
            'rows' => $rows
        );
    } catch (Exception $e) {
        return array(
            'success' => false,
            'error' => array('errcd' => $e->getCode(), 'errmsg' => $e->getMessage()),
            'message' => ''
        );
    }
}

function generateID($db, $tableName, $payload) {
    $id = 'name';
    if ($tableName === 'subscription_plan') {
        $id = $payload['plan_name'];
    } elseif ($tableName === 'customers') {
        $id = $payload['customer_name'];
    } elseif ($tableName === 'subscription') {
        $id = Utils\makeAutoName($db, 'SUB');
    } elseif ($tableName === 'invoice_entry') {
        $id = Utils\makeAutoName($db, 'INVE');
    } elseif ($tableName === 'invoice') {
        $id = Utils\makeAutoName($db, 'INV');
    } elseif ($tableName === 'payment') {
        $id = Utils\makeAutoName($db, 'PAY');
    }
    return $id;
}
function constructStatement($db, $tableName, $payload) {
    $columns = array_keys($payload);
    $values = array_values($payload);
    $statement = '';

    if (isset($payload['name'])) {
        $columnPlaceholders = array_map(function($column) {
            return "$column=?";
        }, $columns);

        $columnPlaceholdersString = implode(', ', $columnPlaceholders);
        $statement = "UPDATE $tableName SET $columnPlaceholdersString WHERE name=?";
        $values[] = $payload['name'];
        $id = $payload['name'];
    } else {
        $columnString = implode(', ', $columns);
        $columnString = 'name, ' . $columnString;
        $valuePlaceholders = array_fill(0, count($values), '?');
        $valuePlaceholdersString = implode(', ', $valuePlaceholders);
        $valuePlaceholdersString = '?, ' . $valuePlaceholdersString;
        $statement = "INSERT INTO $tableName ($columnString) VALUES ($valuePlaceholdersString)";
        $id = generateID($db, $tableName, $payload);
        array_unshift($values, $id);
    }

    return [$columns, $values, $statement, $id];
}
function saveRecord($db, $tableName, $payload) {
    list($columns, $values, $statement, $name) = constructStatement($db, $tableName, $payload);
    $payload['recordId'] = $name;
    // Insert a new record into the database
    try {
        $rows = $db->sql($statement, $values);
        return getRecord($db, $tableName, $payload);
    } catch (Exception $e) {
        return array(
            'success' => false,
            'error' => array('errcd' => $e->getCode(), 'errmsg' => $e->getMessage()),
            'message' => ''
        );
    }
}