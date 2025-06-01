<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: user_chat.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "splitxs1_admin", "Sunil96241", "splitxs1_livechat");
    $name = $conn->real_escape_string($_POST['name']);
    $conn->query("INSERT INTO users (name) VALUES ('$name')");
    $_SESSION['user_id'] = $conn->insert_id;
    header("Location: user_chat.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Start Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="center-container">
        <div class="card">
            <h2>Welcome to Blue Bird!</h2>
            <form method="post">
                <input class="form-input" type="text" name="name" placeholder="Enter your name" required>
                <button class="button-main" type="submit">Continue</button>
            </form>
        </div>
    </div>
</body>
</html>
