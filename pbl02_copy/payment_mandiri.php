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
    <title>Payment Mandiri Virtual Account</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background-color: #fff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .icon-check {
            font-size: 60px;
            color: #28a745;
            animation: pop 0.6s ease-out;
        }

        @keyframes pop {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        h2 {
            color: #d1005b;
            margin: 20px 0 10px;
        }

        .va-box {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            font-size: 18px;
        }

        .copy-btn {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 6px 14px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .copy-btn:hover {
            background-color: #0069d9;
        }

        .notes {
            margin: 20px 0;
            font-size: 15px;
            color: #777;
        }

        .steps {
            text-align: left;
            margin-top: 25px;
        }

        .steps h4 {
            color: #333;
        }

        .steps ol {
            padding-left: 20px;
        }

        .timer {
            font-size: 16px;
            color: #dc3545;
            margin-top: 10px;
        }

        .btn {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 25px;
        }

        .btn-primary {
            background-color: #28a745;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-check">💳</div>
        <h2>Payment via Mandiri Virtual Account</h2>
        <p>Please make a transfer to the virtual account below:</p>

        <div class="va-box">
            <strong id="vaNumber">70000-9876543210</strong>
            <button class="copy-btn" onclick="copyVA()">Salin</button>
        </div>
        <p>On behalf of: <strong>PT JACKARMY</strong></p>

        <div class="timer">
            Payment Deadline: <span id="countdown">02:00</span> minute
        </div>

        <div class="steps">
            <h4>Payment method:</h4>
            <ol>
                <li>Open the Livin' by Mandiri application or Mandiri Internet Banking.</li>
                <li>Select the transfer menu to Virtual Account.</li>
                <li>Enter the VA number above and the nominal amount according to the total purchase.</li>
                <li>Confirm and complete payment.</li>
            </ol>
        </div>

        <div class="notes">After making payment, click the button below.</div>
        <button onclick="refreshPayment()" class="btn btn-primary">Refresh Payment</button>
    </div>

    <script>
        function refreshPayment() {
            alert("Payment successful!");
            window.location.href = "payment_mandiri.php?status=success";
        }

        function copyVA() {
            const va = document.getElementById("vaNumber").textContent;
            navigator.clipboard.writeText(va);
            alert("VA number successfully copied!");
        }

        // Countdown timer 2 minutes (120 detik)
        let time = 120;
        const countdownEl = document.getElementById("countdown");

        const timer = setInterval(() => {
            const minutes = Math.floor(time / 60);
            const seconds = time % 60;
            countdownEl.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            time--;

            if (time < 0) {
                clearInterval(timer);
                countdownEl.textContent = "Time has run out!";
                alert("Payment time has expired. Please re-order.");
                // window.location.href = "cart.php"; // Aktifkan jika ingin redirect otomatis
            }
        }, 1000);
    </script>
</body>
</html>
