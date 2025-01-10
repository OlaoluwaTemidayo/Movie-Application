<?php
session_start();


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Movie ID is required.");
}

$movieId = intval($_GET['id']);
$apiKey = '2b6d0226ebea157ba4d75651899a42e9';
$apiUrl = "https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey";

// Fetch movie details from API
$movieDetails = file_get_contents($apiUrl);
if ($movieDetails === FALSE) {
    die("Failed to fetch movie details. Please try again later.");
}
$movie = json_decode($movieDetails, true);


if (!$movie || isset($movie['status_code'])) {
    die("Movie not found.");
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $comment = trim($_POST['comment']);
    if (!empty($comment)) {
        // Store comments temporarily in a session for simplicity
        $_SESSION['comments'][$movieId][] = [
            'username' => $_SESSION['username'],
            'comment' => $comment,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $success = "Your comment has been posted.";
    } else {
        $error = "Comment cannot be empty.";
    }
}

// Retrieve stored comments for the current movie
$comments = isset($_SESSION['comments'][$movieId]) ? $_SESSION['comments'][$movieId] : [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> - MovieApp</title>
    <link rel="stylesheet" href="styles/movie-detail.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
        <p><?php echo htmlspecialchars($movie['overview']); ?></p>
    </header>

    <main>
        <div class="movie-details">
            <img src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
            <ul>
                <li><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></li>
                <li><strong>Rating:</strong> <?php echo htmlspecialchars($movie['vote_average']); ?>/10</li>
                <li><strong>Genres:</strong>
                    <?php 
                        $genres = array_map(fn($genre) => $genre['name'], $movie['genres']);
                        echo htmlspecialchars(implode(', ', $genres)); 
                    ?>
                </li>
            </ul>
        </div>

        <h2>Comments</h2>

        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="movie.php?id=<?php echo $movieId; ?>" method="POST">
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
                <textarea name="comment" rows="4" placeholder="Enter your comment" required></textarea>
                <button type="submit">Post Comment</button>
            <?php else: ?>
                <p>You must <a href="login.php">log in</a> to post a comment.</p>
            <?php endif; ?>
        </form>

        <div class="comments-section">
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong></p>
                    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                    <p><small><?php echo htmlspecialchars($comment['created_at']); ?></small></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
