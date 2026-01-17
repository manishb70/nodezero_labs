<?php
session_start();

// --- CONFIGURATION ---
// This is the flag your users need to submit
$flag_code = "NodeZero{y0u_f0und_th3_h1dd3n_path}";
// ---------------------

// Initialize users if not set (Reset logic)
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [
        'carlos' => 'Carlos Montoya',
        'wiener' => 'Peter Wiener',
        'admin'  => 'System Administrator'
    ];
}

$message = "";

// Handle Deletion
if (isset($_GET['delete'])) {
    $user_to_delete = $_GET['delete'];
    
    // Prevent deleting the real admin (optional safety)
    if ($user_to_delete === 'admin') {
        $message = "Error: You cannot delete the main Administrator.";
    } 
    elseif (isset($_SESSION['users'][$user_to_delete])) {
        unset($_SESSION['users'][$user_to_delete]);
        $message = "User '$user_to_delete' deleted successfully.";
    }
}

// Check if 'carlos' has been deleted to release the flag
$is_carlos_deleted = !isset($_SESSION['users']['carlos']);

// Handle Reset (to play again)
if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: secret_admin_panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Hidden</title>
    <style>
        body { font-family: 'Courier New', monospace; padding: 50px; background: #1a1a1a; color: #ddd; }
        .panel { border: 1px solid #444; padding: 30px; background: #2b2b2b; max-width: 700px; margin: auto; box-shadow: 0 0 15px rgba(0,0,0,0.5); }
        h1 { color: #e74c3c; margin-top: 0; }
        .user-row { display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid #444; }
        .user-row:last-child { border-bottom: none; }
        a.delete { color: #e74c3c; text-decoration: none; border: 1px solid #e74c3c; padding: 5px 15px; border-radius: 3px; transition: 0.3s; }
        a.delete:hover { background: #e74c3c; color: white; }
        
        /* Flag Box Styles */
        .flag-box { 
            background: #27ae60; 
            color: white; 
            font-size: 18px; 
            text-align: center; 
            padding: 20px; 
            margin-top: 30px; 
            border-radius: 5px; 
            animation: fadeIn 1s;
        }
        .flag-code {
            background: rgba(0,0,0,0.3);
            padding: 5px 10px;
            font-weight: bold;
            font-family: monospace;
            border-radius: 4px;
            margin-left: 10px;
            user-select: all; /* Makes it easy to copy */
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        .reset-btn { display: block; margin-top: 20px; color: #777; font-size: 12px; text-decoration: none; text-align: right; }
    </style>
</head>
<body>

<div class="panel">
    <h1>⚠ ADMINISTRATOR PANEL</h1>
    <p>System User Management Interface. Authorized personnel only.</p>
    
    <?php if ($message): ?>
        <p style="color: #f1c40f; border-left: 3px solid #f1c40f; padding-left: 10px;">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <div class="user-list">
        <?php foreach ($_SESSION['users'] as $username => $fullname): ?>
            <div class="user-row">
                <div>
                    <strong><?php echo $fullname; ?></strong><br>
                    <small style="color:#888">Username: <?php echo $username; ?></small>
                </div>
                <a href="?delete=<?php echo $username; ?>" class="delete">DELETE USER</a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($is_carlos_deleted): ?>
        <div class="flag-box">
            ✅ <strong>Mission Accomplished!</strong><br><br>
            Here is your flag: <span class="flag-code"><?php echo $flag_code; ?></span>
        </div>
    <?php endif; ?>

    <a href="?reset=true" class="reset-btn">↻ Reset Lab</a>
</div>

</body>
</html>