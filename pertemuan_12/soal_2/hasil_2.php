<?php
$username = $_POST['username'];
$password = $_POST['password'];

echo ' <style>
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
    </style>';

if ($username === "admin" && $password === "admin") {
    echo "<div class='message'>Login berhasil!<br>Selamat datang, <span class='highlight'>admin</span>.</div>";
    echo '<a href="soal_2.php">Kembali ke halaman login</a>';
} else {
    echo "<div class='error'>Username: <strong>" . htmlspecialchars($username) . "</strong> Tidak Terdaftar!</div>";
    echo '<a href="soal_2.php">Kembali ke halaman login</a>';
}
?>
