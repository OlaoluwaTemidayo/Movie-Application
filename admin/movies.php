<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect non-admins or unauthenticated users
    header('Location: ../login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movie_appp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// API details
$api_key = "2b6d0226ebea157ba4d75651899a42e9";
$api_url = "https://api.themoviedb.org/3/movie/popular?api_key=$api_key&language=en-US&page=1";

// Fetch movies from TMDb API
$response = file_get_contents($api_url);
$data = json_decode($response, true);
$api_movies = $data['results'] ?? [];

// Fetch movies from the database
$db_movies = [];
$sql = "SELECT * FROM admin_movies ORDER BY release_date DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $db_movies[] = $row;
    }
}

// Combine database and API movies
$all_movies = [];

// Add database movies to the combined list
foreach ($db_movies as $movie) {
    $all_movies[] = [
        'id' => $movie['id'],
        'title' => $movie['title'],
        'release_date' => $movie['release_date'],
        'description' => $movie['description'],
        'poster_url' => $movie['poster_url'],
        'source' => 'database'
    ];
}

// Add API movies to the combined list
foreach ($api_movies as $movie) {
    $all_movies[] = [
        'id' => $movie['id'],
        'title' => $movie['title'],
        'release_date' => $movie['release_date'],
        'description' => $movie['overview'],
        'poster_url' => "https://image.tmdb.org/t/p/w500" . $movie['poster_path'],
        'source' => 'api'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Movies</title>
    <link rel="stylesheet" href="../styles/admin-movies-style.css">
</head>
<body>
    <header>
        <h1>Admin - Manage Movies</h1>
        <nav>
            <a href="add_movie.php">Add Movie</a>
        </nav>
    </header>
    <main>
        <h2>All Movies</h2>
        <table>
            <thead>
                <tr>
                    <th>Poster</th>
                    <th>Title</th>
                    <th>Release Date</th>
                    <th>Description</th>
                    <th>Source</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($all_movies)): ?>
                    <?php foreach ($all_movies as $movie): ?>
                        <tr>
                            <td>
                                <?php if (!empty($movie['poster_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>" alt="Poster" style="width: 50px; height: auto;">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($movie['title']); ?></td>
                            <td><?php echo htmlspecialchars($movie['release_date']); ?></td>
                            <td><?php echo htmlspecialchars(substr($movie['description'], 0, 50)) . '...'; ?></td>
                            <td><?php echo htmlspecialchars($movie['source']); ?></td>
                            <td>
                                <?php if ($movie['source'] === 'database'): ?>
                                    <a href="movie-detail.php?id=<?php echo $movie['id']; ?>">View</a> |
                                    <a href="edit_movie.php?id=<?php echo $movie['id']; ?>">Edit</a>
                                <?php else: ?>
                                    <a href="movie-detail.php?tmdb_id=<?php echo $movie['id']; ?>">View</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No movies found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
<?php
// Close database connection
$conn->close();
?>
