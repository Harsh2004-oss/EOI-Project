<?php
session_start();
include("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns and Orders - TechCart</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
        }
        
        header {
            background-color: black;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        h1, h2 {
            color: #fff;
            text-align: center;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .orders-section {
            margin-bottom: 40px;
        }

        .order-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            background-color: #f9f9f9;
        }

        .order-card h3 {
            margin: 0;
            color: #555;
        }

        .order-card p {
            margin: 5px 0;
            color: #777;
        }

        .return-form {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
        }

        .return-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .return-form input, .return-form select, .return-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .return-form button {
            background-color: #f0c14b;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .return-form button:hover {
            background-color: #d1a538;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Returns and Orders</h1>
    </header>

    <!-- Main Content -->
    <div class="container">

        <!-- Orders Section -->
        <div class="orders-section">
            <h2>Your Orders</h2>
            
            <!-- Example Order Cards -->
            <div class="order-card">
                <h3>Order #12345</h3>
                <p><strong>Date:</strong> 2024-12-01</p>
                <p><strong>Items:</strong> Wireless Headphones</p>
                <p><strong>Total:</strong> ₹2,499</p>
                <p><strong>Status:</strong> Delivered</p>
                <button onclick="alert('Return request initiated for Order #12345');">Initiate Return</button>
            </div>

            <div class="order-card">
                <h3>Order #12346</h3>
                <p><strong>Date:</strong> 2024-11-28</p>
                <p><strong>Items:</strong> Smartphone Case</p>
                <p><strong>Total:</strong> ₹499</p>
                <p><strong>Status:</strong> In Transit</p>
            </div>

            <!-- Add more orders dynamically from the database -->
        </div>

        <!-- Return Request Form -->
        <div class="return-form">
            <h2>Request a Return</h2>
            <form action="process_return.php" method="POST">
                <label for="order-id">Order ID</label>
                <input type="text" id="order-id" name="order_id" placeholder="Enter your order ID" required />

                <label for="reason">Reason for Return</label>
                <textarea id="reason" name="reason" rows="5" placeholder="Describe the reason for return" required></textarea>

                <label for="return-method">Preferred Return Method</label>
                <select id="return-method" name="return_method" required>
                    <option value="pickup">Pickup</option>
                    <option value="dropoff">Drop-off</option>
                </select>

                <button type="submit">Submit Request</button>
            </form>
        </div>

    </div>

</body>
</html>
