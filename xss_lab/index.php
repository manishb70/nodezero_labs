<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NodeZero Search</title>
    <style>
        body { margin: 0; font-family: sans-serif; background: #fff; color: #333; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; }
        h1 { font-size: 3rem; color: #333; margin-bottom: 20px; }
        .search-box { width: 500px; display: flex; box-shadow: 0 2px 5px rgba(0,0,0,0.2); border-radius: 24px; overflow: hidden; border: 1px solid #dfe1e5; }
        input { flex: 1; padding: 12px 20px; border: none; outline: none; font-size: 16px; }
        button { background: transparent; border: none; cursor: pointer; padding: 0 15px; }
        button svg { fill: #4285f4; width: 24px; height: 24px; }
    </style>
</head>
<body>

    <h1 style="font-family: 'Arial', sans-serif; letter-spacing: -2px;">
        <span style="color:#4285f4">N</span><span style="color:#ea4335">o</span><span style="color:#fbbc05">d</span><span style="color:#4285f4">e</span><span style="color:#34a853">Z</span><span style="color:#ea4335">e</span><span style="color:#fbbc05">r</span><span style="color:#4285f4">o</span>
    </h1>
    
    <form action="xss_lab.php" method="GET" class="search-box">
        <input type="text" name="q" placeholder="Search the web...">
        <button type="submit">
            <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
        </button>
    </form>

</body>
</html>