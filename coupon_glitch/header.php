<?php
if (session_status() === PHP_SESSION_NONE) session_start();
// Count how many coupons applied (just for visual effect)
$cart_count = isset($_SESSION['applied_coupons']) ? count($_SESSION['applied_coupons']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CyberShop | Electronics & More</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Cyber<span>Shop</span></div>
        <div class="nav-links">
            <a href="index.php">Today's Deals</a>
            <a href="index.php">Customer Service</a>
            <a href="index.php">Gift Cards</a>
            <a href="checkout.php" class="cart-icon">
                🛒 Cart <?php if($cart_count > 0) echo "($cart_count)"; ?>
            </a>
        </div>
    </nav>