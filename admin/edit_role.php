<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'movie_appp');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    header('Location: manage_roles.php');
    exit();
}

// Fetch user
$sql = "SELECT id, username, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_role = $_POST['role'];
    $update_sql = "UPDATE users SET role = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('si', $new_role, $user_id);

    if ($update_stmt->execute()) {
        header('Location: manage_roles.php');
        exit();
    } else {
        echo "Error updating role: " . $conn->error;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role</title>
    <link rel="stylesheet" href="../styles/admin-edit_role-style.css">
</head>
<body>
    <header>
        <h1>Edit User Role</h1>
    </header>
    <main>
        <form method="POST">
            <label for="role">Role:</label>
            <select name="role" id="role">
                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
            <button type="submit">Update Role</button>
        </form>
    </main>
</body>
</html>
