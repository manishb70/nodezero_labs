<?php
/**
 * OMEGA PORTAL - MEDIUM LEVEL (Privilege Escalation)
 * Vulnerability: Insecure Cookie Handling (Client-Side Trust)
 * Tool Required: Burp Suite (Repeater & Decoder) or Cookie Editor
 */

// --- 1. CONFIGURATION ---
$flag_secret = "NodeZero{c00kies_must_b3_sign3d_n0t_just_enc0ded}";

// --- 2. HELPERS ---

// Safe redirect
function redirect($url) {
    header("Location: $url");
    exit;
}

// --- 3. CONTROLLERS ---

$page = $_GET['page'] ?? 'login';

// LOGOUT
if ($page === 'logout') {
    setcookie("auth_token", "", time() - 3600); // Kill cookie
    redirect("?page=login");
}

// LOGIN LOGIC
if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    if ($u === 'guest' && $p === 'guest') {
        // VULNERABILITY: 
        // We create a JSON object describing the user, BASE64 encode it, 
        // and send it as a cookie. We do NOT sign it (HMAC).
        // This allows a hacker to decode -> modify -> re-encode the cookie.
        
        $user_data = [
            'username' => 'guest',
            'role' => 'user', // <--- TARGET
            'avatar' => '👤'
        ];
        
        $cookie_payload = base64_encode(json_encode($user_data));
        
        setcookie("auth_token", $cookie_payload, time() + 3600);
        redirect("?page=dashboard");
    } else {
        $error = "Invalid Login. Try: guest / guest";
    }
}

// AUTH CHECK (On every page load)
$current_user = null;
if (isset($_COOKIE['auth_token'])) {
    // Decode the cookie to see who the user is
    $decoded = json_decode(base64_decode($_COOKIE['auth_token']), true);
    if ($decoded) {
        $current_user = $decoded;
    }
}

// Redirect if not logged in
if ($page !== 'login' && !$current_user) {
    redirect("?page=login");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Omega Admin Portal</title>
    <style>
        /* Admin Theme */
        :root { --dark: #1a202c; --light: #f7fafc; --blue: #3182ce; --red: #e53e3e; }
        body { font-family: 'Courier New', monospace; background: var(--light); color: var(--dark); margin: 0; }
        
        .navbar { background: var(--dark); color: white; padding: 15px 30px; display: flex; justify-content: space-between; }
        .logo { font-weight: bold; letter-spacing: 2px; }
        
        .container { max-width: 800px; margin: 50px auto; padding: 20px; }
        .card { background: white; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        
        .btn { background: var(--blue); color: white; border: none; padding: 10px 20px; cursor: pointer; font-weight: bold; }
        .btn-red { background: var(--red); }
        input { padding: 10px; width: 100%; border: 1px solid #cbd5e1; margin-bottom: 15px; box-sizing: border-box; }
        
        .badge { background: #edf2f7; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; border: 1px solid #cbd5e1; }
        .badge-admin { background: #fed7d7; color: #c53030; border-color: #feb2b2; }

        .flag-box { margin-top: 20px; padding: 20px; background: #2d3748; color: #48bb78; border-left: 5px solid #48bb78; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">OMEGA::SYSTEM</div>
        <?php if ($current_user): ?>
            <div>
                User: <?php echo htmlspecialchars($current_user['username']); ?> 
                <span class="badge <?php echo $current_user['role'] === 'admin' ? 'badge-admin' : ''; ?>">
                    <?php echo htmlspecialchars($current_user['role']); ?>
                </span>
                <a href="?page=logout" style="color:#a0aec0; margin-left:15px; text-decoration:none;">[Logout]</a>
            </div>
        <?php endif; ?>
    </nav>

    <div class="container">
        
        <?php if ($page === 'login'): ?>
            <div class="card" style="max-width:400px; margin:0 auto;">
                <h2 style="margin-top:0;">System Login</h2>
                <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
                <form method="POST">
                    <label>Username</label>
                    <input type="text" name="username" value="guest">
                    <label>Password</label>
                    <input type="password" name="password" value="guest">
                    <button name="login" class="btn" style="width:100%">Authenticate</button>
                </form>
            </div>

        <?php elseif ($page === 'dashboard'): ?>
            
            <div class="card">
                <h2>Welcome, <?php echo htmlspecialchars($current_user['username']); ?></h2>
                <p>You have successfully logged into the employee dashboard.</p>
                <hr style="border:0; border-top:1px solid #eee; margin:20px 0;">
                
                <h3>Available Actions</h3>
                <ul style="line-height:1.8;">
                    <li><a href="#">View Company Policy</a></li>
                    <li><a href="#">Submit Timesheet</a></li>
                    <li><a href="#">Update Profile</a></li>
                </ul>

                <div style="margin-top:40px; border: 2px dashed #e53e3e; padding: 20px; background: #fff5f5;">
                    <h3 style="margin-top:0; color: #c53030;">⚠ Admin Management Panel</h3>
                    
                    <?php if ($current_user['role'] === 'admin'): ?>
                        <p><strong>ACCESS GRANTED.</strong> Welcome back, Administrator.</p>
                        <div class="flag-box">
                            SYSTEM UNLOCKED.<br>
                            Flag: <code><?php echo $flag_secret; ?></code>
                        </div>
                    <?php else: ?>
                        <p style="color: #c53030;"><strong>ACCESS DENIED.</strong></p>
                        <p>Your current role is '<strong><?php echo htmlspecialchars($current_user['role']); ?></strong>'.<br>
                        Only users with role '<strong>admin</strong>' can view this section.</p>
                    <?php endif; ?>
                </div>

            </div>

        <?php endif; ?>
    </div>

</body>
</html>