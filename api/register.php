<?php
require_once 'App/Database/DB.php';
require_once 'App/Utils/requestParser.php';

use App\Database\DB;
use App\Utils;

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = Utils\parseRequestBody();

    // Get registration data from the request
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert the user into the database
        $db = DB::getInstance();
        $query = 'INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)';
        $params = [$email, $username, $email, $hashedPassword];
        $rows = $db->sql($query, $params);

        $response = array(
          'success' => true,
          'error' => '',
          'message' => 'User registered successfully'
        );
    } catch (Exception $e) {
        // Handle any exceptions that occured during the execution of the SQL statement
        $response = array(
          'success' => false,
          'error' => array('errcd' => $e->getCode(), 'errmsg' => $e->getMessage()),
          'message' => ''
        );
    }
} else {
    // Return an error response for non-POST requests
    $response = array(
      'success' => false,
      'error' => '',
      'message' => 'Invalid request method'
    );
}

// Set the content type and return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
