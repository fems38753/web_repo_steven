<?php
session_start();
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
    echo "Admin cannot access this page.";
    exit();
}
?>
<!-- Form Registrasi -->
<form method="POST" action="">
  <!-- Nama, Email, Password -->
</form>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connect.php';
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Hanya untuk user
    $role = 'user';
    mysqli_query($conn, "INSERT INTO users (email, password, role) VALUES ('$email', '$password', '$role')");
    header("Location: login.php");
}
?>
