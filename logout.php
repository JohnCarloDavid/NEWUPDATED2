<?php
// Start the session
session_start();
include('db_connection.php');

// Destroy the session
session_unset();  // Clear all session variables
session_destroy();  // Destroy the session itself

// Redirect to login page or another page
header("Location: login.php");  // Change 'login.php' to your desired redirection page
exit();
?>
