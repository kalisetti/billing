<?php
session_start();

// Clear the session data
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
// header('Location: /login');
exit();

