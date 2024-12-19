<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['shipping'] = $_POST; // Save shipping details to the session
    header("Location: payment.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="checkout.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Address</title>
</head>
<body>
    <h1>Enter Shipping Address</h1>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="address">Address:</label>
        <textarea id="address" name="address" required></textarea><br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required><br>
        <label for="postal_code">Postal Code:</label>
        <input type="text" id="postal_code" name="postal_code" required><br>
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" required><br>
        <button type="submit">Proceed to Payment</button>
    </form>
</body>
</html>
