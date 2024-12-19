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

// If the product_id is passed through the URL, add the product to the cart
if (isset($_GET['product_id']) && isset($_SESSION['user_id'])) {
    $product_id = (int)$_GET['product_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the product is already in the cart for this user
    $sql_check = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $user_id, $product_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // If the product is already in the cart, update the quantity
        $sql_update = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $user_id, $product_id);
        $stmt_update->execute();
    } else {
        // If the product is not in the cart, insert it
        $sql_insert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $quantity = 1;  // Default quantity
        $stmt_insert->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt_insert->execute();
    }

    // Redirect to the cart page after adding the item
    header("Location: cart.php");
    exit();
}

// Retrieve products in the cart
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// If cart is not empty, fetch products from the database
if (!empty($cart)) {
    $ids = implode(",", $cart); // Use product IDs from the cart session array
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
} else {
    $result = null;
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    <header>
        <h1>Your Cart</h1>
    </header>
    <main>
        <div class="cart-container">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <div class="cart-item">
                        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                        <p>Price: ₹<?php echo number_format($product['price'], 2); ?></p>
                        <!-- Buy Now Button -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="checkout.php?product_id=<?php echo $product['id']; ?>" class="buy-now-item">Buy Now</a>
                        <?php else: ?>
                            <!-- Redirect to login page with redirect to shopping.php -->
                            <a href="shopping.php?product_id=<?php echo $product['id']; ?>" class="buy-now-item">Buy Now</a>
                        <?php endif; ?>
                        <!-- Remove Item -->
                        <a href="cart.php?remove=<?php echo $product['id']; ?>" class="remove-item">Remove</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>