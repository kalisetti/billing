<?php
require_once 'App/Database/DB.php';
require_once 'App/Utils/utils.php';

use App\Database\DB;
use App\Utils;

// Get the table name from the query parameter
$customer = Utils\scrub($_GET['customer']);

$columns = [];
$rows = [];
try {
    $db = DB::getInstance();
    
    // Fetch the columns of the table
    $columns = $db->getColumns('invoice');

    // Fetch the rows from the table
    $result = $db->sql("SELECT * FROM invoice WHERE customer = ? AND IFNULL(outstanding,0) > 0", [$customer]);
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
