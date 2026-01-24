<?php
/**
 * NEON DEV - SOURCE CODE HUNT
 * Goal: Find the 3 parts of the flag hidden in HTML, CSS, and JS.
 */

$success_msg = "";
if (isset($_POST['check_flag'])) {
    $input = trim($_POST['flag_input']);
    if ($input === "NodeZero{v1ew_s0urc3_m4ster_undetect3d}") {
        $success_msg = "🎉 CORRECT! You found all the hidden pieces : NodeZero{v1ew_s0urc3_m4ster_undetect3d}.";
    } else {
        $success_msg = "❌ Incorrect. Keep looking through the code!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- TODO: Fix z-index on navbar.
               FLAG PART 1: NodeZero{v1ew_
               
             -->

    <title>NeonDev | Under Construction</title>
    <style>
        /* --- MAIN STYLESHEET --- */   
        :root {
            --bg: #0d0d12;
            --card: #15151e;
            --text: #e0e0e0;
            --accent: #00f3ff;
            /* TODO: Fix z-index on navbar.
               FLAG PART 2: s0urc3_m4ster_
            */
            --secondary: #ff0055;
        }

        /* ... rest of CSS ... */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .navbar {
            width: 100%;
            padding: 20px;
            background: rgba(21, 21, 30, 0.8);
            border-bottom: 1px solid #333;
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            letter-spacing: 2px;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 20px;
        }

        .card {
            background: var(--card);
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #333;
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.1);
            text-align: center;
        }

        h1 {
            margin-top: 0;
            color: white;
        }

        p {
            color: #888;
            line-height: 1.6;
        }

        input[type="text"] {
            padding: 12px;
            width: 70%;
            border-radius: 4px;
            border: 1px solid #444;
            background: #222;
            color: white;
            margin-top: 20px;
        }

        button {
            padding: 12px 20px;
            background: var(--accent);
            color: black;
            border: none;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background: #00c4cf;
        }

        .result {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="navbar">NEON<span>DEV</span></div>

    <div class="container">
        <div class="card">
            <div style="font-size: 4rem;">🚧</div>
            <h1>Site Under Maintenance</h1>
            <p>We are currently updating our frontend codebase. Developers, please check the console for debug logs.</p>
            <hr style="border: 0; border-top: 1px solid #333; margin: 30px 0;">
            <h3>Verify Flag</h3>
            <p>If you found all 3 parts, combine them and paste below:</p>
            <form method="POST">
                <input type="text" name="flag_input" placeholder="NodeZero{...}">
                <button name="check_flag">Check</button>
            </form>
            <?php if ($success_msg): ?>
                <div class="result"
                    style="background: <?php echo strpos($success_msg, 'CORRECT') ? '#0f0' : '#f00'; ?>; color: black;">
                    <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // --- CLIENT SIDE LOGIC ---
        console.log("System initialized...");
        const appVersion = "v2.1.0-beta";
        const debugMode = true;

        if (debugMode) {
            console.log("Debugging enabled.");
            // FLAG PART 3: undetect3d}
            var secret_part_3 = "undetect3d}";
            console.log("Note to self: Remove secret_part_3 before deploy.");
        }

        function checkStatus() {
            return "All systems operational.";
        }
    </script>
</body>

</html>