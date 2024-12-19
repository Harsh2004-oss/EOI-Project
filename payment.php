<?php
session_start();

// Handle payment form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $payment_method = $_POST['payment_method'];
    // Process payment (dummy processing here)
    $payment_status = "Success"; // Replace this with actual payment gateway integration

    // Save the payment status in session to use in confirmation page
    $_SESSION['payment_status'] = $payment_status;

    // Redirect to confirmation page after processing payment
    header("Location: confirmation.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            background-color: #007BFF;
            color: white;
            width: 100%;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin: 0;
            font-size: 1.8em;
        }

        main {
            flex: 1;
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            margin-top: 10px;
            font-weight: bold;
        }

        form input, form select, form button {
            margin-top: 5px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 15px;
            padding: 12px;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        select {
            width: 100%;
        }

        #card_details, #upi_details, #net_banking_details, #qr_code_details {
            margin-top: 15px;
            display: none;
        }

        #card_details input, #upi_details input, #net_banking_details input {
            margin-bottom: 10px;
        }

        #qr_code_details img {
            width: 200px;
            height: 200px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Payment Gateway</h1>
    </header>
    <main>
        <form action="payment.php" method="POST">
            <label for="payment_method">Payment Method</label>
            <select id="payment_method" name="payment_method" required>
                <option value="">Select a payment method</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="net_banking">Net Banking</option>
                <option value="upi">UPI</option>
                <option value="QR">QR-Code</option>
            </select>

            <!-- Credit/Debit Card Payment -->
            <div id="card_details">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" name="card_number" maxlength="16" placeholder="Enter your card number">

                <label for="expiration_date">Expiration Date</label>
                <input type="month" id="expiration_date" name="expiration_date">

                <label for="cvv">CVV</label>
                <input type="password" id="cvv" name="cvv" maxlength="3" placeholder="CVV">
            </div>

            <!-- Net Banking -->
            <div id="net_banking_details">
                <label for="bank_name">Bank Name</label>
                <input type="text" id="bank_name" name="bank_name" placeholder="Enter your bank name">

                <label for="account_number">Account Number</label>
                <input type="text" id="account_number" name="account_number" placeholder="Enter your account number">
            </div>

            <!-- UPI -->
            <div id="upi_details">
                <label for="upi_id">UPI ID</label>
                <input type="text" id="upi_id" name="upi_id" placeholder="Enter your UPI ID">
            </div>

            <!-- QR Code -->
            <div id="qr_code_details">
                <p>Scan this QR code to complete your payment:</p>
                <img src="qr-code.png.jpg" alt="QR Code">
            </div>

            <button type="submit">Proceed to Pay</button>
        </form>
    </main>

    <script>
        const paymentMethod = document.getElementById('payment_method');
        const cardDetails = document.getElementById('card_details');
        const netBankingDetails = document.getElementById('net_banking_details');
        const upiDetails = document.getElementById('upi_details');
        const qrCodeDetails = document.getElementById('qr_code_details');

        paymentMethod.addEventListener('change', () => {
            cardDetails.style.display = 'none';
            netBankingDetails.style.display = 'none';
            upiDetails.style.display = 'none';
            qrCodeDetails.style.display = 'none'; // Hide QR code details by default

            if (paymentMethod.value === 'credit_card' || paymentMethod.value === 'debit_card') {
                cardDetails.style.display = 'block';
            } else if (paymentMethod.value === 'net_banking') {
                netBankingDetails.style.display = 'block';
            } else if (paymentMethod.value === 'upi') {
                upiDetails.style.display = 'block';
            } else if (paymentMethod.value === 'QR') {
                qrCodeDetails.style.display = 'block'; // Show QR code details
            }
        });
    </script>
</body>
</html>
