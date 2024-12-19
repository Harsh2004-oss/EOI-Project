<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "login";

// Corrected connection parameters
$conn = new mysqli($host, $user, $pass, $db);

// Check for connection error
if ($conn->connect_error) {
    echo "Failed to connect to DB: " . $conn->connect_error;
}
?>
