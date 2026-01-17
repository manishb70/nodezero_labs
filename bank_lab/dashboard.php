<?php
session_start();
if (!isset($_SESSION['user'])) header("Location: index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account - Fortress Bank</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="brand">Fortress<span>Bank</span></div>
        <div class="nav-links">
            <span>Welcome, <?php echo $_SESSION['user']; ?></span>
            <a href="logout.php">Sign Out</a>
        </div>
    </nav>

    <div class="main-layout">
        <div class="sidebar">
            <a href="dashboard.php" class="active">Account Summary</a>
            <a href="transfer.php">Transfer Funds</a>
            <a href="vip.php">VIP Services</a> </div>

        <div class="content">
            <h2 style="margin-top:0;">Account Overview</h2>
            
            <div class="card balance-card">
                <div class="balance-label">Available Balance</div>
                <div class="balance-amount">$<?php echo number_format($_SESSION['balance'], 2); ?></div>
                <div>Account #: ****-****-8821</div>
            </div>

            <div class="card">
                <h3>Recent Transactions</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_reverse($_SESSION['transactions']) as $t): ?>
                        <tr>
                            <td><?php echo $t['date']; ?></td>
                            <td><?php echo $t['desc']; ?></td>
                            <td class="<?php echo $t['amount'] > 0 ? 'amount-pos' : 'amount-neg'; ?>">
                                <?php echo $t['amount'] > 0 ? '+' : ''; ?><?php echo number_format($t['amount'], 2); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>