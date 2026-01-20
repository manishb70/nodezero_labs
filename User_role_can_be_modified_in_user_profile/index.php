<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProfileX | Identity Management Solutions</title>
    <style>
        /* Modern SaaS Theme */
        :root {
            --primary: #6c5ce7;
            --secondary: #a29bfe;
            --dark: #2d3436;
            --light: #dfe6e9;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--dark);
        }

        /* Navbar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 10%;
            background: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: -1px;
        }
        .nav-links a {
            text-decoration: none;
            color: #636e72;
            margin-left: 30px;
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-links a:hover { color: var(--primary); }
        .btn-login {
            background-color: var(--primary);
            color: white !important;
            padding: 10px 25px;
            border-radius: 50px;
            box-shadow: 0 4px 10px rgba(108, 92, 231, 0.3);
        }
        .btn-login:hover { background-color: #5849be; }

        /* Hero Section */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5rem 10%;
            height: 70vh;
        }
        .hero-text { flex: 1; padding-right: 50px; }
        .hero-text h1 {
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 20px;
            background: -webkit-linear-gradient(45deg, #6c5ce7, #0984e3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-text p {
            font-size: 1.2rem;
            color: #636e72;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        .hero-image {
            flex: 1;
            display: flex;
            justify-content: center;
        }
        
        /* Simple illustration using CSS shapes */
        .illustration-card {
            width: 350px;
            height: 250px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            position: relative;
            padding: 20px;
        }
        .skeleton-line { height: 10px; background: #eee; margin-bottom: 15px; border-radius: 5px; }
        .w-80 { width: 80%; }
        .w-60 { width: 60%; }
        .w-40 { width: 40%; }
        .avatar { width: 60px; height: 60px; background: var(--secondary); border-radius: 50%; margin-bottom: 20px; }

    </style>
</head>
<body>

    <nav>
        <a href="#" class="logo">ProfileX.</a>
        <div class="nav-links">
            <a href="#">Features</a>
            <a href="#">Enterprise</a>
            <a href="#">Developers</a>
            <a href="login.php" class="btn-login">Client Login</a>
        </div>
    </nav>

    <header>
        <div class="hero-text">
            <h1>Identity. <br>Simplified.</h1>
            <p>Manage user profiles with our blazing fast JSON API. Seamlessly update user data in real-time with our cutting-edge mass-assignment technology.</p>
            
            <a href="login.php" style="text-decoration:none; background:#2d3436; color:white; padding:15px 35px; border-radius:8px; font-weight:bold;">Get Started</a>
        </div>

        <div class="hero-image">
            <div class="illustration-card">
                <div class="avatar"></div>
                <div class="skeleton-line w-40"></div>
                <div class="skeleton-line w-80"></div>
                <div class="skeleton-line w-60"></div>
                
                <div style="position:absolute; bottom:-20px; right:-20px; background:white; padding:15px; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.1); font-weight:bold; color:#00b894;">
                    JSON API Ready ✓
                </div>
            </div>
        </div>
    </header>

</body>
</html>