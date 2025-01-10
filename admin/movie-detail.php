<?php
session_start();

if (!isset($_GET['id']) && !isset($_GET['tmdb_id'])) {
    die("Movie ID is required.");
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movie_appp";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Determine if the movie is from the database or the API
if (isset($_GET['id'])) {
    // Fetch movie details from the database
    $movieId = intval($_GET['id']);
    $sql = "SELECT * FROM admin_movies WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    $movie = $result->fetch_assoc();
    
    if (!$movie) {
        die("Movie not found in the database.");
    }

    // Close statement
    $stmt->close();
} elseif (isset($_GET['tmdb_id'])) {
    // Fetch movie details from the TMDb API
    $tmdbId = intval($_GET['tmdb_id']);
    $apiKey = '2b6d0226ebea157ba4d75651899a42e9';
    $apiUrl = "https://api.themoviedb.org/3/movie/$tmdbId?api_key=$apiKey";

    $movieDetails = file_get_contents($apiUrl);
    if ($movieDetails === FALSE) {
        die("Failed to fetch movie details. Please try again later.");
    }
    $movie = json_decode($movieDetails, true);

    if (!$movie || isset($movie['status_code'])) {
        die("Movie not found in TMDb.");
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> - MovieApp</title>
    <link rel="stylesheet" href="../styles/movie-detail.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
        <?php if (isset($movie['overview'])): ?>
            <p><?php echo htmlspecialchars($movie['overview']); ?></p>
        <?php elseif (isset($movie['description'])): ?>
            <p><?php echo htmlspecialchars($movie['description']); ?></p>
        <?php endif; ?>
    </header>

    <main>
        <div class="movie-details">
            <img src="<?php echo isset($movie['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : $movie['poster_url']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">

            <ul>
                <li><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></li>
                <?php if (isset($movie['vote_average']) || isset($movie['rating'])): ?>
                    <li><strong>Rating:</strong> <?php echo htmlspecialchars($movie['vote_average'] ?? $movie['rating']); ?>/5</li>
                <?php endif; ?>
                <?php if (isset($movie['ticket_price'])): ?>
                    <li><strong>Ticket Price:</strong> ₦<?php echo htmlspecialchars($movie['ticket_price']); ?></li>
                <?php endif; ?>
                <?php if (isset($movie['country'])): ?>
                    <li><strong>Country:</strong> <?php echo htmlspecialchars($movie['country']); ?></li>
                <?php endif; ?>
                <?php if (isset($movie['genres']) || isset($movie['genre'])): ?>
                    <li><strong>Genre:</strong> 
                        <?php 
                            if (isset($movie['genres'])) {
                                $genres = array_map(fn($genre) => $genre['name'], $movie['genres']);
                                echo htmlspecialchars(implode(', ', $genres));
                            } else {
                                echo htmlspecialchars($movie['genre']);
                            }
                        ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </main>
</body>
</html>
