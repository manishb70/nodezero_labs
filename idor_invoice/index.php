<?php
/**
 * INVOICIFY - SAAS BILLING PORTAL
 * Vulnerability: IDOR (Insecure Direct Object Reference)
 * Scenario: You can only see your own invoices on the dashboard.
 * But can you access someone else's by guessing the ID?
 */

session_start();

// --- 1. MOCK DATABASE ---
$users = [
    'demo' => ['password' => 'demo', 'name' => 'John Doe (Freelancer)', 'id' => 10],
    'admin' => ['password' => 'admin', 'name' => 'CEO (Target)', 'id' => 1]
];

// The Invoice Database
// Notice: The ID is predictable (Sequential: 5001, 5002...)
$invoices = [
    5001 => [
        'owner_id' => 10, // Belongs to John (You)
        'date' => '2023-10-01',
        'service' => 'Web Design Services',
        'amount' => 500.00,
        'status' => 'PAID'
    ],
    5002 => [
        'owner_id' => 10, // Belongs to John (You)
        'date' => '2023-10-15',
        'service' => 'Hosting Maintenance',
        'amount' => 120.00,
        'status' => 'PENDING'
    ],
    // --- THE TARGET ---
    5003 => [
        'owner_id' => 1, // Belongs to ADMIN (Not you!)
        'date' => '2023-10-20',
        'service' => 'Dark Web Intelligence Feed',
        'amount' => 15000.00,
        'status' => 'PAID',
        'notes' => 'Confidential. Flag: NodeZero{ID0R_in_b1lling_syst3ms_is_cr1tical}'
    ],
    5004 => [
        'owner_id' => 99, // Some other random user
        'date' => '2023-10-22',
        'service' => 'Consulting',
        'amount' => 200.00,
        'status' => 'PAID'
    ]
];

// --- 2. LOGIC CONTROLLERS ---

$page = $_GET['page'] ?? 'login';

// Login Logic
if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    if (isset($users[$u]) && $users[$u]['password'] === $p) {
        $_SESSION['user_id'] = $users[$u]['id'];
        $_SESSION['user_name'] = $users[$u]['name'];
        header("Location: ?page=dashboard");
        exit;
    } else {
        $error = "Invalid credentials. Try: demo / demo";
    }
}

// Logout Logic
if ($page === 'logout') {
    session_destroy();
    header("Location: ?page=login");
    exit;
}

// Security Check for internal pages
if ($page !== 'login' && !isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoicify | Cloud Billing</title>
    <style>
        /* SaaS Theme */
        :root { --primary: #6366f1; --dark: #1e293b; --light: #f8fafc; --text: #334155; }
        body { font-family: 'Inter', sans-serif; background: var(--light); color: var(--text); margin: 0; display: flex; flex-direction: column; min-height: 100vh; }
        
        /* Navbar */
        .navbar { background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-weight: 800; font-size: 1.2rem; color: var(--dark); text-decoration: none; }
        .logo span { color: var(--primary); }
        .nav-links a { text-decoration: none; color: var(--text); font-size: 0.9rem; margin-left: 20px; }
        
        /* Containers */
        .container { max-width: 900px; margin: 40px auto; padding: 20px; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        
        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; padding: 10px; border-bottom: 1px solid #e2e8f0; }
        td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; }
        .status-badge { padding: 4px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: bold; }
        .paid { background: #dcfce7; color: #166534; }
        .pending { background: #fef9c3; color: #854d0e; }
        
        /* Invoice Paper Style */
        .invoice-paper { background: white; border: 1px solid #e2e8f0; padding: 40px; max-width: 700px; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .invoice-header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 2px solid var(--primary); padding-bottom: 20px; }
        .invoice-title { font-size: 2rem; color: var(--primary); font-weight: bold; }
        
        /* Buttons */
        .btn { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #4f46e5; }
        .btn-sm { font-size: 0.8rem; padding: 5px 10px; }
    </style>
</head>
<body>

    <?php if ($page === 'login'): ?>
        <div style="display:flex; justify-content:center; align-items:center; height:100vh;">
            <div class="card" style="width: 350px; text-align:center;">
                <div class="logo" style="font-size: 1.5rem; margin-bottom: 20px;">Invoicify<span>.</span></div>
                <?php if(isset($error)) echo "<p style='color:red; font-size:0.9rem;'>$error</p>"; ?>
                <form method="POST">
                    <input type="text" name="username" placeholder="Username" style="width:100%; padding:10px; margin-bottom:10px; box-sizing:border-box; border:1px solid #ccc; border-radius:4px;" value="demo">
                    <input type="password" name="password" placeholder="Password" style="width:100%; padding:10px; margin-bottom:20px; box-sizing:border-box; border:1px solid #ccc; border-radius:4px;" value="demo">
                    <button name="login" class="btn" style="width:100%;">Sign In</button>
                </form>
                <p style="font-size:0.8rem; color:#888; margin-top:20px;">Secure Client Access Portal v2.1</p>
            </div>
        </div>

    <?php else: ?>
        <nav class="navbar">
            <a href="?page=dashboard" class="logo">Invoicify<span>.</span></a>
            <div class="nav-links">
                <span><?php echo $_SESSION['user_name']; ?></span>
                <a href="?page=logout" style="color:#ef4444;">Sign Out</a>
            </div>
        </nav>

        <div class="container">
            
            <?php if ($page === 'dashboard'): ?>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <h2>Billing History</h2>
                    <button class="btn">Create New Invoice</button>
                </div>
                
                <div class="card" style="margin-top:20px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Invoice ID</th>
                                <th>Date</th>
                                <th>Service</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // SECURE LOGIC: Dashboard only shows YOUR invoices
                            $has_invoices = false;
                            foreach($invoices as $id => $inv) {
                                if ($inv['owner_id'] === $_SESSION['user_id']) {
                                    $has_invoices = true;
                                    $status_class = strtolower($inv['status']);
                                    echo "<tr>
                                        <td>#$id</td>
                                        <td>{$inv['date']}</td>
                                        <td>{$inv['service']}</td>
                                        <td>$" . number_format($inv['amount'], 2) . "</td>
                                        <td><span class='status-badge $status_class'>{$inv['status']}</span></td>
                                        <td><a href='?page=view&id=$id' class='btn btn-sm'>View</a></td>
                                    </tr>";
                                }
                            }
                            if (!$has_invoices) echo "<tr><td colspan='6'>No invoices found.</td></tr>";
                            ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($page === 'view'): ?>
                <?php
                    $id = $_GET['id'] ?? 0;
                    
                    // CHECK 1: Does invoice exist?
                    if (!isset($invoices[$id])) {
                        echo "<div class='card' style='text-align:center; color:red;'><h2>Error 404</h2><p>Invoice #$id not found.</p><a href='?page=dashboard' class='btn'>Back</a></div>";
                    } else {
                        $inv = $invoices[$id];
                        
                        // --- VULNERABILITY IS HERE ---
                        // We check if the invoice exists, but we DO NOT check 
                        // if ($inv['owner_id'] === $_SESSION['user_id'])
                        // This allows you to view ANY invoice by changing the ID.
                        
                        $is_admin_doc = ($inv['owner_id'] === 1);
                ?>
                    <a href="?page=dashboard" style="color:#64748b; text-decoration:none;">&larr; Back to Dashboard</a>
                    <div style="height:20px;"></div>
                    
                    <div class="invoice-paper">
                        <div class="invoice-header">
                            <div>
                                <div class="invoice-title">INVOICE</div>
                                <div style="color:#64748b;">#<?php echo $id; ?></div>
                            </div>
                            <div style="text-align:right;">
                                <div class="logo">Invoicify<span>.</span></div>
                                <p style="font-size:0.9rem; color:#64748b;">123 Cloud Avenue<br>San Francisco, CA</p>
                            </div>
                        </div>

                        <div style="display:flex; justify-content:space-between; margin-bottom:40px;">
                            <div>
                                <strong>Bill To:</strong><br>
                                <?php 
                                    // Reverse lookup name for realism
                                    foreach($users as $u) {
                                        if($u['id'] == $inv['owner_id']) echo $u['name'];
                                    }
                                ?>
                            </div>
                            <div style="text-align:right;">
                                <strong>Date:</strong> <?php echo $inv['date']; ?><br>
                                <strong>Status:</strong> <?php echo $inv['status']; ?>
                            </div>
                        </div>

                        <table style="margin-bottom:40px;">
                            <thead>
                                <tr><th>Description</th><th style="text-align:right;">Amount</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $inv['service']; ?></td>
                                    <td style="text-align:right; font-weight:bold;">$<?php echo number_format($inv['amount'], 2); ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <?php if ($is_admin_doc && isset($inv['notes'])): ?>
                            <div style="background:#fee2e2; color:#991b1b; padding:15px; border-radius:6px; font-family:monospace;">
                                <strong>⚠ CONFIDENTIAL NOTES:</strong><br>
                                <?php echo $inv['notes']; ?>
                            </div>
                        <?php endif; ?>

                        <div style="margin-top:50px; text-align:center; color:#94a3b8; font-size:0.8rem;">
                            Thank you for your business.
                        </div>
                    </div>
                <?php } ?>

            <?php endif; ?>
        </div>
    <?php endif; ?>

</body>
</html>