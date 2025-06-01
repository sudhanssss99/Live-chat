<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ketto Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="chat.css">
</head>
<body>
<div class="chat-container">
    <div class="header-bar">
        <span class="back-arrow" onclick="window.history.back()">&#8592;</span>
        <span class="chat-title">Blue Bird</span>
    </div>
    <div class="chat-content" id="chat-box"></div>
    <form id="message-form" enctype="multipart/form-data" autocomplete="off" class="input-bar">
        <!-- 3-line menu as img icon -->
        <button type="button" class="menu-btn" aria-label="Menu">
            <img src="https://splitx.shop/live/Img-live/menu.png" alt="Menu" class="icon-img">
        </button>
        <input class="input-msg" type="text" name="message" placeholder="Type Your message">
        <input type="file" name="image" class="file-btn" title="Attach image" style="display:none;" id="fileInput" accept="image/*">
        <!-- Paperclip icon as image -->
        <button type="button" class="file-btn" id="fileInputBtn" aria-label="Attach file">
            <img src="https://splitx.shop/live/Img-live/paper-clip.png" alt="Attach" class="icon-img">
        </button>
        <!-- Optionally show selected file name -->
        <span id="fileName" style="color:#009688;font-size:0.95em;margin-right:8px;"></span>
        <!-- Send icon as image, not inside a colored button -->
        <button type="submit" class="icon-send-btn" aria-label="Send">
            <img src="https://splitx.shop/live/Img-live/paper-plane.png" alt="Send" class="icon-img">
        </button>
    </form>
</div>

<script>
function fetchMessages() {
    fetch('fetch_messages.php')
        .then(res => res.text())
        .then(data => {
            document.getElementById('chat-box').innerHTML = data;
            document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
        });
}

// Trigger file input when paperclip button is clicked
document.getElementById('fileInputBtn').onclick = function() {
    document.getElementById('fileInput').click();
};

// Optionally show selected file name
document.getElementById('fileInput').addEventListener('change', function() {
    var fileNameSpan = document.getElementById('fileName');
    if(this.files && this.files.length > 0) {
        fileNameSpan.textContent = this.files[0].name;
    } else {
        fileNameSpan.textContent = '';
    }
});

document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = new FormData(this);
    fetch('send_message.php', {
        method: 'POST',
        body: form
    }).then(res => res.text()).then(() => {
        fetchMessages();
        this.reset();
        document.getElementById('fileName').textContent = '';
    });
});

setInterval(fetchMessages, 3000);
fetchMessages();
</script>
</body>
</html>
