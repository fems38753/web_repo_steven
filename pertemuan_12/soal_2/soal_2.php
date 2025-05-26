<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2373010-Steven Effendi</title>
</head>
<body>
   <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .login-box {
            width: 500px;
            margin: 100px auto;
            border: 2px solid navy;
            border-radius: 5px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: navy;
            color: white;
            font-size: 50px;
            text-align: center;
            padding: 10px 0;
        }
        .form-container {
            padding: 25px;
        }
        .form-container label {
            display: inline-block;
            width: 450px;
            font-size: 20px;
        }
        .form-container input[type="text"],
        .form-container input[type="password"] {
            width: 400px;
            height: 25px;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .form-container input[type="submit"] {
            display: block;
            margin: 10px auto;
            font-size: 16px;
            padding: 5px 15px;
            cursor: pointer;
        }
        .footer {
            border-top: 1px solid #ccc;
            text-align: left;
            padding: 10px 0;
            font-size: 14px;
            color: gray;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="header">Login</div>
        <form class="form-container" action="hasil_2.php" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username"><br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password"><br>
            <input type="submit" value="login">
        </form>
        <div class="footer">
            @UKM2014<br>
            Steven Effendi-2373010&copy;
        </div>
    </div>
</body>
</html>