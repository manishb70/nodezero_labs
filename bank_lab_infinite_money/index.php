<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === 'guest' && $_POST['password'] === 'password') {
        $_SESSION['user'] = 'Guest User';
        $_SESSION['balance'] = 100.00; // Starting Balance
        $_SESSION['transactions'] = [
            ['date' => date('Y-m-d'), 'desc' => 'Opening Balance', 'amount' => 100.00]
        ];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid Access ID or Password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fortress Bank - Secure Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { justify-content: center; align-items: center; background: #e2e8f0; }
        .login-box { background: white; width: 400px; padding: 2.5rem; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
        .logo { text-align: center; margin-bottom: 2rem; font-size: 1.8rem; font-weight: 800; color: #0f172a; }
        .logo span { color: #2563eb; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">Fortress<span>Bank</span></div>
        <?php if(isset($error)) echo "<p style='color:red; text-align:center'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label>Access ID</label>
                <input type="text" name="username" class="form-control" value="guest">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="password">
            </div>
            <button class="btn" style="width:100%">Secure Login</button>
        </form>
        <p style="text-align:center; margin-top:20px; color:#64748b; font-size:0.9rem;">
            Authorized Personnel Only. <br>System Version 4.2.1
        </p>
    </div>
</body>
</html>