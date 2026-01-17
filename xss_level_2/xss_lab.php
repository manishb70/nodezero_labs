<?php
// xss_level2_secure.php

// 1. DISABLE BROWSER PROTECTION (Crucial for Localhost Labs)
header("X-XSS-Protection: 0");

// --- BACKEND API FOR THE FLAG ---
// This part listens for a secret signal from the JavaScript.
// If the browser asks for "?fetch_flag=1", we give it the flag.
if (isset($_GET['fetch_flag'])) {
    // We add a tiny delay to make it feel like a "hack" is processing
    usleep(300000); 
    echo "<strong>LEVEL CLEARED!</strong><br>";
    echo "You bypassed the filter.<br>";
    echo "Flag: <code>NodeZero{fetched_via_javascript_execution}</code>";
    exit; // Stop running the rest of the page
}

// --- NORMAL PAGE LOGIC ---
$query = $_GET['q'] ?? '';
$error_msg = '';

// SECURITY FILTER (The Challenge)
// We block the word "<script>" to force the user to use other tags.
if (stripos($query, '<script>') !== false) {
    $error_msg = "Security Alert: Malicious tag detected! The &lt;script&gt; tag is blocked.";
    $query = " [BLOCKED CONTENT] "; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Level 2: Secure Flag Loading</title>
    
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #e9ecef; padding: 50px; text-align: center; }
        .container { max-width: 600px; margin: auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-top: 5px solid #ffc107; }
        input[type="text"] { width: 70%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 20px; background: #ffc107; color: #333; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .results { margin-top: 30px; text-align: left; border-top: 1px solid #eee; padding-top: 20px; }
        .error { color: red; background: #ffdce0; padding: 10px; border: 1px solid red; margin-bottom: 20px; border-radius: 5px; }

        /* The Flag Box starts EMPTY and HIDDEN */
        #flag-container {
            display: none; 
            margin-top: 20px;
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border: 2px solid #c3e6cb;
            border-radius: 5px;
        }
    </style>

    <script>
        // We override the browser's default alert() function.
        // Instead of showing a popup, it calls the server to get the flag.
        window.alert = function(message) {
            console.log("XSS Triggered! Fetching flag from server...");
            
            // 1. Make a request to THIS same file, asking for the flag
            fetch('?fetch_flag=1')
                .then(response => response.text())
                .then(data => {
                    // 2. Put the data (the flag) into the HTML
                    let box = document.getElementById('flag-container');
                    box.innerHTML = data;
                    box.style.display = 'block';
                })
                .catch(err => console.error('Error fetching flag:', err));
        };
    </script>
</head>
<body>

<div class="container">
    <h2>Product Search (Level 2)</h2>
    <p>We updated our security. <code>&lt;script&gt;</code> tags are banned.</p>

    <?php if ($error_msg): ?>
        <div class="error"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <form method="GET" action="">
        <input type="text" name="q" placeholder="Enter search term..." value="">
        <button type="submit">Search</button>
    </form>

    <div class="results">
        <?php if ($query && !$error_msg): ?>
            <h3>Results for: <?php echo $query; ?></h3>
            <p>No products found matching your criteria.</p>
        <?php endif; ?>
    </div>

    <div id="flag-container"></div>

</div>

</body>
</html>