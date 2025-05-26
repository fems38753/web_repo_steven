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
    </style>
</head>
<body>

<form action="hasil_1.php" method="POST">
    <table class="form-table">
        <tr><th colspan="2">ADD PROFILE</th></tr>
        <tr>
            <td>Name</td>
            <td><input type="text" name="name"></td>
        </tr>
        <tr>
            <td>Position</td>
            <td>
                <select name="position">
                    <optgroup label="Programmer">
                        <option value="Senior Programmer" selected>Senior Programmer</option>
                        <option value="Junior Programmer">Junior Programmer</option>
                    </optgroup>
                    <optgroup label="System Analyst">
                        <option value="Senior Analyst">Senior Analyst</option>
                        <option value="Junior Analyst">Junior Analyst</option>
                    </optgroup>
                </select>
            </td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" name="password"></td>
        </tr>
        <tr>
            <td>Confirm Password</td>
            <td><input type="password" name="confirm_password"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;">
                <input type="reset" value="Reset">
                <input type="submit" value="Save">
            </td>
        </tr>
    </table>
</form>
</body>
</html>