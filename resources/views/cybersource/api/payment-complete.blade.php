<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 50px;
        }
        h1 {
            color: #00a654;
        }
        p {
            font-size: 18px;
            color: #333;
        }
        .success-icon {
            font-size: 64px;
            color: #00a654;
        }
        .back-to-home {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #00a654;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-to-home:hover {
            background-color: #00904a;
        }
    </style>
</head>
<body>
    <div class="container">
        <i class="success-icon">&#10004;</i>
        <h1>Payment Successful!</h1>
        <p>Thank you for your purchase. Your payment has been successfully processed.</p>
        <a href="{{ url('/') }}" class="back-to-home">Back to Home</a>
    </div>
</body>
</html>
