<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// HANDLE JSON UPDATE REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Read the raw JSON input
    $json_input = file_get_contents('php://input');
    $data = json_decode($json_input, true);

    if ($data) {
        // --- VULNERABILITY HERE ---
        // The developer intended to only update the email:
        // $_SESSION['user']['email'] = $data['email'];
        
        // But instead, they used a loop to update EVERYTHING sent:
        foreach ($data as $key => $value) {
            $_SESSION['user'][$key] = $value;
        }
        
        // Return the updated profile so the user can see changes
        header('Content-Type: application/json');
        echo json_encode([
            "status" => "success", 
            "user" => $_SESSION['user']
        ]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 40px; background: #eee; }
        .card { background: white; max-width: 500px; margin: auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .field { margin-bottom: 15px; }
        label { font-weight: bold; display: block; }
        input { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        button { background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer; margin-right: 10px; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

<div class="card">
    <h2>User Profile</h2>
    
    <div class="field">
        <label>Username</label>
        <input type="text" value="<?php echo $_SESSION['user']['username']; ?>" disabled>
    </div>

    <div class="field">
        <label>Email Address</label>
        <input type="email" id="email" value="<?php echo $_SESSION['user']['email']; ?>">
    </div>

    <p style="color: #666; font-size: 0.9em;">Debug Info: Current Role ID = <strong><?php echo $_SESSION['user']['roleid']; ?></strong></p>

    <button onclick="updateProfile()">Update Email</button>
    <a href="admin.php">Go to Admin Panel</a>
</div>

<script>
    // This script sends the data as JSON, just like modern Single Page Apps (SPA)
    async function updateProfile() {
        const email = document.getElementById('email').value;
        
        // Normal request only sends email
        const payload = {
            email: email
        };

        const response = await fetch('profile.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await response.json();
        
        if(result.status === 'success') {
            alert('Profile updated! Server returned role: ' + result.user.roleid);
            location.reload();
        }
    }
</script>

</body>
</html>