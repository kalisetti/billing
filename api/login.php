<?php
require_once 'config.php';
require_once 'request-parser.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $_POST = parseRequestBody();

  // Get login data from the request
  $email = $_POST['email'];
  $password = $_POST['password'];

  try {
    // Retrieve the user from the database
    $stmt = $conn->prepare('SELECT name, password FROM users WHERE name = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
      // User login successful
      session_start();
      $_SESSION['user_id'] = $email;
      
      echo json_encode(['message' => 'Login successful']);
    } else {
      // Invalid credentials
      echo json_encode(['error' => 'Invalid credentials']);
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
  } catch (Exception $e) {
    // Handle any exceptions that occured during the execution of the SQL statement
    echo json_encode(['error' => 'Error '.$e->getCode().' : '.$e->getMessage()]);
  }
} else {
  // Return an error response for non-POST requests
  echo json_encode(['error' => 'Invalid request method']);
}
?>
