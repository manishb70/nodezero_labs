<?php
// store.php
session_start();

// 1. Initialize User State (Starting Money)
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 100.00; // You start with $100
    $_SESSION['inventory'] = [];
}

// 2. Reset Button Logic (In case you break the economy)
if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: store.php");
    exit;
}

// 3. Define Products
$products = [
    'sticker' => [
        'name' => 'Hacker Sticker',
        'price' => 10,
        'desc' => 'A cool laptop sticker.',
        'img' => '💻'
    ],
    'flag' => [
        'name' => 'The Golden Flag',
        'price' => 2000, // Too expensive for you!
        'desc' => 'The goal of this level.',
        'img' => '🚩'
    ]
];

$message = "";
$message_type = ""; // success or error
$flag_reveal = "";

// 4. TRANSACTION LOGIC (VULNERABLE AREA)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // LOGIC FLAW: We check if it's a number, but we FORGET to check if it is positive!
    // A secure system would have: if ($quantity <= 0) die("Invalid quantity");
    
    if (isset($products[$item_id]) && is_numeric($quantity)) {
        
        $price = $products[$item_id]['price'];
        $total_cost = $price * $quantity;

        // Check if user has enough money
        if ($_SESSION['balance'] >= $total_cost) {
            
            // Deduct money (Subtracting a negative number ADDS money!)
            $_SESSION['balance'] -= $total_cost;
            
            // Add to inventory (Visual only)
            if (!isset($_SESSION['inventory'][$item_id])) $_SESSION['inventory'][$item_id] = 0;
            $_SESSION['inventory'][$item_id] += $quantity;

            $message = "Transaction Successful! You bought $quantity x {$products[$item_id]['name']}.";
            $message_type = "success";
            
            // CHECK WIN CONDITION
            if ($item_id === 'flag' && $quantity > 0) {
                $flag_reveal = "NodeZero{n3gative_numb3rs_p0sitive_pr0fit}";
            }

        } else {
            $message = "Transaction Failed: Insufficient Funds.";
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CyberMarket - Dark Net Store</title>
    <style>
        /* Dark Theme Styling */
        body { font-family: 'Courier New', monospace; background-color: #0d1117; color: #c9d1d9; padding: 20px; }
        .navbar { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #30363d; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 1.5rem; font-weight: bold; color: #58a6ff; }
        .balance-box { font-size: 1.2rem; background: #21262d; padding: 10px 20px; border-radius: 6px; border: 1px solid #30363d; }
        .money { color: #2ea043; font-weight: bold; }
        
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: #161b22; border: 1px solid #30363d; border-radius: 6px; padding: 20px; text-align: center; }
        .icon { font-size: 4rem; display: block; margin-bottom: 10px; }
        .price-tag { display: block; font-size: 1.5rem; color: #e3b341; margin: 10px 0; }
        
        input[type="number"] { background: #0d1117; border: 1px solid #30363d; color: white; padding: 8px; width: 60px; text-align: center; }
        button { background: #238636; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: bold; margin-left: 5px; }
        button:hover { background: #2ea043; }
        
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; border: 1px solid transparent; text-align: center; }
        .success { background: rgba(46, 160, 67, 0.15); border-color: rgba(46, 160, 67, 0.4); color: #3fb950; }
        .error { background: rgba(248, 81, 73, 0.15); border-color: rgba(248, 81, 73, 0.4); color: #ff7b72; }

        .flag-modal { background: #1f6feb; color: white; padding: 20px; margin-top: 20px; border-radius: 8px; text-align: center; font-size: 1.2rem; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(31, 111, 235, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(31, 111, 235, 0); } 100% { box-shadow: 0 0 0 0 rgba(31, 111, 235, 0); } }
        
        .reset-link { color: #8b949e; font-size: 0.8rem; text-decoration: none; }
        .reset-link:hover { color: #58a6ff; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">CyberMarket_v1.0</div>
        <div class="balance-box">
            Wallet: <span class="money">$<?php echo number_format($_SESSION['balance'], 2); ?></span>
        </div>
        <a href="?reset=1" class="reset-link">Reset Balance</a>
    </nav>

    <?php if ($message): ?>
        <div class="alert <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($flag_reveal): ?>
        <div class="flag-modal">
            🎉 <strong>SYSTEM HACKED!</strong> 🎉<br>
            Here is your flag: <br>
            <code><?php echo $flag_reveal; ?></code>
        </div>
    <?php endif; ?>

    <div class="grid">
        <div class="card">
            <span class="icon"><?php echo $products['sticker']['img']; ?></span>
            <h3><?php echo $products['sticker']['name']; ?></h3>
            <p><?php echo $products['sticker']['desc']; ?></p>
            <span class="price-tag">$<?php echo $products['sticker']['price']; ?></span>
            
            <form method="POST">
                <input type="hidden" name="item_id" value="sticker">
                <label>Qty:</label>
                <input type="number" name="quantity" value="1">
                <button type="submit">Buy</button>
            </form>
        </div>

        <div class="card" style="border-color: #e3b341;">
            <span class="icon"><?php echo $products['flag']['img']; ?></span>
            <h3><?php echo $products['flag']['name']; ?></h3>
            <p><?php echo $products['flag']['desc']; ?></p>
            <span class="price-tag">$<?php echo number_format($products['flag']['price']); ?></span>
            
            <form method="POST">
                <input type="hidden" name="item_id" value="flag">
                <label>Qty:</label>
                <input type="number" name="quantity" value="1">
                <button type="submit">Buy</button>
            </form>
        </div>
    </div>

</body>
</html>