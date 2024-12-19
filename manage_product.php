<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure 'today_deal' column exists in the database


// Add a new product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $today_deal = isset($_POST['todays_deal']) ? 1 : 0; // Checkbox for Today's Deal

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target_dir = "image/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert product into the database, including Today's Deal
    $stmt = $conn->prepare("INSERT INTO products (name, price, description, stock, image, category, todays_deal) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsissi", $name, $price, $description, $stock, $target_file, $category, $today_deal);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_product.php");
}

// Delete a product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_product.php");
}

// Fetch all products or filter "Today's Deals"
$filter_today_deals = isset($_GET['filter']) && $_GET['filter'] === 'todays_deals';
$query = $filter_today_deals ? "SELECT * FROM products WHERE todays_deal = 1" : "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="products.css">
</head>
<body>
    <h1>Manage Products</h1>

    <!-- Add Product Form -->
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" name="stock" placeholder="Stock Quantity" required>
        <input type="file" name="image" accept="image/*" required>
        
        <!-- Category selection -->
        <select name="category" required>
            <option value="Electronics">Electronics</option>
            <option value="Fashion">Fashion</option>
            <option value="Home and Kitchen">Home and Kitchen</option>
            <option value="Toys">Toys</option>
        </select>

        <!-- Today's Deals checkbox -->
        <label>
            <input type="checkbox" name="todays_deal"> Mark as Today's Deal
        </label>

        <button type="submit" name="add_product">Add Product</button>
    </form>

    <hr>

    <!-- Products Table -->
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Today's Deal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td>
                    <img src="<?php echo $row['image']; ?>" alt="Product Image" style="width: 50px; height: 50px;">
                </td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>₹<?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo $row['todays_deal'] ? "Yes" : "No"; ?></td>
                <td>
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="manage_product.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
