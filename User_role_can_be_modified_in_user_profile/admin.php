<?php
session_start();

$user = $_SESSION['user'] ?? null;

// CHECK ACCESS CONTROL
if (!$user || $user['roleid'] !== 2) {
    // Styling for Access Denied
    echo "<body style='background:#eee; font-family:sans-serif; text-align:center; padding-top:50px;'>";
    echo "<div style='background:white; padding:30px; display:inline-block; border-radius:8px;'>";
    echo "<h1 style='color:red;'>Access Denied</h1>";
    echo "<p>Admin interface is only available to users with <code>roleid: 2</code>.</p>";
    echo "<p>Your current roleid is: <strong>" . ($user['roleid'] ?? 'Guest') . "</strong></p>";
    echo "<a href='profile.php'>Back to Profile</a>";
    echo "</div></body>";
    exit;
}

// HANDLE DELETION
$message = "";
if (isset($_GET['delete']) && $_GET['delete'] === 'carlos') {
    $message = "NodeZero{mass_assignm3nt_mast3r}"; // THE FLAG
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body { font-family: sans-serif; background: #222; color: white; padding: 50px; text-align: center; }
        .panel { border: 2px solid #e74c3c; padding: 40px; display: inline-block; background: #333; }
        h1 { color: #e74c3c; }
        .btn { background: red; color: white; padding: 10px 20px; text-decoration: none; font-weight: bold; }
        .success { background: #2ecc71; color: white; padding: 20px; margin-top: 20px; font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="panel">
        <h1>ADMINISTRATION</h1>
        <p>Welcome, Admin. You have full privileges.</p>
        
        <h3>User Management</h3>
        <p>Carlos (ID: 559) - <a href="?delete=carlos" class="btn">Delete User</a></p>
        
        <?php if ($message): ?>
            <div class="success">LAB SOLVED! Flag: <?php echo $message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>