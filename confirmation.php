<?php
session_start();

// Get the payment status from session
$payment_status = isset($_SESSION['payment_status']) ? $_SESSION['payment_status'] : '';

// Clear the session after displaying the message (to avoid showing the message again)
unset($_SESSION['payment_status']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }

        .message {
            padding: 20px;
            background-color: #28a745; /* Green color for success */
            color: white;
            border-radius: 5px;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .error-message {
            padding: 20px;
            background-color: #dc3545; /* Red color for error */
            color: white;
            border-radius: 5px;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .redirect-message {
            font-size: 1.2em;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <?php if ($payment_status === 'Success'): ?>
        <!-- Show this message if payment was successful -->
        <div class="message">
            Payment Received Successfully!
        </div>
        <div class="redirect-message">
            You will be redirected shortly. Please wait...
        </div>
    <?php else: ?>
        <!-- Show this message if payment failed -->
        <div class="error-message">
            Payment Failed. Please try again later.
        </div>
        <div class="redirect-message">
            You will be redirected shortly to try again.
        </div>
    <?php endif; ?>

    <script>
        // Redirect to 'thank_you.php' after 3 seconds (3000 milliseconds)
        setTimeout(function() {
            window.location.href = 'thank_you.php'; // Redirect to the next page
        }, 3000); // 3 seconds delay
    </script>

</body>
</html>
