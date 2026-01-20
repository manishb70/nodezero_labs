<?php
/**
 * OMEGA IT SERVICE DESK - PARAMETER TAMPERING LAB (PATCHED)
 * Vulnerability: Hidden Field Manipulation / Mass Assignment
 * Tool Required: Burp Suite (to intercept and modify POST data)
 */

session_start();

// --- 1. CONFIGURATION ---
$flag_secret = "NodeZero{h1dd3n_param3t3rs_rule_the_w0rld}";

// --- 2. ROBUST SESSION INIT (THE FIX) ---
// This ensures 'tickets' is ALWAYS an array, preventing the fatal error.
if (!isset($_SESSION['tickets']) || !is_array($_SESSION['tickets'])) {
    $_SESSION['tickets'] = [];
}
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = 'Guest';
}

// --- 3. CONTROLLERS ---

$page = $_GET['page'] ?? 'login';

// Handle Reset
if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    session_destroy();
    header("Location: service_desk.php");
    exit;
}

// Handle Login
if (isset($_POST['login'])) {
    if ($_POST['username'] === 'employee' && $_POST['password'] === 'password') {
        $_SESSION['user'] = 'Alice (Employee)';
        $_SESSION['role'] = 'standard';
        // Re-initialize tickets on fresh login to be safe
        $_SESSION['tickets'] = [];
        header("Location: ?page=dashboard");
        exit;
    } else {
        $error = "Invalid credentials. Try: employee / password";
    }
}

// Handle Logout
if ($page === 'logout') {
    session_destroy();
    header("Location: ?page=login");
    exit;
}

// Handle Ticket Submission (VULNERABLE)
$ticket_result = "";
if (isset($_POST['create_ticket'])) {
    
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);
    
    // VULNERABILITY: 
    // The HTML form sends "priority=low" as a hidden field.
    // The backend blindly accepts whatever is sent in $_POST['priority'].
    $priority = $_POST['priority'] ?? 'low';

    // Logic based on Priority
    if ($priority === 'critical') {
        // WIN CONDITION
        $status = "ESCALATED";
        $response = "<strong>ALERT:</strong> CRITICAL TICKET RECEIVED. ADMIN NOTIFIED.<br>Flag: <code>$flag_secret</code>";
        $color = "red";
    } elseif ($priority === 'high') {
        $status = "URGENT";
        $response = "Ticket marked as High Priority. Response time: 4 hours.";
        $color = "orange";
    } else {
        $status = "OPEN";
        $response = "Ticket submitted successfully. Standard response time: 3-5 business days.";
        $color = "green";
    }

    // Double check session before adding (Safety Net)
    if (!isset($_SESSION['tickets']) || !is_array($_SESSION['tickets'])) {
        $_SESSION['tickets'] = [];
    }

    // Save to session just for display
    array_unshift($_SESSION['tickets'], [
        'id' => rand(1000, 9999),
        'subject' => $subject,
        'priority' => strtoupper($priority),
        'status' => $status,
        'color' => $color
    ]);
    
    // If we won, show the flag immediately
    if ($priority === 'critical') {
        $ticket_result = $response;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Omega IT Service Desk</title>
    <style>
        /* Modern Corporate IT Theme */
        :root { --primary: #2c3e50; --accent: #3498db; --bg: #ecf0f1; --text: #34495e; --white: #fff; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); margin: 0; display: flex; flex-direction: column; min-height: 100vh; }
        
        /* Navbar */
        .navbar { background: var(--primary); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .brand { font-size: 1.4rem; font-weight: bold; display: flex; align-items: center; gap: 10px; }
        .brand span { color: var(--accent); }
        .nav-link { color: #bdc3c7; text-decoration: none; font-size: 0.9rem; transition: 0.3s; }
        .nav-link:hover { color: white; }
        
        /* Containers */
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; flex: 1; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 20px; }
        .card-header { background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
        .card-body { padding: 25px; }

        /* Forms */
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-family: inherit; }
        .btn { background: var(--accent); color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        .btn:hover { background: #2980b9; }

        /* Tickets Table */
        .ticket-item { border-bottom: 1px solid #eee; padding: 15px 0; display: flex; justify-content: space-between; align-items: center; }
        .ticket-item:last-child { border-bottom: none; }
        .badge { padding: 4px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: bold; color: white; }
        .bg-green { background: #2ecc71; }
        .bg-orange { background: #f39c12; }
        .bg-red { background: #e74c3c; }

        /* Footer */
        footer { text-align: center; padding: 20px; font-size: 0.8rem; color: #7f8c8d; }

        /* Flag Box */
        .flag-box { background: #2c3e50; color: #2ecc71; padding: 20px; border-radius: 6px; margin-bottom: 20px; font-family: monospace; border-left: 5px solid #2ecc71; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="brand">🖥️ Omega<span>Desk</span></div>
        <?php if ($page !== 'login'): ?>
            <div>
                <span style="margin-right: 15px; font-size: 0.9rem; opacity: 0.8;">Signed in as <?php echo isset($_SESSION['user']) ? $_SESSION['user'] : 'Guest'; ?></span>
                <a href="?page=logout" class="nav-link">Logout</a>
            </div>
        <?php endif; ?>
    </nav>

    <div class="container">
        
        <?php if ($page === 'login'): ?>
            <div style="display: flex; justify-content: center; align-items: center; height: 60vh;">
                <div class="card" style="width: 400px;">
                    <div class="card-header">Employee Login</div>
                    <div class="card-body">
                        <?php if(isset($error)) echo "<p style='color:red; font-size:0.9rem; margin-top:0;'>$error</p>"; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" value="employee">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" value="password">
                            </div>
                            <button name="login" class="btn" style="width:100%;">Access Portal</button>
                        </form>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Support Dashboard</h2>
                <a href="?action=reset" style="color: #e74c3c; text-decoration: none; font-size: 0.9rem;">Reset System</a>
            </div>

            <?php if ($ticket_result): ?>
                <div class="flag-box">
                    <?php echo $ticket_result; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <span>📝 Submit New Request</span>
                    <span style="font-size: 0.7rem; color: #95a5a6; font-weight: normal;">Server v2.4 | Priority Levels: Low, High, Critical</span>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Issue Subject</label>
                            <input type="text" name="subject" placeholder="e.g., VPN Connection Failed" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="message" rows="4" placeholder="Describe your issue..." required></textarea>
                        </div>
                        
                        <input type="hidden" name="priority" value="low">
                        
                        <button name="create_ticket" class="btn">Submit Ticket</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">My Ticket History</div>
                <div class="card-body" style="padding-top: 0; padding-bottom: 0;">
                    <?php if (empty($_SESSION['tickets'])): ?>
                        <p style="padding: 20px; text-align: center; color: #95a5a6;">No tickets submitted yet.</p>
                    <?php else: ?>
                        <?php foreach($_SESSION['tickets'] as $t): ?>
                            <div class="ticket-item">
                                <div>
                                    <div style="font-weight: bold;">#<?php echo $t['id']; ?> - <?php echo $t['subject']; ?></div>
                                    <div style="font-size: 0.8rem; color: #7f8c8d;">Status: <?php echo $t['status']; ?></div>
                                </div>
                                <span class="badge bg-<?php echo $t['color']; ?>"><?php echo $t['priority']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <footer>
        &copy; 2026 Omega IT Services. Authorized Personnel Only.
    </footer>

</body>
</html>