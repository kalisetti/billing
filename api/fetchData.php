<?php
require_once 'App/Database/DB.php';

use App\Database\DB;

// Get the table name from the query parameter
$tableName = $_GET['table'];
$limit = $_GET['limit'];

$columns = [];
$rows = [];
try {
    $db = DB::getInstance();

    // Fetch the columns of the table
    $columns = $db->getColumns($tableName);

    // Fetch the rows from the table
    $result = $db->sql("SELECT * FROM $tableName ORDER BY modified_on DESC LIMIT $limit");
    foreach ($result as $row) {
      $rows[] = $row;
    }

    $response = [
      'columns' => $columns,
      'rows' => $rows
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
