<?php
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

// Fetch orders
$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    // Sanitize inputs
    $order_id = (int)$_POST['order_id']; // Ensure it's an integer
    $status = $conn->real_escape_string($_POST['status']); // Sanitize status value

    // Update order status
    $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo "Order status updated successfully!";
    } else {
        echo "Error updating order status: " . $stmt->error;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .status-form select {
            padding: 5px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Order Management</h1>

    <!-- Display orders in a table -->
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo $order['customer_name']; ?></td>
                        <td><?php echo $order['customer_email']; ?></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($order['order_date'])); ?></td>
                        <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo $order['status']; ?></td>
                        <td>
                            <!-- Update status form -->
                            <form method="POST" action="manage_orders.php" class="status-form" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status">
                                    <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Shipped" <?php echo ($order['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="Delivered" <?php echo ($order['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                </select>
                                <button type="submit" name="update_status" class="btn">Update</button>
                            </form>
                            <!-- View Details Link -->
                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn">View Details</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
