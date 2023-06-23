<?php
	error_reporting(E_ALL);
	ini_set('display_startup_errors', 1);
	ini_set('display_errors', '1');

	define('DB_USERNAME', 'admin');
	define('DB_PASSWORD', 'admin');
	define('DB_NAME', 'billing');
	// define('DB_SERVER', 'localhost:/tmp/mysql.sock');
	define('DB_SERVER', '127.0.0.1');

	// Connect to MySQL database
	$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	// Check database connection
	if($conn->connect_errno){
		die("ERROR: Unable to connect to database. " . $conn->connect_error);
	}
?>