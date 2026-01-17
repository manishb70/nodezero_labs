<?php
// login.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Credentials: wiener : peter
    if ($username === 'wiener' && $password === 'peter') {
        // VULNERABILITY: Setting the role in a cookie that the user can see and change.
        // In real life, this should be stored in a server-side Session.
        setcookie("Admin", "false", time() + 3600, "/");
        setcookie("User", "wiener", time() + 3600, "/");
        
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OmniCorp - Secure Login</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #eef2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 350px; }
        h2 { margin-top: 0; color: #333; text-align: center; }
        .logo { text-align: center; margin-bottom: 20px; font-weight: bold; color: #0056b3; font-size: 24px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #0056b3; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px;}
        button:hover { background: #004494; }
        .error { color: red; font-size: 14px; text-align: center; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="logo">OmniCorp Portal</div>
    <h2>Employee Login</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required value="wiener">
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign In</button>
    </form>
    <div class="footer">Authorized Personnel Only</div>
</div>

</body>
</html>