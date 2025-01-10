<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['movie_id'], $_POST['name'], $_POST['comment'])) {
        echo "Invalid request.";
        exit;
    }

    $movieId = intval($_POST['movie_id']);
    $name = trim($_POST['name']);
    $comment = trim($_POST['comment']);

    if (empty($name) || empty($comment)) {
        echo "Name and comment are required.";
        exit;
    }

   
    $stmt = $conn->prepare("INSERT INTO comments (movie_id, name, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $movieId, $name, $comment);

    if ($stmt->execute()) {
        header("Location: movie.php?id=$movieId");
        exit;
    } else {
        echo "Error posting comment.";
    }

    $stmt->close();
    $conn->close();
}
?>
