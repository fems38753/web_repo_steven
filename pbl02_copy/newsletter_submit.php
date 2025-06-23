<?php
require 'php/connect.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM newsletter WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "You are already subscribed.";
    } else {
        $stmt = $conn->prepare("INSERT INTO newsletter (email) VALUES (?)");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            echo "Thank you for subscribing!";
        } else {
            echo "Failed to subscribe. Please try again.";
        }
    }
}
?>