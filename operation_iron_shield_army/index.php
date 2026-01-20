<?php
/**
 * OPERATION IRON SHIELD
 * Theme: Indian Army Cyber Defense
 * Mission: Neutralize the enemy threat and secure the border grid.
 */

session_start();

// --- CONFIGURATION ---
$FLAG_PART_1 = "NodeZero{JAI_HIND_";
$FLAG_PART_2 = "S3CUR3_TH3_";
$FLAG_PART_3 = "B0RD3R_VAND3_MATARAM}";

// --- SESSION INIT ---
if (!isset($_COOKIE['clearance_level'])) {
    setcookie('clearance_level', 'recruit', time() + 3600);
    $_COOKIE['clearance_level'] = 'recruit';
}

$page = $_GET['page'] ?? 'briefing';
$msg = "";
$msg_type = "";

// --- LOGIC CONTROLLERS ---

// STAGE 2: DECODER LOGIC
if (isset($_POST['decode_intel'])) {
    $input = trim($_POST['cipher_text']);
    // The code is Base64 of "ATTACK_AT_DAWN"
    if ($input === "ATTACK_AT_DAWN") {
        $_SESSION['intel_decoded'] = true;
        $msg = "INTEL DECRYPTED. ENEMY PLAN REVEALED.";
        $msg_type = "success";
    } else {
        $msg = "DECRYPTION FAILED. INCORRECT PLAIN TEXT.";
        $msg_type = "error";
    }
}

// STAGE 3: LAUNCH COUNTER MEASURE
if ($page === 'launch_defense') {
    // Check for Privilege Escalation
    if ($_COOKIE['clearance_level'] === 'general') {
        // WIN CONDITION
        $page = 'victory';
    } else {
        $msg = "ACCESS DENIED. AUTHORIZATION LEVEL 'GENERAL' REQUIRED.";
        $msg_type = "error";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Operation Iron Shield | Indian Cyber Command</title>
    <style>
        /* MILITARY THEME */
        :root {
            --bg: #1a1c1a; 
            --panel: #2b302b; 
            --text: #c0dcc0; 
            --accent: #4caf50; 
            --alert: #e53935;
            --orange: #ff9800;
        }
        @import url('https://fonts.googleapis.com/css2?family=Black+Ops+One&family=Share+Tech+Mono&display=swap');

        body {
            background-color: var(--bg);
            background-image: repeating-linear-gradient(45deg, #151715 25%, transparent 25%, transparent 75%, #151715 75%, #151715), repeating-linear-gradient(45deg, #151715 25%, #1a1c1a 25%, #1a1c1a 75%, #151715 75%, #151715);
            background-position: 0 0, 10px 10px;
            background-size: 20px 20px;
            color: var(--text);
            font-family: 'Share Tech Mono', monospace;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .header {
            width: 100%;
            background: #0f110f;
            border-bottom: 4px solid var(--orange); /* Indian Saffronish */
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.8);
        }

        h1 { font-family: 'Black Ops One', cursive; font-size: 2.5rem; margin: 0; color: #fff; letter-spacing: 2px; text-transform: uppercase; }
        .sub-header { color: var(--accent); font-weight: bold; letter-spacing: 1px; }

        .container { max-width: 800px; width: 90%; margin-top: 40px; }

        .panel {
            background: var(--panel);
            border: 2px solid #444;
            padding: 30px;
            position: relative;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        
        .panel::before {
            content: "TOP SECRET // FOR OFFICIAL USE ONLY";
            /* first part NodeZero{JAI_HIND_ */
            position: absolute;
            top: -12px;
            left: 20px;
            background: var(--bg);
            padding: 0 10px;
            color: var(--alert);
            font-weight: bold;
            font-size: 0.8rem;
            border: 1px solid var(--alert);
        }

        .btn {
            background: var(--accent);
            color: black;
            border: none;
            padding: 15px 30px;
            font-family: 'Black Ops One', cursive;
            font-size: 1.2rem;
            cursor: pointer;
            text-transform: uppercase;
            width: 100%;
            margin-top: 20px;
            transition: 0.3s;
        }
        .btn:hover { background: #66bb6a; letter-spacing: 1px; }
        .btn-alert { background: var(--alert); color: white; }
        .btn-alert:hover { background: #ef5350; }

        .nav-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 20px; }
        .nav-item { background: #111; padding: 10px; text-align: center; border: 1px solid #333; color: #888; text-decoration: none; transition: 0.2s; }
        .nav-item:hover, .nav-item.active { background: var(--accent); color: black; font-weight: bold; }

        .console-output {
            background: black;
            color: var(--accent);
            padding: 15px;
            border: 1px solid var(--accent);
            font-family: monospace;
            margin-top: 20px;
            min-height: 100px;
        }

        .flag-box {
            border: 2px dashed var(--orange);
            background: rgba(255, 152, 0, 0.1);
            color: var(--orange);
            padding: 10px;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }

        .tricolor-bar {
            height: 10px;
            width: 100%;
            background: linear-gradient(to right, #FF9933 33%, #FFFFFF 33%, #FFFFFF 66%, #138808 66%);
            margin-top: 20px;
        }
        
        input[type="text"] { width: 100%; padding: 10px; background: #111; border: 1px solid #555; color: white; font-family: inherit; font-size: 1.1rem; }

    </style>
</head>
<body>

    <div class="header">
        <h1>🇮🇳 Indian Cyber Command</h1>
        <div class="sub-header">Operation Iron Shield</div>
    </div>

    <div class="container">
        
        <div class="nav-grid">
            <a href="?page=briefing" class="nav-item <?php echo $page=='briefing'?'active':''; ?>">1. INTEL BRIEF</a>
            <a href="?page=decoder" class="nav-item <?php echo $page=='decoder'?'active':''; ?>">2. DECRYPTION</a>
            <a href="?page=command" class="nav-item <?php echo $page=='command'?'active':''; ?>">3. COMMAND CENTER</a>
        </div>

        <?php if ($msg): ?>
            <div class="panel" style="background: <?php echo $msg_type=='error'?'#300':'#030'; ?>; margin-bottom: 20px; border-color: <?php echo $msg_type=='error'?'red':'lime'; ?>;">
                <strong>SYSTEM MESSAGE:</strong> <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <?php if ($page === 'briefing'): ?>
            <div class="panel">
                <h2>MISSION BRIEFING</h2>
                <p><strong>OFFICER:</strong> We have detected unauthorized signals from across the border.</p>
                <p>Your orders are to:</p>
                <ul>
                    <li>Locate the hidden flag in the Intelligence Report (Source Code).</li>
                    <li>Intercept and Decode the enemy transmission.</li>
                    <li>Take control of the Command Center and launch the Iron Shield Defense.</li>
                </ul>
                <div class="console-output">
                    > DOWNLOADING INTEL...<br>
                    > SCANNING SOURCE CODE...<br>
                    > HINT: View Page Source (Ctrl+U) to find FLAG PART 1.
                </div>
            </div>

        <?php elseif ($page === 'decoder'): ?>
            <div class="panel">
                <h2>SIGNAL INTERCEPTED</h2>
                <p>We caught this encrypted string from an enemy radio.</p>
                <div style="background: #000; color: red; padding: 10px; text-align: center; font-size: 1.2rem; margin-bottom: 20px; letter-spacing: 2px;">
                    QVRUQUNLX0FUX0RBV04=
                </div>
                <p>Decrypt this "Base64" string and enter the plain text below to verify threat.</p>
                
                <form method="POST">
                    <input type="text" name="cipher_text" placeholder="ENTER DECRYPTED TEXT HERE">
                    <button name="decode_intel" class="btn">VERIFY INTEL</button>
                </form>

                <?php if (isset($_SESSION['intel_decoded'])): ?>
                    <div class="flag-box">
                        GOOD WORK, SOLDIER.<br>
                        FLAG PART 2: <?php echo $FLAG_PART_2; ?>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($page === 'command'): ?>
            <div class="panel">
                <h2>DEFENSE CONTROL GRID</h2>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #555; padding-bottom: 10px; margin-bottom: 20px;">
                    <span>OPERATIVE: <strong>Guest</strong></span>
                    <span>CLEARANCE: <strong><?php echo strtoupper($_COOKIE['clearance_level']); ?></strong></span>
                </div>

                <p>Status: <span style="color:red; blink:true;">ENEMY MISSILE INBOUND.</span></p>
                <p>Protocol: Engage Iron Shield Defense System.</p>

                <?php if ($_COOKIE['clearance_level'] !== 'general'): ?>
                    <div class="console-output" style="color: var(--alert);">
                        > ERROR: ACCESS DENIED.<br>
                        > REQUIRED CLEARANCE: 'GENERAL'<br>
                        > CURRENT CLEARANCE: 'RECRUIT'<br>
                        > HINT: Inspect your cookies and promote yourself.
                    </div>
                    <button class="btn btn-alert" style="opacity: 0.5; cursor: not-allowed;">LAUNCH DEFENSE (LOCKED)</button>
                <?php else: ?>
                    <div class="console-output" style="color: lime;">
                        > ACCESS GRANTED.<br>
                        > WELCOME, GENERAL.<br>
                        > SYSTEM READY.
                    </div>
                    <form action="?page=launch_defense" method="POST">
                        <button class="btn">LAUNCH IRON SHIELD</button>
                    </form>
                <?php endif; ?>
            </div>

        <?php elseif ($page === 'victory'): ?>
            <div class="panel" style="text-align: center; border-color: var(--orange);">
                <h1 style="color: var(--orange); font-size: 4rem; text-shadow: 0 0 20px orange;">VANDE MATARAM!</h1>
                <div class="tricolor-bar"></div>
                
                <p style="font-size: 1.5rem; margin-top: 30px;">MISSION ACCOMPLISHED</p>
                <p>The enemy threat has been neutralized. India is safe.</p>

                <div style="background: #111; padding: 20px; border: 2px solid lime; margin-top: 30px;">
                    <div style="color: #aaa; font-size: 0.9rem;">FINAL FLAG PART 3</div>
                    <div style="color: lime; font-size: 1.5rem; font-weight: bold;"><?php echo $FLAG_PART_3; ?></div>
                </div>

                <p style="margin-top: 30px; color: #888;">Combine all parts: NodeZero{JAI_HIND_S3CUR3_TH3_B0RD3R_VAND3_MATARAM}</p>
                
                <a href="?page=briefing" style="color: var(--accent);">Return to Base</a>
            </div>

        <?php endif; ?>

    </div>

    <div style="margin-top: 50px; color: #444; font-size: 0.8rem;">
        INDIAN ARMY CYBER CELL // RECRUITMENT LAB v1.0
    </div>

</body>
</html>