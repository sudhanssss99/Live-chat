<?php
session_start();
if (!isset($_SESSION['admin'])) die("Access denied");
$conn = new mysqli("localhost", "splitxs1_admin", "Sunil96241", "splitxs1_livechat");

// Fetch users with latest message time and total messages count
$users = $conn->query("
    SELECT u.*, 
           (SELECT MAX(timestamp) FROM chat_messages WHERE user_id=u.id) as last_msg_time,
           (SELECT COUNT(*) FROM chat_messages WHERE user_id=u.id) as total_msgs
    FROM users u
    ORDER BY last_msg_time DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Live Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="admin_chat.css">
</head>
<body>
<div class="admin-app">
    <div class="sidebar">
        <div class="sidebar-header">Users</div>
        <div class="user-list" id="user-list">
        <?php while ($user = $users->fetch_assoc()): ?>
            <div class="user-list-item" data-uid="<?php echo $user['id']; ?>">
                <div class="avatar"><?php echo strtoupper(substr($user['name'],0,1)); ?></div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                    <div class="user-meta"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
                <?php if ($user['total_msgs'] > 0): ?>
                    <div class="msg-badge"><?php echo $user['total_msgs']; ?></div>
                <?php endif; ?>
                <div class="user-time">
                    <?php
                    if ($user['last_msg_time']) {
                        $t = strtotime($user['last_msg_time']);
                        echo date('H:i', $t);
                    }
                    ?>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
    </div>
    <div class="main-chat" id="main-chat">
        <div class="empty-chat-msg">Select a user to start chatting</div>
    </div>
</div>

<script>
let currentUid = null;
let chatInterval = null;

document.querySelectorAll('.user-list-item').forEach(item => {
    item.onclick = function() {
        let uid = this.getAttribute('data-uid');
        loadChat(uid, this);
    };
});

function loadChat(uid, el) {
    document.querySelectorAll('.user-list-item').forEach(i => i.classList.remove('active'));
    if(el) el.classList.add('active');
    currentUid = uid;
    document.getElementById('main-chat').innerHTML = '<div class="loading-msg">Loading...</div>';
    fetch('fetch_user_chat_ui.php?uid=' + uid)
        .then(res => res.text())
        .then(html => {
            document.getElementById('main-chat').innerHTML = html;
            scrollChatBottom();
            setupReplyForm();
        });
    if(chatInterval) clearInterval(chatInterval);
    chatInterval = setInterval(() => reloadMessages(uid), 3000);
}

function reloadMessages(uid) {
    fetch('fetch_user_messages.php?uid=' + uid)
        .then(res => res.text())
        .then(html => {
            let chatBox = document.getElementById('chat-box');
            if(chatBox) chatBox.innerHTML = html;
            scrollChatBottom();
        });
}

function scrollChatBottom() {
    let chatBox = document.getElementById('chat-box');
    if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
}

function setupReplyForm() {
    let form = document.getElementById('reply-form');
    if(!form) return;
    form.onsubmit = function(e) {
        e.preventDefault();
        let formData = new FormData(form);
        fetch('send_reply.php', {
            method: 'POST',
            body: formData
        }).then(() => {
            form.reset();
            reloadMessages(formData.get('user_id'));
        });
    };
    // File attachment trigger
    let clipBtn = document.getElementById('admin-clip');
    let fileInput = document.getElementById('admin-file');
    if(clipBtn && fileInput) {
        clipBtn.onclick = () => fileInput.click();
    }
}
</script>
</body>
</html>
