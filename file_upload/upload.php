<?php
session_start();
$base_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$upload_dir = "uploads/";

// Ensure upload directory exists
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// -------------------------
// BACKEND LOGIC
// -------------------------

// 1. Login Logic (Hardcoded for simulation)
if (isset($_POST['login'])) {
    if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin') {
        $_SESSION['user'] = 'admin';
        header("Location: $base_url?page=dashboard");
        exit;
    } else {
        $error = "Invalid credentials. Try admin:admin";
    }
}

// 2. Logout Logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: $base_url");
    exit;
}

// 3. File Upload Logic (THE VULNERABILITY)
$upload_message = "";
if (isset($_FILES['avatar']) && isset($_SESSION['user'])) {
    $file_name = $_FILES['avatar']['name'];
    $target_file = $upload_dir . basename($file_name);
    
    // REAL LIFE MISTAKE:
    // The developer added <input accept=".jpg"> in HTML (see below),
    // thinking that was enough. There is NO validation here in PHP!
    
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
        $upload_message = "<div class='alert success'>Profile picture updated! <a href='$target_file' target='_blank'>View Image</a></div>";
    } else {
        $upload_message = "<div class='alert error'>Upload failed.</div>";
    }
}

// -------------------------
// ROUTING & VIEWS
// -------------------------
$page = $_GET['page'] ?? 'login';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechCorp Portal</title>
    <style>
        /* CSS to make it look "Corporate" */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .navbar { background-color: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { margin: 0; font-size: 20px; }
        .navbar a { color: white; text-decoration: none; font-size: 14px; }
        .container { max-width: 800px; margin: 50px auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; }
        input[type="text"], input[type="password"], input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #2980b9; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .error { background-color: #f8d7da; color: #721c24; }
        .success { background-color: #d4edda; color: #155724; }
        .info-box { background: #e8f4fd; padding: 15px; border-left: 4px solid #3498db; margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>TechCorp Employee Portal</h1>
        <?php if (isset($_SESSION['user'])): ?>
            <div>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></span> | 
                <a href="?logout=true">Logout</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="container">
        
        <?php if ($page === 'login' && !isset($_SESSION['user'])): ?>
            <h2>Employee Login</h2>
            <?php if (isset($error)) echo "<div class='alert error'>$error</div>"; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="admin">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" value="admin">
                </div>
                <button type="submit" name="login">Login</button>
            </form>

        <?php elseif (isset($_SESSION['user'])): ?>
            <h2>My Profile</h2>
            
            <div class="info-box">
                <strong>System Notice:</strong> Please update your profile picture. Only JPG and PNG files are allowed for security reasons.
            </div>

            <?php echo $upload_message; ?>

            <form method="POST" enctype="multipart/form-data" id="uploadForm">
                <div class="form-group">
                    <label>Upload Avatar Image</label>
                    <input type="file" name="avatar" id="avatarInput" accept=".jpg, .png, .jpeg">
                </div>
                <button type="submit">Update Profile</button>
            </form>

            <script>
                // FAKE SECURITY: Client-side JavaScript validation
                // This stops regular users, but HACKERS can bypass this easily.
                document.getElementById('uploadForm').onsubmit = function(event) {
                    var fileInput = document.getElementById('avatarInput');
                    var filePath = fileInput.value;
                    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                    
                    if(!allowedExtensions.exec(filePath)){
                        alert('Security Alert: Only Image files are allowed!');
                        event.preventDefault(); // Stop the form from submitting
                        return false;
                    }
                };
            </script>

        <?php else: ?>
            <?php header("Location: $base_url?page=login"); ?>
        <?php endif; ?>

    </div>

</body>
</html>