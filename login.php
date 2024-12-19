<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "login";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the user exists
    $query = "SELECT id, name FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    // If user exists, log them in
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $name);
        $stmt->fetch();
        
        // Store user information in session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;

        // Check if there's a redirect parameter
        $redirect_url = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php'; // Default to 'index.php'

        // Redirect to the target page
        header("Location: $redirect_url");
        exit();
    } else {
        echo "Invalid email or password.";
    }
}
?>
