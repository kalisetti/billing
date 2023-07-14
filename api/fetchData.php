<?php
require_once 'App/Database/DB.php';
require_once 'App/Utils/utils.php';

use App\Database\DB;
use App\Utils;

// Get the table name from the query parameter
$tableName = Utils\scrub($_GET['table']);
$limit = $_GET['limit'];
$offset = $_GET['offset'];

$columns = [];
$rows = [];
try {
    $db = DB::getInstance();
    
    // Fetch the columns of the table
    $columns = $db->getColumns($tableName);

    // Fetch the rows from the table
    $result = $db->sql("SELECT * FROM $tableName ORDER BY LENGTH(name),name LIMIT $offset,$limit");
    $response = [
      'columns' => $columns,
      'rows' => $result
    ];
} catch (Exception $e) {
    // Handle any exceptions that occured during the execution of the SQL statement
    $response = array(
      'success' => false,
      'error' => array('errcd' => $e->getCode(), 'errmsg' => $e->getMessage()),
      'message' => ''
    );
}

// Set the content type and return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
