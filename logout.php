<?php
session_start();

// Destroy all session data
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the homepage or login page
header("Location: explore.html");  // You can replace 'index.php' with your homepage or login page
exit();
?>
