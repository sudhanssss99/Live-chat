<?php
session_start();
if (!isset($_SESSION['admin'])) die("Access denied");
$conn = new mysqli("localhost", "splitxs1_admin", "Sunil96241", "splitxs1_livechat");
$uid = intval($_GET['uid']);
$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
?>
<div class="chat-header">
    <div class="avatar"><?php echo strtoupper(substr($user['name'],0,1)); ?></div>
    <div class="user-title">
        <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
        <div class="user-meta"><?php echo htmlspecialchars($user['email']); ?></div>
    </div>
</div>
<div class="chat-content" id="chat-box">
    <?php include 'fetch_user_messages.php'; ?>
</div>
<form id="reply-form" class="chat-inputbar" autocomplete="off" enctype="multipart/form-data" method="post">
    <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
    <input class="input-msg" type="text" name="message" placeholder="Type your message">
    <input type="file" name="image" id="admin-file" style="display:none;">
    <button type="button" id="admin-clip" class="file-btn" aria-label="Attach file">
        <img src="https://splitx.shop/live/Img-live/paper-clip.png" class="icon-img" alt="Attach">
    </button>
    <button type="submit" class="icon-send-btn" aria-label="Send">
        <img src="https://splitx.shop/live/Img-live/paper-plane.png" class="icon-img" alt="Send">
    </button>
</form>
