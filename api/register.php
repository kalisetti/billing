<?php
require_once 'config.php';

// Get registration data from the request
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the user into the database
$stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $name, $email, $hashedPassword);
$stmt->execute();

// Close the database connection
$stmt->close();
$conn->close();

// Return a success message
echo json_encode(['message' => 'User registered successfully']);
?>
