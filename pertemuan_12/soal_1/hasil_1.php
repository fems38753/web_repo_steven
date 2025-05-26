<?php
$name = $_POST['name'] ?? '';
$position = $_POST['position'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$name) $errors[] = "Input Nama belum di isi!";
    if (!$password) $errors[] = "Input Password belum di isi!";
    if (!$confirm_password) $errors[] = "Input Confirm Password belum di isi!";
    if ($password && $confirm_password && $password !== $confirm_password) {
        $errors[] = "Password dan Confirm Password belum sama!";
    }

    echo ' <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 30px;
        }
        table.form-table {
            border: 2px solid #333;
            width: 500px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.2);
        }
        th {
            background-color:rgb(134, 130, 130);
            color: white;
            font-size: 22px;
            padding: 12px;
            text-align: center;
        }
        td {
            padding: 10px;
            font-size: 16px;
        }
        input[type=text], input[type=password], select {
            width: 100%;
            padding: 6px;
            border: 1px solid #aaa;
            border-radius: 4px;
        }
        input[type=submit], input[type=reset] {
            padding: 8px 16px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type=submit] {
            background-color:rgb(134, 130, 130);
            color: white;
        }
        input[type=reset] {
            background-color:rgb(134, 130, 130);
            color: white;
        }
    </style>';

    if (!empty($errors)) {
        foreach ($errors as $err) {
            echo "<p>$err</p>";
        }
        echo '<a href="soal_1.php">back</a>';
    } else {
        echo "<table>";
        echo "<tr><th colspan='2'>Data yang Anda Masukkan!</th></tr>";
        echo "<tr><td>Name</td><td>: " . htmlspecialchars($name) . "</td></tr>";
        echo "<tr><td>Position</td><td>: " . htmlspecialchars($position) . "</td></tr>";
        echo "</table>";
        echo '<br><a href="soal_1.php">Kembali</a>';
    }
}
?>
