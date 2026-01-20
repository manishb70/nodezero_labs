<?php
/**
 * CHRONOS LUXURY - PAYMENT BYPASS LAB
 * Vulnerability: Payment Status Tampering (Insecure Callback)
 * Goal: Change the payment status from FAIL to SUCCESS using Burp Suite.
 */

session_start();

$FLAG = "NodeZero{paym3nt_gat3way_resp0nse_man1pulat3d}";
$PRODUCT_NAME = "Rolex Submariner Date";
$PRICE = 1250000; // ₹12.5 Lakhs

// --- CONTROLLER ---
$page = $_GET['page'] ?? 'home';

// 1. PAYMENT CALLBACK (VULNERABLE ENDPOINT)
// This is where the "Payment Gateway" sends the user back to after a transaction.
if ($page === 'callback' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // VULNERABILITY: 
    // The server trusts the POST parameter 'txn_status' coming from the user's browser.
    // In a real secure system, this would be a server-to-server call or use a signed hash.
    
    $status = $_POST['txn_status']; // Expected: SUCCESS or FAIL
    $txnid = $_POST['txn_id'];

    if ($status === 'SUCCESS') {
        // WIN CONDITION
        $_SESSION['receipt'] = [
            'id' => $txnid,
            'product' => $PRODUCT_NAME,
            'amount' => $PRICE,
            'status' => 'Paid'
        ];
        header("Location: ?page=receipt");
        exit;
    } else {
        // FAIL CONDITION
        $error_msg = "Transaction Declined by Bank.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chronos | Swiss Luxury</title>
    <style>
        /* Luxury Dark Theme */
        :root { --bg: #0b0b0b; --card: #151515; --text: #e0e0e0; --gold: #d4af37; --red: #c0392b; }
        body { margin: 0; font-family: 'Playfair Display', serif; background: var(--bg); color: var(--text); }
        
        .navbar { padding: 30px; text-align: center; border-bottom: 1px solid #333; letter-spacing: 2px; }
        .logo { font-size: 2rem; color: var(--gold); font-weight: bold; text-transform: uppercase; }

        .container { max-width: 900px; margin: 50px auto; padding: 20px; }
        
        /* Product Card */
        .product-card { display: flex; background: var(--card); border: 1px solid #333; border-radius: 4px; overflow: hidden; }
        .product-img { width: 50%; background: #222; display: flex; align-items: center; justify-content: center; font-size: 10rem; color: #333; }
        .product-details { padding: 50px; width: 50%; display: flex; flex-direction: column; justify-content: center; }
        
        h1 { margin: 0 0 20px 0; font-size: 2.5rem; color: white; }
        .price { font-size: 2rem; color: var(--gold); margin-bottom: 30px; font-family: sans-serif; }
        
        .btn { background: var(--gold); color: black; border: none; padding: 15px 30px; font-size: 1rem; font-weight: bold; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; width: 100%; display: block; text-decoration: none; text-align: center; }
        .btn:hover { background: #b59230; }

        /* Fake Gateway Styles */
        .gateway-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.9); display: flex; align-items: center; justify-content: center; z-index: 100; }
        .gateway-box { background: white; color: black; padding: 40px; border-radius: 8px; width: 400px; text-align: center; font-family: sans-serif; position: relative; }
        .loader { border: 5px solid #f3f3f3; border-top: 5px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 20px auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Receipt */
        .receipt-box { background: var(--card); border: 2px solid var(--gold); padding: 40px; text-align: center; max-width: 500px; margin: 0 auto; }
        .flag-box { margin-top: 30px; border: 1px dashed var(--gold); padding: 20px; color: var(--gold); font-family: monospace; }
        
        /* Error */
        .error-box { background: #300; border: 1px solid var(--red); color: #ffcccc; padding: 20px; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">Chronos</div>
    </nav>

    <div class="container">
        
        <?php if ($page === 'home' || $page === 'callback'): ?>
            
            <?php if (isset($error_msg)): ?>
                <div class="error-box">
                    <strong>PAYMENT FAILED</strong><br>
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <div class="product-card">
                <div class="product-img">⌚</div>
                <div class="product-details">
                    <span style="color: #666; letter-spacing: 2px; font-size: 0.9rem;">SWISS MADE</span>
                    <h1><?php echo $PRODUCT_NAME; ?></h1>
                    <p style="color: #999; line-height: 1.6;">
                        Oystersteel. Black dial. Cerachrom bezel insert in black ceramic. The reference among divers' watches.
                    </p>
                    <div class="price">₹<?php echo number_format($PRICE); ?></div>
                    
                    <button class="btn" onclick="startPayment()">Purchase</button>
                </div>
            </div>

            <div id="gateway" style="display:none;" class="gateway-overlay">
                <div class="gateway-box">
                    <h2 style="margin-top:0;">Secure Payment</h2>
                    <p>Processing card ending 8821...</p>
                    <div id="loader" class="loader"></div>
                    
                    <div id="failMessage" style="display:none; color: #c0392b;">
                        <h3 style="margin-bottom: 10px;">❌ Declined</h3>
                        <p>Insufficient Funds.</p>
                        
                        <form action="?page=callback" method="POST" id="callbackForm">
                            <input type="hidden" name="txn_id" value="TXN-<?php echo rand(10000,99999); ?>">
                            <input type="hidden" name="txn_status" value="FAIL">
                            
                            <button type="submit" style="background:#c0392b; color:white; border:none; padding:10px 20px; cursor:pointer; margin-top:10px; border-radius:4px;">
                                Return to Merchant
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function startPayment() {
                    document.getElementById('gateway').style.display = 'flex';
                    
                    // Simulate processing delay
                    setTimeout(() => {
                        document.getElementById('loader').style.display = 'none';
                        document.getElementById('failMessage').style.display = 'block';
                    }, 2000);
                }
            </script>

        <?php elseif ($page === 'receipt'): ?>
            <div class="receipt-box">
                <h1 style="color: var(--gold);">Order Confirmed</h1>
                <p>Thank you for shopping with Chronos.</p>
                <div style="border-top: 1px solid #333; border-bottom: 1px solid #333; padding: 20px 0; margin: 20px 0;">
                    <div style="font-size: 1.2rem; margin-bottom: 10px;"><?php echo $_SESSION['receipt']['product']; ?></div>
                    <div style="font-size: 1.5rem; font-family: sans-serif;">PAID: ₹<?php echo number_format($_SESSION['receipt']['amount']); ?></div>
                    <div style="color: #666; font-size: 0.9rem; margin-top: 10px;">ID: <?php echo $_SESSION['receipt']['id']; ?></div>
                </div>
                
                <div class="flag-box">
                    <strong>PAYMENT STATUS BYPASSED!</strong><br><br>
                    FLAG: <?php echo $FLAG; ?>
                </div>
                
                <a href="?page=home" style="color: white; margin-top: 20px; display: inline-block;">Back to Store</a>
            </div>

        <?php endif; ?>

    </div>
</body>
</html>