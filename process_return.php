<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve ID from POST or GET and validate it
$id = null;

if (isset($_POST['id'])) {
    $id = $_POST['id'];
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
}

// Validate that the ID is a valid integer
if ($id !== null && is_numeric($id)) {
    // Prepare the SQL statement with the ID
    $sql = "SELECT * FROM returns WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Check if the prepare statement was successful
    if ($stmt === false) {
        die("Error preparing the query: " . $conn->error);
    }

    // Bind the parameter
    $stmt->bind_param("i", $id);

    // Execute the query
    if (!$stmt->execute()) {
        die("Error executing the query: " . $stmt->error);
    }

    // Get the result
    $result = $stmt->get_result();

    // Check if there are any matching rows
    if ($result->num_rows > 0) {
        // Fetch and display the return record
        while ($row = $result->fetch_assoc()) {
            echo "<p>Return record found for ID: " . htmlspecialchars($row['id']) . "</p>";
            echo "<p>Reason for Return: " . htmlspecialchars($row['reason']) . "</p>";
            echo "<p>Return Method: " . htmlspecialchars($row['return_method']) . "</p>";
            // Display more fields as needed
        }
    } else {
        echo "No return record found for ID: " . htmlspecialchars($id);
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid or missing ID.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Order</title>
    <style>
        /* Your existing styles go here */
    </style>
</head>
<body>

    <!-- Your Header -->
    <header>
        <h1>Returns and Orders</h1>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Return Request Form -->
        <div class="return-form">
            <h2>Request a Return</h2>
            <!-- Form to submit Order ID -->
            <form action="return.php" method="POST">
                <label for="id">Order ID</label>
                <input type="text" name="id" placeholder="Enter Order ID" required>
                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Orders Section (Can be dynamic or static) -->
        <div class="orders-section">
            <!-- Add your order listing here, for example -->
            <div class="order-card">
                <h3>Order #12345</h3>
                <p><strong>Date:</strong> 2024-12-01</p>
                <p><strong>Items:</strong> Wireless Headphones</p>
                <p><strong>Total:</strong> ₹2,499</p>
                <p><strong>Status:</strong> Delivered</p>
                <button onclick="alert('Return request initiated for Order #12345');">Initiate Return</button>
            </div>
        </div>

    </div>

</body>
</html>
