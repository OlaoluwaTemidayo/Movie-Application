<?php
session_start();

// Verify admin access
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles/admin-style.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="movies.php">Manage Movies</a>
            <a href="manage_roles.php">Manage Users</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <p>Use the navigation links to manage the application.</p>
    </main>

    <footer>
        <p>&copy; 2025 MovieApp Admin Panel</p>
    </footer>
</body>
</html>
