<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmniCorp Global | Building the Future</title>
    <style>
        /* Corporate Stylesheet */
        :root {
            --primary: #0056b3;
            --secondary: #2c3e50;
            --light: #f8f9fa;
            --dark: #343a40;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; }
        
        /* Navigation */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .logo { font-size: 1.5rem; font-weight: bold; color: var(--secondary); letter-spacing: -1px; text-decoration: none; }
        .logo span { color: var(--primary); }
        
        .nav-links a {
            text-decoration: none;
            color: var(--secondary);
            margin-left: 20px;
            font-weight: 500;
            font-size: 0.95rem;
        }
        .btn-login {
            background: var(--primary);
            color: white !important;
            padding: 8px 18px;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .btn-login:hover { background: #004494; }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 0 20px;
        }
        .hero h1 { font-size: 3rem; margin-bottom: 10px; }
        .hero p { font-size: 1.2rem; max-width: 600px; margin: 0 auto 30px; opacity: 0.9; }
        
        /* Services Section */
        .container { max-width: 1100px; margin: 0 auto; padding: 60px 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; }
        .card { padding: 30px; background: white; border: 1px solid #eee; border-radius: 8px; text-align: center; transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .icon { font-size: 2.5rem; color: var(--primary); margin-bottom: 20px; display: block; }

        /* Footer */
        footer { background: var(--secondary); color: white; padding: 40px 20px; text-align: center; font-size: 0.9rem; }
        footer a { color: #aaa; text-decoration: none; margin: 0 10px; }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo">Omni<span>Corp</span></a>
        <div class="nav-links">
            <a href="#">Solutions</a>
            <a href="#">Investors</a>
            <a href="#">Careers</a>
            <a href="login.php" class="btn-login">Employee Portal</a>
        </div>
    </nav>

    <header class="hero">
        <div>
            <h1>Innovating for Tomorrow.</h1>
            <p>OmniCorp is the world leader in digital infrastructure, secure cloud computing, and enterprise solutions.</p>
            <a href="#" style="background:white; color:#333; padding:12px 25px; text-decoration:none; font-weight:bold; border-radius:4px;">Learn More</a>
        </div>
    </header>

    <section class="container">
        <div style="text-align:center; margin-bottom:50px;">
            <h2 style="font-size:2rem; color:var(--secondary);">Our Expertise</h2>
            <p style="color:#666;">Delivering excellence across every sector.</p>
        </div>
        
        <div class="grid">
            <div class="card">
                <span class="icon">☁️</span>
                <h3>Cloud Systems</h3>
                <p>Scalable architecture for the modern enterprise, ensuring 99.99% uptime.</p>
            </div>
            <div class="card">
                <span class="icon">🛡️</span>
                <h3>Cyber Security</h3>
                <p>Advanced threat detection and identity management solutions.</p>
            </div>
            <div class="card">
                <span class="icon">🤖</span>
                <h3>AI Research</h3>
                <p>Pushing the boundaries of machine learning and automated decision making.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 OmniCorp Global. All rights reserved.</p>
        <div style="margin-top:10px;">
            <a href="#">Privacy Policy</a> | 
            <a href="#">Terms of Service</a> | 
            <a href="#">Contact Support</a>
        </div>
        <p style="margin-top:20px; color:#555; font-size:0.8rem;">
            System Version: v4.2.1 | Server: US-EAST-1
        </p>
    </footer>

</body>
</html>