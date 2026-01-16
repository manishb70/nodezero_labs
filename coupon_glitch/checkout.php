<?php
/**
 * CYBERSHOP - CTF LAB (Logic Flaw: Coupon Stacking)
 * * INSTRUCTIONS:
 * 1. Save as 'cybershop_lab.php'
 * 2. Run with: php -S localhost:8000
 * 3. Browse to: http://localhost:8000/cybershop_lab.php
 */

session_start();

// --- 1. CONFIGURATION & DATABASE ---
$product_price = 1000.00;
$product_name = "Premium CTF Flag";

// Initialize Session Arrays
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Stores product IDs
    $_SESSION['coupons'] = []; // Stores applied coupons
}

// Router: Determine which 'page' to show
$page = $_GET['page'] ?? 'home';

// --- 2. BACKEND LOGIC (CONTROLLERS) ---

// Handle: Reset Lab
if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    session_destroy();
    header("Location: cybershop_lab.php");
    exit;
}

// Handle: Add to Cart (Simple Logic)
if (isset($_POST['add_to_cart'])) {
    $_SESSION['cart'][] = 1; // Add product ID 1
    // Redirect to cart to show flow
    header("Location: ?page=cart");
    exit;
}

// Handle: Apply Coupon (THE VULNERABILITY IS HERE)
$coupon_msg = "";
if (isset($_POST['apply_coupon'])) {
    $code = strtoupper(trim($_POST['coupon_code']));
    
    if ($code === 'SAVE10') {
        // VULNERABILITY: 
        // We push the coupon to the array WITHOUT checking if it exists!
        // A secure system would use: if (!in_array($code, $_SESSION['coupons'])) ...
        $_SESSION['coupons'][] = $code; 
        $coupon_msg = "success|Coupon SAVE10 Applied! (10% Off)";
    } else {
        $coupon_msg = "error|Invalid Coupon Code.";
    }
}

// Handle: Checkout / Flag Check
$flag_alert = "";
if (isset($_POST['checkout'])) {
    // Recalculate Total
    $total = $product_price;
    foreach ($_SESSION['coupons'] as $c) {
        if ($c === 'SAVE10') {
            $total -= 100.00; // Deduct $100 (10% of base)
        }
    }
    if ($total < 0) $total = 0;

    // Win Condition
    if ($total == 0) {
        $flag_alert = "NodeZero{stacking_coupons_for_the_win}";
    } else {
        $coupon_msg = "error|Payment Failed: Insufficient funds on card.";
    }
}

// --- 3. HELPER FUNCTIONS ---
function get_cart_count() {
    return count($_SESSION['cart']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberShop | Electronics & More</title>
    <style>
        /* --- CSS STYLES (Amazon/Classic Style) --- */
        :root {
            --primary: #232f3e;
            --accent: #ff9900;
            --link: #007185;
            --bg: #eaeded;
            --white: #ffffff;
            --text: #0F1111;
        }
        body { font-family: Arial, sans-serif; margin: 0; background-color: var(--bg); color: var(--text); }
        a { text-decoration: none; color: inherit; }
        
        /* Navbar */
        .navbar { background-color: var(--primary); color: white; padding: 10px 20px; display: flex; align-items: center; justify-content: space-between; height: 60px; }
        .logo { font-size: 1.5rem; font-weight: bold; font-style: italic; }
        .logo span { color: var(--accent); }
        .nav-links a { color: #ccc; margin-left: 15px; font-size: 0.9rem; font-weight: bold; }
        .nav-links a:hover { color: white; border-bottom: 1px solid white; }
        .cart-btn { background: var(--primary); border: 1px solid white; padding: 8px 15px; border-radius: 4px; font-weight: bold; color: white; cursor: pointer; }
        
        /* Layout */
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .card { background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; text-align: center; }
        .btn { display: inline-block; padding: 8px 15px; border-radius: 20px; font-size: 0.9rem; cursor: pointer; text-align: center; border: 1px solid; width: 100%; box-sizing: border-box; }
        .btn-primary { background: #ffd814; border-color: #fcd200; color: #0F1111; }
        .btn-primary:hover { background: #f7ca00; }
        .btn-secondary { background: #f0f2f2; border-color: #d5d9d9; }
        
        /* Grid */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .price { color: #B12704; font-size: 1.2rem; font-weight: bold; margin: 10px 0; }
        
        /* Cart Specifics */
        .checkout-layout { display: grid; grid-template-columns: 3fr 1fr; gap: 20px; }
        .cart-item { background: white; padding: 20px; border-radius: 4px; display: flex; gap: 20px; border-bottom: 1px solid #ddd; }
        .summary-box { background: white; padding: 20px; border-radius: 4px; height: fit-content; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .final-price { color: #B12704; font-size: 1.4rem; font-weight: bold; border-top: 1px solid #ccc; padding-top: 10px; margin-top: 10px; }
        
        /* Alerts */
        .alert { padding: 10px; margin-bottom: 10px; border-radius: 4px; text-align: center; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        
        .flag-box { background: #232f3e; color: #00ff00; padding: 20px; font-family: monospace; text-align: center; margin-top: 20px; border-radius: 4px; border: 2px solid #00ff00; }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="?page=home" class="logo">Cyber<span>Shop</span></a>
        <div class="nav-links">
            <a href="?page=home">Today's Deals</a>
            <a href="?page=home">Customer Service</a>
            <a href="?page=cart" class="cart-btn">
                🛒 Cart (<?php echo get_cart_count(); ?>)
            </a>
        </div>
    </nav>

    <div class="container">
        
        <?php if ($page === 'home'): ?>
            <div style="background: white; padding: 15px; margin-bottom: 20px; text-align: center; border-bottom: 1px solid #ddd;">
                <strong>Summer Sale:</strong> Use promo code <span style="background: #ffd814; padding: 2px 5px; font-weight:bold;">SAVE10</span> for 10% Off!
            </div>

            <h2 style="margin-top: 0;">Featured Products</h2>
            <div class="grid">
                <div class="card">
                    <div style="font-size: 4rem;">🎧</div>
                    <h3>Pro Headphones</h3>
                    <div class="price">$299.00</div>
                    <button class="btn btn-secondary">Out of Stock</button>
                </div>

                <div class="card" style="border: 2px solid #ff9900;">
                    <div style="font-size: 4rem;">🚩</div>
                    <h3>Premium CTF Flag</h3>
                    <div class="price">$1,000.00</div>
                    <div style="color: green; font-size: 0.9rem; margin-bottom: 10px;">In Stock</div>
                    <form method="POST">
                        <button name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                    </form>
                </div>

                <div class="card">
                    <div style="font-size: 4rem;">💻</div>
                    <h3>Dev Laptop</h3>
                    <div class="price">$1,499.00</div>
                    <button class="btn btn-secondary">Out of Stock</button>
                </div>
            </div>

        <?php elseif ($page === 'cart'): ?>
            <h2>Shopping Cart</h2>
            
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="card">
                    <h3>Your CyberShop Cart is empty.</h3>
                    <p>Check your Saved for later items below or <a href="?page=home" style="color:#007185;">continue shopping</a>.</p>
                </div>
            <?php else: ?>
                
                <div class="checkout-layout">
                    <div style="background: white; border-radius: 4px; padding: 20px;">
                        
                        <?php if($flag_alert): ?>
                            <div class="flag-box">
                                <strong>PAYMENT SUCCESSFUL!</strong><br><br>
                                <?php echo $flag_alert; ?>
                            </div>
                            <div style="text-align:center; margin-top:20px;">
                                <a href="?action=reset" style="color:#007185;">Reset Lab</a>
                            </div>
                        <?php else: ?>
                        
                            <div style="display: flex; gap: 20px; border-bottom: 1px solid #ddd; padding-bottom: 20px;">
                                <div style="font-size: 5rem;">🚩</div>
                                <div>
                                    <h3>Premium CTF Flag (Digital Edition)</h3>
                                    <div style="color: green; font-size: 0.8rem;">In Stock</div>
                                    <div class="price">$1,000.00</div>
                                    <a href="?action=reset" style="color: #007185; font-size: 0.8rem;">Delete</a>
                                </div>
                            </div>
                            
                            <p style="margin-top:20px; font-size:0.9rem;">
                                <strong>Note:</strong> Limit 1 per customer.
                            </p>

                        <?php endif; ?>
                    </div>

                    <div class="summary-box">
                        <?php 
                            // Calculate Totals for Display
                            $subtotal = $product_price; 
                            $discount_total = 0;
                            
                            foreach($_SESSION['coupons'] as $c) {
                                if($c == 'SAVE10') $discount_total += 100;
                            }
                            
                            $grand_total = $subtotal - $discount_total;
                            if($grand_total < 0) $grand_total = 0;
                        ?>

                        <?php if($coupon_msg): 
                            list($type, $txt) = explode('|', $coupon_msg);
                        ?>
                            <div class="alert alert-<?php echo $type; ?>"><?php echo $txt; ?></div>
                        <?php endif; ?>

                        <h3>Order Summary</h3>
                        
                        <div class="total-row">
                            <span>Items (1):</span>
                            <span>$<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span>$0.00</span>
                        </div>

                        <?php if($discount_total > 0): ?>
                            <div style="margin: 10px 0; border-top: 1px dashed #ccc; padding-top: 10px;">
                                <?php foreach($_SESSION['coupons'] as $c): ?>
                                    <div class="total-row" style="color: #166534; font-size:0.9rem;">
                                        <span>Promotion (<?php echo $c; ?>):</span>
                                        <span>-$100.00</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="total-row final-price">
                            <span>Order Total:</span>
                            <span>$<?php echo number_format($grand_total, 2); ?></span>
                        </div>

                        <form method="POST" style="margin-top: 20px; background: #f0f2f2; padding: 10px; border-radius: 4px;">
                            <label style="font-size: 0.8rem; font-weight: bold;">Gift Cards & Promos</label>
                            <div style="display: flex; gap: 5px; margin-top: 5px;">
                                <input type="text" name="coupon_code" placeholder="Enter Code" style="flex: 1; padding: 5px; border: 1px solid #ccc; border-radius: 3px;">
                                <button name="apply_coupon" class="btn-secondary" style="border-radius: 3px; padding: 5px 10px; cursor: pointer;">Apply</button>
                            </div>
                        </form>

                        <form method="POST" style="margin-top: 20px;">
                            <button name="checkout" class="btn btn-primary">Place your order</button>
                        </form>
                    </div>
                </div>

            <?php endif; ?>

        <?php endif; ?>
        
    </div>

    <div style="text-align: center; margin-top: 50px; padding: 30px; background: white; border-top: 1px solid #ddd; font-size: 0.8rem; color: #555;">
        &copy; 2026 CyberShop Inc. <br>
        For Educational Use Only.
    </div>

</body>
</html>