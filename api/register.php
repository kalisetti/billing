<?php
require_once 'config.php';
require_once 'request-parser.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = parseRequestBody();

    // Get registration data from the request
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert the user into the database
        $stmt = $conn->prepare('INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $email, $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Registration successful
            echo json_encode(['message' => 'User registered successfully']);
        } else {
            // Registration failed
            echo json_encode(['error' => 'Failed to register user']);
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
