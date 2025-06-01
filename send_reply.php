<?php
session_start();
if (!isset($_SESSION['admin'])) exit;

$conn = new mysqli("localhost", "splitxs1_admin", "Sunil96241", "splitxs1_livechat");
$msg = $conn->real_escape_string($_POST['message']);
$user_id = intval($_POST['user_id']);
$image = '';

if (!empty($_FILES['image']['name'])) {
    $target = 'uploads/' . time() . '_' . basename($_FILES['image']['name']);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $image = $conn->real_escape_string($target);
    }
}

$stmt = $conn->prepare("INSERT INTO chat_messages (user_id, sender, message, image) VALUES (?, 'admin', ?, ?)");
$stmt->bind_param("iss", $user_id, $msg, $image);
$stmt->execute();
header("Location: admin_panel.php");
?>
