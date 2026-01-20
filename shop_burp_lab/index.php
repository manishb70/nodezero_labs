<?php
/**
 * TITANIUM STORE - BURP SUITE INTERCEPTION LAB
 * Difficulty: Medium
 * Vulnerability: JSON Parameter Tampering (API Logic Flaw)
 * Goal: Intercept the AJAX request and buy the phone for ₹1.
 */

session_start();

$FLAG = "NodeZero{js0n_tampering_via_burp_suite_success}";

// --- API: HANDLE PAYMENT (This is what you intercept) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['api']) && $_GET['api'] === 'pay') {
    
    // Read JSON input
    $json_str = file_get_contents('php://input');
    $data = json_decode($json_str, true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
        exit;
    }

    // VULNERABILITY: 
    // The server blindly trusts the 'amount' sent in the JSON packet.
    $amount_paid = (float) $data['amount'];
    $product_name = $data['product'];

    // Generate Fake Order ID
    $order_id = "ORD-" . rand(100000, 999999);

    // Save order to session for the receipt page
    $_SESSION['order'] = [
        'id' => $order_id,
        'product' => $product_name,
        'amount' => $amount_paid,
        'timestamp' => date("Y-m-d H:i:s")
    ];

    // Response
    echo json_encode(["status" => "success", "redirect" => "?page=receipt"]);
    exit;
}

// --- CONTROLLER: PAGE ROUTING ---
$page = $_GET['page'] ?? 'home';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titanium | The Future of Smartphones</title>
    <style>
        /* Premium Dark Theme (Apple/Cyberpunk Style) */
        :root { --bg: #000000; --text: #f5f5f7; --accent: #2997ff; --card: #1c1c1e; }
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: var(--bg); color: var(--text); }
        
        /* Navbar */
        .navbar { display: flex; justify-content: space-between; padding: 20px 40px; align-items: center; background: rgba(28,28,30, 0.8); backdrop-filter: blur(10px); position: sticky; top:0; z-index: 100; border-bottom: 1px solid #333; }
        .logo { font-weight: 700; font-size: 1.2rem; letter-spacing: 1px; }
        .menu a { color: #86868b; text-decoration: none; margin-left: 20px; font-size: 0.9rem; transition: 0.3s; }
        .menu a:hover { color: #fff; }

        /* Container */
        .container { max-width: 1100px; margin: 0 auto; padding: 40px 20px; }

        /* Product Section */
        .hero { display: flex; align-items: center; justify-content: space-between; gap: 50px; min-height: 70vh; }
        .product-info h1 { font-size: 4rem; line-height: 1.1; margin-bottom: 20px; background: linear-gradient(90deg, #fff, #86868b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .price-tag { font-size: 2.5rem; font-weight: 600; margin: 30px 0; color: var(--text); }
        .btn-buy { background: var(--accent); color: white; border: none; padding: 15px 40px; border-radius: 30px; font-size: 1.2rem; cursor: pointer; transition: transform 0.2s; font-weight: 600; }
        .btn-buy:hover { transform: scale(1.05); background: #007aff; }

        /* Image Placeholder */
        .phone-mockup { width: 400px; height: 600px; background: linear-gradient(145deg, #333, #111); border-radius: 40px; border: 4px solid #444; position: relative; box-shadow: 0 0 50px rgba(41, 151, 255, 0.2); display: flex; align-items: center; justify-content: center; color: #555; font-size: 1.5rem; }
        
        /* Modal (Checkout) */
        #checkoutModal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); z-index: 200; align-items: center; justify-content: center; }
        .modal-content { background: var(--card); padding: 40px; border-radius: 20px; width: 400px; text-align: center; border: 1px solid #333; position: relative; }
        .summary-row { display: flex; justify-content: space-between; margin: 15px 0; font-size: 1.1rem; color: #aaa; }
        .total-row { color: #fff; font-weight: bold; border-top: 1px solid #444; padding-top: 20px; margin-top: 20px; font-size: 1.4rem; }
        .loader { display: none; margin: 20px auto; border: 4px solid #333; border-top: 4px solid var(--accent); border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Receipt */
        .receipt-card { background: white; color: black; padding: 40px; border-radius: 4px; max-width: 500px; margin: 40px auto; font-family: 'Courier New', monospace; box-shadow: 0 10px 40px rgba(0,0,0,0.5); transform: rotate(-1deg); }
        .flag-success { background: #1c1c1e; color: #00ff00; padding: 20px; margin-top: 20px; border-radius: 8px; font-family: monospace; text-align: center; border: 1px solid #00ff00; }
        .fail-msg { background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-top: 20px; text-align: center; }

    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">TITANIUM</div>
        <div class="menu">
            <a href="?page=home">Store</a>
            <a href="#">Mac</a>
            <a href="#">iPad</a>
            <a href="#">iPhone</a>
            <a href="#">Watch</a>
        </div>
    </nav>

    <?php if ($page === 'home'): ?>
    <div class="container">
        <div class="hero">
            <div class="product-info">
                <span style="color: #orange; font-weight: bold; letter-spacing: 2px; font-size: 0.9rem; text-transform: uppercase; color: #f5a623;">New Arrival</span>
                <h1>iPhone 15 Pro Max.<br>Titanium.</h1>
                <p style="color: #aaa; font-size: 1.2rem; max-width: 500px; line-height: 1.5;">
                    The first iPhone to feature an aerospace-grade titanium design, using the same alloy that spacecraft use for missions to Mars.
                </p>
                <div class="price-tag">₹1,59,900.00</div>
                <button class="btn-buy" onclick="openCheckout()">Buy Now</button>
            </div>
            <div class="phone-mockup">
                [ Phone Image ]
            </div>
        </div>
    </div>

    <div id="checkoutModal">
        <div class="modal-content">
            <h2 style="margin-top: 0;">Order Summary</h2>
            <div style="text-align: left; margin-top: 30px;">
                <div class="summary-row">
                    <span>iPhone 15 Pro Max (1TB)</span>
                    <span>₹1,59,900</span>
                </div>
                <div class="summary-row">
                    <span>AppleCare+</span>
                    <span>₹0</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>FREE</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total</span>
                    <span>₹1,59,900</span>
                </div>
            </div>

            <div style="margin-top: 30px; background: #2c2c2e; padding: 15px; border-radius: 10px; display: flex; align-items: center; gap: 15px;">
                <div style="background: #fff; width: 40px; height: 25px; border-radius: 4px;"></div>
                <div style="text-align: left;">
                    <div style="font-size: 0.9rem; font-weight: bold;">HDFC Bank Credit Card</div>
                    <div style="font-size: 0.8rem; color: #aaa;">•••• 8821</div>
                </div>
            </div>

            <div class="loader" id="spinner"></div>

            <button class="btn-buy" id="payBtn" style="width: 100%; margin-top: 30px;" onclick="processPayment()">
                Pay ₹1,59,900
            </button>
            <button onclick="closeCheckout()" style="background: none; border: none; color: #86868b; margin-top: 15px; cursor: pointer;">Cancel</button>
        </div>
    </div>

    <script>
        function openCheckout() {
            document.getElementById('checkoutModal').style.display = 'flex';
        }
        function closeCheckout() {
            document.getElementById('checkoutModal').style.display = 'none';
        }

        function processPayment() {
            // UI Feedback
            document.getElementById('payBtn').style.display = 'none';
            document.getElementById('spinner').style.display = 'block';

            // DATA TO SEND
            // Vulnerability: We define the price here in JavaScript!
            // Burp Suite can intercept this request before it hits the server.
            const payload = {
                product: "iPhone 15 Pro Max",
                amount: 159900  // <--- TARGET FOR INTERCEPTION
            };

            // SEND AJAX REQUEST
            fetch('?api=pay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    alert("Payment Failed");
                }
            });
        }
    </script>

    <?php elseif ($page === 'receipt'): ?>
    <?php 
        $order = $_SESSION['order'] ?? null; 
        if(!$order) echo "<script>window.location='?page=home'</script>";
    ?>
    <div class="container" style="text-align: center;">
        <h1 style="margin-bottom: 10px;">Order Confirmed</h1>
        <p style="color: #aaa;">Thank you for your purchase.</p>

        <div class="receipt-card">
            <div style="border-bottom: 2px dashed #000; padding-bottom: 20px; margin-bottom: 20px;">
                <h2 style="margin: 0;">TITANIUM STORE</h2>
                <p style="margin: 5px 0; font-size: 0.9rem;">Electronic Receipt</p>
                <p style="margin: 0; font-size: 0.8rem;"><?php echo $order['id']; ?></p>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span>ITEM</span>
                <span>AMT</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-weight: bold;">
                <span><?php echo $order['product']; ?></span>
                <span>₹<?php echo number_format($order['amount'], 2); ?></span>
            </div>
            
            <div style="border-top: 2px dashed #000; margin-top: 20px; padding-top: 20px; display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold;">
                <span>TOTAL PAID:</span>
                <span>₹<?php echo number_format($order['amount'], 2); ?></span>
            </div>
        </div>

        <?php if ($order['amount'] <= 100): ?>
            <div class="flag-success">
                <strong>SYSTEM ALERT: PRICE MANIPULATION DETECTED (BUT ACCEPTED!)</strong><br><br>
                Congratulations! You bought it for almost free.<br>
                FLAG: <code><?php echo $FLAG; ?></code>
            </div>
        <?php else: ?>
            <div class="fail-msg">
                <strong>You paid full price (₹1,59,900).</strong><br>
                Try again! Use Burp Suite to intercept the payment request and lower the amount.
            </div>
            <a href="?page=home" style="color: var(--accent); margin-top: 20px; display: inline-block;">Try Again</a>
        <?php endif; ?>

    </div>
    <?php endif; ?>

</body>
</html>