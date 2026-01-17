<?php
session_start();
if (!isset($_SESSION['user'])) header("Location: index.php");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient = $_POST['recipient'];
    $amount = $_POST['amount'];

    // VULNERABLE LOGIC CHECK
    // Developer checked if it is numeric, but forgot to check if it's POSITIVE!
    if (is_numeric($amount)) {
        
        // Standard Check: Do you have enough money?
        // Logic Flaw: If amount is -1000, and balance is 100...
        // 100 >= -1000 is TRUE. So this check passes.
        if ($_SESSION['balance'] >= $amount) {
            
            // Execute Transfer
            // 100 - (-1000) = 1100. You just GAINED money.
            $_SESSION['balance'] -= $amount;
            
            // Log it
            $_SESSION['transactions'][] = [
                'date' => date('Y-m-d H:i'), 
                'desc' => "Transfer to $recipient", 
                'amount' => -$amount
            ];

            $message = "<div style='color:green; padding:10px; background:#dcfce7; border-radius:6px; margin-bottom:15px;'>Transfer Successful!</div>";
        } else {
            $message = "<div style='color:red; padding:10px; background:#fee2e2; border-radius:6px; margin-bottom:15px;'>Insufficient Funds.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transfer Funds - Fortress Bank</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="brand">Fortress<span>Bank</span></div>
        <div class="nav-links"><a href="logout.php">Sign Out</a></div>
    </nav>

    <div class="main-layout">
        <div class="sidebar">
            <a href="dashboard.php">Account Summary</a>
            <a href="transfer.php" class="active">Transfer Funds</a>
            <a href="vip.php">VIP Services</a>
        </div>

        <div class="content">
            <h2>Wire Transfer</h2>
            <div class="card" style="max-width: 600px;">
                <?php echo $message; ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Recipient Name / Account #</label>
                        <select name="recipient" class="form-control">
                            <option>Corporate Payroll (8832-1102)</option>
                            <option>Alice (Friend)</option>
                            <option>Bob (Landlord)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Amount ($)</label>
                        <input type="text" name="amount" class="form-control" placeholder="0.00">
                        <small style="color:#64748b">Current Balance: $<?php echo number_format($_SESSION['balance'], 2); ?></small>
                    </div>
                    <button class="btn">Send Funds</button>
                </form>
            </div>
            
            <div class="card" style="background: #fffbeb; border-left: 4px solid #f59e0b;">
                <strong>Security Notice:</strong> Transfers over $10,000 require manual approval.
            </div>
        </div>
    </div>
</body>
</html>