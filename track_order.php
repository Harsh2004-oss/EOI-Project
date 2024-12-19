<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the order tracking request
$order_status = null;
if (isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];

    // Retrieve the order details based on the order ID
    $sql = "SELECT o.id, o.status, o.order_date, p.name AS product_name, o.quantity, o.shipping_address
            FROM orders o
            JOIN products p ON o.product_id = p.id
            WHERE o.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order_status = $result->fetch_assoc();
    } else {
        $error_message = "Order not found.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: #fff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    text-align: center;
}

h1 {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #333;
}

.track-order-form {
    margin-bottom: 20px;
}

.track-order-form label {
    font-weight: bold;
    display: block;
    margin-bottom: 10px;
    color: #555;
}

.track-order-form input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 20px;
    font-size: 16px;
}

.track-btn {
    background-color: #f0c14b;
    color: black;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    width: 100%;
}

.track-btn:hover {
    background-color: #d1a538;
}

.order-status {
    background-color: #f1f1f1;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.order-status h2 {
    margin-bottom: 15px;
    font-size: 1.5rem;
    color: #333;
}

.status-text {
    font-weight: bold;
    color: #4CAF50; /* Green for positive status */
}

.status-text.cancelled {
    color: #f44336; /* Red for cancelled orders */
}

.status-text.processing {
    color: #ff9800; /* Orange for orders that are being processed */
}

.status-text.shipped {
    color: #2196F3; /* Blue for shipped orders */
}

.status-text.delivered {
    color: #4CAF50; /* Green for delivered orders */
}
</style>
<body>
    <header>
        <h1>Track Your Order</h1>
    </header>

    <main>
        <div class="order-track-container">
            <form action="track_order.php" method="POST">
                <label for="order_id">Enter Order ID:</label>
                <input type="text" id="order_id" name="order_id" required placeholder="Order ID">
                <button type="submit">Track Order</button>
            </form>

            <?php if (isset($order_status)): ?>
                <div class="order-details">
                    <h2>Order Details</h2>
                    <p><strong>Order ID:</strong> <?php echo $order_status['id']; ?></p>
                    <p><strong>Product:</strong> <?php echo htmlspecialchars($order_status['product_name']); ?></p>
                    <p><strong>Quantity:</strong> <?php echo $order_status['quantity']; ?></p>
                    <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order_status['shipping_address']); ?></p>
                    <p><strong>Order Date:</strong> <?php echo $order_status['order_date']; ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($order_status['status']); ?></p>
                </div>
            <?php elseif (isset($error_message)): ?>
                <p><?php echo $error_message; ?></p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
