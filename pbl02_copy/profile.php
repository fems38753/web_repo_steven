<?php
session_start();
include 'php/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Account Settings</title>
  <link rel="stylesheet" href="pbl02.css" />
</head>
<body>
  <main style="max-width: 600px; margin: 50px auto; padding: 30px; background: #f9f9f9; border-radius: 10px;">
    <h2>Account Settings</h2>
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Created At:</strong> <?= date("F j, Y", strtotime($user['created_at'])) ?></p>
    <a href="account.php" style="display:inline-block; margin-top:20px;">⬅ Back to Dashboard</a>
  </main>
</body>
</html>