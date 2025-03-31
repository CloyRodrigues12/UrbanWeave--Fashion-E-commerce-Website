<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Credit Card Payment</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f4f4f9;
        }
        .container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 500px;
            padding: 30px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        /* Virtual Credit Card Preview */
        .credit-card {
            width: 100%;
            background: linear-gradient(135deg, #4C9F70, #56CCF2);
            border-radius: 12px;
            padding: 20px;
            color: white;
            text-align: left;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            font-family: 'Courier New', monospace;
        }
        .card-number, .card-name, .card-expiry, .card-cvv {
            font-size: 18px;
            margin: 5px 0;
        }
        .card-number {
            letter-spacing: 2px;
        }
        .card-details {
            display: flex;
            justify-content: space-between;
        }
        /* Input Styling */
        form label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
            color: #555;
            text-align: left;
        }
        form input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        form input:focus {
            outline: none;
            border-color: #4CAF50;
        }
        button {
            width: 50%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Credit Card Payment</h2>
        <!-- Virtual Credit Card Preview -->
        <div class="credit-card">
            <div class="card-number" id="display-card-number">**** **** **** ****</div>
            <div class="card-details">
                <div class="card-name" id="display-card-name">YOUR NAME</div>
                <div class="card-expiry" id="display-card-expiry">MM/YY</div>
            </div>
            <div class="card-cvv">CVV: <span id="display-card-cvv">***</span></div>
        </div>

        <form action="complete_payment.php" method="POST" id="payment-form">
            <input type="hidden" name="payment_method" value="credit-card">

            <label>Cardholder Name:</label>
            <input type="text" name="cardholder_name" id="cardholder_name" required>

            <label>Card Number:</label>
            <input type="text" name="card_number" id="card_number" maxlength="19" placeholder="1234 5678 9012 3456" required>

            <label>Expiration Date:</label>
            <input type="month" name="expiry_date" id="expiry_date" required>

            <label>CVV:</label>
            <input type="text" name="cvv" id="cvv" maxlength="3" required>

            <button type="submit">Confirm Payment</button>
        </form>
    </div>

    <script>
        // Display elements on virtual credit card
        const displayCardNumber = document.getElementById('display-card-number');
        const displayCardName = document.getElementById('display-card-name');
        const displayCardExpiry = document.getElementById('display-card-expiry');
        const displayCardCVV = document.getElementById('display-card-cvv');

        // Input elements for form fields
        const cardNumberInput = document.getElementById('card_number');
        const cardNameInput = document.getElementById('cardholder_name');
        const expiryInput = document.getElementById('expiry_date');
        const cvvInput = document.getElementById('cvv');

        // Format card number and update display
        cardNumberInput.addEventListener('input', () => {
            let value = cardNumberInput.value.replace(/\D/g, '').substring(0, 16);
            value = value.replace(/(.{4})/g, '$1 ').trim();
            cardNumberInput.value = value;
            displayCardNumber.innerText = value.padEnd(19, '*');
        });

        // Update name on virtual card
        cardNameInput.addEventListener('input', () => {
            displayCardName.innerText = cardNameInput.value.toUpperCase() || "YOUR NAME";
        });

        // Update expiry date on virtual card
        expiryInput.addEventListener('input', () => {
            const [year, month] = expiryInput.value.split('-');
            displayCardExpiry.innerText = month && year ? `${month}/${year.slice(-2)}` : 'MM/YY';
        });

        // Update CVV on virtual card
        cvvInput.addEventListener('input', () => {
            displayCardCVV.innerText = cvvInput.value.padEnd(3, '*');
        });
    </script>
</body>
</html>

