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


// Get the user's ID from session
$user_id = $_SESSION['user_id'];

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty. Please add products to the cart before checking out.";
    exit;
}

// Initialize total amount
$total_amount = 0;

// Calculate the total amount based on cart contents
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Get product details
    $query = "SELECT name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($name, $price);
    $stmt->fetch();

    if ($name && $price) {
        // If product is found, calculate total price
        $total_amount += $price * $quantity;
    } else {
        // If product is not found in the database
        echo "Product ID: $product_id not found in database. Please remove this item from your cart.";
        exit;
    }
}

// Insert order into orders table
$status = 'Pending'; // Set the initial status as 'Pending'
$order_id = 0; // Order ID variable

$insert_order = "INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, ?)";
$stmt = $conn->prepare($insert_order);
$stmt->bind_param("ids", $user_id, $total_amount, $status);
$stmt->execute();
$order_id = $conn->insert_id; // Get the ID of the newly created order

// Insert products into order_items table
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Get the product's price
    $query = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();

    // Insert product details into order_items table
    $insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_item);
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
    $stmt->execute();
}

// Clear the cart after a successful order placement
unset($_SESSION['cart']);

// Display a success message with the order ID and link to order details
echo "Your order has been placed successfully. Order ID: " . $order_id;
echo "<br><a href='order_details.php?id=" . $order_id . "'>View Order Details</a>";

// Close the database connection
$conn->close();
?>
