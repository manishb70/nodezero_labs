<?php
/**
 * OMEGA CLOUD - ACCOUNT TAKEOVER LAB
 * Vulnerability: Password Reset Parameter Tampering
 * Goal: Take over 'admin', log in, and delete the database.
 */

session_start();

// --- CONFIGURATION ---
$FLAG = "NodeZero{p4ssw0rd_r3s3t_l0gic_bypass_succ3ss}";

// --- MOCK DATABASE ---
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [
        'admin' => ['password' => 'Sup3rS3cr3tAdminP@ss', 'role' => 'admin'],
        'guest' => ['password' => 'guest', 'role' => 'user']
    ];
}
// Mock Reset Tokens
if (!isset($_SESSION['tokens'])) {
    $_SESSION['tokens'] = [];
}

// --- CONTROLLERS ---

$page = $_GET['page'] ?? 'login';
$msg = "";
$msg_type = "";

// 1. HANDLE LOGIN
if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    
    if (isset($_SESSION['users'][$u]) && $_SESSION['users'][$u]['password'] === $p) {
        $_SESSION['logged_in_user'] = $u;
        $_SESSION['logged_in_role'] = $_SESSION['users'][$u]['role'];
        header("Location: ?page=dashboard");
        exit;
    } else {
        $msg = "Invalid Credentials";
        $msg_type = "error";
    }
}

// 2. HANDLE FORGOT PASSWORD (GENERATE TOKEN)
if (isset($_POST['request_reset'])) {
    $u = $_POST['username'];
    if (isset($_SESSION['users'][$u])) {
        // Generate a simple token
        $token = bin2hex(random_bytes(4));
        // Save token to session (In real life, this goes to DB)
        // Note: The system stores that A token exists, but maybe 
        // essentially trusts the "username" passed in the next step too much.
        $_SESSION['tokens'][$token] = $u; 
        
        // In a lab, we simply echo the link to the user
        $msg = "Reset Link Sent! (Check your 'email' below)<br>
        <a href='?page=reset&token=$token&user=$u'>Click to Reset Password</a>";
        $msg_type = "success";
    } else {
        $msg = "User not found.";
        $msg_type = "error";
    }
}

// 3. HANDLE NEW PASSWORD (THE VULNERABILITY)
if (isset($_POST['set_new_password'])) {
    $token = $_POST['token'];
    $new_pass = $_POST['new_password'];
    
    // VULNERABILITY:
    // The application checks if the token is valid.
    // BUT, it uses the POST parameter 'username' to decide WHO gets the new password.
    // It *should* look up the user associated with the token in the database.
    // Instead, it blindly trusts the form input.
    
    $target_user = $_POST['username']; // <--- ATTACK VECTOR
    
    if (isset($_SESSION['tokens'][$token])) {
        // Token is valid (it exists)
        
        // Update the password for the TARGET USER (chosen by hacker), not the token owner.
        $_SESSION['users'][$target_user]['password'] = $new_pass;
        
        // Invalidate token
        unset($_SESSION['tokens'][$token]);
        
        $msg = "Password successfully changed for user: <strong>$target_user</strong>. <a href='?page=login'>Login Now</a>";
        $msg_type = "success";
    } else {
        $msg = "Invalid or Expired Token.";
        $msg_type = "error";
    }
}

// 4. DELETE ACTION (WIN CONDITION)
if ($page === 'delete_db') {
    if (!isset($_SESSION['logged_in_role']) || $_SESSION['logged_in_role'] !== 'admin') {
        die("ACCESS DENIED");
    }
    // Win!
    $page = 'flag';
}

// LOGOUT
if ($page === 'logout') {
    session_destroy();
    header("Location: ?page=login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Omega Cloud | Admin Panel</title>
    <style>
        :root { --primary: #4f46e5; --bg: #f3f4f6; --text: #1f2937; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        
        .card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .logo { font-size: 1.5rem; font-weight: bold; color: var(--primary); text-align: center; margin-bottom: 20px; }
        
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        button:hover { background: #4338ca; }
        
        .alert { padding: 10px; border-radius: 6px; margin-bottom: 15px; text-align: center; font-size: 0.9rem; word-break: break-all; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        
        .link { text-align: center; margin-top: 15px; font-size: 0.9rem; }
        .link a { color: var(--primary); text-decoration: none; }
        
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .danger-zone { border: 2px dashed #ef4444; padding: 20px; border-radius: 8px; text-align: center; background: #fef2f2; margin-top: 20px; }
        .btn-danger { background: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
    </style>
</head>
<body>

    <div class="card">
        <div class="logo">OMEGA<span>CLOUD</span></div>
        
        <?php if ($msg): ?>
            <div class="alert alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <?php if ($page === 'login'): ?>
            <h2 style="text-align: center; margin-top: 0;">Sign In</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <button name="login">Login</button>
            </form>
            <div class="link">
                <a href="?page=forgot">Forgot Password?</a>
            </div>

        <?php elseif ($page === 'forgot'): ?>
            <h2 style="text-align: center; margin-top: 0;">Reset Password</h2>
            <p style="text-align: center; color: #666; font-size: 0.9rem;">Enter your username to receive a reset link.</p>
            <form method="POST">
                <input type="text" name="username" placeholder="Username (e.g. guest)">
                <button name="request_reset">Send Reset Link</button>
            </form>
            <div class="link"><a href="?page=login">Back to Login</a></div>

        <?php elseif ($page === 'reset'): ?>
            <?php 
                $token = $_GET['token'] ?? ''; 
                $user = $_GET['user'] ?? '';
            ?>
            <h2 style="text-align: center; margin-top: 0;">Set New Password</h2>
            <form method="POST">
                <label style="font-size: 0.8rem; font-weight: bold;">For User: <?php echo htmlspecialchars($user); ?></label>
                <input type="password" name="new_password" placeholder="New Password">
                
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($user); ?>">
                
                <button name="set_new_password">Change Password</button>
            </form>

        <?php elseif ($page === 'dashboard'): ?>
            <?php if (!isset($_SESSION['logged_in_user'])) header("Location: ?page=login"); ?>
            
            <div class="dashboard-header">
                <strong>User: <?php echo $_SESSION['logged_in_user']; ?></strong>
                <a href="?page=logout" style="color: #666; font-size: 0.8rem;">Logout</a>
            </div>
            
            <p>Welcome to the Omega Cloud Management Portal.</p>
            
            <?php if ($_SESSION['logged_in_role'] === 'admin'): ?>
                <div class="danger-zone">
                    <h3 style="color: #dc2626; margin-top: 0;">⚠ Admin Zone</h3>
                    <p>Warning: This action is irreversible.</p>
                    <a href="?page=delete_db"><button class="btn-danger">DELETE DATABASE</button></a>
                </div>
            <?php else: ?>
                <div style="background: #e0e7ff; padding: 15px; border-radius: 6px; color: #3730a3; text-align: center;">
                    You are logged in as a Standard User.<br>
                    <strong>Admin access required for system controls.</strong>
                </div>
            <?php endif; ?>

        <?php elseif ($page === 'flag'): ?>
            <div style="text-align: center;">
                <h1 style="color: #ef4444; font-size: 3rem; margin: 0;">⚠ DELETED</h1>
                <p>Database has been wiped.</p>
                <div style="background: #111; color: #0f0; padding: 20px; font-family: monospace; border-radius: 8px; margin-top: 20px;">
                    MISSION ACCOMPLISHED.<br>
                    FLAG: <?php echo $FLAG; ?>
                </div>
                <div class="link"><a href="?page=logout">Reset Lab</a></div>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>