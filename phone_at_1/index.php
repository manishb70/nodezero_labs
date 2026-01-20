<?php
/**
 * ELECTRO-KART - REALISTIC SHOPPING LAB
 * Vulnerability: Price Parameter Tampering (Business Logic Flaw)
 * Goal: Buy the iPhone for ₹1 instead of the real price.
 */

session_start();

// --- CONFIGURATION ---
$REAL_PRICE = 159900.00;
$PRODUCT_NAME = "Apple iPhone 15 Pro Max (1 TB) - Titanium";
$FLAG = "NodeZero{d0nt_trust_us3r_input_f0r_pr1c3s}";

// --- ROUTER ---
$page = $_GET['page'] ?? 'product';

// --- CONTROLLERS ---

// 1. Process Payment (Backend Logic)
if ($page === 'process_payment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // VULNERABILITY IS HERE:
    // The server is accepting the price directly from the POST request
    // instead of looking up the real price in a database based on product ID.
    $charged_amount = (float)$_POST['final_price_input'];
    $product_ordered = $_POST['product_name_input'];

    // Simulate Payment Gateway Success
    $order_id = "ORD-" . strtoupper(bin2hex(random_bytes(4)));
    $_SESSION['latest_order'] = [
        'id' => $order_id,
        'product' => $product_ordered,
        'amount_paid' => $charged_amount,
        'date' => date("F j, Y, g:i a")
    ];

    // Redirect to confirmation
    header("Location: ?page=confirmation");
    exit;
}

// Order Data for confirmation page
$order = $_SESSION['latest_order'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElectroKart | Premium Electronics</title>
    <style>
        /* Modern E-Commerce CSS Theme */
        :root { --primary: #0071e3; --dark: #1d1d1f; --light: #f5f5f7; --success: #28a745; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; background: var(--light); color: var(--dark); }
        
        /* Navbar */
        .navbar { background: rgba(0,0,0,0.8); backdrop-filter: blur(20px); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; position: sticky; top:0; z-index: 10; }
        .brand { font-weight: 600; font-size: 1.2rem; letter-spacing: -0.5px; }
        .brand span { color: #4facfe; }
        .nav-items span { margin-left: 20px; font-size: 0.9rem; color: #ccc; cursor: pointer; }

        /* Layout */
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; padding: 40px; }
        
        /* Product Layout */
        .product-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
        .product-image { text-align: center; }
        .product-image img { max-width: 100%; height: auto; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2)); }
        
        /* Typography */
        h1 { margin-top: 0; font-size: 2.5rem; }
        .price { font-size: 2rem; font-weight: 600; color: var(--dark); margin: 20px 0; }
        .price sup { font-size: 1rem; }
        .stock-tag { display: inline-block; background: #e5f9e7; color: var(--success); padding: 5px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-bottom: 15px; }

        /* Buttons */
        .btn { background: var(--primary); color: white; border: none; padding: 15px 30px; border-radius: 30px; font-size: 1.1rem; font-weight: 600; cursor: pointer; width: 100%; transition: 0.2s; }
        .btn:hover { background: #005bb5; transform: scale(1.02); }
        
        /* Checkout Styles */
        .checkout-summary { background: var(--light); padding: 30px; border-radius: 12px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 1.1rem; }
        .total-row { font-weight: 700; font-size: 1.4rem; border-top: 1px solid #ddd; padding-top: 20px; }

        /* Confirmation Styles */
        .success-header { text-align: center; color: var(--success); }
        .checkmark { font-size: 5rem; margin-bottom: 20px; }
        .receipt-box { background: white; border: 1px dashed #ccc; padding: 30px; margin-top: 30px; border-radius: 12px; }
        .flag-alert { background: var(--dark); color: #00ff00; padding: 20px; text-align: center; font-family: monospace; margin-top: 30px; border-radius: 8px; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="brand">Electro<span>Kart</span></div>
        <div class="nav-items">
            <span>Store</span>
            <span>Mac</span>
            <span>iPhone</span>
            <span>Support</span>
            <span>🛒</span>
        </div>
    </nav>

    <div class="container">

        <?php if ($page === 'product'): ?>
            <div class="card product-grid">
                <div class="product-image">
                    <img src="https://store.storeimages.cdn-apple.com/4668/as-images.apple.com/is/iphone-15-pro-finish-select-202309-6-7inch-naturaltitanium?wid=5120&hei=2880&fmt=p-jpg&qlt=80&.v=1692845785453" alt="iPhone 15 Pro Max">
                </div>
                <div>
                    <span class="stock-tag">In Stock & Ready to Ship</span>
                    <h1><?php echo $PRODUCT_NAME; ?></h1>
                    <p style="color: #666; line-height: 1.6;">
                        The ultimate iPhone. Forged in titanium. Featuring the groundbreaking A17 Pro chip, a customizable Action button, and the most powerful iPhone camera system ever.
                    </p>
                    <div class="price">
                        <sup>₹</sup><?php echo number_format($REAL_PRICE); ?>
                    </div>
                    <div style="margin-bottom: 30px; font-size: 0.9rem; color: #666;">
                        Applying for finance? EMI starts at ₹7,500/mo.
                    </div>
                    <a href="?page=checkout">
                        <button class="btn">Buy Now</button>
                    </a>
                    <p style="text-align: center; font-size: 0.9rem; margin-top: 15px; color: #888;">Free delivery by tomorrow.</p>
                </div>
            </div>


        <?php elseif ($page === 'checkout'): ?>
            <div class="card">
                 <h2>Checkout Securely</h2>
                 <div class="product-grid" style="align-items: start;">
                    <div>
                        <h3>Shipping Address</h3>
                        <div style="background: var(--light); padding: 20px; border-radius: 12px; color: #555;">
                            <strong>John Doe</strong><br>
                            123 Cyber Street, Tech Park<br>
                            Bangalore, KA 560100<br>
                            India
                        </div>
                        <h3>Payment Method</h3>
                        <div style="background: var(--light); padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 10px;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/MasterCard_Logo.svg/200px-MasterCard_Logo.svg.png" width="40">
                            <span>Mastercard ending in •••• 8821</span>
                        </div>
                    </div>

                    <div class="checkout-summary">
                        <h3>Order Summary</h3>
                        <div class="summary-row">
                            <span><?php echo $PRODUCT_NAME; ?> x 1</span>
                            <span>₹<?php echo number_format($REAL_PRICE, 2); ?></span>
                        </div>
                        <div class="summary-row" style="color: var(--success);">
                            <span>Delivery</span>
                            <span>FREE</span>
                        </div>
                        <div class="summary-row total-row">
                            <span>Total to Pay</span>
                            <span>₹<?php echo number_format($REAL_PRICE, 2); ?></span>
                        </div>
                        
                        <form action="?page=process_payment" method="POST">
                            <input type="hidden" name="product_name_input" value="<?php echo $PRODUCT_NAME; ?>">
                            <input type="hidden" name="final_price_input" value="<?php echo $REAL_PRICE; ?>">
                            
                            <button type="submit" class="btn" style="margin-top: 20px;">Example Pay ₹<?php echo number_format($REAL_PRICE); ?></button>
                        </form>
                        <p style="text-align: center; font-size: 0.8rem; margin-top: 15px; color: #888;">
                            By confirming, you agree to ElectroKart's Terms of Sale.
                        </p>
                    </div>
                 </div>
            </div>


        <?php elseif ($page === 'confirmation' && $order): ?>
            <div class="card">
                <div class="success-header">
                    <div class="checkmark">🎉</div>
                    <h1>Order Placed Successfully!</h1>
                    <p style="font-size: 1.2rem; color: var(--dark);">Thank you for your purchase, John.</p>
                </div>

                <div class="receipt-box">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
                        <div>
                            <strong>Order Number:</strong><br>
                            #<?php echo $order['id']; ?>
                        </div>
                        <div style="text-align: right;">
                            <strong>Date:</strong><br>
                            <?php echo $order['date']; ?>
                        </div>
                    </div>
                    <h3>Item Details</h3>
                    <div class="summary-row">
                        <span><?php echo $order['product']; ?></span>
                        <strong>₹<?php echo number_format($order['amount_paid'], 2); ?></strong>
                    </div>
                    <div class="summary-row total-row" style="margin-top: 20px;">
                        <span>Amount Paid On Card ending 8821:</span>
                        <span>₹<?php echo number_format($order['amount_paid'], 2); ?></span>
                    </div>
                </div>

                <?php if ($order['amount_paid'] <= 1.00 && $order['amount_paid'] > 0): ?>
                    <div class="flag-alert">
                        WAIT A MINUTE... YOU PAID LESS THAN ₹1? <br>
                        NICE HACK!<br><br>
                        FLAG: <code><?php echo $FLAG; ?></code>
                    </div>
                <?php endif; ?>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="?page=product" style="text-decoration: none; color: var(--primary);">Continue Shopping</a>
                </div>
            </div>
        <?php else: ?>
            <script>window.location.href = "?page=product";</script>
        <?php endif; ?>

    </div>
    <div style="text-align: center; padding: 30px; color: #888; font-size: 0.9rem;">
        © 2024 ElectroKart Inc. All rights reserved. This is a CTF Lab demo.
    </div>
</body>
</html>