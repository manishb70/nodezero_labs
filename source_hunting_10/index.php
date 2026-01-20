<?php
/**
 * QUANTUM LEAP DYNAMICS - 10-PART FLAG HUNT
 * Goal: Find all 10 parts of the flag hidden in the source code of 10 different pages.
 */

// Full Flag: NodeZero{r3c0n_m4ster_10_p4g3s_d0wn}
$FLAG_PARTS = [
    1 => "NodeZero{",        // Home (HTML Comment)
    2 => "r3c0n_",           // About (Meta Tag)
    3 => "m4ster_",          // Products (Alt Text)
    4 => "10_",              // Careers (Console Log)
    5 => "p4g3s_",           // News (Hidden Div)
    6 => "d0wn",             // Contact (CSS Variable)
    7 => "_un",              // Login (Form Placeholder/Value) -> Wait, let's adjust to fit 10.
                             // Let's re-split the flag to be longer or just split it 10 ways.
                             // Flag: NodeZero{w3b_src_hunt_3xpert_lvl_99_c0mplet3}
                             // 1: NodeZero{
                             // 2: w3b_
                             // 3: src_
                             // 4: hunt_
                             // 5: 3xpert_
                             // 6: lvl_
                             // 7: 99_
                             // 8: c0m
                             // 9: plet
                             // 10: 3}
];

// RE-DEFINING FLAGS FOR THE 10 PAGES
$F = [
    'home' => "NodeZero{",
    'about' => "w3b_",
    'products' => "src_",
    'careers' => "hunt_",
    'news' => "3xpert_",
    'contact' => "lvl_",
    'legal' => "99_",
    'login' => "c0m",
    'dashboard' => "plet",
    'sitemap' => "3}"
];

$page = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuantumLeap | Future Tech</title>
    
    <?php if ($page === 'home'): ?>
        <?php endif; ?>

    <?php if ($page === 'about'): ?>
        <meta name="description" content="Leading the way in quantum computing.">
        <meta name="flag-part-2" content="<?php echo $F['about']; ?>">
    <?php endif; ?>

    <style>
        :root {
            --primary: #00d2ff;
            --dark: #0a0a12;
            --text: #e0e0e0;
            
            <?php if ($page === 'contact'): ?>
            /* FLAG PART 6: <?php echo $F['contact']; ?> */
            <?php endif; ?>
        }
        
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--dark); color: var(--text); display: flex; flex-direction: column; min-height: 100vh; }
        
        .navbar { background: rgba(0,0,0,0.5); padding: 20px; border-bottom: 1px solid #333; display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
        .navbar a { color: var(--primary); text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.9rem; }
        .navbar a:hover { color: white; }
        
        .container { max-width: 900px; margin: 40px auto; padding: 20px; flex: 1; text-align: center; }
        .card { background: #15151e; padding: 40px; border-radius: 8px; border: 1px solid #333; }
        
        h1 { color: white; font-size: 2.5rem; }
        p { color: #aaa; line-height: 1.6; }

        .hidden-flag { display: none; }
        
        footer { text-align: center; padding: 20px; color: #555; font-size: 0.8rem; border-top: 1px solid #333; }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="?page=home">Home</a>
        <a href="?page=about">About</a>
        <a href="?page=products">Products</a>
        <a href="?page=careers">Careers</a>
        <a href="?page=news">News</a>
        <a href="?page=contact">Contact</a>
        <a href="?page=legal">Legal</a>
        <a href="?page=login">Portal</a>
        <a href="?page=dashboard">Dash</a>
        <a href="?page=sitemap">Sitemap</a>
    </div>

    <div class="container">
        
        <?php if ($page === 'home'): ?>
            <div class="card">
                <h1>QuantumLeap Dynamics</h1>
                <p>Building the processors of tomorrow, today.</p>
                <div style="font-size: 5rem; margin: 30px;">⚛️</div>
                <!-- Flag Part 1: <?php echo $F['home']; ?> -->    
                <p>Welcome to our corporate intranet. Please explore our pages to learn more.</p>
            </div>

        <?php elseif ($page === 'about'): ?>
            <div class="card">
                <h1>About Us</h1>
                <p>Founded in 2024, we specialize in qubit stabilization and quantum error correction.</p>
                <p><strong>Hint:</strong> Check the &lt;meta&gt; tags in the head.</p>
            </div>

        <?php elseif ($page === 'products'): ?>
            <div class="card">
                <h1>Our Products</h1>
                <div style="display: flex; justify-content: center; gap: 20px; margin-top: 30px;">
                    <img src="https://via.placeholder.com/150/00d2ff/000000?text=Q-Chip" alt="Flag Part 3: <?php echo $F['products']; ?>">
                    <img src="https://via.placeholder.com/150/00d2ff/000000?text=Cooling" alt="Cryo-Cooler v2">
                </div>
                <p>Hover over images or check the source code for descriptions.</p>
            </div>

        <?php elseif ($page === 'careers'): ?>
            <div class="card">
                <h1>Join the Team</h1>
                <p>We are hiring Quantum Physicists and Security Researchers.</p>
                <button onclick="console.log('No positions available.')" style="padding:10px 20px; background:var(--primary); border:none; cursor:pointer;">Apply Now</button>
            </div>
            <script>
                console.log("Debug: Application Form Loaded.");
                console.log("Flag Part 4: <?php echo $F['careers']; ?>");
            </script>

        <?php elseif ($page === 'news'): ?>
            <div class="card">
                <h1>Press Releases</h1>
                <p>2026-01-15: QLD achieves quantum supremacy.</p>
                <div class="hidden-flag">
                    Flag Part 5: <?php echo $F['news']; ?>
                </div>
                <p><em>(Some content is hidden for subscribers only. Check the source HTML.)</em></p>
            </div>

        <?php elseif ($page === 'contact'): ?>
            <div class="card">
                <h1>Contact Support</h1>
                <p>Email: support@quantumleap.com</p>
                <p><strong>Hint:</strong> We hid something in the CSS variables for this page.</p>
            </div>

        <?php elseif ($page === 'legal'): ?>
            <div class="card">
                <h1>Terms of Service</h1>
                <p>By using this site, you agree to our NDA.</p>
                <p data-secret-flag="Flag Part 7: <?php echo $F['legal']; ?>">
                    Hovering won't help. Inspect this paragraph element.
                </p>
            </div>

        <?php elseif ($page === 'login'): ?>
            <div class="card">
                <h1>Employee Login</h1>
                <form>
                    <input type="text" name="user" placeholder="Username" style="padding:10px; width:60%;">
                    <input type="hidden" name="csrf_token" value="Flag Part 8: <?php echo $F['login']; ?>">
                    <button type="button" style="padding:10px;">Login</button>
                </form>
                <p>Check the hidden form fields.</p>
            </div>

        <?php elseif ($page === 'dashboard'): ?>
            <div class="card">
                <h1>Guest Dashboard</h1>
                <p>You do not have access to full metrics.</p>
                <script src="non_existent_script.js"></script>
                <noscript>
                    Flag Part 9: <?php echo $F['dashboard']; ?>
                </noscript>
                <p>(Hint: Sometimes flags are hidden in &lt;noscript&gt; tags or comments inside scripts.)</p>
            </div>

        <?php elseif ($page === 'sitemap'): ?>
            <div class="card">
                <h1>XML Sitemap</h1>
               <textarea>
<?php
echo htmlspecialchars(
'<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>http://www.quantumleap.com/</loc>
    </url>
</urlset>'
);
// Flag Part 10:
echo   "<!-- Flag Part 10   -->             " .  $F['sitemap']; 
?>
</textarea>
                <p>Check the XML comment inside the text area code.</p>
            </div>

        <?php endif; ?>

    </div>

    <footer>
        &copy; 2026 QuantumLeap Dynamics. <br>
        Find all 10 parts to assemble the flag: NodeZero{...}
    </footer>

</body>
</html>