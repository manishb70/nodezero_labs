<?php
// xss_multi_param.php

// 1. Disable Browser XSS Protection
header("X-XSS-Protection: 0");

// --- BACKEND API FOR FLAG (Hidden from Source) ---
if (isset($_GET['fetch_flag'])) {
    usleep(200000); // Small delay
    echo "<strong>MISSION ACCOMPLISHED!</strong><br>";
    echo "You found the hidden vulnerable parameter.<br>";
    echo "Flag: <code>NodeZero{param3t3r_p0lluti0n_succ3ss}</code>";
    exit;
}

// --- INPUT HANDLING ---
// The user submits these via GET request
$term     = $_GET['term'] ?? '';
$category = $_GET['category'] ?? '';
$sort     = $_GET['sort'] ?? '';
$filter   = $_GET['filter'] ?? ''; // <--- THIS IS THE VULNERABLE ONE

// --- SANITIZATION LOGIC ---
// The developer remembers to sanitize the visible stuff...
$clean_term     = htmlspecialchars($term);
$clean_category = htmlspecialchars($category);
$clean_sort     = htmlspecialchars($sort);

// ...but forgets to sanitize the 'filter' parameter because it's rarely used!
// $filter is echoed RAW later.

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced Search</title>
    
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f1f3f5; padding: 40px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { border-bottom: 2px solid #eee; padding-bottom: 15px; color: #333; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; }
        label { margin-bottom: 5px; font-weight: bold; color: #555; }
        input, select { padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        
        button { background: #007bff; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #0056b3; }

        .results { margin-top: 40px; background: #fafafa; padding: 20px; border-left: 5px solid #007bff; }
        
        #flag-container {
            display: none; 
            margin-top: 20px;
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border: 2px solid #c3e6cb;
            border-radius: 5px;
            text-align: center;
        }
    </style>

    <script>
        window.alert = function(message) {
            fetch('?fetch_flag=1')
                .then(response => response.text())
                .then(data => {
                    let box = document.getElementById('flag-container');
                    box.innerHTML = data;
                    box.style.display = 'block';
                });
        };
    </script>
</head>
<body>

<div class="container">
    <h2>Inventory Search</h2>
    <p>Use the advanced filters to find products.</p>

    <form method="GET" action="">
        <div class="form-grid">
            <div class="form-group">
                <label>Search Term</label>
                <input type="text" name="term" value="<?php echo $clean_term; ?>" placeholder="e.g. Laptop">
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option value="all">All Categories</option>
                    <option value="electronics" <?php if($category=='electronics') echo 'selected'; ?>>Electronics</option>
                    <option value="furniture" <?php if($category=='furniture') echo 'selected'; ?>>Furniture</option>
                </select>
            </div>

            <div class="form-group">
                <label>Sort By</label>
                <select name="sort">
                    <option value="price_asc" <?php if($sort=='price_asc') echo 'selected'; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php if($sort=='price_desc') echo 'selected'; ?>>Price: High to Low</option>
                </select>
            </div>

            <div class="form-group">
                <label>Filter ID (Advanced)</label>
                <input type="text" name="filter" value="<?php echo htmlspecialchars($filter); ?>" placeholder="Optional Filter ID">
            </div>
        </div>

        <button type="submit">Apply Filters</button>
    </form>

    <?php if ($term || $category || $filter): ?>
        <div class="results">
            <h3>Search Results</h3>
            
            <p>Searching for: <strong><?php echo $clean_term; ?></strong></p>
            <p>Category: <strong><?php echo $clean_category; ?></strong></p>
            
            <p>Active Filter ID: <?php echo $filter; ?></p>
            
            <hr>
            <p style="color:#777;">No items found in inventory.</p>
        </div>
    <?php endif; ?>

    <div id="flag-container"></div>
</div>

</body>
</html>