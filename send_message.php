<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;
$user_id = $_SESSION['user_id'];
$conn = new mysqli("localhost", "splitxs1_admin", "Sunil96241", "splitxs1_livechat");

$msg = $conn->real_escape_string($_POST['message']);
$image = '';

if (!empty($_FILES['image']['name'])) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed) && $_FILES['image']['size'] < 2*1024*1024) { // 2MB limit
        $target = 'uploads/' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $conn->real_escape_string($target);
        }
    }
}

$stmt = $conn->prepare("INSERT INTO chat_messages (user_id, sender, message, image) VALUES (?, 'user', ?, ?)");
$stmt->bind_param("iss", $user_id, $msg, $image);
$stmt->execute();
?>
