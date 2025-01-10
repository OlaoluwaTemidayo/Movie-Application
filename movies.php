<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Movies - MovieApp</title>
    <link rel="stylesheet" href="styles/movies-style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <div class="fas fa-film"></div>
                MovieApp
            </div>
    
            <div class="nav-links">
                <?php if(isset($_SESSION['user_id'])): ?>
                   
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
    </header>

    <main>
        <div class="container">
            <h1>Popular Movies</h1>
            <div id="movies-grid" class="movies-container">
                
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 MovieApp. All rights reserved.</p>
        </div>
    </footer>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="scripts/script.js"></script>
</body>
</html>