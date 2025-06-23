<?php
session_start();

if (isset($_GET['status']) && $_GET['status'] === 'success') {
    file_put_contents('cart.json', json_encode([]));
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Credit Card Payment (Visa)</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 60px auto;
            background-color: #fff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #00457c;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .btn {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            width: 100%;
            margin-top: 25px;
        }

        .btn-primary {
            background-color: #00457c;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #002f5f;
        }

        .secure-info {
            font-size: 14px;
            color: #555;
            text-align: center;
            margin-top: 15px;
        }

        .timer {
            font-size: 16px;
            color: #dc3545;
            text-align: center;
            margin-top: 20px;
        }

        .card-icon {
            font-size: 48px;
            text-align: center;
            margin-bottom: 20px;
            color: #00457c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card-icon">💳</div>
        <h2>Payment via Visa</h2>

        <form onsubmit="submitPayment(event)">
            <div class="form-group">
                <label for="cardNumber">Card Number</label>
                <input type="text" id="cardNumber" placeholder="**** **** **** 5678" maxlength="19" required>
            </div>
            <div class="form-group">
                <label for="cardName">Card Name</label>
                <input type="text" id="cardName" placeholder="Nama Lengkap" required>
            </div>
            <div class="form-group">
                <label for="expDate">Validity period (MM/YY)</label>
                <input type="text" id="expDate" placeholder="MM/YY" maxlength="5" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="number" id="cvv" placeholder="***" maxlength="3" required>
            </div>
            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>

        <div class="timer">
            Payment deadline:<span id="countdown">05:00</span> minute
        </div>

        <div class="secure-info">
Your transactions are processed securely through an encrypted payment system
        </div>
    </div>

    <script>
        function submitPayment(event) {
            event.preventDefault();
            alert("Visa payment successfully processed!");
            window.location.href = "payment_visa.php?status=success";
        }

        let time = 300;
        const countdownEl = document.getElementById("countdown");

        const timer = setInterval(() => {
            const minutes = Math.floor(time / 60);
            const seconds = time % 60;
            countdownEl.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            time--;

            if (time < 0) {
                clearInterval(timer);
                countdownEl.textContent = "Time has run out!";
                alert("Payment time has expired. Please repeat the process.");
            }
        }, 1000);
    </script>
</body>
</html>