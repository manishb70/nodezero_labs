<?php
session_start();
if (!isset($_SESSION['user'])) header("Location: index.php");

$flag = "";
$cost = 1000000; // 1 Million Dollars

if (isset($_POST['buy_flag'])) {
    if ($_SESSION['balance'] >= $cost) {
        $_SESSION['balance'] -= $cost;
        $flag = "NodeZero{unlimi7ed_funds_logic_bypass}";
    } else {
        $error = "You are too poor for this service.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VIP Services - Fortress Bank</title>
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
            <a href="transfer.php">Transfer Funds</a>
            <a href="vip.php" class="active">VIP Services</a>
        </div>

        <div class="content">
            <h2>Platinum VIP Area</h2>
            <p>Exclusive services for our high-net-worth individuals.</p>

            <div class="card" style="text-align: center; border: 2px solid #eab308;">
                <h3 style="color: #ca8a04;">Purchase "The Golden Secret"</h3>
                <p>Unlock the secrets of the banking elite.</p>
                <div style="font-size: 2rem; font-weight: bold; margin: 20px 0;">$1,000,000.00</div>
                
                <?php if($flag): ?>
                    <div style="background:#dcfce7; padding:20px; border-radius:8px; margin-bottom:20px;">
                        <strong>TRANSACTION APPROVED</strong><br>
                        Flag: <code><?php echo $flag; ?></code>
                    </div>
                <?php else: ?>
                    <form method="POST">
                        <button name="buy_flag" class="btn" style="background:#ca8a04;">Purchase Access</button>
                    </form>
                    <?php if(isset($error)) echo "<p style='color:red; margin-top:10px;'>$error</p>"; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>