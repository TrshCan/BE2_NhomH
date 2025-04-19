<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <link rel="stylesheet" href="../assets/styles/livechat.css" />
    <style>
    .windown {
        position: relative;
    }

    .chat-input {
        position: absolute;
        bottom: 0;
        width: 80%;
        background-color: #fff;
        padding: 10px;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body>
    <div id="chat-container" class="chat-container">

        <?php if ($role == 'admin'): ?>
        <div class="sidebar">
            <div>My chat</div>
            <div class="chat-list" id="chat-list">
                <!-- User list will be populated here -->
            </div>
        </div>
        <?php endif; ?>
        <div id="chat-window" class="chat-window window">

            <div class="chat-header">
                <div class="chat-title" id="title chat-title">
                    Chọn người dùng để chat
                </div>
                <div id="chat-body"></div>
            </div>
            <div class="chat-input">
                <input id="messageInput" type="text" placeholder="Type a message...">
                <button onclick="sendMessage()">Send</button>

            </div>
        </div>

        <script>
        const userId = <?= json_encode($user_id) ?>;
        let selectedUserId = null;
        const title = document.querySelector('.chat-title');


        <?php
            if ($role != 'admin') {
            ?>
        selectedUserId = 4;
        title.textContent = 'Chat with Admin';
        <?php } ?>

        function loadUserList() {
            fetch('get_user_list.php')
                .then(response => response.json())
                .then(users => {

                    const chatList = document.getElementById('chat-list');
                    chatList.innerHTML = '';
                    users.data.forEach(user => {

                        const chatItem = document.createElement('div');
                        chatItem.classList.add('chat-item');

                        // Tạo phần flur user
                        const flurUser = document.createElement('div');
                        flurUser.classList.add('flur', 'user');
                        flurUser.setAttribute('data-id', user.user_id);
                        chatItem.appendChild(flurUser);

                        // Tạo phần active
                        const active = document.createElement('div');
                        active.classList.add('active');
                        const avatar = document.createElement('img');
                        avatar.src = 'image/avatar1.png';
                        avatar.classList.add('avatar');
                        avatar.alt = '';
                        active.appendChild(avatar);
                        chatItem.appendChild(active);

                        // Tạo phần chat-text
                        const chatText = document.createElement('div');
                        chatText.classList.add('chat-text');

                        const chatName = document.createElement('div');
                        chatName.classList.add('chat-name');
                        chatName.textContent = user.full_name;

                        const chatPreview = document.createElement('div');
                        chatPreview.classList.add('chat-preview');
                        chatPreview.textContent = user.message;

                        chatText.appendChild(chatName);
                        chatText.appendChild(chatPreview);

                        chatItem.appendChild(chatText);

                        flurUser.onclick = () => {
                            selectedUserId = user.user_id;
                            title.textContent = `Chat with ${user.full_name}`;
                            loadMessages();
                        };
                        chatList.appendChild(chatItem);
                    });

                });
        }

        function loadMessages() {
            if (!selectedUserId) return;
            fetch(`get_messages.php?receiver_id=${selectedUserId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    const chatBody = document.getElementById('chat-body');
                    chatBody.innerHTML = '';
                    data.messages.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = msg.sender_id == userId ? 'message agent' :
                            'message customer';
                        messageDiv.innerHTML = `
      <div class="message-bubble">${msg.message}</div>  
    `;
                        chatBody.appendChild(messageDiv);
                    });
                });
        }

        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (!message) return;

            fetch('send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    sender_id: userId,
                    receiver_id: selectedUserId,
                    message
                })
            }).then(() => {
                loadMessages();
                messageInput.value = '';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadUserList();
            setInterval(loadMessages, 500);
        });
        setInterval(loadUserList, 500);
        </script>
</body>

</html>