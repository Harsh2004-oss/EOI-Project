<?php
// Include database connection
include('adconnect.php');

// Fetch order statistics
$total_orders_sql = "SELECT COUNT(*) AS total_orders FROM orders";
$total_orders_result = mysqli_query($conn, $total_orders_sql);
$total_orders = mysqli_fetch_assoc($total_orders_result)['total_orders'];

// Fetch orders by status
$status_sql = "SELECT status, COUNT(*) AS count FROM orders GROUP BY status";
$status_result = mysqli_query($conn, $status_sql);

// Fetch recent orders
$recent_orders_sql = "SELECT * FROM orders ORDER BY order_date DESC LIMIT 5";
$recent_orders_result = mysqli_query($conn, $recent_orders_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Dashboard</title>
    <link rel="stylesheet" href="dashb.css">
</head>
<body>
    <div class="dashboard">
        <h1>Admin Dashboard</h1>

        <!-- Order Overview -->
        <section class="overview">
            <h2>Order Overview</h2>
            <p>Total Orders: <?php echo $total_orders; ?></p>
        </section>

        <!-- Orders by Status -->
        <section class="orders-status">
            <h2>Orders by Status</h2>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($status_result)) { ?>
                    <li><?php echo $row['status']; ?>: <?php echo $row['count']; ?></li>
                <?php } ?>
            </ul>
        </section>

        <!-- Recent Orders -->
        <section class="recent-orders">
            <h2>Recent Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($recent_orders_result)) { ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo $order['user_id']; ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td><a href="view_order.php?id=<?php echo $order['order_id']; ?>">View</a> | 
                                <a href="update_status.php?id=<?php echo $order['order_id']; ?>">Update Status</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>

        <!-- Optional: Order Statistics Chart -->
        <!-- You can use a library like Chart.js to create a simple graph here -->

    </div>
</body>
</html>
