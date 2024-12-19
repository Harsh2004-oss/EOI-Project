<?php
session_start();
include("adconnect.php");

// Get the search query and category
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Check if the search query is empty
if ($query === '') {
    die("<p>Please enter a search term.</p>");
}

// SQL Query based on category
if ($category === 'all') {
    $sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("SQL error: " . $conn->error);
    }
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $sql = "SELECT * FROM products WHERE (name LIKE ? OR description LIKE ?) AND category = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("SQL error: " . $conn->error);
    }
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $category);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="search.css">
</head>
<body>
    <!-- Search Bar Section -->
    <header>
        <div class="search-container">
            <h1>Search for Products</h1>
            <form method="GET" action="search.php">
                <input type="text" name="query" placeholder="Search for products..." value="<?php echo htmlspecialchars($query); ?>" required>
                <select name="category">
                    <option value="all" <?php echo ($category == 'all') ? 'selected' : ''; ?>>All Categories</option>
                    <option value="electronics" <?php echo ($category == 'electronics') ? 'selected' : ''; ?>>Electronics</option>
                    <option value="fashion" <?php echo ($category == 'fashion') ? 'selected' : ''; ?>>Fashion</option>
                    <option value="home" <?php echo ($category == 'home') ? 'selected' : ''; ?>>Home</option>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>
    </header>

    <!-- Search Results Section -->
    <main>
        <div class="results-container">
            <?php
            if ($result->num_rows > 0) {
                echo "<h2>Search Results for '$query'</h2>";
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-card'>";
                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p><strong>Price:</strong> ₹" . htmlspecialchars($row['price']) . "</p>";
                    echo "<p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>";
                    if (isset($row['image']) && !empty($row['image'])) {
                        echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' />";
                    }
                    echo "<a href='cart.php?id=" . $row['id'] . "'>Add To Cart</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No products found matching your search criteria.</p>";
            }

            // Close connection
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </main>

</body>
</html>
