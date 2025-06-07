<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../account.html?redirect=checkout");
    exit;
}
