<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;
$user_id = $_SESSION['user_id'];
$conn = new mysqli("localhost", "splitxs1_admin", "Sunil96241", "splitxs1_livechat");

$res = $conn->query("SELECT * FROM chat_messages WHERE user_id = $user_id ORDER BY timestamp ASC");

$messages = [];
while ($row = $res->fetch_assoc()) {
    $messages[] = $row;
}

$i = 0;
$len = count($messages);

while ($i < $len) {
    $row = $messages[$i];
    if ($row['sender'] === 'user') {
        echo "<div class='msg-row msg-user'>";
        echo "<span class='bubble'>";
        if (!empty($row['message'])) {
            echo nl2br(htmlspecialchars($row['message'])) . "<br>";
        }
        // Show image if exists and file exists
        if (!empty($row['image']) && file_exists($row['image'])) {
            // For correct web path, remove leading "./" or "/" if present
            $webPath = $row['image'];
            if (strpos($webPath, './') === 0) $webPath = substr($webPath, 2);
            if (strpos($webPath, '/') === 0) $webPath = substr($webPath, 1);
            echo "<img src='" . htmlspecialchars($webPath) . "' style='max-width:150px;max-height:150px;border-radius:10px;display:block;margin-top:5px;'>";
        }
        echo "</span>";
        echo "<span class='msg-time'>" . date('g:i A', strtotime($row['timestamp'])) . "</span>";
        echo "</div>";
        $i++;
    } else {
        // Admin messages: group consecutive admin messages as a bubble-group
        echo "<div class='msg-row msg-admin'>";
        echo "<span class='avatar'><img src='bird.png' alt=''></span>";
        echo "<span class='bubble-group'>";
        while ($i < $len && $messages[$i]['sender'] === 'admin') {
            $msg = $messages[$i];
            if (!empty($msg['message'])) {
                echo "<span class='bubble'>" . nl2br(htmlspecialchars($msg['message'])) . "</span>";
            }
            if (!empty($msg['image']) && file_exists($msg['image'])) {
                $webPath = $msg['image'];
                if (strpos($webPath, './') === 0) $webPath = substr($webPath, 2);
                if (strpos($webPath, '/') === 0) $webPath = substr($webPath, 1);
                echo "<img src='" . htmlspecialchars($webPath) . "' style='max-width:150px;max-height:150px;border-radius:10px;display:block;margin-top:5px;'><br>";
            }
            $last_admin_time = $msg['timestamp'];
            $i++;
            // Only group if next admin message is within 60 seconds of previous
            if ($i < $len && $messages[$i]['sender'] === 'admin') {
                $curr = strtotime($messages[$i]['timestamp']);
                $prev = strtotime($last_admin_time);
                if (($curr - $prev) > 60) break;
            }
        }
        echo "</span>";
        echo "<span class='msg-time'>" . date('g:i A', strtotime($last_admin_time)) . "</span>";
        echo "</div>";
    }
}
?>
