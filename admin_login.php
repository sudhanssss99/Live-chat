<?php
session_start();
if ($_POST['pass'] ?? '' === 'admin123') {
    $_SESSION['admin'] = true;
    header("Location: admin_panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="center-container">
        <div class="card">
            <h2>Admin Login</h2>
            <form method="post">
                <input class="form-input" type="password" name="pass" placeholder="Admin Password">
                <button class="button-main" type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
