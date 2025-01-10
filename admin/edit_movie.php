<?php
session_start();

// Check if movie ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Movie ID is required.");
}

$movieId = intval($_GET['id']);

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

// Fetch movie details
$sql = "SELECT * FROM admin_movies WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movieId);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    die("Movie not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user inputs
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date'];
    $rating = $_POST['rating'];
    $ticket_price = $_POST['ticket_price'];
    $country = $_POST['country'];
    $genre = $_POST['genre'];
    $poster_url = $_POST['poster_url'];

    // Validation (check if required fields are filled)
    if (empty($title) || empty($release_date) || empty($rating)) {
        $error = "Title, Release Date, and Rating are required.";
    } else {
        // Prepare the update SQL statement with partial updates
        $updateFields = [];
        $params = [];

        // Only add fields that are not empty to the update query
        $updateFields[] = "title = ?";
        $params[] = $title;

        if (!empty($description)) {
            $updateFields[] = "description = ?";
            $params[] = $description;
        }

        $updateFields[] = "release_date = ?";
        $params[] = $release_date;

        $updateFields[] = "rating = ?";
        $params[] = $rating;

        if (!empty($ticket_price)) {
            $updateFields[] = "ticket_price = ?";
            $params[] = $ticket_price;
        }

        if (!empty($country)) {
            $updateFields[] = "country = ?";
            $params[] = $country;
        }

        if (!empty($genre)) {
            $updateFields[] = "genre = ?";
            $params[] = $genre;
        }

        if (!empty($poster_url)) {
            $updateFields[] = "poster_url = ?";
            $params[] = $poster_url;
        }

        // Final query with dynamic fields
        $updateSql = "UPDATE admin_movies SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $params[] = $movieId;

        // Bind the parameters
        $stmtUpdate = $conn->prepare($updateSql);
        $stmtUpdate->bind_param(str_repeat("s", count($params)-1) . "i", ...$params);

        if ($stmtUpdate->execute()) {
            $success = "Movie updated successfully.";
            // Refresh movie details
            $stmt->execute();
            $movie = $stmt->get_result()->fetch_assoc();
        } else {
            $error = "Failed to update the movie.";
        }

        $stmtUpdate->close();
    }
}

// Close connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie - <?php echo htmlspecialchars($movie['title']); ?></title>
    <link rel="stylesheet" href="../styles/admin-edit_movie-style.css">
</head>
<body>
    <header>
        <h1>Edit Movie: <?php echo htmlspecialchars($movie['title']); ?></h1>
    </header>

    <main>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($movie['description']); ?></textarea>

            <label for="release_date">Release Date:</label>
            <input type="date" id="release_date" name="release_date" value="<?php echo htmlspecialchars($movie['release_date']); ?>" required>

            <label for="rating">Rating:</label>
            <input type="number" step="0.1" id="rating" name="rating" value="<?php echo htmlspecialchars($movie['rating']); ?>" required>

            <label for="ticket_price">Ticket Price:</label>
            <input type="number" step="0.01" id="ticket_price" name="ticket_price" value="<?php echo htmlspecialchars($movie['ticket_price']); ?>">

            <label for="country">Country:</label>
            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($movie['country']); ?>">

            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($movie['genre']); ?>">

            <label for="poster_url">Poster URL:</label>
            <input type="text" id="poster_url" name="poster_url" value="<?php echo htmlspecialchars($movie['poster_url']); ?>">

            <button type="submit">Update Movie</button>
        </form>
    </main>
</body>
</html>
