<?php
$order_id = $_GET['id']; // Get the order ID from the URL

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "login";
$conn = new mysqli($host, $user, $password, $database);

// Fetch order details
$query = "
    SELECT oi.quantity, oi.price, p.name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

// Display order details
while ($row = $result->fetch_assoc()) {
    echo "Product: " . $row['name'] . " | Quantity: " . $row['quantity'] . " | Price: ₹" . number_format($row['price'], 2) . "<br>";
}

$conn->close();
?>
