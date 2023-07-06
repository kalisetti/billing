<?php
require_once 'App/Database/DB.php';
require_once 'App/Utils/requestParser.php';

use App\Database\DB;
use App\Utils;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $_POST = Utils\parseRequestBody();

  // Get login data from the request
  $email = $_POST['email'];
  $password = $_POST['password'];
  
  try {
    $db = DB::getInstance();
    $query = 'SELECT name, password FROM users WHERE name = ?';
    $params = [$email];
    $rows = $db->sql($query, $params);
    $user = $rows[0];

    // Check if the user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
      // User login successful
      session_start();
      $_SESSION['user_id'] = $email;
      
      $response = array(
        'success' => true,
        'error' => '',
        'message' => 'Login successful'
      );
    } else {
      // Invalid credentials
      $response = array(
        'success' => false,
        'error' => '',
        'message' => 'Invalid credentials'
      );
    }
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