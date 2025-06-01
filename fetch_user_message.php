<?php
if (!isset($_GET['uid'])) exit;
$uid = intval($_GET['uid']);
$conn = new mysqli("localhost", "splitxs1_admin", "Sunil96241", "splitxs1_livechat");

$res = $conn->query("SELECT * FROM chat_messages WHERE user_id = $uid ORDER BY timestamp ASC");
while ($row = $res->fetch_assoc()) {
    $who = strtoupper($row['sender']);
    echo "<b>$who:</b> ";
    if (!empty($row['message'])) echo htmlspecialchars($row['message']) . "<br>";
    if (!empty($row['image'])) echo "<img src='{$row['image']}' style='max-width:150px'><br>";
}
?>
