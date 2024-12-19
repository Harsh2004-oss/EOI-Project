<?php
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch electronics products
$result = $conn->query("SELECT * FROM products WHERE description = 'Electronics'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion</title>
    <link rel="stylesheet" href="electronics.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="elproducts.php" class="active">Electronics</a></li>
            </ul>
        </nav>
    </header>

    <section class="product-list">
        <h1>Electronics</h1>
        <div class="products-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="<?php echo $row['image']; ?>" alt="Product Image" class="product-image">
                    <div class="product-info">
                        <h2><?php echo $row['name']; ?></h2>
                        <p class="product-description"><?php echo $row['description']; ?></p>
                        <p class="product-price">Price: ₹<?php echo $row['price']; ?></p>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

   
</body>
</html>
