<?php
/**
 * CYBERSHOP PRO - MEDIUM DIFFICULTY LAB (PATCHED)
 * Vulnerability: HTTP Parameter Pollution (HPP)
 * * INSTRUCTIONS:
 * 1. Save as 'index.php' inside your folder
 * 2. If you see errors, click the "Reset Lab" link at the bottom or go to ?action=reset
 */

session_start();

// --- 1. CONFIGURATION ---
$products = [
    1 => ['name' => "Noise Cancelling Headphones", 'price' => 299.00, 'img' => '🎧', 'stock' => 'In Stock'],
    2 => ['name' => "Enterprise Firewall License", 'price' => 2000.00, 'img' => '🛡️', 'stock' => 'Digital Delivery'],
    3 => ['name' => "Dev Laptop Pro (32GB RAM)", 'price' => 1499.00, 'img' => '💻', 'stock' => 'Out of Stock'],
];
$target_product_id = 2; 
$flag_secret = "NodeZero{hidden_array_parameters_bypass_logic}";

// --- 2. SESSION INIT (ROBUST) ---
// We check each key individually to prevent "Undefined array key" errors
if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
if (!isset($_SESSION['used_coupons'])) { $_SESSION['used_coupons'] = []; }
if (!isset($_SESSION['discounts'])) { $_SESSION['discounts'] = []; }

// --- 3. CONTROLLERS ---

$page = $_GET['page'] ?? 'home';

// Action: Reset
if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Action: Add to Cart
if (isset($_POST['add_to_cart'])) {
    $pid = $_POST['product_id'];
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]++;
    } else {
        $_SESSION['cart'][$pid] = 1;
    }
    header("Location: ?page=cart");
    exit;
}

// Action: Apply Coupon
$alert_msg = "";
if (isset($_POST['apply_coupon'])) {
    $input = $_POST['coupon_code'];
    
    // Normalize input (String -> Array) OR keep Array (if hacked)
    $coupons_to_process = is_array($input) ? $input : [$input];

    $processed_count = 0;

    foreach ($coupons_to_process as $code) {
        $code = strtoupper(trim($code));

        if ($code === 'SAVE20') {
            // VULNERABILITY: Checks session history, not current batch duplicates
            if (!in_array('SAVE20', $_SESSION['used_coupons'])) {
                $_SESSION['discounts'][] = ['code' => 'SAVE20', 'amount' => 400.00];
                $processed_count++;
            }
        }
    }

    if ($processed_count > 0) {
        $_SESSION['used_coupons'][] = 'SAVE20';
        $alert_msg = "success|Discount Applied ($processed_count x SAVE20)";
    } else {
        $alert_msg = "error|Invalid code or code already used.";
    }
}

// Action: Checkout
$win = false;
if (isset($_POST['checkout'])) {
    $cart_total = 0;
    foreach ($_SESSION['cart'] as $pid => $qty) {
        // Safety check if product ID exists
        if(isset($products[$pid])) {
            $cart_total += $products[$pid]['price'] * $qty;
        }
    }
    $discount_total = 0;
    // Safety check for discounts array
    if(isset($_SESSION['discounts']) && is_array($_SESSION['discounts'])) {
        foreach ($_SESSION['discounts'] as $d) {
            $discount_total += $d['amount'];
        }
    }
    
    $final_total = max(0, $cart_total - $discount_total);

    if ($final_total == 0 && isset($_SESSION['cart'][$target_product_id])) {
        $win = true;
    } else {
        $alert_msg = "error|Transaction Declined: Invalid Amount.";
    }
}

// Helper: Calculate Totals for Display
$display_subtotal = 0;
foreach ($_SESSION['cart'] as $pid => $qty) {
    if(isset($products[$pid])) {
        $display_subtotal += $products[$pid]['price'] * $qty;
    }
}
$display_discount = 0;
if(isset($_SESSION['discounts']) && is_array($_SESSION['discounts'])) {
    foreach ($_SESSION['discounts'] as $d) {
        $display_discount += $d['amount'];
    }
}
$display_total = max(0, $display_subtotal - $display_discount);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CyberShop Pro | Enterprise Solutions</title>
    <style>
        :root { --primary: #0f172a; --accent: #3b82f6; --bg: #f8fafc; --text: #334155; --white: #fff; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); margin: 0; }
        .navbar { background: var(--primary); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        .logo { font-size: 1.5rem; font-weight: bold; text-decoration: none; color: white; }
        .logo span { color: var(--accent); }
        .cart-link { background: #1e293b; padding: 8px 15px; border-radius: 4px; text-decoration: none; color: white; border: 1px solid #334155; }
        .container { max-width: 1100px; margin: 30px auto; padding: 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
        .card { background: var(--white); border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e2e8f0; display: flex; flex-direction: column; }
        .card-img { height: 180px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 5rem; }
        .card-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
        .price { font-size: 1.5rem; font-weight: bold; color: var(--primary); margin: 10px 0; }
        .btn { background: var(--primary); color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; margin-top: auto; font-size: 1rem; }
        .btn:hover { background: #1e293b; }
        .btn-disabled { background: #cbd5e1; cursor: not-allowed; }
        .cart-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        .cart-items, .cart-summary { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem; }
        .total { border-top: 1px solid #e2e8f0; padding-top: 15px; margin-top: 10px; font-weight: bold; font-size: 1.2rem; color: var(--primary); }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .flag-box { background: #10b981; color: white; padding: 20px; text-align: center; font-weight: bold; border-radius: 6px; margin-top: 20px; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3); }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="?page=home" class="logo">Cyber<span>Shop</span> Pro</a>
        <div>
            <a href="?page=home" style="color:#cbd5e1; text-decoration:none; margin-right:20px;">Products</a>
            <a href="?page=cart" class="cart-link">🛒 Cart ($<?php echo number_format($display_total, 0); ?>)</a>
        </div>
    </nav>

    <div class="container">
        
        <?php if ($page === 'home'): ?>
            <div style="text-align:center; margin-bottom:40px;">
                <h1>Enterprise Solutions</h1>
                <p style="color:#64748b;">Secure your infrastructure with our premium tools.</p>
                <div style="background:#fff7ed; color:#c2410c; display:inline-block; padding:5px 15px; border-radius:20px; font-size:0.9rem; border:1px solid #ffedd5;">
                    🔥 Promo: Use code <strong>SAVE20</strong> for $400 off Enterprise items!
                </div>
            </div>

            <div class="grid">
                <?php foreach($products as $id => $p): ?>
                <div class="card">
                    <div class="card-img"><?php echo $p['img']; ?></div>
                    <div class="card-body">
                        <h3><?php echo $p['name']; ?></h3>
                        <div style="color:green; font-size:0.9rem; margin-bottom:5px;"><?php echo $p['stock']; ?></div>
                        <div class="price">$<?php echo number_format($p['price'], 2); ?></div>
                        <?php if($p['stock'] !== 'Out of Stock'): ?>
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <button name="add_to_cart" class="btn">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-disabled">Unavailable</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($page === 'cart'): ?>
            <h2>Your Cart</h2>

            <?php 
                if ($alert_msg) {
                    list($type, $msg) = explode('|', $alert_msg);
                    echo "<div class='alert alert-$type'>$msg</div>";
                }
            ?>

            <?php if (empty($_SESSION['cart'])): ?>
                <div style="text-align:center; padding:50px; background:white; border-radius:8px;">
                    <div style="font-size:3rem; margin-bottom:20px;">🛒</div>
                    <h3>Your cart is empty</h3>
                    <a href="?page=home" class="btn" style="width:auto; padding:10px 30px;">Continue Shopping</a>
                </div>
            <?php else: ?>
                
                <?php if($win): ?>
                    <div class="flag-box">
                        🎉 ORDER CONFIRMED! 🎉<br><br>
                        Flag: <?php echo $flag_secret; ?>
                    </div>
                    <div style="text-align:center; margin-top:20px;">
                        <a href="?action=reset" style="color:#64748b;">Reset Lab</a>
                    </div>
                <?php else: ?>

                <div class="cart-layout">
                    <div class="cart-items">
                        <?php foreach($_SESSION['cart'] as $pid => $qty): 
                            if(!isset($products[$pid])) continue;
                            $p = $products[$pid];
                        ?>
                        <div style="display:flex; gap:20px; border-bottom:1px solid #e2e8f0; padding-bottom:20px; margin-bottom:20px;">
                            <div style="font-size:3rem; width:80px; text-align:center;"><?php echo $p['img']; ?></div>
                            <div style="flex:1;">
                                <h4><?php echo $p['name']; ?></h4>
                                <div style="color:#64748b; font-size:0.9rem;">Qty: <?php echo $qty; ?></div>
                            </div>
                            <div style="font-weight:bold;">$<?php echo number_format($p['price'] * $qty, 2); ?></div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div style="text-align:right;">
                            <a href="?action=reset" style="color:#ef4444; font-size:0.9rem;">Clear Cart</a>
                        </div>
                    </div>

                    <div class="cart-summary">
                        <h3>Order Summary</h3>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>$<?php echo number_format($display_subtotal, 2); ?></span>
                        </div>
                        
                        <?php if($display_discount > 0): ?>
                        <div style="border-top:1px dashed #cbd5e1; margin:10px 0; padding-top:10px;">
                            <?php foreach($_SESSION['discounts'] as $d): ?>
                            <div class="summary-row" style="color:#16a34a;">
                                <span>Promo (<?php echo $d['code']; ?>)</span>
                                <span>-$<?php echo number_format($d['amount'], 2); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <div class="summary-row total">
                            <span>Total</span>
                            <span>$<?php echo number_format($display_total, 2); ?></span>
                        </div>

                        <form method="POST" style="margin-top:20px; background:#f1f5f9; padding:15px; border-radius:6px;">
                            <label style="font-size:0.85rem; font-weight:bold; display:block; margin-bottom:5px;">Promo Code</label>
                            <div style="display:flex; gap:10px;">
                                <input type="text" name="coupon_code" placeholder="Code" style="flex:1; padding:8px; border:1px solid #cbd5e1; border-radius:4px;">
                                <button name="apply_coupon" class="btn" style="width:auto; padding:8px 15px;">Apply</button>
                            </div>
                        </form>

                        <form method="POST" style="margin-top:20px;">
                            <button name="checkout" class="btn">Place Order</button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>

        <?php endif; ?>
    </div>

</body>
</html>