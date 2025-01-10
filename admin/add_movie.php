<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date'];
    $rating = $_POST['rating'];
    $ticket_price = $_POST['ticket_price'];
    $country = $_POST['country'];
    $genre = $_POST['genre'];
    $photo = $_FILES['photo'];

    // Validate and move uploaded file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo["name"]);
    if (move_uploaded_file($photo["tmp_name"], $target_file)) {
        $poster_url = $target_file;
    } else {
        $poster_url = null;
    }

    // Connect to database
    $conn = new mysqli('localhost', 'root', '', 'movie_appp');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO admin_movies (title, release_date, description, poster_url, rating, ticket_price, country, genre) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdss", $name, $release_date, $description, $poster_url, $rating, $ticket_price, $country, $genre);

    if ($stmt->execute()) {
        echo "<p>Movie added successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
    <link rel="stylesheet" href="../styles/admin-addmovie-style.css">
</head>
<body>
    <header>
        <h1>Add a New Movie</h1>
    </header>
    <main>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="release_date">Release Date:</label>
            <input type="date" id="release_date" name="release_date" required>

            <label for="rating">Rating:</label>
            <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" required>

            <label for="ticket_price">Ticket Price:</label>
            <input type="number" id="ticket_price" name="ticket_price" step="0.01" required>

            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>

            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" required>

            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>

            <button type="submit">Add Movie</button>
        </form>
    </main>
</body>
</html>
