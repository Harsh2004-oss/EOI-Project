<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "login");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set and valid
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the product details based on the product ID
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // If the product does not exist, redirect
    if (!$product) {
        header("Location: manage_product.php");
        exit();
    }
}

// Update the product
if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    // Handle image upload (if updated)
    $image = $_FILES['image']['name'];
    if ($image) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        // Use the current image if not updated
        $target_file = $product['image'];
    }

    // Update the product in the database
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, stock = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sdsisi", $name, $price, $description, $stock, $target_file, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect after update
    header("Location: manage_product.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="products.css">
</head>
<body>
    <h1>Edit Product</h1>

    <!-- Edit Product Form -->
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        <input type="number" step="0.01" name="price" placeholder="Price" value="<?php echo $product['price']; ?>" required>
        <textarea name="description" placeholder="Description"><?php echo htmlspecialchars($product['description']); ?></textarea>
        <input type="number" name="stock" placeholder="Stock Quantity" value="<?php echo $product['stock']; ?>" required>
        
        <!-- Current Image -->
        <p>Current Image:</p>
        <img src="<?php echo $product['image']; ?>" alt="Product Image" style="width: 50px; height: 50px;">
        <input type="file" name="image" accept="image/*">
        
        <button type="submit" name="update_product">Update Product</button>
    </form>
</body>
</html>
