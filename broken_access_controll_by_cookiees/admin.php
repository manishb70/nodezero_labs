<?php
// admin.php

// 1. Check if user is logged in at all
if (!isset($_COOKIE['User'])) {
    header("Location: login.php");
    exit;
}

// 2. CHECK THE VULNERABLE COOKIE
$isAdmin = false;
if (isset($_COOKIE['Admin']) && $_COOKIE['Admin'] === 'true') {
    $isAdmin = true;
}

// 3. Handle "Delete Carlos" Action
$flag = "";
$message = "";
if (isset($_GET['delete_user']) && $_GET['delete_user'] === 'carlos') {
    if ($isAdmin) {
        $flag = "NodeZero{c00k13_m4n1pul4t10n_succ3ss}"; // <--- YOUR FLAG HERE
        $message = "User 'carlos' has been deleted.";
    } else {
        die("403 Forbidden: You do not have permission to perform this action.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OmniCorp - Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; }
        .navbar { background: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { margin: 0; font-size: 20px; }
        .navbar a { color: #ecf0f1; text-decoration: none; font-size: 14px; }
        .container { max-width: 900px; margin: 50px auto; background: white; padding: 40px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .header { border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        
        /* User vs Admin Styles */
        .role-badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .role-user { background: #e0e0e0; color: #555; }
        .role-admin { background: #e74c3c; color: white; }

        .user-list { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .user-list th, .user-list td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        .user-list th { background: #f9f9f9; }
        
        .btn-delete { color: white; background: #e74c3c; padding: 6px 12px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .btn-disabled { color: #ccc; pointer-events: none; border: 1px solid #ccc; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }

        .flag-box { background: #27ae60; color: white; padding: 20px; text-align: center; margin-bottom: 20px; border-radius: 5px; font-weight: bold; font-size: 1.2em; }
    </style>
</head>
<body>

<div class="navbar">
    <h1>OmniCorp Intranet</h1>
    <div>
        Logged in as: <strong><?php echo htmlspecialchars($_COOKIE['User']); ?></strong> | <a href="login.php">Logout</a>
    </div>
</div>

<div class="container">
    <?php if ($flag): ?>
        <div class="flag-box">
            MISSION ACCOMPLISHED!<br>
            Flag: <?php echo $flag; ?>
        </div>
    <?php endif; ?>

    <div class="header">
        <h2>Administration Panel</h2>
        <p>Current Access Level: 
            <?php if ($isAdmin): ?>
                <span class="role-badge role-admin">Admin (True)</span>
            <?php else: ?>
                <span class="role-badge role-user">User (False)</span>
            <?php endif; ?>
        </p>
    </div>

    <?php if (!$isAdmin): ?>
        <div style="text-align: center; padding: 40px; color: #777;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <h3>Access Denied</h3>
            <p>You do not have administrative privileges to manage users.</p>
            <p><em>(Hint: Check your cookies...)</em></p>
        </div>
    <?php else: ?>
        <p>Welcome, Administrator. You have full control over the user database.</p>
        
        <table class="user-list">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>wiener</td>
                    <td>Employee</td>
                    <td><a href="#" class="btn-disabled">Delete</a></td>
                </tr>
                <tr>
                    <td><strong>carlos</strong></td>
                    <td>Contractor</td>
                    <td><a href="?delete_user=carlos" class="btn-delete">Delete User</a></td>
                </tr>
                <tr>
                    <td>manager</td>
                    <td>Manager</td>
                    <td><a href="#" class="btn-disabled">Delete</a></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>