<?php
session_start();
$base_price = 1000.00;

// Reset Logic
if (isset($_GET['clear'])) {
    session_destroy();
    header("Location: checkout.php");
    exit;
}

// Init Coupon Storage
if (!isset($_SESSION['applied_coupons'])) {
    $_SESSION['applied_coupons'] = [];
}

$msg = "";

// --- VULNERABILITY: APPLY COUPON ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coupon'])) {
    $code = strtoupper(trim($_POST['coupon']));
    
    if ($code === 'SAVE10') {
        // FLAW: No check if coupon is already in the array!
        $_SESSION['applied_coupons'][] = $code;
        $msg = "<div class='alert success'>Success: 10% Discount Applied!</div>";
    } else {
        $msg = "<div class='alert error'>Error: Invalid Coupon Code.</div>";
    }
}

// Calculate Math
$discount_amount = 0;
foreach ($_SESSION['applied_coupons'] as $c) {
    if ($c === 'SAVE10') {
        $discount_amount += 100.00; // 10% of 1000
    }
}
$final_total = $base_price - $discount_amount;
if ($final_total < 0) $final_total = 0;

// --- HANDLE PURCHASE ---
$flag = "";
if (isset($_POST['place_order'])) {
    if ($final_total == 0) {
        $flag = "NodeZero{stacking_coupons_for_the_win}";
    } else {
        $msg = "<div class='alert error'>Transaction Failed: Insufficient funds on card ending in 4421.</div>";
    }
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Shopping Cart</h2>
    <div class="checkout-grid">
        
        <div class="main-content">
            <div style="display: flex; border-bottom: 1px solid #ddd; padding-bottom: 20px;">
                <div style="font-size: 5rem; margin-right: 20px;">🚩</div>
                <div>
                    <h3>Premium CTF Flag (Collectors Edition)</h3>
                    <div class="stock">In Stock</div>
                    <div class="price">$1,000.00</div>
                    <p>Sold by: CyberShop Inc.</p>
                    <a href="?clear=1" style="color: #007185; font-size: 0.9rem;">Delete</a>
                </div>
            </div>
            
            <?php if($msg) echo "<br>" . $msg; ?>

            <?php if($flag): ?>
                <div class="flag-box">
                    <strong>ORDER CONFIRMED</strong><br>
                    Your Flag: <?php echo $flag; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="order-summary">
            <h3>Order Summary</h3>
            
            <div class="total-line">
                <span>Items (1):</span>
                <span>$1,000.00</span>
            </div>
            <div class="total-line">
                <span>Shipping:</span>
                <span>$0.00</span>
            </div>
            
            <?php if(count($_SESSION['applied_coupons']) > 0): ?>
                <div style="border-top: 1px dashed #ccc; margin: 10px 0; padding-top: 10px;">
                <?php foreach($_SESSION['applied_coupons'] as $k => $c): ?>
                    <div class="total-line" style="color: #166534;">
                        <span>Promotion (SAVE10):</span>
                        <span>-$100.00</span>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="total-line grand-total">
                <span>Order Total:</span>
                <span>$<?php echo number_format($final_total, 2); ?></span>
            </div>

            <form method="POST" style="margin-top: 20px; background: #e7f4f5; padding: 10px; border-radius: 4px;">
                <label style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px;">Add a gift card or promotion code</label>
                <div style="display: flex; gap: 5px;">
                    <input type="text" name="coupon" class="coupon-input" placeholder="Enter code">
                    <button class="btn btn-secondary">Apply</button>
                </div>
            </form>

            <form method="POST" style="margin-top: 20px;">
                <button name="place_order" class="btn btn-primary" style="width: 100%;">Place your order</button>
            </form>
            
            <div style="font-size: 0.75rem; text-align: center; margin-top: 10px; color: #555;">
                By placing your order, you agree to CyberShop's privacy notice and conditions of use.
            </div>
        </div>

    </div>
</div>

</body>
</html>