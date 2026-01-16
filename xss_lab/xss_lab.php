<?php
// xss_lab.php

// 1. DISABLE BROWSER PROTECTION (Crucial for Localhost Labs)
// This tells Chrome/Edge NOT to block the XSS attack so you can practice.
header("X-XSS-Protection: 0");

$flag = "NodeZero{XSS_p0pup_m4st3r}";
$query = $_GET['q'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search - NodeZero Store</title>
    
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; padding: 50px; text-align: center; }
        .container { max-width: 600px; margin: auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        input[type="text"] { width: 70%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .results { margin-top: 30px; text-align: left; border-top: 1px solid #eee; padding-top: 20px; }
        
        /* The Hidden Flag Box */
        #flag-box {
            display: none; 
            margin-top: 20px;
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border: 2px solid #c3e6cb;
            border-radius: 5px;
            animation: popIn 0.5s;
        }
        @keyframes popIn { from { transform: scale(0); } to { transform: scale(1); } }
    </style>

    <script>
        // We override the alert function BEFORE the page body loads.
        // This ensures we catch the XSS immediately.
        window.alert = function(message) {
            // 1. Wait for the DOM to be ready before showing the flag
            window.addEventListener('DOMContentLoaded', (event) => {
                var flagBox = document.getElementById('flag-box');
                if(flagBox) {
                    flagBox.style.display = 'block';
                    console.log("XSS Successful! Flag Revealed.");
                }
            });
        };
    </script>
</head>
<body>

<div class="container">
    <h1>Product Search</h1>
    <p>Find the best hacking tools...</p>

    <form method="GET" action="">
        <input type="text" name="q" placeholder="Search for products..." value="<?php echo str_replace('"', '&quot;', $query); ?>">
        <button type="submit">Search</button>
    </form>

    <div class="results">
        <?php if ($query): ?>
            <h3>Results for: <?php echo $query; ?></h3>
            <p>No products found matching your criteria.</p>
        <?php endif; ?>
    </div>

    <div id="flag-box">
        <strong>CONGRATULATIONS!</strong><br>
        You executed XSS.<br>
        Flag: <code><?php echo $flag; ?></code>
    </div>

</div>

</body>
</html>