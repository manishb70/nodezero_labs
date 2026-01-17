<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Innovations</title>
    <link rel="stylesheet" href="style.css">
    <script>
        window.alert = function(msg) {
            // Signal the backend to fetch the flag
            fetch('products.php?fetch_flag=1')
                .then(res => res.text())
                .then(data => {
                    document.getElementById('notification-area').innerHTML = data;
                    document.getElementById('notification-area').style.display = 'block';
                });
        };
    </script>
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">Nexus<span>.</span></a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="products.php">Solutions</a>
            <a href="#">Enterprise</a>
            <a href="#" class="btn-primary">Client Portal</a>
        </div>
    </nav>
    <div id="notification-area"></div>