<?php
require_once 'config.php';

// Get login data from the request
$email = $_POST['email'];
$password = $_POST['password'];

// Retrieve the user from the database
$stmt = $conn->prepare('SELECT id, password FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the user exists and verify the password
if ($user && password_verify($password, $user['password'])) {
  // User login successful
  echo json_encode(['message' => 'Login successful']);
} else {
  // Invalid credentials
  echo json_encode(['error' => 'Invalid credentials']);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
