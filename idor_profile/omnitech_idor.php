<?php
/**
 * OmniTech Employee Portal - IDOR Lab
 * INSTRUCTIONS:
 * 1. Save as omnitech_idor.php
 * 2. Run: php -S localhost:8000
 * 3. Access in browser.
 */

session_start();

// --- 1. MOCK DATABASE ---
// In real life, this would be a SQL database.
$users = [
    // Regular Employees (IDs start at 100)
    101 => [
        'name' => 'Alice Smith',
        'role' => 'Software Engineer',
        'email' => 'alice.smith@omnitech.com',
        'avatar' => '👩‍💻',
        'bio' => 'Joined in 2022. Focused on frontend React development.'
    ],
    102 => [
        'name' => 'Bob Johnson',
        'role' => 'Project Manager',
        'email' => 'bob.johnson@omnitech.com',
        'avatar' => '👨‍💼',
        'bio' => 'PMP certified. Leads the "Phoenix" overhaul project.'
    ],
    103 => [
        'name' => 'Charlie Davis',
        'role' => 'UX Designer',
        'email' => 'charlie.d@omnitech.com',
        'avatar' => '🎨',
        'bio' => 'Advocate for accessible design principles.'
    ],
    // THE TARGET (Hidden Admin Account with a different ID structure)
    999 => [
        'name' => 'SYSTEM ADMINISTRATOR',
        'role' => 'Root Access',
        'email' => 'admin@omnitech.com',
        'avatar' => '🛡️',
        // THE FLAG IS HERE
        'bio' => 'Highly classified profile. Flag: NodeZero{ID0R_unl0cks_th3_c0rp0rat3_s3cr3ts}'
    ]
];

// --- 2. CONTROLLERS (Routing logic) ---

// Handle Login (Simplified for lab - automatically logs in as Alice)
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    $_SESSION['logged_in_user_id'] = 101; // Log in as Alice (ID 101)
    // Redirect to her profile
    header("Location: omnitech_idor.php?page=profile&id=101");
    exit;
}

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: omnitech_idor.php");
    exit;
}

// Page Routing
$page = $_GET['page'] ?? 'home';
$current_user = isset($_SESSION['logged_in_user_id']) ? $users[$_SESSION['logged_in_user_id']] : null;

// Profile fetch logic (VULNERABLE PART)
$profile_data = null;
if ($page === 'profile' && $current_user) {
    // VULNERABILITY: 
    // The application takes the 'id' directly from the URL (`$_GET['id']`).
    // It DOES NOT check if `$_GET['id']` matches `$_SESSION['logged_in_user_id']`.
    // It DOES NOT check if the logged-in user has admin privileges to view others.
    $requested_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['logged_in_user_id'];

    if (isset($users[$requested_id])) {
        $profile_data = $users[$requested_id];
        $profile_id_display = $requested_id;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmniTech Employee Portal</title>
    <style>
        /* Professional Corporate CSS Theme */
        :root {
            --primary: #0056b3;
            --secondary: #f8f9fa;
            --text-dark: #343a40;
            --text-light: #6c757d;
            --border: #dee2e6;
        }
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #e9ecef; margin: 0; color: var(--text-dark); }
        a { text-decoration: none; color: var(--primary); transition: 0.2s; }
        a:hover { color: #004494; }
        
        /* Navbar */
        .navbar { background-color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .brand { font-size: 1.5rem; font-weight: bold; color: var(--primary); }
        .brand span { color: var(--text-dark); font-weight: normal; }
        .nav-user { display: flex; align-items: center; gap: 15px; }
        .btn-login { background: var(--primary); color: white; padding: 8px 20px; border-radius: 4px; font-weight: 500; }
        .btn-logout { color: var(--text-light); font-size: 0.9rem; border: 1px solid var(--border); padding: 5px 10px; border-radius: 4px; }

        /* Main Layout */
        .container { max-width: 960px; margin: 40px auto; padding: 0 20px; }

        /* Login Screen / Home */
        .hero-box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); text-align: center; }
        
        /* Profile Card */
        .profile-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); display: flex; }
        .profile-sidebar { background: linear-gradient(135deg, var(--primary), #004494); color: white; padding: 40px; text-align: center; min-width: 250px; display: flex; flex-direction: column; align-items: center; }
        .avatar { font-size: 5rem; background: rgba(255,255,255,0.2); width: 120px; height: 120px; line-height: 120px; border-radius: 50%; margin-bottom: 20px; }
        .profile-main { padding: 40px; flex: 1; }
        .info-group { margin-bottom: 25px; }
        .info-label { display: block; font-size: 0.85rem; text-transform: uppercase; color: var(--text-light); font-weight: bold; margin-bottom: 5px; }
        .info-value { font-size: 1.1rem; color: var(--text-dark); }
        .employee-id-badge { display: inline-block; background: var(--secondary); border: 1px solid var(--border); padding: 2px 8px; border-radius: 4px; font-family: monospace; font-size: 0.9rem; margin-top: 10px;}
        
        .flag-box { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; font-family: monospace; font-weight: bold; margin-top: 10px;}
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="brand">OmniTech <span>Solutions Portal</span></div>
        <div class="nav-user">
            <?php if ($current_user): ?>
                <span>Welcome, <?php echo htmlspecialchars($current_user['name']); ?></span>
                <a href="?action=logout" class="btn-logout">Logout</a>
            <?php else: ?>
                <a href="#" class="nav-link">Help Desk</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">

        <?php if (!$current_user): ?>
            <div class="hero-box">
                <h1>Internal Employee Directory</h1>
                <p style="color: var(--text-light); margin-bottom: 30px;">Please log in to view employee profiles and operational data.</p>
                <a href="?action=login" class="btn-login">Login as Employee (Alice Smith)</a>
                <p style="margin-top: 20px; font-size: 0.8rem; color: #aaa;">Authorized access only. All activity is logged.</p>
            </div>

        <?php elseif ($page === 'profile'): ?>
            <?php if ($profile_data): ?>
                <div class="profile-card">
                    <div class="profile-sidebar">
                        <div class="avatar"><?php echo $profile_data['avatar']; ?></div>
                        <h2 style="margin:0;"><?php echo htmlspecialchars($profile_data['name']); ?></h2>
                        <p style="opacity: 0.8;"><?php echo htmlspecialchars($profile_data['role']); ?></p>
                        <div class="employee-id-badge">EMP ID: #<?php echo $profile_id_display; ?></div>
                    </div>
                    <div class="profile-main">
                        <div style="border-bottom: 1px solid var(--border); padding-bottom: 15px; margin-bottom: 20px;">
                            <h3 style="margin:0; color: var(--primary);">Employee Data Sheet</h3>
                        </div>
                        
                        <div class="info-group">
                            <span class="info-label">Contact Email</span>
                            <span class="info-value">
                                <a href="mailto:<?php echo $profile_data['email']; ?>"><?php echo $profile_data['email']; ?></a>
                            </span>
                        </div>
                        
                        <div class="info-group">
                            <span class="info-label">Biography / Notes</span>
                            <div class="info-value">
                                <?php echo htmlspecialchars($profile_data['bio']); ?>
                            </div>
                        </div>

                        <div style="margin-top: 40px; border-top: 1px solid var(--border); padding-top: 20px;">
                            <a href="?page=profile&id=<?php echo $_SESSION['logged_in_user_id']; ?>" style="margin-right: 20px;">← Back to My Profile</a>
                             <a href="?page=profile&id=102" style="color: var(--text-light);">View next employee...</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="hero-box" style="color: #dc3545;">
                    <h2>Error 404</h2>
                    <p>Employee Profile Not Found.</p>
                    <a href="?page=profile&id=<?php echo $_SESSION['logged_in_user_id']; ?>">Return to my profile</a>
                </div>
            <?php endif; ?>

        <?php else: ?>
             <div class="hero-box">
                <h2>Welcome to the Intranet</h2>
                <a href="?page=profile&id=<?php echo $_SESSION['logged_in_user_id']; ?>" class="btn-login">View My Profile</a>
             </div>
        <?php endif; ?>

    </div>

</body>
</html>